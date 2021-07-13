<?php

/*
|--------------------------------------------------------------------------
| Payment Routes
|--------------------------------------------------------------------------
|
| Routes for payment process
|
*/

Route::group(['namespace' => 'Payment'], function () {
    Route::get('/', 'RedirectController@index');
    Route::post('/success', 'CallbackController@success');
    Route::post('/failure', 'CallbackController@failure');
    Route::post('/notification', 'CallbackController@notification');
});
