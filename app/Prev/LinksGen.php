<?php

namespace App\Prev;
use Str;
use App\Linker;

class LinksGen{
    public $link;
    public function __construct($url, $slug){
        if (! $link = Linker::where('url', $url)->first()) {
            $link = Linker::create([
                'url'   => $url,
                'slug'  => $slug ?? $this->randomString(6),
            ]);
        }
        $this->link = $link;
    }

    public function randomString($length = 10){
        return Str::random($length);
    }
}
