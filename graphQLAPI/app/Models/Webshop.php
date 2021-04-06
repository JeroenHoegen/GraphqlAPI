<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Webshop extends Model
{
    protected $fillable = [
        'url', 'key', 'secret', 'type'
    ];
}
