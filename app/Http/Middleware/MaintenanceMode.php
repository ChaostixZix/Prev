<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;
use Validator,Redirect,Response;

use Closure;
use General;

class MaintenanceMode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    private $settings;
    
    public function __construct(){
        $general = new General();
        $this->settings = $general->settings();
    }
    public function handle($request, Closure $next)
    {
        if (!empty($this->settings->maintenance) && !$this->settings->maintenance->enabled || $request->user() && $request->user()->role == 1) {
            return $next($request);
        }
        if ($request->user()) {
            Auth::guard()->logout();

            $request->session()->invalidate();

            $request->session()->regenerateToken();
        }
        return response(view('index.maintenance')); 
    }
}
