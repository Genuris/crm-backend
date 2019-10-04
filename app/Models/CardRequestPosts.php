<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CardRequestPosts extends Model
{
    protected $table = "cards_request_posts";
    protected $fillable = ['card_request_id', 'card_object_id', 'user_id', 'created_at', 'updated_at',
        'post', 'initial_card'];
    
    public function getFields() {
        return $this->fillable;
    }
}
