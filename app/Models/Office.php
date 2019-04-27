<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Office extends Model
{
    public $table = "offices";
    protected $fillable = ['agency_id', 'user_id', 'title', 'city', 'area', 'street', 'building', 'apartment'];

    public function OfficeUsers(){
        return $this->hasMany('App\User', 'id', 'user_id');
    }

    public function OfficePartitions(){
        return $this->hasMany('App\Models\OfficesPartition', 'office_id', 'id');
    }

    public function OfficeCards(){
        return $this->hasMany('App\Models\Card', 'office_id', 'id');
    }

    public function getFields() {
        return $this->fillable;
    }
}
