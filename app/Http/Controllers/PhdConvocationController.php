<?php
namespace App\Http\Controllers;

use App\Models\PrevApp;
use App\Models\Result2018;
use App\Models\Result2023;
use App\Models\ThesisExaminer;
use App\Models\TransDetailsNew;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PhdConvocationController extends Controller
{
    public function form()
    {
        return view('admin.phd_convocation_form');
    }

    public function find(Request $request)
    {
        $request->validate([
            'matric' => 'required|string|max:50',
        ]);

        $matric = trim($request->input('matric'));

        // Get user_id from prev_app by matric (fac/dept/degree not present here per schema)
        $prev = PrevApp::where('matric', $matric)
            ->select('user_id', 'matric')
            ->first();

        if (! $prev) {
            return back()->with('error', 'Matric not found in prev_app');
        }

        // Ensure this user_id exists in thesis_examiner as candidate_id
        $examiner = ThesisExaminer::where('candidate_id', $prev->user_id)
            ->first();

        if (! $examiner) {
            return back()->with('error', 'Candidate not available for Ph.D Convocation processing');
        }

        // Attempt to gather biodata-like info for transcriptHigher page
        $student = (object) [
            'matric'      => $matric,
            'Othernames'  => null,
            'Surname'     => null,
            'sex'         => null,
            'sessionadmin'=> null,
            'faculty'     => $examiner->faculty ?? null,
            'department'  => $examiner->department_id ?? null,
            'degree'      => 'Ph.D',
            'email'       => null,
        ];

        // Try pull name/sex from any existing TransDetailsNew for this matric
        $existingTrans = TransDetailsNew::where('matric', $matric)->latest('date_requested')->first();
        if ($existingTrans) {
            $student->Othernames   = $existingTrans->Othernames;
            $student->Surname      = $existingTrans->Surname;
            $student->sex          = $existingTrans->sex;
            $student->sessionadmin = $existingTrans->sessionadmin;
            $student->email        = $existingTrans->email;
        }

        // Fallback to results to determine session admitted and compute degree narration/cgpa on final page
        $results2023 = Result2023::with(['course', 'department', 'faculty'])
            ->where('matric', $matric)
            ->get();

        $results2018 = collect();
        if ($results2023->isEmpty()) {
            $results2018 = Result2018::with('course')
                ->where('stud_id', $matric)
                ->get();
        }

        // Render the higher transcript edit page to capture award, degree awarded, thesis title, etc.
        $gender      = $student->sex ?? 'N/A';
        $results     = $results2023->isNotEmpty() ? $results2023 : $results2018;
        $degreeAwarded = $examiner->degree_awarded ?? null;
        $cgpa = null;
        $thesisTitle = null;
        $dateAward = $examiner->award_year ?? null;

        return view('admin.transcriptHigherPhD', compact('student', 'results', 'gender', 'degreeAwarded', 'cgpa', 'thesisTitle', 'dateAward'));
    }

    public function submit(Request $request)
    {
        $request->validate([
            'matric'       => 'required|string|max:50',
            'surname'      => 'nullable|string|max:255',
            'othernames'   => 'nullable|string|max:255',
            'sex'          => 'nullable|string|max:10',
            'sessionadmin' => 'required|string|max:20',
            'faculty'      => 'nullable|string|max:50',
            'department'   => 'nullable|string|max:50',
            'degree_awarded' => 'required|string|max:255',
            'award_date'   => 'required|string|max:255',
            'thesis_title' => 'required|string|max:255',
        ]);

        $normalizedSecAdmin = preg_replace('/\/(\d{2})$/', '/20$1', $request->sessionadmin);

        // Resolve faculty/department/programme from thesis_examiner if missing
        $prev = PrevApp::where('matric', $request->matric)->select('user_id')->first();
        $examiner = $prev ? ThesisExaminer::where('candidate_id', $prev->user_id)->first() : null;
        $resolvedFaculty    = $request->faculty ?? ($examiner->faculty ?? null);
        $resolvedDepartment = $request->department ?? ($examiner->department_id ?? null);
        $resolvedProgramme  = $request->degree_awarded ?? ($examiner->degree_awarded ?? null);
        $resolvedArea       = $examiner->area_of_specialization ?? null;
        $resolvedAwardDate  = $request->award_date ?? ($examiner->award_year ?? null);

        // Create new trans_details_new record
        $trans = TransDetailsNew::create([
            'matric'        => $request->matric,
            'Surname'       => $request->surname,
            'Othernames'    => $request->othernames,
            'sex'           => $request->sex,
            'sessionadmin'  => $normalizedSecAdmin,
            'faculty'       => $resolvedFaculty,
            'department'    => $resolvedDepartment,
            'degree'        => 'Ph.D',
            'award'         => null, // CGPA/WA to be computed elsewhere if needed
            'programme'     => $resolvedProgramme,
            'dateAward'     => $resolvedAwardDate,
            'thesis_title'  => $request->thesis_title,
            'feildofinterest'=> $resolvedArea,
            'date_requested'=> now(),
            'status'        => 7,
        ]);

        // Build data needed by view_transcript view similar to StudentsByDepartmentController
        $matric = $request->matric;

        $results2023 = Result2023::where('matric', $matric)
            ->where('yr_of_entry', $normalizedSecAdmin)
            ->where('session_of_grad', '!=', null)
            ->where('resulttype', '!=', null)
            ->with(['course', 'department', 'faculty'])
            ->get();

        if ($results2023->isEmpty()) {
            $results2018 = Result2018::with('course')
                ->where('stud_id', $matric)
                ->where('sec2', $normalizedSecAdmin)
                ->get();
            $results = $results2018;
            $dataSource = '2018';
        } else {
            $results = $results2023;
            $dataSource = '2023';
        }

        $biodata = (object) [
            'matric'       => $trans->matric,
            'Othernames'   => $trans->Othernames,
            'Surname'      => $trans->Surname,
            'sessionadmin' => $trans->sessionadmin,
            'faculty'      => $trans->faculty,
            'department'   => $trans->department,
        ];

        $gender        = $trans->sex ?? 'N/A';
        $degreeAwarded = $trans->programme;
        $dateAward     = $trans->dateAward;

        // Compute CGPA/WA
        $program = 'Academics';
        $metrics = $this->calculateAcademicMetrics($results, $program, $dataSource);
        $cgpa    = $metrics['cgpa'];

        return view('admin.view_transcript', compact('biodata', 'results', 'gender', 'degreeAwarded', 'dateAward', 'cgpa'));
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

            $point = $this->getGradePoint($score);
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

