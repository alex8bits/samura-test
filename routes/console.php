<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command(\App\Console\Commands\ImportProducts::class)
    ->dailyAt('00:00')
    ->runInBackground();
Schedule::command(\App\Console\Commands\GetAnalytics::class)
    ->dailyAt('00:05')
    ->runInBackground();
Schedule::command(\App\Console\Commands\ImportPostings::class)
    ->dailyAt('00:10')
    ->runInBackground();
