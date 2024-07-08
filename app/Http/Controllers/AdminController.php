<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Recharge;
use App\Models\Setting;
use App\Models\User;
use App\Models\Withdrawal;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{

    public function index()
    {
        $pagetitle = "Dashboard";
        $role = Auth::guard('admin')->user()->role;
        $ref = Auth::guard('admin')->user()->referal;
        $u=User::query()->where('refcode',$ref)->pluck('username');
        if($role==3)
        {
        $Totalwithdraw = 0;
        $Todaywithdraw = 0;
        $Totaldeposit = 0;
        $Todaydeposit = 0;
        $Users = User::where('refcode',$ref)->where('status', 0)->count();
        foreach ($u as $u) {
        $Totalwithdraw += Withdrawal::where('username', $u)->where('status', '=', 'Complete')->sum('withdraw');
        $Todaywithdraw += Withdrawal::where('username', $u)->where('status', '=', 'Complete')->whereDate('created_at',Carbon::now())->sum('withdraw');
        $Totaldeposit += Recharge::where('username', $u)->where('status',2)->sum('recharge');
        $Todaydeposit += Recharge::where('username', $u)->where('status',2)->whereDate('created_at',Carbon::now())->sum('recharge');
        }
        }
        else{
        $Users = User::where('status', 0)->count();
        $Totalwithdraw = Withdrawal::where('status', '=', 'Complete')->sum('withdraw');
        $Todaywithdraw = Withdrawal::where('status', '=', 'Complete')->whereDate('created_at',Carbon::now())->sum('withdraw');
        $Totaldeposit = Recharge::where('status',2)->sum('recharge');
        $Todaydeposit = Recharge::where('status',2)->whereDate('created_at',Carbon::now())->sum('recharge');
        }
        return view('dashboard', compact('pagetitle', 'Users', 'Totalwithdraw','Todaywithdraw','Todaydeposit', 'Totaldeposit'));
    }
    public function invites()
    {
        $pagetitle = "Invite Records";
        return view('invites', compact('pagetitle'));
    }
    public function invitesRecords(Request $request)
    {
        $user = User::where('username', $request->username)->first();
        $usernames = User::where('refcode', $user->usercode)->pluck('username')->toArray();
        $recharges = Recharge::whereIn('username', $usernames)
            ->orderBy('created_at') // Assuming 'created_at' is the field indicating when the recharge was made
            ->select('username', 'recharge', 'status')
            ->get()
            ->groupBy('username')
            ->map(function ($group) {
                $firstSuccessRecharge = $group->firstWhere('status', 2);
                $totalRecharge = $group->where('status', 2)->sum('recharge');
                return [
                    'username' => $firstSuccessRecharge->username,
                    'first_recharge' => $firstSuccessRecharge ? $firstSuccessRecharge->recharge : null,
                    'total_recharge' => $totalRecharge,
                ];
            })
            ->values()
            ->toArray();
        $dataRow = '';

        foreach ($recharges as $recharge) {
            $total = round($recharge['total_recharge'], 2) ?: 0;
            $first = $recharge['first_recharge'] ?: 0;
            $date = User::where('username',$recharge['username'])->first();
            $dataRow .= "<tr><td>{$recharge['username']}</td><td>{$total}</td><td>{$first}</td><td>".Carbon::createFromFormat('Y-m-d H:i:s', $date->created_at)->format('d-m-Y')."</td></tr>";
        }
        return $dataRow;
    }
    public  function profileSettings(){
        $id = Auth::guard('admin')->user()->id;
        $admin = Admin::find($id);
        $pagetitle = "Profile Settings";
        return view('profile', compact('pagetitle','admin'));
    }
    public function profileUpdate(Request $request){
        $id = Auth::guard('admin')->user()->id;
        $admin = Admin::find($id);
        $request->validate([
            'name'=>"required",
            "email"=>"required|email|regex:/^\S+@\S+\.\S+$/"
        ]);
        $admin->name = $request->name;
        $admin->email = $request->email;
        if($request->password){
            $request->validate([
                "NewPassword" => "required|min:6",
                "ConfirmNewPassword" => "required|same:NewPassword",
            ]);
            $admin->password = Hash::make($request->password);
        }
        if($admin->save()){
            $notify[] = ['success','Profile Setting Updated'];
            return redirect()->route('admin.profile')->withNotify($notify);
        }else{
            $notify[] = ['error','Error Profile Updated'];
            return back()->withNotify($notify);
        }
    }
    public function upi(){
        $settings = Setting::first();
        $pagetitle = "Upi Settings";
        return view('settings.upi', compact('pagetitle','settings'));
    }
    public function upiAdd(Request $request){
        $request->validate([
            "phonepayUpi"=>"required|regex:'^[0-9A-Za-z.-]{2,256}@[A-Za-z]{2,64}$'",
            "PaytmUpi"=>"required|regex:'^[0-9A-Za-z.-]{2,256}@[A-Za-z]{2,64}$'",
            "gpayupi"=>"required|regex:'^[0-9A-Za-z.-]{2,256}@[A-Za-z]{2,64}$'"
        ]);
        $settings = Setting::first();
        $settings->upi = $request->phonepayUpi;
        $settings->upi1 = $request->PaytmUpi;
        $settings->upi2 = $request->gpayupi;
        if($settings->save()){
            $notify[] = ['success','Upi Setting Updated'];
            return redirect()->route('admin.upi')->withNotify($notify);
        }else{
            $notify[] = ['error','Error Upi Updated'];
            return back()->withNotify($notify);
        }
    }
}
