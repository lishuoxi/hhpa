<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Schedule::command('app:trade-fail')->everyMinute()->withoutOverlapping();
Schedule::command('app:update-login')->dailyAt('5:00');
Schedule::command('app:data-clear')->dailyAt('4:00');
