<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Information;
use App\Models\Appointment;
use App\Models\Time;
use App\Models\Wishlist;
class WishlistController extends Controller
{
    
    public function addExpert($id){
        $user = auth()->user()->id;
       // $expert = User::where('id',$id)->first();
       $status = Wishlist::where(['user_id'=>$user,'expert_id'=>$id] )
       ->where('expert_id',$id)
       ->first();
       if(isset($status->id)){
        return response()->json(['message'=>'You Already add this expert'],403);

    }
        $wishlist = new Wishlist();

        $wishlist->user_id  =$user; 
        $wishlist->expert_id  =$id;
        $wishlist->like = true;

        $wishlist->save();
        return response()->json([
            "status"=>1,
            "message"=>"expert added successfully"
        ]);

    }
    public function show(){
        $user = auth()->user();
        $wishlist = Wishlist::where(['user_id'=>$user->id])->get();

            return response()->json([
                "status"=>1,
                "data"=>$wishlist
             ]);
  
    }

}
