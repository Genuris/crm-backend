<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OfficesPartition extends Model
{
    public $table = "offices_partitions";
    protected $fillable = ['id', 'office_id', 'user_id', 'type'];

    public function OfficesPartitionUsers(){
        return $this->hasMany('App\User', 'offices_partition_id', 'id');
    }

    public function getFields() {
        return $this->fillable;
    }
}

