<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\TransInvoiceController;
use App\Http\Controllers\GetRecordController;
use App\Http\Controllers\AdminLoginController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\ResultOldController;
use App\Http\Controllers\AuthenticateController;
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
    Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('dashboard');
    Route::get('/transrecieveDashboard', [DashboardController::class, 'transrecieveDashboard'])->name('transrecieveDashboard');
    // Route::get('/processed-record', [DashboardController::class, 'recordProcessed'])->name('recordProcessed');
    Route::get('/approved-record', [DashboardController::class, 'recordApproved'])->name('recordApproved');
    Route::post('/logout', [AdminLoginController::class, 'destroy'])->name('logout');
    Route::post('/process-record', [DashboardController::class, 'processRecord'])->name('processRecord');
    Route::post('/process-transcript', [DashboardController::class, 'processTranscript'])->name('processTranscript');
    Route::post('/view-transcript', [DashboardController::class, 'processView'])->name('processView');
    Route::post('/approve', [DashboardController::class, 'approve'])->name('transcriptApprove');
    Route::post('/reject', [DashboardController::class, 'reject'])->name('transcriptReject');
    Route::post('/reject-to-key', [DashboardController::class, 'reject'])->name('transcriptRejectToKey');
    Route::post('/transcript/submit', [DashboardController::class, 'submitForApproval'])->name('transcriptSubmit');


    Route::get('/dashboard', [AdminLoginController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard-to', [AdminLoginController::class, 'dashboardTo'])->name('dashboard.to');
    Route::get('/dashboard-ki', [AdminLoginController::class, 'dashboardKi'])->name('dashboard.ki');
    Route::get('/dashboard-po', [AdminLoginController::class, 'dashboardPo'])->name('dashboard.po');
    Route::get('/dashboard-fo', [AdminLoginController::class, 'dashboardFo'])->name('dashboard.fo');
    Route::get('/dashboard-hd', [AdminLoginController::class, 'dashboardHd'])->name('dashboard.hd');

 Route::get('/edit-transcript-realtime', [ResultOldController::class, 'editTranscriptRealtimePage'])->name('edit_transcript_realtime');
    Route::get('/edit-transcript-realtime/fetch', [ResultOldController::class, 'fetchTranscriptRealtime'])->name('edit_transcript_realtime.fetch');
    // Route::post('/edit-transcript-realtime/update', [ResultOldController::class, 'updateTranscriptRealtime'])->name('edit_transcript_realtime.update');
    Route::post('/edit-transcript-realtime/save', [ResultOldController::class, 'saveTranscriptRealtime'])->name('edit_transcript_realtime.save');
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
