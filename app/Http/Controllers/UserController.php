<?php

namespace App\Http\Controllers;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Information;
use App\Models\Appointment;
use App\Models\Time;
use App\Models\Wallet;
use App\Models\Review;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class UserController extends Controller
{
    public function showAll(){
        $user = auth()->user();
        $informations = Information::get();
        $i=0;
        while(isset($informations[$i])){
            $x  =$informations[$i]['expert_id'];
            $informations[$i]['rating'] = $this->getReview($x);
            $informations[$i]['favourites'] = $this->countFavourites($x);   
            $i++;

        }
        return response()->json([
            "experts"=>$informations,
        ]);
    }
    public function countFavourites($id){
        $x = Wishlist::where(['expert_id'=>$id])->get()->count();
        return $x;
    }
    public function getReview($id)
    {
        $review = Review::where('expert_id',$id);
        $averageStars= number_format($review->avg('stars'), 1);
        return $averageStars;      
    }
    public function getIfReview($id)
    {
        $user = auth()->user();
        $review = Review::where(['expert_id'=>$id,'user_id'=>$user->id]);
        if(isset($review->id)){
            return true;
        }
        return false;      
    }
    public function getImage($id){
        $expert = Information::where('expert_id',$id)->first();
        $url = $expert->image_url;
        return $url;/*response()->json([
            "status"=>1,
            "data"=>$url
        ]);*/
    }
    /*public function profile(){
        $user= auth()->user();
        $wallet = $this->getWallet($user->name);
        if($user->type=='expert'){
            $information = Information::where('expert_id',$user->id)->first();
            return response()->json([
                "status"=>1,
                "message"=>"good",
                "expert:"=>$information,
                "wallet"=>$wallet
            ]);

        } */
        public function profile(){
            $user= auth()->user();
            $wallet = $this->getWallet($user->name);
            if($user->type=='expert'){
                $information = Information::where('expert_id',$user->id)->first();
                return response()->json([
                    "status"=>1,
                    "message"=>"good",
                    "expert:"=>$information,
                    "wallet"=>$wallet
                ]);
    
            }
            return response()->json([
                "status"=>1,
                "message"=>"good",
                "data"=>$user,
                "wallet"=>$wallet
            ]);
        }
    
    public function logout(){
        auth()->user()->tokens()->delete();
        return response()->json([
            "status"=>1,
            "message"=>"Successfully user logged out"
        ]);
    }
    public function getConsultation($type){
        $list = Information::where('consultation_type',$type)->get(['expert_name']);
        
        return response()->json([
            "status"=>1,
            "data"=>$list,
        ]);      
    }
    public function getGuard()
    {
        $user = auth()->user();
        $guard = auth()->user()->name; 
        return response()->json([
            "status"=>1,
            "guard"=>$guard
        ]);   
    }
    public function search($name){

        $information = Information::where('expert_name',$name)->first();

        if(isset($information->id)){
            return response()->json([
                "status"=>1,
                "expert"=>$information
            ]);
        }
        $elements = DB::select("SHOW COLUMNS FROM `informations` WHERE Field = 'consultation_type'");
        foreach ($elements as $element) {
            $enum_values = $element->Type;
        }
        preg_match_all("/'([^']+)'/", $enum_values, $matches);

        $enum_array = $matches[1];

        for($x=0;$x<count($enum_array);$x++){
            if($name==$enum_array[$x]){
                $list = Information::where('consultation_type',$name)->get(['expert_name']);
              
                return response()->json([
                    "status"=>1,
                    "data"=>$name,
                    "experts:"=>$list
                ]);

            }
        }    
        return response()->json([
            "status"=>0,
            "message"=>"data not found"
        ]);

    }
    public function getExpertInfo($name){

        $information = Information::where('expert_id',$id)->first(); 
        if($information){
           return response()->json([
              "status"=>1,
              "expert"=>$information
          ]);
        }else{ 
            return response()->json([
            "status"=>0,
            "message"=>"not found"
        ]);}
    
    }
    public function getWallet($name){
        $wallet = Wallet::where('user_name',$name)->first();

        if(isset($wallet->id)){
            return $wallet->money;

        }
    }
}
