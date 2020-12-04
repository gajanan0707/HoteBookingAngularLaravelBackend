<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group([

    'middleware' => 'api',
    'namespace' => 'App\Http\Controllers',
    'prefix' => 'auth'


], function ($router) {

    Route::post('contact_form', 'FrontApi@save_contact_query');
    Route::post('subscribe_user', 'FrontApi@subscribe_user');
    Route::get('getService', 'FrontApi@getService');
    Route::get('get_room_type', 'FrontApi@get_room_type');
    Route::get('feedback_type', 'FrontApi@feedback_type');
    Route::post('room_booking_request', 'FrontApi@room_booking_request');
    Route::delete('cancelBooking/{id}','FrontApi@cancelBooking');
    //get one Api
    Route::get('getonebookingRequest/{id}', 'FrontApi@getOneBookingRequest');
    Route::patch('updateBookingRequest/{id}','FrontApi@updateBookingRequest');

    Route::post('save_feedback', 'FrontApi@save_feedback');
    Route::get('userbookingrequest', 'FrontApi@userbookingrequest');
    Route::get('getFeedback', 'FrontApi@getFeedback');

    Route::post('login', 'AuthController@login');
    Route::post('register', 'AuthController@register');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');

     //Add sendPasswordResetLink Api
     Route::post('sendPasswordResetLink', 'ResetPasswordController@sendEmail');

     //Add resetPassword Api
     Route::post('resetPassword', 'ChangePasswordController@process');
    // Route::post('allusers', 'userController@allusers');
    // Route::post('userinfo', 'userController@userInfo');
});

