<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Spatie\ShortSchedule\ShortSchedule;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
//    protected function schedule(Schedule $schedule)
//    {
//        $schedule->command('test:name')->everyMinute();
//    }

    protected function shortSchedule(ShortSchedule $shortSchedule)
    {
        $shortSchedule->command('match:user_v2')
            ->withoutOverlapping()
            ->everySeconds(3);
    }


    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
