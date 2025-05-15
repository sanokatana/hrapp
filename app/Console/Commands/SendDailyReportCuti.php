<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\LaporanController;

class SendDailyReportCuti extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daily:cutireport';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check cuti requests and return unused days to balance';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $controller = new LaporanController();
        $controller->sendDailyReportCuti();

        $this->info('Daily cuti adjustment report sent successfully!');
        return 0;
    }
}
