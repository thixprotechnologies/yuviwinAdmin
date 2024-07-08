<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class FastParity extends Command
{
    protected $signature = 'game:intervel';

    protected $description = 'Description of your command';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->info('Starting the loop...');
        $paritytime = 6;
        while (true) {
            // Execute your commands here
            $currentSecond =  (int)date('s');
            if ($currentSecond == 29 || $currentSecond == 59) {
              $output1 =   exec('curl https://admin.yuviwin.com/api/cron/v1/circle > /dev/null 2>&1 &');
              $output2 =  exec('curl https://admin.yuviwin.com/api/cron/v1/fast-parity > /dev/null 2>&1 &'); 
                $paritytime = $paritytime-1;
                if($paritytime == 0){
                    $paritytime = 6;
                    $output =   exec('curl https://admin.yuviwin.com/api/cron/v1/parity > /dev/null 2>&1 &');
                    Log::info('Commands executed parity at' . now());
                    $output =   exec('curl https://admin.yuviwin.com/api/cron/v1/ranks > /dev/null 2>&1 &');
                    Log::info('Commands executed Ranks at' . now());
                }
              Log::info('Output of command fast parity: ' . $output2);
              Log::info('Output of command circle: ' . $output1);               
              Log::info('Commands executed at ' . now());
            }
            if ($currentSecond == 59) {
                break; // Exit the loop when current second is 59
            }
            sleep(1);
        }
    }
}
