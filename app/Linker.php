<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Linker extends Model
{
    protected $fillable = [
        'url', 'slug',
    ];


    protected $table = 'linker';
}
