<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\TransDetailsNew;
use App\Models\TransDetailsFiles;
//App\Models\TransDetailsFile
use App\Models\AdminUser;

class AdminLoginController extends Controller
{
    public function index()
    {
        return view('admin.index');
    }

    public function store(Request $request)
{
    $request->validate([
        'username' => 'required|string',
        'password' => 'required|string',
    ]);

    $credential = $request->only('username', 'password');

    if (Auth::guard('admin')->attempt($credential)) {
    $user = AdminUser::where('username', $credential['username'])->first();

    $records = TransDetailsNew::where('status', '>=', 0)
        ->whereRaw('email REGEXP "^[0-9]+$"')
        ->whereHas('transInvoice', function ($query) {
            $query->whereColumn('amount_charge', 'amount_paid');
        })
        ->with([
            'transInvoice' => function ($query) {
                $query->select('invoiceno', 'purpose', 'dy', 'mth', 'cheque');
            },
            'transDetailsFiles:id,trans_details_id,file_path' // Include file_path here
        ])
        ->get();

    Session::put('admin_user', $user);
    Session::put('admin_username', $user->username);
    Session::put('records', $records);
    Log::error($records);



        switch ($user->role) {
            case 7:
                return redirect()->route('admin.recordProcesseds');
            case 2:
                return redirect()->route('admin.dashboard.to');
            case 3:
                return redirect()->route('admin.dashboard.ki');
            case 4:
                return redirect()->route('admin.dashboard_po');
            case 5:
                return redirect()->route('admin.dashboard.fo');
            case 6:
                return redirect()->route('admin.transrecevedashboard');
            default:
                Log::error('Unauthorized role for username: ' . $user->username . ' Role: ' . $user->role);
                return redirect('admin/login')->withErrors(['login_error' => 'Unauthorized role.']);
        }
    }

    return back()->withErrors(['login_error' => 'Invalid Username or Password'])->withInput();
}

    public function dashboard(Request $request)
{
    $request->validate([
        'username' => 'required|string',
        'password' => 'required|string',
    ]);

    $credentials = $request->only('username', 'password');

    if (Auth::guard('admin')->attempt($credentials)) {
        $user = AdminUser::where('username', $credentials['username'])->first();

        $records = TransDetailsNew::where('status', '>=', 0)
            ->whereRaw('email REGEXP "^[0-9]+$"')
            ->whereHas('transInvoice', function ($query) {
                $query->whereColumn('amount_charge', 'amount_paid');
            })
            ->with([
                'transInvoice' => function ($query) {
                    $query->select('invoiceno', 'purpose', 'dy', 'mth', 'cheque');
                }
            ])
            ->get();

        Session::put('admin_user', $user);
        Session::put('admin_username', $user->username);

        return view('admin.dashboard', ['records' => $records]);
    }

    // Optional: return back with error if login fails
    return back()->withErrors([
        'username' => 'Invalid credentials provided.',
    ]);
}

public function recordProcesseds()
{
    $records = session('records');
    return view('admin.recordProcessed', compact('records'));
}
public function dashboardto()
{
    $records = session('records');
    return view('admin.dashboardto', compact('records'));
}

public function dashboardKi()
{
    $records = session('records');
    return view('admin.dashboard_ki', compact('records'));
}

public function dashboardPo()
{
    $records = session('records');
    return view('admin.dashboard_po', compact('records'));
}

public function dashboardFo()
{
    $records = session('records');
    return view('admin.dashboard_fo', compact('records'));
}

public function dashboardHd()
{
    $records = session('records');
    return view('admin.dashboard_hd', compact('records'));
}

public function transreceiveDashboard()
{
    $records = session('records');
    return view('admin.transrecevedashboard', compact('records'));
}

    public function destroy()
    {
        Session::forget('admin_user');
        Session::forget('admin_username');
        return redirect()->route('login');
    }

    public function logout(Request $request)
{
    Auth::guard('admin')->logout(); // or just Auth::logout() if you're using the default guard
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('login'); // this is your Route::get('/records'...) with name 'login'
}

public function transcriptDetails($id)
    {
        $record = TransDetailsNew::with(['file', 'courier', 'transInvoice'])->findOrFail($id);
        return view('admin.transcriptDetails', compact('record'));
    }
}
