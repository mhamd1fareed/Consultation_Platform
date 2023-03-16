<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Information;
use App\Models\Appointment;
use App\Models\Time;
use App\Models\Review;
class ReviewController extends Controller
{
    public function index($id)
    {
        $review = Review::where('expert_id',$id);
        $averageStars= number_format($review->avg('stars'), 1);
        return response()->json([        
            'avgerage'=>$averageStars
        ]);      
    }
    public function store(Request $request){
        $user = auth()->user();
        $status = Review::where(['user_id'=>$user->id,'expert_id'=>$request->expert_id] )->first();
        
            if(isset($status->id)){
            return response()->json(['message'=>'You have already reviewed this expert'],403);

         }


    if($request->stars > '5'){
        return response()->json([
            'message'=>'5 star or less'
        ],403);

    }
    if($request->stars < '1'){
        return response()->json([
            'message'=>'1 star or more'
        ],403);

    }
    $request->validate([
        'expert_id'=>'required|integer',
        'stars'=>'required|integer',
    ]);
                $review = new Review();
                
                $review->stars = $request->stars;
                $review->user_id = $user->id;
                $review->expert_id = $request->expert_id;

                $review->save();
                return response()->json([
                    'message'=>'Review Added',
                     'review'=>$review
                    ]);
    } 
}
