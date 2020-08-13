<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', 'HomeController@index')->name('home');
Route::get('cancel', function ()
{
    return view('errors.cancel');
});

//Installer Routes
Route::get('/install', 'InstallController@install')->name('install');

Route::get('/install/app', 'InstallController@appDetail')->name('InstallApp');

Route::post('/install/app', 'InstallController@appDetailSubmit')->name('InstallApp');

Route::get('install/database', 'InstallController@migration')->name('InstallDatabase');

Route::get('install/final', 'InstallController@final')->name('InstallFinal');

Route::post('logout', 'Auth\LoginController@logout')->name('logout');

Route::get('i-am-cron', 'GeneralController@cron');

// Activate user
Route::get('activate/u/{token}','GeneralController@activate_email');

# Guest Route

Route::group(['middleware' => ['guest']], function(){

	# Auth Login
	Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');

	Route::post('login', 'Auth\LoginController@login')->name('login');

	Route::get('reset-password', 'Auth\ResetPasswordController@showResetForm')->name('resetpassword');

	Route::post('reset-password', 'Auth\ResetPasswordController@validatePasswordRequest')->name('resetpassword');
	
	Route::post('reset_password_with_token', 'Auth\ResetPasswordController@resetPassword')->name('reset.password');

	Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');

	Route::post('register', 'Auth\RegisterController@register')->name('register');
	Route::get('auth/facebook', 'Auth\SocialloginCrontroller@redirectToFacebook')->name('user-auth-facebook');

	Route::get('auth/facebook/callback', 'Auth\SocialloginCrontroller@handleFacebookCallback');

	Route::get('auth/google', 'Auth\SocialloginCrontroller@redirectToGoogle')->name('user-auth-google');

    Route::get('auth/google/callback', 'Auth\SocialloginCrontroller@handleGoogleCallback');

});

# Resend token
Route::get('resend-activation', 'Auth\ResendTokenController@resendemailtoken')->name('resend-token');

Route::post('resend-activation', 'Auth\ResendTokenController@resendemailtoken_send')->name('resend-token');

# Pages and category
Route::get('pages', 'HomeController@pages')->name('all-pages');

Route::get('pages/{slug}', 'HomeController@innerpages');

Route::get('page/{page}', 'HomeController@page');
    
// Pricing
Route::get('pricing', 'HomeController@pricing')->name('pricing');

Route::post('pricing-bank/{package}/{duration}', 'PaymentController@postBankTransfer')->name('user-pricing-bank');

// Faq
Route::get('faq', 'ManageController@faq')->name('user.faq');

Route::post('contact', 'HomeController@contact')->name('contact-us');

// Auth Route
Route::group(['middleware' => ['auth']], function(){


	Route::group(['prefix' => 'manage'], function () {

		Route::get('transactions-history', 'ManageController@transactions_history')->name('user-transactions');

		Route::get('invoice/{plan}', 'PaymentController@payment_invoice')->name('user-invoice');

		Route::get('plan', 'ManageController@plans')->name('plans');

		Route::get('plan/{plan}', 'PaymentController@payment_select');

		Route::get('payment-callback/{plan}/{gateway}', 'PaymentController@callback')->name('paymentcallback');

		Route::group(['middleware' => 'PackageStatus'], function () {
		Route::get('/', 'ManageController@manage')->name('home.manage');

		Route::post('back-to-free', 'ManageController@back_to_free')->name('user-back-to-free');
			Route::post('menu', 'ManageController@sortable')->name('sortable.menu');

			// Support
			Route::get('support', 'ManageController@support')->name('support');

			Route::get('support/create', 'ManageController@createsupport')->name('support.create');

			Route::post('support/create', 'ManageController@post_support')->name('post.support');

			Route::post('reply-support', 'ManageController@postsupportreply')->name('reply.support');

			// Manage Profile

			Route::get('profile', 'ManageController@profile')->name('profile');

			Route::get('profile/delete-banner', 'ManageController@delete_banner')->name('delete-banner');

			Route::post('profile', 'ManageController@profile_post')->name('post.profile');

			// Login Activity

			Route::get('login-activity', 'ManageController@login_activity')->name('activities');

			Route::post('login-activity', 'ManageController@deleteActivities')->name('activities');

			// Links

			Route::get('links', 'ManageController@links')->name('links');

			Route::post('links', 'ManageController@post_link')->name('post.link');

			Route::get('links/{id}', 'ManageController@link_stats');

			Route::post('links-sort', 'ManageController@links_sortable')->name('sortable.links');

			Route::get('links/{id}/delete', 'ManageController@link_delete');

			// Portfolio
			Route::get('portfolio', 'ManageController@portfolio')->name('portfolio');

			Route::get('portfolio/{id}', 'ManageController@portfolio_stats');

			Route::post('portfolio', 'ManageController@post_portfolio')->name('portfolio');

			Route::post('portfolio-sort', 'ManageController@portfolio_sortable')->name('sortable.portfolio');

			Route::get('portfolio/{id}/delete', 'ManageController@portfolio_delete');


			# Skills 
			Route::get('skills', 'ManageController@getSkills')->name('user-skills');

			Route::post('skills', 'ManageController@postSkills')->name('user-skills');

			Route::post('skills-sort', 'ManageController@skillsSortable')->name('user-skills-sortable');

			Route::get('skills/{id}/delete', 'ManageController@skillsDelete')->name('skills-delete');

			// Saats
			Route::get('stats', 'ManageController@user_stats')->name('stats');
		});
		//Admin

		Route::group(['prefix' => 'admin'], function () {
			Route::get('/', 'AdminController@home_admin')->name('home.admin');

			Route::get('domains', 'AdminController@domains')->name('admin-domains');

			Route::get('domains/post', 'AdminController@domains_post_get')->name('admin-domains-post');

			Route::post('domains/post', 'AdminController@domains_post')->name('admin-domains-post');

			Route::get('admin-trans', 'AdminController@admin_trans')->name('admin-translation');

			Route::post('admin-trans/{type}', 'AdminController@admin_post_trans')->name('admin-translation-post');

			Route::get('update-migrate', 'AdminController@updateMigrate')->name('admin-update-migrate');

			Route::get('update-cloud', 'AdminController@runUpdateOnline')->name('admin-update-cloud');

			Route::post('update-check', 'AdminController@checkForUpdate')->name('admin-update-check');

			Route::post('update-cloud-license-code', 'AdminController@update_license_code')->name('admin-update-license-code');

			Route::post('update-manual', 'AdminController@updateManual')->name('admin-update-manual');

			// Users
			Route::get('users', 'AdminController@users')->name('admin-users');

			Route::post('user/send', 'AdminController@send_usermail')->name('send.user.mail');

			Route::post('user/delete', 'AdminController@delete_user')->name('delete.user');

			Route::post('users', 'AdminController@user_post')->name('post-user');

			Route::get('users/{id}', 'AdminController@user_view');

			Route::get('users/{id}/delete-banner', 'AdminController@delete_userbanner');


			// Stats
			Route::get('stats', 'AdminController@stats')->name('admin-stats');

			# Route::get('stats/browser', 'AdminController@stats_browser')->name('admin-stats-browser');

			# Route::get('stats/os', 'AdminController@stats_os')->name('admin-stats-os');

			# Route::get('stats/traffic', 'AdminController@stats_traffic')->name('admin-stats-traffic');

			# Route::get('stats/country', 'AdminController@stats_country')->name('admin-stats-country');

			# Route::get('stats/user', 'AdminController@stats_user')->name('admin-stats-user');



			// Pages
			Route::get('pages', 'AdminController@pages')->name('pages');

			Route::get('pages/add', 'AdminController@add_pages')->name('add.pages');

			Route::post('pages/add', 'AdminController@post_page')->name('post.page');

			Route::get('pages/{id}', 'AdminController@edit_pages');

			Route::post('pages/edit', 'AdminController@edit_post_page')->name('edit.post.page');

			Route::post('pages/delete', 'AdminController@delete_page')->name('delete-admin-page');


			// Pages category
			Route::get('pages-category', 'AdminController@category')->name('category');

			Route::get('pages-category/add', 'AdminController@add_category')->name('add.category');

			Route::post('pages-category/add', 'AdminController@post_category')->name('post.category');

			Route::get('pages-category/{id}', 'AdminController@edit_category');

			Route::post('pages-category/edit', 'AdminController@edit_post_category')->name('edit.post.category');

			Route::post('pages-category/delete', 'AdminController@delete_category')->name('delete-admin-category');


			// Faq
			Route::get('faq', 'AdminController@faq')->name('faq');

			Route::post('faq', 'AdminController@post_faq')->name('post.faq');

			Route::post('edit-faq', 'AdminController@edit_faq')->name('edit.faq');

			Route::post('faq/delete', 'AdminController@delete_faq')->name('delete-admin-faq');


			// Payments
			Route::get('payments', 'AdminController@payments')->name('payments');
			Route::get('pending-payments', 'AdminController@pending_payments')->name('admin-pendiing-payments');

			Route::get('pending-payments/{type}/{id}', 'AdminController@activate_pending_payment')->name('admin-activate-pendiing-payments');


			// Package
			Route::get('packages', 'AdminController@packages')->name('admin-packages');

			Route::get('packages/create', 'AdminController@create_packages')->name('admin-add-package');

			Route::post('packages/create', 'AdminController@post_packages')->name('post.package');

			Route::post('packages/edit/{id}', 'AdminController@edit_post_package')->name('edit.post.package');

			Route::get('packages/edit/{id}', 'AdminController@edit_package');

			Route::post('packages/{id}/{type}', 'AdminController@PostPackagePrices')->name('admin-packagePrices');

			Route::get('packages/{id}', 'AdminController@DeletePackagePrice')->name('admin-deletepackageprice');

			Route::post('packages/delete', 'AdminController@delete_package')->name('admin-delete-package');

			// Support
			Route::get('support', 'AdminController@support')->name('admin.support');

			Route::get('support/create', 'AdminController@createsupport')->name('admin.support.create');

			Route::post('support/create', 'AdminController@post_support')->name('admin.support.create');

			Route::post('reply-support', 'AdminController@postsupportreply')->name('admin.reply.support');

			Route::post('mark-support', 'AdminController@marksupport')->name('mark-as-closed');

			Route::get('support/{supportID}', 'AdminController@replysupport');

			// Links
			Route::get('links', 'AdminController@links')->name('admin-links');

			Route::post('links', 'AdminController@post_link')->name('admin-link');

			Route::get('links/{id}/delete', 'AdminController@link_delete');

			Route::get('portfolio', 'AdminController@portfolio')->name('admin-portfolio');

			Route::post('portfolio', 'AdminController@post_portfolio')->name('admin-portfolio');

			Route::get('portfolio/{id}/delete', 'AdminController@portfolio_delete');


			// Settings
			Route::get('settings', 'AdminController@settings')->name('admin-settings');

			Route::post('settings', 'AdminController@post_settings')->name('post.settings');


			Route::get('updates', 'AdminController@adminUpdates')->name('admin-updates');

			Route::post('updates', 'AdminController@adminPostUpdates')->name('admin-updates');
		});
	});
});

// Redirect links
Route::get('/t/f/{url}', 'RedirectController@frame')->name('frame-route');
Route::get('t/{slug}', 'RedirectController@linkerRedirect')->name('linker');

// Profile

 Route::group(['prefix' => '{profile}'], function () {
	Route::get('/', 'ProfileController@index');
	Route::get('{second}', 'ProfileController@allPages');
	Route::get('{second}/{third}', 'ProfileController@third');
#	Route::get('links', 'ProfileController@links');
#	Route::get('about', 'ProfileController@about');
#	Route::get('home', 'ProfileController@home');
#	Route::get('portfolio', 'ProfileController@portfolio');
#	Route::get('portfolio/{slug}', 'ProfileController@singleportfolio');
 });