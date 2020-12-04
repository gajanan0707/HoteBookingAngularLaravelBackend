<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class contact extends Model
{
    protected $table="contacts";
    protected $primarykey="id";
    protected $fillable=['name','email','mobile_no','message'];
}
