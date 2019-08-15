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
        'name', 'email', 'password', 'surname', 'middle_name', 'time_zone', 'role_id', 'agency_id', 'office_id', 'offices_partition_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $appends = ['count_cards_not_archived'];

    public function UserDetails(){
        return $this->hasOne('App\Models\UserDetails', 'user_id', 'id');
    }

    public function UserSocials(){
        return $this->hasMany('App\Models\UserSocialNetworks', 'user_id', 'id');
    }

    public function UserPhones(){
        return $this->hasMany('App\Models\UserPhones', 'user_id', 'id');
    }

    public function UserAgency(){
        return $this->hasOne('App\Models\Agency', 'id', 'agency_id');
    }

    public function UserOffice(){
        return $this->hasOne('App\Models\Office', 'id', 'office_id');
    }

    public function UserOfficesPartition(){
        return $this->hasOne('App\Models\OfficesPartition', 'id', 'offices_partition_id');
    }

    public function UserRole(){
        return $this->hasOne('App\Models\Role', 'id', 'role_id');
    }

    public function getFields() {
        return $this->fillable;
    }

    public function getCountCardsNotArchivedAttribute() {
        if (isset($this->attributes['count_cards_not_archived']))
            return $this->attributes['count_cards_not_archived'];
        return 0;
    }
}
