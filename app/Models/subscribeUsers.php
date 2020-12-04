<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class subscribeUsers extends Model
{
    protected $table = "subscribe_users";
    protected $primarykey = "id";
    protected $fillable = ['email'];

}
