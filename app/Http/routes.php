<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::group(['prefix' => 'wechat', 'namespace' => 'Wechat'], function () {
    Route::controller('test', 'TestController');
    Route::controller('notlogin', 'NotloginController');
    Route::controller('payment', 'PaymentController');

    Route::group(['middleware' => ['wechat.login']], function () {
        // 用户登陆才能访问
        Route::controller('login', 'LoginController');
    });
});
