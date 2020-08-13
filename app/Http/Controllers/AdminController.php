<?php

namespace App\Http\Controllers;

use Validator, Redirect, Response, uri, File,  Storage, Crypt;
use Ausi\SlugGenerator\SlugGenerator;
use Camroncade\Timezone\Facades\Timezone;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Routing\Redirector;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Mail\GeneralMail;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Settings;
use App\Links;
use App\User;
use App\Support;
use App\SupportReply;
use App\Pages;
use App\Category;
use App\Faq;
use App\PendingPayments;
use App\Payments;
use App\Packages;
use App\Track;
use GuzzleHttp\Client;
use App\Portfolio;
use App\Domains;
use App\Locale;
use General;

class AdminController extends Controller
{
    #|--------------------------------------------------------------------------
    #| PREV PROFILE BUILDER
    #|--------------------------------------------------------------------------


    /*
    |--------------------------------------------------------------------------
    | Admin Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles all the admin pages including the settings, stats packages, users, 
    | links, portfolio, support, payments and more.
    |
    */


    # devine general settings
    private $settings;
    private $code;
    
    # construct
    public function __construct(){
        # check if user is logged in
        $this->middleware('auth');
        # check if user is admin
        $this->middleware('admin');

         # get all from GENERAL CONTROLLER
         $general = new General();
         $this->middleware(function ($request, $next) {
            if (env('APP_DEMO') && isset($request->_token)) {
                return back()->with('error', 'Option not available in demo mode');
            }
            return $next($request);
         });
         if (file_exists(storage_path('app/.code'))) {
            try {
                $this->code = json_decode(Crypt::decryptString(Storage::get('.code')));
            } catch (\Exception $e) {
                $this->code = null;
            }
         }

         # move general settings into variable
         $this->settings = $general->settings();

         # get unread ticket 
        $unreadticket = Support::where('viewed', 0)->get();
        # inject unread ticket into admin pages
        View::share('unreadticket', $unreadticket);
    }

    # Admin home function
    public function home_admin(User $user, Track $track){
        $general = new General();
        $settings = $general->settings();
        # get previous month start date
        $fromDate = Carbon::now($this->settings->timezone)->subDay()->startOfWeek()->toDateString();
        $thisMonth = Carbon::now($this->settings->timezone)->startOfMonth()->toDateString();
        $fromYear = Carbon::now($this->settings->timezone)->startOfYear();

        # get previous month end date
        $tillDate = Carbon::now($this->settings->timezone)->subDay()->toDateString();

        # get total visits of current month
        $total_visits = Track::select(\DB::raw("COUNT(*) as count"))->groupBy(\DB::raw("Month(date)"))->first();

        # get all payment
        $payments   = DB::select("SELECT COUNT(*) AS `payment`, IFNULL(TRUNCATE(SUM(`price`), 2), 0) AS `earnings` FROM `payment` WHERE `currency` = '{$settings->currency}' AND MONTH(`date`) = MONTH(CURRENT_DATE()) AND YEAR(`date`) = YEAR(CURRENT_DATE())");

        # get all users
        $users = DB::select('SELECT (SELECT COUNT(*) FROM `users` WHERE MONTH(`activity`) = MONTH(CURRENT_DATE()) AND YEAR(`activity`) = YEAR(CURRENT_DATE())) AS `active_users_month`,
              (SELECT COUNT(*) FROM `users`) AS `all_users`');

        # get new users from last month stat date
        $newusers = User::select(DB::raw('*, DATE(`created_at`) as `date`'), DB::raw('COUNT(*) as `count`'))
                    ->where('created_at', '>=', $fromDate)
                    ->groupBy('email')
                    ->limit(4)
                    ->orderBy('date', 'DESC')
                    ->get();

        $topUsers = $track
        ->leftJoin('users', 'users.id', '=', 'track.user')
        ->select('users.name', 'users.username', 'users.id as user_id', DB::raw('count(user) as total'))
        ->groupBy('user')
        ->where('track.date', '>=', $fromYear)
        ->orderBy('total', 'DESC')
        ->limit(5)
        ->get();

        # Payments Chart
        $paymentschart = [];
        $results = Payments::select(\DB::raw("COUNT(*) as count, DATE_FORMAT(`date`, '%Y-%m-%d') AS `formatted_date`, TRUNCATE(SUM(`price`), 2) AS `amount`"))
        ->where('date', '>=', $thisMonth)
        ->groupBy('formatted_date')
        ->get();

        foreach ($results as $value) {
            $value->formatted_date = Carbon::parse($value->formatted_date)->toFormattedDateString();
            $paymentschart[$value->formatted_date] = [
                'sales' => $value->count,
                'amount' => $value->amount
            ];
        }
        $sidebarR = (object) ['countPackages' => Packages::count(), 'countUnreadSupport' => Support::where('viewed', 0)->count()];
        /*
        if (!$settings->registration) {
            $messageSetting = 'You can enable user registration here';
        }elseif (!$settings->) {
            # code...
        }*/

        $paymentschart = $general->get_chart_data($paymentschart);

        # get current user
        $user = Auth::user();

        # define array of counters
        $count = ['total_visits' => $total_visits, 'users' => count($users)];

        # view page with array
        return view('admin.admin', ['count' => $count, 'newusers' => $newusers, 'topusers' => $topUsers, 'payments' => $payments, 'user' => $user, 'paymentschart' => $paymentschart, 'users' => $users, 'sidebarR' => $sidebarR]);
    }


    public function domains(Domains $domains){
        $allDomains = $domains->leftJoin('users', 'users.domain', '=', 'domains.id')->select('domains.*', DB::raw("count(users.domain) AS total_domain"))->groupBy('domains.id')->get();
        return view('admin.domains.domains', ['domains' => $allDomains]);
    }

    public function domains_post_get(Domains $domains, Request $request){
        $domain_id = $request->get('id');
        $domain = null;
        if ($request->get('delete') == true) {
            $domains->find($request->get('id'))->delete();
            return back()->with('success', __('Deleted successfully'));
        }
        if (!empty($domain_id) && $domain = $domains->where('id', $domain_id)->first()) {
        }

        return view('admin.domains.post-domain', ['domain' => $domain]);
    }

    public function domains_post(Domains $domains, Request $request){
        if (is_object($this->code) && $this->code->license !== 'Extended License') {
            return back()->with('error', 'License upgrade required!');
        }
        $request->validate([
            'scheme' => 'required',
            'host' => 'required',
        ]);
        $requests = $request->all();
        unset($requests['_token'], $requests['submit'], $requests['domain_id']);
        $requests['created_at'] = Carbon::now($this->settings->timezone);
        if (isset($request->domain_id)) {
            unset($requests['created_at']);
            $requests['updated_at'] = Carbon::now($this->settings->timezone);
            $update = $domains->where('id', $request->domain_id)->update($requests);
            return back()->with('success', __('Domain updated successfully'));
        }
        $new = $domains->insert($requests);
        return redirect()->route('admin-domains')->with('success', __('Domain created successfully'));
    }

    public function admin_trans(Request $request){
       $trans = $request->get('trans');
       $locale = config('app.locale');
       try {
           $transpath = file_get_contents(resource_path('lang/'.config('app.locale').'.json'));
       } catch (\Exception $e) {
           $transpath = NULL;
       }
       if (!empty($trans)) {
           if (file_exists(resource_path('lang/'.$trans.'.json'))) {
            $locale = $trans;
            $transpath = file_get_contents(resource_path('lang/'.$trans.'.json'));
           }else{
             return redirect()->route('admin-translation');
           }
       }
       $transpath = json_decode($transpath, true);
       !empty($transpath) ? asort($transpath) : '';
       $path = resource_path('lang');
       $alltransfiles = File::files($path);

       return view('admin.translation.trans', ['alltrans' => $alltransfiles, 'trans_files' => $transpath, 'locale' => $locale]);
    }

    public function admin_post_trans(Request $request, $type){
        $slugGenerator = new SlugGenerator;
        if (file_exists(resource_path('lang/'.$request->trans.'.json'))) {
            $transpath = file_get_contents(resource_path('lang/'.$request->trans.'.json'));
            $transpath = json_decode($transpath);
        }
        if ($type == 'post') {
            $transpath->{$request->key} = $request->value;
            file_put_contents(resource_path('lang/'.$request->trans.'.json'), json_encode($transpath));
        }elseif ($type == 'delete') {
            unset($transpath->{$request->key});
            file_put_contents(resource_path('lang/'.$request->trans.'.json'), json_encode($transpath));
        }elseif ($type == 'edit') {
            unset($transpath->{$request->previous_key});
            $transpath->{$request->key} = $request->value;
            file_put_contents(resource_path('lang/'.$request->trans.'.json'), json_encode($transpath));
        }elseif ($type == 'new') {
            if (file_exists(resource_path('lang/'.strtolower($request->name).'.json'))) {
                return back()->with('error', __('Translation file exists'));
            }
            file_put_contents(resource_path('lang/'.$slugGenerator->generate($request->name, ['delimiter' => '_']).'.json'), '{}');
        }elseif ($type == 'delete-trans') {
            unlink(resource_path('lang/'.strtolower($request->trans).'.json'));
            return back()->with('success', 'Saved successfully');
        }elseif ($type == 'set-active') {
            $update_env = [
                 'APP_LOCALE'            => $request->locale,
            ];
            $this->changeEnv($update_env);
        }
        return back()->with('success', 'Saved successfully');
    }

    # settings function
    public function settings(Domains $domains){
        $path = resource_path('lang');
        $alltransfiles = File::files($path);
        $domains    = $domains->where('status', 1)->get();

        # get all timezone and of current admin user
        $timezone_select = Timezone::selectForm((!empty($this->settings->timezone) ? $this->settings->timezone : "Africa/Lagos"), '', ['class' => 'form-select', 'name' => 'timezone', 'data-ui' => 'lg', 'data-search' => 'on']);

        # view page with array
        return view('admin.settings', ['timezone' => $timezone_select, 'alltransfiles' => $alltransfiles, 'domains' => $domains]);
    }

    public function delete_category(Request $request){
        if (strtoupper($request->delete) !== strtoupper("delete")) {
            # redirect to page with error
            return back()->with('error', 'Word not correct');
        }
        if (!Category::where('id', $request->category_id)->exists()) {
            return back()->with('Category does not exists');
        }
        $category = Category::find($request->category_id);
        $category->delete();
        return redirect()->route('category')->with('success', 'That category was successfully deleted');
    }

    # categroy function
    public function category() {
       # get all categories
       $categories = Category::get();

        # view page with array
       return view('admin.page.category.all', ['categories' => $categories]);
    }
    # add category function
    public function add_category() {
        # view page
        return view('admin.page.category.add');
    }
    # post category function
    public function post_category(Request $request) {
        $general = new General();
        $slug = $general->generate_slug($request->url);
        # get values from request
        $value = array('title' => $request->title, 'description' => $request->description, 'icon' => $request->icon, 'url' => $slug, 'order' => $request->order, 'status' => $request->status, 'date' => Carbon::now($this->settings->timezone));

        # insert category
        Category::insert($value);

        # redirect to categories page with success
        return redirect()->route('category')->with("success", "saved successfully");
    }

    # edit category function
    public function edit_category($id) {
        #check if category exists and continue
        if (Category::where('id', $id)->count() > 0) {
            #get current category
            $category = Category::where('id', $id)->first();
            #view page with array
            return view('admin.page.category.edit', ['category' => $category]);
        }else{
            abort(404, 'Not Found');
        }
    }

    # edit post category
    public function edit_post_category(Request $request) {
        $general = new General();
        $slug = $general->generate_slug($request->url);
        
        # get values from request
        $value = array('title' => $request->title, 'description' => $request->description, 'icon' => $request->icon, 'url' => $slug, 'order' => $request->order, 'status' => $request->status, 'edited_on' => Carbon::now($this->settings->timezone));

        # update category
        Category::where('id', $request->category_id)->update($value);
        return back()->with("success", "saved successfully");
    }


    
    # faq function
    public function faq() {
        #get faq
        $faqs = Faq::get();
        #view page with array
        return view('admin.faq', ['faqs' => $faqs]);
    }

    # post faq
    public function post_faq(Request $request) {
        # get values from request
        $value = array('name' => $request->name, 'note' => $request->note, 'status' => $request->status, 'date' => Carbon::now($this->settings->timezone));

        # insert Faq
        Faq::insert($value);
        # redirect to faq page with success
        return redirect()->route('faq')->with("success", "saved successfully");
    }

    public function delete_faq(Request $request){
        if (strtoupper($request->delete) !== strtoupper("delete")) {
            # redirect to page with error
            return back()->with('error', 'word not correct');
        }
        if (!Faq::where('id', $request->faq_id)->exists()) {
            return back()->with('faq does not exists');
        }
        $faq = Faq::find($request->faq_id);
        $faq->delete();
        return redirect()->route('faq')->with('success', 'That faq was successfully deleted');
    }

    #edit faq
    public function edit_faq(Request $request) {
        # get values from request
        $value = array('name' => $request->name, 'note' => $request->note, 'status' => $request->status, 'edited_on' => Carbon::now($this->settings->timezone));

        # update faq
        Faq::where('id', $request->faq_id)->update($value);

        # redirect to faq page with success
        return back()->with("success", "saved successfully");
    }

    # Delete banner with get
    public function delete_userbanner($id){
        # check if user exists
        if (!User::where('id', $id)->exists()) {
            abort(404);
        }
        # get user 
        $user = User::find($id);
        # check if banner row is not empty
        if (!empty($user->banner)) {
            # check if file exisits
            if(file_exists(public_path('img/user/banner/' . $user->banner))){
                #unlink the file
                unlink(public_path('img/user/banner/' . $user->banner));
                # redirect to page with success
                return redirect(route('admin-users') . '/' . $user->id)->with('success', 'successfully removed your banner');
            }
        }
        # redirect to page with nothing
        return redirect()->route('profile');
    }


    # Users funtion

    public function user_post(Request $request){
        # get all from GENERAL CONTROLLER
        $general = new General();
        # get user from requests
        $user = User::where('id', $request->user_id)->first();
        # define bool value
        $request->settings_showbuttombar = (bool) $request->settings_showbuttombar;
        #validate some requests
        $request->validate([
            'email' => 'required|email|string|max:255|unique:users,email,'.$user->id,
            'username' => 'required|string|max:255|unique:users,username,'.$user->id,
        ]);
        #array settings to be updated
        $settings_keys = ['name', 'about','active', 'verified', 'username', 'email', 'settings' => ['work_experience', 'template', 'default_color', 'showbuttombar', 'branding', 'custom_branding', 'custom_branding_text', 'general_color', 'location', 'tagline']];
        #loop array adn update
        foreach ($settings_keys as $key => $value) {
            # if its sub array
            if(is_array($value)) {

                $values_array = [];

                foreach ($value as $sub_key) {
                    $values_array[$sub_key] = $request->{$key . '_' . $sub_key};
                }

                $value = json_encode($values_array);

            } else {
                # if it's single
                $key = $value;
                $value = $_POST[$key];
            }
            #update the user
            $value = array($key => $value);
            User::where('id', $user->id)->update($value);
        }
        # avatar
        if (!empty($request->avatar)) {
            #validate request
            $request->validate([
                'avatar' => 'image|mimes:jpeg,png,jpg,gif,svg|max:1024',
            ]);
            # check if avatar row is not empty
            if (!empty($user->avatar)) {
                # check if file exisits
                if(file_exists(public_path('img/user/avatar/' . $user->avatar))){
                    #unlink the file
                    unlink(public_path('img/user/avatar/' . $user->avatar)); 
                }
            }
            #generate new image name
            $imageName = md5(microtime());
            $imageName = $imageName . '.' .$request->avatar->extension();
            #move avatar to folder
            $request->avatar->move(public_path('img/user/avatar'), $imageName);
            #update user
            $values = array('avatar' => $imageName);
            User::where('id', $user->id)->update($values);
        }
        # background
        if (!empty($request->banner)) {
            #validate request
            $request->validate([
                'banner' => 'image|mimes:jpeg,png,jpg,gif,svg|max:1024',
            ]);
            # check if banner row is not empty
            if (!empty($user->banner)) {
                # check if file exisits
                if(file_exists(public_path('img/user/banner/' . $user->banner))){
                    #unlink the file
                    unlink(public_path('img/user/banner/' . $user->banner)); 
                }
            }
            #generate new image name
            $imageName = md5(microtime());
            $imageName = $imageName . '.' .$request->banner->extension();
            #move avatar to folder
            $request->banner->move(public_path('img/user/banner'), $imageName);
            #update user
            $values = array('banner' => $imageName);
            User::where('id', $user->id)->update($values);
        }
        # update background
        $array = array('background_type' => $request->background_type, 'background' => ($request->background_type == 'color') ? $request->background : (($request->background_type == "gradient") ? $request->background_gradient : ""));
        User::where('id', $user->id)->update($array);
        # update link row
        $array = array('column' => $request->link_row_column, 'color_type' => $request->link_row_color_type, 'textcolor' => $request->link_row_textcolor, 'background' => $request->link_row_background, 'outline' => $request->link_row_outline, 'radius' => $request->link_row_radius);
        $array = json_encode($array);
        $array = array('link_row' => $array, 'package' => $request->package, 'package_due' => $request->package_due);
        User::where('id', $user->id)->update($array);
        # redirect to page with success
        return back()->with('success', 'saved successfully');

    }
    public function send_usermail(Request $request){
        #get user from request
        $user = User::find($request->user_id);
        # get all from GENERAL CONTROLLER
        $general = new General();
        #define shortcodes for subject
        $subject = $request->subject;
        $subject = str_replace("{{username}}", $user->username, $subject);
        $subject = str_replace("{{name}}", $user->name, $subject);
        $subject = str_replace("{{email}}", $user->email, $subject);
        #define shortcode for messages
        $message = $request->message;
        $message = str_replace("{{username}}", $user->username, $message);
        $message = str_replace("{{name}}", $user->name, $message);
        $message = str_replace("{{email}}", $user->email, $message);
        $message = str_replace("{{tagline}}", $user->settings->tagline ?? '', $message);
        $message = str_replace("{{last_login}}", $user->activity, $message);
        $message = str_replace("{{package_name}}", $general->package($user)->name, $message);
        $message = str_replace("{{count_links}}", Links::where('user', $user->id)->count(), $message);
        $message = str_replace("{{count_portfolio}}", Portfolio::where('user', $user->id)->count(), $message);
        $message = str_replace("{{package_due}}", Carbon::parse($user->package_due)->toFormattedDateString(), $message);

        # send the email 
        $email = (object) array('subject' => $subject, 'message' => $message);
        try {
         Mail::to($user->email)->send(new GeneralMail($email));
        } catch (\Exception $e) {
            return back()->with('error', 'could not send email. smtp error.');
         }
        # redirect to page with success
        return back()->with('success', 'Email sent');
    }
    public function users(Request $request) {
        #get users
        $users = User::leftJoin('links', 'links.user', '=', 'users.id')
            ->select('users.*', DB::raw("count(links.user) AS total_links"))
            ->groupBy('users.id');
        if (!empty($request->get('email'))) {
            $users->where('users.email', 'LIKE','%'.$request->get('email').'%');
        }
        if (!empty($request->get('username'))) {
            $users->where('users.username', 'LIKE','%'.$request->get('username').'%');
        }

        $users = $users->paginate(10);


        #view page with array
        return view('admin.users.users', ['users' => $users]);
    }

    public function user_view($id){
        $general = new General();
        if (!User::where('id', $id)->exists()) {
            abort(404);
        }
        $user = User::where('id', $id)->first();
        $package = $general->package($user);
        $packages = Packages::get();
        $themes = $general->gradient_preset();
        $templates  = $general->get_json_data('Profiletemplates');
        #view page with array
        return view('admin.users.user', ['user' => $user, 'packages' => $packages, 'package' => $package, 'themes' => $themes, 'templates' => $templates]);
    }

    public function delete_user(Request $request){
        if ($request->user_id == Auth()->user()->id) {
            return back()->with('error', 'cant delete yourself');
        }
        if (strtoupper($request->delete) !== strtoupper("delete")) {
            # redirect to page with error
            return back()->with('error', 'Word not correct');
        }
        $user = User::find($request->user_id);
        $portfolios = Portfolio::where('user', $user->id)->get();
        if (!empty($user->banner)) {
            # check if file exisits
            if(file_exists(public_path('img/user/banner/' . $user->banner))){
                #unlink the file
                unlink(public_path('img/user/banner/' . $user->banner));
            }
        }
        if (!empty($user->avatar)) {
            # check if file exisits
            if(file_exists(public_path('img/user/avatar/' . $user->avatar))){
                #unlink the file
                unlink(public_path('img/user/avatar/' . $user->avatar));
            }
        }
        foreach ($portfolios as $key) {
            if (!empty($key->image)) {
                if(file_exists(public_path('img/user/portfolio/' . $key->image))){
                    unlink(public_path('img/user/portfolio/' . $key->image)); 
                }
            }
        }
        Track::where('user', $request->user_id)->delete();
        Links::where('user', $request->user_id)->delete();
        Portfolio::where('user', $request->user_id)->delete();
        Support::where('user', $request->user_id)->delete();
        SupportReply::where('user', $request->user_id)->delete();
        DB::table('users_logs')->where('user', $request->user_id)->delete();
        User::where('id', $request->user_id)->delete();
        return back()->with('success', 'That user was deleted');
    }
    // Payments

    public function payments(Request $request) {
        $email = $request->get('email');
        $ref   = $request->get('ref');
        $payments = Payments::leftJoin('users', 'users.id', '=', 'payment.user')
            ->leftJoin('packages', 'packages.id', '=', 'payment.package')
            ->select('users.username', DB::raw("packages.name AS packages_name"), 'payment.*', DB::raw("count(payment.user) AS total_links"), DB::raw('COUNT(*) as `count`'));

        if (!empty($email)) {
            $payments->where('payment.email','LIKE','%'.$email.'%');
        }

        if (!empty($ref)) {
            $payments->where('payment.ref','LIKE','%'.$ref.'%');
        }

        $payments = $payments->groupBy('payment.id')->orderBy('id', 'DESC')->paginate(10);
        #view page with array
        return view('admin.payments', ['payments' => $payments]);
    }

    public function pending_payments(PendingPayments $payments){
        $allpayments = $payments->
                       leftJoin('users', 'users.id', '=', 'pending_payments.user')
                        ->leftJoin('packages', 'packages.id', '=', 'pending_payments.package')
                        ->select('users.username', DB::raw("packages.name AS package_name"), 'pending_payments.*')
                        ->groupBy('pending_payments.id')
                        ->orderBy('id', 'DESC')
                       ->paginate(15);
        $options = (object) ['payments' => $allpayments];
        return view('admin.pending-payments', ['options' => $options]);
    }

    public function activate_pending_payment($type, $id, PendingPayments $payments, User $user){
        if (!$payments->where('id', $id)->exists()) {
            abort(404);
        }
        $pending = $payments->find($id);
        $user = $user->find($pending->user);
        $package = Packages::where('id', $pending->package)->first();
        if ($type == 'approve') {
            $newdue = Carbon::now($this->settings->timezone);
            $payment_date = NULL;
            if ($pending->duration == "month") {
                $newdue->addMonths(1);
                $payment_date = $newdue;
            }
            if ($pending->duration == "quarter") {
                $newdue->addMonths(6);
                $payment_date = $newdue;
            }
            if ($pending->duration == "annual") {
                $newdue->addMonths(12);
                $payment_date = $newdue;
            }
            $user->package = $pending->package;
            $user->package_due = $payment_date;
            $user->save();

            $newPayment = new Payments;
            $newPayment->user = $user->id;
            $newPayment->email = $user->email;
            $newPayment->name = $user->name;
            $newPayment->ref = 'PR_'. $this->randomShortname();
            $newPayment->package_name  = $package->name;
            $newPayment->price     = $package->price->{$pending->duration} ?? Null;
            $newPayment->currency  = $this->settings->currency;
            $newPayment->package = $pending->package;
            $newPayment->duration = $pending->duration;
            $newPayment->gateway = "Bank transfer";
            $newPayment->date = Carbon::now($this->settings->timezone);
            $newPayment->save();
            
            $pending->status = 1;
            $pending->save();
            return back()->with('success', 'Approved');
        }elseif ($type == 'decline') {
            $pending->status = 2;
            $pending->save();
            return back()->with('success', 'Payment Declined');
        }elseif ($type == 'delete') {
            if (!empty($pending->proof)) {
                if(file_exists(public_path('img/user/bankProof/' . $pending->proof))){
                     unlink(public_path('img/user/bankProof/' . $pending->proof)); 
                }
            }
            $pending->delete();
            return back()->with('success', 'Payment Deleted');
        }
        return back()->with('error', 'Undefined error');
    }


    // Packages

    public function packages() {
        $packages = Packages::leftJoin('users', 'users.package', '=', 'packages.id')
                    ->select('packages.*', DB::raw("count(users.package) AS total_package"))
                    ->groupBy('packages.id')
                    ->get();
        $free_count = User::where('package', 'free')->count();
        #view page with array
        return view('admin.packages.all', ['packages' => $packages, 'free_count' => $free_count]);
    }

    public function create_packages() {
        $general = new General();
        $templates = $general->get_json_data('Profiletemplates');
        foreach ($templates as $key => $value) {
            if ($value->default == 'yes') {
                unset($templates->{$key});
            }
        }
        return view('admin.packages.create', ['templates' => $templates]);
    }


    public function post_packages(Request $request) {


        $general = new General();

        $form = $request->all();
        unset($form['_token'], $form['package_name'], $form['status'], $form['month'], $form['quarter'], $form['annual'], $form['portfolio_limit'], $form['links_limit'], $form['support_limit']);
        $dataSettings = [];
        foreach ($form as $key => $values) {
            $dataSettings[$key] = (bool) $values;

        }
        $dataSettings['links_limit'] = $request->links_limit;
        $dataSettings['support_limit'] = $request->support_limit;
        $dataSettings['portfolio_limit'] = $request->portfolio_limit;
        $prices = $request->only('month', 'quarter', 'annual');

        $plan = new Packages();
        $plan->name     = $request->package_name;
        $plan->slug     = $general->generate_slug($request->package_name);
        $plan->status   = $request->status;
        $plan->price    = $prices;
        $plan->settings = $dataSettings;
        $plan->date     = Carbon::now($this->settings->timezone);
        $plan->save();
        return back()->with("success", "saved successfully");
    }
    
    public function edit_package($id) {
        $general = new General();
        $templates = $general->get_json_data('Profiletemplates');
        foreach ($templates as $key => $value) {
            if ($value->default == 'yes') {
                unset($templates->{$key});
            }
        }
        if (!Packages::where('id', $id)->exists() && $id !== 'free') {
            abort(404);
        }
        if ($id !== 'free') {
            $package = Packages::where('id', $id)->first();
        }else{
            $package = $this->settings->package_free;
        }
        return view('admin.packages.edit', ['package' => $package, 'templates' => $templates]);
    }
    
    public function edit_post_package(Request $request, $id) {
        $general = new General();

        $form = $request->all();
        unset($form['_token'], $form['package_name'], $form['status'], $form['month'], $form['quarter'], $form['annual'], $form['portfolio_limit'], $form['links_limit'], $form['support_limit']);
        $dataSettings = [];
        foreach ($form as $key => $values) {
            $dataSettings[$key] = (bool) $values;

        }
        $dataSettings['links_limit'] = $request->links_limit;
        $dataSettings['support_limit'] = $request->support_limit;
        $dataSettings['portfolio_limit'] = $request->portfolio_limit;
        $prices = $request->only('month', 'quarter', 'annual');
        if ($id == 'free') {
            $prices = array("month" => "FREE", "quarter" =>  "FREE", "annual" =>  "FREE");
            $values = array("id" => $id, "name" => $request->package_name, "status" => $request->status, "price" => $prices, "settings" => $dataSettings);
            $values = json_encode($values);
            $values = array("value" => $values);
            Settings::where('key', 'package_' . $id)->update($values);
        }else{
            $plan = Packages::find($id);
            $plan->name     = $request->package_name;
            $plan->slug     = $general->generate_slug($request->package_name);
            $plan->status   = $request->status;
            $plan->price    = $prices;
            $plan->settings = $dataSettings;
            $plan->date     = Carbon::now($this->settings->timezone);
            $plan->save();
        }

        return back()->with("success", "saved successfully");
    }

    public function delete_package(Request $request){
        if (strtoupper($request->delete) !== strtoupper("delete")) {
            # redirect to page with error
            return back()->with('error', 'word not correct');
        }
        if (!Packages::where('id', $request->package_id)->exists()) {
            return back()->with('package does not exists');
        }
        $package = Packages::find($request->package_id);
        User::where('package', $package->id)->update(array('package' => 'free', 'package_due' => NULL));
        $package->delete();
        return redirect()->route('admin-packages')->with('success', 'That package was successfully deleted');
    }



    // Stats 
    public function stats(Request $request){
        $general = new General();
        $start_date = $request->get('start_date');
        $end_date   = $request->get('end_date');
        $username   = $request->get('username');
        $start_date = isset($start_date) ? $start_date : Carbon::now($this->settings->timezone)->subDays(30)->format('Y-m-d');
        $end_date = isset($end_date) ? $end_date : Carbon::now($this->settings->timezone)->format('Y-m-d');
        $date = $general->get_start_end_dates($start_date, $end_date);


        # User Chart
        $userschart = [];
        $results = User::select(\DB::raw("COUNT(*) as count, DATE_FORMAT(`created_at`, '%Y-%m-%d') AS `formatted_date`"))->whereBetween('created_at', [$date->start_date_query, $date->end_date_query])->groupBy('formatted_date')->orderBy('formatted_date')->get();

        foreach ($results as $value) {
            $value->formatted_date = Carbon::parse($value->formatted_date)->toFormattedDateString();
            $userschart[$value->formatted_date] = [
                'users' => $value->count
            ];
        }

        $userschart = $general->get_chart_data($userschart);


        # Links Chart
        $linkschart = [];
        $results = Links::select(\DB::raw("COUNT(*) as count, DATE_FORMAT(`date`, '%Y-%m-%d') AS `formatted_date`"))->whereBetween('date', [$date->start_date_query, $date->end_date_query])->groupBy('formatted_date')->orderBy('formatted_date')->get();

        foreach ($results as $value) {
            $value->formatted_date = Carbon::parse($value->formatted_date)->toFormattedDateString();
            $linkschart[$value->formatted_date] = [
                'count' => $value->count
            ];
        }

        $linkschart = $general->get_chart_data($linkschart);


        # Payments Chart
        $paymentschart = [];
        $results = Payments::select(\DB::raw("COUNT(*) as count, DATE_FORMAT(`date`, '%Y-%m-%d') AS `formatted_date`, TRUNCATE(SUM(`price`), 2) AS `amount`"))->whereBetween('date', [$date->start_date_query, $date->end_date_query])->groupBy('formatted_date')->get();

        foreach ($results as $value) {
            $value->formatted_date = Carbon::parse($value->formatted_date)->toFormattedDateString();
            $paymentschart[$value->formatted_date] = [
                'count' => $value->count,
                'amount' => $value->amount
            ];
        }

        $paymentschart = $general->get_chart_data($paymentschart);

        # Profile visits
        $profilevisitschart = [];
        $results = Track::leftJoin('users', 'users.id', '=', 'track.user')
        ->select(\DB::raw("COUNT(*) as count, track.count as views, users.username as username, DATE_FORMAT(`date`, '%Y-%m-%d') AS `formatted_date`"))->whereBetween('date', [$date->start_date_query, $date->end_date_query]);
        if (!empty($username)) {
            $results->where('username','LIKE','%'.$username.'%');
        }
        $results = $results->groupBy('formatted_date')->orderBy('formatted_date')->get();

        foreach ($results as $value) {
            $value->formatted_date = Carbon::parse($value->formatted_date)->toFormattedDateString();
            if(!array_key_exists($value->formatted_date, $profilevisitschart)) {
                $profilevisitschart[$value->formatted_date] = [
                    'impression'        => 0,
                    'unique'            => 0,
                    'count' => $value->count
                ];
            }
            $profilevisitschart[$value->formatted_date]['unique']++;
            $profilevisitschart[$value->formatted_date]['impression'] += $value->views;
        }

        $profilevisitschart = $general->get_chart_data($profilevisitschart);

        $options = (object) ['start_date' => $start_date, 'end_date' => $end_date, 'date' => $date, 'userschart' => $userschart, 'linkschart' => $linkschart, 'profilevisitschart' => $profilevisitschart, 'paymentschart' => $paymentschart];

        #view page with array
        return view('admin.stats.stats', ['options' => $options]);
    }

    public function stats_browser(){
        $visit_chart = Track::select(\DB::raw("`country`,`os`,`browser`,`referer`,`count`, DATE_FORMAT(`date`, '%Y-%m-%d') AS `formatted_date`"))->get();

        $logs_data = ['browser'  => []];
        foreach ($visit_chart as $key) {
            
          if(!array_key_exists($key->browser, $logs_data['browser'])) {
              $logs_data['browser'][$key->browser ?? 'N/A'] = 1;
          } else {
              $logs_data['browser'][$key->browser]++;
          }
        }
        arsort($logs_data['browser']);
        #view page with array
        return view('admin.stats.browser', ['logs_data' => $logs_data]);
    }

    public function stats_os(){
        $visit_chart = Track::select(\DB::raw("`country`,`os`,`browser`,`referer`,`count`, DATE_FORMAT(`date`, '%Y-%m-%d') AS `formatted_date`"))->get();

        $logs_data = ['os'  => []];
        foreach ($visit_chart as $key) {
            
          if(!array_key_exists($key->os, $logs_data['os'])) {
              $logs_data['os'][$key->os ?? 'N/A'] = 1;
          } else {
              $logs_data['os'][$key->os]++;
          }
        }
        arsort($logs_data['os']);
        #view page with array
        
        return view('admin.stats.os', ['logs_data' => $logs_data]);
    }

    public function stats_traffic(){
        $visit_chart = Track::select(\DB::raw("`country`,`os`,`browser`,`referer`,`count`, DATE_FORMAT(`date`, '%Y-%m-%d') AS `formatted_date`"))->get();

        $logs_data = ['referer'  => []];
        foreach ($visit_chart as $key) {
            if(!array_key_exists($key->referer, $logs_data['referer'])) {
                $logs_data['referer'][$key->referer ?? 'false'] = 1;
            } else {
                $logs_data['referer'][$key->referer]++;
            }
        }
        arsort($logs_data['referer']);
        #view page with array
        
        return view('admin.stats.traffic', ['logs_data' => $logs_data]);
    }

    public function stats_country(){
        $visit_chart = Track::select(\DB::raw("`country`,`os`,`browser`,`referer`,`count`, DATE_FORMAT(`date`, '%Y-%m-%d') AS `formatted_date`"))->get();

        $logs_data = ['country'  => []];
        foreach ($visit_chart as $key) {
            if(!array_key_exists($key->country, $logs_data['country'])) {
                $logs_data['country'][$key->country ?? 'false'] = 1;
            } else {
                $logs_data['country'][$key->country]++;
            }
        }
        arsort($logs_data['country']);
        #view page with array
        
        return view('admin.stats.country', ['logs_data' => $logs_data]);
    }


    public function stats_user(){
        $visit_chart = Track::select(\DB::raw("`country`,`os`,`browser`,`referer`,`count`, DATE_FORMAT(`date`, '%Y-%m-%d') AS `formatted_date`"))->get();

        $logs_data = ['country'  => []];
        foreach ($visit_chart as $key) {
            if(!array_key_exists($key->country, $logs_data['country'])) {
                $logs_data['country'][$key->country ?? 'false'] = 1;
            } else {
                $logs_data['country'][$key->country]++;
            }
        }
        arsort($logs_data['country']);
        print_r($logs_data);
        return ;
        #view page with array
        return view('admin.stats.country', ['logs_data' => $logs_data]);
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



    // Pages
    public function delete_page(Request $request){
        if (strtoupper($request->delete) !== strtoupper("delete")) {
            # redirect to page with error
            return back()->with('error', 'Word not correct');
        }
        if (!Pages::where('id', $request->page_id)->exists()) {
            return back();
        }
        $page = Pages::find($request->page_id);
        if (!empty($page->image)) {
            if(file_exists(public_path('img/pages/' . $page->image))){
                unlink(public_path('img/pages/' . $page->image)); 
            }
        }
        $page->delete();
        return redirect()->route('pages')->with('success', 'That page was successfully deleted');
    }
    public function pages() {
       $pages = Pages::orderBy('id', 'DESC')->get();
       #view page with array
       return view('admin.page.all', ['pages' => $pages]);
    }

    public function add_pages(){
        $categories = Category::where('status', 1)->get();
        #view page with array
        return view('admin.page.add', ['categories' => $categories]);
    }

    public function edit_pages($id){
        if (Pages::where('id', $id)->count() > 0) {
            $page = Pages::where('id', $id)->first();
            $categories = Category::where('status', 1)->get();
            $page->settings = json_decode($page->settings);
            #view page with array
            return view('admin.page.edit', ['page' => $page, 'categories' => $categories]);
        }else{
            abort(404, 'Not Found');
        }
    }

    public function edit_post_page(Request $request){
        $general = new General();
        $page = Pages::where('id', $request->page_id)->first();
        $json = array('sh_description' => $request->sh_description, 'content' => $request->content);
        $json = json_encode($json);
        $url = $general->generate_slug($request->url);
        $value = array('title' => $request->title, 'category' => $request->category, 'type' => $request->type, 'status' => $request->status, 'url' => $url, 'order' => $request->order, 'settings' => $json, 'edited_on' => Carbon::now($this->settings->timezone));

        Pages::where('id', $page->id)->update($value);
        if (!empty($request->image)) {
            $slug = md5(microtime());
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            $imageName = $slug . '.' .$request->image->extension();

            if (!empty($page->image)) {
                if(file_exists($_SERVER['DOCUMENT_ROOT'] . '/img/pages/' . $page->image)){
                    unlink($_SERVER['DOCUMENT_ROOT'] . '/img/pages/' . $page->image); 
                }
            }
            $request->image->move(public_path('img/pages'), $imageName);
            $values = array('image' => $imageName);
            Pages::where('id', $page->id)->update($values);
        }

        return back()->with("success", "saved successfully");
    }

    public function post_page(Request $request){
        
        $general = new General();
        $url = $general->generate_slug($request->url);
        $request->validate([
            'url' => 'required|string|max:25|unique:pages',
        ]);

        $json = array('sh_description' => $request->sh_description, 'content' => $request->content);
        $json = json_encode($json);
        $value = array('title' => $request->title, 'category' => $request->category, 'type' => $request->type, 'status' => $request->status, 'url' => $url, 'order' => $request->order, 'settings' => $json, 'date' => Carbon::now($this->settings->timezone));

        $id = Pages::insertGetId($value);
        if (!empty($request->image)) {
            $slug = md5(microtime());
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            $imageName = $slug . '.' .$request->image->extension();

            $request->image->move(public_path('img/pages'), $imageName);
            $values = array('image' => $imageName);
            Pages::where('id', $id)->update($values);
        }

        return redirect()->route('pages')->with("success", "saved successfully");
    }


    public function randomnumbers($min = 4, $max = 9) {
      $length = rand($min, $max);
      $chars = array_merge(range("0", "9"));
      $max = count($chars) - 1;
      $randomnumbers = '';
      for($i = 0; $i < $length; $i++) {
        $char = random_int(0, $max);
        $randomnumbers .= $chars[$char];
      }
      return $randomnumbers;
    }


    //Links


    public function links(){
        $links = Links::join('users', 'users.id', '=', 'links.user')
            ->leftJoin('track', 'track.dyid', '=', 'links.id')
            ->select('links.*', 'users.username', DB::raw("count(track.dyid) AS track_links"))
            ->groupBy('links.id')
            ->orderBy('id', 'DESC')
            ->get();
        $users = User::get();
        #view page with array
        return view('admin.links.links', ['links' => $links, 'users' => $users]);
    }
    public function link_delete($id){
        if (!Links::where('id', $id)->exists()) {
            abort(404);
        }
        $link = Links::find($id);
        $link->delete();
        Track::where('dyid', $id)->delete();
        return redirect()->route('admin-link')->with('success', 'That link was successfully deleted');
    }
    public function post_link(Request $request) {

        // VALIDATE

        $request->validate([
            'url' => 'required|url',
            'name' => 'required|string|min:2',
        ]);

        // Define request
        $name = $request->name;
        $url  = $request->url;
        $note = $request->note;
        $slug = $this->randomShortname();
        $user = $request->user;
        $values = array('user' => $user, 'name' => $name, 'url' => $url, 'url_slug' => $slug, 'note' => $note, 'date' => Carbon::now($this->settings->timezone));

        $values_update = array('name' => $name, 'url' => $url, 'note' => $note, 'date' => Carbon::now($this->settings->timezone));
        if (!isset($request->link_id)) {
            Links::insert($values);
        }else{
          Links::where('id', $request->link_id)->update($values_update);
        }
        return back()->with('success', 'Link posted');
    }




    //Portfolio


    public function portfolio(){
        $portfolios = Portfolio::join('users', 'users.id', '=', 'portfolio.user')
            ->leftJoin('track', 'track.dyid', '=', 'portfolio.id')
            ->select('portfolio.*', 'users.username', DB::raw("count(track.dyid) AS track_links"))
            ->groupBy('portfolio.id')
            ->orderBy('id', 'DESC')
            ->get();
        $users = User::get();
        #view page with array
        return view('admin.portfolio.portfolios', ['portfolio' => $portfolios, 'users' => $users]);
    }

    public function portfolio_delete($id){
        if (!Portfolio::where('id', $id)->exists()) {
            abort(404);
        }
        $portfolio = Portfolio::find($id);
        Track::where('dyid', $id)->delete();
        if (!empty($portfolio->image)) {
            if(file_exists(public_path('img/user/portfolio/' . $portfolio->image))){
                unlink(public_path('img/user/portfolio/' . $portfolio->image)); 
            }
        }
        $portfolio->delete();
        return redirect()->route('admin-portfolio')->with('success', 'That portfolio was successfully deleted');
    }

    public function post_portfolio(Request $request) {
        $general = new General();

        $request->validate([
            'name' => 'required|string|min:3|max:255',
        ]);
        if (!empty($request->image)) {
          $request->validate([
              'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:1024',
          ]);
        }

        $name = $request->name;
        $note = $request->note;
        $slug = $general->generate_slug($request->name);
        $user = $request->user;
        $settings = array('name' => $name, 'note' => $note);
        $settings = json_encode($settings);
        $values = array('user' => $user, 'settings' => $settings, 'slug' => $slug, 'date' => Carbon::now($this->settings->timezone));

        $values_update = array('settings' => $settings, 'slug' => $slug, 'date' => Carbon::now($this->settings->timezone));
        if (!isset($request->portfolio_id)) {
            $id = Portfolio::insertGetId($values);
            if (!empty($request->image)) {
                $imageName = md5(microtime());
                $imageName = $imageName . '.' .$request->image->extension();
                $request->image->move(public_path('img/user/portfolio'), $imageName);
                $values = array('image' => $imageName);
                Portfolio::where('id', $id)->update($values);
            }
        }else{
            $portfolio = Portfolio::find($request->portfolio_id);
            if (!empty($request->image)) {
                $imageName = md5(microtime());
                $imageName = $imageName . '.' .$request->image->extension();
                if (!empty($portfolio->image)) {
                    if(file_exists(public_path('img/user/portfolio/' . $portfolio->image))){
                        unlink(public_path('img/user/portfolio/' . $portfolio->image)); 
                    }
                }
                $request->image->move(public_path('img/user/portfolio'), $imageName);
                $portfolio->image = $imageName;
           }
          $settings = array('name' => $name, 'note' => $note);
          $portfolio->settings = $settings;
          $portfolio->slug = $slug;
          $portfolio->date = Carbon::now($this->settings->timezone);
          $portfolio->save();
        }

        return back()->with('success', 'portfolio posted');
    }
    // Support

    public function support(Request $request){
        $ticket  = $request->get('ticket');
        $status  = $request->get('status');
        $search  = $request->get('search');
        $tickets = Support::orderBy('id', 'DESC')->get();
        $sidebar = Support::leftJoin('users', 'users.id', '=', 'support.user')->select('users.username', 'users.name', 'users.id as user_id', 'users.email', 'support.*')->orderBy('id', 'DESC')->groupBy('support.id');
        $users = User::get();

        if ($status == 'closed') {
            $sidebar->where('support.status', '0');
        }elseif($status == 'active'){
            $sidebar = $sidebar->where('status', '1');
        }elseif($status == 'unread'){
            $sidebar = $sidebar->where('viewed', '0');
        }
        if (!empty($search)) {
            $sidebar->where('support.support_id','LIKE','%'.$search.'%');
        }
        if ($status == 'all') {
            $sidebar = $sidebar->get();
        }else{
            $sidebar = $sidebar->get();
        }
        if ($request->get('create') == 'true') {
            return $this->createsupport();
        }
        return $this->replysupport($ticket, ['sidebar' => $sidebar, 'users' => $users]);

        #view page with array
        return view('admin.support.support', ['tickets' => $tickets]);
    }

    public function createsupport(){
        $users = User::get();
        #view page with array
        return view('admin.support.create', ['users' => $users]);
    }

    public function marksupport(Request $request){
        if (!Support::where('support_id', $request->supportID)->exists()) {
            return back()->with('error', 'Ticket not found');
        }
        Support::where('support_id', $request->supportID)->update(array('status' => 0));
        return back()->with('success', 'Status updated');
        //Send email...
    }

    public function replysupport($supportID, $parms = []){
        $user = null;
        $ticketreplies = null;
        if($ticket = Support::where('support_id', $supportID)->first()):
            $thisticket = Support::find($ticket->id);
            $thisticket->viewed = 1;
            $thisticket->save();
            $user   = User::where('id', $ticket->user)->first();
            $countT = Support::where('user', $user->id)->count();
            $parms['countT'] = $countT;
            $ticketreplies = SupportReply::where('user', $user->id)->where('support_id', $supportID)->get();
        endif;
        #view page with array
        return view('admin.support.reply', ['user' => $user, 'ticket' => $ticket, 'ticketreplies' => $ticketreplies, 'parms' => $parms]);
    }

    public function postsupportreply(Request $request){
        if (!Support::where('support_id', $request->supportID)->exists()) {
            return back()->with('error', 'Support ticket not found');
        }
        $support = Support::where('support_id', $request->supportID)->first();
        $user = User::where('id', $support->user)->first();
        $settings = array('message' => $request->message);
        $settings = json_encode($settings);
        $values = array('user' => $user->id, 'from' => 'admin', 'support_id' => $request->supportID, 'settings' => $settings, 'date' => Carbon::now($this->settings->timezone));
        SupportReply::insert($values);
        Support::where('support_id', $request->supportID)->update(array('updated_on' => Carbon::now($this->settings->timezone)));
        $support = Support::where('support_id', $request->supportID)->first();

        // Send email
        # define message to be sent
        $message = "<p> Support id: $support->support_id </p> <br> <p> Problem: ". $support->settings->problem ." </p> <br> <p> Message: $request->message </p> <br>";
        # define email subject and message
        $email = (object) array('subject' => 'Support reply', 'message' => $message);
        # send mail
        try {
         Mail::to($user->email)->send(new GeneralMail($email));
        } catch (\Exception $e) {
            return back()->with('error', 'could not send email. smtp error.');
         }
        return back()->with('success', 'Reply added');
    }

    public function post_support(Request $request){
        $request->validate([
            'problem' => 'required|string|min:10',
        ]);
        // Define Variable
        $user = User::where('id', $request->user)->first();
        $rand = 'P'. rand(9999, 999999);
        $category = $request->category;
        $type = $request->type;
        $priority = $request->priority;
        $problem = $request->problem;
        $message = $request->message;
        $email = $user->email;

        $array_settings = array('problem' => $problem, "email" =>  $email, "message" => $message);
        $array_settings = json_encode($array_settings);

        $values = array("user" => $user->id, 'support_id' => $rand, "from" => "admin", "type" => $type, "category" => $category, "priority" => $priority, "settings" => $array_settings, "date" => Carbon::now());

        Support::insert($values);

        // Send mail to admins

        # define message to be sent
        $message = "<p> Problem: ". $request->problem ." </p> <br> <p> ".$request->message." </p>";
        # define email subject and message
        $email = (object) array('subject' => 'Support ticket created', 'message' => $message);
        # send mail
         try {
          Mail::to($user->email)->send(new GeneralMail($email));
        } catch (\Exception $e) {
            return back()->with('error', 'could not send email. smtp error.');
         }

        return back()->with('success', 'Message sent successfully');
    }

    private function writeHttps($sch){
$https = '
    RewriteCond %{HTTPS} !=on
    RewriteRule .* https://%{HTTP_HOST}%{REQUEST_URI} [R,L]';
$scheme = '<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews
    </IfModule>

    RewriteEngine On

    RewriteCond %{REQUEST_FILENAME} -d [OR]
    RewriteCond %{REQUEST_FILENAME} -f
    RewriteRule ^ ^$1 [N]

    RewriteCond %{REQUEST_URI} (\.\w+$) [NC]
    RewriteRule ^(.*)$ public/$1 

    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ server.php
    '.($sch == 'https' ? $https : '').'
</IfModule>';

        $update = fopen(base_path('.htaccess'), 'w');
        fwrite($update, $scheme);
    }

    // Settings
    public function post_settings(Request $request, Domains $domains) {
        $domains = $domains->get();
        $appurl = url('/');
        foreach ($domains as $item) {
            if (parse_url(url('/'))['host'] == $item->host) {
                $appurl = env('APP_URL');
            }
        }
        $scheme = 'http';
        if ($request->scheme == 'https') {
            $scheme = 'https';
            if (env('APP_SCHEME') !== 'https') {
             $this->writeHttps($request->scheme);
            }
        }
        if ($request->scheme == 'http') {
          $scheme = 'http';
          if (env('APP_SCHEME') !== 'http') {
           $this->writeHttps($request->scheme);
          }
        }

        $update_env = [
            'CAPTCHA_STATUS'        => $request->captcha_status,
            'CAPTCHA_TYPE'          => $request->captcha_type,
            'RECAPTCHA_SITE_KEY'    => $request->recaptcha_site_key,
            'RECAPTCHA_SECRET_KEY'  => $request->recaptcha_secret_key,
            'MIDTRANS_STATUS'         => $request->midtrans_status,
            'MIDTRANS_CLIENT'         => $request->midtrans_client,
            'MIDTRANS_SECRET'         => $request->midtrans_secret,
            'STRIPE_STATUS'         => $request->stripe_status,
            'STRIPE_CLIENT'         => $request->stripe_client,
            'STRIPE_SECRET'         => $request->stripe_secret,
            'GOOGLE_STATUS'         => $request->google_status,
            'GOOGLE_CLIENT_ID'      => $request->google_client_id,
            'GOOGLE_SECRET'         => $request->google_secret_key,
            'FACEBOOK_STATUS'       => $request->facebook_status,
            'FACEBOOK_CLIENT_ID'    => $request->facebook_client_id,
            'FACEBOOK_SECRET'       => $request->facebook_secret_key,
            'APP_LOCALE'            => $request->locale,
            'APP_URL'               => $appurl,
            'APP_SCHEME'            => $scheme,
        ];
        $update_env["MAIL_HOST"]            = $request->smtp_host;
        $update_env["MAIL_FROM_ADDRESS"]    = $request->smtp_from_address;
        $update_env["MAIL_FROM_NAME"]       = (!empty($request->smtp_from_name) ? $request->smtp_from_name : '${APP_NAME}');
        $update_env["MAIL_ENCRYPTION"]      = $request->smtp_encryption;
        $update_env["MAIL_PORT"]            = $request->smtp_port;
        $update_env["MAIL_USERNAME"]        = $request->smtp_username;
        $update_env["MAIL_PASSWORD"]        = $request->smtp_password;
        $update_env["APP_NAME"]             = $request->title;
        $update_env["PAYPAL_STATUS"]        = $request->paypal_status;
        $update_env["PAYPAL_CLIENT_ID"]     = $request->paypal_client_id;
        $update_env["PAYPAL_SECRET"]        = $request->paypal_secret;
        $update_env["PAYPAL_MODE"]          = $request->paypal_mode;
        $update_env["PAYSTACK_STATUS"]      = $request->paystack_status;
        $update_env["PAYSTACK_PUBLIC_KEY"]  = $request->paystack_public_key;
        $update_env["PAYSTACK_SECRET_KEY"]  = $request->paystack_secret_key;
        $update_env["RAZOR_STATUS"]         = $request->rozor_status;
        $update_env["RAZOR_KEYID"]          = $request->razor_key_id;
        $update_env["RAZOR_SECRET"]         = $request->rozor_secret_key;
        $update_env["BANK_DETAILS"]         = $request->bank_details;
        $update_env["BANK_STATUS"]          = $request->bank_status;
        $this->changeEnv($update_env);
        $request->registration              = (bool) $request->registration;
        $_POST['registration']              = (bool) $_POST['registration'];
        $request->email_activation          = (bool) $request->email_activation;
        $request->email_notify_support      = (bool) $request->email_notify_support;
        $request->email_notify_bank_transfer  = (bool) $request->email_notify_bank_transfer;
        $request->business_enabeled                  = (bool) $request->business_enabeled;
        $request->email_notify_supportreply             = (bool) $request->email_notify_supportreply;
        $request->email_notify_user                     = (bool) $request->email_notify_user;
        $request->email_notify_payment                  = (bool) $request->email_notify_payment;
        $request->under_construction_enabled            = (bool) $request->under_construction_enabled;
        $request->contact = (bool) $request->contact;
        $request->payment_system = (bool) $request->payment_system;

        $request->custom_code_enabled            = (bool) $request->custom_code_enabled;

        $settings_keys = [
            # Main
            'email',
            'email_activation',
            'registration',
            'payment_system',
            'timezone',
            'currency',
            'location',
            'terms',
            'privacy',
            'contact',
            'support_status_change',
            'custom_home',
            'user' => [
                'domains_restrict',
            ],
            # Ads
            'ads' => [
                'enabled',
                'profile_header',
                'profile_footer',
                'site_header',
                'site_footer',
            ],

            # Social
            'social' => [
                'facebook',
                'instagram',
                'youtube',
                'whatsapp',
                'twitter',
            ],
            # Maintenance
            'maintenance' => [
                'enabled',
                'custom_text',
            ],
            # Custom code
            'custom_code' => [
                'enabled',
                'js',
                'css',
            ],
            /* Business */
            'business' => [
                'enabled',
                'name',
                'address',
                'city',
                'county',
                'zip',
                'country',
                'email',
                'phone',
                'tax_type',
                'tax_id',
                'custom_key_one',
                'custom_value_one',
                'custom_key_two',
                'custom_value_two'
            ],
            # Topbar
            'topbar' => [
                'enabled',
                'location',
                'social',
            ],

            # Email notify
            'email_notify' => [
                'emails',
                'user',
                'payment',
                'supportreply',
                'support',
                'bank_transfer',
            ],
        ];

        foreach ($settings_keys as $key => $value) {

            if(is_array($value)) {

                $values_array = [];

                foreach ($value as $sub_key) {

                    /* Check if the field needs cleaning */
                    $values_array[$sub_key] = $request->{$key . '_' . $sub_key};
                }

                $value = json_encode($values_array);

            } else {
                $key = $value;
                $value = $_POST[$key];
            }
           if (Settings::where('key', $key)->count() > 0) {
             $value = array("value" => $value);
             Settings::where('key', $key)->update($value);
           }else{
             $value = array("key" => $key, "value" => $value);
             Settings::insert($value);
           }

        }
        if (!empty($request->logo)) {
            $slug = md5(microtime());
            $request->validate([
                'logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            $imageName = 'logo.' .$request->logo->extension();

            if (!empty($this->settings->logo)) {
                if(file_exists(public_path('img/logo/' . $this->settings->logo))){
                    unlink(public_path('img/logo/' . $this->settings->logo)); 
                }
            }
            $request->logo->move(public_path('img/logo'), $imageName);
            $values = array('value' => $imageName);
            Settings::where('key', 'logo')->count() > 0 ? Settings::where('key', 'logo')->update($values) : Settings::insert(['key' => 'logo', 'value' => $imageName]);
        }
        if (!empty($request->favicon)) {
            $slug = md5(microtime());
            $request->validate([
                'favicon' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            $imageName = 'favicon.' .$request->favicon->extension();

            if (!empty($this->settings->favicon)) {
                if(file_exists(public_path('img/favicon/' . $this->settings->favicon))){
                    unlink(public_path('img/favicon/' . $this->settings->favicon)); 
                }
            }
            $request->favicon->move(public_path('img/favicon'), $imageName);
            $values = array('value' => $imageName);
            Settings::where('key', 'favicon')->count() > 0 ? Settings::where('key', 'favicon')->update($values) : Settings::insert(['key' => 'favicon', 'value' => $imageName]);
        }
        // Email

        return back()->with("success", "saved successfully");
    }


    public function adminUpdates(){
        return view('admin.updates');
    }

    public function updateMigrate(Request $request){
        if ($request->get('steps') == 1) {
            DB::table('migrations')->where('migration', '2020_06_26_091850_updates')->delete();
        }elseif ($request->get('steps') == 2) {
            try {
                Artisan::call('migrate', ["--force"=> true]);
                $update_env["APP_MIGRATION"]  = 'no';
                $this->changeEnv($update_env);
                return "SUCCESS";
            } catch(\Exception $e) {
                return "FAILED _ " . $e->getMessage();
            }
        }
    }


    public function runUpdateOnline(Request $request){
        return false;
    }
    public function checkForUpdate(){
        $check = $this->getClovPrevPurchase(env('LICENSE_KEY'));
        if ($check->status == 'success') {
            $check = $check->response;
            if ($check->version == env('APP_VERSION')) {
                $update_env["UPDATE_AVAILABLE"] = 'no';
                $this->changeEnv($update_env);
            }else{
                $update_env["UPDATE_AVAILABLE"] = 'yes';
                $update_env["UPDATE_VERSION"]   = $check->version;
                $this->changeEnv($update_env);
                return back()->with('success', 'Update available V' . $check->version);
            }
        }else{
            return back()->with('error', $check->response);
        }

        return back()->with('info', 'No update available');
    }
    public function update_license_code(Request $request){
        $request->validate([
            'license_code' => 'required|min:15',
        ]);
        $license = $this->getClovPrevPurchase($request->license_code);
        if ($license->status == 'success') {
            $update_env["LICENSE_KEY"]  = $request->license_code;
            $update_env["LICENSE_NAME"] = $license->response->buyer;
            $update_env["LICENSE_TYPE"] = $license->response->license;
            $response                   = json_encode($license->response);
            Storage::put('.code', Crypt::encryptString($response));
            $this->changeEnv($update_env);
        }else{
            $update_env["LICENSE_KEY"]  = '';
            $update_env["LICENSE_NAME"] = '';
            $update_env["LICENSE_TYPE"] = '';
            $this->changeEnv($update_env);
            if (file_exists(storage_path('app/.code'))) {
                unlink(storage_path('app/.code'));
            }
            return back()->with('error', 'Invalid license code');
        }
        return back()->with('success', 'License key updated');
    }
    public function updateManual(Request $request){
        $file = $request->file('zipFile');
        if($file) {
            $request->zipFile->move(base_path(), "publish.zip");
            $zipper = new \Madnest\Madzipper\Madzipper;
            $zipper->make('publish.zip')->extractTo(base_path());
            $zipper->close();
            unlink("publish.zip");
            try {
                Artisan::call('migrate', ["--force"=> true]);
                $update_env["APP_MIGRATION"]  = 'no';
                $this->changeEnv($update_env);
            } catch(\Exception $e) {
                return "FAILED _ " . $e->getMessage();
            }
            return back()->with('success', 'App Updated');
        }

        return back()->with('error', 'Try again');
    }

    private function getClovPrevPurchase($license = ''){
        $client = new Client();
        $result = $client->request('GET', 'https://prev-update.clovdigital.com/?code=' . $license, ['verify' => false]);
        $check = json_decode($result->getBody()->getContents());
        return $check;
    }


    // Helpers



    protected function changeEnv($data = array()){
        if(count($data) > 0){
            $env = file_get_contents(base_path() . '/.env');
            $env = explode("\n", $env);
            foreach((array)$data as $key => $value) {
                if($key == "_token") {
                    continue;
                }
                $notfound = true;
                foreach($env as $env_key => $env_value) {
                    $entry = explode("=", $env_value, 2);
                    if($entry[0] == $key){
                        $env[$env_key] = $key . "=\"" . $value."\"";
                        $notfound = false;
                    } else {
                        $env[$env_key] = $env_value;
                    }
                }
                if($notfound) {
                    $env[$env_key + 1] = "\n".$key . "=\"" . $value."\"";
                }
            }
            $env = implode("\n", $env);
            file_put_contents(base_path() . '/.env', $env);
            return true;
        } else {
            return false;
        }
    }

    public function randomShortname($min = 3, $max = 9) {
      $length = rand($min, $max);
      $chars = array_merge(range("a", "z"), range("A", "Z"), range("0", "9"));
      $max = count($chars) - 1;
      $url = '';
      for($i = 0; $i < $length; $i++) {
        $char = random_int(0, $max);
        $url .= $chars[$char];
      }
      return $url;
    }
}
