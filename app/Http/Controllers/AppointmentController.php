<?php

namespace App\Http\Controllers;
use Auth;

use App\Models\Appointment;
use App\Models\Information;
use App\Models\User;
use App\Models\Wallet;
use Carbon\Carbon;
use App\Models\Time;
use App\VSE;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
class AppointmentController extends Controller
{
 
    public function showFalseTimes(){
        $id = auth()->user()->id;
        $times = Time::where(['expert_id'=>$id,'state'=>false])->get();

        return response()->json([
            "status"=>1,        
            "data"=>$times
        ]);
    }
    public function bookAppointment($id){

        $user_name = auth()->user();
        $name = $user_name->name; 
        $time = Time::where('id',$id)->first();
        if($time->state == false){
            return response()->json([
                "status"=>0,
                "message"=>"the time has been token!!??"
            ]);
        }
        $id = $user_name->id;
        
        $price = $time->price; 
        $sell = Wallet::where('user_name',$name)->first();
        if($sell->money<$price){
            return response()->json([
                "status"=>0,
                "message"=>"You do not have enough money!!??"
            ]);
        }
        $sell->money-=$price;
        $sell->save();
        $information = Information::where('expert_id',$time->expert_id)->first();
        $wallet = Wallet::where('user_name',$information->expert_name)->first();
        $wallet->money+=$price;
        $wallet->save();

         

   
        $time->state = false;
        $time->user_name = $name;
        $time->save();

        return response()->json([
            "status"=>1,
            "message"=>"time booked successfully!"
        ]);

    }
    public function addAppointment(Request $request){
        $request->validate([
              'first_time'=>'required|date_format:H:i:s',
              'end_time'=>'required|date_format:H:i:s',
              'first_day'=>'required|date_format:D',
              'end_day'=>'required|date_format:D',
              'price'=>'required'
          ]);
        $id = auth()->user()->id;
        $exp = Appointment::where("expert_id",$id)->first();
        if(isset($exp->id)){
            return response()->json([
                "status"=>0,
                "message"=>"Dear,you have already added your appointments!"
            ]);           
        }
        $fi_ti = (new Carbon($request->first_time));        
        $en_ti =(new Carbon($request->end_time));
        $fi_da = (new Carbon($request->first_day));
        $en_da =(new Carbon($request->end_day));

        $today = Carbon::today();
        $a = (new Carbon($request->first_day));
        $b = (new Carbon($request->end_day));
                
        $d =(new Carbon($request->end_time));
          if($a->format('D')==$today->format('D')){
            $a->addDays(7);
            $b->addDays(7);

          }
               
          if($b->format('D')==$today->format('D')){
            $t+=2;
            $b->addDays(7);
          }
              
        for($y=0;$y<1;$y++){
          $e = (new Carbon($a));
            for($x=0;$x<7;$x+=1){
            $c = (new Carbon($request->first_time));
              if($e<=$b&&$e>=$a){
                
            $appointment = new Appointment();
        
            $appointment->first_time = (new Carbon($fi_ti))->format('H:i:s');
            $appointment->end_time = (new Carbon($en_ti))->format('H:i:s');
            $appointment->date = (new Carbon($e));
            $appointment->day = $e->format('D');
            $appointment->expert_id = auth()->user()->id;
        
            $appointment->save();
            while($c<$d){
                $time = new Time();

                $time->start=(new Carbon($c))->format('H:i:s');
                $c->addMinutes(30);
                $time->finish=(new Carbon($c))->format('H:i:s');
                $time->day=$e->format('D');
                $time->appointment_id=$appointment->id;
                $time->price=$request->price;
                $time->user_name="";
                $time->expert_id = auth()->user()->id;
                $time->date= (new Carbon($e));
                $time->save();

            }

            $e->addDay(1);
        }       
        }
        $a->addDays(7);
        $b->addDays(7);
        
   }



        return response()->json([
            "status"=>1,
            "message"=>"Your appointments was added at times have you inputted Successfully"
        ]);
    }
    public function getAppointments($id){

        $appointments = Appointment::where(['expert_id'=>$id])->get();
        return response()->json([
            "status"=>1,        
            "data"=>$appointments
        ]);
    }
    public function getTimes($id){

        $times = Time::where([
            'appointment_id'=>$id,
            'state'=>true
        ])->get();
        return response()->json([
            "status"=>1,
            "data"=>$times
        ]);
    }
}
