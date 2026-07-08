<?php

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

//Route::get('/', function () {
//    return view('welcome');
//});
Route::get('/', function () {
    return redirect()->route("login");
});

Auth::routes();

//Route::get('/home', 'HomeController@index')->name('home');
/**
 * admin route
 */
Route::group(['prefix'=>'admin','middleware'=>['admin','auth'],'namespace'=>'Admin','as'=>'admin.'],function() {

    Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
    Route::get('/users', 'UserController@index')->name('user');
    Route::get('/user-view', 'UserController@view');
    Route::get('/control/{user_id}', 'UserController@control');
    Route::get('/user-edit/{user_id}', 'UserController@userEdit');
    Route::post('/user-edit/{user_id}', 'UserController@userUpdate');
    Route::get('/login/{user_id}', 'UserController@login');
    Route::get('/user-delete/{user_id}', 'UserController@userDelete');
    Route::post('/app-update/{app_id}', 'UserController@appUpdate');

    Route::resource('category', 'CategoryController')->except(['show', 'edit', 'update', 'create']);
    Route::resource('university', 'UniversityController')->except(['show', 'edit', 'update', 'create']);
    Route::resource('installapp', 'InstallAppController')->except(['show', 'create', 'store']);

});

/**
 * user route
 */
Route::group(['prefix'=>'user','middleware'=>['user','auth'],'namespace'=>'User','as'=>'user.'],function(){

    Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
    Route::get('/instruction', 'InstructionController@index')->name('instruction');

    Route::resource("installapp",'InstallAppController')->names([
        'index' => 'install',
    ])->except([
        'create','store','destroy','show','edit','update','destroy'
    ]);
    Route::post('/install', 'InstallAppController@store');
    Route::get('/faq-generator', 'FaqController@index')->name('faq');
    Route::post('/faq_generator', 'FaqController@faqGenerator');
    Route::get('/sendsms', 'SendSmsController@index')->name('sendsms');
    Route::post('/sendsms', 'SendSmsController@sendSms');
    Route::get('/content', 'ContentController@index')->name('content');
    Route::post('/content', 'ContentController@store')->name('content');
    Route::get('/app-content/{app_id}', 'ContentController@appContent');
});

/**
 * bd apps api
 */
Route::post('api/ussd/send', 'API\UssdSubscriptionController@index');
Route::post('api/sms/send', 'API\SMSSubscriptionController@index');
Route::post('api/subscription/notify', 'API\SubscriptionNotificationController@index');
Route::post('api/otp/send', 'API\OtpController@send');
Route::post('api/bdapps/proxy', 'API\ProxyController@forward');

/**
 * universal route
 */
Route::group(['middleware' => ['auth']], function () {
    Route::get('/password-change', 'PasswordController@index')->name('password_change');
    Route::post('/password-change', 'PasswordController@changePassword');
});
