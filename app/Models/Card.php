<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    public $table = "cards";
    protected $fillable = [
        'agency_id',
        'user_id',
        'office_id',
        'cards_contacts_id',
        'type',
        'sale_type',
        'city',
        'area',
        'street',
        'building',
        'apartment',
        'price',
        'currency',
        'landmark',
        'owner_or_realtor',
        'year_built',
        'floors_house',
        'number_rooms',
        'type_building',
        'roof',
        'total_area',
        'living_area',
        'kitchen_area',
        'ceiling_height',
        'condition',
        'heating',
        'electricity',
        'water_pipes',
        'bathroom',
        'sewage',
        'internet',
        'gas',
        'security',
        'land_area',
        'how_plot_fenced',
        'entrance_door',
        'furniture',
        'window',
        'limes',
        'garage_height',
        'garage_length',
        'garage_width',
        'ceiling',
        'basement',
        'balcony',
        'corner',
        'gate_height',
        'gate_width',
        'view_from_windows',
        'garbage_chute',
        'number_of_floors',
        'layout',
        'reason_for_sale',
        'category',
        'subcategory',
        'description',
        'comment',
        'stage_transaction',
        'commission',
        'number_contract',
        'contract_expiration_date',
        'is_archived',
        'elevator',
        'apartment_type',
        'payments',
        'household_appliances',
        'will_live',
    ];

    protected $appends = ['percent'];

    public function CardUser(){
        return $this->hasOne('App\User', 'id', 'user_id');
    }

    public function CardOffice(){
        return $this->hasOne('App\Models\Office', 'id', 'office_id');
    }

    public function CardAgency(){
        return $this->hasOne('App\Models\Card', 'id', 'agency_id');
    }

    public function CardFiles(){
        return $this->hasMany('App\Models\CardsFile', 'card_id', 'id');
    }

    public function CardContact(){
        return $this->hasOne('App\Models\CardContacts', 'id', 'cards_contacts_id');
    }

    public function getFields() {
        return $this->fillable;
    }

    /*public function setPercent($value = null) {
        $this->percent = $value;
    }*/

    public function getPercentAttribute() {
        if (isset($this->attributes['percent']))
            return $this->attributes['percent'];
        return 0;
    }

}
