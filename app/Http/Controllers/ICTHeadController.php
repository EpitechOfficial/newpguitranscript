<?php

namespace App\Http\Controllers;

use App\Models\AdminUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ICTHeadController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Display the ICT Head dashboard
     */
    public function index()
    {
        $users = AdminUser::where('role', '!=', 1) // Exclude ICT Head
            ->orderBy('created_at', 'desc')
            ->get();
        $roleNames = $this->getRoleNames();

        // Check if this is an AJAX request
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'users' => $users,
                'roleNames' => $roleNames
            ]);
        }

        return view('admin.icthead.dashboard', compact('users', 'roleNames'));
    }

    /**
     * Store a newly created admin user
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fullname' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:admin_users,username',
            'password' => 'required|string|min:6',
            'role' => 'required|string|in:1,2,3,4,5,6,7',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = AdminUser::create([
                'fullname' => $request->fullname,
                'username' => $request->username,
                'password' => $request->password,
                'role' => $request->role,
                'status' => 'active',
            ]);

            Log::info('ICT Head created new admin user: ' . $user->username . ' (Role: ' . $user->role . ')');

            return response()->json([
                'success' => true,
                'message' => 'Admin user created successfully',
                'user' => $user
            ]);
        } catch (\Exception $e) {
            Log::error('Error creating admin user: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error creating admin user'
            ], 500);
        }
    }

    /**
     * Display the specified admin user
     */
    public function show($id)
    {
        try {
            $user = AdminUser::findOrFail($id);
            $roleNames = $this->getRoleNames();

            return response()->json([
                'success' => true,
                'user' => $user,
                'roleName' => $roleNames[$user->role] ?? 'Unknown'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }
    }

    /**
     * Update the specified admin user
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'fullname' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:admin_users,username,' . $id,
            'password' => 'nullable|string|min:6',
            'role' => 'required|string|in:1,2,3,4,5,6,7',
            'status' => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = AdminUser::findOrFail($id);

            $updateData = [
                'fullname' => $request->fullname,
                'username' => $request->username,
                'role' => $request->role,
                'status' => $request->status,
            ];

            // Only update password if provided
            if ($request->filled('password')) {
                $updateData['password'] = bcrypt($request->password);
            }

            $user->update($updateData);

            Log::info('ICT Head updated admin user: ' . $user->username . ' (Role: ' . $user->role . ')');

            return response()->json([
                'success' => true,
                'message' => 'Admin user updated successfully',
                'user' => $user
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating admin user: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating admin user'
            ], 500);
        }
    }

    /**
     * Remove the specified admin user
     */
    public function destroy($id)
    {
        try {
            $user = AdminUser::findOrFail($id);

            // Don't allow deletion of the current user
            if (auth()->guard('admin')->id() == $id) {
                return response()->json([
                    'success' => false,
                    'message' => 'You cannot delete your own account'
                ], 400);
            }

            $username = $user->username;
            $user->delete();

            Log::info('ICT Head deleted admin user: ' . $username);

            return response()->json([
                'success' => true,
                'message' => 'Admin user deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting admin user: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error deleting admin user'
            ], 500);
        }
    }

    /**
     * Toggle user status
     */
    public function toggleStatus($id)
    {
        try {
            $user = AdminUser::findOrFail($id);
            $user->status = $user->status == 'active' ? 'inactive' : 'active';
            $user->save();

            Log::info('ICT Head toggled status for user: ' . $user->username . ' to ' . $user->status);

            return response()->json([
                'success' => true,
                'status' => $user->status,
                'message' => 'User status updated successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error toggling user status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating user status'
            ], 500);
        }
    }

    /**
     * Get role names mapping
     */
    private function getRoleNames()
    {
        return [
            '1' => 'ICT Head',
            '2' => 'Transcript Officer',
            '3' => 'Key in Officer',
            '4' => 'Processing Officer',
            '5' => 'Filing Officer',
            '6' => 'Help Desk',
            '7' => 'Record Officer',
        ];
    }

    /**
     * Get user statistics for dashboard
     */
    public function getStats()
    {
        try {
            $stats = [
                'total_users' => AdminUser::where('role', '!=', 1)->count(),
                'active_users' => AdminUser::where('role', '!=', 1)->where('status', 'active')->count(),
                'inactive_users' => AdminUser::where('role', '!=', 1)->where('status', 'inactive')->count(),
                'users_by_role' => AdminUser::selectRaw('role, count(*) as count')
                    ->where('role', '!=', 1)
                    ->groupBy('role')
                    ->get()
                    ->keyBy('role')
            ];

            return response()->json([
                'success' => true,
                'stats' => $stats
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting user stats: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error getting user statistics'
            ], 500);
        }
    }
}
