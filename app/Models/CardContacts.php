<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CardContacts extends Model
{
    protected $table = "cards_contacts";
    protected $fillable = ['name', 'email', 'agency_id', 'created_at', 'updated_at',
        'children', 'car', 'work_place', 'is_married',
        'is_client', 'is_partner', 'is_realtor', 'years', 'leisure',
        'kind_of_activity', 'animals', 'decision_makers', 'is_black_list'];

    public function CardsContacts(){
        return $this->hasMany('App\Models\Card', 'cards_contacts_id', 'agency_id', 'id');
    }

    public function CardsContactsPhones(){
        return $this->hasMany('App\Models\CardContactsPhones', 'cards_contacts_id', 'id');
    }
}
