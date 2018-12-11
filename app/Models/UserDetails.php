<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDetails extends Model
{
    protected $table = "users_details";
    protected $fillable = ['user_id', 'city', 'postal_code', 'profile_image_id', 'currency'];
}
