<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\LaporanController;

class SendDailyReportPengajuan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daily:pengajuanreport';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check and adjust leave requests for employees who clocked in';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $controller = new LaporanController();
        $controller->sendDailyReportPengajuan();

        $this->info('Daily pengajuan adjustment report sent successfully!');
        return 0;
    }
}
