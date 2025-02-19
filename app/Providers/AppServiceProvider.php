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
            DB::connection('mysql2')->enableQueryLog();
        }

        // Add query time logging in local environment
        DB::listen(function ($query) {
            if ($query->time > 100) { // Log queries that take more than 100ms
                Log::channel('queries')->info(
                    $query->sql,
                    ['bindings' => $query->bindings, 'time' => $query->time]
                );
            }
        });
        // Enable query caching
        DB::connection('mysql2')->getPdo()->setAttribute(
            PDO::ATTR_EMULATE_PREPARES,
            false
        );
    }
}
