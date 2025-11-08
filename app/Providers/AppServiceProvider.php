<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use PDO;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (config('app.env') === 'local') {
            DB::enableQueryLog();
        }

        // Ensure PDO uses native prepared statements on the default connection
        DB::connection()->getPdo()->setAttribute(
            PDO::ATTR_EMULATE_PREPARES,
            false
        );
    }
}
