<?php
// app/Imports/ResultOldImport.php
namespace App\Imports;

use App\Models\ResultOld;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\DB;

class ResultOldImport implements ToModel, WithHeadingRow
{
   public function model(array $row)
{
    // Get matno once and reuse
    $matno = (string) ($row['matno'] ?? '');

    // Avoid update if matno is empty
    if (empty($matno)) {
        return null;
    }

    // Update trans_details_new table
DB::table('trans_details_new')
    ->where('matric', $matno)
    ->update([
        'status' => 2,
        'sessionadmin' => $row['sec']
    ]);
    // Update trans_invoice table
    DB::table('transinvoice')
        ->where('appno', $matno)
        ->update(['cheque' => 2]);

    // Save to result_olds table
    return new ResultOld([
        'matno'  => $matno,
        'code'   => $row['code'] ?? null,
        'status' => $row['status'] ?? null,
        'score'  => $row['score'] ?? null,
        'wa'     => $row['wa'] ?? null,
        'sec'    => $row['sec'] ?? null,
        'dept'   => $row['dept'] ?? null,
    ]);
}

}


?>
