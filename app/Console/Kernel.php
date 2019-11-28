<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Order_payment;
use App\Order;
use App\Order_detail;
use Carbon\Carbon;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            Order_payment::where('expired_at', '>', Carbon::now())->update(['payment_status_id' => 4, 'expired_at' => null]);
            $order = Order::where('expired_at', '>', Carbon::now())->pluck('id');
            Order_detail::whereIn('order_id', $order)->update(['order_status_id' => 12]);
            Order::where('expired_at', '>', Carbon::now())->update(['expired_at' => null]);
        })->everyMinute();
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
