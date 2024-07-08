<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->call(function () {
        //     $currentSecond = (int)date('s');
        //     Log::info('Current second: ' . $currentSecond);
        // })->everyMinute()->name('log-second');
        // $schedule->exec('curl http://admin.yuviwin.com/api/cron/v1/parity')->everyMinute(3)->before(function () {
        //     Log::info('Executing cron/v1/parity task...');
        // })->withoutOverlapping()->runInBackground();
        // $schedule->exec('curl http://admin.yuviwin.com/api/cron/v1/ranks')->everyMinute(5)->before(function () {
        //     Log::info('Executing cron/v1/ranks task...');
        // })->withoutOverlapping()
        // ->runInBackground();
        $schedule->command('game:intervel')
         ->everyMinute()
         ->before(function () {
            Log::info('Executing Fast-parity & circle task...');
        })->withoutOverlapping();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
