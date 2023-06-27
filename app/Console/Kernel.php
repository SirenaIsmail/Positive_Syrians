<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;
use App\Models\Course;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */

    protected $commands = [
        \App\Console\Commands\FillDateTable::class,
    ];

    protected function schedule(Schedule $schedule)
    {
 $schedule->call(function () {
        $today = date('Y-m-d');
        $courses = DB::table('courses')->where('start', '>=', $today)->get();

        foreach ($courses as $course) {
            if ($course->start == $today && $course->approved == 1) {
                $course->approved = 2;
            } elseif ($course->approved == 2 && $course->end == $today) {
                $course->approved = 3;
            } elseif ($course->approved == 0 && $course->start < $today) {
                $course->approved = 4;
            }
            $course->save();
        }
    })->everyFiveMinutes();
    // تحديث حالات الكورسات المحددة كل خمس دقائق
   
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
