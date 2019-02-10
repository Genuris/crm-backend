<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocialNetworks extends Model
{
    public $table = "social_networks";
    protected $fillable = ['title'];
}
