<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CardSubcategories extends Model
{
    public $table = "card_subcategories";
    protected $fillable = ['card_categories_id', 'value', 'title', 'created_at', 'updated_at'];

    public function CardCategory(){
        return $this->hasOne('App\Models\CardCategories', 'id', 'card_categories_id');
    }

    public function getFields() {
        return $this->fillable;
    }
}
