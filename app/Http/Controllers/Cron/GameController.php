<?php

namespace App\Http\Controllers\Cron;

use App\Http\Controllers\Controller;
use App\Models\Bonus;
use App\Models\CircleBetting;
use App\Models\CircleRecords;
use App\Models\CircleSet;
use App\Models\FastParityBetting;
use App\Models\FastParityRecords;
use App\Models\FastParitySet;
use App\Models\ParityBetting;
use App\Models\ParityRecords;
use App\Models\ParitySet;
use App\Models\Ranks;
use App\Models\Setting;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use GrahamCampbell\ResultType\Success;
use Illuminate\Support\Facades\DB;

class GameController extends Controller
{
    public function fastParity()
    {
        $current = Carbon::createFromFormat('Y-m-d H:i:s', date("Y-m-d 00:00:00"))->timestamp;
        $now = Carbon::now()->timestamp;
        $firstperiodid = Carbon::tomorrow()->format('Ymd') . sprintf("%03d", 480);
        $lastperiodid = Carbon::today()->format('Ymd') . sprintf("%03d", 1);


        $nextPre = FastParitySet::first();
        if ($nextPre->nxt == 11) {
            $betting  = FastParityBetting::where('status', 'pending')
                ->where('period', $nextPre->period)
                ->selectRaw('(SUM(CASE WHEN ans = "0" THEN amount ELSE 0 END)*6 +
                        (SELECT SUM(CASE WHEN ans = "Small" THEN amount ELSE 0 END) )*2+
                        (SELECT SUM(CASE WHEN ans = "red" THEN amount ELSE 0 END) )*1.5 +
                        (SELECT SUM(CASE WHEN ans = "violet" THEN amount ELSE 0 END))*3) as option0')
                ->selectRaw('(SUM(CASE WHEN ans = "1" THEN amount ELSE 0 END)*6 +
                        (SELECT SUM(CASE WHEN ans = "Small" THEN amount ELSE 0 END) )*2+
                        (SELECT SUM(CASE WHEN ans = "green" THEN amount ELSE 0 END))*2) as option1')
                ->selectRaw('(SUM(CASE WHEN ans = "2" THEN amount ELSE 0 END)*6 +
                        (SELECT SUM(CASE WHEN ans = "Small" THEN amount ELSE 0 END) )*2+
                        (SELECT SUM(CASE WHEN ans = "red" THEN amount ELSE 0 END))*2) as option2')
                ->selectRaw('(SUM(CASE WHEN ans = "3" THEN amount ELSE 0 END)*6 +
                        (SELECT SUM(CASE WHEN ans = "Small" THEN amount ELSE 0 END) )*2+
                        (SELECT SUM(CASE WHEN ans = "green" THEN amount ELSE 0 END))*2) as option3')
                ->selectRaw('(SUM(CASE WHEN ans = "4" THEN amount ELSE 0 END)*6 +
                        (SELECT SUM(CASE WHEN ans = "Small" THEN amount ELSE 0 END) )*2+
                        (SELECT SUM(CASE WHEN ans = "red" THEN amount ELSE 0 END))*2) as option4')
                ->selectRaw('(SUM(CASE WHEN ans = "5" THEN amount ELSE 0 END)*6 +
                        (SELECT SUM(CASE WHEN ans = "Big" THEN amount ELSE 0 END) )*2+
                        (SELECT SUM(CASE WHEN ans = "green" THEN amount ELSE 0 END) )*1.5 +
                        (SELECT SUM(CASE WHEN ans = "violet" THEN amount ELSE 0 END))*3) as option5')
                ->selectRaw('(SUM(CASE WHEN ans = "6" THEN amount ELSE 0 END)*6 +
                        (SELECT SUM(CASE WHEN ans = "Big" THEN amount ELSE 0 END) )*2+
                        (SELECT SUM(CASE WHEN ans = "red" THEN amount ELSE 0 END))*2) as option6')
                ->selectRaw('(SUM(CASE WHEN ans = "7" THEN amount ELSE 0 END)*6 +
                        (SELECT SUM(CASE WHEN ans = "Big" THEN amount ELSE 0 END) )*2+
                        (SELECT SUM(CASE WHEN ans = "green" THEN amount ELSE 0 END))*2) as option7')
                ->selectRaw('(SUM(CASE WHEN ans = "8" THEN amount ELSE 0 END)*6 +
                        (SELECT SUM(CASE WHEN ans = "Big" THEN amount ELSE 0 END) )*2+
                        (SELECT SUM(CASE WHEN ans = "red" THEN amount ELSE 0 END))*2) as option8')
                ->selectRaw('(SUM(CASE WHEN ans = "9" THEN amount ELSE 0 END)*6 +
                        (SELECT SUM(CASE WHEN ans = "Big" THEN amount ELSE 0 END) )*2+
                        (SELECT SUM(CASE WHEN ans = "green" THEN amount ELSE 0 END))*2) as option9')
                ->first()->toArray();
            $min = min($betting);
            $a = [];
            for ($i = 0; $i < 10; $i++) {
                if ($betting["option" . $i] == $min) {
                    array_push($a, $i);
                }
            }
            $random_keys = array_rand($a, 1);
            $t = $a[$random_keys];
            $x = rand(40000, 50000);
            $y = $x % 10;
            $num = ($x - $y) + $t;
        } else {
            $x = rand(40000, 50000);
            $y = $x % 10;
            $num = ($x - $y)+$nextPre->nxt;
        }
        $price = $num;
        $prices = $num % 10;
        $ans = $prices;
        if ($prices == 0 || $prices == 5) {
            $res1 = "violet";
        } else {
            $res1 = "";
        }
        $e = $ans % 2;

        if ($e == 0) {
            $res = 'red';
        } elseif ($e == 1) {
            $res = 'green';
        }
        if ($prices >= 5) {
            $resbs = "Big";
        } else {
            $resbs = "Small";
        }
        $bettingCount = FastParityBetting::where('status', 'pending')->where('period', $nextPre->period)->count();
        if ($bettingCount == 0) {
            $bettingResult = FastParityRecords::updateOrCreate(
                [
                    'period' => $nextPre->period,
                ],
                [
                    'ans' => $ans,
                    'num' => $num,
                    'clo' => $res,
                    'res1' => $res1
                ]
            );
        } else {
            $bettingUpdate = FastParityBetting::where('period', $nextPre->period)
                ->where('status', 'pending')
                ->update([
                    'res' => 'fail',
                    'price' => $num,
                    'number' => $prices,
                    'color' => $res,
                    'color2' => $res1,
                    'bigsmall' => $resbs
                ]);
            switch ($prices) {
                case "1":
                    $this->addWinnerAsNumberFastParity('1', $nextPre->period, $prices);
                    break;
                case "2":
                    $this->addWinnerAsNumberFastParity('2', $nextPre->period, $prices);
                    break;
                case "3":
                    $this->addWinnerAsNumberFastParity('3', $nextPre->period, $prices);
                    break;
                case "4":
                    $this->addWinnerAsNumberFastParity('4', $nextPre->period, $prices);
                    break;
                case "5":
                    $this->addWinnerAsNumberFastParity('5', $nextPre->period, $prices);
                    break;
                case "6":
                    $this->addWinnerAsNumberFastParity('6', $nextPre->period, $prices);
                    break;
                case "7":
                    $this->addWinnerAsNumberFastParity('7', $nextPre->period, $prices);
                    break;
                case "8":
                    $this->addWinnerAsNumberFastParity('8', $nextPre->period, $prices);
                    break;
                case "9":
                    $this->addWinnerAsNumberFastParity('9', $nextPre->period, $prices);
                    break;
                case "0":
                    $this->addWinnerAsNumberFastParity('0', $nextPre->period, $prices);
                    break;
                default:
                    echo "ERROR NO NUMBER FOUND";
                    break;
            }

            if ($res == "red" && $resbs=="Big" && $res1 == "") {
                $this->addWinnerAsColorFastParity('red', 2, $nextPre->period, $prices, $res, $res1, $resbs);
                $this->addWinnerAsBigSmallFastParity('Big', 2, $nextPre->period, $prices, $res, $res1, $resbs);
            }else if ($res == "red" && $resbs=="Small" && $res1 == "") {
                $this->addWinnerAsColorFastParity('red', 2, $nextPre->period, $prices, $res, $res1, $resbs);
                $this->addWinnerAsBigSmallFastParity('Small', 2, $nextPre->period, $prices, $res, $res1, $resbs);
            } elseif ($res == "green" && $resbs=="Big" && $res1 == "") {
                $this->addWinnerAsColorFastParity('green', 2, $nextPre->period, $prices, $res, $res1, $resbs);
                $this->addWinnerAsBigSmallFastParity('Big', 2, $nextPre->period, $prices, $res, $res1, $resbs);
            }elseif ($res == "green" && $resbs=="Small" && $res1 == "") {
                $this->addWinnerAsColorFastParity('green', 2, $nextPre->period, $prices, $res, $res1, $resbs);
                $this->addWinnerAsBigSmallFastParity('Small', 2, $nextPre->period, $prices, $res, $res1, $resbs);
            } elseif ($res == "green" && $resbs=="Big" && $res1 == "violet") {
                $this->addWinnerAsColorFastParity('green', 0.5, $nextPre->period, $prices, $res, $res1, $resbs);
                $this->addWinnerAsColorFastParity('violet', 3.5, $nextPre->period, $prices, $res, $res1, $resbs);
                $this->addWinnerAsBigSmallFastParity('Big', 2, $nextPre->period, $prices, $res, $res1, $resbs);
            }elseif ($res == "green" && $resbs=="Small" && $res1 == "violet") {
                $this->addWinnerAsColorFastParity('green', 0.5, $nextPre->period, $prices, $res, $res1, $resbs);
                $this->addWinnerAsColorFastParity('violet', 3.5, $nextPre->period, $prices, $res, $res1, $resbs);
                $this->addWinnerAsBigSmallFastParity('Small', 2, $nextPre->period, $prices, $res, $res1, $resbs);
            } elseif ($res == "red" && $resbs=="Big" && $res1 == "violet") {
                $this->addWinnerAsColorFastParity('red', 0.5, $nextPre->period, $prices, $res, $res1, $resbs);
                $this->addWinnerAsColorFastParity('violet', 3.5, $nextPre->period, $prices, $res, $res1, $resbs);
                $this->addWinnerAsBigSmallFastParity('Big', 2, $nextPre->period, $prices, $res, $res1, $resbs);
            }elseif ($res == "red" && $resbs=="Small" && $res1 == "violet") {
                $this->addWinnerAsColorFastParity('red', 0.5, $nextPre->period, $prices, $res, $res1, $resbs);
                $this->addWinnerAsColorFastParity('violet', 3.5, $nextPre->period, $prices, $res, $res1, $resbs);
                $this->addWinnerAsBigSmallFastParity('Small', 2, $nextPre->period, $prices, $res, $res1, $resbs);
            }
            $bettingResult = FastParityRecords::updateOrCreate(
                [
                    'period' => $nextPre->period,
                ],
                [
                    'ans' => $ans,
                    'num' => $num,
                    'clo' => $res,
                    'res1' => $res1
                ]
            );
        }
        FastParityBetting::where('status', 'pending')
            ->update(['status' => 'successful']);
        // $latestPeriod = FastParitySet::orderBy('id', 'desc')->first();
        // $latestPeriodCount = FastParitySet::orderBy('id', 'desc')->count();
        // if ($lastperiodid == $latestPeriod['period']) {
            // FastParitySet::truncate();
            // FastParitySet::insert([
                // 'period' => $firstperiodid,
                // 'nxt' => '11'
            // ]);
        // } elseif ($latestPeriodCount == '0') {
            // FastParitySet::insert([
                // 'period' => $firstperiodid,
                // 'nxt' => '11'
            // ]);
        // } else {
            FastParitySet::where('id', 1)
                ->update([
                    'period' => DB::raw('period + 1'),
                    'nxt' => '11'
                ]);
        // }
        // DB::table('bet')->where('id', 1)->delete();
        // DB::table('bet')->insert(['id' => 1]);
        echo "Success";
    }
    private function addWinnerAsNumberFastParity($ans, $period, $prices)
    {
        $betres0 = FastParityBetting::select('username', 'amount')
            ->where('status', 'pending')
            ->where('ans', $ans)
            ->get();

        foreach ($betres0 as $row0) {
            $winner0 = $row0->username;
            $fbets0 = $row0->amount;
            $winamount0 = ($fbets0 - (2 / 100) * $fbets0) * 8;

            User::where('username', $winner0)
                ->increment('balance', $winamount0);
            Transaction::insert([
                'username' => $winner0,
                'reason' => 'FastParity Winning',
                'amount' => $winamount0,
                'status' => 2
            ]);
            FastParityBetting::where('username', $winner0)
                ->where('period', $period)
                ->where('ans', $ans)
                ->update(['res' => 'success']);
        }
    }
    private function addWinnerAsColorFastParity($ans, $multiplier, $period, $number, $res, $res1, $resbs)
    {
        $betres2 = FastParityBetting::select('username', 'amount')
            ->where('status', 'pending')
            ->where('ans', $ans)
            ->get();

        foreach ($betres2 as $row2) {
            $winner2 = $row2->username;
            $fbets2 = $row2->amount;
            if ($multiplier == .5) {
                $winamount2 = ($fbets2 - (4 / 100) * $fbets2) * $multiplier;
            } else {
                $winamount2 = ($fbets2 - (2 / 100) * $fbets2) * $multiplier;
            }

            User::where('username', $winner2)
                ->increment('balance', $winamount2);
            Transaction::insert([
                'username' => $winner2,
                'reason' => 'FastParity Winning',
                'amount' => $winamount2,
                'status' => 2
            ]);

            FastParityBetting::where('username', $winner2)
                ->where('period', $period)
                ->where('ans', $ans)
                ->update([
                    'res' => 'success',
                    'price' => $winamount2,
                    'number' => $number,
                    'color' => $res,
                    'color2' => $res1,
                    'bigsmall' => $resbs
                ]);
        }
    }
    private function addWinnerAsBigSmallFastParity($ans, $multiplier, $period, $number, $res, $res1,$resbs)
    {
        $betres2 = FastParityBetting::select('username', 'amount')
            ->where('status', 'pending')
            ->where('ans', $ans)
            ->get();

        foreach ($betres2 as $row2) {
            $winner2 = $row2->username;
            $fbets2 = $row2->amount;
            if ($multiplier == .5) {
                $winamount2 = ($fbets2 - (4 / 100) * $fbets2) * $multiplier;
            } else {
                $winamount2 = ($fbets2 - (2 / 100) * $fbets2) * $multiplier;
            }

            User::where('username', $winner2)
                ->increment('balance', $winamount2);
            Transaction::insert([
                'username' => $winner2,
                'reason' => 'FastParity Winning',
                'amount' => $winamount2,
                'status' => 2
            ]);

            FastParityBetting::where('username', $winner2)
                ->where('period', $period)
                ->where('ans', $ans)
                ->update([
                    'res' => 'success',
                    'price' => $winamount2,
                    'number' => $number,
                    'color' => $res,
                    'color2' => $res1,
                    'bigsmall' => $resbs
                ]);
        }
    }
    public function parity()
    {
        $current = strtotime(date("Y-m-d 00:00:00"));
        $now = strtotime(date("Y-m-d H:i:s"));
        $firstperiodid = date('Ymd', strtotime("+1 days")) . sprintf("%03d", 480);
        $lastperiodid = date('Ymd') . sprintf("%03d", 1);
        $nextPre = ParitySet::first();
        if ($nextPre->nxt == 11) {
            $betting  = ParityBetting::where('status', 'pending')
                ->where('period', $nextPre->period)
                ->selectRaw('(SUM(CASE WHEN ans = "0" THEN amount ELSE 0 END)*6 +
                        (SELECT SUM(CASE WHEN ans = "Small" THEN amount ELSE 0 END) )*2+
                        (SELECT SUM(CASE WHEN ans = "red" THEN amount ELSE 0 END) )*1.5 +
                        (SELECT SUM(CASE WHEN ans = "violet" THEN amount ELSE 0 END))*3) as option0')
                ->selectRaw('(SUM(CASE WHEN ans = "1" THEN amount ELSE 0 END)*6 +
                        (SELECT SUM(CASE WHEN ans = "Small" THEN amount ELSE 0 END) )*2+
                        (SELECT SUM(CASE WHEN ans = "green" THEN amount ELSE 0 END))*2) as option1')
                ->selectRaw('(SUM(CASE WHEN ans = "2" THEN amount ELSE 0 END)*6 +
                        (SELECT SUM(CASE WHEN ans = "Small" THEN amount ELSE 0 END) )*2+
                        (SELECT SUM(CASE WHEN ans = "red" THEN amount ELSE 0 END))*2) as option2')
                ->selectRaw('(SUM(CASE WHEN ans = "3" THEN amount ELSE 0 END)*6 +
                        (SELECT SUM(CASE WHEN ans = "Small" THEN amount ELSE 0 END) )*2+
                        (SELECT SUM(CASE WHEN ans = "green" THEN amount ELSE 0 END))*2) as option3')
                ->selectRaw('(SUM(CASE WHEN ans = "4" THEN amount ELSE 0 END)*6 +
                        (SELECT SUM(CASE WHEN ans = "Small" THEN amount ELSE 0 END) )*2+
                        (SELECT SUM(CASE WHEN ans = "red" THEN amount ELSE 0 END))*2) as option4')
                ->selectRaw('(SUM(CASE WHEN ans = "5" THEN amount ELSE 0 END)*6 +
                        (SELECT SUM(CASE WHEN ans = "Big" THEN amount ELSE 0 END) )*2+
                        (SELECT SUM(CASE WHEN ans = "green" THEN amount ELSE 0 END) )*1.5 +
                        (SELECT SUM(CASE WHEN ans = "violet" THEN amount ELSE 0 END))*3) as option5')
                ->selectRaw('(SUM(CASE WHEN ans = "6" THEN amount ELSE 0 END)*6 +
                        (SELECT SUM(CASE WHEN ans = "Big" THEN amount ELSE 0 END) )*2+
                        (SELECT SUM(CASE WHEN ans = "red" THEN amount ELSE 0 END))*2) as option6')
                ->selectRaw('(SUM(CASE WHEN ans = "7" THEN amount ELSE 0 END)*6 +
                        (SELECT SUM(CASE WHEN ans = "Big" THEN amount ELSE 0 END) )*2+
                        (SELECT SUM(CASE WHEN ans = "green" THEN amount ELSE 0 END))*2) as option7')
                ->selectRaw('(SUM(CASE WHEN ans = "8" THEN amount ELSE 0 END)*6 +
                        (SELECT SUM(CASE WHEN ans = "Big" THEN amount ELSE 0 END) )*2+
                        (SELECT SUM(CASE WHEN ans = "red" THEN amount ELSE 0 END))*2) as option8')
                ->selectRaw('(SUM(CASE WHEN ans = "9" THEN amount ELSE 0 END)*6 +
                        (SELECT SUM(CASE WHEN ans = "Big" THEN amount ELSE 0 END) )*2+
                        (SELECT SUM(CASE WHEN ans = "green" THEN amount ELSE 0 END))*2) as option9')
                ->first()->toArray();
            $min = min($betting);
            $a = [];
            for ($i = 0; $i < 10; $i++) {
                if ($betting["option" . $i] == $min) {
                    array_push($a, $i);
                }
            }
            $random_keys = array_rand($a, 1);
            $t = $a[$random_keys];
            $x = rand(40000, 50000);
            $y = $x % 10;
            $num = ($x - $y) + $t;
        } else {
            $x = rand(40000, 50000);
            $y = $x % 10;
            $num = ($x - $y) + $nextPre->nxt;
        }
        $price = $num;
        $prices = $num % 10;
        $ans = $prices;
        if ($prices == 0 || $prices == 5) {
            $res1 = "violet";
        } else {
            $res1 = "";
        }
        $e = $ans % 2;

        if ($e == 0) {
            $res = 'red';
        } elseif ($e == 1) {
            $res = 'green';
        }
        if ($prices >= 5) {
            $resbs = "Big";
        } else {
            $resbs = "Small";
        }
        $bettingCount = ParityBetting::where('status', 'pending')->where('period', $nextPre->period)->count();
        if ($bettingCount == 0) {
            $bettingResult = ParityRecords::updateOrCreate(
                [
                    'period' => $nextPre->period,
                ],
                [
                    'ans' => $ans,
                    'num' => $num,
                    'clo' => $res,
                    'res1' => $res1
                ]
            );
        } else {
            $bettingUpdate = ParityBetting::where('period', $nextPre->period)
                ->where('status', 'pending')
                ->update([
                    'res' => 'fail',
                    'price' => $num,
                    'number' => $prices,
                    'color' => $res,
                    'color2' => $res1,
                    'bigsmall' => $resbs
                ]);

            // Fetch data from the betting table
            // $betres0 = ParityBetting::select('username', 'amount')
            //     ->where('status', 'pending')
            //     ->get();

            // // Iterate through the retrieved data
            // foreach ($betres0 as $row0) {
            //     $winner0 = $row0->username;
            //     $fbets0 = $row0->amount;
            //     $b1 = (40 / 100) * ((5 / 100) * $fbets0);
            //     $b2 = (20 / 100) * ((5 / 100) * $fbets0);
            //     $b3 = (10 / 100) * ((5 / 100) * $fbets0);

            //     // Fetch data from the users table
            //     $getuc = User::select('refcode', 'refcode1', 'refcode2')
            //         ->where('username', $winner0)
            //         ->first();

            //     if ($getuc) {
            //         $r = $getuc->refcode;
            //         $r1 = $getuc->refcode1;
            //         $r2 = $getuc->refcode2;

            //         if ($r) {
            //             User::where('usercode', $r)
            //                 ->increment('bonus', $b1);
            //             Bonus::insert([
            //                 'giver' => $winner0,
            //                 'usercode' => $r,
            //                 'amount' => $b1,
            //                 'level' => '1'
            //             ]);
            //             if ($r1) {
            //                 User::where('usercode', $r1)
            //                     ->increment('bonus', $b2);

            //                 Bonus::insert([
            //                     'giver' => $winner0,
            //                     'usercode' => $r1,
            //                     'amount' => $b2,
            //                     'level' => '2'
            //                 ]);
            //                 if ($r2) {
            //                     User::where('usercode', $r2)
            //                         ->increment('bonus', $b3);
            //                     Bonus::insert([
            //                         'giver' => $winner0,
            //                         'usercode' => $r2,
            //                         'amount' => $b3,
            //                         'level' => '3'
            //                     ]);
            //                 }
            //             }
            //         }
            //     }
            // }
            switch ($prices) {
                case "1":
                    $this->addWinnerAsNumberParity('1', $nextPre->period, $prices);
                    break;
                case "2":
                    $this->addWinnerAsNumberParity('2', $nextPre->period, $prices);
                    break;
                case "3":
                    $this->addWinnerAsNumberParity('3', $nextPre->period, $prices);
                    break;
                case "4":
                    $this->addWinnerAsNumberParity('4', $nextPre->period, $prices);
                    break;
                case "5":
                    $this->addWinnerAsNumberParity('5', $nextPre->period, $prices);
                    break;
                case "6":
                    $this->addWinnerAsNumberParity('6', $nextPre->period, $prices);
                    break;
                case "7":
                    $this->addWinnerAsNumberParity('7', $nextPre->period, $prices);
                    break;
                case "8":
                    $this->addWinnerAsNumberParity('8', $nextPre->period, $prices);
                    break;
                case "9":
                    $this->addWinnerAsNumberParity('9', $nextPre->period, $prices);
                    break;
                case "0":
                    $this->addWinnerAsNumberParity('0', $nextPre->period, $prices);
                    break;
                default:
                    echo "ERROR NO NUMBER FOUND";
                    break;
            }
            
            if($res == "red" && $resbs=="Big" && $res1 == "") {
                $this->addWinnerAsColorParity('red', 2, $nextPre->period, $prices, $res, $res1, $resbs);
                $this->addWinnerAsBigSmallParity('Big', 2, $nextPre->period, $prices, $res, $res1, $resbs);
            }elseif($res == "red" && $resbs=="Small" && $res1 == "") {
                $this->addWinnerAsColorParity('red', 2, $nextPre->period, $prices, $res, $res1, $resbs);
                $this->addWinnerAsBigSmallParity('Small', 2, $nextPre->period, $prices, $res, $res1, $resbs);
            }elseif ($res == "green" && $resbs=="Big" && $res1 == "") {
                $this->addWinnerAsColorParity('green', 2, $nextPre->period, $prices, $res, $res1, $resbs);
                $this->addWinnerAsBigSmallParity('Big', 2, $nextPre->period, $prices, $res, $res1, $resbs);
            } elseif ($res == "green" && $resbs=="Small" && $res1 == "") {
                $this->addWinnerAsColorParity('green', 2, $nextPre->period, $prices, $res, $res1, $resbs);
                $this->addWinnerAsBigSmallParity('Small', 2, $nextPre->period, $prices, $res, $res1, $resbs);
            } elseif ($res == "green" && $resbs=="Big" && $res1 == "violet") {
                $this->addWinnerAsColorParity('green', 0.5, $nextPre->period, $prices, $res, $res1, $resbs);
                $this->addWinnerAsColorParity('violet', 3.5, $nextPre->period, $prices, $res, $res1, $resbs);
                $this->addWinnerAsBigSmallParity('Big', 2, $nextPre->period, $prices, $res, $res1, $resbs);
            } elseif ($res == "green" && $resbs=="Small" && $res1 == "violet") {
                $this->addWinnerAsColorParity('green', 0.5, $nextPre->period, $prices, $res, $res1, $resbs);
                $this->addWinnerAsColorParity('violet', 3.5, $nextPre->period, $prices, $res, $res1, $resbs);
                $this->addWinnerAsBigSmallParity('Small', 2, $nextPre->period, $prices, $res, $res1, $resbs);
            } elseif ($res == "red" && $resbs=="Big" && $res1 == "violet") {
                $this->addWinnerAsColorParity('red', 0.5, $nextPre->period, $prices, $res, $res1, $resbs);
                $this->addWinnerAsColorParity('violet', 3.5, $nextPre->period, $prices, $res, $res1, $resbs);
                $this->addWinnerAsBigSmallParity('Big', 2, $nextPre->period, $prices, $res, $res1, $resbs);
            }elseif ($res == "red" && $resbs=="Small" && $res1 == "violet") {
                $this->addWinnerAsColorParity('red', 0.5, $nextPre->period, $prices, $res, $res1, $resbs);
                $this->addWinnerAsColorParity('violet', 3.5, $nextPre->period, $prices, $res, $res1, $resbs);
                $this->addWinnerAsBigSmallParity('Small', 2, $nextPre->period, $prices, $res, $res1, $resbs);
            }
            $bettingResult = ParityRecords::updateOrCreate(
                [
                    'period' => $nextPre->period,
                ],
                [
                    'ans' => $ans,
                    'num' => $num,
                    'clo' => $res,
                    'res1' => $res1
                ]
            );
        }
        ParityBetting::where('status', 'pending')
            ->update(['status' => 'successful']);
        $latestPeriod = ParitySet::orderBy('id', 'desc')->first();
        $latestPeriodCount = ParitySet::orderBy('id', 'desc')->count();
        if ($lastperiodid == $latestPeriod['period']) {
            ParitySet::truncate();
            ParitySet::insert([
                'period' => $firstperiodid,
                'nxt' => '11'
            ]);
        } elseif ($latestPeriodCount == '0') {
            ParitySet::insert([
                'period' => $firstperiodid,
                'nxt' => '11'
            ]);
        } else {
            ParitySet::where('id', 1)
                ->update([
                    'period' => DB::raw('period + 1'),
                    'nxt' => '11'
                ]);
        }
        DB::table('emredbet')->where('id', 1)->delete();
        DB::table('emredbet')->insert(['id' => 1]);
        echo "Success";
    }
    private function addWinnerAsNumberParity($ans, $period, $prices)
    {
        $betres0 = ParityBetting::select('username', 'amount')
            ->where('status', 'pending')
            ->where('ans', $ans)
            ->get();

        foreach ($betres0 as $row0) {
            $winner0 = $row0->username;
            $fbets0 = $row0->amount;
            $winamount0 = ($fbets0 - (5 / 100) * $fbets0) * 8;

            User::where('username', $winner0)
                ->increment('balance', $winamount0);
            Transaction::insert([
                'username' => $winner0,
                'reason' => 'Parity Winning',
                'amount' => $winamount0,
                'status' => 2
            ]);
            ParityBetting::where('username', $winner0)
                ->where('period', $period)
                ->where('ans', $ans)
                ->update(['res' => 'success']);
        }
    }
    private function addWinnerAsColorParity($ans, $multiplier, $period, $number, $res, $res1,$resbs)
    {
        $betres2 = ParityBetting::select('username', 'amount')
            ->where('status', 'pending')
            ->where('ans', $ans)
            ->get();

        foreach ($betres2 as $row2) {
            $winner2 = $row2->username;
            $fbets2 = $row2->amount;
            if ($multiplier == .5) {
                $winamount2 = ($fbets2 - (10 / 100) * $fbets2) * $multiplier;
            } else if ($multiplier == 2) {
                $winamount2 = ($fbets2 - (2.5 / 100) * $fbets2) * $multiplier;
            } else {
                $winamount2 = ($fbets2 - (5 / 100) * $fbets2) * $multiplier;
            }
            User::where('username', $winner2)
                ->increment('balance', $winamount2);
            Transaction::insert([
                'username' => $winner2,
                'reason' => 'Parity Winning',
                'amount' => $winamount2,
                'status' => 2
            ]);

            ParityBetting::where('username', $winner2)
                ->where('period', $period)
                ->where('ans', $ans)
                ->update([
                    'res' => 'success',
                    'price' => $winamount2,
                    'number' => $number,
                    'color' => $res,
                    'color2' => $res1,
                    'bigsmall' => $resbs
                ]);
        }
    }
    private function addWinnerAsBigSmallParity($ans, $multiplier, $period, $number, $res, $res1,$resbs)
    {
        $betres2 = ParityBetting::select('username', 'amount')
            ->where('status', 'pending')
            ->where('ans', $ans)
            ->get();

        foreach ($betres2 as $row2) {
            $winner2 = $row2->username;
            $fbets2 = $row2->amount;
            if ($multiplier == .5) {
                $winamount2 = ($fbets2 - (10 / 100) * $fbets2) * $multiplier;
            } else if ($multiplier == 2) {
                $winamount2 = ($fbets2 - (2.5 / 100) * $fbets2) * $multiplier;
            } else {
                $winamount2 = ($fbets2 - (5 / 100) * $fbets2) * $multiplier;
            }
            User::where('username', $winner2)
                ->increment('balance', $winamount2);
            Transaction::insert([
                'username' => $winner2,
                'reason' => 'Parity Winning',
                'amount' => $winamount2,
                'status' => 2
            ]);

            ParityBetting::where('username', $winner2)
                ->where('period', $period)
                ->where('ans', $ans)
                ->update([
                    'res' => 'success',
                    'price' => $winamount2,
                    'number' => $number,
                    'color' => $res,
                    'color2' => $res1,
                    'bigsmall' => $resbs
                ]);
        }
    }
    public function circle()
    {
        $current = strtotime(date("Y-m-d 00:00:00"));
        $now = strtotime(date("Y-m-d H:i:s"));
        $firstperiodid = date('Ymd', strtotime("+1 days")) . sprintf("%03d", 480);
        $lastperiodid = date('Ymd') . sprintf("%03d", 1);
        $nextPre = CircleSet::first();
        $nextres='';
        if ($nextPre->nxt == 0) {
            // $results = CircleBetting::where('period', $nextPre->period)
            //     ->selectRaw('
            //     SUM(CASE WHEN ans = "red" THEN amount ELSE 0 END) * 3 * 18 + SUM(CASE WHEN ans = "lion" THEN amount ELSE 0 END) * 50 * 3 AS case_0,
            //     SUM(CASE WHEN ans = "red" THEN amount ELSE 0 END) * 3 * 18 + SUM(CASE WHEN ans = "elephant" THEN amount ELSE 0 END) * 50 * 3 AS case_1,
            //     SUM(CASE WHEN ans = "green" THEN amount ELSE 0 END) * 2 * 2 + SUM(CASE WHEN ans = "lion" THEN amount ELSE 0 END) * 50  * 3 AS case_2,
            //     SUM(CASE WHEN ans = "green" THEN amount ELSE 0 END) * 2 * 2 + SUM(CASE WHEN ans = "king" THEN amount ELSE 0 END) * 50* 3 AS case_3,
            //     SUM(CASE WHEN ans = "green" THEN amount ELSE 0 END) * 2 * 2 + SUM(CASE WHEN ans = "elephant" THEN amount ELSE 0 END) * 50 * 3 AS case_4,
            //     SUM(CASE WHEN ans = "green" THEN amount ELSE 0 END) * 2 * 2 + SUM(CASE WHEN ans = "king" THEN amount ELSE 0 END) * 50 * 18 AS case_5,
            //     SUM(CASE WHEN ans = "yellow" THEN amount ELSE 0 END) * 5 * 2 + SUM(CASE WHEN ans = "lion" THEN amount ELSE 0 END) * 50 * 3 AS case_6,
            //     SUM(CASE WHEN ans = "yellow" THEN amount ELSE 0 END) * 5 * 2 + SUM(CASE WHEN ans = "elephant" THEN amount ELSE 0 END) * 50 * 3 AS case_7,
            //     SUM(CASE WHEN ans = "yellow" THEN amount ELSE 0 END) * 5 * 2 + SUM(CASE WHEN ans = "king" THEN amount ELSE 0 END) * 18 AS case_8,
            //     SUM(CASE WHEN ans = "yellow" THEN amount ELSE 0 END) * 5 * 2 + SUM(CASE WHEN ans = "cow" THEN amount ELSE 0 END) * 50 * 3 AS case_9
            //     ')
            //     ->where('status', 'pending')
            //     ->first()->toArray();
            $results = CircleBetting::where('period', $nextPre->period)
                ->selectRaw('SUM(CASE WHEN ans = "gold" THEN amount ELSE 0 END) * 2   AS case_0')
               ->selectRaw('SUM(CASE WHEN ans = "red" THEN amount ELSE 0 END) * 3 AS case_1')
               ->selectRaw('SUM(CASE WHEN ans = "violet" THEN amount ELSE 0 END) * 5 AS case_2')
               ->selectRaw('SUM(CASE WHEN ans = "green" THEN amount ELSE 0 END) * 50 AS case_3')
                ->where('status', 'pending')
                ->first()->toArray();
            $colors = array('gold', 'red', 'violet', 'green');
            $min = min($results);
            $a = [];
            for ($i = 0; $i < 4; $i++) {
                if ($results["case_" . $i] == $min) {
                    array_push($a, $i);
                }
            }
            $a;
            $index = array_rand($a, 1);
            $nextres = $colors[$a[$index]];
            $nclo = $this->generateRes($nextres);
            $clo = $nextres;
            $num = rand(40000, 50000);
            $ans = 3.5 + ((360 / 54) * $nclo);
            $ans = number_format($ans, 2);
        } else {
            if ($nextPre->nxt ==  1) {
                $nextres = 'gold';
            } elseif ($nextPre->nxt ==  2) {
                $nextres = 'red';
            } elseif ($nextPre->nxt ==  3) {
                $nextres = 'violet';
            } elseif ($nextPre->nxt ==  4) {
                $nextres = 'green';
            }
            $nclo = $this->generateRes($nextres);
            $clo = $nextres;
            $num = rand(40000, 50000);
            $ans = 3.5 + ((360 / 54) * $nclo);
            $ans = number_format($ans, 2);
        }
        $bettingCount = CircleBetting::where('status', 'pending')->where('period', $nextPre->period)->count();
        if ($bettingCount == 0) {
            $bettingResult = CircleRecords::updateOrCreate(
                [
                    'period' => $nextPre->period,
                ],
                [
                    'ans' => $ans,
                    'num' => $num,
                    'clo' => $clo,
                ]
            );
        } else {
            $bettingUpdate = CircleBetting::where('period', $nextPre->period)
                ->where('status', 'pending')
                ->update([
                    'res' => 'fail',
                    'fres' => $clo,
                ]);
            // $betres0 = CircleBetting::select('username', 'amount')
            //     ->where('period', $nextPre->period)
            //     ->where('status', 'pending')
            //     ->get();
            // foreach ($betres0 as $row0) {
            //     $winner0 = $row0->username;
            //     $fbets0 = $row0->amount;
            //     $b1 = (40 / 100) * ((2 / 100) * $fbets0);
            //     $b2 = (20 / 100) * ((2 / 100) * $fbets0);
            //     $b3 = (10 / 100) * ((2 / 100) * $fbets0);
            //     $getuc = User::select('refcode', 'refcode1', 'refcode2')
            //         ->where('username', $winner0)
            //         ->first();
            //     if ($getuc) {
            //         $r = $getuc->refcode;
            //         $r1 = $getuc->refcode1;
            //         $r2 = $getuc->refcode2;

            //         if ($r) {
            //             User::where('usercode', $r)
            //                 ->increment('bonus', $b1);
            //             Bonus::insert([
            //                 'giver' => $winner0,
            //                 'usercode' => $r,
            //                 'amount' => $b1,
            //                 'level' => '1'
            //             ]);
            //             if ($r1) {
            //                 User::where('usercode', $r1)
            //                     ->increment('bonus', $b2);

            //                 Bonus::insert([
            //                     'giver' => $winner0,
            //                     'usercode' => $r1,
            //                     'amount' => $b2,
            //                     'level' => '2'
            //                 ]);
            //                 if ($r2) {
            //                     User::where('usercode', $r2)
            //                         ->increment('bonus', $b3);
            //                     Bonus::insert([
            //                         'giver' => $winner0,
            //                         'usercode' => $r2,
            //                         'amount' => $b3,
            //                         'level' => '3'
            //                     ]);
            //                 }
            //             }
            //         }
            //     }
            // }
            if ($clo == 'gold') {
                $this->addWinCircle('gold', 2, $nextPre->period);
            } elseif ($clo == 'red') {
                $this->addWinCircle('red', 3, $nextPre->period);
            } elseif ($clo == 'violet') {
                $this->addWinCircle('violet', 5, $nextPre->period);
            } else if ($clo == 'green') {
                $this->addWinCircle('green', 50, $nextPre->period);
            }
            $bettingResult = CircleRecords::updateOrCreate(
                [
                    'period' => $nextPre->period,
                ],
                [
                    'ans' => $ans,
                    'num' => $num,
                    'clo' => $clo,
                ]
            );
        }
        CircleBetting::where('status', 'pending')
            ->update(['status' => 'successful']);
        $latestPeriod = CircleSet::orderBy('id', 'desc')->first();
        $latestPeriodCount = CircleSet::orderBy('id', 'desc')->count();
        if ($lastperiodid == $latestPeriod['period']) {
            CircleSet::truncate();
            CircleSet::insert([
                'period' => $firstperiodid,
                'nxt' => '0'
            ]);
        } elseif ($latestPeriodCount == '0') {
            CircleSet::insert([
                'period' => $firstperiodid,
                'nxt' => '0'
            ]);
        } else {
            CircleSet::where('id', 1)
                ->update([
                    'period' => DB::raw('period + 1'),
                    'nxt' => '0'
                ]);
        }
        echo "Success";
    }
    private function addWinCircle($ans, $multiplier, $period)
    {
        $betres2 = CircleBetting::select('username', 'amount')
            ->where('status', 'pending')
            ->where('ans', $ans)
            ->get();

        foreach ($betres2 as $row2) {
            $winner2 = $row2->username;
            $fbets2 = $row2->amount;
            $winamount2 = ($fbets2 - (2.5 / 100) * $fbets2) * $multiplier;

            User::where('username', $winner2)
                ->increment('balance', $winamount2);
            Transaction::insert([
                'username' => $winner2,
                'reason' => 'Circle Winning',
                'amount' => $winamount2,
                'status' => 2
            ]);
            CircleBetting::where('username', $winner2)
                ->where('period', $period)
                ->where('ans', $ans)
                ->update(['res' => 'success']);
        }
    }
    private function generateRes($fres)
    {
        $master = null;
        switch ($fres) {
            case "red":
                $even = [1, 5, 7, 11, 13, 17, 19, 23, 25, 29, 31, 35, 37, 41, 43, 47, 51];
                $master = $even[array_rand($even)];
                break;
            case "green":
                $even = [53];
                $master = $even[array_rand($even)];
                break;
            case "violet":
                $odd = [3, 9, 15, 21, 27, 33, 39, 45.49, 52];
                $master = $odd[array_rand($odd)];
                break;
            case "gold":
                $odd = [0, 2, 4, 6, 8, 10, 12, 14, 16, 18, 20, 22, 24, 26, 28, 30, 32, 34, 36, 38, 40, 42, 44, 46, 48, 50];
                $master = $odd[array_rand($odd)];
                break;
                // case "yellowelephant":
                //     $odd = [1, 3, 5, 7, 10, 12];
                //     $master = $odd[array_rand($odd)];
                //     break;
                // case "yellowcow":
                //     $odd = [14, 16, 18, 20, 22, 24];
                //     $master = $odd[array_rand($odd)];
                //     break;
                // case "greenlion":
                //     $odd = [25, 27, 30, 32, 34, 36];
                //     $master = $odd[array_rand($odd)];
                //     break;
                // case "greenking":
                //     $odd = [0];
                //     $master = $odd[array_rand($odd)];
                //     break;
                // case "greenelephant":
                //     $odd = [2, 4, 6, 8, 11];
                //     $master = $odd[array_rand($odd)];
                //     break;
                // case "greencow":
                //     $odd = [13, 15, 17, 19, 21, 23];
                //     $master = $odd[array_rand($odd)];
                //     break;
        }

        return $master;
    }

    // renk generate
    public function rank()
    {
        $isauto = Setting::first()->autoranks;
        $currentDate = Carbon::now()->toDateString();
        if ($isauto) {
            // $ranks = [];
            // $users = User::where('is_verified', 1)->get();
            // foreach ($users as $user) {
            //     $rank = [];
            //     $rank['username'] = $user->username;
            //     $rank['count'] = User::where('is_verified', 1)
            //         ->where('created_at', '>=', $currentDate)
            //         ->where('refcode', $user->usercode)
            //         ->count();
            //     $rank['amount'] = Bonus::where('usercode',$user->usercode)->whereIn('level',['1,2,3'])->sum('amount');
            //     $ranks[] = $rank;
            // }
            // return $ranks;
            $ranks =  DB::table('users')
                ->where('users.is_verified', 1)
                ->select(
                    'users.username',
                    DB::raw('(SELECT COUNT(*) FROM users AS u2 WHERE u2.refcode = users.usercode AND u2.is_verified = 1 AND DATE(u2.created_at) >= CURDATE()) as count'),
                    DB::raw('COALESCE((SELECT SUM(amount) FROM bonus WHERE bonus.usercode = users.usercode AND DATE(bonus.created_at) >= CURDATE()), 0) as amount')
                )
                ->orderByDesc('count') // Order by count in descending order
                ->orderByRaw('CASE WHEN count = 0 THEN amount ELSE 0 END DESC')
                // ->groupBy('users.username')
                ->get();
            Ranks::query()->update(['ranks' => 0]);
            foreach ($ranks as $index => $user) {
                Ranks::updateOrCreate(
                    ['username' => $user->username],
                    ['username' => $user->username, 'ranks' => $index + 1, 'amount' => $user->amount]
                );
            }
            return 'Ranks generated';
        } else {
            return "ranks not auto generate allowed";
        }
    }
}
