<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CardRequestStatus extends Model
{
    protected $table = "cards_request_status";
    protected $fillable = ['card_request_id', 'card_object_id', 'user_id', 'created_at', 'updated_at',
        'status'];

    public function getFields() {
        return $this->fillable;
    }
}
