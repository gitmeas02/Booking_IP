<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Scheduled tasks for automatic payment checking
Schedule::command('payments:check-pending --limit=100 --age=2')
    ->everyFiveMinutes()
    ->withoutOverlapping()
    ->runInBackground()
    ->onSuccess(function () {
        Artisan::call('log:info', ['message' => 'Payment status check completed successfully']);
    })
    ->onFailure(function () {
        Artisan::call('log:error', ['message' => 'Payment status check failed']);
    });

// More frequent check for recent transactions
Schedule::command('payments:check-pending --limit=50 --age=1')
    ->everyTwoMinutes()
    ->withoutOverlapping()
    ->runInBackground();

// Cleanup expired transactions daily
Schedule::command('payments:check-pending --limit=1000 --age=0')
    ->daily()
    ->at('02:00')
    ->withoutOverlapping();
