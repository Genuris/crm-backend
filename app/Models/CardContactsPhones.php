<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CardContactsPhones extends Model
{
    protected $table = "cards_contacts_phones";
    protected $fillable = ['cards_contacts_id', 'agency_id', 'phone', 'created_at', 'updated_at'];

    public function contact(){
        return $this->hasOne('App\Models\CardContacts', 'id', 'cards_contacts_id');
    }
}
