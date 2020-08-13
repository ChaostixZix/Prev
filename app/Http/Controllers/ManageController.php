<?php

namespace App\Http\Controllers;
use Validator, Redirect, Response, General, Location, Str, QrCode;
use App\Http\Controllers\Controller;
use Jenssegers\Agent\Agent;
use Ausi\SlugGenerator\SlugGenerator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Mail\ActivateEmail;
use App\Mail\supportMail;
use App\Mail\supportReplyMail;
use Carbon\Carbon;
use App\Settings;
use App\Domains;
use App\Links;
use App\Track;
use App\Linker;
use App\TrackLinks;
use App\User;
use App\Faq;
use App\Skills;
use App\Packages;
use App\Payments;
use App\Portfolio;
use App\Support;
use App\SupportReply;

class ManageController extends Controller
{
    private $settings;
    private $package;
    
    public function __construct(){
        # check if script is already installed
        $this->middleware(function ($request, $next) {
            # check if install file doesnt exists and install
            if(!file_exists(storage_path('installed'))) {
                return redirect()->route('install');
            }
            # check if install file exists and proceeed
            return $next($request);
        });
        # check if site is on mainenance mode and lock
        $this->middleware('MaintenanceMode');
        # if is logged in 
        $this->middleware('auth');
        # check if user is banned
        $this->middleware('banned');
        # check if script is insalled
        if(file_exists(storage_path('installed'))) {
          # get all from GENERAL CONTROLLER
            $general = new General();
            $this->settings = $general->settings();

            if ($this->settings->email_activation == 1) {
              $this->middleware('activeEmail');
            }
        }
    }


    public function sortable(Request $request){
        $user = User::find(Auth()->user()->id);
        $user->menus = $request->data;
        $user->save();
    }




    // Login activity

    public function login_activity(){
        $activities = DB::table('users_logs')->where('user', Auth()->user()->id)->orderBy('id', 'DESC')->paginate(10);
        return view('manage.activities', ['activities' => $activities]);
    }

    public function deleteActivities(){
        $activities = DB::table('users_logs')->where('user', Auth()->user()->id)->delete();
        return back()->with('success', 'Deleted successfully');
    }


    // link stats

    public function link_stats($id){
        $user = Auth::user();
        $general = new General();
        if (!$link = Links::where('user', $user->id)->where('id', $id)->first()) {
            abort(404);
        }
        $visit_chart_date = TrackLinks::select(\DB::raw("`views`, DATE_FORMAT(`created_at`, '%Y-%m') AS `formatted_date`"))->where('user', $user->id)->where('slug', $link->url_slug)->orderBy('created_at', 'ASC')->distinct()->get();
        $visit_chart_date_fetch = [];
        $logsFD = [];
        foreach ($visit_chart_date as $key => $value) {
           if(!array_key_exists($value->formatted_date, $logsFD)) {
               $logsFD[$value->formatted_date] = [
                   'impression'        => 0,
                   'unique'            => 0,
               ];
           }
           /* Distribute the data from the database key */
           $logsFD[$value->formatted_date]['unique']++;
           $logsFD[$value->formatted_date]['impression'] += $value->views;
        }
        $visit_chart = TrackLinks::select(\DB::raw("`country`,`os`,`browser`,`views`, DATE_FORMAT(`created_at`, '%Y-%m-%d') AS `formatted_date`"))->where('user', $user->id)->where('slug', $link->url_slug)->get();

        $total_visits = TrackLinks::select(\DB::raw("COUNT(*) as count"))->where('user', $user->id)->where('slug', $link->url_slug)->groupBy(\DB::raw("Month(created_at)"))->pluck('count');
        $logs_chart = [];
        $logs_data = ['country' => [],'os' => [],'browser'  => []];
        foreach ($visit_chart as $key) {
            if(!array_key_exists($key->country, $logs_data['country'])) {
                $logs_data['country'][$key->country ?? 'false'] = 1;
            } else {
                $logs_data['country'][$key->country]++;
            }

            if(!array_key_exists($key->os, $logs_data['os'])) {
                $logs_data['os'][$key->os ?? 'N/A'] = 1;
            } else {
                $logs_data['os'][$key->os]++;
            }

            if(!array_key_exists($key->browser, $logs_data['browser'])) {
                $logs_data['browser'][$key->browser ?? 'N/A'] = 1;
            } else {
                $logs_data['browser'][$key->browser]++;
            }
        }
        $logsFD = $general->get_chart_data($logsFD);
        $logs_chart = $general->get_chart_data($logs_chart);
        arsort($logs_data['browser']);
        arsort($logs_data['os']);
        arsort($logs_data['country']);
        $options = (object) ['logsFD' => $logsFD];

        return view('manage.link.stats', ['logs_data' => $logs_data, 'link' => $link, 'options' => $options]);
    }

    public function transactions_history(Payments $payments, Request $request){
        $user = Auth::user();
        $general = new General();
        $invoice_id = $request->get('invoice_id');

        if (!empty($invoice_id)) {
            if (!$this->settings->business->enabled) {
                abort(404);
            }
            if (!$invoice = $payments->where('id', $invoice_id)->first()) {
                abort(404);
            }

            return view('manage.transaction-invoice', ['invoice' => $invoice]);
        }

        $allpayments = $payments->where('user', $user->id)->orderBy('id', 'DESC')->paginate(10);
        # Payments Chart
        $paymentschart = [];
        $results = $payments->select(\DB::raw("COUNT(*) as count, DATE_FORMAT(`date`, '%Y-%m-%d') AS `formatted_date`, TRUNCATE(SUM(`price`), 2) AS `amount`"))->where('user', $user->id)->groupBy('formatted_date')->get();

        foreach ($results as $value) {
            $value->formatted_date = Carbon::parse($value->formatted_date)->toFormattedDateString();
            $paymentschart[$value->formatted_date] = [
                'count' => $value->count,
                'amount' => $value->amount
            ];
        }

        $paymentschart = $general->get_chart_data($paymentschart);
        
        return view('manage.transactions-history', ['payments' => $allpayments, 'paymentschart' => $paymentschart]);
    }











    // user stats

    public function user_stats(Request $request, TrackLinks $track_links, Linker $linker){
        $user = Auth::user();
        $general = new General();
        $type = $request->get('type');
        $url = $request->get('url');
        $link = $request->get('link');
        $url_slug = $request->get('url_slug');
        $fromDate = Carbon::now()->subMonth()->startOfMonth()->toDateString();
        $thisMonth = Carbon::now()->startOfMonth()->toDateString();
        if (!$general->package($user)->settings->statistics) {
            return redirect()->route('home.manage')->with('info', 'You cant access that page');
        }
        if ($type == 'links') {
            if (!empty($link)) {
                if (!$track_links->where('slug', $link)->exists()) {
                    abort(404);
                }
                $linksLogs = $track_links->select(\DB::raw("*, DATE_FORMAT(`created_at`, '%Y-%m-%d') AS `formatted_date`"))
                ->where('user', $user->id)
                ->where('slug', $link)
                ->get();

                $linksLogsFD = $track_links->select(\DB::raw("*, DATE_FORMAT(`created_at`, '%Y-%m-%d') AS `formatted_date`"))
                ->where('user', $user->id)
                ->where('created_at', '>=', $thisMonth)
                ->where('slug', $link)
                ->get();
                $data = ['os' => [], 'browser' => [], 'country' => [], 'slug' => []];
                $log = [];
                $logsFD = [];
                foreach ($linksLogsFD as $value) {
                    if(!array_key_exists($value->formatted_date, $logsFD)) {
                        $logsFD[$value->formatted_date] = [
                            'impression'        => 0,
                            'unique'            => 0,
                        ];
                    }
                    /* Distribute the data from the database key */
                    $logsFD[$value->formatted_date]['unique']++;
                    $logsFD[$value->formatted_date]['impression'] += $value->views;
                }
                foreach ($linksLogs as $value) {
                    if(!array_key_exists($value->slug, $log)) {
                        $log[$value->slug] = [
                            'impression'        => 0,
                            'unique'            => 0,
                        ];
                    }
                    /* Distribute the data from the database key */
                    $log[$value->slug]['unique']++;
                    $log[$value->slug]['impression'] += $value->views;
                    if(!array_key_exists($value->os, $data['os'])) {
                        $data['os'][$value->os ?? 'N/A'] = 1;
                    } else {
                        $data['os'][$value->os]++;
                    }

                    if(!array_key_exists($value->country, $data['country'])) {
                        $data['country'][$value->country ?? 'false'] = 1;
                    } else {
                        $data['country'][$value->country]++;
                    }

                    if(!array_key_exists($value->browser, $data['browser'])) {
                        $data['browser'][$value->browser ?? 'N/A'] = 1;
                    } else {
                        $data['browser'][$value->browser]++;
                    }

                    if(!array_key_exists($value->slug, $data['slug'])) {
                        $data['slug'][$value->slug ?? 'N/A'] = 1;
                    } else {
                        $data['slug'][$value->slug]++;
                    }
                }
                unset($data['country']['false']);
                unset($data['country']['']);
                $logsFD = $general->get_chart_data($logsFD);
                $options = (object) ['data' => $data, 'logs' => $log, 'logsFD' => $logsFD];

                return view('manage.stats.singlelinks-stats', ['options' => $options]);
            }
            $linksLogs = $track_links->select(\DB::raw("*, DATE_FORMAT(`created_at`, '%Y-%m-%d') AS `formatted_date`"))->where('user', $user->id)->get();

            $getAll = $track_links
            ->leftJoin('linker', 'linker.slug', '=', 'track_links.slug')
            ->select('track_links.*', 'linker.url as link_url');
            $getAll2 = $track_links
            ->leftJoin('linker', 'linker.slug', '=', 'track_links.slug')
            ->select('track_links.*', 'linker.url as link_url')->where('track_links.user', $user->id);
            if (!empty($url)) {
              $getAll->where('linker.url','LIKE','%'.$url.'%');
            }
            if (!empty($url_slug)) {
              $getAll->where('track_links.slug','LIKE','%'.$url_slug.'%');
            }
            $getAll = $getAll->where('track_links.user', $user->id)
            ->groupBy('track_links.slug')
            ->orderBy('track_links.views', 'DESC');
            $data = ['os' => [], 'browser' => [], 'country' => [], 'slug' => []];
            $log = [];
            foreach ($linksLogs as $value) {
                if(!array_key_exists($value->slug, $log)) {
                    $log[$value->slug] = [
                        'impression'        => 0,
                        'unique'            => 0,
                    ];
                }
                /* Distribute the data from the database key */
                $log[$value->slug]['unique']++;
                $log[$value->slug]['impression'] += $value->views;
                if(!array_key_exists($value->os, $data['os'])) {
                    $data['os'][$value->os ?? 'N/A'] = 1;
                } else {
                    $data['os'][$value->os]++;
                }
                if(!array_key_exists($value->country, $data['country'])) {
                    $data['country'][$value->country ?? 'false'] = 1;
                } else {
                    $data['country'][$value->country]++;
                }
                if(!array_key_exists($value->browser, $data['browser'])) {
                    $data['browser'][$value->browser ?? 'N/A'] = 1;
                } else {
                    $data['browser'][$value->browser]++;
                }

                if(!array_key_exists($value->slug, $data['slug'])) {
                    $data['slug'][$value->slug ?? 'N/A'] = 1;
                } else {
                    $data['slug'][$value->slug]++;
                }
            }
            unset($data['country']['false']);
            unset($data['country']['']);
            $logs_chart = $general->get_chart_data($log);
            $options = (object) ['getAll' => $getAll, 'getAll2' => $getAll2, 'data' => $data, 'logs' => $log, 'logs_chart' => $logs_chart];
            return view('manage.links-stats', ['options' => $options]);
        }


        $visit_chart_date = Track::select(\DB::raw("DATE_FORMAT(`date`, '%Y-%m') AS `formatted_date`"))->where('user', Auth()->user()->id)->where('type', 'profile')->groupBy(\DB::raw("formatted_date"))->distinct()->get();
        $visit_chart_date_fetch = [];
        foreach ($visit_chart_date as $key => $value) {
            $visit_chart_date_fetch[] = date("F", strtotime($value->formatted_date));
        }

        $visit_chart = Track::select(\DB::raw("`country`,`os`,`browser`,`count`, DATE_FORMAT(`date`, '%Y-%m-%d') AS `formatted_date`"))->where('user', Auth()->user()->id)->where('type', 'profile')->get();

        $logs_chart = Track::select(\DB::raw("`count`, DATE_FORMAT(`date`, '%Y-%m-%d') AS `formatted_date`"))
        ->where('user', $user->id)
        ->where('type', 'profile')
        ->where('date', '>=', $thisMonth)
        ->get();

        $dataTrack = Track::where('user', Auth()->user()->id)->get()
                ->groupBy(function($val) {
                    return Carbon::parse($val->date)->format('m');
                });
        $total_visits = [];
        foreach ($dataTrack as $value) {
            $total_visits[] = count($value);
        }

        $total_visits_count = Track::select(\DB::raw("COUNT(*) as count"))->where('user', Auth()->user()->id)->where('type', 'profile')->where('date', '>=', $fromDate)->first();

        $logs_data = ['country' => [],'os' => [],'browser'  => []];
        $log = [];
        foreach ($logs_chart as $key) {
            if(!array_key_exists($key->formatted_date, $log)) {
                $log[$key->formatted_date] = [
                    'impression'        => 0,
                    'unique'            => 0,
                ];
            }
            /* Distribute the data from the database key */
            $log[$key->formatted_date]['unique']++;
            $log[$key->formatted_date]['impression'] += $key->count;
        }
        foreach ($visit_chart as $key) {
            if(!array_key_exists($key->country, $logs_data['country'])) {
                $logs_data['country'][$key->country ?? 'false'] = 1;
            } else {
                $logs_data['country'][$key->country]++;
            }

            if(!array_key_exists($key->os, $logs_data['os'])) {
                $logs_data['os'][$key->os ?? 'N/A'] = 1;
            } else {
                $logs_data['os'][$key->os]++;
            }

            if(!array_key_exists($key->browser, $logs_data['browser'])) {
                $logs_data['browser'][$key->browser ?? 'N/A'] = 1;
            } else {
                $logs_data['browser'][$key->browser]++;
            }
        }
        arsort($logs_data['browser']);
        arsort($logs_data['os']);
        arsort($logs_data['country']);
        unset($logs_data['country']['false']);
        unset($logs_data['country']['']);
        $logs_chart = $general->get_chart_data($log);

        $countryPercent = [];
        $count = 0;
        foreach ($logs_data['country'] as $key => $value) {
            $count = ($count + $value);
        }
        foreach ($logs_data['country'] as $key => $value) {
            $countryPercent[$key] = [$value, round($value / ($count / 100),2)];
        }
        $portfolios = Portfolio::where('user', $user->id)->get();
        $links = Links::where('user', $user->id)->get();

        $total_visits_chart = ['total_visits' => $total_visits, 'visit_chart_date' => $visit_chart_date_fetch, 'total_visits_count' => $total_visits_count];
        $options = (object) ['countryPercent' => $countryPercent, 'logs_chart' => $logs_chart];
        return view('manage.stats', ['total_visits' => $total_visits_chart, 'links' => $links, 'portfolios' => $portfolios, 'logs_data' => $logs_data, 'options' => $options]);
    }




    public function faq(){
        $faq = Faq::where('status', 1)->get();
        return view('manage.faq', ['faqs' => $faq]);    
    }



    // Support

    public function support(Request $request){
        $user = Auth::user();
        $general = new General();
        $ticket  = $request->get('ticket');
        $status  = $request->get('status');
        $tickets = Support::orderBy('id', 'DESC')->get();
        $sidebar = Support::leftJoin('users', 'users.id', '=', 'support.user')->select('users.username', 'users.name', 'users.id as user_id', 'users.email', 'support.*')->where('user', $user->id)->orderBy('id', 'DESC')->groupBy('support.id');
        $users = User::get();

        if ($status == 'closed') {
            $sidebar->where('support.status', '0');
        }elseif($status == 'active'){
            $sidebar = $sidebar->where('status', '1');
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
        return view('manage.support.support', ['tickets' => $tickets]);
    }

    public function replysupport($supportID, $parms = []){
        $general = new General();
        $user = Auth::user();
        $ticketreplies = null;
        if($ticket = Support::where('support_id', $supportID)->first()):
            $ticketreplies = SupportReply::where('user', $user->id)->where('support_id', $supportID)->get();
            if ($general->package($user)->settings->support == 0) {
                return back()->with('error', 'Upgrade your plan!');
            }
        endif;
        return view('manage.support.reply', ['tuser' => $user, 'ticket' => $ticket, 'ticketreplies' => $ticketreplies, 'parms' => $parms]);
    }

    public function postsupportreply(Request $request){
        $user = Auth::user();
        if (!Support::where('support_id', $request->supportID)->where('user', $user->id)->exists()) {
            return back()->with('error', 'Support ticket not found');
        }
        $settings = array('message' => $request->message);
        $settings = json_encode($settings);
        $values = array('user' => $user->id, 'from' => 'user', 'support_id' => $request->supportID, 'settings' => $settings, 'date' => Carbon::now($this->settings->timezone));
        SupportReply::insert($values);
        Support::where('support_id', $request->supportID)->update(array('updated_on' => Carbon::now($this->settings->timezone)));
        $support = Support::where('support_id', $request->supportID)->first();

        // Send email
        if (!empty($this->settings->email_notify->supportreply) && $this->settings->email_notify->supportreply) {
            $reply = (object) array("supportID" => $support->support_id, 'messsage' => $request->message, "email" => $support->settings->email, 'username' => $user->name, 'problem' => $support->settings->problem, 'type' => $support->type, 'priority' => $support->priority);

             $emails = $this->settings->email_notify->emails;
             $emails = explode(',', $emails);
             $emails = str_replace(' ', '', $emails);
            try {
              Mail::to($emails)->send(new supportReplyMail($reply));
            } catch (\Exception $e) {
                return back()->with('info', 'success with errors');
             }
        }
        return back()->with('success', 'Reply added');
    }
    public function createsupport(){
        $user = Auth::user();
        $general = new General();
        if ($general->package($user)->settings->support == 0) {
            return back()->with('error', 'Upgrade your plan!');
        }
        return view('manage.support.create');
    }

    public function post_support(Request $request){
        $general = new General();
        $user = Auth::user();
        if ($general->package($user)->settings->support == 0) {
            return back()->with('error', 'Upgrade your plan!');
        }
        $request->validate([
            'problem' => 'required|string|min:10',
        ]);
        // Define Variable
        $rand = 'P'. rand(9999, 999999);
        $category = $request->category;
        $type = $request->type;
        $priority = $request->priority;
        $problem = $request->problem;
        $message = $request->message;
        $email = (Auth::check() ? $user->email : $request->email);
        $supports = Support::where('user', $user->id)->get();
        if($general->package($user)->settings->support_limit != -1 && count($supports) >= $general->package($user)->settings->support_limit) {
                return back()->with('error', "You've reached your plan limit.");
        }
        $array_settings = array('problem' => $problem, "email" =>  $email, "from" => "user", "message" => $message);
        $array_settings = json_encode($array_settings);

        $values = array("user" => $user->id, 'support_id' => $rand, "from" => "user", "type" => $type, "category" => $category, "priority" => $priority, "settings" => $array_settings, "date" => Carbon::now());

        Support::insert($values);

        // Send mail to admins

        if (!empty($this->settings->email_notify->support) && $this->settings->email_notify->support) {
             $emails = $this->settings->email_notify->emails;
             $emails = explode(',', $emails);
             $emails = str_replace(' ', '', $emails);
            try {
                Mail::to($emails)->send(new supportMail($user));
            } catch (\Exception $e) {
                return back()->with('info', 'success with errors');
             }
        }

        return back()->with('success', 'Message sent successfully');
    }


    // Manage
    public function manage(General $general) {
        $user = Auth::user();
        $fromDate = Carbon::now()->subMonth()->startOfMonth()->toDateString();
        $thisMonth = Carbon::now()->startOfMonth()->toDateString();
        $thisYear = Carbon::now()->startOfYear()->toDateString();
        $total_visits = Track::select(\DB::raw("COUNT(*) as count"))->where('user', Auth()->user()->id)->where('type', 'profile')->where('date', '>=', $fromDate)->first();
        $support_count = Support::where('user', $user->id)->get();
        $lastpayment = Payments::where('user', $user->id)
            ->orderBy('id', 'DESC')
            ->limit(1)
            ->get();
        $portfolios = Portfolio::where('user', $user->id)->get();
        $links = Links::where('user', $user->id)->get();
        $visit_chart = Track::select(\DB::raw("`country`,`count`, YEAR(`date`) AS `year`"))->where('user', Auth()->user()->id)->get();

        $month_visits_count = Track::select(\DB::raw("DATE_FORMAT(`date`, '%Y-%m-%d') AS `formatted_date`"))->where('user', $user->id)->where('date', '>=', $thisMonth)->where('type', 'profile')->groupBy(\DB::raw("formatted_date"))->distinct()->get();
        $month_visits_m = NULL;
        foreach ($month_visits_count as $key => $value) {
            $month_visits_m = date("M", strtotime($value->formatted_date));
        }

        $month_visits_day = [];
        foreach ($month_visits_count as $key => $value) {
            $month_visits_day[] = date("d", strtotime($value->formatted_date)) . ' ' . $month_visits_m;
        }
        $dataTrack = Track::where('user', $user->id)->where('type', 'profile')->where('date', '>=', $thisMonth)->get()
                ->groupBy(function($val) {
                    return Carbon::parse($val->date)->format('d');
                });
        $month_visits = [];
        foreach ($dataTrack as $value) {
            $month_visits[] = count($value);
        }

        $yearly_visits = Track::where('user', $user->id)->where('date', '>=', $thisYear)->get();

        $month_visits_all = Track::select(\DB::raw("*, MONTH(`date`) AS `month`"))->where('user', $user->id)->where('date', '>=', $thisMonth)->get();

        $logs_data = ['country' => []];
        $year = [];
        $month = [];
        foreach ($month_visits_all as $key) {
         if(!array_key_exists($key->month, $month)) {
             $month[$key->month] = [
                 'impression'        => 0,
                 'unique'            => 0,
             ];
         }
         $month[$key->month]['unique']++;
         $month[$key->month]['impression'] += $key->count;
        }
        foreach ($visit_chart as $key) {
         if(!array_key_exists($key->year, $year)) {
             $year[$key->year] = [
                 'impression'        => 0,
                 'unique'            => 0,
             ];
         }
         /* Distribute the data from the database key */
         $year[$key->year]['unique']++;
         $year[$key->year]['impression'] += $key->count;
         $key->country = strtolower($key->country);
         if(!array_key_exists($key->country, $logs_data['country'])) {
              $logs_data['country'][$key->country ?? 'false'] = '1';
          } else {
              $logs_data['country'][$key->country]++;
          }
        }
        unset($logs_data['country']['false']);
        unset($logs_data['country']['']);
        arsort($logs_data['country']);
        $year = $general->get_chart_data($year);
        $month = $general->get_chart_data($month);
        $month = preg_replace('/[^0-9]/', '', $month);
        $year = preg_replace('/[^0-9]/', '', $year);
        $count = 0;
        $countryPercent = [];
        foreach ($logs_data['country'] as $key => $value) {
            $count = ($count + $value);
        }
        foreach ($logs_data['country'] as $key => $value) {
            $countryPercent[$key] = [$value, round($value / ($count / 100),2)];
        }
        QrCode::format('png')->size(500)->generate(url("$user->username"), public_path('img/user/qrcode/'.strtolower($user->username).'.png'));

        $options = (object) ['month' => $month, 'month-visits' => $month_visits, 'month_visits_day' => $month_visits_day, 'year' => $year, 'countryPercent' => $countryPercent];

        return view('manage.manage', ['total_visits' => $total_visits, 'links' => $links, 'support_count' => $support_count, 'lastpayment' => $lastpayment, 'portfolios' => $portfolios, 'logsData' => $logs_data, 'options' => $options]);
    }







    // Profile

    public function profile(Domains $domains){
        $general    = new General();
        $user       = Auth::user();
        $themes     = $general->gradient_preset();
        $package    = $general->package($user);
        $templates  = $general->get_json_data('Profiletemplates');
        $socials    = $general->get_resource_file('socials');
        $userMenu   = $general->get_resource_file('usermenu');
        $domains    = $domains->where('status', 1)->get();
        $options = (object) ['socials' => $socials, 'menu' => $userMenu, 'domains' => $domains];
        return view('manage.profile', ['user' => $user, 'themes' => $themes, 'templates' => $templates, 'options' => $options]);
    }

    # Delete banner with get
    public function delete_banner(){
        $user = Auth::user();
        if (!empty($user->banner)) {
            if(file_exists(public_path('img/user/banner/' . $user->banner))){
                unlink(public_path('img/user/banner/' . $user->banner)); 
                return redirect()->route('profile')->with('success', 'successfully removed your banner');
            }
        }
        return redirect()->route('profile');
    }

    # Post profile
    public function profile_post(Request $request){
        $general = new General();
        $slugGenerator = new SlugGenerator;
        $user = Auth::user();
        $username = $maybe_slug = $slugGenerator->generate($request->username, ['delimiter' => '_']);
        $next = '_';
        while (User::where('username', '=', $username)->where('id', '!=', $user->id)->first()) {
            $username = "{$maybe_slug}{$next}";
            $next = $next . '_';
        }
        $usermenuStatus = [];
        foreach ($request->usermenuStatus as $key => $value) {
            $usermenuStatus[$key] = (bool) $value;
        }
        $usermenu = [];
        foreach ($request->usermenu as $key => $value) {
            $usermenu[$key] = $slugGenerator->generate($value, ['delimiter' => '_']);
        }
        $userMenu = array('menuTitle' => $usermenu, 'menuStatus' => $usermenuStatus, 'active' => $request->menuActive);
        $request->button_enabled = (bool) $request->button_enabled;
        $request->settings_showbuttombar = (bool) $request->settings_showbuttombar;
        $request->validate([
            'email' => 'required|email|string|max:191|unique:users,email,'.$user->id,
            'username' => 'required|string|max:191',
        ]);
        if (!$general->package($user)->settings->branding) {
            $request->settings_branding = $user->settings->branding ?? '';
        }
        if (!$general->package($user)->settings->custom_branding) {
            $request->settings_custom_branding = $user->settings->custom_branding ?? '';
        }
        $update = User::find($user->id);
        $update->menus = $userMenu;
        $update->username = $username;
        $update->save();
        if (!$general->package($user)->settings->domains) {
            $request->domain = 'main';
            $_POST['domain'] = 'main';
        }

        $settings_keys = ['name', 'about', 'email', 'domain', 'settings' => ['work_experience', 'works', 'template', 'menu_style', 'default_color', 'showbuttombar', 'branding', 'custom_branding', 'general_color', 'location', 'tagline']];

        $socials = $request->socials;

        if (!empty($socials)) {
            foreach ($socials as $key => $value) {
                $update = User::find($user->id);
                $update->socials = $socials;
                $update->save();
            }
        }

        foreach ($settings_keys as $key => $value) {
            if(is_array($value)) {

                $values_array = [];

                foreach ($value as $sub_key) {
                    $values_array[$sub_key] = $request->{$key . '_' . $sub_key};
                }

                $value = json_encode($values_array);

            } else {
                $key = $value;
                $value = $_POST[$key];
            }
            $value = array($key => $value);
            User::where('id', $user->id)->update($value);
        }

        if (!empty($request->avatar)) {
            $request->validate([
                'avatar' => 'image|mimes:jpeg,png,jpg,gif,svg|max:1024',
            ]);
            if (!empty($user->avatar)) {
                if(file_exists(public_path('img/user/avatar/' . $user->avatar))){
                    unlink(public_path('img/user/avatar/' . $user->avatar)); 
                }
            }
            $imageName = md5(microtime());
            $imageName = $imageName . '.' .$request->avatar->extension();
            $request->avatar->move(public_path('img/user/avatar'), $imageName);
            $values = array('avatar' => $imageName);
            User::where('id', $user->id)->update($values);
        }
        if ($general->package($user)->settings->custom_background) {
            if (!empty($request->banner)) {
                $request->validate([
                    'banner' => 'image|mimes:jpeg,png,jpg,gif,svg|max:1024',
                ]);
                if (!empty($user->banner)) {
                    if(file_exists(public_path('img/user/banner/' . $user->banner))){
                        unlink(public_path('img/user/banner/' . $user->banner)); 
                    }
                }
                $imageName = md5(microtime());
                $imageName = $imageName . '.' .$request->banner->extension();
                $request->banner->move(public_path('img/user/banner'), $imageName);
                $values = array('banner' => $imageName);
                User::where('id', $user->id)->update($values);
            }
            // background
            $array = array('background_type' => $request->background_type, 'background' => ($request->background_type == 'color') ? $request->background : (($request->background_type == "gradient") ? $request->background_gradient : ""));

            User::where('id', $user->id)->update($array);
        }
        if ($general->package($user)->settings->links_style) {
            $array = array('column' => $request->link_row_column, 'color_type' => $request->link_row_color_type, 'textcolor' => $request->link_row_textcolor, 'background' => $request->link_row_background, 'outline' => $request->link_row_outline, 'radius' => $request->link_row_radius);
            $array = json_encode($array);

            User::where('id', $user->id)->update(array('link_row' => $array));
        }
        if (!empty($request->password)) {
            $array = array('password' => Hash::make($request->password));
            User::where('id', $user->id)->update($array);
        }
        return back()->with('success', 'saved successfully');

    }









    // Plans 

    public function back_to_free(Request $request){
        if (strtolower($request->free) !== 'free') {
            return back()->with('error', 'Type FREE');
        }
        $user = User::find(Auth()->user()->id);
        $user->package = 'free';
        $user->package_due = NULL;
        $user->save();
        return back()->with('success', 'Plan activated');
    }

    public function plans() {
        if (!$this->settings->payment_system) {
            return redirect()->route('home.manage');
        }
        $plans = Packages::where('status', 1)->get();
        return view('manage.plan.plans', ['plans' => $plans]);
    }

    # Skills

    public function skillsDelete($id, Skills $skills){
        if (!$skills->where('id', $id)->exists()) {
            abort(404);
        }
        $skills = $skills->find($id);
        $skills->delete();
        return redirect()->route('user-skills')->with('success', 'That skill was successfully removed');
    }

    public function skillsSortable(Request $request, Skills $skills){
     foreach($request->data as $key) {
        $key['id']         = (int) $key['id'];
        $key['order']   = (int) $key['order'];
        $skills            = $skills->find($key['id']);
        $skills->position  = $key['order'];
        $skills->save();
     }
    }

    public function getSkills(Skills $skills){
        $user = Auth::user();
        $Allskills  =   $skills->where('user', $user->id)->orderBy('position', 'DESC')->orderBy('id', 'ASC')->get();
        return view('manage.skills', ['skills' => $Allskills]);
    }

    public function postSkills(Request $request, Skills $skills){
        $general = new General();

        $request->validate([
            'name' => 'required|string|min:3|max:255',
        ]);
        // Define request
        $name = $request->name;
        $user = Auth::user();
        if (!isset($request->skills_id)) {
            $insert = new Skills;
            $insert->user   = $user->id;
            $insert->name   = $name;
            $insert->bar    = $request->bar ?? '0';
            $insert->date   = Carbon::now($this->settings->timezone);
            $insert->save();
            return redirect()->route('user-skills')->with('success', 'Skills added');
        }else{
            $skills = $skills->find($request->skills_id);
            $skills->name   = $name;
            $skills->bar    = $request->bar;
            $skills->date   = Carbon::now($this->settings->timezone);
            $skills->save();
            return redirect()->route('user-skills')->with('success', 'Skills updated');
        }
    }

    # Portfolio
    public function portfolio(Request $request, Skills $skills){
        $user = Auth::user();
        $general = new General();
        if (!$general->package($user)->settings->portfolio) {
            return redirect()->route('home.manage')->with('info', 'You cant access that page');
        }
        $portfolios = Portfolio::leftJoin('track', 'track.dyid', '=', 'portfolio.id')
            ->select('portfolio.*', DB::raw("count(track.dyid) AS track_portfolio"))
            ->groupBy('portfolio.id')->where('portfolio.user', $user->id)->orderBy('order', 'ASC')->orderBy('id', 'DESC')->get();
        return view('manage.portfolio.portfolio', ['portfolios' => $portfolios]);
    }

    public function portfolio_delete($id){
        if (!Portfolio::where('id', $id)->exists()) {
            abort(404);
        }
        $portfolio = Portfolio::find($id);
        if (!empty($portfolio->image)) {
            if(file_exists(public_path('img/user/portfolio/' . $portfolio->image))){
                unlink(public_path('img/user/portfolio/' . $portfolio->image)); 
            }
        }
        $portfolio->delete();
        Track::where('dyid', $id)->delete();
        return redirect()->route('portfolio')->with('success', 'That portfolio was successfully removed');
    }

    public function portfolio_sortable(Request $request){
     foreach($request->data as $key) {
        $key['id'] = (int) $key['id'];
        $key['order'] = (int) $key['order'];
        $link = Portfolio::find($key['id']);
        $link->order = $key['order'];
        $link->save();
     }
    }

    public function post_portfolio(Request $request){
        $general = new General();

        $request->validate([
            'name' => 'required|string|min:3|max:255',
        ]);
        if (!empty($request->image)) {
          $request->validate([
              'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:1024',
          ]);
        }
        // Define request
        $slugGenerator = new SlugGenerator;
        $name = $request->name;
        $note = $request->note;
        $slug = $slugGenerator->generate($request->name);
        $user = Auth::user();
        $settings = array('name' => $name, 'note' => $note);
        $portfolios = Portfolio::where('user', $user->id)->get();
        if (!isset($request->portfolio_id)) {
            if($general->package($user)->settings->portfolio_limit != -1 && count($portfolios) >= $general->package($user)->settings->portfolio_limit) {
                return back()->with('error', "You've reached your plan limit.");
            }
            $insert = new Portfolio;
            $insert->user = $user->id;
            $insert->slug = $slug;
            $insert->date = Carbon::now($this->settings->timezone);
            $insert->settings = $settings;
            $insert->save();
            if (!empty($request->image)) {
                $imageName = md5(microtime());
                $imageName = $imageName . '.' .$request->image->extension();
                $request->image->move(public_path('img/user/portfolio'), $imageName);
                $values = array('image' => $imageName);
                Portfolio::where('id', $insert->id)->update($values);
            }
          return redirect()->route('portfolio')->with('success', 'Portfolio posted');
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
           $portfolio->settings = $settings;
           $portfolio->slug = $slug;
           $portfolio->date = Carbon::now($this->settings->timezone);
           $portfolio->save();

          return redirect()->route('portfolio')->with('success', 'Portfolio updated');
        }
    }



    public function portfolio_stats($id){
        $user = Auth::user();
        $general = new General();
        if (!Portfolio::where('user', $user->id)->where('id', $id)->count() > 0) {
            abort(404);
        }
        $visit_chart_date = Track::select(\DB::raw("DATE_FORMAT(`date`, '%Y-%m') AS `formatted_date`"))->where('user', Auth()->user()->id)->where('dyid', $id)->where('type', 'portfolio')->groupBy(\DB::raw("formatted_date"))->distinct()->get();
        $portfolio = Portfolio::where('user', $user->id)->where('id', $id)->first();
        $visit_chart_date_fetch = [];
        foreach ($visit_chart_date as $key => $value) {
            $visit_chart_date_fetch[] = date("F", strtotime($value->formatted_date));
        }

        $visit_chart = Track::select(\DB::raw("`country`,`os`,`browser`,`count`, DATE_FORMAT(`date`, '%Y-%m-%d') AS `formatted_date`"))->where('user', Auth()->user()->id)->where('type', 'portfolio')->where('dyid', $id)->get();

        $total_visits = Track::select(\DB::raw("COUNT(*) as count"))->where('user', Auth()->user()->id)->where('type', 'portfolio')->where('dyid', $id)->groupBy(\DB::raw("Month(date)"))->pluck('count');

        $total_visits_count = Track::select(\DB::raw("COUNT(*) as count"))->where('user', Auth()->user()->id)->where('type', 'portfolio')->where('dyid', $id)->groupBy(\DB::raw("Month(date)"))->first();

        $logs_chart = [];
        $logs_data = ['country' => [],'os' => [],'browser'  => []];
        foreach ($visit_chart as $key) {
            
            if(!array_key_exists($key->country, $logs_data['country'])) {
                $logs_data['country'][$key->country ?? 'false'] = 1;
            } else {
                $logs_data['country'][$key->country]++;
            }

            if(!array_key_exists($key->os, $logs_data['os'])) {
                $logs_data['os'][$key->os ?? 'N/A'] = 1;
            } else {
                $logs_data['os'][$key->os]++;
            }

            if(!array_key_exists($key->browser, $logs_data['browser'])) {
                $logs_data['browser'][$key->browser ?? 'N/A'] = 1;
            } else {
                $logs_data['browser'][$key->browser]++;
            }
        }
        $logs_chart = $general->get_chart_data($logs_chart);
        arsort($logs_data['browser']);
        arsort($logs_data['os']);
        arsort($logs_data['country']);

        $total_visits_chart = ['total_visits' => $total_visits, 'visit_chart_date' => $visit_chart_date_fetch, 'total_visits_count' => $total_visits_count];
        return view('manage.portfolio.stats', ['total_visits' => $total_visits_chart, 'logs_data' => $logs_data, 'portfolio' => $portfolio]);
    }







    // Links Section

    public function links_sortable(Request $request){
     foreach($request->data as $key) {
        $key['id'] = (int) $key['id'];
        $key['order'] = (int) $key['order'];
        $link = Links::find($key['id']);
        $link->order = $key['order'];
        $link->save();
     }
    }

    public function links(){
        $user = Auth::user();
        $general = new General();
        if (!$general->package($user)->settings->links) {
            return redirect()->route('home.manage')->with('info', 'You cant access that page');
        }
        $user = Auth()->user()->id;
        $links = Links::leftJoin('track_links', 'track_links.slug', '=', 'links.url_slug')
            ->select('links.*', DB::raw("track_links.views AS track_links"))
            ->groupBy('links.id')
            ->where('links.user', $user)
            ->orderBy('order', 'ASC')->orderBy('id', 'DESC')
            ->get();
        $link = [];
        $linkstr = [];
        foreach ($links as $value) {
            $link[] = $value;
        }
        $count = 0;
        $track = TrackLinks::get();
        foreach ($track as $key) {
            $linkstr[$key->slug] = ($count + $key->views);
        }
        return view('manage.link.links', ['links' => $links, 'linkstr' => $linkstr]);
    }

    public function link_delete($id){
        if (!Links::where('id', $id)->exists()) {
            abort(404);
        }
        $link = Links::find($id);
        $link->delete();
        Track::where('dyid', $id)->delete();
        return redirect()->route('links')->with('success', 'That link was successfully deleted');
    }

    public function post_link(Request $request) {
        $general = new General();


        $request->validate([
            'url' => 'required',
            'name' => 'required|string|min:2',
        ]);

        $name = $request->name;
        $url  = $general->addHttps($request->url);
        $note = $request->note;
        $slug = $this->randomShortname();
        $user = Auth::user();
        $total_links = Links::where('user', $user->id)->get();
        $values = array('user' => $user->id, 'name' => $name, 'url' => $url, 'url_slug' => $slug, 'note' => $note, 'date' => Carbon::now($this->settings->timezone));

        $values_update = array('name' => $name, 'url' => $url, 'note' => $note, 'date' => Carbon::now($this->settings->timezone)); 
        if (!isset($request->link_id)) {
            if($general->package($user)->settings->links_limit != -1 && count($total_links) >= $general->package($user)->settings->links_limit) {
                return back()->with('error', "You've reached your plan limit.");
            }
            Links::insert($values);
        }else{
          Links::where('id', $request->link_id)->update($values_update);
        }
        return redirect()->route('links')->with('success', 'Link posted');
    }







   // Helpers
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

    public static function user_profile($user_id){
      $user = User::where('id', $user_id)->first();
      $check = public_path('img/user/avatar/') . $user->avatar;
      $path = url('img/user/avatar/' . $user->avatar);
      $default = url('img/default_avatar.png');
      return (file_exists($check)) ? $path : $default;
    }
    
}
