<?php
namespace App\Http\Controllers;

use App\Models\DeptNew;
use App\Models\Result2023;
use App\Models\Result2018;
use App\Models\StudentRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StudentsByDepartmentController extends Controller
{
    /**
     * Show the students by department page
     */
    public function index()
    {
        dd('This runs');
        if (! config('features.mass_transcript')) {
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

            if (! $departmentId) {
                return response()->json(['error' => 'Department is required'], 400);
            }

            // Add logging to debug the query
            Log::info('Fetching students for department: ' . $departmentId);

            // Get department name for Result2018 query
            $department = DeptNew::find($departmentId);
            if (!$department) {
                return response()->json(['error' => 'Department not found'], 404);
            }

            // Fetch students from Result2023 (existing logic)
            $query2023 = Result2023::where('dept', $departmentId)
                ->where('status', 1)
                ->where('session_of_grad','!=' , null)
                ->where('resulttype','!=' , null);

            // Log the SQL query for debugging
            Log::info('SQL Query 2023: ' . $query2023->toSql());
            Log::info('Query Bindings 2023: ' . json_encode($query2023->getBindings()));

            $students2023 = $query2023
                ->with(['department', 'studentRecord'])
                ->select('matric', 'dept', 'yr_of_entry as session')
                ->orderBy('matric')
                ->distinct()
                ->get()
                ->map(function ($student) {
                    $name              = 'Record not in Student Database';
                    $completenessScore = 0;

                    // Handle name display logic
                    if ($student->studentRecord && ! empty(trim($student->studentRecord->name ?? ''))) {
                        $name = trim($student->studentRecord->name);
                    } elseif (isset($student->name) && ! empty(trim($student->name))) {
                        $name = trim($student->name);
                    } else {
                        $name = 'Name not found';
                    }

                    // Calculate completeness score for ALL records that have studentRecord
                    if ($student->studentRecord) {
                        $record = $student->studentRecord;
                        $completenessScore += (! is_null($record->name) && trim($record->name) !== '') ? 1 : 0;
                        $completenessScore += (! is_null($record->user_id) && trim($record->user_id) !== '') ? 1 : 0;
                        $completenessScore += (! is_null($record->dept) && trim($record->dept) !== '') ? 1 : 0;
                        $completenessScore += (! is_null($record->specialization) && trim($record->specialization) !== '') ? 1 : 0;
                        $completenessScore += (! is_null($record->specialization2) && trim($record->specialization2) !== '') ? 1 : 0;
                    }
                    // If no studentRecord exists at all, completeness score remains 0

                    return [
                        'matric'             => $student->matric,
                        'name'               => $name,
                        'department'         => $student->department->department ?? 'Unknown Department',
                        'session'            => $student->session ?? 'Unknown Session',
                        'completeness_score' => $completenessScore,
                        'source'             => '2023'
                    ];
                });

            // Fetch students from Result2018 using department text
            $query2018 = DB::table('results')
                ->join('notify', 'results.stud_id', '=', 'notify.matric')
                ->where('results.dept', $department->department)
                ->orWhere('results.dept', $department->name ?? $department->department);

            Log::info('SQL Query 2018: ' . $query2018->toSql());
            Log::info('Query Bindings 2018: ' . json_encode($query2018->getBindings()));

            // Debug: Let's see what we get from the basic join first
            $debugQuery = DB::table('results')
                ->join('notify', 'results.stud_id', '=', 'notify.matric')
                ->where('results.dept', $department->department)
                ->select('results.stud_id', 'results.dept', 'results.effectivedate', 'notify.student_record')
                ->limit(5)
                ->get();
            
            Log::info('Debug - Basic join results: ' . json_encode($debugQuery));

            // Debug: Check what's in results table for this department
            $debugResults = DB::table('results')
                ->where('dept', $department->department)
                ->select('stud_id', 'dept', 'effectivedate')
                ->limit(5)
                ->get();
            
            Log::info('Debug - Results table for department ' . $department->department . ': ' . json_encode($debugResults));

            // Debug: Check all unique department values in results table
            $debugAllDepts = DB::table('results')
                ->select('dept')
                ->distinct()
                ->limit(10)
                ->get();
            
            Log::info('Debug - All departments in results table: ' . json_encode($debugAllDepts));

            // Debug: Check what's in notify table
            $debugNotify = DB::table('notify')
                ->select('matric', 'student_record')
                ->limit(5)
                ->get();
            
            Log::info('Debug - Notify table sample: ' . json_encode($debugNotify));

            $students2018 = $query2018
                ->select('results.stud_id as matric', 'results.dept', 'results.sec2 as session', 'results.effectivedate')
                ->orderBy('results.stud_id')
                ->distinct()
                ->get()
                ->map(function ($student) {
                    $name = 'Name not found';
                    
                    // Get name from biodata relationship using the matric (stud_id)
                    $biodata = DB::table('biodata')->where('matric', $student->matric)->first();
                    if ($biodata) {
                        $name = $biodata->surname . ' ' . $biodata->othername;
                    }

                    return [
                        'matric'             => $student->matric,
                        'name'               => $name,
                        'department'         => $student->dept ?? 'Unknown Department',
                        'session'            => $student->session ?? 'Unknown Session',
                        'completeness_score' => 0, // Default for 2018 records
                        'source'             => '2018'
                    ];
                });

            // Get existing matric numbers from 2023 to avoid duplicates
            $existingMatrics = $students2023->pluck('matric')->toArray();

            // Filter out 2018 students that already exist in 2023
            $uniqueStudents2018 = $students2018->filter(function ($student) use ($existingMatrics) {
                return !in_array($student['matric'], $existingMatrics);
            });

            // Combine both collections
            $allStudents = $students2023->concat($uniqueStudents2018);

            // Sort by matric number
            $sortedStudents = $allStudents->sortBy('matric')->values();

            Log::info('Found ' . $students2023->count() . ' students from 2023');
            Log::info('Found ' . $students2018->count() . ' students from 2018');
            Log::info('Total unique students: ' . $sortedStudents->count());

            return response()->json([
                'students' => $sortedStudents,
                'count'    => $sortedStudents->count(),
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Database Query Error: ' . $e->getMessage());
            Log::error('SQL: ' . $e->getSql());
            return response()->json([
                'error'   => 'Database query failed: ' . $e->getMessage(),
                'details' => config('app.debug') ? $e->getTrace() : 'Enable debug mode for more details',
            ], 500);
        } catch (\Exception $e) {
            Log::error('Error fetching students: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'error' => 'Error fetching students: ' . $e->getMessage(),
                'line'  => $e->getLine(),
                'file'  => $e->getFile(),
            ], 500);
        }
    }

    /**
     * Process and view a student's transcript
     */
    public function viewTranscript(Request $request)
    {
        $matric       = $request->input('matric');
        $sessionAdmin = $request->input('sessionadmin');

        if (! $matric || ! $sessionAdmin) {
            return back()->with('error', 'Matric number and session are required');
        }

        try {
            // First, try to get student from StudentRecord (for 2023 students)
            $student = StudentRecord::where('matric', $matric)
                ->whereNotNull('name')
                ->where('name', '!=', '')
                ->first();

            // Fallback if no record with name exists
            if (! $student) {
                $student = StudentRecord::where('matric', $matric)->first();
            }

            // If still no student in StudentRecord, try Biodata (for 2018 students)
            if (! $student) {
                $biodata = DB::table('biodata')->where('matric', $matric)->first();
                if ($biodata) {
                    // Create a student object from biodata using the actual table structure
                    $student = (object) [
                        'matric' => $biodata->matric,
                        'name' => trim($biodata->surname . ' ' . $biodata->othername),
                        'user_id' => $biodata->user_id,
                        'dept' => $biodata->department,
                        'dept_id' => $biodata->dept_id,
                        'specialization' => $biodata->specialization,
                        'field_id' => $biodata->field_id,
                        'faculty' => $biodata->faculty,
                        'fac_id' => $biodata->fac_id,
                        'degree' => $biodata->degree,
                        'degree_id' => $biodata->degree_id,
                        'yr_of_entry' => $biodata->yr_of_entry,
                        'sex' => $biodata->sex,
                        'cgpa' => $biodata->cgpa,
                        'degreeawarded' => $biodata->degreeawarded
                    ];
                    
                    Log::info("Created student object from biodata for matric: $matric");
                    Log::info("Biodata fields - Name: {$student->name}, Dept: {$student->dept}, Degree: {$student->degree}, Sex: {$student->sex}");
                    Log::info("Biodata ID fields - dept_id: " . ($biodata->dept_id ?? 'NULL') . 
                             ", degree_id: " . ($biodata->degree_id ?? 'NULL') . 
                             ", field_id: " . ($biodata->field_id ?? 'NULL'));
                }
            }

            if (! $student) {
                return back()->with('error', 'Student record not found');
            }

            // Determine data source and fetch results accordingly
            $results = null;
            $dataSource = null;

            // Try Result2023 first
            $results2023 = Result2023::where('matric', $matric)
                ->where('yr_of_entry', $sessionAdmin)
                ->where('session_of_grad','!=' , null)
                ->where('resulttype','!=' , null)
                ->with(['course', 'department', 'faculty'])
                ->get();

            Log::info("Result2023 query for matric: $matric, session: $sessionAdmin, count: " . $results2023->count());

            if ($results2023->count() > 0) {
                $results = $results2023;
                $dataSource = '2023';
                Log::info("Using Result2023 data for student: $matric, session: $sessionAdmin");
            } else {
                // Try Result2018 if no 2023 results found
                $results2018 = Result2018::with(['biodata', 'course'])
                    ->where('stud_id', $matric)
                    ->where('sec2', $sessionAdmin)
                    ->whereNotNull('stud_id')
                    ->where('stud_id', '!=', '')
                    ->get();

                Log::info("Result2018 query for stud_id: $matric, sec: $sessionAdmin, count: " . $results2018->count());

                if ($results2018->count() > 0) {
                    $results = $results2018;
                    $dataSource = '2018';
                    Log::info("Using Result2018 data for student: $matric, session: $sessionAdmin");
                    
                    // Log sample result data for debugging
                    $sampleResult = $results2018->first();
                    Log::info("Sample Result2018 data: " . json_encode($sampleResult));
                    
                    // Log course relationship data if available
                    if ($sampleResult->course) {
                        Log::info("Course relationship loaded for Result2018: " . json_encode($sampleResult->course));
                    } else {
                        Log::warning("Course relationship not loaded for Result2018 student: $matric");
                    }
                    
                    // Validate that required fields exist for academic calculations
                    $requiredFields = ['score', 'c_unit', 'status'];
                    $missingFields = [];
                    foreach ($requiredFields as $field) {
                        if (!isset($sampleResult->$field)) {
                            $missingFields[] = $field;
                        }
                    }
                    
                    if (!empty($missingFields)) {
                        Log::warning("Missing required fields in Result2018: " . implode(', ', $missingFields));
                    }
                }
            }

            if (!$results || $results->count() == 0) {
                return back()->with('error', 'No results found for this student and session');
            }

            // Get rendition data based on data source
            $rendition = null;
            if ($dataSource === '2023') {
                $rendition = DB::table('rendition')
                    ->where('dept', $results->first()->dept ?? null)
                    ->where('degree', $results->first()->degree ?? null)
                    ->where('specialization', $results->first()->field ?? null)
                    ->first();
            } else {
                // For 2018, use biodata ID fields for department and specialization
                // If IDs are missing, look them up from text fields
                $deptId = $student->dept_id;
                $degreeId = $student->degree_id;
                $fieldId = $student->field_id;
                
                // Log the available fields for debugging
                Log::info("Biodata fields for rendition lookup - dept_id: " . ($deptId ?? 'NULL') . 
                         ", degree_id: " . ($degreeId ?? 'NULL') . 
                         ", field_id: " . ($fieldId ?? 'NULL'));
                
                // Look up missing IDs from text fields
                if (!$deptId && $student->dept) {
                    $deptLookup = DB::table('dept_new')->where('department', $student->dept)->first();
                    $deptId = $deptLookup->id ?? null;
                    Log::info("Looked up dept_id for '{$student->dept}': " . ($deptId ?? 'NOT_FOUND'));
                }
                
                if (!$degreeId && $student->degree) {
                    $degreeLookup = DB::table('degree_new')->where('degree', $student->degree)->first();
                    $degreeId = $degreeLookup->id ?? null;
                    Log::info("Looked up degree_id for '{$student->degree}': " . ($degreeId ?? 'NOT_FOUND'));
                }
                
                // field_id should be numeric, but if it's missing or contains text, use specialization text to look up
                if ((!$fieldId || !is_numeric($fieldId)) && $student->specialization) {
                    $fieldLookup = DB::table('field_new')->where('field_title', $student->specialization)->first();
                    $fieldId = $fieldLookup->id ?? null;
                    Log::info("Looked up field_id for '{$student->specialization}': " . ($fieldId ?? 'NOT_FOUND'));
                }
                
                // Log final resolved IDs before rendition lookup
                Log::info("Final resolved IDs - dept_id: $deptId, degree_id: $degreeId, field_id: " . ($fieldId ?? 'NULL'));
                
                // Now use the resolved IDs for rendition lookup
                $rendition = DB::table('rendition')
                    ->where('dept', $deptId)
                    ->where('degree', $degreeId)
                    ->where('specialization', $fieldId)
                    ->first();
                
                if (!$rendition) {
                    Log::warning("Rendition not found for 2018 student: $matric after ID resolution");
                } else {
                    Log::info("Rendition found for 2018 student: $matric using resolved IDs");
                }
            }

            // Get gender information
            $gender = 'Not Specified';
            if ($dataSource === '2023' && $student->user_id) {
                $zmain_app = DB::table('zmain_app')
                    ->where('user_id', $student->user_id)
                    ->first();

                if ($zmain_app) {
                    if ($zmain_app->sex == 2) {
                        $gender = 'Male';
                    } else if ($zmain_app->sex == 1) {
                        $gender = 'Female';
                    }
                }
            } elseif ($dataSource === '2018' && isset($student->sex)) {
                // Use sex field from biodata for 2018 students
                $sexValue = strtolower(trim($student->sex));
                if ($sexValue === 'male' || $sexValue === 'm') {
                    $gender = 'Male';
                } elseif ($sexValue === 'female' || $sexValue === 'f') {
                    $gender = 'Female';
                } else {
                    $gender = 'Not Specified';
                }
                
                Log::info("Gender from biodata for 2018 student: $matric, sex: {$student->sex}, determined: $gender");
            }

            // Get program information
            $program = 'Academics'; // Default
            if ($dataSource === '2023') {
                $programCgpa = DB::table('programme_cgpa')
                    ->where('degree_id', $results->first()->degree ?? null)
                    ->first();
                if ($programCgpa) {
                    $program = $programCgpa->type;
                }
            } elseif ($dataSource === '2018' && isset($student->degree)) {
                // Try to determine program from degree in biodata
                $degree = strtolower(trim($student->degree));
                if (strpos($degree, 'phd') !== false || strpos($degree, 'doctor') !== false) {
                    $program = 'Academics';
                } elseif (strpos($degree, 'm.phil') !== false || strpos($degree, 'master') !== false) {
                    $program = 'Academics';
                } elseif (strpos($degree, 'pgd') !== false || strpos($degree, 'diploma') !== false) {
                    $program = 'PGD';
                } else {
                    $program = 'Academics'; // Default fallback
                }
                
                Log::info("Program determined from biodata degree for 2018 student: $matric, degree: {$student->degree}, program: $program");
            }

            // Calculate academic metrics
            $academicData = $this->calculateAcademicMetrics($results, $program, $dataSource);

            // Prepare student record data
            $studentRecord = [
                'biodata'             => $student,
                'gender'              => $gender,
                'results'             => $results,
                'program'             => $program,
                'dataSource'          => $dataSource,
                'cgpa'                => $academicData['cgpa'],
                'rawCgpa'             => $academicData['rawCgpa'],
                'result'              => $academicData['result'],
                'remark'              => $academicData['remark'],
                'meetsPassConditions' => $academicData['meetsPassConditions'],
                'degreeAwarded'       => $rendition->naration ?? 'Not Specified',
                'totalUnits'          => $academicData['totalUnits'],
                'totalGradePoints'    => $academicData['totalGradePoints'],
                'unitsPassedCore'     => $academicData['unitsPassedCore'],
                'unitsPassedRequired' => $academicData['unitsPassedRequired'],
                'totalUnitsPassed'    => $academicData['totalUnitsPassed'],
                'coreUnitsToPass'     => $academicData['coreUnitsToPass'],
                'requiredUnitsToPass' => $academicData['requiredUnitsToPass'],
            ];

            return view('admin.view_transcript', $studentRecord);
        } catch (\Exception $e) {
            Log::error('Error in viewTranscript: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return back()->with('error', 'Error processing transcript: ' . $e->getMessage());
        }
    }
    /**
     * Calculate CGPA and other academic metrics based on the PHP logic
     */
    private function calculateAcademicMetrics($results, $program = 'Academics', $dataSource = '2023')
    {
        $totalUnits          = 0;
        $totalGradePoints    = 0;
        $unitsPassedCore     = 0;
        $unitsPassedRequired = 0;
        $totalUnitsPassed    = 0;
        $coreUnitsToPass     = 0;
        $requiredUnitsToPass = 0;

        foreach ($results as $result) {
            // Handle different field names based on data source
            if ($dataSource === '2023') {
                $score        = $result->score;
                $courseUnit   = $result->cunit;
                $courseStatus = $result->cstatus; // Assuming 'C' = Core, 'R' = Required, 'E'/'EE' = Elective
            } else {
                // Result2018 fields
                $score        = $result->score;
                $courseUnit   = $result->c_unit;
                $courseStatus = $result->status; // Check if this field exists and has the same values
            }

            // Log field values for debugging (only for first few records)
            if ($totalUnits < 3) {
                Log::info("Processing result - DataSource: $dataSource, Score: $score, Unit: $courseUnit, Status: $courseStatus");
            }

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

        $cgpa   = $resultData['cgpa'];
        $result = $resultData['result'];
        $remark = $resultData['remark'];

        return [
            'cgpa'                => $cgpa,
            'rawCgpa'             => $rawCgpa, // Store raw CGPA for reference
            'result'              => $result,
            'remark'              => $remark,
            'meetsPassConditions' => $meetsPassConditions,
            'totalUnits'          => $totalUnits,
            'totalGradePoints'    => $totalGradePoints,
            'unitsPassedCore'     => $unitsPassedCore,
            'unitsPassedRequired' => $unitsPassedRequired,
            'totalUnitsPassed'    => $totalUnitsPassed,
            'coreUnitsToPass'     => $coreUnitsToPass,
            'requiredUnitsToPass' => $requiredUnitsToPass,
            'passStatus'          => $meetsPassConditions ? 'PASS' : 'FAIL',
        ];
    }

    /**
     * Check if student meets the pass conditions based on program type
     */
    private function checkPassConditions($program, $totalUnitsPassed, $unitsPassedCore, $coreUnitsToPass, $unitsPassedRequired, $requiredUnitsToPass)
    {
        $coreRequirementMet     = $unitsPassedCore >= $coreUnitsToPass;
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
