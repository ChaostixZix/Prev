<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Support extends Model
{
    protected $table = 'support';
    public $timestamps = false;
    protected $casts = [
        'settings'  =>  'object',
    ];
}
