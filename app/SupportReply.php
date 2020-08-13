<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SupportReply extends Model
{
    protected $table = 'support_replies';
    public $timestamps = false;
    protected $casts = [
        'settings'  =>  'object',
    ];
}
