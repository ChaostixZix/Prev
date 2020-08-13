<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
use Stripe\Stripe;
use App\Mail\GeneralMail;
use Razorpay\Api\Api;
use App\PendingPayments;
use App\CurrencySymbol;
use Illuminate\Support\Facades\Config;
use Redirect, URL, Session, General, Paystack;
use App\Packages;
use App\Payments;
use App\User;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Guzzle\Http\Exception\ClientErrorResponseException;


class PaymentController extends Controller
{
    private $api_context;
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    private $settings;

    public function __construct()
    {
        $this->middleware('auth');
        $general = new General();
        $this->settings = $general->settings();

    }

    public function payment_select($plan, Request $request)
    {
        $general = new General();
        $gateways = $general->get_json_data('gateway');
        if (!$this->settings->payment_system) {
            return redirect()->route('home.manage');
        }
        $user = Auth::user();
        if ($plan == 'free') {

            return view('manage.plan.back-to-free');
        }
        if (!Packages::where('slug', $plan)->exists()) {
            abort(404);
        }
        $plan = Packages::where('slug', $plan)->first();
        $int_check = ["month" => $plan->price->month, "quarter" => $plan->price->quarter, "annual" => $plan->price->annual];
        $not_int = [];

        foreach ($int_check as $key => $item) {
            if (!is_numeric($item)) {
                $not_int[$key] = $item;
            }
        }
        if (!empty($not_int)) {
            return back()->with('error', 'Invalid package price on ' . strtoupper(implode(' , ', array_keys($not_int))) . '. Prices have to be in numbers. Kindly fix for package to work');
        }
        $yearly_price_savings = ceil(($plan->price->month * 12) - $plan->price->annual);

        $quarterly_price_savings = ceil(($plan->price->month * 6) - $plan->price->quarter);

        $savings = ['yearly' => $yearly_price_savings, 'quarterly' => $quarterly_price_savings];

        if (!empty($request->get('payment_plan')) && !in_array($request->get('payment_plan'), ['annual', 'month', 'quarter'])) {
            abort(404);
        }

        if (empty($request->get('payment_plan')) && empty($request->get('gateway'))) {
            # code...
        }
        $payment = new \StdClass();

        $payment->price = "0";

        $payment->duration = $request->get('payment_plan');

        $payment->gateway = $request->get('gateway');

        // Duration
        switch ($payment->duration) {
            case 'month':
                $payment->price = $plan->price->month;
                break;

            case 'quarter':
                $payment->price = $plan->price->quarter;
                break;

            case 'annual':
                $payment->price = $plan->price->annual;
                break;
        }


        /* if ($payment->price == 0) {
            return back()->with('error', 'Price not set.');
        } */

        // Process paypal

        switch ($payment->gateway) {
            case 'paypal':
                // Do paypal stuff
                $paypal_conf = \Config::get('paypal');
                $this->api_context = new ApiContext(new OAuthTokenCredential(
                        $paypal_conf['client_id'],
                        $paypal_conf['secret'])
                );
                $request->session()->put('price', $payment->price);
                $this->api_context->setConfig($paypal_conf['settings']);
                // We create the payer and set payment method, could be any of "credit_card", "bank", "paypal", "pay_upon_invoice", "carrier", "alternate_payment".
                $payer = new Payer();
                $payer->setPaymentMethod('paypal');
                // Create and setup items being paid for.. Could multiple items like: 'item1, item2 etc'.
                $item = new Item();
                $item->setName($plan->name)->setCurrency('USD')->setQuantity(1)->setPrice($payment->price);
                // Create item list and set array of items for the item list.
                $itemList = new ItemList();
                $itemList->setItems(array($item));
                // Create and setup the total amount.
                $amount = new Amount();
                $amount->setCurrency('USD')->setTotal($payment->price);
                // Create a transaction and amount and description.
                $transaction = new Transaction();
                $transaction->setAmount($amount)->setItemList($itemList)
                    ->setDescription('Payment for' . $plan->name);
                //You can set custom data with '->setCustom($data)' or put it in a session.// Create a redirect urls, cancel url brings us back to current page, return url takes us to confirm payment.
                $redirect_urls = new RedirectUrls();
                $redirect_urls->setReturnUrl(url('manage/payment-callback/' . $plan->slug . '/paypal?duration=' . $request->get('payment_plan') . ''))
                    ->setCancelUrl(url()->current());
                // We set up the payment with the payer, urls and transactions.
                $payment = new Payment();
                $payment->setIntent('Sale')->setPayer($payer)->setRedirectUrls($redirect_urls)
                    ->setTransactions(array($transaction));
                // Put the payment creation in try and catch in case of exceptions.
                try {
                    $payment->create($this->api_context);
                } catch (\Exception $ex) {
                    return back()->with('error', 'Paypal response: ' . json_decode($ex->getData())->error_description);
                }// We get 'approval_url' a paypal url to go to for payments.
                foreach ($payment->getLinks() as $link) {
                    if ($link->getRel() == 'approval_url') {
                        $redirect_url = $link->getHref();
                        break;
                    }
                }

                if (isset($redirect_url)) {
                    return redirect($redirect_url);
                }// If we don't have redirect url, we have unknown error.
                return redirect()->back()->withError('Unknown error occurred');

                break;

            case 'stripe':
                $this->stripe_create($user, $plan, $payment);
                break;

            case 'paystack':
                $request->session()->put('price', $payment->price);
                $payment->price = ($payment->price * 100);
                // url to go to after payment
                $callback_url = url('manage/payment-callback/' . $plan->slug . '/paystack?duration=' . $request->get('payment_plan') . '');

                $client = new Client(['http_errors' => false]);
                $headers = [
                    'Content-Type' => 'application/json',
                    'cache-control' => 'no-cache',
                    'authorization' => 'Bearer ' . env('PAYSTACK_SECRET_KEY'),
                ];
                $body = json_encode(['amount' => $payment->price, 'email' => $user->email, 'callback_url' => $callback_url]);
                $result = $client->request('POST', 'https://api.paystack.co/transaction/initialize', ['headers' => $headers, 'body' => $body, 'verify' => false]);
                $statuscode = $result->getStatusCode();
                if (404 === $statuscode) {
                    return back()->with('error', 'Paystack response: 404');
                } elseif (401 === $statuscode) {
                    return back()->with('error', 'Paystack response: unauthorised');
                }
                return redirect(json_decode($result->getBody()->getContents())->data->authorization_url);
                break;
        }

        \Illuminate\Support\Facades\Session::put('plan', $plan->slug);
        return view('manage.plan.purchase', ['plan' => $plan, 'savings' => $savings, 'gateway' => $gateways]);
    }


    private function stripe_create($user, $plan, $payment)
    {
        Stripe::setApiKey(config('app.stripe_secret'));
        $price = in_array($this->settings->currency, ['MGA', 'BIF', 'CLP', 'PYG', 'DJF', 'RWF', 'GNF', 'UGX', 'JPY', 'VND', 'VUV', 'XAF', 'KMF', 'KRW', 'XOF', 'XPF']) ? number_format($payment->price, 2, '.', '') : number_format($payment->price, 2, '.', '') * 100;
        $stripe = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'name' => $plan->name,
                'description' => "Purchasing $plan->name Package on " . ucfirst(config('app.name')),
                'amount' => $price,
                'currency' => $this->settings->currency,
                'quantity' => 1,
            ]],
            'metadata' => [
                'user_id' => $user->id,
                'package_id' => $plan->id,
                'package' => $plan->name,
            ],
            'success_url' => url('manage/payment-callback/' . $plan->slug . '/stripe?duration=' . $payment->duration . ''),
            'cancel_url' => route('pricing'),
        ]);
        Session::put('stripe', $stripe);
        return (object)['status' => 'success', 'response' => $stripe];
    }

    public function paypal_create()
    {

        # return (object) ['status' => 'success', 'response' => ];
    }

    public function postBankTransfer(Request $request, $package, $duration, PendingPayments $payment)
    {
        $user = Auth::user();
        $plan = Packages::where('id', $package)->first();
        $payment = new PendingPayments;
        $payment->user = Auth()->user()->id;
        $payment->email = $request->email;
        $payment->name = $request->name;
        $payment->bankName = $request->bank_name;

        $payment->ref = 'PR_' . $this->randomShortname();
        $payment->package = $package;
        $payment->duration = $duration;

        if (!empty($request->proof)) {
            $request->validate([
                'proof' => 'image|mimes:jpeg,png,jpg,gif,svg|max:1024',
            ]);
            $imageName = md5(microtime());
            $imageName = $imageName . '.' . $request->proof->extension();
            $request->proof->move(public_path('img/user/bankProof'), $imageName);
            $payment->proof = $imageName;
        }
        $payment->save();

        if (!empty($this->settings->email_notify->bank_transfer) && $this->settings->email_notify->bank_transfer) {
            $emails = $this->settings->email_notify->emails;
            $emails = explode(',', $emails);
            $emails = str_replace(' ', '', $emails);
            $email = (object)array('subject' => 'New Pending Payment', 'message' => '<p> <b>' . ucfirst($user->name) . '</b> Just submitted the manual payment form for <b>' . ucfirst($plan->name) . '</b>. <br> Head to your dashboard to view payment.</p><br>');
            try {
                Mail::to($emails)->send(new GeneralMail($email));
            } catch (\Exception $e) {
                return (object)['status' => 'error', 'response' => 'send mail error'];
            }
        }

        return back()->with('success', 'Pending Transaction');
    }


    public function callback(Request $request, $plan, $gateway)
    {
        $user = Auth::user();
        $plan = Packages::where('slug', $plan)->first();
        $plan_id = $plan->id;
        $price = $request->session()->get('price');
        $duration = $request->get('duration');

        if ($gateway == "paypal") {
            $paypal_conf = \Config::get('paypal');
            $this->api_context = new ApiContext(new OAuthTokenCredential(
                    $paypal_conf['client_id'],
                    $paypal_conf['secret'])
            );
            $this->api_context->setConfig($paypal_conf['settings']);
            if (empty($request->query('paymentId')) || empty($request->query('PayerID')) || empty($request->query('token'))) {
                return redirect('/checkout')->withError('Payment was not successful.');
            }
            $payment = Payment::get($request->query('paymentId'), $this->api_context);
            $execution = new PaymentExecution();
            $execution->setPayerId($request->query('PayerID'));
            $result = $payment->execute($execution, $this->api_context);
            if ($result->getState() != 'approved') {
                return redirect()->route('pricing')->with('error', 'Payment was not successful.');
            }
            if ($result->getState() == 'approved') {
                $post = $this->addPlanToUser($user->id, $plan_id, $duration, 'paypal');
                if ($post->status == 'success') {
                    $email = $this->sendPayment($user, $plan);
                    if (!empty($email->status) && $email->status == 'success') {
                        return redirect()->route('pricing')->with('success', 'Package activated');
                    } else {
                        return redirect()->route('pricing')->with('success', 'payment successful with little errors');
                    }
                }
            }
        }


        if ($gateway == 'razor') {
            $api = new Api(config('app.razor_key'), config('app.razor_secret'));
            if (!empty($request->get('razorpay_payment_id'))) {
                $success = true;
                try {
                    $payment = $api->payment->fetch($request->get('razorpay_payment_id'));
                } catch (\Exception $e) {
                    $success == false;
                    \Session::put('error', $e->getMessage());
                    return redirect()->back()->withError($e->getMessage());
                }
                if ($success == true) {
                    # Check if the amount is same as duration amount
                    # if (substr($payment['amount'], 0, -2) !== $plan->price->{$duration}) {
                    #    return redirect()->route('pricing')->with('error', 'Cannot proceed');
                    # }
                    $post = $this->addPlanToUser($user->id, $plan_id, $duration, 'razorPay');
                    if ($post->status == 'success') {
                        $email = $this->sendPayment($user, $plan);
                        if (!empty($email->status) && $email->status == 'success') {
                            return redirect()->route('pricing')->with('success', 'Package activated');
                        } else {
                            return redirect()->route('pricing')->with('success', 'payment successful with little errors');
                        }
                    }
                } else {
                    return redirect()->route('pricing')->with('error', 'Payment was not successful.');
                }
            }
        }


        if ($gateway == 'midtrans') {
            $post = $this->addPlanToUser($user->id, Packages::where('slug', Session::get('plan'))->first()->id, $duration, 'midtrans');
            Session::pull('plan');
            if ($post->status == 'success') {
                $email = $this->sendPayment($user, $plan);
                if (!empty($email->status) && $email->status == 'success') {
                    return redirect()->route('pricing')->with('success', 'Package activated');
                } else {
                    return redirect()->route('pricing')->with('success', 'payment successful with little errors');
                }
            }
        }
        if ($gateway == 'stripe') {
            $post = $this->addPlanToUser($user->id, $plan_id, $duration, 'stripe');
            if ($post->status == 'success') {
                $email = $this->sendPayment($user, $plan);
                if (!empty($email->status) && $email->status == 'success') {
                    return redirect()->route('pricing')->with('success', 'Package activated');
                } else {
                    return redirect()->route('pricing')->with('success', 'payment successful with little errors');
                }
            }
        }

        if ($gateway == 'paystack') {
            $client = new Client();
            $headers = [
                'Content-Type' => 'application/json',
                'cache-control' => 'no-cache',
                'authorization' => 'Bearer ' . env('PAYSTACK_SECRET_KEY'),
            ];
            $reference = !empty($request->get('reference')) ? $request->get('reference') : '';
            if (empty($reference)) {
                return redirect()->route('plans')->with('error', 'No reference supplied.');
            }
            $result = $client->request('GET', 'https://api.paystack.co/transaction/verify/' . rawurlencode($reference), ['headers' => $headers]);
            $tranx = json_decode($result->getBody()->getContents());

            if (!$tranx->status) {
                return redirect()->route()->with('error', 'API returned error: ' . $tranx->message);
            }
            $post = $this->addPlanToUser($user->id, $plan_id, $duration, 'paystack');
            if ('success' == $tranx->data->status) {
                if ($post->status == 'success') {
                    $email = $this->sendPayment($user, $plan);
                    if (!empty($email->status) && $email->status == 'success') {
                        return redirect()->route('pricing')->with('success', 'Package activated');
                    } else {
                        return redirect()->route('pricing')->with('success', 'payment successful with little errors');
                    }
                }
            }
        }
        return false;
    }

    private function sendPayment($user, $plan)
    {

        if (!empty($this->settings->email_notify->payment) && $this->settings->email_notify->payment) {
            $emails = $this->settings->email_notify->emails;
            $emails = explode(',', $emails);
            $emails = str_replace(' ', '', $emails);
            $email = (object)array('subject' => 'New Payment', 'message' => '<p> <b>' . ucfirst($user->name) . '</b> Just paid for <b>' . ucfirst($plan->name) . '</b>. <br> Head to your dashboard to view earnings</p><br>');
            try {
                Mail::to($emails)->send(new GeneralMail($email));
            } catch (\Exception $e) {
                return (object)['status' => 'error', 'response' => 'send mail error'];
            }
            return (object)['status' => 'success', 'response' => 'Email Sent'];
        }
    }

    public function payment_invoice($plan, Request $request)
    {
        $gateway = $request->get('gateway');
        $duration = $request->get('payment_plan');
        if (!in_array($duration, ['month', 'annual', 'quarter'])) {
            abort(404);
        }
        if (in_array($gateway, ['razor', 'paypal', 'paystack', 'bank', 'stripe', 'midtrans'])) {
            $gateway = ucfirst($gateway);
        } else {
            $gateway = false;
        }
        if (!$plan = Packages::where('slug', $plan)->first()) {
            if ($this->settings->business->enabled) {
                return abort(404);
            }
            return abort(404);
        }
        return view('manage.invoice', ['plan' => $plan, 'gateway' => $gateway, 'duration' => $duration]);
    }


    private function addPlanToUser($user_id, $plan_id, $duration, $gateway)
    {
        $newdue = Carbon::now($this->settings->timezone);
        $user = User::find($user_id);
        $package = Packages::where('id', $plan_id)->first();
        $payment = new \StdClass();
        $payment->date = "";
        if ($duration == "month") {
            $newdue->addMonths(1);
            $payment->date = $newdue;
        } elseif ($duration == "quarter") {
            $newdue->addMonths(6);
            $payment->date = $newdue;
        } elseif ($duration == "annual") {
            $newdue->addMonths(12);
            $payment->date = $newdue;
        } else {
            $newdue->addMonths(1);
            $payment->date = $newdue;
        }
        $user->package = $plan_id;
        $user->package_due = $payment->date;
        $user->save();
        $new = new Payments();
        $new->user = $user_id;
        $new->name = $user->name;
        $new->email = $user->email;
        $new->duration = $duration;
        $new->package_name = $package->name;
        $new->price = $package->price->{$duration} ?? Null;
        $new->currency = $this->settings->currency;
        $new->ref = 'PR_' . $this->randomShortname();
        $new->package = $plan_id;
        $new->gateway = $gateway;
        $new->date = Carbon::now($this->settings->timezone);
        $new->save();
        return (object)['status' => 'success'];
    }

    public function randomShortname($min = 3, $max = 9)
    {
        $length = rand($min, $max);
        $chars = array_merge(range("a", "z"), range("A", "Z"), range("0", "9"));
        $max = count($chars) - 1;
        $url = '';
        for ($i = 0; $i < $length; $i++) {
            $char = random_int(0, $max);
            $url .= $chars[$char];
        }
        return $url;
    }
}