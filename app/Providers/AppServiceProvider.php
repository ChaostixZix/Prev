<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Settings;
use App\Packages;
use App\Pages;
use App\User;
use App\Domains;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(){
        Schema::defaultStringLength(191);
        if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] !== 'on') {

        }else{
           \URL::forceScheme('https');
        }
        try {
            DB::connection()->getPdo();
            if(DB::connection()->getDatabaseName()){
            View::composer('*', function ($view){
                    if (Auth::check()) {
                        $user = Auth::user();
                        if ($user->package == 'free') {
                            $package = Settings::where('key', 'package_free')->first();
                            $package = json_decode($package->value);
                            $package = (json_last_error() === JSON_ERROR_NONE) ? $package : $package->value;
                        }else{
                            $package = Packages::where('id', Auth::user()->package)->first();
                        }
                        $domain = $user->domain;
                        if ($domain == 'main') {
                          $domain = env('APP_URL');
                        }elseif ($domain = Domains::where('id', $user->domain)->first()) {
                          $domain = $domain->scheme.$domain->host;
                        }else{
                          $domain = env('APP_URL');
                        }
                        $profile_url = $domain.'/'.$user->username;
                        View::share('profile_url', $profile_url);
                        View::share('package', $package);
                        View::share('user', $user);
                    }
                });
                if (Schema::hasTable('settings')) {
                    $getsettings = Settings::get();
                    foreach ($getsettings as $key) {
                        $value = json_decode($key->value);
                        $value = (json_last_error() === JSON_ERROR_NONE) ? $value : $key->value;
                        $settings[$key->key] = $value;
                    }
                    $settings = (object) $settings;
                    View::share('website', $settings);
                }
                if (Schema::hasTable('users')) {
                    $allusers = User::get();
                    foreach ($allusers as $item) {
                        if (!empty($item->menus)) { 
                        }
                    }
                }
                if (Schema::hasTable('pages')) {
                    $pages = Pages::where('status', 1)->limit(4)->orderBy('order', 'ASC')->orderBy('id', 'DESC')->get();
                    View::share('allPages', $pages);
                }
                if (Schema::hasTable('pages_categories')) {
                    $categories = DB::table('pages_categories')->limit(4)->orderBy('order', 'ASC')->orderBy('id', 'DESC')->get();
                    View::share('allCategories', $categories);
                }
            }
        } catch (\Exception $e) {
            // Do nothing
        }
    }
}
