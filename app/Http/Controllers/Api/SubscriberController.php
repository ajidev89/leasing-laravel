<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

class SubscriberController extends Controller
{
    public function subscribe(Request $request){
        $validator = Validator::make($request->all(),[ 
            'email' => 'required|string|email|max:255|unique:users',
        ]);
        // Check validation failure
        if ($validator->fails()) {
            $errors = $validator->errors()->getMessages();
            return response()->json(['code'=>'1500','message'=>"Cannot subscribe to our service",'error'=>$errors]);
        }

        $subscribe = new Subscriber ();
        $subscribe->email = $request->email; 
        $subscribe->active = 1;
        $subscribe->save();
       return response()->json(['code'=>'1000','message'=>"Sucessfully subscribe to our newsletter"]);
    } 
    public function unsubscribe(Request $request){
        $validator = Validator::make($request->all(),[ 
            'email' => 'required|string|email|max:255|unique:users',
        ]);
        // Check validation failure
        if ($validator->fails()) {
            $errors = $validator->errors()->getMessages();
            return response()->json(['code'=>'1500','message'=>"Cannot unsubscribe to our Newsletter",'error'=>$errors]);
        }

        $subscribe = new Subscriber ();
        $subscribe->email = $request->email; 
        $subscribe->active = 0;
        $subscribe->save();
       return response()->json(['code'=>'1000','message'=>"Sucessfully subscribe to our newsletter"]);
    } 
}
