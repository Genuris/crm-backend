<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDetails extends Model
{
    protected $table = "users_details";
    protected $fillable = ['id', 'user_id', 'city', 'birthday', 'postal_code', 'profile_image_id', 'currency'];

    public function profileImage(){
        return $this->hasOne('App\Models\File', 'id', 'profile_image_id');
    }
}
