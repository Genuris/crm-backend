<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataChangeLogs extends Model
{
    public $table = "data_change_logs";
    protected $fillable = ['object', 'item_id', 'user_id', 'data'];

    public function DataChangeLogsUser(){
        return $this->hasOne('App\User', 'id', 'user_id');
    }

    public function getFields() {
        return $this->fillable;
    }

}
