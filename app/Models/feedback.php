<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class feedback extends Model
{
    protected $table="feedback";
    protected $primarykey="id";
    protected $fillable=['name','email', 'mobile_no','feedback_type','message','rating'];
}
