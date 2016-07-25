<?php

Route::group(['prefix' => 'api', 'namespace' => 'Api'], function () {
    Route::group(['prefix' => '1', 'namespace' => 'V1'], function () {
        Route::group(['prefix' => 'wechat', 'namespace' => 'Wechat'], function () {
            Route::any('server', 'ServerController@server');
            Route::any('payment-callback','ServerController@paymentCallback');

            Route::get('token', 'ServerController@getAccessToken');
            Route::get('ip', 'ServerController@getAllIp');
            Route::get('sentence', 'ServerController@sentence');
        });
    });
});

