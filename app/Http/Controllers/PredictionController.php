<?php

namespace App\Http\Controllers;

use App\Models\CircleBetting;
use App\Models\CircleSet;
use App\Models\FastParityBetting;
use App\Models\FastParitySet;
use App\Models\JetBetting;
use App\Models\JetSet;
use App\Models\ParityBetting;
use App\Models\ParitySet;
use Illuminate\Http\Request;

class PredictionController extends Controller
{
    public function nextPrediction()
    {
        $pagetitle = "Fast Parity Prediction";
        $nextPre = FastParitySet::first();
        // $red = FastParityBetting::where('status','pending')->where('ans','red')->sum('amount');
        // $green = FastParityBetting::where('status','pending')->where('ans','green')->sum('amount');
        // $violet = FastParityBetting::where('status','pending')->where('ans','violet')->sum('amount');
        // $option = FastParityBetting::where('status','pending')->where('ans','0')->sum('amount');
        // $option1 = FastParityBetting::where('status','pending')->where('ans','1')->sum('amount');
        // $option2 = FastParityBetting::where('status','pending')->where('ans','2')->sum('amount');
        // $option3 = FastParityBetting::where('status','pending')->where('ans','3')->sum('amount');
        // $option4 = FastParityBetting::where('status','pending')->where('ans','4')->sum('amount');
        // $option5 = FastParityBetting::where('status','pending')->where('ans','5')->sum('amount');
        // $option6 = FastParityBetting::where('status','pending')->where('ans','6')->sum('amount');
        // $option7 = FastParityBetting::where('status','pending')->where('ans','7')->sum('amount');
        // $option8 = FastParityBetting::where('status','pending')->where('ans','8')->sum('amount');
        // $option9 = FastParityBetting::where('status','pending')->where('ans','9')->sum('amount');
        $betting = FastParityBetting::where('status', 'pending')
        ->where('period',$nextPre->period)
        ->selectRaw('SUM(CASE WHEN ans = "Big" THEN amount ELSE 0 END) as Big')
        ->selectRaw('SUM(CASE WHEN ans = "Small" THEN amount ELSE 0 END) as Small')
        ->selectRaw('SUM(CASE WHEN ans = "red" THEN amount ELSE 0 END) as red')
        ->selectRaw('SUM(CASE WHEN ans = "green" THEN amount ELSE 0 END) as green')
        ->selectRaw('SUM(CASE WHEN ans = "violet" THEN amount ELSE 0 END) as violet')
        ->selectRaw('SUM(CASE WHEN ans = "0" THEN amount ELSE 0 END) as option0')
        ->selectRaw('SUM(CASE WHEN ans = "1" THEN amount ELSE 0 END) as option1')
        ->selectRaw('SUM(CASE WHEN ans = "2" THEN amount ELSE 0 END) as option2')
        ->selectRaw('SUM(CASE WHEN ans = "3" THEN amount ELSE 0 END) as option3')
        ->selectRaw('SUM(CASE WHEN ans = "4" THEN amount ELSE 0 END) as option4')
        ->selectRaw('SUM(CASE WHEN ans = "5" THEN amount ELSE 0 END) as option5')
        ->selectRaw('SUM(CASE WHEN ans = "6" THEN amount ELSE 0 END) as option6')
        ->selectRaw('SUM(CASE WHEN ans = "7" THEN amount ELSE 0 END) as option7')
        ->selectRaw('SUM(CASE WHEN ans = "8" THEN amount ELSE 0 END) as option8')
        ->selectRaw('SUM(CASE WHEN ans = "9" THEN amount ELSE 0 END) as option9')
        ->first();
        $bettingTotal =  FastParityBetting::where('status', 'pending')
        ->where('period',$nextPre->period)->sum('amount'); 
        return view('prediction.index', compact('pagetitle','betting','nextPre','bettingTotal'));

    }
    public function addAnser(Request $request){
        $request->validate([
            'answer'=>"required"
        ]);
        if($request->betting == 'fastParity'){
            $nextPre = FastParitySet::first();
            $nextPre->nxt = $request->answer;
            if($nextPre->save()){
                $notify[] = ['success','Added Prediction Details for Fast Parity Game'];
                return back()->withNotify($notify);
            }else{
                $notify[] = ['error','Some Error In update'];
                return back()->withNotify($notify);
            }
        }
        else if($request->betting == 'Parity'){
            $nextPre = ParitySet::first();
            $nextPre->nxt = $request->answer;
            if($nextPre->save()){
                $notify[] = ['success','Added Prediction Details for Parity Game'];
                return back()->withNotify($notify);
            }else{
                $notify[] = ['error','Some Error In update'];
                return back()->withNotify($notify);
            }
        }
        else if($request->betting == 'circle'){
            $nextPre = CircleSet::first();
            $nextPre->nxt = $request->answer;
            if($nextPre->save()){
                $notify[] = ['success','Added Prediction Details for Circle Game'];
                return back()->withNotify($notify);
            }else{
                $notify[] = ['error','Some Error In update'];
                return back()->withNotify($notify);
            }
        }
        else if($request->betting == 'jet'){
            $request->validate([
                'answer'=>"numeric|max:25"
            ]);
            $nextPre = JetSet::first();
            $nextPre->nxt = $request->answer;
            if($nextPre->save()){
                $notify[] = ['success','Added Prediction Details for Jet Game'];
                return back()->withNotify($notify);
            }else{
                $notify[] = ['error','Some Error In update'];
                return back()->withNotify($notify);
            }
        }
        else{
            $notify[] = ['error','Invalid Details'];
            return back()->withNotify($notify);
        }
    }
    public function parityPrediction(){
        $pagetitle = "Parity Prediction";
        $nextPre = ParitySet::first();
        $betting = ParityBetting::where('status', 'pending')
        ->where('period',$nextPre->period)
        ->selectRaw('SUM(CASE WHEN ans = "Big" THEN amount ELSE 0 END) as Big')
        ->selectRaw('SUM(CASE WHEN ans = "Small" THEN amount ELSE 0 END) as Small')
        ->selectRaw('SUM(CASE WHEN ans = "red" THEN amount ELSE 0 END) as red')
        ->selectRaw('SUM(CASE WHEN ans = "green" THEN amount ELSE 0 END) as green')
        ->selectRaw('SUM(CASE WHEN ans = "violet" THEN amount ELSE 0 END) as violet')
        ->selectRaw('SUM(CASE WHEN ans = "0" THEN amount ELSE 0 END) as option0')
        ->selectRaw('SUM(CASE WHEN ans = "1" THEN amount ELSE 0 END) as option1')
        ->selectRaw('SUM(CASE WHEN ans = "2" THEN amount ELSE 0 END) as option2')
        ->selectRaw('SUM(CASE WHEN ans = "3" THEN amount ELSE 0 END) as option3')
        ->selectRaw('SUM(CASE WHEN ans = "4" THEN amount ELSE 0 END) as option4')
        ->selectRaw('SUM(CASE WHEN ans = "5" THEN amount ELSE 0 END) as option5')
        ->selectRaw('SUM(CASE WHEN ans = "6" THEN amount ELSE 0 END) as option6')
        ->selectRaw('SUM(CASE WHEN ans = "7" THEN amount ELSE 0 END) as option7')
        ->selectRaw('SUM(CASE WHEN ans = "8" THEN amount ELSE 0 END) as option8')
        ->selectRaw('SUM(CASE WHEN ans = "9" THEN amount ELSE 0 END) as option9')
        ->first();
        $bettingTotal =  ParityBetting::where('status', 'pending')
        ->where('period',$nextPre->period)->sum('amount'); 
        return view('prediction.parity', compact('pagetitle','betting','nextPre','bettingTotal'));

    }
    public function circlePrediction(){
        $pagetitle = "Circle Prediction";
        $nextPre = CircleSet::first();
        $betting = CircleBetting::where('status', 'pending')
        ->where('period',$nextPre->period)
        // ->groupBy('ans')
        ->selectRaw('SUM(CASE WHEN ans = "red" THEN amount ELSE 0 END) as red')
        ->selectRaw('SUM(CASE WHEN ans = "green" THEN amount ELSE 0 END) as green')
        ->selectRaw('SUM(CASE WHEN ans = "violet" THEN amount ELSE 0 END) as violet')
        ->selectRaw('SUM(CASE WHEN ans = "gold" THEN amount ELSE 0 END) as gold')
        ->first();
        $bettingTotal =  CircleBetting::where('status', 'pending')
        ->where('period',$nextPre->period)->sum('amount'); 
        return view('prediction.circle', compact('pagetitle','betting','nextPre','bettingTotal'));
    }
    public function jetPrediction(){
        $pagetitle = "Jet Prediction";
        $nextPre = JetSet::first();

        $betting = JetBetting::where(['status'=> 'pending','period'=>$nextPre->period])->sum('amount');
        $bettingSuccess = JetBetting::where(['status'=> 'success','period'=>$nextPre->period])->sum('amount');
        return view('prediction.jet', compact('pagetitle','betting','nextPre','bettingSuccess'));
    }

}
