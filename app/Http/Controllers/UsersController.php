<?php

namespace App\Http\Controllers;

use App\Models\CircleBetting;
use App\Models\FastParityBetting;
use App\Models\JetBetting;
use App\Models\ParityBetting;
use App\Models\Ranks;
use App\Models\Recharge;
use App\Models\Transaction;
use App\Models\Setting;
use App\Models\User;
use App\Models\Admin;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    public function index(Request $request){
        $pagetitle = "Users List";
        $role = Auth::guard('admin')->user()->role;
        if($role==3)
        {
        $ref = Auth::guard('admin')->user()->referal;
        $users = User::query()->where('refcode',$ref);
        }
        else
        {
        $users = User::query();
        }
        if($request->username){
            $users->where('username','like',$request->username.'%');
        }
        $users =$users->where('status',0)->paginate(10);
        return view('users.index',compact('pagetitle','users'));
    }
    public function info($id){
        $user = User::find($id);
        if($user){
            $fast = FastParityBetting::where('username',$user->username)->count();
            $parity = ParityBetting::where('username',$user->username)->count();
            $jet =JetBetting::where('username',$user->username)->count();
            $circle = CircleBetting::where('username',$user->username)->count();
            $pagetitle = ($user->name!="") ? $user->name." Information" : $user->nickname." Information";
            $battingCount = $fast+$parity+$jet+$circle;
            $rechargeRecord = Recharge::where('username',$user->username)->orderby('created_at','desc')->get();
            $withdrawRecord = Withdrawal::where('username',$user->username)->orderby('created_at','desc')->get();
            $withdraw = Withdrawal::where('username',$user->username)->where('status','=','Success')->sum('withdraw');
            $deposit = Recharge::where('username',$user->username)->where('status','=','Success')->sum('recharge');
            return view('users.info',compact('pagetitle','fast','parity','jet','circle','battingCount','rechargeRecord','withdrawRecord','user','withdraw','deposit'));
        }else{
            $notify[] = ['error','User Not found'];
            return back()->withNotify($notify);
        }

    }
    public function agents(Request $request){
        $pagetitle = "Agents List";
        $ref = Admin::query()->where('role','3')->pluck('referal');
        $users = User::query()->whereIn('usercode',$ref)->get();
        // dd($users);
        // $totalusers=User::query()->where('refcode',$ref)->count();
        // dd($totalusers);
        // $users =$users->where('status',0)->paginate(10);
        return view('agent.index',compact('pagetitle','users'));
    }
    public function add_agent(){
        $pagetitle = "Add Agent";
        return view('agent.add',compact('pagetitle'));
    }
    public function addagent(Request $request){
        $request->validate([
            "role" => "required",
            "username" => "required|regex:/^[6789]\d{9}$/|unique:admins,email",
            "password" => "required|min:6",
            "ConfirmPassword" => "required|same:password",
        ]);
        if($request->role==3)
        {
        $name='Agent';
        }
        else
        {
        $name='Accountent';  
        }
        $user = User::where('username',$request->username)->first();
        if(!$user)
        {
        $user = new User();
        $user->username = $request->username;
        $user->mobile = $request->username;
        $user->name =$name;
        $user->is_verified = 1;
        $user->password = Hash::make($request->password);
        $user->save();
        $user = User::where('username',$request->username)->first();
        }
        
        $agent = new Admin();
        $agent->name = $name;
        $agent->email = $request->username;
        $agent->role = $request->role;
        $agent->referal = $user->usercode;
        $agent->password = Hash::make($request->password);
        if($agent->save()){
            $notify[] = ['success','New User Added'];
            if($request->role==3)
            {
            return redirect()->route('admin.agents')->withNotify($notify);
            }
            else{
                return redirect()->route('admin.accountent')->withNotify($notify);   
            }
        }else{
            $notify[] = ['error','Error in Add New User'];
            return back()->withNotify($notify);
        }
    }
    public function updateAccountent(Request $request){
        $request->validate([
            "username" => "required",
            'name'=>'required|string',
        ]);
        $user = Admin::where('id',$request->username)->first();
        if($user){
           
            $user->name  = $request->name;
            $user->save();
            return response()->json([
                'status' => true,
                'message' => 'Name updated',
            ]);
        }else{
            return response()->json([
                'status' => false,
                'message' => 'User Not found.',
            ]);
        }

    }

    public function accountent(Request $request){
        $pagetitle = "Accountent List";
        $agents = Admin::query()->where('role','4')->get();
        return view('accountent.index',compact('pagetitle','agents'));
    }

    public function add(){
        $pagetitle = "Add User";
        return view('users.add',compact('pagetitle'));
    }
    public function updateUser(Request $request){
        $request->validate([
            "username" => "required|regex:/^\d{10}$/",
            "balance" => "nullable|numeric",
            // "bonus" => "nullable|numeric",
            'reason'=>'required|string',
        ]);
        $user = User::where('username',$request->username)->first();
        if($user){
            if($request->reason == 'Bombwin Bonus'){
                if(isset($request->balance)){
                    $user->bonus =  $user->bonus + $request->balance;
                    $trans = new Transaction();
                    $trans->username = $user->username;
                    $trans->reason = 'Admin Bonus';//$request->reason;
                    $trans->amount = $request->balance;
                    $trans->status = 1;
                    $res1 = $trans->save();
                }
            }
            if($request->reason == 'Bombwin Recharge'){
                if(isset($request->balance)){
                    $user->balance  = $user->balance+ $request->balance;
                    $trans = new Transaction();
                    $trans->username = $user->username;
                    $trans->reason = 'Admin Bonus';//$request->reason;
                    $trans->amount = $request->balance;
                    $trans->status = 1;
                    $res1 = $trans->save();
                    $recharge= new Recharge();
                    $recharge->username=$user->username;
                    $recharge->recharge=$request->balance;
                    $recharge->type=0;
                    $recharge->img='';
                    $recharge->status=2;
                    $recharge->status_str='Complete';
                    $recharge->upi='';
                    $recharge->utr='';
                    $recharge->rand='';
                    $recharge->save();
                }
            }
            $user->save();
            return response()->json([
                'status' => true,
                'message' => 'Balance and Bonus info updated',
            ]);
        }else{
            return response()->json([
                'status' => false,
                'message' => 'User Not found.',
            ]);
        }

    }
    public function addUser(Request $request){
        $request->validate([
            "username" => "required|regex:/^[6789]\d{9}$/|unique:users,username",
            "password" => "required|min:6",
            "ConfirmPassword" => "required|same:password",
        ]);

        $user = new User();
        $user->username = $request->username;
        $user->mobile = $request->username;
        $user->refcode = $request->ref;
        $user->is_verified = 1;
        $user->password = Hash::make($request->password);
        if($user->save()){
            $notify[] = ['success','New User Added'];
            return redirect()->route('admin.users')->withNotify($notify);
        }else{
            $notify[] = ['error','Error in Add New User'];
            return back()->withNotify($notify);
        }
    }
    public function ranks(){
        $pagetitle = "Ranks List";
        $autorank = Setting::first()->autoranks;
        $ranks = Ranks::paginate(10);
        return view('ranks.index',compact('ranks','pagetitle','autorank'));
    }
    public function ranksAuto(){
        $autorank = Setting::first();
        $autorank->autoranks = ($autorank->autoranks == 1) ? 0 : 1 ;
        $autorank->save();
        return response()->json([
            'status'=>true,
            'message'=>'Ranks Status changed for auto generate'
        ]);
    }
    public function ranksAdd(Request $request){
        $request->validate([
            'username'=>'required|regex:/^\d{10}$/',
            'rank'=>'required',
            'amount'=>'required'
        ]);
        $old = Ranks::where('ranks',$request->rank)->first();
        if($old){
            $old->username = $request->username;
            $old->ranks = $request->rank;
            $old->amount = $request->amount;
            $old->save();
            return response()->json([
                'status'=>true,
                'message'=>'Ranks Added at provided details.'
            ]);
        }else{
            $rank = new Ranks();
            $rank->username = $request->username;
            $rank->ranks = $request->rank;
            $rank->amount = $request->amount;
            $rank->save();
            return response()->json([
                'status'=>true,
                'message'=>'Ranks Added at provided details.'
            ]);
        }
    }
    public function ranksUpdate(Request $request){
        $request->validate([
            'id'=>'required',
            'username'=>'required|regex:/^\d{10}$/',
            'rank'=>'required',
            'amount'=>'required'
        ]);
        $oldrank = Ranks::where('id',$request->id)->first();
        if($oldrank){
            $old = Ranks::where('ranks',$request->rank)
            ->where('username', '!=', $oldrank->username)
            ->first();
            if($old){
                // $old->username = $request->username;
                $old->ranks = $oldrank->ranks;
                $oldrank->ranks = $request->ranks;
                // $old->amount = $request->amount;
                $old->save();
                $oldrank->save();
                return response()->json([
                    'status'=>true,
                    'message'=>'Ranks Added at provided details.'
                ]);
            }else{
                $oldrank->username = $request->username;
                $oldrank->ranks = $request->rank;
                $oldrank->amount = $request->amount;
                $oldrank->save();
                return response()->json([
                    'status'=>true,
                    'message'=>'Ranks Added at provided details.'
                ]);
            }
        }else{
            return response()->json([
                'status'=>false,
                'message'=>'details not found.'
            ]);
        }
    }
    public function gameRecord(Request $request){
        $request->validate([
            'type'=>['in:0,1,2,3,4'],
            'username'=>['numeric','nullable']
        ]);
        $pagetitle = 'Game Records Users';
        if($request->type == 1){
            $records = FastParityBetting::query();
            if($request->username){
                $records->where('username',$request->username.'%');
            }
           $records = $records->orderby('id','DESC')->paginate(10);
        }else if($request->type == 2){
            $records = ParityBetting::query();
            if($request->username){
                $records->where('username',$request->username.'%');
            }
            $records = $records->orderby('id','desc')->paginate(10);
        }else if($request->type == 3){
            $records = JetBetting::query();
            if($request->username){
                $records->where('username',$request->username.'%');
            }
            $records = $records->orderby('id','desc')->paginate(10);
        }else if($request->type == 4){
            $records = CircleBetting::query();
            if($request->username){
                $records->where('username',$request->username.'%');
            }
            $records = $records->orderby('id','desc')->paginate(10);
        }else{
            $records = Transaction::query();
            if($request->username){
                $records->where('username',$request->username.'%');
            }
            $records = $records->orderby('id','desc')->paginate(10);
        }
        return view('gameRecord.index',compact('pagetitle','records'));
    }
}
