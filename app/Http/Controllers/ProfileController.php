<?php

namespace App\Http\Controllers;

use Validator,Redirect,Response, Theme, Storage, Crypt;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;
use Carbon\Carbon;
use App\Track;
use App\User;
use App\Skills;
use App\Links;
use App\Domains;
use App\Portfolio;
use App\Packages;
use Location;
use General;

class ProfileController extends Controller{
  private $settings;
  private $user;
  private $route;
  public  $template;
  
    public function __construct(Request $request){
        # get all from GENERAL CONTROLLER
        $general = new General();
        # move general settings into variable
        $this->settings = $general->settings();
        # check if site is on mainenance mode and lock
        $this->middleware('MaintenanceMode');
        if (!$this->user = User::where('username', $request->profile)->first()) {
            abort(404);
        }
        $domain = $this->user->domain;
        if ($domain == 'main') {
          $domain = env('APP_URL');
        }elseif ($domain = Domains::where('id', $this->user->domain)->first()) {
          $domain = $domain->scheme.$domain->host;
        }else{
          $domain = env('APP_URL');
        }
        $host     = parse_url($domain);
        $thishost = $_SERVER['HTTP_HOST'];
        if ($host['host'] == $thishost) {
          # Proceed with request
        }else{
          if (!empty($this->settings->user->domains_restrict) && $this->settings->user->domains_restrict) {
          # Proceed with request
          }else{
            abort(404);
          }
        }
        View::share('background_color', $this->solid_background_color($this->user));
        $package = $general->package($this->user);
        $templates = $general->get_json_data('Profiletemplates');

        foreach ($templates as $key => $value) {
          if (!empty($this->user->settings->template)) {
             $this->template = $this->user->settings->template;
          }else{
            if ($value->default == 'yes') {
               $this->template = $key;
            }
          }
        }
         Theme::set($this->template);
         $links_limit = Links::where('user', $this->user->id)->orderBy('order', 'ASC')->orderBy('id', 'DESC')->limit(4)->get();
         $Allskills  = Skills::where('user', $this->user->id)->orderBy('position', 'ASC')->orderBy('id', 'DESC')->get();
         $socials    = $general->get_resource_file('socials');
         $userMenu   = $general->get_resource_file('usermenu');
         $options = (object) ['socials' => $socials, 'menu' => $userMenu];
         View::share('links_limit', $links_limit);
         View::share('skills', $Allskills);
         View::share('options', $options);
         $this->middleware(function ($request, $next) {
            if ($this->user && !$this->user->active && empty($this->user->email_token)) {
                return response(view('errors.banned'));
            }
            return $next($request);
         });
    }


    public function index(Request $request, $profile, Skills $skills){
        $general = new General();
        $userMenu   = $general->get_resource_file('usermenu');
        $Allskills  = $skills->where('user', $this->user->id)->limit(4)->orderBy('position', 'ASC')->orderBy('id', 'DESC')->get();
        $package = $general->package($this->user);
        $this->track($this->user, $request);
        $user = $this->user;
        $links = Links::where('user', $user->id)->orderBy('order', 'ASC')->orderBy('id', 'DESC')->get();
        $portfolios = Portfolio::where('user', $user->id)->orderBy('order', 'ASC')->orderBy('id', 'DESC')->get();
        $portfolios_limit = Portfolio::where('user', $user->id)->orderBy('order', 'ASC')->orderBy('id', 'DESC')->limit(2)->get();



        $menu = [];
        foreach ($userMenu as $key => $value) {
          $menu[$value['section']] = ['title' => $user->menus->menuTitle->{$key} ?? $key];
        }
        if (!empty($user->menus->active)) {
          foreach ($userMenu as $key => $value) {
            if ($user->menus->active == $key) {
             return view($value['section'], ['user' => $user, 'portfolios_limit' => $portfolios_limit, 'portfolios' => $portfolios, 'package' => $package, 'links' => $links, 'skills_limits' => $Allskills, 'pageTitle' => $user->menus->menuTitle->{$key}, 'cm' => $menu]);
            }
          }
        }
        return view('home', ['user' => $user, 'portfolios_limit' => $portfolios_limit, 'portfolios' =>$portfolios, 'package' => $package, 'links' => $links, 'skills_limits' => $Allskills, 'pageTitle' => 'home', 'cm' => $menu]);
    }


    public function allPages(Request $request, $profile, $second, Skills $skills){
        $general    = new General();
        $userMenu   = $general->get_resource_file('usermenu');
        $Allskills  = $skills->where('user', $this->user->id)->limit(4)->orderBy('position', 'ASC')->orderBy('id', 'DESC')->get();
        $package = $general->package($this->user);
        $this->track($this->user, $request);
        $user  = $this->user;
        $links = Links::where('user', $user->id)->orderBy('order', 'ASC')->orderBy('id', 'DESC')->get();
        $portfolios = Portfolio::where('user', $user->id)->orderBy('order', 'ASC')->orderBy('id', 'DESC')->get();
        $portfolios_limit = Portfolio::where('user', $user->id)->limit(2)->orderBy('order', 'ASC')->orderBy('id', 'DESC')->get();

        $menu = [];
        foreach ($userMenu as $key => $value) {
          $menu[$value['section']] = ['title' => $user->menus->menuTitle->{$key} ?? $key];
        }
        foreach ($userMenu as $key => $value) {
          $title = $user->menus->menuTitle->{$key} ?? $key;
          if ($second == $title) {
           return view($value['section'], ['user' => $user, 'portfolios_limit' => $portfolios_limit, 'portfolios' => $portfolios, 'package' => $package, 'links' => $links, 'skills_limits' => $Allskills, 'pageTitle' => $title, 'cm' => $menu]);
          }
        }
        abort(404);
    }

    public function third(Request $request, $profile, $second, $third){
        $user  = $this->user;
        $general    = new General();
        $userMenu   = $general->get_resource_file('usermenu');

          foreach ($userMenu as $key => $value) {
            $title = $user->menus->menuTitle->{$key} ?? $key;
            if ($second == $title) {
              if ($value['section'] == 'portfolio') {
                return $this->singleportfolio($request, $profile, $third);
              }
            }
        }
        abort(404);
    }

    public function singleportfolio($request, $profile, $slug){
        $general = new General();
        $package = $general->package($this->user);
        if ($package->settings->portfolio == 0) {
            return Redirect::to($profile);
        }
        if (!Portfolio::where('slug', $slug)->where('user', $this->user->id)->exists()) {
            abort(404);
        }
        $portfolio = Portfolio::where('slug', $slug)->where('user', $this->user->id)->first();
        $portfolio_note = (!empty($portfolio->settings->note) ?  $portfolio->settings->note : "");
        $match = '|\B\#([\d\w_]+\.[\w\.]{2,6}[^\s\]\[\<\>]*/?)|i';
        $match_2 = '|\B\+([\d\w_]+\.[\w\.]{2,6}[^\s\]\[\<\>]*/?)|i';
        $tag = '<a class="btn theme-btn border-0" href="'.$general->addHttps('$1').'" target="_blank">$1</a>';
        $tag_2 = '<a class="border-0" href="'.$general->addHttps('$1').'" target="_blank">$1</a>';
        $portfolio_note = preg_replace($match, $tag, $portfolio_note);
        $portfolio_note = preg_replace($match_2, $tag_2, $portfolio_note);
        $portfolio_note = str_replace("{{title}}", (!empty($portfolio->settings->name) ?  $portfolio->settings->name : ""), $portfolio_note);
        $this->track_portfolio($this->user, $request, $portfolio->id, "portfolio");
        $user = $this->user;
        return view('singleportfolio', ['user' => $user, 'package' => $package, 'portfolio' => $portfolio, 'portfolio_note' => $portfolio_note]);
    }

    public function track_portfolio($user, $request, $dyid = Null, $type = 'profile'){
        $agent = new Agent();
        $general = new General();
        $visitor_id = md5(microtime());
        $request->session()->put('visitor', 1);
        $session  = $request->session();
        if (empty($session->get('visitor_id'))) {
            $request->session()->put('visitor_id', $visitor_id);
        }

        if (Track::where('visitor_id', $session->get('visitor_id'))->where('dyid', $dyid)->where('type', $type)->count() > 0 ) {
            $track = Track::where('visitor_id', $session->get('visitor_id'))->where('dyid', $dyid)->where('type', $type)->first();
            $values = array('count' => ($track->count + 1), 'date' => Carbon::now($this->settings->timezone));
            Track::where('visitor_id', $session->get('visitor_id'))->update($values);
        }else{
            $values = array('user' => $user->id, 'visitor_id' => $session->get('visitor_id'), 'country' => (!empty(Location::get($general->getIP())->countryCode)) ? Location::get($general->getIP())->countryCode : Null, 'type' => $type, 'dyid' => $dyid, 'ip' => $this->getIp(), 'os' => $agent->platform(), 'browser' => $agent->browser(), 'count' => 1, 'date' => Carbon::now($this->settings->timezone));
            Track::insert($values);
        }
        
    }

    public function redirect_link(Request $request, $url){
        if (!Links::where('url_slug', $url)->exisits()) {
            return back();
        }
        $link = Links::where('url_slug', $url)->first();
        $this->track($link->user, $request, $link->id, 'link');
        return Redirect::to($link->url);
    }

    // Helpers
    public function get_json_data($file) {
        return json_decode(file_get_contents(storage_path() . "/json/" . $file . '.json'));
    }
    public function solid_background_color($user){
       $general = new General();
       $package = $general->package($user);
       if (!$package->settings->custom_background) {
            $styles = "'/* nothing here */'";
            return $styles;   
       }
       $solid_color = "#000";
       $general_color = "#000";
       $general_color = (!empty($user->settings->general_color) ? $user->settings->general_color : "#000");
       $solid_color = ($user->background_type == 'color' ? $user->background : "#000");
       $styles = '
      body .profile-header .profile-header-left .profile-avatar {
        background: '.$solid_color.';
        border: 0;
      }
      body .profile-block-card.active {
        background: '.$solid_color.';
      }
      body .profile-block-card.active span, body .profile-block-card.active em {
        color: '.$general_color.';
      }
      body .profile-header .profile-header-left .profile-header-name {
        background: '.$solid_color.';
        border-radius: 10px;
      }
      body .profile-header .profile-header-left .profile-header-name h3, body.gra-1 .profile-header .profile-header-left .profile-header-name p {
        color: '.$general_color.';
      }
      body .theme-btn {
        background: '.$solid_color.';
        color: '.$general_color.';
      }
      body .bottom-bar .bottom-bar-inner li.active a {
        background: '.$solid_color.';
        color: '.$general_color.';
      }
      body .bottom-bar .bottom-bar-inner li.active em, body .bottom-bar .bottom-bar-inner li.active i {
        color: '.$general_color.';
      }
      body .skillbar-bar {
        background: '.$solid_color.';
      }
      body.section-social {
        background: '.$solid_color.';
        color: '.$general_color.';
      }
      body .menu-icon {
        color: '.$general_color.';
      }
      body .intro-header {
        background: '.$solid_color.';
      }
      body .intro-menu .intro-menu-item a, .navbar-toggler {
        color:  '.$general_color.';
      }
      body .theme-bg{
        background: '.$solid_color.';
        color:  '.$general_color.';
      }
      body .navbar-light .navbar-nav .nav-link.intro-menu-item{
        background: '.$solid_color.' !important;
        color:  '.$general_color.';
      }
      body .navbar-light .navbar-nav .nav-link{
        color:  '.$general_color.';
      }
      body .link.default {
        background: '.$solid_color.' !important;
        color:  '.$general_color.';
      }';

     # use dynamic generated css
     # $file = @fopen(public_path('css/profile/'.strtolower($user->username).'.css'), 'w');
     # fwrite($file, $styles);
     # fclose($file);
     return $styles;
    }

    public function track($user, $request, $dyid = Null, $type = 'profile'){
      $agent = new Agent();
      $general = new General();
      $ip = $general->getIP();
      $visitor_id = md5(microtime());
      $request->session()->put('visitor', 1);
      $session  = $request->session();
      if (empty($session->get('visitor_id'))) {
        $request->session()->put('visitor_id', $visitor_id);
      }

      if (Track::where('visitor_id', $session->get('visitor_id'))->count() > 0 ) {
        $track = Track::where('visitor_id', $session->get('visitor_id'))->first();
            $values = array('count' => ($track->count + 1), 'date' => Carbon::now($this->settings->timezone));
            Track::where('visitor_id', $session->get('visitor_id'))->update($values);
      }else{
            $values = array('user' => $user->id, 'visitor_id' => $session->get('visitor_id'), 'country' => (!empty(Location::get($general->getIP())->countryCode)) ? Location::get($general->getIP())->countryCode : Null, 'type' => $type, 'dyid' => $dyid, 'ip' => $this->getIp(), 'os' => $agent->platform(), 'browser' => $agent->browser(), 'count' => 1, 'date' => Carbon::now($this->settings->timezone));
            Track::insert($values);
      }
      
    }

    public function getIp(){
        if(array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)) {

            if(strpos($_SERVER['HTTP_X_FORWARDED_FOR'], ',')) {
                $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);

                return trim(reset($ips));
            } else {
                return $_SERVER['HTTP_X_FORWARDED_FOR'];
            }

        } else if (array_key_exists('REMOTE_ADDR', $_SERVER)) {
            return $_SERVER['REMOTE_ADDR'];
        } else if (array_key_exists('HTTP_CLIENT_IP', $_SERVER)) {
            return $_SERVER['HTTP_CLIENT_IP'];
        }

        return '';
    }
}
