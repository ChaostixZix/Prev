<?php

namespace App;
class Uri {
    public static function to($url = '', $type = 'url'){
    	$url = $appUrl;
		$domain = parse_url($url, PHP_URL_HOST);
		$domain = str_replace('www.','',$domain);
    	$path = '/'.trim($url, '/');
    	return $path;
    }
    public static function route($route, $parms = []){
    	$url = config('app.url');
    	$route = $url . route($route, $parms, false);
    	return $route;
    }

    public function isValidUrl($path){
        if (! preg_match('~^(#|//|https?://|(mailto|tel|sms):)~', $path)) {
            return filter_var($path, FILTER_VALIDATE_URL) !== false;
        }

        return true;
    }
}
