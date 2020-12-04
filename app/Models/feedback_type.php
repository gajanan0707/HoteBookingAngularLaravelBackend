<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class feedback_type extends Model
{
    protected $table="feedback_types";
    protected $primarykey="id";
    protected $fillable=['title','status'];
}
