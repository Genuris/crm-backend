<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CardCategories extends Model
{
    protected $table = "card_categories";
    protected $fillable = ['value', 'title', 'created_at', 'updated_at'];
}
