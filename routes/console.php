<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

// Jalankan perintah mark-alpha setiap hari jam 23:59
Schedule::command('attendance:mark-alpha')
    ->dailyAt('23:59')
    ->timezone('Asia/Jakarta'); // Pastikan timezone sesuai

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
