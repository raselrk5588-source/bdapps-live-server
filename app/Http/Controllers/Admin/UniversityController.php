<?php

namespace App\Http\Controllers\Admin;

use App\University;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;

class UniversityController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            Session::put('top_menu', "university");
            Session::put('sub_menu', "university");
            return $next($request);
        });
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $universities = University::orderBy('name', 'asc')->get();
        return view('admin.university.index', compact('universities'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:universities,name'
        ]);

        $university = new University();
        $university->name = $request->name;
        $university->save();

        return redirect()->back()->with('success', 'University added successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(University $university)
    {
        $university->delete();
        return redirect()->back()->with('success', 'University removed successfully.');
    }
}
