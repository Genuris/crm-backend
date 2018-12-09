<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'surname', 'middle_name', 'time_zone'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function UserDetails(){
        return $this->hasOne('App\Models\UserDetails', 'user_id', 'id');
    }

    public function UserSocials(){
        return $this->hasMany('App\Models\UserSocialNetworks', 'user_id', 'id');
    }

    public function UserPhones(){
        return $this->hasMany('App\Models\UserPhones', 'user_id', 'id');
    }
}
