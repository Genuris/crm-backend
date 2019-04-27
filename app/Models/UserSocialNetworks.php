<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSocialNetworks extends Model
{
    protected $table = "users_social_networks";
    protected $fillable = ['id', 'value', 'user_id', 'social_network_id', 'currency'];

    public function getFields() {
        return $this->fillable;
    }
}
