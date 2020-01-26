<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CardShare extends Model
{
    public $table = "cards_share";
    protected $fillable = [
        'card_id',
        'user_id',
        'hash',
        'share_cards',
        'created_at',
        'updated_at'
    ];

    public function Card(){
        return $this->hasOne('App\Models\Card', 'id', 'card_id');
    }

    public function User(){
        return $this->hasOne('App\Models\Card', 'id', 'user_id');
    }

    public function getFields() {
        return $this->fillable;
    }

}
