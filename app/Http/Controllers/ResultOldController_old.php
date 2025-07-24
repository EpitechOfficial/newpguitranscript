<?php

// app/Http/Controllers/ResultOldController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\ResultOldImport;
use Maatwebsite\Excel\Facades\Excel;

class ResultOldController extends Controller
{
    public function uploadForm()
    {
        return view('result_old.upload');
    }

    public function uploadExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xls,xlsx,csv'
        ]);

        Excel::import(new ResultOldImport, $request->file('file'));

        // return back()->with('success', 'Records uploaded successfully.');
        return redirect()->route('admin.dashboard.ki')->with('success', 'Records uploaded successfully.');
    }
}

