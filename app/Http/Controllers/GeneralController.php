<?php

namespace App\Http\Controllers;
use Validator,Redirect,Response, Linker;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\CurrencySymbol;
use Carbon\Carbon;
use App\Settings;
use App\User;
use App\Country;
use App\Links;
use App\Packages;
use App\Support;
use General;


class GeneralController extends Controller{
    public $settings;

    public function settings(){
        $getsettings = Settings::get();
        foreach ($getsettings as $key) {
            $value = json_decode($key->value);
            $value = (json_last_error() === JSON_ERROR_NONE) ? $value : $key->value;
            $this->settings[$key->key] = $value;
        }
        return (object) $this->settings;
    }

    public static function addHttps($url, $scheme = 'https://'){
      return parse_url($url, PHP_URL_SCHEME) === null ?
        $scheme . $url : $url;
    }

    public function get_json_data($file) {
        return json_decode(file_get_contents(storage_path() . "/json/" . $file . '.json'));
    }

    public function get_resource_file($file) {
        return require base_path('resources') . "/others/" . $file . '.php';
    }
    
    public function profilemenus(){
        return [
            array('menu' => 'Home', 'icon' => 'ni-list-thumb-alt', 'status' => '1', 'slug' => 'home'),
            array('menu' => 'About', 'icon' => 'ni-user-alt', 'status' => '1', 'slug' => 'about'),
            array('menu' => 'Links', 'icon' => 'ni-link-alt', 'status' => '1', 'slug' => 'links'),
            array('menu' => 'Portfolio', 'icon' => 'ni-briefcase', 'status' => '1', 'slug' => 'portfolio')
        ];
    }

    public static function validateDate($date, $format = 'Y-m-d') {
        $d = Carbon::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }
    public static function get_start_end_dates($start_date, $end_date) {
        $return = new \StdClass();
        $general = new GeneralController();
        /* Date selection for the notification logs */
        if($start_date && $end_date && ($general->validateDate($start_date, 'Y-m-d') || $general->validateDate($start_date, 'Y-m-d H:i:s')) && ($general->validateDate($end_date, 'Y-m-d') || $general->validateDate($end_date, 'Y-m-d H:i:s'))) {
            $return->start_date = $start_date;
            $return->start_date_query = Carbon::parse($start_date)->format('Y-m-d H:i:s');
            $return->end_date = $end_date;
            $return->end_date_query = Carbon::parse($end_date)->addDays('1')->format('Y-m-d H:i:s');
        } else {
            $return->start_date_query = Carbon::now($general->settings()->timezone)->subDays(30)->format('Y-m-d H:i:s');
            $return->start_date = Carbon::now($general->settings()->timezone)->subDays(30)->format('Y-m-d');
            $return->end_date_query = Carbon::now($general->settings()->timezone)->addDays(1)->format('Y-m-d H:i:s');
            $return->end_date = Carbon::now($general->settings()->timezone)->addDays(1)->format('Y-m-d');
        }

        $return->input_date_range = $return->start_date . ',' . $return->end_date;

        return $return;
    }

    public function cron(){
        
        $users = User::get();
        foreach ($users as $user) {
            if ($user->package !== 'free' && !empty($user->package_due) && Carbon::parse($user->package_due)->isToday()) {
                User::where('id', $user->id)->update(array('package' => 'free', 'package_due' => NULL));
            }
        }
        # change support status after x days
        if (!empty($this->settings()->support_status_change)) {
            $support = Support::where('status', 1)->get();
            foreach ($support as $item) {
                if ((!empty($item->updated_on) ? Carbon::parse($item->updated_on)->addDays($this->settings()->support_status_change)->isSameDay(Carbon::now()) : Carbon::parse($item->date)->addDays($this->settings()->support_status_change)->isSameDay(Carbon::now()))) {
                    Support::where('id', $item->id)->update(array('status' => 0));
                }
            }
        }
        # fake 404 for user :)
        abort(404);
    }

    # Profile menus

    public static function profilemenu($user_id, $type){
        # General class
        $general = new General();
        # Get passed user id
        $user = User::find($user_id);
        # Get user package
        $package = $general->package($user);
        # Get menu from user
        $menu = $user->menus;
        $userMenu = $general->get_resource_file('usermenu');
        # Loop User menus
        if (!empty($menu->menuTitle)) {
            foreach ($menu->menuTitle as $key => $value) {
                #check user has links on package
                $portfolio = $userMenu[$key]['section'] ?? '' == 'portfolio';
                $links = $userMenu[$key]['section'] ?? '' == 'links';
                if ($links && $package->settings->links == 0 || $portfolio && $package->settings->portfolio == 0){
                 unset($menu->menuTitle->{$key});
                }
            }
        }else{

        }
        $first = true;
        foreach ($userMenu as $key => $value) {
            # check menu type is main
            # Check menu status
            $status = $menu->menuStatus->{$key} ?? 1;
            $title  = $menu->menuTitle->{$key} ?? $key;
            if ($type == 'main') {
                if ($status) {
                    # echo the menu in html
                    $html = '<li class="nav-item mr-lg-4">
                          <a class="nav-link theme-btn intro-menu-item" href="'.Linker::url(url($user->username . '/' . $title), ['ref' => $user->username]) .'">'. ucfirst(str_replace('_', ' ', $title)) .'</a>
                        </li>';
                    echo $html;
                }
            }
            # check menu type is bottom
            if ($type == 'bottom') {
                # check menu status
               if ($status == 1) {
                    $html = '<li><a route="'.url($user->username . '/' . $title).'" href="'. Linker::url(url($user->username . '/' . $title), ['ref' => $user->username]) .'"><em class="icon ni ni-'. $value['icon'] .'"></em><span>'. ucfirst($title) .'</span></a></li>';
                    echo $html;
               }
            }
        }
    }

    # Profile pages

    public static function activate_email($token){
    	if (!User::where('email_token', $token)->exists()) {
    		abort(404);
    	}
    	$user = User::where('email_token', $token)->first();
    	$user = User::find($user->id);
    	$user->active = 1;
    	$user->email_token = '';
    	$user->save();
    	if (Auth::check()) {
    		return redirect()->route('home.manage')->with('success', 'Email activated.');
    	}
    	return redirect()->route('login')->with('success', 'Email activated. Kindly login to access our dashboard.');
    }

    public static function user_profile($user_id){
      $default = url('img/default_avatar.png');
      if (!$user = User::where('id', $user_id)->first()) {
          return $default;
      }
      $check = public_path('img/user/avatar/') . $user->avatar;
      $path = url('img/user/avatar/' . $user->avatar);
      return (file_exists($check)) ? $path : $default;
    }


    public function gradient_preset(){
      $preset = ['gra-1', 'gra-2', 'gra-3', 'gra-4', 'gra-5', 'gra-6', 'gra-7', 'gra-8', 'gra-9', 'gra-10', 'gra-11', 'gra-12', 'gra-13'];
      return $preset;
    }

    public static function get_link($link_id, $user_id){
        $user = User::find($user_id);
        $link = Links::find($link_id);
        $general = new GeneralController();
        $design = new \StdClass();
        $design->background_class    = "default";
        $design->background_radius   = "";
        $design->background_css      = "";
        $design->popover             = "";
        $design->outline             = "";
        $design->column              = "col-12";

        if (!empty($link->note)) {
            $design->popover = '<button class="bg-transparent text-right mr-3" data-toggle="popover" data-trigger="focus" data-content="'. $link->note .'"><em class="icon ni ni-bulb"></em></button>';
        }
        /* Check if the user has the access needed from the package */
        if($general->package($user)->settings->links_style) {
            if ($user->link_row->color_type == "default") {
                $design->background_class = 'default';
                if ($general->package($user)->settings->custom_background && $user->background_type == "gradient") {
                  $design->background_class = 'gra-bg ' . $user->background;
                }
            }
            switch ($user->link_row->radius) {
                case 'round':
                    $design->background_radius   = "round";
                break;
                case 'straight':
                    $design->background_radius   = "straight";
                break;
                case 'rounded':
                    $design->background_radius   = "rounded";
                break;
            }
            if ($user->link_row->column == "two") {
                $design->column = "col-6";
            }
            if ($user->link_row->outline) {
                 $design->outline   = "outline";
                $design->background_class = '';
            }
            if ($user->link_row->color_type == "background") {
                $design->background_class = '';
                $design->background_css = 'background: ' . $user->link_row->background . '; color: '. $user->link_row->textcolor .'';
            }
        }else{
           if ($general->package($user)->settings->custom_background && $user->background_type == "gradient") {
             $design->background_class = 'color ' . $user->background;
           }
        }
        $html = '<div class="'.$design->column.'" ><div class="link '.$design->background_class .' '. $design->background_radius .' '.$design->outline. '" style="'.$design->background_css.'"> <a href="'.Linker::use($link->url_slug, ['ref' =>  $user->username, 'url' => $link->url]).'" class="btn" target="_blank">'.ucfirst($link->name).'</a>'.$design->popover.'</div></div>';
        return $html;
    }


    public function package($user){
        if ($user->package == 'free') {
            $package = $this->settings()->package_free;
        }else{
            if (Packages::where('id', $user->package)->exists()) {
                 $package = Packages::where('id', $user->package)->first();
            }else{
             $package = $this->settings()->package_free;
            }
        }
        return $package;
    }

    public static function Spackage($user){
        $general = new GeneralController();
        if ($user->package == 'free') {
            $package = $general->settings()->package_free;
        }else{
            if (Packages::where('id', $user->package)->exists()) {
                 $package = Packages::where('id', $user->package)->first();
            }else{
             $package = $general->settings()->package_free;
            }
        }
        return $package;
    }

    public function get_chart_data(Array $main_array) {

        $results = [];

        foreach($main_array as $date_label => $data) {

            foreach($data as $label_key => $label_value) {

                if(!isset($results[$label_key])) {
                    $results[$label_key] = [];
                }

                $results[$label_key][] = $label_value;

            }

        }

        foreach($results as $key => $value) {
            $results[$key] = '["' . implode('", "', $value) . '"]';
        }

        $results['labels'] = '["' . implode('", "', array_keys($main_array)) . '"]';

        return $results;
    }

    public function generate_slug($string, $delimiter = '_') {

        // remove all non alphanumeric characters except spaces
        $string =  preg_replace('/[^a-zA-Z0-9\s._-]/', '', $string); 

        // replace one or multiple spaces into single dash (-)
        $string =  preg_replace('!\s+!', '_', $string);
        $string = preg_replace('/[.]+/', '.', $string);
        $string = preg_replace('/[-]+/', '-', $string);

        return strtolower($string);
    }

    public static function getIP(){
        $request = request();
        $session = $request->session();



        if(!empty($session->get('ip'))):
            $ip = $session->get('ip');
            else:
            try {
                $ip = file_get_contents('http://ipv4bot.whatismyipaddress.com');
                $session->put('ip', $ip);
            } catch (\Exception $e) {
                $ip = request()->ip();
            }
        endif;

        return $ip;
    }


    public static function countries($code){
        $country = new Country($code);
        return $country->country;
    }

    public static function currencysymbol($code){
        $currency = new CurrencySymbol($code);
        return $currency->symbol;
    }

    public static function nr($number, $decimals = 0, $extra = true) {
        if($extra) {
            if($number > 999999999) {
                return floor($number / 1000000000) . 'B';
            }
            if($number > 999999) {
                return floor($number / 1000000) . 'M';
            }
            if($number > 999) {
                return floor($number / 1000) . 'K';
            }
        }

        if($number == 0) {
            return 0;
        }

        return number_format($number, $decimals, '.', ',');
    }
}
