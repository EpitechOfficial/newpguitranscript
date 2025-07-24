<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Carbon\Carbon;
use App\Models\Transinvoice;

class DeleteOldTransinvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transinvoices:delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete transinvoices older than 5 days';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $fiveDaysAgo = Carbon::now()->subDays(5);
        Transinvoice::where('yr', '<', $fiveDaysAgo)->delete();
        $this->info('Old transinvoices deleted successfully.');
    }
}
