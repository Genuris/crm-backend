<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Task extends Model
{
    protected $table = "tasks";
    protected $fillable = ['cards_contacts_id', 'creator_id', 'card_id', 'date_time', 'remind', 'type', 'description', 'responsibles'];

    public function Creator()
    {
        return $this->hasOne('App\User', 'id', 'creator_id');
    }

    public function Card()
    {
        return $this->hasOne('App\Models\Card', 'id', 'card_id');
    }

    public function Contact()
    {
        return $this->hasOne('App\Models\CardContacts', 'id', 'cards_contacts_id');
    }
}
