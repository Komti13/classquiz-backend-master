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

use App\Http\Controllers\Admin\LogisticController;

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
        Route::post('/{source}', 'LogisticController@store')->name('logistics.store');
        Route::get('/', 'LogisticController@index')->name('logistics');
        Route::get('/create', 'LogisticController@create')->name('logistics.create');
        // Route::get('/editform/{id}', 'LogisticController@editForm');
        Route::get('/edit/{id}', 'LogisticController@edit')->name('logistics.edit');
        Route::post('/update/{id}/{source}', 'LogisticController@update')->name('logistics.update');
        Route::get('/delete/{id}', 'LogisticController@destroy')->name('logistics.destroy');
        Route::get('/pdf/{type}/{id}', 'LogisticController@createPDF')->name('pdf');
        Route::get('/session/{param}','LogisticController@insertSession')->name('session');
        
        Route::get('/loadata', 'LogisticController@dataSheets')->name('logistics.sheets');
        Route::get('/write', function(){
            return view('admin.logistics.create.loadFile');
        })->name('write');

        // Route::post('/pdf', 'LogisticController@createPDF');



        // Route::get('/ticket', function(){
        //     return view('admin.logistics.test');
        // });
        // Route::get('/construction', function(){
        //     return view('admin.logistics.construction');
        // });
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