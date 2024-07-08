<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Date;

class Parity extends Command
{
    protected $signature = 'game:Parityintervel';

    protected $description = 'Description of your command';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        while (true) {
            // Execute your commands here
            $currentMinute = Date::now()->format('i');
            if (($currentMinute + 2) % 3 == 0) {
               $output =  exec('curl https://admin.yuviwin.com/api/cron/v1/parity');
               Log::info('Output of command parity: ' . $output.' at '.now());
            }
            sleep(60);
        }
    }
}
