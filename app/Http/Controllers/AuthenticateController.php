<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Authenticate;

class AuthenticateController extends Controller
{
    public function create()
    {
        // Show the form view
        return view('register');
    }

    public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'matric'     => 'required|numeric|unique:authenticate,matric',
        'Surname'    => 'nullable|string|max:50',
        'othername'  => 'nullable|string|max:80',
        'phone'      => 'nullable|string|max:50',
        'email'      => 'nullable|email|max:255',
        'password'   => 'required|string|min:6|confirmed',
    ]);

    if ($validator->fails()) {
        return back()->withErrors($validator)->withInput();
    }

    Authenticate::create([
        'matric'    => $request->matric,
        'Surname'   => $request->Surname,
        'Othernames'=> $request->othername,
        'phone'     => $request->phone,
        'email'     => $request->email,
        'password'  => $request->password, // Will be auto hashed by mutator
        'created_at'=> now(),
        'updated_at'=> now(),
    ]);

    // return redirect()->back()->with('success', 'Registration successful.');
    return redirect('/')->with('Registration', 'Registration was successfully done, please login with your credentials.');
}
}
