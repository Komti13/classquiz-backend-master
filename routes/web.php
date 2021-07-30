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
Route::group(['namespace' => 'Admin', 'middleware' => 'admin'], function () {
    Route::get('/', 'DashboardController@dashboard')->name('dashboard');
    Route::resource('admins', 'AdminController')->except('show');

    Route::group(['prefix' => 'users/{role}/'], function () {
        Route::get('/', 'UserController@index')->name('users.index');
        Route::get('/create', 'UserController@create')->name('users.create');
        Route::post('/', 'UserController@store')->name('users.store');
        Route::get('/{user}/edit', 'UserController@edit')->name('users.edit');
        Route::patch('/{user}', 'UserController@update')->name('users.update');
        Route::delete('/{user}', 'UserController@destroy')->name('users.destroy');
        Route::get('/{user}', 'UserController@show')->name('users.show');
    });

    Route::resource('subjects', 'SubjectController')->except('show');
    Route::resource('chapters', 'ChapterController')->except('show');
    Route::resource('chapter-types', 'ChapterTypeController')->except('show');

    Route::get('schools/import-export', 'SchoolController@importExport')->name('schools.importExport');
    Route::get('schools/export-excel/{type}', 'SchoolController@exportExcel')->name('schools.export');
    Route::post('schools/import-excel', 'SchoolController@importExcel')->name('schools.import');

    Route::resource('schools', 'SchoolController')->except('show');
    Route::resource('levels', 'LevelController')->except('show');
    Route::resource('atlases', 'AtlasController')->except('show');
    Route::resource('icons', 'IconController')->except('show');
    Route::get('questions/get-levels', 'QuestionController@getLevels')->name('get-levels');
    Route::get('questions/get-subjects/{level}', 'QuestionController@getSubjects')->name('get-subjects');
    Route::get('questions/get-chapters/{level}/{subject}', 'QuestionController@getChapters')->name('get-chapters');
    Route::get('questions/get-subjects/{level}', 'QuestionController@getSubjects')->name('get-subjects');
    Route::get('questions/get-question-groups/{chapter}', 'QuestionController@getQuestionGroups')->name('get-question-groups');
    Route::get('questions/get-questions/{group}', 'QuestionController@getQuestions')->name('get-questions');
    Route::resource('questions', 'QuestionController')->only(['index', 'show', 'destroy']);

    Route::resource('countries', 'CountryController')->except('show');
    Route::resource('governorates', 'GovernorateController')->except('show');
    Route::resource('delegations', 'DelegationController')->except('show');
    Route::resource('templates', 'TemplateController')->except('show');
    Route::resource('avatars', 'AvatarController');

    Route::resource('packs', 'PackController')->except('show');
    Route::resource('pack-promotions', 'PackPromotionController')->except('show');
    Route::post('payments/payment-status/{id}', 'PaymentController@status')->name('payment.status');
    Route::resource('payments', 'PaymentController')->only('index', 'show');
    Route::resource('payment-methods', 'PaymentMethodController')->except('show');
    Route::resource('subscriptions', 'SubscriptionController')->except('show');
    Route::post('tokens/batch-store', 'TokenController@batchStore')->name('tokens.batch-store');
    Route::resource('tokens', 'TokenController')->except('show');
    Route::resource('templates', 'TemplateController')->except('show');

    Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index')->name('logs');
    Route::get('generateToken', 'TokenController@generateToken')->name('tokengen');


    // Route::resource('logistics', 'LogisticController');
    // Route::get('/logistics', 'LogisticController@index')->name('logistics');
   
Route::group(['prefix' => 'logistics/'], function () {
        Route::get('/', 'LogisticController@index')->name('logistics');
        Route::get('/create', 'LogisticController@create')->name('logistics.create');


        Route::get('/form', function(){
            return view('admin.logistics.form_template');
        })->name('form');
        // Route::post('/first','LogisticController@store1' )->name('first');
        // Route::post('/second','LogisticController@store2' )->name('second');
        // Route::post('/third','LogisticController@store3' )->name('third');

        Route::post('/{source}', 'LogisticController@store')->name('logistics.store');
    //     Route::get('/{source}/edit', 'LogisticController@edit')->name('users.edit');
    //     Route::patch('/{source}', 'LogisticController@update')->name('sources.update');
    //     Route::delete('/{source}', 'LogisticController@destroy')->name('sources.destroy');
        // Route::get('/{source}', 'LogisticController@show')->name('sources.show');
    });


});

Route::group(['namespace' => 'Admin\Auth'], function () {
    Route::get('/login', 'LoginController@showLoginForm')->name('login');
    Route::post('/login', 'LoginController@login');
    Route::post('/logout', 'LoginController@logout')->name('logout');

    Route::get('password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('password.request');
    Route::post('password/email', 'ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    Route::get('password/reset/{token}', 'ResetPasswordController@showResetForm')->name('password.reset');
    Route::post('password/reset', 'ResetPasswordController@reset');

});