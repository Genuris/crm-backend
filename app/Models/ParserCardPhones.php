<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParserCardPhones extends Model
{
    protected $table = "parser_cards_phones";
    protected $fillable = ['parser_cards_id', 'phone', 'created_at', 'updated_at'];

    public function parserCard(){
        return $this->hasOne('App\Models\ParserCard', 'id', 'parser_cards_id');
    }

    public function ContactPhone(){
        return $this->hasOne('App\Models\CardContactsPhones', 'phone', 'phone');
    }

    public function getFields() {
        return $this->fillable;
    }
}
