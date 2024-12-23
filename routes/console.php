<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\CutiController;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('daily:sendreport', function () {
    // Access the route/controller method logic
    $controller = app(\App\Http\Controllers\LaporanController::class);
    $controller->sendDailyReport();

    $this->info('Daily report has been sent successfully!');
})->purpose('Send daily attendance report at 9 AM WIB');

