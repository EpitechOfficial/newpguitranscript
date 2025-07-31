<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\DegreeNew;
use App\Models\DeptNew;
use App\Models\FacNew;
use App\Models\FieldNew;
use App\Models\NewRecord;
use App\Models\Biodata;
use App\Models\StudentRecord;
use App\Models\RequestType;
use App\Models\ResultOld;
use App\Models\TransDetailsNew;
use App\Models\TransDetailsFiles;
use App\Models\User;
use App\Models\Result2018;
use App\Models\Result2023;
use App\Models\Courier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;



class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Unauthorized access');
        }
        $matric = session('matric');

        $users = TransDetailsNew::where('matric', $matric)
            ->where('status', '>=' , 0)
            ->where('degree', '!=' ,'null')
            ->where('department', '!=' ,'null')
            ->where('faculty', '!=' ,'null')
            ->where('feildofinterest', '!=' ,'null')
            ->select('faculty', 'department', 'degree', 'feildofinterest')
            ->distinct()
            ->get();




        return view('dashboard', ['users' => $users]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $user = Auth::user();
        $matric = $user->matric;

        $user = User::where('matric', $matric)->first();

        if (!$user) {
            return response()->json(['error' => 'User not found.'], 404);
        }

        $surname = $user->Surname;
        $othernames = $user->Othernames;
        $maidenname = ' ';
        $title = $user->title;
        $sex = $user->sex;

        // Get the query parameters passed from the link, if they exist
        $faculty = $request->get('faculty') ?? 'Select Faculty';
        $department = $request->get('department') ?? 'Select Department';
        $degree = $request->get('degree') ?? 'Select Degree';
        $field = $request->get('field') ?? 'Select Specialization';

        $requestTypes = RequestType::all();
        $faculties = FacNew::orderBy('faculty', 'asc')->get();

        // Fetch session of entry and graduation from Result2023
        $result2023 = Result2023::where('matric', $matric)->get();
        $secAdmin = [];
        $secGrad = [];

        foreach ($result2023 as $r) {
            if ($r->yr_of_entry) {
                $normalizedEntry = preg_replace('/\/(\d{2})$/', '/20$1', $r->yr_of_entry);
                $secAdmin[] = $normalizedEntry;
            }
            if ($r->session_of_grad) {
                $normalizedGrad = preg_replace('/\/(\d{2})$/', '/20$1', $r->session_of_grad);
                $secGrad[] = $normalizedGrad;
            }
        }

        // Fetch session of entry from Result2018
        $result2018 = Result2018::where('stud_id', $matric)->get();
        foreach ($result2018 as $r) {
            if ($r->sec) {
                $secAdmin[] = $r->sec;
            }
        }

        // Fetch session of entry from ResultOld
        $resultOld = ResultOld::where('matno', $matric)->get();
        foreach ($resultOld as $r) {
            if ($r->sec) {
                $secAdmin[] = $r->sec;
            }
        }

        // Remove duplicates and sort
        $secAdmin = array_unique($secAdmin);
        $secGrad = array_unique($secGrad);
        sort($secAdmin);
        sort($secGrad);

        return view('apply', compact('requestTypes', 'faculties', 'surname', 'othernames', 'maidenname', 'title', 'sex', 'faculty', 'department', 'degree', 'field', 'secAdmin', 'secGrad'));
    }

    public function apply()
    {
        $requestTypes = RequestType::all();
        return view('apply2', compact('requestTypes'));
    }

    // public function store(Request $request)
    // {
    //     if (!auth()->check()) {
    //         return redirect()->route('login')->with('error', 'Unauthorized access');
    //     }

    //     $matric = auth()->user()->matric;

    //     $user = User::where('matric', $matric)->first();

    //     $validatedData = $request->validate([
    //         'transcript_type' => 'required',
    //         'number_of_copies' => 'required|numeric|min:1',
    //         'faculty' => 'sometimes|required',
    //         'department' => 'sometimes|required',
    //         'degree' => 'sometimes|required',
    //         'field' => 'sometimes|required',
    //         'title' => 'sometimes|required',
    //         'sex' => 'sometimes|required',
    //         'surname' => 'sometimes|required',
    //         'othernames' => 'sometimes|required',
    //         'maiden' => 'sometimes',
    //         'session_of_entry' => 'sometimes|required',
    //         'session_of_graduation' => 'sometimes|required',
    //         'file' => 'required|mimes:pdf,jpg,jpeg,png|max:2048',
    //     ]);

    //     $fac = FacNew::where('id', $validatedData['faculty'])
    //         ->orWhere('faculty', $validatedData['faculty'])
    //         ->first();

    //     if (!$fac) {
    //         return redirect()->back()->with('error', 'Faculty not found');
    //     }

    //     $dept = DeptNew::where('id', $validatedData['department'])
    //         ->orWhere('department', $validatedData['department'])
    //         ->first();

    //     if (!$dept) {
    //         return redirect()->back()->with('error', 'Department not found');
    //     }

    //     $degrees = DegreeNew::where('id', $validatedData['degree'])
    //         ->orWhere('degree', $validatedData['degree'])
    //         ->first();

    //     if (!$degrees) {
    //         return redirect()->back()->with('error', 'Degree not found');
    //     }

    //     $specializations = FieldNew::where('id', $validatedData['field'])
    //         ->orWhere('field_title', $validatedData['field'])
    //         ->first();

    //     if (!$specializations) {
    //         return redirect()->back()->with('error', 'Specialization not found');
    //     }

    //     $facName = $fac->faculty;
    //     $deptName = $dept->department;
    //     $degreeName = $degrees->degree;
    //     $specializationName = $specializations->field_title;




    //     $transcriptAmount = RequestType::where('requesttype', $request->transcript_type)->first();
    //     if (!$transcriptAmount) {
    //         return redirect()->back()->with('error', 'Invalid transcript type');
    //     }

    //     $cartItem = [
    //         'matric' => $matric,
    //         'request' => $validatedData["transcript_type"],
    //         'num_copies' => $validatedData["number_of_copies"],
    //         'fee' => $transcriptAmount['amount'],
    //         'degree' => $degreeName,
    //     ];

    //     Cart::create($cartItem);

    //     // Prepare the data for storage
    //     $transDetailsItems = [
    //         'matric' => $matric, // Ensure this is provided in the request
    //         'Surname' => $validatedData['surname'],
    //         'Othernames' => $validatedData['othernames'],
    //         'maiden' => $validatedData['maiden'] ?? '',
    //         'sex' => $validatedData['sex'],
    //         'tittle' => $validatedData['title'], // Make sure 'tittle' matches your database column name
    //         'degree' => $degreeName,
    //         'sessionadmin' => $validatedData['session_of_entry'], // Rename as necessary
    //         'sessiongrad' => $validatedData['session_of_graduation'], // Duplicate key corrected below
    //         'faculty' => $facName,
    //         'department' => $deptName,
    //         'feildofinterest' => $specializationName,
    //         'award' => null,
    //         'programme' => null,
    //         'date_requested' => now(),
    //     ];


    //     // Store the data
    //     $transDetails = TransDetailsNew::create($transDetailsItems);

    //     // Store uploaded file path separately
    //     if ($request->hasFile('file')) {
    //         $file = $request->file('file');
    //         $filePath = $file->store('uploads/notification', 'public');

    //         TransDetailsFiles::create([
    //             'trans_details_id' => $transDetails->id,
    //             'file_path' => $filePath,
    //         ]);
    //     } else {
    //         return redirect()->back()->with('error', 'File Upload Failed');
    //     }


    //     session()->push('cart', $cartItem);

    //     return redirect()->to('cart');
    // }

public function store(Request $request)
{
    if (!auth()->check()) {
        return redirect()->route('login')->with('error', 'Unauthorized access');
    }

    $matric = auth()->user()->matric;
    $user = User::where('matric', $matric)->first();

    // Check if it's an e-copy request
    $isEcopy = Str::contains($request->transcript_type, ['E-Copy', 'Soft Copy']);

    // Set up validation rules BEFORE validation
    $validationRules = [
        'transcript_type' => 'required',
        'number_of_copies' => 'required|numeric|min:1',
        'faculty' => 'sometimes|required',
        'department' => 'sometimes|required',
        'degree' => 'sometimes|required',
        'field' => 'sometimes|required',
        'title' => 'sometimes|required',
        'sex' => 'sometimes|required',
        'surname' => 'sometimes|required',
        'othernames' => 'sometimes|required',
        'maiden' => 'sometimes',
        'session_of_entry' => 'sometimes|required',
        'session_of_graduation' => 'sometimes|required',
        'file' => 'required|mimes:pdf,jpg,jpeg,png|max:2048',
    ];

    // Add conditional validation rules based on transcript type
    if ($isEcopy) {
        $validationRules['ecopy_email'] = 'required|email';
        $validationRules['ecopy_address'] = 'nullable|string';
    } else {
        $validationRules['dispatch_mode'] = 'required';
        $validationRules['dispatch_country'] = 'required';
        $validationRules['destination_address'] = 'required';
        $validationRules['destination2'] = 'nullable';
    }

    // NOW validate with all the rules
    $validatedData = $request->validate($validationRules);

    $fac = FacNew::where('id', $validatedData['faculty'])
        ->orWhere('faculty', $validatedData['faculty'])
        ->first();

    if (!$fac) {
        return redirect()->back()->with('error', 'Faculty not found');
    }

    $dept = DeptNew::where('id', $validatedData['department'])
        ->orWhere('department', $validatedData['department'])
        ->first();

    if (!$dept) {
        return redirect()->back()->with('error', 'Department not found');
    }

    $degrees = DegreeNew::where('id', $validatedData['degree'])
        ->orWhere('degree', $validatedData['degree'])
        ->first();

    if (!$degrees) {
        return redirect()->back()->with('error', 'Degree not found');
    }

    $specializations = FieldNew::where('id', $validatedData['field'])
        ->orWhere('field_title', $validatedData['field'])
        ->first();

    if (!$specializations) {
        return redirect()->back()->with('error', 'Specialization not found');
    }

    $facName = $fac->faculty;
    $deptName = $dept->department;
    $degreeName = $degrees->degree;
    $specializationName = $specializations->field_title;

    $transcriptAmount = RequestType::where('requesttype', $request->transcript_type)->first();
    if (!$transcriptAmount) {
        return redirect()->back()->with('error', 'Invalid transcript type');
    }

    $cartItem = [
        'matric' => $matric,
        'request' => $validatedData["transcript_type"],
        'num_copies' => $validatedData["number_of_copies"],
        'fee' => $transcriptAmount['amount'],
        'degree' => $degreeName,
    ];

    Cart::create($cartItem);

    // Check if matric matches results.stud_id or result_old.appno
    $matchExists = DB::table('results')
        ->where('stud_id', $matric)
        ->exists();

    if (!$matchExists) {
        $matchExists = DB::table('result_old')
            ->where('matno', $matric)
            ->exists();
    }
    if (!$matchExists) {
        $matchExists = DB::table('testscore')
            ->where('matric', $matric)
            ->exists();
    }

    // Update status in TransDetailsNew based on the match
    $statusToInsert = $matchExists ? 2 : 0;

    // Prepare the data for storage
    $transDetailsItems = [
        'matric' => $matric,
        'Surname' => $validatedData['surname'],
        'Othernames' => $validatedData['othernames'],
        'maiden' => $validatedData['maiden'] ?? '',
        'sex' => $validatedData['sex'],
        'tittle' => $validatedData['title'],
        'degree' => $degreeName,
        'sessionadmin' => $validatedData['session_of_entry'],
        'sessiongrad' => $validatedData['session_of_graduation'],
        'faculty' => $facName,
        'department' => $deptName,
        'feildofinterest' => $specializationName,
        'award' => null,
        'programme' => null,
        'date_requested' => now(),
        'status' => $statusToInsert,
    ];

    if ($isEcopy) {
        $transDetailsItems['ecopy_email'] = $validatedData['ecopy_email'] ?? null;
        $transDetailsItems['ecopy_address'] = $validatedData['ecopy_address'] ?? null;
    } else {
        // Store courier details for non-e-copy requests
        Courier::create([
            'appno' => $matric,
            'surname' => $validatedData['surname'],
            'othernames' => $validatedData['othernames'],
            'email' => $user->email,
            'phone' => $user->phone,
            'courier_name' => $validatedData['dispatch_mode'],
            'destination' => $validatedData['dispatch_country'],
            'address' => $validatedData['destination_address'],
            'address2' => $validatedData['destination2'] ?? null,
            'courier_type' => $validatedData['dispatch_mode'],
            'perm_address' => $user->email,
            'date' => now(),
        ]);
    }

    // Store the data
    $transDetails = TransDetailsNew::create($transDetailsItems);

    // Store uploaded file path separately
    if ($request->hasFile('file')) {
        $file = $request->file('file');
        $filePath = $file->store('uploads/notification', 'public');

        TransDetailsFiles::create([
            'trans_details_id' => $transDetails->id,
            'file_path' => $filePath,
        ]);
    } else {
        return redirect()->back()->with('error', 'File Upload Failed');
    }

    // Handle optional WES file upload
    if ($request->hasFile('wes_file')) {
        $wesFile = $request->file('wes_file');
        $wesFilePath = $wesFile->store('uploads/wes', 'public');
        
        TransDetailsFiles::create([
            'trans_details_id' => $transDetails->id,
            'file_path' => $wesFilePath,
            'file_type' => 'wes', // Add this column if you want to distinguish
        ]);
    }

    session()->push('cart', $cartItem);

    return redirect()->to('cart');
}



    public function adminDashboard()
    {
        $records = TransDetailsNew::where('status', 0)
            ->whereRaw('email REGEXP "^[0-9]+$"')
            ->whereHas('transInvoice', function ($query) {
                $query->whereColumn('amount_charge', 'amount_paid');
            })->with([
                'transInvoice' => function ($query) {
                    $query->select('invoiceno', 'purpose', 'dy', 'mth'); // Select only necessary fields
                }
            ])
            ->get();




        return view('admin.dashboard', ['records' => $records]); // Adjust the view path as necessary
    }

    public function transrecieveDashboard()
    {

        $records = TransDetailsNew::where('status', 2)
            ->whereRaw('email REGEXP "^[0-9]+$"')
            ->whereHas('transInvoice', function ($query) {
                $query->whereColumn('amount_charge', 'amount_paid');
            })->with([
                'transInvoice' => function ($query) {
                    $query->select('invoiceno', 'purpose', 'dy', 'mth', 'cheque'); // Select only necessary fields
                }
            ])
            ->get();



        return view('admin.transrecevedashboard', ['records' => $records]); // Adjust the view path as necessary
    }



    // public function transrecieveDashboard()
    // {
    //     $record = DB::table('trans_details_new')
    //     ->join('transinvoice', 'transinvoice.appno', '=', 'trans_details_new.matric') // Exact join you want
    //     ->where('trans_details_new.matric', $request->matric)
    //     ->where('transinvoice.invoiceno', $request->invoiceno)
    //     ->select('trans_details_new.id')
    //     ->whereHas('transInvoice', function ($query) {
    //         $query->whereColumn('amount_charge', 'amount_paid');
    //     })->with([
    //             'transInvoice' => function ($query) {
    //                 $query->select('invoiceno', 'purpose', 'dy', 'mth','cheque'); // Select only necessary fields
    //             }
    //         ])
    //     ->first();


    //     return view('admin.transrecevedashboard', ['records' => $records]); // Adjust the view path as necessary
    // }

    //     public function updateCheque(Request $request)
    // {
    //     // Validate the data
    //     $request->validate([
    //         'matric' => 'required',
    //         'invoiceno' => 'required',
    //         'cheque' => 'required|integer', // Corrected to 'cheque'
    //     ]);

    //     try {
    //         $updated = DB::table('trans_details_new')
    //             ->where('matric', $request->matric)
    //             ->where('invoiceno', $request->invoiceno)
    //             ->update(['status' => $request->cheque]);

    //         if ($updated) {
    //             return response()->json(['success' => true]);
    //         } else {
    //             return response()->json(['success' => false, 'message' => 'Record not found or no changes made']);
    //         }
    //     } catch (\Exception $e) {
    //         Log::error('Error updating cheque: ' . $e->getMessage());
    //         return response()->json(['success' => false, 'message' => 'Server error: ' . $e->getMessage()], 500);
    //     }
    // }


    public function updateCheque(Request $request)
    {
        $request->validate([
            'matric' => 'required',
            'invoiceno' => 'required',
            'cheque' => 'required|integer',
        ]);

        try {
            // Find trans_details_new record joined with transinvoice
            $record = DB::table('trans_details_new')
                ->join('transinvoice', 'transinvoice.appno', '=', 'trans_details_new.matric')
                ->where('trans_details_new.matric', $request->matric)
                ->where('transinvoice.invoiceno', $request->invoiceno)
                ->select('trans_details_new.id', 'transinvoice.invoiceno')
                ->first();

            if ($record) {
                // Update trans_details_new.status
                $transUpdate = DB::table('trans_details_new')
                    ->where('matric', $request->matric)
                    ->update(['status' => $request->cheque]);

                // Update transinvoice.cheque
                $invoiceUpdate = DB::table('transinvoice')
                    ->where('invoiceno', $record->invoiceno)
                    ->update(['cheque' => $request->cheque]);

                return response()->json([
                    'success' => true,
                    'trans_updated' => $transUpdate,
                    'invoice_updated' => $invoiceUpdate,
                    'id' => $record->id,
                    'invoiceno' => $record->invoiceno
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No matching record found with provided matric and invoiceno'
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error updating cheque: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }



    public function processRecord(Request $request)
    {

        $matric = $request->input('matric');
        $sessionAdmin = $request->input('sessionadmin');
        $sessiongrad = $request->input('sessiongrad');
        $invoiceNo = $request->input('invoiceNo');

        Log::info("Processing record for matric: $matric, sessionAdmin: $sessionAdmin,sessionGrad: $sessiongrad");

        if (preg_match('/^(\d{4})\/(\d{4})$/', $sessionAdmin, $matches)) {
            $startYear = intval($matches[1]);
            $endYear = intval($matches[2]);

            if ($startYear >= 2023) {
                // **2023/2024 and above: Check Result2023 first, fallback to Result2018**
                $biodata = StudentRecord::where('matric', $matric)->first();
                if (!$biodata) {
                    Log::error('No StudentRecord found for matric: ' . $matric);
                }
                $normalizedSecAdmin = preg_replace('/\/20(\d{2})$/', '/$1', $sessionAdmin);

                $biodata = TransDetailsNew::where('matric', $matric)->where('sessionadmin', $sessionAdmin)->where('email', $invoiceNo)->first();
                Log::info('biodata: ' . $biodata);
                Log::info('transDetails: ' . $transDetails);
                $gender = $biodata->sex ?? $transDetails->sex ?? 'N/A';


                $results = Result2023::with('course') // Eager load the 'course' relationship
                    ->with(['faculty', 'department'])
                    ->select('*') // Select all columns
                    ->where('matric', $matric)
                    ->where('yr_of_entry', $normalizedSecAdmin)
                    ->get()
                    ->makeHidden(['status']); // Hide the 'status' column


                Log::info('results: ' . $results);


                if ($results->isEmpty()) {
                    // If Result2023 is empty, fallback to Result2018
                    //$biodata = Biodata::where('matric', $matric)->first();
                    $biodata = TransDetailsNew::where('matric', $matric)->where('sessionadmin', $sessionAdmin)->where('email', $invoiceNo)->first();


                    // Try fetching from Result2018 first
                    $results = Result2018::with('course')
                        ->where('stud_id', $matric)
                        ->where('sec', $sessionAdmin)
                        ->get();
                }

                return view('admin.transcript', compact('biodata', 'results', 'gender'));
            } elseif ($startYear >= 2018 && $startYear <= 2022) {
                // **2018/2019 and above: Use Result2018**
                //$biodata = Biodata::where('matric', $matric)->first();
                $biodata = TransDetailsNew::where('matric', $matric)->where('sessionadmin', $sessionAdmin)->where('email', $invoiceNo)->first();

                $results = Result2018::with('course')
                    ->where('stud_id', $matric)
                    ->where('sec', $sessionAdmin)
                    ->get();

                return view('admin.transcript', compact('biodata', 'results'));
            } elseif ($startYear >= 2013 && $startYear <= 2017) {
                // **2013/2014 to 2016/2017: Check Result2018 first, fallback to ResultOld**
                $biodata = TransDetailsNew::where('matric', $matric)->where('sessionadmin', $sessionAdmin)->where('email', $invoiceNo)->first();

                $results = Result2018::where('stud_id', $matric)
                    ->where('sec', $sessionAdmin)
                    ->with('course')
                    ->get();


                if ($results->isEmpty() || !$biodata) {
                    // If Result2018 is empty, fallback to ResultOld
                    $records = TransDetailsNew::where('matric', $matric)
                        ->where('sessionadmin', $sessionAdmin)
                        ->where('email', $invoiceNo)
                        ->first();

                    $results = ResultOld::where('matno', $matric)
                        ->where('sec', $sessionAdmin)
                        ->with('course')
                        ->get();


                    return view('admin.transcript_old', compact('records', 'results'));
                }
                Log::info('Biodata: ' . $biodata);
                Log::info('result: ' . $results);

                return view('admin.transcript', compact('biodata', 'results'));
            } else {
                // **Older than 2013: Use ResultOld**
                $records = TransDetailsNew::where('matric', $matric)
                    ->where('sessionadmin', $sessionAdmin)
                    ->where('email', $invoiceNo)
                    ->first();

                $results = ResultOld::where('matno', $matric)
                    ->where('sec', $sessionAdmin)
                    ->with('course')
                    ->get();


                return view('admin.transcript_old', compact('records', 'results'));
            }

            // // **CGPA Calculation (for 2013 and above)**
            // $totalGradePoints = 0;
            // $totalUnits = 0;

            // foreach ($results as $result) {
            //     $score = $result->score;
            //     $courseUnit = $result->c_unit ?? 3;

            //     // Assign grade points
            //     $point = match (true) {
            //         $score <= 39 => 0,
            //         $score >= 40 && $score < 45 => 1,
            //         $score >= 45 && $score < 50 => 2,
            //         $score >= 50 && $score < 55 => 3,
            //         $score >= 55 && $score < 60 => 4,
            //         $score >= 60 && $score < 65 => 5,
            //         $score >= 65 && $score < 70 => 6,
            //         $score >= 70 && $score <= 100 => 7,
            //         default => 0
            //     };

            //     $totalGradePoints += $point * $courseUnit;
            //     $totalUnits += $courseUnit;
            // }

            // $cgpa = ($totalUnits > 0) ? number_format($totalGradePoints / $totalUnits, 2) : 'N/A';

            // Log::info("Results found: ", $results->toArray());

            // return view('admin.transcript', compact('biodata', 'results'));
        } else {
            Log::info('Not Match');
        }






        // if (
        //     preg_match('/^(\d{4})\/(\d{4})$/', $sessionAdmin, $matches)
        // ) {
        //     $startYear = intval($matches[1]);
        //     $endYear = intval($matches[2]);

        //     if ($startYear >= 2018 || ($endYear == 18 && $startYear >= 2017)) {
        //         // 2018/2019 and above
        //         $biodata = Biodata::where('matric', $matric)->first();
        //         $yearOfEntry = $biodata->yr_of_entry;
        //         $results = Result2018::with('course')
        //             ->where('stud_id', $matric)
        //             ->where('sec2', $yearOfEntry)
        //             ->get();

        //         $totalGradePoints = 0;
        //         $totalUnits = 0;

        //         foreach ($results as $result) {
        //             $score = $result->score;
        //             $courseUnit = $result->c_unit ?? 3;

        //             // Assign grade points
        //             $point = match (true) {
        //                 $score <= 39 => 0,
        //                 $score >= 40 && $score < 45 => 1,
        //                 $score >= 45 && $score < 50 => 2,
        //                 $score >= 50 && $score < 55 => 3,
        //                 $score >= 55 && $score < 60 => 4,
        //                 $score >= 60 && $score < 65 => 5,
        //                 $score >= 65 && $score < 70 => 6,
        //                 $score >= 70 && $score <= 100 => 7,
        //                 default => 0
        //             };

        //             $totalGradePoints += $point * $courseUnit;
        //             $totalUnits += $courseUnit;
        //         }

        //         $cgpa = ($totalUnits > 0) ? number_format($totalGradePoints / $totalUnits, 2) : 'N/A';

        //         Log::info("Results found: ", $results->toArray());

        //         return view('admin.transcript', compact('biodata', 'results', 'cgpa'));

        //     } elseif ($startYear >= 2013 && $startYear <= 2017) {
        //         // Handle 2013/2014 to 2016/2017 sessions
        //         $results = Result2018::where('stud_id', $matric)
        //             ->where('sec2', $sessionAdmin)
        //             ->with('course')
        //             ->get();
        //         $biodata = Biodata::where('matric', $matric)->first();

        //         $totalGradePoints = 0;
        //         $totalUnits = 0;

        //         foreach ($results as $result) {
        //             $score = $result->score;
        //             $courseUnit = $result->c_unit ?? 3;

        //             // Assign grade points
        //             $point = match (true) {
        //                 $score <= 39 => 0,
        //                 $score >= 40 && $score < 45 => 1,
        //                 $score >= 45 && $score < 50 => 2,
        //                 $score >= 50 && $score < 55 => 3,
        //                 $score >= 55 && $score < 60 => 4,
        //                 $score >= 60 && $score < 65 => 5,
        //                 $score >= 65 && $score < 70 => 6,
        //                 $score >= 70 && $score <= 100 => 7,
        //                 default => 0
        //             };

        //             $totalGradePoints += $point * $courseUnit;
        //             $totalUnits += $courseUnit;
        //         }

        //         $cgpa = ($totalUnits > 0) ? number_format($totalGradePoints / $totalUnits, 2) : 'N/A';

        //         Log::info("Results found: ", $results->toArray());

        //         return view('admin.transcript', compact('biodata', 'results', 'cgpa'));


        //         if ($results->isEmpty()) {
        //             // If no results in Result2018, check in ResultOld
        //             $records = TransDetailsNew::where('matric', $matric)
        //             ->where('sessionadmin', $sessionAdmin)
        //             ->first();

        //         $results = ResultOld::where('matno', $matric)
        //             ->where('sec', $sessionAdmin)
        //             ->with('course')
        //             ->get();

        //         Log::error("Invalid sessionadmin value: $sessionAdmin");
        //         return view('admin.transcript_old', compact('records', 'results'));

        //         }



        //     } else {
        //         // Sessions before 2013 or unrecognized formats
        //         $records = TransDetailsNew::where('matric', $matric)
        //             ->where('sessionadmin', $sessionAdmin)
        //             ->first();

        //         $results = ResultOld::where('matno', $matric)
        //             ->where('sec', $sessionAdmin)
        //             ->with('course')
        //             ->get();

        //         Log::error("Invalid sessionadmin value: $sessionAdmin");
        //         return view('admin.transcript_old', compact('records', 'results'));
        //     }
        // }

    }

    public function processTranscript(Request $request)
    {

        $matric = $request->input('matric');
        $sessionAdmin = $request->input('sessionadmin');
        $sessiongrad = $request->input('sessiongrad');

        $transDetails = TransDetailsNew::with([
            'transInvoice' => function ($query) {
                $query->select('invoiceno', 'purpose', 'dy', 'mth', 'cheque');
            },
            'transDetailsFiles:id,trans_details_id,file_path'
        ])
            ->where('matric', $matric)
            ->where('sessionadmin', $sessionAdmin)
            ->first();

        $cgpa = $transDetails->award;
        $degreeAwarded = $transDetails->programme;
        $dateAward = $transDetails->dateAward;
        $gender = $transDetails->sex ?? 'N/A';

        // try {
        //             $this->sendTranscriptEmailOnApproval($transDetails);
        //             Log::info("Transcript email sent successfully to: " );
        //         } catch (\Exception $e) {
        //             Log::error("Failed to send transcript email: " . $e->getMessage());
        //             // Don't fail the approval process if email fails
        //         }


        Log::info("Processing record for matric: $matric, sessionAdmin: $sessionAdmin,sessionGrad: $sessiongrad");

        if (preg_match('/^(\d{4})\/(\d{4})$/', $sessionAdmin, $matches)) {
            $startYear = intval($matches[1]);
            $endYear = intval($matches[2]);

            if ($startYear >= 2023) {
                // **2023/2024 and above: Check Result2023 first, fallback to Result2018**
                $biodata = StudentRecord::where('matric', $matric)->first();
                if (!$biodata) {
                    Log::error('No StudentRecord found for matric: ' . $matric);
                }
                $normalizedSecAdmin = preg_replace('/\/20(\d{2})$/', '/$1', $sessionAdmin);

                $biodata = TransDetailsNew::with([
                    'transInvoice' => function ($query) {
                        $query->select('invoiceno', 'purpose', 'dy', 'mth', 'cheque');
                    },
                    'transDetailsFiles:id,trans_details_id,file_path'
                ])
                    ->where('matric', $matric)
                    ->where('sessionadmin', $sessionAdmin)
                    ->first();
                Log::info('biodata: ' . $biodata);
                Log::info('transDetails: ' . $transDetails);
                $gender = $biodata->sex ?? $transDetails->sex ?? 'N/A';


                $results = Result2023::with('course') // Eager load the 'course' relationship
                    ->with(['faculty', 'department'])
                    ->select('*') // Select all columns
                    ->where('matric', $matric)
                    ->where('yr_of_entry', $normalizedSecAdmin)
                    ->get()
                    ->makeHidden(['status']); // Hide the 'status' column


                Log::info('results: ' . $results);


                if ($results->isEmpty()) {
                    // If Result2023 is empty, fallback to Result2018
                    //$biodata = Biodata::where('matric', $matric)->first();
                    $biodata = TransDetailsNew::with([
                        'transInvoice' => function ($query) {
                            $query->select('invoiceno', 'purpose', 'dy', 'mth', 'cheque');
                        },
                        'transDetailsFiles:id,trans_details_id,file_path'
                    ])
                        ->where('matric', $matric)
                        ->where('sessionadmin', $sessionAdmin)
                        ->first();


                    // Try fetching from Result2018 first
                    $results = Result2018::with('course')
                        ->where('stud_id', $matric)
                        ->where('sec', $sessionAdmin)
                        ->get();
                }

                Log::info('Biodata: ' . $biodata);
                Log::info('result: ' . $results);

                return view('admin.approvedTranscript', compact('biodata', 'results', 'degreeAwarded', 'dateAward', 'cgpa', 'gender'));
            } elseif ($startYear >= 2018 && $startYear <= 2022) {
                // **2018/2019 and above: Use Result2018**
                //$biodata = Biodata::where('matric', $matric)->first();
                $biodata = TransDetailsNew::with([
                    'transInvoice' => function ($query) {
                        $query->select('invoiceno', 'purpose', 'dy', 'mth', 'cheque');
                    },
                    'transDetailsFiles:id,trans_details_id,file_path'
                ])
                    ->where('matric', $matric)
                    ->where('sessionadmin', $sessionAdmin)
                    ->first();

                $results = Result2018::with('course')
                    ->where('stud_id', $matric)
                    ->where('sec', $sessionAdmin)
                    ->get();
                Log::info('Biodata: ' . $biodata);
                Log::info('result: ' . $results);
                return view('admin.approvedTranscript', compact('biodata', 'results', 'degreeAwarded', 'dateAward', 'cgpa', 'gender'));
            } elseif ($startYear >= 2013 && $startYear <= 2017) {
                // **2013/2014 to 2016/2017: Check Result2018 first, fallback to ResultOld**
                $biodata = TransDetailsNew::with([
                    'transInvoice' => function ($query) {
                        $query->select('invoiceno', 'purpose', 'dy', 'mth', 'cheque');
                    },
                    'transDetailsFiles:id,trans_details_id,file_path'
                ])
                    ->where('matric', $matric)
                    ->where('sessionadmin', $sessionAdmin)
                    ->first();

                $results = Result2018::where('stud_id', $matric)
                    ->where('sec', $sessionAdmin)
                    ->with('course')
                    ->get();


                if ($results->isEmpty() || !$biodata) {
                    // If Result2018 is empty, fallback to ResultOld
                    $biodata = TransDetailsNew::with([
                        'transInvoice' => function ($query) {
                            $query->select('invoiceno', 'purpose', 'dy', 'mth', 'cheque');
                        },
                        'transDetailsFiles:id,trans_details_id,file_path'
                    ])
                        ->where('matric', $matric)
                        ->where('sessionadmin', $sessionAdmin)
                        ->first();

                    $results = ResultOld::where('matno', $matric)
                        ->where('sec', $sessionAdmin)
                        ->with('course')
                        ->get();
                    Log::info('Biodata: ' . $biodata);
                    Log::info('result: ' . $results);

                    return view('admin.approvedTranscript', compact('biodata', 'results', 'degreeAwarded', 'dateAward', 'cgpa', 'gender'));
                }
                Log::info('Biodata: ' . $biodata);
                Log::info('result: ' . $results);

                return view('admin.approvedTranscript', compact('biodata', 'results', 'degreeAwarded', 'dateAward', 'cgpa', 'gender'));
            } else {
                // **Older than 2013: Use ResultOld**
                $biodata = TransDetailsNew::with([
                    'transInvoice' => function ($query) {
                        $query->select('invoiceno', 'purpose', 'dy', 'mth', 'cheque');
                    },
                    'transDetailsFiles:id,trans_details_id,file_path'
                ])
                    ->where('matric', $matric)
                    ->where('sessionadmin', $sessionAdmin)
                    ->first();

                $results = ResultOld::where('matno', $matric)
                    ->where('sec', $sessionAdmin)
                    ->with('course')
                    ->get();

                Log::info('Biodata: ' . $biodata);
                Log::info('result: ' . $results);
                return view('admin.approvedTranscript', compact('biodata', 'results', 'degreeAwarded', 'dateAward', 'cgpa', 'gender'));
            }
            

            // // **CGPA Calculation (for 2013 and above)**
            // $totalGradePoints = 0;
            // $totalUnits = 0;

            // foreach ($results as $result) {
            //     $score = $result->score;
            //     $courseUnit = $result->c_unit ?? 3;

            //     // Assign grade points
            //     $point = match (true) {
            //         $score <= 39 => 0,
            //         $score >= 40 && $score < 45 => 1,
            //         $score >= 45 && $score < 50 => 2,
            //         $score >= 50 && $score < 55 => 3,
            //         $score >= 55 && $score < 60 => 4,
            //         $score >= 60 && $score < 65 => 5,
            //         $score >= 65 && $score < 70 => 6,
            //         $score >= 70 && $score <= 100 => 7,
            //         default => 0
            //     };

            //     $totalGradePoints += $point * $courseUnit;
            //     $totalUnits += $courseUnit;
            // }

            // $cgpa = ($totalUnits > 0) ? number_format($totalGradePoints / $totalUnits, 2) : 'N/A';

            // Log::info("Results found: ", $results->toArray());

            // return view('admin.transcript', compact('biodata', 'results'));
        } else {
            Log::info('Not Match');
        }
    }



    public function submitForApproval(Request $request)
    {
        try {
            Log::info('Request Method: ' . $request->method());
            Log::info('Request Data:', $request->all());

            // Validate input
            $request->validate([
                'matric' => 'required',
                'invoiceNo' => 'required',
                'secAdmin' => 'required',
                'cgpa' => 'required|numeric|min:0',
                'degreeAward' => 'required|string|max:255',
                'awardDate' => 'required|string|max:255',
            ]);

            // Normalize session admin value
            $normalizedSecAdmin = preg_replace('/\/(\d{2})$/', '/20$1', $request->secAdmin);

            // Retrieve transcript record
                $transcript = TransDetailsNew::where('email', $request->invoiceNo)
                ->where('matric', $request->matric)
                ->where('sessionadmin', $normalizedSecAdmin)
                ->firstOrFail();


            if (!$transcript) {
                return back()->with('error', 'Transcript record not found.');
            }

            // Update transcript record
            $transcript->update([
                'award' => $request->cgpa,
                'programme' => $request->degreeAward,
                'dateAward' => $request->awardDate,
                'status' => 7,
            ]);

            return redirect()->route('admin.dashboard.to')->with('success', 'Transcript submitted for approval.');
        } catch (\Exception $e) {
            Log::error('Error submitting transcript: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while submitting the transcript. Please try again.');
        }
    }
    public function approve(Request $request)
    {
        try {
            Log::info('approve Request Method: ' . $request->method());
            Log::info('approve Request Data:', $request->all());

            // Validate input
            $request->validate([
                'matric' => 'required',
                'sessionadmin' => 'required',

            ]);


            // Retrieve transcript record
            $transcript = TransDetailsNew::with([
                'transInvoice' => function ($query) {
                    $query->select('invoiceno', 'purpose', 'dy', 'mth', 'cheque');
                },
                'transDetailsFiles:id,trans_details_id,file_path'
            ])
                ->where('matric', $request->matric)
                ->where('sessionadmin', $request->sessionadmin)
                ->where('status', 7)
                ->first();

            if (!$transcript) {
                return back()->with('error', 'Transcript record not found.');
            }

            // Update transcript record
            $transcript->update([
                'status' => 8,
            ]);

            // Send transcript email if email is available
            //$courier = Courier::where('appno', $request->matric)->first();
            $destinationEmail = $transcript->ecopy_email ?? null;

            if ((Str::contains($transcript->transInvoice->purpose, 'E-Copy')) || (Str::contains($transcript->transInvoice->purpose, 'Soft Copy'))) {
                try {
                    $this->sendTranscriptEmailOnApproval($transcript);
                    Log::info("Transcript email sent successfully to: " . $destinationEmail);
                } catch (\Exception $e) {
                    Log::error("Failed to send transcript email: " . $e->getMessage());
                    // Don't fail the approval process if email fails
                }
            } else {
                Log::warning("No email address found for transcript approval. Matric: " . $request->matric);
            }

            return redirect()->route('admin.recordProcesseds')->with('success', 'Transcript Approved Successfully.');
        } catch (\Exception $e) {
            Log::error('Error submitting approve transcript: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while submitting the transcript. Please try again.');
        }
    }
    public function reject(Request $request)
    {
        try {
            Log::info('reject Request Method: ' . $request->method());
            Log::info('reject Request Data:', $request->all());

            // Validate input
            $request->validate([
                'matric' => 'required',
                'sessionadmin' => 'required',

            ]);

            // Normalize session admin value

            // Retrieve transcript record
            $transcript = TransDetailsNew::where('matric', $request->matric)
                ->where('sessionadmin', $request->sessionadmin)
                ->first();

            if (!$transcript) {
                return back()->with('error', 'Transcript record not found.');
            }

            // Update transcript record
            $transcript->update([
                'status' => 0,
            ]);

            return redirect()->route('admin.recordProcesseds')->with('success', 'Transcript Rejected Successfully.');
        } catch (\Exception $e) {
            Log::error('Error submitting reject transcript: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while submitting the transcript. Please try again.');
        }
    }
    public function transcriptRejectToKey(Request $request)
    {
        try {
            Log::info('reject Request Method: ' . $request->method());
            Log::info('reject Request Data:', $request->all());

            // Validate input
            $request->validate([
                'matric' => 'required',
                'sessionadmin' => 'required',

            ]);

            // Normalize session admin value

            // Retrieve transcript record
            $transcript = TransDetailsNew::where('matric', $request->matric)
                ->where('sessionadmin', $request->sessionadmin)
                ->first();

            if (!$transcript) {
                return back()->with('error', 'Transcript record not found.');
            }

            // Update transcript record
            $transcript->update([
                'status' => 3,
            ]);

            return redirect()->route('admin.dashboard.to')->with('success', 'Transcript Rejected Successfully.');
        } catch (\Exception $e) {
            Log::error('Error submitting reject transcript: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while submitting the transcript. Please try again.');
        }
    }


    public function recordProcessed()
    {
        $records = TransDetailsNew::where('status', 7)
            ->whereRaw('email REGEXP "^[0-9]+$"')
            ->whereHas('transInvoice', function ($query) {
                $query->whereColumn('amount_charge', 'amount_paid');
            })->with([
                'transInvoice' => function ($query) {
                    $query->select('invoiceno', 'purpose', 'dy', 'mth'); // Select only necessary fields
                }
            ])
            ->get();



        return view('admin.recordProcessed', ['records' => $records]); // Adjust the view path as necessary
    }
    public function recordApproved()
    {
        $records = TransDetailsNew::where('status', 8)
            ->whereRaw('email REGEXP "^[0-9]+$"')
            ->whereHas('transInvoice', function ($query) {
                $query->whereColumn('amount_charge', 'amount_paid');
            })->with([
                'transInvoice' => function ($query) {
                    $query->select('invoiceno', 'purpose', 'dy', 'mth'); // Select only necessary fields
                }
            ])
            ->get();



        return view('admin.recordApproved', ['records' => $records]); // Adjust the view path as necessary
    }


    public function trackApplication()
    {

        $matric = session('matric');
        $records = TransDetailsNew::where('matric', $matric)->whereRaw('email REGEXP "^[0-9]+$"')
            ->whereHas('transInvoice', function ($query) {
                $query->whereColumn('amount_charge', 'amount_paid');
            })->with([
                'transInvoice' => function ($query) {
                    $query->select('invoiceno', 'purpose', 'dy', 'mth'); // Select only necessary fields
                }
            ])
            ->get();



        return view('track', ['records' => $records]); // Adjust the view path as necessary


    }

    //  public function processTranscript(Request $request)
    // {

    //     $matric = $request->input('matric');
    //     $sessionAdmin = $request->input('sessionadmin');

    //     $transDetails = TransDetailsNew::where('matric', $matric)
    //     ->where('sessionadmin', $sessionAdmin)
    //     ->first();

    //     $cgpa = $transDetails->award;
    //     $degreeAwarded = $transDetails->programme;
    //     $gender = $transDetails->sex ?? 'N/A';


    //     Log::info("Processing Transcript for matric: $matric, sessionAdmin: $sessionAdmin");

    //     if (preg_match('/^(\d{4})\/(\d{4})$/', $sessionAdmin, $matches)) {
    //         $startYear = intval($matches[1]);
    //         $endYear = intval($matches[2]);

    //         if ($startYear >= 2023) {
    //             // **2023/2024 and above: Check Result2023 first, fallback to Result2018**
    //             $biodata = StudentRecord::where('matric', $matric)->first();
    //             if (!$biodata) {
    //                 Log::error('No StudentRecord found for matric: ' . $matric);
    //             }
    //             $normalizedSecAdmin = preg_replace('/\/20(\d{2})$/', '/$1', $sessionAdmin);

    //             $transDetail = TransDetailsNew::where('matric', $matric)->first();
    //             Log::info('biodata: ' . $biodata);
    //             Log::info('transDetails: ' . $transDetail);
    //             $gender = $biodata->sex ?? $transDetail->sex ?? 'N/A';




    //             $results = Result2023::with('course') // Eager load the 'course' relationship
    //             ->with(['faculty', 'department'])
    //                 ->select('*') // Select all columns
    //                 ->where('matric', $matric)
    //                 ->where('yr_of_entry', $normalizedSecAdmin)
    //                 ->get()
    //                 ->makeHidden(['status']); // Hide the 'status' column


    //             Log::info('results: ' . $results);


    //             if ($results->isEmpty()) {
    //                 // If Result2023 is empty, fallback to Result2018
    //                 $biodata = Biodata::where('matric', $matric)->first();


    //                 // Try fetching from Result2018 first
    //                 $results = Result2018::with('course')
    //                     ->where('stud_id', $matric)
    //                     ->where('sec', $sessionAdmin)
    //                     ->get();
    //             }



    //         } elseif ($startYear >= 2018 && $startYear <= 2022) {
    //             // **2018/2019 and above: Use Result2018**
    //             $biodata = Biodata::where('matric', $matric)->first();

    //             $results = Result2018::with('course')
    //                 ->where('stud_id', $matric)
    //                 ->where('sec', $sessionAdmin)
    //                 ->get();



    //         } elseif ($startYear >= 2013 && $startYear <= 2017) {
    //             // **2013/2014 to 2016/2017: Check Result2018 first, fallback to ResultOld**
    //             $biodata = Biodata::where('matric', $matric)->first();

    //             $results = Result2018::where('stud_id', $matric)
    //                 ->where('sec', $sessionAdmin)
    //                 ->with('course')
    //                 ->get();


    //             if ($results->isEmpty() || !$biodata) {
    //                 // If Result2018 is empty, fallback to ResultOld
    //                 $biodata = TransDetailsNew::where('matric', $matric)
    //                     ->where('sessionadmin', $sessionAdmin)
    //                     ->first();

    //                 $results = ResultOld::where('matno', $matric)
    //                     ->where('sec', $sessionAdmin)
    //                     ->with('course')
    //                     ->get();

    //             }


    //         } else {
    //             // **Older than 2013: Use ResultOld**
    //             $biodata = TransDetailsNew::where('matric', $matric)
    //                 ->where('sessionadmin', $sessionAdmin)
    //                 ->first();

    //             $results = ResultOld::where('matno', $matric)
    //                 ->where('sec', $sessionAdmin)
    //                 ->with('course')
    //                 ->get();

    //         }
    //         return view('admin.approvedTranscript', compact('biodata', 'results', 'degreeAwarded', 'cgpa', 'gender'));

    //     }


    // }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }

    private function sendTranscriptEmailOnApproval($transcript)
    {
        $matric = $transcript->matric;
        $sessionAdmin = $transcript->sessionadmin;

        // Get courier information for destination email
        //$courier = Courier::where('appno', $matric)->first();
        $destinationEmail = $transcript->ecopy_email ;

        $transDetails = TransDetailsNew::with([
            'transInvoice' => function ($query) {
                $query->select('invoiceno', 'purpose', 'dy', 'mth', 'cheque');
            },
            'transDetailsFiles:id,trans_details_id,file_path'
        ])
            ->where('matric', $matric)
            ->where('sessionadmin', $sessionAdmin)
            ->first();

        $cgpa = $transDetails->award;
        $degreeAwarded = $transDetails->programme;
        $dateAward = $transDetails->dateAward;
        $gender = $transDetails->sex ?? 'N/A';

        Log::info("Sending transcript email for matric: $matric, sessionAdmin: $sessionAdmin, to: $destinationEmail");

        if (preg_match('/^(\d{4})\/(\d{4})$/', $sessionAdmin, $matches)) {
            $startYear = intval($matches[1]);
            $endYear = intval($matches[2]);

            if ($startYear >= 2023) {
                // **2023/2024 and above: Check Result2023 first, fallback to Result2018**
                $biodata = StudentRecord::where('matric', $matric)->first();
                if (!$biodata) {
                    Log::error('No StudentRecord found for matric: ' . $matric);
                }
                $normalizedSecAdmin = preg_replace('/\/20(\d{2})$/', '/$1', $sessionAdmin);

                $biodata = TransDetailsNew::with([
                    'transInvoice' => function ($query) {
                        $query->select('invoiceno', 'purpose', 'dy', 'mth', 'cheque');
                    },
                    'transDetailsFiles:id,trans_details_id,file_path'
                ])
                    ->where('matric', $matric)
                    ->where('sessionadmin', $sessionAdmin)
                    ->first();

                $gender = $biodata->sex ?? $transDetails->sex ?? 'N/A';

                $results = Result2023::with('course')
                    ->with(['faculty', 'department'])
                    ->select('*')
                    ->where('matric', $matric)
                    ->where('yr_of_entry', $normalizedSecAdmin)
                    ->get()
                    ->makeHidden(['status']);

                if ($results->isEmpty()) {
                    $biodata = TransDetailsNew::with([
                        'transInvoice' => function ($query) {
                            $query->select('invoiceno', 'purpose', 'dy', 'mth', 'cheque');
                        },
                        'transDetailsFiles:id,trans_details_id,file_path'
                    ])
                        ->where('matric', $matric)
                        ->where('sessionadmin', $sessionAdmin)
                        ->first();

                    $results = Result2018::with('course')
                        ->where('stud_id', $matric)
                        ->where('sec', $sessionAdmin)
                        ->get();
                }
            } elseif ($startYear >= 2018 && $startYear <= 2022) {
                // **2018/2019 and above: Use Result2018**
                $biodata = TransDetailsNew::with([
                    'transInvoice' => function ($query) {
                        $query->select('invoiceno', 'purpose', 'dy', 'mth', 'cheque');
                    },
                    'transDetailsFiles:id,trans_details_id,file_path'
                ])
                    ->where('matric', $matric)
                    ->where('sessionadmin', $sessionAdmin)
                    ->first();

                $results = Result2018::with('course')
                    ->where('stud_id', $matric)
                    ->where('sec', $sessionAdmin)
                    ->get();
            } elseif ($startYear >= 2013 && $startYear <= 2017) {
                // **2013/2014 to 2016/2017: Check Result2018 first, fallback to ResultOld**
                $biodata = TransDetailsNew::with([
                    'transInvoice' => function ($query) {
                        $query->select('invoiceno', 'purpose', 'dy', 'mth', 'cheque');
                    },
                    'transDetailsFiles:id,trans_details_id,file_path'
                ])
                    ->where('matric', $matric)
                    ->where('sessionadmin', $sessionAdmin)
                    ->first();

                $results = Result2018::where('stud_id', $matric)
                    ->where('sec', $sessionAdmin)
                    ->with('course')
                    ->get();

                if ($results->isEmpty() || !$biodata) {
                    $biodata = TransDetailsNew::with([
                        'transInvoice' => function ($query) {
                            $query->select('invoiceno', 'purpose', 'dy', 'mth', 'cheque');
                        },
                        'transDetailsFiles:id,trans_details_id,file_path'
                    ])
                        ->where('matric', $matric)
                        ->where('sessionadmin', $sessionAdmin)
                        ->first();

                    $results = ResultOld::where('matno', $matric)
                        ->where('sec', $sessionAdmin)
                        ->with('course')
                        ->get();
                }
            } else {
                // **Older than 2013: Use ResultOld**
                $biodata = TransDetailsNew::with([
                    'transInvoice' => function ($query) {
                        $query->select('invoiceno', 'purpose', 'dy', 'mth', 'cheque');
                    },
                    'transDetailsFiles:id,trans_details_id,file_path'
                ])
                    ->where('matric', $matric)
                    ->where('sessionadmin', $sessionAdmin)
                    ->first();

                $results = ResultOld::where('matno', $matric)
                    ->where('sec', $sessionAdmin)
                    ->with('course')
                    ->get();
            }

            // Prepare data for PDF generation
            $data = compact('biodata', 'results', 'degreeAwarded', 'dateAward', 'cgpa', 'gender');

            // Generate PDFs
            $pdfTranscript = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.partials.transcript_main', $data+ ['forPdf' => true])->output();
            $pdfLetter = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.partials.transcript_letter', $data + ['forPdf' => true])->output();

            // Send email
            try {
                Mail::to($destinationEmail)->send(new \App\Mail\TranscriptMail($data, $pdfTranscript, $pdfLetter));
                Log::info("Transcript email sent successfully to: " . $destinationEmail);
            } catch (\Exception $e) {
                Log::error("Failed to send transcript email: " . $e->getMessage());
                throw $e; // Re-throw the exception to handle it in the calling method
            }
            // Mail::to($destinationEmail)->send(new \App\Mail\TranscriptMail($data, $pdfTranscript, $pdfLetter));
        } else {
            Log::info('Invalid session format for email sending');
            throw new \Exception('Invalid session format');
        }
    }
}
