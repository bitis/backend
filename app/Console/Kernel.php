<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('XiaomiSports 15138674502 liuyuan321')->dailyAt('8:00');
        $schedule->command('monitor:visa')->everyThreeMinutes()->between('8:00', '20:00')->runInBackground();
        $schedule->command('monitor:lenovo')->everyMinute()->between('8:00', '18:00')->runInBackground();
        $schedule->command('monitor:icbc_mastercard')->everyMinute()->between('9:00', '12:00')->runInBackground();
        $schedule->command('financial:webank')->weekdays()->between('0:00', '10:00')->everyFiveMinutes();
        $schedule->command('financial:citic')->weekdays()->between('0:00', '10:00')->everyFiveMinutes();

        $schedule->command('app:stats-init')->at('0:00')->runInBackground();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
