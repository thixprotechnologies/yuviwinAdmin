<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;


class AuthController extends Controller
{
    public function index(){
        return view('auth.login');
    }
    
    public function login(Request $request){
        $rules = [
            'email' => 'required',
            'password'  => 'required',
        ];
        $messages = [
            'email.required' =>'Email id required',
            'password.required' => 'Password required for login',
        ];
        $request->validate($rules, $messages);
        $result = Admin::where('email','=',$request->email)->first();
        if($result){
            if(Hash::check($request->password,$result->password)){
                Auth::guard('admin')->login($result);
                $notify[] = ['success','Login Successfully'];
                return redirect()->route('admin.home')->withNotify($notify);
            }else{
                $notify[] = ['error','Invalid Password'];
                return back()->withNotify($notify);
            }
        }else{
            $notify[] = ['error','Invalid User'];
            return back()->withNotify($notify);
        }
    }
    public function logOut()
    {
        Session::flush();
        Auth::logout();
        $notify[] = ['success','Logout Successfully'];
        return redirect()->route('admin.login')->withNotify($notify);
    }
}
