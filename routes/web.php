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

Route::get('/create-model', function () {
    Artisan::call('make:model', [
        'name' => 'Gift'
    ]);
     $output = Artisan::output();

    return "Model created successfully!";
});

Route::name('admin.')->group(function () {

    Route::get('/login', 'AuthController@index')->name('login');
    Route::post('/login', 'AuthController@login')->name('login.verify');
    Route::get('/logout', 'AuthController@logOut')->name('logout');

    Route::middleware('adminauth')->group(function () {
        Route::get('/', 'AdminController@index')->name('home');
        Route::get('/profile-settings', 'AdminController@profileSettings')->name('profile');
        Route::post('/profile', 'AdminController@profileUpdate')->name('profile.settings');
    });
    Route::middleware('adminauth')->group(function(){
        //  games section routes
        Route::get('/agents','UsersController@agents')->name('agents');
        Route::get('/agent/add','UsersController@add_agent')->name('agent.add');
        Route::post('/agent/add','UsersController@addAgent')->name('agent.addagent');
        Route::post('/accountent/update','UsersController@updateAccountent')->name('accountent.update');
        Route::get('/accountent','UsersController@accountent')->name('accountent');
        Route::get('/users','UsersController@index')->name('users');
        Route::get('/user/add','UsersController@add')->name('users.add');
        Route::post('/user/add','UsersController@addUser')->name('users.addUser');
        Route::post('/user/update','UsersController@updateUser')->name('users.update');
        Route::get('/invites','AdminController@invites')->name('invites');
        Route::post('/inviteRecords','AdminController@invitesRecords')->name('inviteRecords');
        Route::get('/users/{id}/info','UsersController@info')->name('users.info');
        Route::get('/withdrawal','PaymentController@Withdrawal')->name('withdraw');
        Route::post('/withdrawal/statusUpdate','PaymentController@withdrawStatus')->name('withdraw.update');
        Route::get('/recharge','PaymentController@recharge')->name('recharge');
        Route::post('/recharge/statusUpdate','PaymentController@rechargeStatus')->name('recharge.update');
        Route::get('/giftcode/add','BonusPlanController@addgiftcode')->name('giftcode.add');
        Route::post('/giftcode/add','BonusPlanController@addcodegift')->name('giftcode.addcodegift');
        // settings
        Route::get('/upi','AdminController@upi')->name('upi');
        Route::post('/upi-add','AdminController@upiAdd')->name('upi.add');

        // prediction
        Route::get('/add-prediction','PredictionController@nextPrediction')->name('prediction');
        Route::get('/add-prediction/parity','PredictionController@parityPrediction')->name('prediction.parity');
        Route::get('/add-prediction/circle','PredictionController@circlePrediction')->name('prediction.circle');
        Route::get('/add-prediction/jet','PredictionController@jetPrediction')->name('prediction.jet');
        Route::post('/addAnser','PredictionController@addAnser')->name('prediction.ans');

        Route::get('/ranks','UsersController@ranks')->name('ranks.index');
        Route::post('/ranks/auto','UsersController@ranksAuto')->name('ranks.auto');
        Route::post('/ranks/add','UsersController@ranksAdd')->name('ranks.add');
        Route::post('/ranks/update','UsersController@ranksUpdate')->name('ranks.update');

        Route::get('/complains','ComplainsController@index')->name('complains.index');
        Route::post('/complains/update','ComplainsController@update')->name('complains.update');
        Route::get('/game-record','UsersController@gameRecord')->name('gameRecord.index');
        Route::resource('/bonusplans','BonusPlanController');
    });
});

