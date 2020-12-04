<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class room_type extends Model
{
    protected $table="room_types";
    protected $primarykey="id";
    protected $fillable=['titile','status'];
}
