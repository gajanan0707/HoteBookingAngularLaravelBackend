<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use App\Models\contact;
use App\Models\subscribeUsers;
use App\Models\service;
use App\Models\roomBookingRequest;
use App\Models\room_type;
use App\Models\feedback_type;
use App\Models\feedback;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\User;

class FrontApi extends Controller
{
    //----------------------save Contact From----------------------------------------------------------------------
    public function save_contact_query(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
            'mobile_no' => 'required',
            'message' => 'required'
        ]);
        if ($validator->fails()) {
            return Response::json(['error' => $validator->errors()->all()], 409);
        }
        $check_status = contact::where('email', $request->email)->get()->toArray();
        if ($check_status) {
            return Response::json(['message' => "Email Already Exist"]);
        } else {
            $obj = new contact();
            $obj->name = $request->name;
            $obj->email = $request->email;
            $obj->mobile_no = $request->mobile_no;
            $obj->message = $request->message;
            $obj->save();
            $arr = array('status' => 'true', 'message' => "contact form submit");
        }
        echo json_encode($arr);
    }

    //----------------------Subscribe user----------------------------------------------------------------------

    public function subscribe_user(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
        ]);
        if ($validator->fails()) {
            return Response::json(['error' => $validator->errors()->all()], 409);
        }
        $check_status = subscribeUsers::where('email', $request->email)->get()->toArray();
        if ($check_status) {
            return Response::json(['message' => "Email Already Exist"]);
        } else {

            $obj = new subscribeUsers();
            $obj->email = $request->email;
            $obj->save();
            return Response::json(['message' => "Thanks For Subcribe"]);
        }
    }

    //----------------------get  service----------------------------------------------------------------------
    public function getService(Request $request)
    {
        $service = service::get()->toArray();
        if ($service) {
            $arr = array('status' => 'true', 'message' => 'sucess', 'data=' => $service);
        } else {
            $arr = array('status' => 'true', 'message' => 'Service Not Found');
        }
        echo json_encode($arr);
    }



    //----------------------save Room Booking Request----------------------------------------------------------------------
    public function room_booking_request(Request $request)
    {
        $validator = Validator::make($request->all(), ['name' => 'required', 'email' => 'required', 'mobile_no' => 'required']);
        if ($validator->fails()) {
            return Response::json(['error' => $validator->errors()->all()], 409);
        }

        $req = new roomBookingRequest();
        $req->name = $request->name;
        $req->email = $request->email;
        $req->mobile_no = $request->mobile_no;
        $req->address = $request->address;

        $req->from_date = $request->from_date;
        $req->to_date = $request->to_date;
        $req->no_of_member = $request->no_of_member;
        $req->no_of_rooms = $request->no_of_rooms;
        $req->room_type = $request->room_type;
        $req->statusBooking = "pending";
        $req->userId = $request->userId;
        $req->save();
        $arr = array('status' => 'true', 'message' => 'sucesss');

        echo json_encode($arr);
    }

     //Cancel Booking  controller
     function cancelBooking(Request $request, $id)
     {
         $id = $request->id;
         $empT = new roomBookingRequest;
         $empT->where('id', $id)->delete();
     }

      //Get One Booking Controller
    function getOneBookingRequest(Request $request,$id){
        $user = DB::table('room_booking_requests')->where('id',$id )->first();
        return response()->json(["data"=> $user]); 
    }

     //update updateBookingRequest  controller
     function updateBookingRequest(Request $request,$id)
     {
         $id=$request->id;
         $empT = new roomBookingRequest;
         $empT->where('id',$id)->update([
             'name' => $request->name,
             'email' => $request->email, 
             'mobile_no'=>$request->mobile_no,
             'address'=>$request->address,
             'from_date'=>$request->from_date,
             'to_date'=>$request->to_date,
             'no_of_member'=>$request->no_of_member,
             'no_of_rooms'=>$request->no_of_rooms,
             ]);
     }
 

    //----------------------get Room Type ----------------------------------------------------------------------
    public function get_room_type(Request $request)
    {
        $room_type = room_type::select(['id', 'titile'])->where('status', '1')->get()->toArray();
        if ($room_type) {
            $arr = array('status' => 'true', 'message' => 'sucess', 'data' => $room_type);
        } else {
            $arr = array('status' => 'false', 'message' => 'Room Type Not Found');
        }

        echo json_encode($arr);
        
    }

    //---------------------- feedback Type----------------------------------------------------------------------

    public function feedback_type(Request $request)
    {
        $feedback_type = feedback_type::select(['id', 'title'])->where('status', '1')->get()->toArray();
        if ($feedback_type) {
            $arr = array('status' => 'true', 'message' => 'sucess', 'data' => $feedback_type);
        } else {
            $arr = array('status' => 'false', 'message' => 'Feedback Type Not Found');
        }

        echo json_encode($arr);
    }


    //----------------------save feedback----------------------------------------------------------------------
    public function save_feedback(Request $request)
    {
        $validator = Validator::make($request->all(), ['name' => 'required', 'email' => 'required', 'mobile_no' => 'required']);
        if ($validator->fails()) {
            return Response::json(['error' => $validator->errors()->all()], 409);
        }
        $feed = new feedback();
        $feed->name = $request->name;
        $feed->email = $request->email;
        $feed->mobile_no = $request->mobile_no;
        $feed->feedback_type = $request->feedback_type;
        $feed->message = $request->message;
        $feed->rating = $request->rating;
        $feed->save();
        $arr = array('status' => 'true', 'message' => 'sucesss');

        echo json_encode($arr);
    }
    //--------------------get userinfo---------------------------------------------------------
    public function userbookingrequest(Request $request)
    {
        $id = Auth::id();
        // $id = auth()->user()->id ;
        $data=DB::table('room_booking_requests')
        ->join("users","room_booking_requests.userId","users.id")->where("room_booking_requests.userId",$id)
        ->select(
            "room_booking_requests.id",
            "room_booking_requests.name",
            "room_booking_requests.email",
            "room_booking_requests.mobile_no",
            "room_booking_requests.address",
            "room_booking_requests.from_date",
            "room_booking_requests.to_date",
            "room_booking_requests.no_of_member",
            "room_booking_requests.room_type",
            "room_booking_requests.no_of_rooms",
            "room_booking_requests.statusBooking",
            "room_booking_requests.created_at",
            "room_booking_requests.updated_at",
            )->orderBy("room_booking_requests.created_at","desc")->get();
        // $res = roomBookingRequest::where('userId', '=', Auth::user()->id)->get();
        return response()->json($data);
    }


    public function getFeedback(Request $request){
        $feedbakcModel = feedback::all();
        $data = $feedbakcModel;
        return response()->json($data);
    }
}
