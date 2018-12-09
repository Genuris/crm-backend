<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPhones extends Model
{
    protected $table = "users_phones";
    protected $fillable = ['value', 'user_id'];
}
