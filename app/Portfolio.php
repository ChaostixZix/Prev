<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
    protected $table = 'portfolio';
    public $timestamps = false;

    protected $casts = [
        'settings'              => 'object',
    ];
}
