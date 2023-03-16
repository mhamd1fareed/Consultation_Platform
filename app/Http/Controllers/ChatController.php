<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Message;
use App\Models\Room;
use App\Events\SendMessage;
class ChatController extends Controller
{
    /**
     * save message
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveMessage(Request $request){
        $roomId = $request->roomId;
        $message = $request->message;
        $userId = auth()->user()->id;

        broadcast(new SendMessage($roomId,$message,$userId));

        Message::create([
            "room_id" => $roomId,
            "user_id" => $userId,
            "message" => $message
        ]);

        return response()->json([
            "success"=>true,
            "message"=>"Message success stored"
        ]);
    }

     /**
     * save chat
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function loadMessage($roomId){
        $message = Message::where("room_id",$roomId)->orderBy("updated_at","asc")->get(['message']);
        return response()->json([
            "success"=>true,
            "data"=>$message
        ]);
    }
    public function loadAllRoom(){
        $id = auth()->user()->id;
       // $rooms = Room::where();
        $x = 0;
        $rooms = Room::all();
        
        /*while(){

        }*/
        $room = Room::where("users",$me.":".$friend)
        ->orWhere("users",$friend.":".$me)->get();
        return response()->json([
            "status"=>1,
            "data"=>$rooms
        ]);
    }
}
