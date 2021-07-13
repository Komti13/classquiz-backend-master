<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['namespace' => 'Api\Auth', 'middleware' => 'api', 'prefix' => 'auth'], function () {
    Route::post('login', 'AuthController@login');
    // Route::post('signup/{role}', 'AuthController@signup', function (App\Role $role) {
    //    return $role->name;
    // });
    Route::post('signup/{role}', 'AuthController@signup')->where('role', 'STUDENT|PARENT|TEACHER|SCHOOL_ADMIN');
    Route::get('signup/activate/{token}', 'AuthController@signupActivate');

    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('logout', 'AuthController@logout');
        Route::get('user', 'AuthController@user');
    });

    Route::get('login/{provider}/url', 'AuthController@providerUrl')->where('provider', 'google|facebook');
    Route::get('login/{provider}/callback', 'AuthController@callback')->where('provider', 'google|facebook');
});

Route::group(['namespace' => 'Api\Auth', 'middleware' => 'api', 'prefix' => 'password'], function () {
    Route::post('create', 'PasswordResetController@create');
    Route::get('find/{token}', 'PasswordResetController@find');
    Route::post('reset', 'PasswordResetController@reset');
});
//'middleware' => ['auth:api','role:EDITOR']
Route::group(['namespace' => 'Api', 'middleware' => ['api']], function () {
    Route::get('schools/students', 'SchoolController@students');
    Route::apiResource('schools', 'SchoolController');
    Route::apiResource('levels', 'LevelController');
    Route::apiResource('countries', 'CountryController');

    Route::apiResource('avatars', 'AvatarController')->only('index');

    Route::post('student-parent', 'StudentParentController@addStudentToParent');
    Route::delete('student-parent/{parentId}/{studentId}', 'StudentParentController@removeStudentFromParent');
    Route::get('student-parent/student-parent/{studentId}', 'StudentParentController@studentParent');
    Route::get('student-parent/parent-students/{parentId}', 'StudentParentController@parentStudents');

    Route::get('users/find-by-email/{email}', 'UserController@findByEmail');
    Route::get('users/find-by-phone/{phone}', 'UserController@findByPhone');

    Route::get('users/students/{levelId}', 'UserController@students');
    Route::get('users/teachers', 'UserController@teachers');
    Route::apiResource('users', 'UserController')->except('store');
    Route::post('users/{role}', 'UserController@store')->where('role', 'STUDENT|PARENT|TEACHER|SCHOOL_ADMIN');
    Route::apiResource('words', 'WordController');
    Route::delete('questions/destroy-by-group/{groupId}', 'QuestionController@destroyByGroup');
    Route::get('questions/list-by-chapter/{chapterId}', 'QuestionController@listQuestionsByChapter');
    Route::apiResource('questions', 'QuestionController');
    Route::get('question-groups/list-by-chapter/{chapterId}', 'QuestionGroupController@listQuestionGroupsByChapter');
    Route::apiResource('question-groups', 'QuestionGroupController');
    Route::get('subjects/list-by-level/{level}', 'SubjectController@listByLevel');
    Route::apiResource('subjects', 'SubjectController');
    Route::apiResource('level-subject', 'LevelSubjectController')->only('index');
    Route::post('icons/list-by-ids', 'IconController@listByIds');
    Route::get('icons/list-by-chapter/{chapter}', 'IconController@listByChapter');
    Route::get('icons/chapter/{chapter}', 'IconController@listByChapterQuestions');
    Route::apiResource('icons', 'IconController');
    Route::get('quiz-field-data/list-by-chapter/{chapter}', 'QuizFieldDatumController@listByChapter');
    Route::post('quiz-field-data/store-batch', 'QuizFieldDatumController@storeBatch');
    Route::put('quiz-field-data/update-batch', 'QuizFieldDatumController@updateBatch');
    Route::delete('quiz-field-data/destroy-by-question/{questionId}', 'QuizFieldDatumController@destroyByQuestion');
    Route::post('quiz-field-data/destroy-batch', 'QuizFieldDatumController@destroyBatch');
    Route::apiResource('quiz-field-data', 'QuizFieldDatumController');
    Route::apiResource('templates', 'TemplateController');

    Route::get('chapters/list-by-level/{level}', 'ChapterController@listByLevel');
    Route::get('chapters/list-by-subject/{subject}/{level}', 'ChapterController@listBySubject');
    Route::apiResource('chapters', 'ChapterController');

    Route::apiResource('chapter-types', 'ChapterTypeController');

    Route::get('scores/user-chapter-progress', 'ScoreController@userChapterProgress');
    Route::get('scores/user-score-by-chapter/{chapter}', 'ScoreController@userScoreByChapter');
    Route::post('scores/store-batch', 'ScoreController@storeBatch');
    Route::apiResource('scores', 'ScoreController');

    Route::apiResource('frames', 'FrameController');

    Route::get('badges/list-by-user/{user}', 'BadgeController@listByUser');
    Route::apiResource('badges', 'BadgeController');

    Route::post('classrooms/student', 'ClassroomController@addStudentToClassroom');
    Route::delete('classrooms/student/{classroomId}/{studentId}', 'ClassroomController@removeStudentFromClassroom');
    Route::get('classrooms/student/{classroomId}', 'ClassroomController@listClassroomStudents');
    Route::post('classrooms/teacher', 'ClassroomController@addTeacherToClassroom');
    Route::delete('classrooms/teacher/{classroomId}/{teacherId}', 'ClassroomController@removeTeacherFromClassroom');
    Route::get('classrooms/teacher/{classroomId}', 'ClassroomController@listClassroomTeachers');
    Route::apiResource('classrooms', 'ClassroomController');

    Route::get('achivements/user/{user}', 'AchivementController@listByUser');
    Route::apiResource('achivements', 'AchivementController');
    Route::get('rewards/user/{user}', 'RewardController@getUserReward');
    Route::put('rewards/user/{user}/{reward}', 'RewardController@updateUserReward');
    Route::post('rewards/user', 'RewardController@addRewardToUser');
    Route::delete('rewards/user/{userId}/{rewardId}', 'RewardController@removeRewardFromUser');
    Route::apiResource('rewards', 'RewardController');

    Route::get('quizo/user/{user}', 'QuizoController@getByUser');
    Route::get('quizo/quizo-item/{quizoItem}', 'QuizoController@getByQuizoItem');
    Route::post('quizo/quizo-item', 'QuizoController@addQuizoItemToQuizo');
    Route::delete('quizo/quizo-item/{quizo}/{quizoItem}', 'QuizoController@removeQuizoItemFromQuizo');
    Route::apiResource('quizo', 'QuizoController');
    Route::get('quizo-item/quizo/{quizo}', 'QuizoItemController@getByQuizo');
    Route::apiResource('quizo-item', 'QuizoItemController');

    Route::get('homeworks/classroom/{classroomId}', 'HomeworkController@listByClassroom');
    Route::apiResource('homeworks', 'HomeworkController')->except('index');

    Route::apiResource('payment-methods', 'PaymentMethodController')->only('index');
    Route::post('subscriptions', 'SubscriptionController@subscription');
    Route::post('subscriptions/price', 'SubscriptionController@calculatePrice');
    Route::get('tokens/{token}/check', 'SubscriptionController@checkToken');
    Route::apiResource('packs', 'PackController')->only('index');
    Route::get('packs/level/{levelId}', 'PackController@byLevel');

    Route::get('datal', 'LogisticController@index');


    Route::get('date', function (){
        return date('m-d-Y/h:m:s');
    });

});