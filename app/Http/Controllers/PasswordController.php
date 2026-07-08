<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PasswordController extends Controller
{
   public function index()
    {
        if ((int) Auth::user()->role_id === 1)
        {
            return view("login.adminpasswordChange");
        }
        else
        {
            return view("login.passwordChange");
        }
    }

    public function changePassword(Request $request)
    {
        $validatedData = $request->validate([
                'email' => ['required', 'string', 'email', 'max:255']
            ]);
        if($request->password)
        {
            $password=Auth::user()->password;
            $oldPass=$request->oldPass;
            if(Hash::check($oldPass,$password))
            {
                $user=User::find(Auth::id());
                $user->password=Hash::make($request->password);
                if ((int) Auth::user()->role_id === 1)
                $user->email=$request->email;
                $user->save();
                Auth::logout();
                return redirect()->route("login")->with("message","Successfully changed profile. Now logged in.");
            }
            else
            {

                setMessage("message","danger","Old Password Not Matched");
                return redirect()->back();
            }
        }
        else
        {
                $user=User::find(Auth::id());
                $user->email=$request->email;
                $user->save();
                Auth::logout();
                return redirect()->route("login")->with("message","Successfully changed profile. Now logged in.");
        }

    }
}
