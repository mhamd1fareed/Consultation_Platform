<?php

namespace App\Http\Controllers;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

use App\Models\Appointment;
use App\Models\Time;
use App\Models\Information;
use App\Models\User;
use Carbon\Carbon;
use App\VSE;

use Illuminate\Support\Facades\Hash;
class ExpertController extends Controller
{

    
    public function getInfo(){
        return response()->json([
            "status"=>1,
            "message"=>"good",
            "data"=>auth()->user()
        ]);
    }
    public function getImage($id){
        $expert = Information::where('expert_id',$id)->first();
        $url = $expert->image_url;
        return response()->json([
            "status"=>1,
            "data"=>$url
        ]);
    }
    public function logout(){
        auth()->user()->tokens()->delete();
        return response()->json([
            "status"=>1,
            "message"=>"Successfully expert logged out"
        ]);
    }
    public function setImage(Request $request){
        //$id=auth()->user()->id;

        $information = Information::where("expert_id",3)->first();

 
        $image_url = 'image_url'.time().'.'.$request->image_url->extension();
        $request->image_url->move(public_path('uploads/experts_images'),$image_url);
        $url = storage_path('public\experts_images\\' . $image_url);
        $information->image_url = "http://127.0.0.1:8000/uploads/experts_images/".$image_url;
              
        $information->save();

        return response()->json([
            "status"=>1,
            "message"=>"Updated successfully!!",
        ]);
    }
    public function setInformation(Request $request){

        $request->validate([
            //'image_url'=>'required|mimes:jpg,jpeg,png,doc,docx,pdf,txt,csv|max:2048',
           'experiences'=>'required',
            'contact_info'=>'required',
            'consultation_type'=>'required'
        ]);

        $id=auth()->user()->id;

        $information = Information::where("expert_id","=",$id)->first();

        $information->experiences = $request->experiences;   
        if(isset($request->image_url)){
            $image_url = 'image_url'.time().'.'.$request->image_url->extension();
            $request->image_url->move(public_path('uploads/experts_images'),$image_url);
            $url = storage_path('public\experts_images\\' . $image_url);
            $information->image_url = "http://127.0.0.1:8000/uploads/experts_images/".$image_url;
        }

        $information->contact_info = $request->contact_info;
        $information->consultation_type = $request->consultation_type;
        
        $information->save();
        

        return response()->json([
            "status"=>1,
            "message"=>"Updated successfully!!",
        ]);
    }
   
}