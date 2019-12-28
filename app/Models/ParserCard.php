<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParserCard extends Model
{
    public $table = "parser_cards";
    protected $fillable = [
        'street',
        'area',
        'number_of_floors',
        'floors_house',
        'number_rooms',
        'price',
        'total_area',
        'site_url',
        'description',
        'comment',
        'created_at',
        'updated_at'
    ];

    public function ParserCardsPhones(){
        return $this->hasMany('App\Models\ParserCardPhones', 'parser_cards_id', 'id');
    }

    public function getFields() {
        return $this->fillable;
    }

}
