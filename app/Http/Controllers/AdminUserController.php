<?php

namespace App\Http\Controllers;

use App\Models\AdminUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    public function index()
    {
        $users = AdminUser::all();
        return view('admin.register', compact('users'));
    }

    public function store(Request $request)
{
    $request->validate([
        'fullname' => 'required',
        'username' => 'required|unique:admin_users,username',
        'password' => 'required',
        'role' => 'required',
    ]);

    AdminUser::create([
        'fullname' => $request->fullname,
        'username' => $request->username,
        'password' => $request->password,
        'role' => $request->role,
        'status' => 'active',
    ]);

    return redirect()->back()->with('success', 'User created successfully.');
}



    public function toggleStatus($id)
{
    $user = AdminUser::findOrFail($id);
    $user->status = $user->status == 'active' ? 'inactive' : 'active';
    $user->save();

    return response()->json(['status' => 'success', 'new_status' => $user->status]);
}


    public function show($id)
    {
        $user = AdminUser::findOrFail($id);
        return response()->json($user);
    }
}
