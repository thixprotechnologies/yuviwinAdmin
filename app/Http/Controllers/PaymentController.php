<?php

namespace App\Http\Controllers;

use App\Models\BankDetails;
use App\Models\Recharge;
use App\Models\User;
use App\Models\Withdrawal;
use Illuminate\Http\Request;

class PaymentController extends Controller
{

    public function Withdrawal()
    {
        $pagetitle = 'Withdrawal';
        $withdrawRecord = Withdrawal::orderby('created_at', 'desc')->paginate(10);
        foreach ($withdrawRecord as $key => $value) {
            $value->bankinfo = BankDetails::where('id', $value->bankdetails)->first();
        }
        return view('payment.withdraw', compact('pagetitle', 'withdrawRecord'));
    }
    public function recharge(Request $request)
    {
        $pagetitle = 'Recharge';
        $rechargeRecord = Recharge::query();
        if ($request->username) {
            $rechargeRecord->where('username', 'like', $request->username . '%');
        }
        if ($request->tnx_id) {
            $rechargeRecord->where('rand', 'like', $request->tnx_id . '%');
        }
        $rechargeRecord = $rechargeRecord->orderby('created_at', 'desc')->paginate(10);
        return view('payment.rechrage', compact('pagetitle', 'rechargeRecord'));
    }
    public function rechargeStatus(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'status' => ['required', 'in:2,4']
        ]);
        $recharge = Recharge::where('id', $request->id)->first();
        if ($recharge) {
            if ($request->status == 2) {
                $user = User::where('username', $recharge->username)->first();
                if ($user) {
                    $user->balance = $user->balance + $recharge->recharge;
                    $user->is_recharge = 1;
                    $user->save();
                }
                $recharge->status = 2;
                $recharge->status_str = 'Complete';
                $recharge->save();

                return response()->json([
                    'status' => true,
                    'message' => 'Payment Marked As Complete.',
                ]);
            } else if ($request->status == 4) {
                $recharge->status = 4;
                $recharge->status_str = 'Rejected';
                $recharge->save();

                return response()->json([
                    'status' => true,
                    'message' => 'Payment Rejected Successfully.',
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Unknown Method.',
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'somthing went Wrong.',
            ]);
        }
    }
    public function withdrawStatus(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'status' => ['required', 'in:2,4']
        ]);
        $withdraw = Withdrawal::where('id', $request->id)->first();
        if ($withdraw) {
            if ($request->status == 2) {
                $withdraw->status = 'Complete';
                $withdraw->save();
                return response()->json([
                    'status' => true,
                    'message' => 'Withdraw Marked As Complete.',
                ]);
            } else if ($request->status == 4) {
                $user = User::where('username', $withdraw->username)->first();
                if ($user) {
                    if ($withdraw->type == 'Withdrawal') {
                        if ($withdraw->withdraw < 1500) {
                            $amt = $withdraw->withdraw + 30;
                        } else {
                            $amt = $withdraw->withdraw * 2;
                        }
                        $user->balance = $user->balance + $amt;
                    } else {
                        $user->bonus = $user->bonus + $withdraw->withdraw;
                    }
                    $user->save();
                }
                $withdraw->status = 'Rejected';
                $withdraw->save();

                return response()->json([
                    'status' => true,
                    'message' => 'Withdraw Rejected Successfully.',
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Unknown Method.',
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'somthing went Wrong.',
            ]);
        }
    }
}
