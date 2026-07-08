<?php

namespace App\Http\Controllers\Admin;

use App\InstallApp;
use App\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;

class InstallAppController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            Session::put('top_menu', "installapp");
            Session::put('sub_menu', "installapp");
            return $next($request);
        });
    }

    /**
     * Display a listing of the installed apps.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = InstallApp::with(['user', 'category']);

        if ($search) {
            $query->where('app_name', 'like', '%' . $search . '%')
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', '%' . $search . '%');
                  });
        }

        $apps = $query->orderBy('id', 'desc')->paginate(15);
        return view('admin.installapp.index', compact('apps', 'search'));
    }

    /**
     * Show the form for editing the specified app.
     */
    public function edit($id)
    {
        $app = InstallApp::findOrFail($id);
        $categories = Category::all();
        return view('admin.installapp.edit', compact('app', 'categories'));
    }

    /**
     * Update the specified app in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'app_name' => 'required|string|max:50|unique:install_apps,app_name,' . $id,
            'app_id' => 'required|string|max:50|unique:install_apps,app_id,' . $id,
            'password' => 'required|string|max:200',
            'category_id' => 'required|integer',
            'sms_time' => 'required|integer',
            'sms_time_format' => 'required|integer',
        ]);

        $app = InstallApp::findOrFail($id);
        $app->app_name = $request->app_name;
        $app->app_id = $request->app_id;
        $app->password = $request->password;
        $app->ussd_code = $request->ussd_code;
        $app->sms_keyword = $request->sms_keyword;
        $app->category_id = $request->category_id;
        $app->sms_time = $request->sms_time;
        $app->sms_time_format = $request->sms_time_format;
        $app->save();

        return redirect()->route('admin.installapp.index')->with('success', 'Installed App updated successfully.');
    }

    /**
     * Remove the specified app from storage.
     */
    public function destroy($id)
    {
        $app = InstallApp::findOrFail($id);
        $app->delete();
        return redirect()->route('admin.installapp.index')->with('success', 'Installed App deleted successfully.');
    }
}
