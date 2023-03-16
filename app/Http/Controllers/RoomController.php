<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;

class RoomController extends Controller
{
    /**
     * create room
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request){
        $me = auth()->user()->id;
        $friend = $request->friend_id;

        $room = Room::where("users",$me.":".$friend)
                    ->orWhere("users",$friend.":".$me)
                    ->first();
        if($room){
            $dataRoom = $room;
        }else{
            $dataRoom = Room::create([
                "users"=>$me.":".$friend
            ]);
        } 
            return response()->json([
                "success"=>true,
                "data"=>$dataRoom
            ]);
           
    }
}
