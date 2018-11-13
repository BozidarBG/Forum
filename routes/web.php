<?php


Auth::routes();

Route::post('/login', [
    'uses'=>'Auth\LoginController@authenticate',
    'as'=>'login'
]);

Route::get('/', [
    'uses'=>'FrontendController@index',
    'as'=>'home'
]);

Route::get('/most-replied', [
    'uses'=>'FrontendController@mostReplied',
    'as'=>'most.replied'
]);

Route::get('/most-liked', [
    'uses'=>'FrontendController@mostLiked',
    'as'=>'most.liked'
]);


Route::get('/all-tags', [
    'uses'=>'FrontendController@tags',
    'as'=>'all.tags'
]);

Route::get('/users', [
    'uses'=>'FrontendController@users',
    'as'=>'all.users'
]);

Route::get('/users/{hashid}', [
    'uses'=>'ProfileController@showUser',
    'as'=>'show.user'
]);

Route::post('/search', [
    'uses'=>'FrontendController@search',
    'as'=>'search'
]);



Route::get('/question/{slug}', [
    'uses'=>'FrontendController@question',
    'as'=>'question.show'
]);

Route::group(['middleware'=>['auth', 'banned']], function(){
    //admin routes
    Route::group(['middleware'=>'admin'], function(){

        Route::get('dashboard', [
            'uses'=>'AdminController@dashboard',
            'as'=>'admin.dashboard'
        ]);

        Route::get('admin-questions', [
            'uses'=>'AdminController@questions',
            'as'=>'admin.questions'
        ]);

        Route::post('delete-question/{question}', [
            'uses'=>'AdminController@deleteQuestion',
            'as'=>'delete.question'
        ]);

        Route::get('admin-replies', [
            'uses'=>'AdminController@replies',
            'as'=>'admin.replies'
        ]);

        Route::post('delete-reply/{reply}', [
            'uses'=>'AdminController@deleteReply',
            'as'=>'delete.reply'
        ]);

        Route::get('admin-users', [
            'uses'=>'AdminController@users',
            'as'=>'admin.users'
        ]);

        Route::post('admin-ban', [
            'uses'=>'AdminController@banUser',
            'as'=>'admin.ban.user'
        ]);
//CRUD for tags

        Route::get('admin-tags', [
            'uses'=>'TagController@index',
            'as'=>'admin.tags'
        ]);
        Route::post('tags', [
            'uses'=>'TagController@store',
            'as'=>'tags.store'
        ]);
        Route::post('tags-update', [
            'uses'=>'TagController@update',
            'as'=>'tags.update'
        ]);
        Route::post('tags-destroy', [
            'uses'=>'TagController@destroy',
            'as'=>'tags.destroy'
        ]);
        //complaints
        Route::get('admin-complaints', [
            'uses'=>'AdminController@complaints',
            'as'=>'admin.complaints'
        ]);
        Route::post('admin-complaint-delete/{complaint}', [
            'uses'=>'AdminController@deleteComplaint',
            'as'=>'admin.complaint.delete'
        ]);
        //CRUD for sponsors
        Route::get('admin-sponsors', [
            'uses'=>'SponsorController@index',
            'as'=>'admin.sponsors'
        ]);
        Route::get('admin-sponsors-create', [
            'uses'=>'SponsorController@create',
            'as'=>'admin.sponsors.create'
        ]);
        Route::post('admin-sponsors-store', [
            'uses'=>'SponsorController@store',
            'as'=>'admin.sponsors.store'
        ]);

        Route::get('admin-sponsors-edit/{sponsor}', [
            'uses'=>'SponsorController@edit',
            'as'=>'admin.sponsors.edit'
        ]);
        Route::post('admin-sponsors-update', [
            'uses'=>'SponsorController@update',
            'as'=>'admin.sponsors.update'
        ]);
        Route::post('admin-sponsors-delete', [
            'uses'=>'SponsorController@destroy',
            'as'=>'admin.sponsors.delete'
        ]);

//Users

    });//end middleware admin

    //authenticated users routes
    Route::get('my-profile', [
        'uses'=>'ProfileController@showMyProfile',
        'as'=>'my.profile'
    ]);
    Route::get('edit-profile', [
        'uses'=>'ProfileController@edit',
        'as'=>'edit.profile'
    ]);

    Route::post('update-avatar',[
        'uses'=>'ProfileController@updateAvatar',
        'as'=>'update.avatar'
    ]);

    Route::post('update-profile',[
        'uses'=>'ProfileController@updateProfile',
        'as'=>'update.profile'
    ]);

    Route::post('user-delete-profile',[
        'uses'=>'ProfileController@deleteProfile',
        'as'=>'user.delete.profile'
    ]);

    //aks questions if you are authenticated
    Route::get('ask-a-question', [
        'uses'=>'QuestionController@create',
        'as'=>'question.create'
    ]);

    Route::post('question', [
        'uses'=>'QuestionController@store',
        'as'=>'question.store'
    ]);

    //reply
    Route::post('reply', [
        'uses'=>'ReplyController@store',
        'as'=>'reply.store'
    ]);

    //report a user
    Route::post('report-user', [
        'uses'=>'QuestionController@reportUser',
        'as'=>'report-user'
    ]);

    //anyone's profile

    Route::get('profile/{hashid}', [
        'uses'=>'ProfileController@show',
        'as'=>'profile.show'
    ]);

    //toggle like/unlike question
    Route::post('/question/like/{question}', [
        'uses'=>'QuestionController@likeQuestion',
        'as'=>'question.like'
    ]);

    //toggle like/unlike reply
    Route::post('/reply/like/{reply}', [
        'uses'=>'ReplyController@likeReply',
        'as'=>'reply.like'
    ]);

});

Route::get('login/{provider}', 'Auth\LoginController@redirectToProvider')
    ->where('provider','twitter|facebook|linkedin|google|github|bitbucket')
    ->name('social.login');

Route::get('login/{provider}/callback', 'Auth\LoginController@handleProviderCallback')
    ->where('provider','twitter|facebook|linkedin|google|github|bitbucket');

//Route::get('/login/{social}','Auth\LoginController@socialLogin')->where('social','twitter|facebook|linkedin|google|github|bitbucket');

//Route::get('/login/{social}/callback','Auth\LoginController@handleProviderCallback')->where('social','twitter|facebook|linkedin|google|github|bitbucket');

Route::get('/{slug}', [
    'uses'=>'FrontendController@showTag',
    'as'=>'tags'
]);