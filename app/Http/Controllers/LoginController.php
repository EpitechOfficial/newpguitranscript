<?php

namespace App\Http\Controllers;

use App\Models\Authenticate;
use App\Models\NewStudent;
use App\Models\TransDetailsNew;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('index');
    }

    /**
     * Store a newly created resource in storage.
     */
    /**
 * Store a newly created resource in storage.
 */
public function store(Request $request)
{
    // Validate inputs
    $request->validate([
        'matric' => 'required|string',
        'password' => 'required|string',
    ]);

    $credentials = $request->only('matric', 'password');
    // Attempt to authenticate
    if (Auth::attempt($credentials)) {
        $user = User::where('matric', $credentials['matric'])->first();
// dd($user);
        // Store user data in the session
        Session::put('user', $user ? $user : null);
        Session::put('matric', $credentials['matric']);


        return redirect()->route('dashboard');
    }



    // Authentication failed
    return back()->withErrors(['message' => 'Invalid Matric or Password'])->withInput();
}



    /**
     * Store a newly created resource in storage.
     */


    /**
     * Remove the specified resource from storage.
     */
    public function destroy()
    {
        Auth::logout();
        Session::forget('user');
        return redirect()->route('login');
    }
}
