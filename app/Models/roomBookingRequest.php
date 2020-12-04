<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class roomBookingRequest extends Model
{
    protected $table="room_booking_requests";
    protected $primarykey="id";
    protected $fillable=[
        'name','email','mobile_no','address','from_date','to_date','no_of_member','no_of_rooms','room_type','statusBooking','userId'
    ];
}
