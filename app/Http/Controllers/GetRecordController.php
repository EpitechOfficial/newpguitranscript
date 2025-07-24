<?php
namespace App\Http\Controllers;

use App\Models\FieldofInterest5;
use App\Models\DeptNew;
use App\Models\DegreeNew;
use App\Models\FieldNew;
use Illuminate\Http\Request;

class GetRecordController extends Controller
{
    public function getDepartments($facultyId)
    {
        $departmentIds = FieldofInterest5::where('fac', $facultyId)->distinct()->pluck('dept');
        $departments = DeptNew::whereIn('id', $departmentIds)->orderBy('department', 'asc')->get(['id', 'department']);

        return response()->json($departments);
    }

    public function getDegrees($departmentId)
    {
        $degreeIds = FieldofInterest5::where('dept', $departmentId)->distinct()->pluck('degree');
        $degrees = DegreeNew::whereIn('id', $degreeIds)->orderBy('degree', 'asc')->get(['id', 'degree']);

        return response()->json($degrees);
    }

    public function getSpecializations($degreeId, $departmentId)
    {
        $specializationIds = FieldofInterest5::where('degree', $degreeId)
                                            ->where('dept', $departmentId)
                                            ->distinct()
                                            ->pluck('field');
                                            
        $specializations = FieldNew::whereIn('id', $specializationIds)->orderBy('field_title', 'asc')->get(['id', 'field_title']);

        return response()->json($specializations);
    }
}
