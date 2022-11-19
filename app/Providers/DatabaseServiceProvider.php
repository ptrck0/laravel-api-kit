<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Log;

class DatabaseServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        DB::listen(function ($query) {
            if ($query->time > 1000) {
                Log::channel('performance')->warning('An individual database query exceeded 1 second.', [
                    'sql' => $query->sql,
                ]);
            }
        });
    }
}
