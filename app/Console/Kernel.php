<?php

namespace App\Console;

use App\Jobs\NewsParserJob;
use App\Models\ApiSource;
use App\Models\News;
use App\Models\Query;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [//
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(
            function () {
                dispatch(new NewsParserJob(1));
            }
        )->everyMinute();
    }

}
