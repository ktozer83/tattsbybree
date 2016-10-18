<?php

Route::group(['middleware' => ['web']], function () {
    /*
     * Public Pages
     */
    Route::get('/', [
        'uses' => 'PublicController@getHome'
    ]);
    
    Route::get('/about', [
        'uses' => 'PublicController@getAbout'
    ]);
    
    Route::get('/portfolio', [
        'uses' => 'PublicController@getPortfolio'
    ]);

    Route::get('/faq', [
        'uses' => 'PublicController@getFaq'
    ]);
    
    Route::get('/contact', [
        'uses' => 'PublicController@getContact'
    ]);
    
    Route::post('/contact', [
        'uses' => 'PublicController@postContact'
    ]);
    
    /*
     * Auth Pages
     */
    Route::get('/login', [
        'uses' => 'Auth\AuthController@getLogin'
    ]);
    
    Route::post('/login', [
        'uses' => 'Auth\AuthController@postLogin'
    ]);
    
    Route::get('/register', [
        'uses' => 'Auth\AuthController@getRegister'
    ]);
    
    Route::post('/register', [
        'uses' => 'Auth\AuthController@postRegister'
    ]);
    
    Route::get('/logout', [
        'uses' => 'Auth\AuthController@doLogout'
    ]);
    
    Route::get('/forgot', [
        'uses' => 'Auth\PasswordController@getForgotPass'
    ]);
    
    /*
     * Password reset pages
     */
    Route::post('/forgot', [
        'uses' => 'Auth\PasswordController@postForgotPass'
    ]);
    
    Route::get('/reset_password/{token?}', [
        'uses' => 'Auth\PasswordController@getResetPassword'
    ]);
    
    Route::post('/reset_password/{token?}', [
        'uses' => 'Auth\PasswordController@postResetPassword'
    ]);
    
    /*
     * User settings page
     */
    Route::get('/members/settings', [
        'middleware' => 'auth',
        'uses' => 'Auth\UserController@getAccountSettings'
    ]);
    
    Route::post('/members/settings', [
        'middleware' => 'auth',
        'uses' => 'Auth\UserController@postAccountSettings'
    ]);
    
    /*
     * quote pages
     */
    Route::get('/members/quote', [
        'middleware' => 'auth',
        'uses' => 'PrivateController@getQuoteForm'
    ]);
    
    Route::post('/members/quote', [
        'middleware' => 'auth',
        'uses' => 'PrivateController@postQuoteForm'
    ]);
    
    /*
     * member home
     */
    Route::get('/members', [
        'middleware' => 'auth',
        'uses' => 'PrivateController@getMembersHome'
    ]);
    
    /*
     * details page
     */
    Route::get('/members/details/{id?}', [
        'middleware' => 'auth',
        'uses' => 'PrivateController@getDetails'
    ]);
    
    Route::post('/members/details/{id?}', [
        'middleware' => 'auth',
        'uses' => 'PrivateController@postDetails'
    ]);
    
    /*
     * admin related
     */
    
    /*
     * Pending Quotes
     */
    Route::get('/members/admin/pending', [
        'middleware' => 'auth',
        'uses' => 'AdminController@getPendingQuotes'
    ]);
    
    /*
     * all appointments
     */
    Route::get('/members/admin/appointments', [
        'middleware' => 'auth',
        'uses' => 'AdminController@getAllAppointments'
    ]);
    
    /*
     * edit details
     */
    Route::get('/members/details/{id?}/edit', [
        'middleware' => 'auth',
        'uses' => 'AdminController@getEditDetails'
    ]);
    
    Route::post('/members/details/{id?}/edit', [
        'middleware' => 'auth',
        'uses' => 'AdminController@postEditDetails'
    ]);
    
    Route::post('/members/admin/quote/delete', [
        'middleware' => 'auth',
        'uses' => 'AdminController@postDeleteQuote'
    ]);
    
    /*
     * portfolio categories
     */
    Route::get('/members/admin/categories', [
        'middleware' => 'auth',
        'uses' => 'AdminController@getPortfolioCategories'
    ]);
    
    Route::post('members/admin/categories', [
        'middleware' => 'auth',
        'uses' => 'AdminController@postPortfolioCategories'
    ]);
    
    Route::post('members/admin/categories/delete', [
        'middleware' => 'auth',
        'uses' => 'AdminController@postDeleteCategory'
    ]);
    
    /*
     * portfolio images
     */
    // edit images
    Route::get('/members/admin/images', [
        'middleware' => 'auth',
        'uses' => 'AdminController@getEditPortfolioImages'
    ]);
    
    Route::post('members/admin/images', [
        'middleware' => 'auth',
        'uses' => 'AdminController@postEditPortfolioImages'
    ]);
    
    // delete image
    Route::post('members/admin/images/delete', [
        'middleware' => 'auth',
        'uses' => 'AdminController@postDeletePortfolioImage'
    ]);
    
    // image upload
    Route::get('/members/admin/images/upload', [
        'middleware' => 'auth',
        'uses' => 'AdminController@getUploadPortfolioImages'
    ]);
    
    Route::post('/members/admin/images/upload', [
        'middleware' => 'auth',
        'uses' => 'AdminController@postUploadPortfolioImages'
    ]);
    
    /*
     * viewing all users
     */
    Route::get('members/admin/users', [
        'middleware' => 'auth',
        'uses' => 'AdminController@getViewUsers'
    ]);
    
    Route::post('members/admin/users', [
        'middleware' => 'auth',
        'uses' => 'AdminController@postViewUsers'
    ]);
    
    /*
     * booking status
     */
    Route::get('members/admin/booking', [
        'middleware' => 'auth',
        'uses' => 'AdminController@getBookingStatus'
    ]);
    
    Route::post('members/admin/booking', [
        'middleware' => 'auth',
        'uses' => 'AdminController@postBookingStatus'
    ]);
    
});
