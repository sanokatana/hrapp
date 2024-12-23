<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\DashboardController;

class SendDailyReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daily:sendreport';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send daily attendance report at 9 AM WIB';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $controller = new DashboardController();
        $controller->sendDailyReport();

        $this->info('Daily report sent successfully!');
        return 0;
    }
}
