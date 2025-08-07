<?php
// app/Imports/ResultOldImport.php
namespace App\Imports;

use App\Models\ResultOld;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterImport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ResultOldImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading, WithEvents
{
    public $duplicates = [];
    public $errors = [];
    public $successCount = 0;
    public $totalRows = 0;

    public function model(array $row)
    {
        $this->totalRows++;
        
        // Get matno once and reuse
        $matno = (string) ($row['matno'] ?? '');
        $code = (string) ($row['code'] ?? '');
        $status = (string) ($row['status'] ?? '');
        $score = (string) ($row['score'] ?? '');
        $sec = (string) ($row['sec'] ?? '');
        $dept = (string) ($row['dept'] ?? '');

        // Avoid processing if matno is empty
        if (empty($matno)) {
            $this->errors[] = "Row {$this->totalRows}: Matric number is empty";
            return null;
        }

        // Check for duplicate record
        $duplicate = ResultOld::where('matno', $matno)
            ->where('code', $code)
            ->where('status', $status)
            ->where('score', $score)
            ->where('sec', $sec)
            ->where('dept', $dept)
            ->first();

        if ($duplicate) {
            $this->duplicates[] = [
                'row' => $this->totalRows,
                'matno' => $matno,
                'code' => $code,
                'status' => $status,
                'score' => $score,
                'sec' => $sec,
                'dept' => $dept
            ];
            return null; // Skip this record
        }

        try {
            // Update trans_details_new table
            DB::table('trans_details_new')
                ->where('matric', $matno)
                ->update([
                    'status' => 2,
                    'sessionadmin' => $sec
                ]);

            // Update trans_invoice table
            DB::table('transinvoice')
                ->where('appno', $matno)
                ->update(['cheque' => 2]);

            $this->successCount++;
            
            // Save to result_olds table
            return new ResultOld([
                'matno'  => $matno,
                'code'   => $code,
                'status' => $status,
                'score'  => $score,
                'wa'     => $row['wa'] ?? null,
                'sec'    => $sec,
                'dept'   => $dept,
            ]);
        } catch (\Exception $e) {
            $this->errors[] = "Row {$this->totalRows}: Error processing record - " . $e->getMessage();
            Log::error("ResultOldImport error at row {$this->totalRows}: " . $e->getMessage());
            return null;
        }
    }

    public function batchSize(): int
    {
        return 1000;
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function registerEvents(): array
    {
        return [
            AfterImport::class => function(AfterImport $event) {
                // Store the results in session for display
                session([
                    'import_results' => [
                        'total_rows' => $this->totalRows,
                        'success_count' => $this->successCount,
                        'duplicates' => $this->duplicates,
                        'errors' => $this->errors
                    ]
                ]);
            },
        ];
    }
}

?>
