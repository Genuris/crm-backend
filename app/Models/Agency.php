<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agency extends Model
{
    protected $table = "agencies";
    protected $fillable = ['title', 'user_id'];

    public function AgencyUsers(){
        return $this->hasMany('App\User', 'id', 'user_id');
    }

    public function AgencyOffices(){
        return $this->hasMany('App\Models\Office', 'agency_id', 'id');
    }

    public function AgencyCards(){
        return $this->hasMany('App\Models\Card', 'agency_id', 'id');
    }
}
