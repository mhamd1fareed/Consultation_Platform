<?php

namespace App\Http\Controllers;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Information;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function login(Request $request){
    
        $request->validate([
            'email'=>'required|email',
            'password'=>'required',
        ]);
        $user = User::where("email",$request->email)->first();
        if((isset($user->id))){
            if(Hash::check($request->password, $user->password)){
                $token = $user->createToken("passport_token")->accessToken;
                if($user->type=='expert'){
                    return response()->json([
                        "status"=>1,
                        "type"=>"expert",
                        "message"=>"logged in successfully!!",
                        "token"=>$token
                    ]);
                }
             return response()->json([
                    "status"=>1,
                    "type"=>"user",
                    "message"=>"logged in successfully",
                    "access_token" =>$token
                ]);
            }else{
                return response()->json([
                    "status"=>0,
                    "message"=>"password does not match"
                ]);
            }
        }
        else{
            return response()->json([
                "status"=>0,
                "message"=>"user not found"
            ]);

        }
    }
    public function register(Request $request){
        $request->validate([
            'name'=>'required',
            'email'=>'required|email|unique:users',
            'password'=>'required',
            'type'=>'required'
        ]);
        if($request->type!='user'&&$request->type!='expert'){
        return response()->json([
            "status"=>0,
            "message"=>"type required:(user_expert)",
        ]);}
        $user = new User();

        $user->name = $request->name; 
        $user->email = $request->email; 
        $user->type = $request->type; 
        $user->password = bcrypt( $request->password);; 

        $user->save();

       $token = $user->createToken("auth_token")->accessToken;

       $wallet = new Wallet();

       $wallet->money = 100000;
       $wallet->user_name = $user->name;

       $wallet->save();
       if($request->type=='expert'){
        $information = new Information();
        
        $information->expert_name = $user->name;
        $information->expert_id   = $user->id;

        $information->save();

        return response()->json([
            "status"=>1,
            "type"=>"expert",
            "message"=>"registered successfully!!",
            "token"=>$token
        ]);
       }


        return response()->json([
            "status"=>1,
            "type"=>"user",
            "message"=>"registered successfully!!",
            "token"=>$token
        ]);
 

}
public function logout(){
    $user = auth()->user()->token();
    $type = auth()->user()->type;
    $user->revoke();

    return response()->json([
        "status"=>1,
        "data"=>$type,
        "message"=>"Successfully user logged out"
    ]);
}
}
