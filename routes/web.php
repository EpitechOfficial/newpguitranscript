<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\TransInvoiceController;
use App\Http\Controllers\GetRecordController;
use App\Http\Controllers\AdminLoginController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\ICTHeadController;
use App\Http\Controllers\ResultOldController;
use App\Http\Controllers\AuthenticateController;
use App\Http\Controllers\StudentsByDepartmentController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
// Public Routes
Route::get('/', [LoginController::class, 'index'])->name('std.login');
Route::post('/', [LoginController::class, 'store'])->name('std.login.store');

Route::get('/apply', function () {
    return view('apply2');
})->name('apply');

// Admin Login Routes
Route::prefix('admin')->group(function () {
    Route::get('/records', [AdminLoginController::class, 'index'])->name('login');
    Route::post('/records', [AdminLoginController::class, 'store'])->name('login.store');
});

// Student Authenticated Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/create', [DashboardController::class, 'create'])->name('dashboard.create');
    Route::get('/track-application', [DashboardController::class, 'trackApplication'])->name('track');

    Route::post('/dashboard/create', [DashboardController::class, 'store'])->name('dashboard.create');
    Route::get('/dashboard/getDepartments', [DashboardController::class, 'getDepartments'])->name('dashboard.getDepartments');
    Route::get('/dashboard/apply', [DashboardController::class, 'apply'])->name('dashboard.apply');
    Route::get('/dashboard/getDegrees', [DashboardController::class, 'getDegrees'])->name('dashboard.getDegrees');
    Route::get('/dashboard/getFields', [DashboardController::class, 'getFields'])->name('dashboard.getFields');
    Route::post('/dashboard/store', [DashboardController::class, 'store'])->name('dashboard.store');
    Route::post('/dashboard/apply', [DashboardController::class, 'apply'])->name('dashboard.apply');
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

    // Cart and Transinvoice Resources
    Route::resource('cart', CartController::class);
    Route::resource('transinvoice', TransInvoiceController::class);
    Route::get('/transreceipt/{id}', [TransInvoiceController::class, 'showReceipt'])->name('transinvoice.showReceipt');

    // Dynamic Record Fetching
    Route::get('/get-departments/{facultyId}', [GetRecordController::class, 'getDepartments']);
    Route::get('/get-degrees/{departmentId}', [GetRecordController::class, 'getDegrees']);
    Route::get('/get-specializations/{degreeId}/{departmentId}', [GetRecordController::class, 'getSpecializations']);
});

// Route::post('/admin/update-cheque', function (Request $request) {
//     return response()->json(['success' => true]);
// });

//Route::middleware(['auth:admin'])->group(function () {
Route::get('admin/record-processed', [AdminLoginController::class, 'recordProcesseds'])->name('admin.recordProcesseds');
Route::get('admin/dashboard-to', [AdminLoginController::class, 'dashboard_to'])->name('admin.dashboardto');
Route::get('admin/dashboard-ki', [AdminLoginController::class, 'dashboardKi'])->name('admin.dashboard_ki');
Route::get('admin/dashboard-po', [AdminLoginController::class, 'dashboardPo'])->name('admin.dashboard_po');
Route::get('admin/dashboard-fo', [AdminLoginController::class, 'dashboardFo'])->name('admin.dashboard_fo');
Route::get('admin/transreceive-dashboard', [AdminLoginController::class, 'transreceiveDashboard'])->name('admin.transrecevedashboard');

//});


// Admin Authenticated Routes
Route::post('/admin/update-cheque', [DashboardController::class, 'updateCheque'])->name('update.cheque');
Route::middleware(['auth:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Main dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // TO role (2)
    Route::get('/dashboard-to', [AdminDashboardController::class, 'toDashboard'])
        ->name('dashboard.to')
        ->middleware('role:2');

    // KI role (3)
    Route::get('/dashboard-ki', [AdminDashboardController::class, 'kiDashboard'])
        ->name('dashboard.ki')
        ->middleware('role:3');

    // PO role (4)
    Route::get('/dashboard-po', [AdminDashboardController::class, 'poDashboard'])
        ->name('dashboard.po')
        ->middleware('role:4');

    // FO role (5)
    Route::get('/dashboard-fo', [AdminDashboardController::class, 'foDashboard'])
        ->name('dashboard.fo')
        ->middleware('role:5');

    // Transreceive role (6)
    Route::get('/transreceive-dashboard', [AdminDashboardController::class, 'transreceiveDashboard'])
        ->name('transrecevedashboard')
        ->middleware('role:6');

    // Record Processed role (7)
    Route::get('/record-processed', [AdminDashboardController::class, 'recordProcessed'])
        ->name('recordProcesseds')
        ->middleware('role:7');

    // Legacy routes for backward compatibility
    Route::get('/transrecieveDashboard', [DashboardController::class, 'transrecieveDashboard'])->name('transrecieveDashboard');
    Route::get('/approved-record', [DashboardController::class, 'recordApproved'])->name('recordApproved');
    Route::post('/logout', [AdminLoginController::class, 'destroy'])->name('logout');
    $mass = true; // Assuming mass transcript is available for all roles
    if ($mass) {
        # code...
        // Students by Department Routes
        Route::get('/students-by-department', [StudentsByDepartmentController::class, 'index'])->name('students_by_department');
        Route::get('/students-by-department/fetch-departments', [StudentsByDepartmentController::class, 'fetchDepartments'])
            ->name('students_by_department.fetch_departments');
        Route::get('/students-by-department/fetch-students', [StudentsByDepartmentController::class, 'fetchStudents'])
            ->name('students_by_department.fetch_students');
        Route::post('/students-by-department/view-transcript', [StudentsByDepartmentController::class, 'viewTranscript'])
            ->name('students_by_department.view_transcript');
    }

    // Students by Department Routes
    Route::get('/students-by-department', function () {
        return view('admin.students_by_department');
    })->name('students_by_department');
    Route::get('/students-by-department/fetch-departments', [StudentsByDepartmentController::class, 'fetchDepartments'])
        ->name('students_by_department.fetch_departments');
    Route::get('/students-by-department/fetch-students', [StudentsByDepartmentController::class, 'fetchStudents'])
        ->name('students_by_department.fetch_students');
    Route::post('/process-record', [DashboardController::class, 'processRecord'])->name('processRecord');
    Route::post('/process-transcript', [DashboardController::class, 'processTranscript'])->name('processTranscript');
    Route::post('/view-transcript', [DashboardController::class, 'processView'])->name('processView');
    Route::post('/approve', [DashboardController::class, 'approve'])->name('transcriptApprove');
    Route::post('/reject', [DashboardController::class, 'reject'])->name('transcriptReject');
    Route::post('/reject-to-key', [DashboardController::class, 'transcriptRejectToKey'])->name('transcriptRejectToKey');
    Route::post('/reject-to-help', [DashboardController::class, 'transcriptRejectToHelp'])->name('transcriptRejectToHelp');
    Route::post('/transcript/submit', [DashboardController::class, 'submitForApproval'])->name('transcriptSubmit');
    Route::post('/transcript/submit-higher', [DashboardController::class, 'submitForApprovalHigher'])->name('transcriptSubmitHigher');

    // 2025 Ph.D Convocation
    Route::get('/phd-convocation', [\App\Http\Controllers\PhdConvocationController::class, 'form'])->name('phd_convocation.form');
    Route::post('/phd-convocation/find', [\App\Http\Controllers\PhdConvocationController::class, 'find'])->name('phd_convocation.find');
    Route::post('/phd-convocation/submit', [\App\Http\Controllers\PhdConvocationController::class, 'submit'])->name('phd_convocation.submit');

    // Transcript editing routes
    Route::get('/edit-transcript-realtime', [ResultOldController::class, 'editTranscriptRealtimePage'])->name('edit_transcript_realtime');
    Route::get('/edit-transcript-realtime/fetch', [ResultOldController::class, 'fetchTranscriptRealtime'])->name('edit_transcript_realtime.fetch');
    Route::get('/edit-transcript-realtime/fetch-sessions', [ResultOldController::class, 'fetchSessions'])->name('edit_transcript_realtime.fetch_sessions');
    Route::post('/edit-transcript-realtime/save', [ResultOldController::class, 'saveTranscriptRealtime'])->name('edit_transcript_realtime.save');
    Route::post('/edit-transcript-realtime/delete', [ResultOldController::class, 'deleteTranscriptRealtime'])->name('edit_transcript_realtime.delete');
    Route::get('/transcript/details/{id}', [AdminLoginController::class, 'transcriptDetails'])->name('transcript.details');
});

// Route::get('admin/dashboard', [AdminLoginController::class, 'dashboard'])->name('dashboard');
// Route::get('admin/dashboard_to', [AdminLoginController::class, 'dashboardTo'])->name('dashboard_to');
// Route::get('admin/dashboard_ki', [AdminLoginController::class, 'dashboardKi'])->name('dashboard_ki');
// Route::get('admin/dashboard_po', [AdminLoginController::class, 'dashboardPo'])->name('dashboard_po');
// Route::get('admin/dashboard_fo', [AdminLoginController::class, 'dashboardFo'])->name('dashboard_fo');
// Route::get('admin/dashboard_hd', [AdminLoginController::class, 'dashboardHd'])->name('dashboard_hd');

Route::get('/admin-users', [AdminUserController::class, 'index'])->name('adminusers.index');
Route::post('/admin-users', [AdminUserController::class, 'store'])->name('adminusers.store');
Route::post('/admin-users/toggle/{id}', [AdminUserController::class, 'toggleStatus'])->name('adminusers.toggle');
Route::get('/admin-users/{id}', [AdminUserController::class, 'show'])->name('adminusers.show');

// ICT Head Routes
Route::middleware(['auth:admin'])->prefix('admin/icthead')->name('admin.icthead.')->group(function () {
    Route::get('/', [ICTHeadController::class, 'index'])->name('dashboard');
    Route::get('/stats', [ICTHeadController::class, 'getStats'])->name('stats');
    Route::resource('users', ICTHeadController::class)->except(['create', 'edit']);
    Route::post('/users/{id}/toggle', [ICTHeadController::class, 'toggleStatus'])->name('users.toggle');
});


//Route::view('/register', 'register')->name('register');



Route::get('/register', [AuthenticateController::class, 'create'])->name('authenticate.create');
Route::post('/register', [AuthenticateController::class, 'store'])->name('authenticate.store');



// input result
Route::get('/result-old/upload', [ResultOldController::class, 'uploadForm'])->name('result_old.upload_form');
Route::post('/result-old/upload', [ResultOldController::class, 'uploadExcel'])->name('result_old.upload_excel');



// Show the admin login page
// Route::get('admin/login', [AdminLoginController::class, 'index'])->name('login');
//Route::middleware(['auth:admin'])->group(function () {
// Handle login submission
// Route::post('admin/login', [AdminLoginController::class, 'store'])->name('login.submit');

// Handle logout
// Route::post('admin/logout', [AdminLoginController::class, 'destroy'])->name('logout');

// Optional dashboard routes (if you want them accessible via URL)
Route::get('admin/dashboard', [AdminLoginController::class, 'dashboard'])->name('admin.dashboard');
// Route::get('admin/dashboard-to', [AdminLoginController::class, 'dashboardTo'])->name('dashboard.to');
// Route::get('admin/dashboard-ki', [AdminLoginController::class, 'dashboardKi'])->name('dashboard.ki');
// Route::get('admin/dashboard-po', [AdminLoginController::class, 'dashboardPo'])->name('dashboard.po');
// Route::get('admin/dashboard-fo', [AdminLoginController::class, 'dashboardFo'])->name('dashboard.fo');
// Route::get('admin/dashboard-hd', [AdminLoginController::class, 'dashboardHd'])->name('dashboard.hd');
//});

// Real-time transcript editing page
