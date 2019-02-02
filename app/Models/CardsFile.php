<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CardsFile extends Model
{
    protected $table = "cards_files";
    protected $fillable = ['type', 'card_id', 'file_id'];

    public function file(){
        return $this->hasOne('App\Models\File', 'id', 'file_id');
    }
}
