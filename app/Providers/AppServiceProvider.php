<?php

namespace App\Providers;
use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Carbon\Carbon::setLocale('id'); // Format teks dalam bahasa Indonesia
        setlocale(LC_TIME, 'id_ID.utf8'); // Format tanggal lokal sistem (opsional)
    }
}
