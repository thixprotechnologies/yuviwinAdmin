<?php

namespace App\Http\Controllers;

use App\Models\Complain;
use Illuminate\Http\Request;

class ComplainsController extends Controller
{
    public function index(){
        $pagetitle = 'Complains List';
        $complains = Complain::paginate(10);
        return view('complains.index',compact('pagetitle','complains'));
    }
    public function update(Request $request){
        $request->validate([
            'id'=>'required',
            'status'=>'required|in:2,0',
            'response'=>'required|string'
        ]);
        $complain = Complain::find($request->id);
        if($complain){
            $complain->status = $request->status;
            $complain->response = $request->response;
            $complain->save();
            return response()->json([
                'status'=>false,
                'message'=>'Complain Updated successfully.'
            ]);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>'Invalid Complain Details'
            ]);
        }
    }
}
