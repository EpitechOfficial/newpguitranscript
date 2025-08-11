<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Result2023;
use App\Models\DeptNew;
use App\Models\StudentRecord;
use Illuminate\Support\Facades\Log;

class StudentsByDepartmentController extends Controller
{
    /**
     * Show the students by department page
     */
    public function index()
    {
       dd('This runs');
         if (!config('features.mass_transcript')) {
        abort(404);
        
    }
        return view('admin.students_by_department');
    }

    /**
     * Fetch all departments that have students
     */
    public function fetchDepartments()
    {
        try {
            // Get departments from DeptNew that have students in Result2023
            $departments = DeptNew::whereIn('id', function ($query) {
                $query->select('dept')
                    ->from('testscore')
                    ->where('status', 1)
                    ->distinct();
            })
                ->orderBy('department', 'asc')
                ->pluck('department', 'id');

            return response()->json(['departments' => $departments]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error fetching departments: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Fetch students for a specific department where status = 1
     */


    public function fetchStudents(Request $request)
    {
        try {
            $departmentId = $request->get('department');

            if (!$departmentId) {
                return response()->json(['error' => 'Department is required'], 400);
            }

            // Add logging to debug the query
            Log::info('Fetching students for department: ' . $departmentId);

            // Check if the Result2023 model and table exist
            if (!class_exists('App\\Models\\Result2023')) {
                Log::error('Result2023 model not found');
                return response()->json(['error' => 'Result2023 model not found'], 500);
            }

            $query = Result2023::where('dept', $departmentId)
                ->where('status', 1);

            // Log the SQL query for debugging
            Log::info('SQL Query: ' . $query->toSql());
            Log::info('Query Bindings: ' . json_encode($query->getBindings()));

            $students = $query
                ->with(['department', 'studentRecord']) // Include studentRecord relationship
                ->select('matric', 'dept', 'yr_of_entry as session')
                ->orderBy('matric')
                ->distinct()
                ->get()
                ->map(function ($student) {
                    $name = 'Record not in Student Database';

                    // Better handling of student name
                    if ($student->studentRecord && !empty($student->studentRecord->name)) {
                        $name = trim($student->studentRecord->name);
                    } elseif (isset($student->name) && !empty($student->name)) {
                        $name = trim($student->name);
                    }

                    // Handle empty names
                    if (empty($name) || $name === ' ') {
                        $name = 'Name not found';
                    }

                    return [
                        'matric' => $student->matric,
                        'name' => $name,
                        'department' => $student->department->department ?? 'Unknown Department',
                        'session' => $student->session ?? 'Unknown Session'
                    ];
                });

            Log::info('Found ' . $students->count() . ' students');

            return response()->json([
                'students' => $students,
                'count' => $students->count()
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Database Query Error: ' . $e->getMessage());
            Log::error('SQL: ' . $e->getSql());
            return response()->json([
                'error' => 'Database query failed: ' . $e->getMessage(),
                'details' => config('app.debug') ? $e->getTrace() : 'Enable debug mode for more details'
            ], 500);
        } catch (\Exception $e) {
            Log::error('Error fetching students: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'error' => 'Error fetching students: ' . $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ], 500);
        }
    }

    /**
     * Process and view a student's transcript
     */
    /**
     * Process and view a student's transcript
     */
    public function viewTranscript(Request $request)
    {
        $matric = $request->input('matric');
        $sessionAdmin = $request->input('sessionadmin');

        if (!$matric || !$sessionAdmin) {
            return back()->with('error', 'Matric number and session are required');
        }

        try {
            // Get student data from StudentRecord model
            $student = StudentRecord::where('matric', $matric)->first();

            if (!$student) {
                return back()->with('error', 'Student record not found');
            }

            // Get results from Result2023 model
            $results = Result2023::where('matric', $matric)
                ->where('yr_of_entry', $sessionAdmin)
                ->with(['course', 'department', 'faculty']) // Load relationships
                ->get();

            $rendition = DB::table('rendition')
                ->where('dept', $results->first()->dept ?? null)
                ->where('degree', $results->first()->degree ?? null)
                ->where('specialization', $results->first()->field ?? null)
                ->first();

            $zmain_app = DB::table('zmain_app')
                ->where('user_id', $student->user_id ?? null)
                ->first();

            if ($zmain_app->sex == 2) {
                $gender = 'Male';
            } else if ($zmain_app->sex == 1) {
                $gender = 'Female';
            } else {
                $gender = 'Not Specified';
            }


            $programCgpa = DB::table('programme_cgpa')
                ->where('degree_id', $results->first()->degree ?? null)
                ->first();
            $program = $programCgpa->type; // Default to Academics if not provided



            $academicData = $this->calculateAcademicMetrics($results, $program);

            // Get additional student info from StudentRecord if needed
            $studentRecord = [
                'biodata' => $student,
                'gender' => $gender,
                'results' => $results,
                'program' => $program,
                'cgpa' => $academicData['cgpa'], // Will be actual CGPA or "-" based on conditions
                'rawCgpa' => $academicData['rawCgpa'], // Actual calculated CGPA for reference
                'result' => $academicData['result'], // "PASS" or "-"
                'remark' => $academicData['remark'], // "Ph.D", "M.Phil", "NG", etc.
                'meetsPassConditions' => $academicData['meetsPassConditions'],
                'degreeAwarded' => $rendition->naration ?? 'Not Specified',
                'totalUnits' => $academicData['totalUnits'],
                'totalGradePoints' => $academicData['totalGradePoints'],
                'unitsPassedCore' => $academicData['unitsPassedCore'],
                'unitsPassedRequired' => $academicData['unitsPassedRequired'],
                'totalUnitsPassed' => $academicData['totalUnitsPassed'],
                'coreUnitsToPass' => $academicData['coreUnitsToPass'],
                'requiredUnitsToPass' => $academicData['requiredUnitsToPass']
            ];

            return view('admin.view_transcript', $studentRecord);
        } catch (\Exception $e) {
            return back()->with('error', 'Error processing transcript: ' . $e->getMessage());
        }
    }
    /**
     * Calculate CGPA and other academic metrics based on the PHP logic
     */
    private function calculateAcademicMetrics($results, $program = 'Academics')
    {
        $totalUnits = 0;
        $totalGradePoints = 0;
        $unitsPassedCore = 0;
        $unitsPassedRequired = 0;
        $totalUnitsPassed = 0;
        $coreUnitsToPass = 0;
        $requiredUnitsToPass = 0;

        foreach ($results as $result) {
            $score = $result->score;
            $courseUnit = $result->cunit;
            $courseStatus = $result->cstatus; // Assuming 'C' = Core, 'R' = Required, 'E'/'EE' = Elective

            // Add to total units
            $totalUnits += $courseUnit;

            // Calculate points based on score (0-7 scale)
            $point = $this->getGradePoint($score);

            // Calculate grade points (point × unit)
            $gradePoints = $point * $courseUnit;
            $totalGradePoints += $gradePoints;

            // Track units based on status
            if ($courseStatus === 'C') {
                $coreUnitsToPass += $courseUnit;
                if ($score >= 40) {
                    $unitsPassedCore += $courseUnit;
                }
            }

            if ($courseStatus === 'R') {
                $requiredUnitsToPass += $courseUnit;
                if ($score >= 30) {
                    $unitsPassedRequired += $courseUnit;
                }
            }

            // Calculate total units passed (TUP) - based on the original logic
            if (($courseStatus === 'C' && $score > 39) ||
                ($score > 29 && ($courseStatus === 'R' || $courseStatus === 'E' || $courseStatus === 'EE'))
            ) {
                $totalUnitsPassed += $courseUnit;
            }
        }

        // Calculate raw CGPA
        $rawCgpa = ($totalUnits > 0) ? round($totalGradePoints / $totalUnits, 2) : 0;

        // Determine if student meets pass conditions
        $meetsPassConditions = $this->checkPassConditions(
            $program,
            $totalUnitsPassed,
            $unitsPassedCore,
            $coreUnitsToPass,
            $unitsPassedRequired,
            $requiredUnitsToPass
        );

        // Determine result, remark and CGPA display based on conditions
        $resultData = $this->determineResult($program, $meetsPassConditions, $rawCgpa);

        $cgpa = $resultData['cgpa'];
        $result = $resultData['result'];
        $remark = $resultData['remark'];

        return [
            'cgpa' => $cgpa,
            'rawCgpa' => $rawCgpa, // Store raw CGPA for reference
            'result' => $result,
            'remark' => $remark,
            'meetsPassConditions' => $meetsPassConditions,
            'totalUnits' => $totalUnits,
            'totalGradePoints' => $totalGradePoints,
            'unitsPassedCore' => $unitsPassedCore,
            'unitsPassedRequired' => $unitsPassedRequired,
            'totalUnitsPassed' => $totalUnitsPassed,
            'coreUnitsToPass' => $coreUnitsToPass,
            'requiredUnitsToPass' => $requiredUnitsToPass,
            'passStatus' => $meetsPassConditions ? 'PASS' : 'FAIL'
        ];
    }

    /**
     * Check if student meets the pass conditions based on program type
     */
    private function checkPassConditions($program, $totalUnitsPassed, $unitsPassedCore, $coreUnitsToPass, $unitsPassedRequired, $requiredUnitsToPass)
    {
        $coreRequirementMet = $unitsPassedCore >= $coreUnitsToPass;
        $requiredRequirementMet = $unitsPassedRequired >= $requiredUnitsToPass;

        switch ($program) {
            case 'Academics':
                return ($totalUnitsPassed > 29) && $coreRequirementMet && $requiredRequirementMet;

            case 'Professional':
                return ($totalUnitsPassed > 44) && $coreRequirementMet && $requiredRequirementMet;

            case 'PGD':
                return ($totalUnitsPassed > 19) && $coreRequirementMet && $requiredRequirementMet;

            default:
                return false;
        }
    }

    /**
     * Determine result, remark and CGPA based on program and conditions
     */
    private function determineResult($program, $meetsPassConditions, $rawCgpa)
    {
        if ($meetsPassConditions) {
            // Student meets pass conditions
            if ($program == 'Academics') {
                if ($rawCgpa < 9.0 && $rawCgpa >= 5.0) {
                    return ['cgpa' => $rawCgpa, 'result' => 'PASS', 'remark' => 'Ph.D'];
                } elseif ($rawCgpa < 5.0 && $rawCgpa >= 4.0) {
                    return ['cgpa' => $rawCgpa, 'result' => 'PASS', 'remark' => 'M.Phil/Ph.D'];
                } elseif ($rawCgpa < 4.0 && $rawCgpa >= 3.0) {
                    return ['cgpa' => $rawCgpa, 'result' => 'PASS', 'remark' => 'M.Phil'];
                } elseif ($rawCgpa < 3.0 && $rawCgpa >= 1.0) {
                    return ['cgpa' => $rawCgpa, 'result' => 'PASS', 'remark' => 'TM'];
                }
            } elseif ($program == 'Professional') {
                return ['cgpa' => $rawCgpa, 'result' => 'PASS', 'remark' => 'PASS'];
            } elseif ($program == 'PGD') {
                return ['cgpa' => $rawCgpa, 'result' => 'PASS', 'remark' => 'PASS'];
            }
        } else {
            // Student doesn't meet pass conditions
            return ['cgpa' => '-', 'result' => '-', 'remark' => 'NG'];
        }

        // Fallback
        return ['cgpa' => '-', 'result' => '-', 'remark' => 'NG'];
    }

    /**
     * Get grade point based on score (following the original PHP logic)
     */
    private function getGradePoint($score)
    {
        if ($score <= 39) {
            return 0;
        } elseif ($score >= 40 && $score < 45) {
            return 1;
        } elseif ($score >= 45 && $score < 50) {
            return 2;
        } elseif ($score >= 50 && $score < 55) {
            return 3;
        } elseif ($score >= 55 && $score < 60) {
            return 4;
        } elseif ($score >= 60 && $score < 65) {
            return 5;
        } elseif ($score >= 65 && $score < 70) {
            return 6;
        } elseif ($score >= 70 && $score <= 100) {
            return 7;
        }

        return 0; // fallback
    }
}
