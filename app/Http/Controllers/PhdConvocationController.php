<?php
namespace App\Http\Controllers;

use App\Models\NewRecord;
use App\Models\PrevApp;
use App\Models\ResultOld;
use App\Models\DeptNew;
use App\Models\FacNew;
use App\Models\ThesisExaminer;
use App\Models\TransDetailsNew;
use Illuminate\Http\Request;

class PhdConvocationController extends Controller
{
    public function form()
    {
        return view('admin.phd_convocation_form');
    }

    public function fetchSessions(Request $request)
    {
        $matric = $request->query('matric');

        // Get sessions from ResultOld for this matric
        $sessions = ResultOld::where('matno', $matric)
            ->distinct()
            ->pluck('sec')
            ->toArray();

        return response()->json(['sessions' => $sessions]);
    }

    // public function find(Request $request)
    // {
    //     $request->validate([
    //         'matric'  => 'required|string|max:50',
    //         'session' => 'required|string|max:50',
    //     ]);

    //     $matric = trim($request->input('matric'));
    //     $sec = $request->input('session');

    //     // Get user_id from prev_app by matric (fac/dept/degree not present here per schema)
    //     $prev = PrevApp::where('matric', $matric)
    //         ->select('user_id', 'matric')
    //         ->first();

    //     if (! $prev) {
    //         return back()->with('error', 'Matric not found in prev_app');
    //     }

    //     // Ensure this user_id exists in thesis_examiner as candidate_id
    //     $examiner = ThesisExaminer::where('candidate_id', $prev->user_id)
    //         ->first();

    //     if (! $examiner) {
    //         return back()->with('error', 'Candidate not available for Ph.D Convocation processing');
    //     }

    //     // Attempt to gather biodata-like info for transcriptHigher page
    //     $student = (object) [
    //         'matric'       => $matric,
    //         'Othernames'   => null,
    //         'Surname'      => null,
    //         'sex'          => null,
    //         'sessionadmin' => null,
    //         'faculty'      => $examiner->faculty ?? null,
    //         'department'   => $examiner->department_id ?? null,
    //         'degree'       => 'Ph.D',
    //         'email'        => null,
    //     ];

    //     // Try pull name/sex from any existing TransDetailsNew for this matric
    //     $existingTrans = TransDetailsNew::where('matric', $matric)->latest('date_requested')->first();
    //     $existingNew   = NewRecord::where('id', $prev->user_id)->first();
    //     if ($existingTrans) {
    //         $student->Othernames   = $existingTrans->Othernames;
    //         $student->Surname      = $existingTrans->Surname;
    //         $student->sex          = $existingTrans->sex;
    //         $student->sessionadmin = $existingTrans->sessionadmin;
    //         $student->email        = $existingTrans->email;
    //     } else {
    //         $student->Othernames = $existingNew->Othernames;
    //         $student->Surname    = $existingNew->Surname;
    //         $student->sex        = $existingTrans->sex;

    //     }

    //     // Fallback to results to determine session admitted and compute degree narration/cgpa on final page

    //     $results = ResultOld::where('matno', $matric)
    //         ->where('sec', $sec)
    //         ->with('course')
    //         ->get();

    //     // Render the higher transcript edit page to capture award, degree awarded, thesis title, etc.
    //     $gender        = $student->sex ?? 'N/A';
    //     $degreeAwarded = $examiner->degree_awarded ?? null;
    //     $cgpa          = null;
    //     $thesisTitle   = null;
    //     $dateAward     = $examiner->award_year ?? null;

    //     return view('admin.transcriptHigherPhD', compact('student', 'results', 'gender', 'degreeAwarded', 'cgpa', 'thesisTitle', 'dateAward'));
    // }


public function find(Request $request)
{
    $request->validate([
        'matric' => 'required|string|max:50',
        'session' => 'required|string'
    ]);

    $matric = trim($request->input('matric'));
    $sec = $request->input('session') === 'NoResult' ? null : $request->input('session');

    $prev = PrevApp::where('matric', $matric)
        ->select('user_id', 'matric')
        ->first();

    if (!$prev) {
        return back()->with('error', 'Matric not found in prev_app');
    }

    $examiner = ThesisExaminer::where('candidate_id', $prev->user_id)->first();

    if (!$examiner) {
        return back()->with('error', 'Candidate not available for Ph.D Convocation processing');
    }

    $facultyName = FacNew::where('id', $examiner->faculty)
        ->value('faculty') ?? $examiner->faculty;

    $deptName = DeptNew::where('id', $examiner->department_id)
        ->value('department') ?? $examiner->department_id;

    $record = TransDetailsNew::where('matric', $matric)
        ->first() ?? NewRecord::where('id', $prev->user_id)->first();

    $fullName = $record ? ($record->Surname . ' ' . $record->Othernames) : 'N/A';
    
    $results = $sec ? ResultOld::where('matno', $matric)
        ->where('sec', $sec)
        ->with('course')
        ->get() : collect([]);

    $data = [
        'matric' => $matric,
        'name' => $fullName,
        'sec' => $sec,
        'faculty' => $facultyName,
        'department' => $deptName,
        'degree' => 'Ph.D',
        'gender' => $record->sex ?? 'N/A',
        'degreeAwarded' => $examiner->degree_awarded ?? null,
        'dateAward' => $examiner->expected_date ?? null,
        'thesisTitle' => $examiner->thesis_title ?? null,
        'results' => $sec ? $results->map(function($r) {
            return [
                'id' => $r->id,
                'course_id' => $r->course?->id ?? '',
                'code' => $r->code,
                'course_title' => $r->course?->title ?? '',
                'c_unit' => $r->course?->unit ?? '',
                'status' => $r->status,
                'score' => $r->score,
                'grade' => $r->grade ?? '',
            ];
        }) : [],
    ];

    $nameParts = array_pad(explode(' ', $fullName, 2), 2, '');

    return view('admin.transcriptHigherPhD', [
        'student' => (object)[
            'matric' => $data['matric'],
            'Surname' => $nameParts[0],
            'Othernames' => $nameParts[1],
            'sessionadmin' => $data['sec'],
            'faculty' => $data['faculty'],
            'department' => $data['department'],
            'degree' => $data['degree'],
        ],
        'results' => $data['results'],
        'gender' => $data['gender'],
        'degreeAwarded' => $data['degreeAwarded'],
        'thesisTitle' => $data['thesisTitle'],
        'dateAward' => $data['dateAward'],
    ]);
}

    public function submit(Request $request)
    {
        $request->validate([
            'matric'         => 'required|string|max:50',
            'surname'        => 'nullable|string|max:255',
            'othernames'     => 'nullable|string|max:255',
            'sex'            => 'nullable|string|max:10',
            'sessionadmin'   => 'nullable|string|max:20',
            'faculty'        => 'nullable|string|max:50',
            'department'     => 'nullable|string|max:50',
            'degree_awarded' => 'required|string|max:255',
            'cgpa'   => 'nullable|string|max:255',
            'award_date'     => 'required|string|max:255',
            'thesis_title'   => 'required|string|max:255',
        ]);

        if ($request->sessionadmin) {
            $normalizedSecAdmin = preg_replace('/\/(\d{2})$/', '/20$1', $request->sessionadmin);
        }

        // Resolve faculty/department/programme from thesis_examiner if missing
        $prev               = PrevApp::where('matric', $request->matric)->select('user_id')->first();
        $examiner           = $prev ? ThesisExaminer::where('candidate_id', $prev->user_id)->first() : null;
        $resolvedFaculty    = $request->faculty ?? ($examiner->faculty ?? null);
        $resolvedDepartment = $request->department ?? ($examiner->department_id ?? null);
        $resolvedProgramme  = $request->degree_awarded ?? ($examiner->degree_awarded ?? null);
        $resolvedArea       = $examiner->area_of_specialization ?? null;
        $resolvedAwardDate  = $request->award_date ?? ($examiner->award_year ?? null);

        // Create new trans_details_new record
        // $trans = TransDetailsNew::create([
        //     'matric'        => $request->matric,
        //     'Surname'       => $request->surname,
        //     'Othernames'    => $request->othernames,
        //     'maiden'        => '',
        //     'sessiongrad'   => null,
        //     'sex'           => $request->sex,
        //     'sessionadmin'  => $normalizedSecAdmin,
        //     'faculty'       => $resolvedFaculty,
        //     'department'    => $resolvedDepartment,
        //     'degree'        => 'Ph.D',
        //     'award'         => null, // CGPA/WA to be computed elsewhere if needed
        //     'programme'     => $resolvedProgramme,
        //     'dateAward'     => $resolvedAwardDate,
        //     'thesis_title'  => $request->thesis_title,
        //     'feildofinterest'=> $resolvedArea,
        //     'date_requested'=> now(),
        //     'status'        => 7,
        // ]);

        // Build data needed by view_transcript view similar to StudentsByDepartmentController
        $matric = $request->matric;

        if ($request->sessionadmin) {
            $results = ResultOld::where('matno', $matric)
                ->where('sec', $normalizedSecAdmin)
                ->with('course')
                ->get();
        } else {
            $results = collect([]);
        }

        $biodata = (object) [
            'matric'       => $request->matric,
            'Othernames'   => $request->othernames,
            'Surname'      => $request->surname,
            'faculty'      => $resolvedFaculty,
            'department'   => $resolvedDepartment,
            'degree'       => $request->degree_awarded,
            'thesis_title' => $request->thesis_title,
        ];

        $gender        = $request->sex ?? 'N/A';
        $degreeAwarded = $request->programme;
        $dateAward     = $request->award_date;

        // Compute CGPA/WA
        $program = 'Academics';
        //$metrics = $this->calculateAcademicMetrics($results, $program, $dataSource = 'old');
        $cgpa    = $request->cgpa;

        return view('admin.view_phd_transcript', compact('biodata', 'results', 'gender', 'degreeAwarded', 'dateAward', 'cgpa'));
    }

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
            if ($dataSource === '2023') {
                $score        = $result->score;
                $courseUnit   = $result->cunit;
                $courseStatus = $result->cstatus;
            } else {
                $score        = $result->score;
                $courseUnit   = $result->c_unit;
                $courseStatus = $result->status;
            }

            $totalUnits += $courseUnit;

            $point       = $this->getGradePoint($score);
            $gradePoints = $point * $courseUnit;
            $totalGradePoints += $gradePoints;

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

            if (($courseStatus === 'C' && $score > 39) ||
                ($score > 29 && ($courseStatus === 'R' || $courseStatus === 'E' || $courseStatus === 'EE'))
            ) {
                $totalUnitsPassed += $courseUnit;
            }
        }

        $rawCgpa = ($totalUnits > 0) ? round($totalGradePoints / $totalUnits, 2) : 0;

        $meetsPassConditions = $this->checkPassConditions(
            $program,
            $totalUnitsPassed,
            $unitsPassedCore,
            $coreUnitsToPass,
            $unitsPassedRequired,
            $requiredUnitsToPass
        );

        $resultData = $this->determineResult($program, $meetsPassConditions, $rawCgpa);

        return [
            'cgpa'                => $resultData['cgpa'],
            'rawCgpa'             => $rawCgpa,
            'result'              => $resultData['result'],
            'remark'              => $resultData['remark'],
            'meetsPassConditions' => $meetsPassConditions,
            'totalUnits'          => $totalUnits,
            'totalGradePoints'    => $totalGradePoints,
            'unitsPassedCore'     => $unitsPassedCore,
            'unitsPassedRequired' => $unitsPassedRequired,
            'totalUnitsPassed'    => $totalUnitsPassed,
            'coreUnitsToPass'     => $coreUnitsToPass,
            'requiredUnitsToPass' => $requiredUnitsToPass,
        ];
    }

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

    private function determineResult($program, $meetsPassConditions, $rawCgpa)
    {
        if ($meetsPassConditions) {
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
            return ['cgpa' => '-', 'result' => '-', 'remark' => 'NG'];
        }

        return ['cgpa' => '-', 'result' => '-', 'remark' => 'NG'];
    }
}
