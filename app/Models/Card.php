<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    public $table = "cards";
    public $object_name = "card";
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
        'floors_house_end',
        'number_rooms',
        'type_building',
        'roof',
        'total_area',
        'total_area_end',
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
        'number_of_floors_end',
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
        'archive_date',
        'creator_id',
        'will_live',
        'data_change_prices',
        'created_at',
        'updated_at'
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

    public function CardRequestObjectStatus(){
        return $this->hasMany('App\Models\CardRequestStatus', 'id', 'card_object_id');
    }

    public function CardRequestStatus(){
        return $this->hasMany('App\Models\CardRequestStatus', 'id', 'card_request_id');
    }

    public function CardRequestObjectPosts(){
        return $this->hasMany('App\Models\CardRequestPosts', 'id', 'card_object_id');
    }

    public function CardRequestPosts(){
        return $this->hasMany('App\Models\CardRequestPosts', 'id', 'card_request_id');
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
