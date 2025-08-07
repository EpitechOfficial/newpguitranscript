<?php

// app/Http/Controllers/ResultOldController.php
namespace App\Http\Controllers;

use App\Models\TransDetailsNew;
use App\Models\ResultOld;
use Illuminate\Http\Request;
use App\Imports\ResultOldImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;


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

        try {
            $import = new ResultOldImport();
            Excel::import($import, $request->file('file'));

            $results = session('import_results', []);
            
            // Build success message
            $message = "Import completed!\n";
            $message .= "Total rows processed: " . ($results['total_rows'] ?? 0) . "\n";
            $message .= "Successfully imported: " . ($results['success_count'] ?? 0) . " records\n";
            
            if (!empty($results['duplicates'])) {
                $message .= "Duplicates found: " . count($results['duplicates']) . " records\n";
            }
            
            if (!empty($results['errors'])) {
                $message .= "Errors: " . count($results['errors']) . " records\n";
            }

            // Store detailed results for display
            session([
                'import_duplicates' => $results['duplicates'] ?? [],
                'import_errors' => $results['errors'] ?? [],
                'import_summary' => [
                    'total_rows' => $results['total_rows'] ?? 0,
                    'success_count' => $results['success_count'] ?? 0,
                    'duplicate_count' => count($results['duplicates'] ?? []),
                    'error_count' => count($results['errors'] ?? [])
                ]
            ]);

            return redirect()->route('result_old.upload_form')->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->route('result_old.upload_form')
                ->with('error', 'Import failed: ' . $e->getMessage());
        }
    }

    // Real-time transcript editing page
    public function editTranscriptRealtimePage()
    {
        return view('admin.edit_transcript_realtime');
    }

    // AJAX: fetch available sessions for a matric number
    public function fetchSessions(Request $request)
    {
        $matric = $request->query('matric');
        if (!$matric) {
            return response()->json(['sessions' => []]);
        }

        $sessions = ResultOld::where('matno', $matric)
            ->distinct()
            ->pluck('sec')
            ->filter()
            ->values()
            ->toArray();

        return response()->json(['sessions' => $sessions]);
    }

    // AJAX: fetch transcript by matric and sec
    public function fetchTranscriptRealtime(Request $request)
    {
        $matric = $request->query('matric');
        $sec = $request->query('sec');
        
        if (!$matric || !$sec) {
            return response()->json(['results' => []]);
        }
        
        $record = TransDetailsNew::where('matric', $matric)->first();
        $fullName = $record ? ($record->Surname . ' ' . $record->Othernames) : 'N/A';

        $results = ResultOld::where('matno', $matric)
            ->where('sec', $sec)
            ->with('course')
            ->get();
            
        if ($results->isEmpty()) {
            return response()->json(['results' => []]);
        }
        
        $data = [
            'matric' => $matric,
            'sec' => $sec,
            'name' => $fullName,
            'results' => $results->map(function($r) {
                return [
                    'id' => $r->id,
                    'course_id' => $r->course ? $r->course->id : '',
                    'code' => $r->code,
                    'course_title' => $r->course->title ?? '',
                    'c_unit' => $r->course->unit ?? '',
                    'status' => $r->status,
                    'score' => $r->score,
                    'grade' => $r->grade ?? '',
                ];
            }),
        ];
        
        return response()->json($data);
    }

    // AJAX: update a course result field
    public function updateTranscriptRealtime(Request $request)
    {
        $id = $request->input('id');
        $matric = $request->input('matric');
        $field = $request->input('field');
        $value = $request->input('value');
        Log::info('value: ' . $value);

        $result = ResultOld::where('matno', $matric)->first();

        if (!$result) {
            return response()->json(['success' => false, 'message' => 'Result not found.']);
        }
        // Only allow certain fields to be updated
        if (in_array($field, ['status', 'score', 'grade'])) {
            $result->$field = $value;
            $result->save();
            return response()->json(['success' => true]);
        }
        // If editing course_title or c_unit, update CourseOnline
        if (in_array($field, ['course_title', 'c_unit'])) {
            if ($result->course) {
                $course = $result->course;
                $course->$field = $value;
                $course->save();
                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false, 'message' => 'Course not found.']);
            }
        }
        // If editing course code
        if (in_array($field, ['code'])) {
            $oldCode = $result->code;
            $newCode = $value;
            if (!$newCode) {
                return response()->json(['success' => false, 'message' => 'Course code cannot be empty.']);
            }
            // Check if course exists, if not create it
            $course = \App\Models\CourseOnline::where('course', $newCode)->first();
            if (!$course) {
                $course = new \App\Models\CourseOnline();
                $course->course = $newCode;
                $course->title = '';
                $course->unit = '';
                $course->status = '';
                $course->save();
            }
            // Update ResultOld to reference new code
            $result->code = $newCode;
            $result->save();
            // Return new course details for UI update
            return response()->json([
                'success' => true,
                'course_title' => $course->course_title,
                'c_unit' => $course->c_unit,
            ]);
        }
        return response()->json(['success' => false, 'message' => 'Field not editable.']);
    }

    public function saveTranscriptRealtime(Request $request)
    {
        $rows = $request->input('rows', []);
        $matric = $request->input('matric');
        $sec = $request->input('sec');
        $errors = [];
        
        foreach ($rows as $row) {
            $result = \App\Models\ResultOld::find($row['result_id']);
            if ($result) {
                $oldCode = $result->code;
                $newCode = $row['code'] ?? $oldCode;

                // Update ResultOld fields
                $result->code = $newCode;
                if (isset($row['status'])) $result->status = $row['status'];
                if (isset($row['score'])) $result->score = $row['score'];
                $result->save();

                // Handle CourseOnline
                $course = \App\Models\CourseOnline::where('course', $newCode)->first();

                if (!$course) {
                    // Only create if it doesn't exist
                    $course = new \App\Models\CourseOnline();
                    $course->course = $newCode;
                    if (isset($row['course_title'])) $course->course_title = $row['course_title'];
                    if (isset($row['c_unit'])) $course->c_unit = $row['c_unit'];
                    if (isset($row['status'])) $course->status = $row['status'];
                    $course->save();
                }
                // If it exists, do not update
            } else {
                $errors[] = "ResultOld not found for ID {$row['result_id']}";
                continue;
            }
        }
        return response()->json([
            'success' => count($errors) === 0,
            'errors' => $errors,
        ]);
    }

    public function deleteTranscriptRealtime(Request $request)
    {
        $resultIds = $request->input('result_ids', []);
        $matric = $request->input('matric');
        $sec = $request->input('sec');
        $errors = [];
        $deletedCount = 0;

        foreach ($resultIds as $resultId) {
            $result = ResultOld::where('id', $resultId)
                ->where('matno', $matric)
                ->where('sec', $sec)
                ->first();

            if ($result) {
                try {
                    $result->delete();
                    $deletedCount++;
                } catch (\Exception $e) {
                    $errors[] = "Failed to delete result ID {$resultId}: " . $e->getMessage();
                }
            } else {
                $errors[] = "Result not found for ID {$resultId}";
            }
        }

        return response()->json([
            'success' => count($errors) === 0,
            'deleted_count' => $deletedCount,
            'errors' => $errors,
        ]);
    }


}

