<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExpertController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\ReviewController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/*
about headers:
        1_ method has middleware so headers are :
                1) Accept
                2) Content-Type
                3) Authorization:which mean you must to pass [Bearer token]
        2_ method does not have middleware so headers are :
                1) Accept
                2) Content-Type
                
 */
Route::post('add_image',[ExpertController::class,'setImage']);
//connecting here
Route::post('login',[LoginController::class,'login']);//login

Route::post('register',[LoginController::class,'register']);//register
                                 //data required:                                                            
                                /* 'name'=>'required',
                                   'email'=>'required|email|unique:users',
                                   'password'=>'required',
                                   'type'=>'required'
 
                                   */
Route::middleware('auth:api')
        ->post('send/{id}',[WishlistController::class,'saveMessage']);
Route::middleware('auth:api')
        ->post('rooms',[WishlistController::class,'saveMessage']);
       
Route::middleware('auth:api','access.controll')
        -> put('add_info',[ExpertController::class,'setInformation']);//expert set his information
        
Route::get('get_image/{id}',[ExpertController::class,'getImage']);//get expert image by expert_id
Route::get('search/{name}',[UserController::class,'search']);//search about name on experts
                                                             //then if false it will go to consultations
Route::get('show_experts',[UserController::class,'showAll']);//show all experts 
Route::get('get_expert/{name}',[UserController::class,'getExpertInfo']);//show expert's information by expert_name
Route::middleware('auth:api')->get('getg',[UserController::class,'getGuard']);//get one Consultation with experts by Consultation name
Route::get('get_appointments/{id}',[AppointmentController::class,'getAppointments']);//show one expert's available days by expert_id
Route::get('get_times/{id}',[AppointmentController::class,'getTimes']);//show one expert day available times by appointment_id
Route::middleware('auth:api')-> get('logout',[LoginController::class,'logout']);//logout
Route::middleware('auth:api')-> get('profile',[UserController::class,'profile']);//user profile
Route::middleware('auth:api')->
         put('book/{id}',[AppointmentController::class,'bookAppointment']);//book appointment with expert by time_id
Route::middleware('auth:api','access.controll')
        -> post('add_appointment',[AppointmentController::class,'addAppointment']);//expert inserts his available day
                                //and it will automaticaly inserts times between first_day and end_day
                                //data required:
                               /* 'first_time'=>'required|date_format:H:i:s',
                                   'end_time'=>'required|date_format:H:i:s',
                                   'first_day'=>'required|date_format:D',
                                   'end_day'=>'required|date_format:D',
                                   'price'=>'required' */
Route::middleware('auth:api')
        ->post('add_expert/{id}',[WishlistController::class,'addExpert']);//add expert to wishlist by expert_id  
Route::middleware('auth:api','access.controll')
        -> get('show_appointments',[AppointmentController::class,'showFalseTimes']);//expert can show his booked up times   

                         //data required:
                        /* 'image_url'=>'mimes:jpg,jpeg,png,doc,docx,pdf,txt,csv|max:2048',
                           'experiences'=>'required',
                           'contact_info'=>'required',
                           'consultation_type'=>'required' */
Route::get('count_fav/{id}',[UserController::class,'countFavourites']);//Calculating the number of expert's follow-ups by expert_id
Route::middleware('auth:api')
        ->post('review',[ReviewController::class,'store']);
Route::get('index/{id}',[ReviewController::class,'index']);
Route::middleware('auth:api')
                ->get('wishlist',[WishlistController::class,'show']);