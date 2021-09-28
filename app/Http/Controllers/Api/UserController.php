<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;

class UserController extends Controller
{

    public function Register(Request $request){
        $validator = Validator::make($request->all(),[ 
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' =>'required|string|min:8',
        ]);
       // Check validation failure
        if ($validator->fails()) {
            $errors = $validator->errors()->getMessages();
            return response()->json(['code'=>'1500','message'=>"Cannot create user",'error'=>$errors]);
        }
        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password); 
        $user->save();
       return response()->json(['code'=>'1000','message'=>"Sucessfully created user"]);
    }
    public function Login(Request $request){
        //Validating boxes   
        $validator = Validator::make($request->all(),[ 
            'email' => 'required|string|email|max:255|exists:users',
            'password' =>'required|string|min:8',
        ]);
       // Check validation failure
        if ($validator->fails()) {
            $errors = $validator->errors()->getMessages();
            return response()->json(['code'=>'1500','message'=>"Cannot login",'error'=>$errors]);
        }
        $email = $request->email;
        $password = $request->password; 
        //Get user
        $user = User::select('*')->where('email',$email)->first();
        $login = Hash::check($password, $user->password);
        if ($login) {
            $token = $user->createToken($email)->plainTextToken;
            return response()->json(['code'=>'1000','user'=>$user,'token'=>$token]);
        }else{
             return response()->json(['code'=>'1500','message'=>'These credentials do not match our records.']);
        }

    }
    public function getUser(Request $request){
        return response()->json(['user' => $request->user()]);
    }
    public function Logout(Request $request){

        $request->user()->currentAccessToken()->delete();
        
        return response()->json(['message' => 'Successfully logout']);
    }
   
    public function ForgetPassword(Request $request)
    {
        $validator = Validator::make($request->all(),[ 
            'email' => 'required|string|email|max:255|exists:users',
        ]);
        // Check validation failure
        if ($validator->fails()) {
            $errors = $validator->errors()->getMessages();
            return response()->json(['code'=>'1500','message'=>"Could not send reset link ",'error'=>$errors]);
        }
        //Send Email
        $status = Password::sendResetLink($request->only('email'));
        
        if ($status === Password::RESET_LINK_SENT) {
            return response()->json(['code'=>'1000','message'=> 'We have emailed your password reset link!']);
        }else {
        return response()->json(['code'=>'1500','message'=> $status /*'We could not send reset link please try again'*/]);
        }
    
    }
    
    public function ChangePassword(Request $request)
    {
        $validator = Validator::make($request->all(),[ 
            'email' => 'required|string|email|max:255|exists:users',
            'password' => 'required|min:8|confirmed',
            'token' => 'required',
        ]);

         // Check validation failure
        if ($validator->fails()) {
            $errors = $validator->errors()->getMessages();
            return response()->json(['code'=>'1500','message'=>"We could not change your password please try again",'error'=>$errors]);
        }
        $status = Password::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function ($user, $password) {
                    $user->forceFill([
                        'password' => Hash::make($password)
                    ])->setRememberToken(Str::random(60));

                    $user->save();
                    event(new PasswordReset($user));
                }
            );
        if ($status === Password::PASSWORD_RESET) {
             return response()->json(['code'=>'1000','message'=> 'Your password has been sucessfully changed']);    
        }else {
             return response()->json(['code'=>'1500','message'=> 'We could not change your password please try again']);            
        }   
    }
    public function NoGet(){
         return response()->json(['code'=>'1500','message'=>'Cannot perform get request']);
    }
}
