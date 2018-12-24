<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CardContacts extends Model
{
    protected $table = "cards_contacts";
    protected $fillable = ['name', 'email', 'created_at', 'updated_at'];

    public function CardsContacts(){
        return $this->hasMany('App\Models\Card', 'cards_contacts_id', 'id');
    }

    public function CardsContactsPhones(){
        return $this->hasMany('App\Models\CardContactsPhones', 'cards_contacts_id', 'id');
    }
}
