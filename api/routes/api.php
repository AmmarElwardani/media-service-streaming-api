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

// Route::get('/welcome', function () {
//     return view('welcome');
// });

Route::group([

    'prefix' => 'auth',
    'namespace' => 'Auth'

], function () {

    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::get('me', 'AuthController@me');
    Route::get('loggeduser', 'AuthController@getAuthenticatedUser');
    Route::post('register', 'AuthController@register');

});

Route::group([
    
    'prefix' => 'user'

], function () {
    
    Route::post('register', 'UserController@store');
    Route::get('show/{id}', 'UserController@show')->middleware('jwt.verify');
    Route::patch('update/{id}', 'UserController@update')->middleware('jwt.verify');
    Route::delete('delete/{id}', 'UserController@destroy')->middleware('jwt.verify');
});
//in order for the update image to work you need to send it in post method with the enctype=multipart/form-data in the form that's why the post method route  after this
Route::resource('channels', 'ChannelController');
//this is temporary
Route::post('/channels/{channel}', 'ChannelController@update');

Route::get('videos/{video}', 'VideoController@show');
Route::put('videos/{video}', 'VideoController@updateViews');
Route::get('videos/{video}/comments', 'CommentController@index');
Route::get('comments/{comment}/replies', 'CommentController@show');
Route::put('videos/{video}/update', 'VideoController@update')->middleware(['auth', 'jwt.verify']);


Route::post('/upload-video', 'VideoController@upload')->middleware('jwt.verify');

Route::middleware(['auth', 'jwt.verify'])->group(function () {
    // testing
    Route::post('comments/{video}', 'CommentController@store');
    Route::post('votes/{entityId}/{type}', 'VoteController@vote');
    Route::get('channels/{channel}/videos', 'VideoController@index');
    Route::post('/channels/{channel}/videos', 'VideoController@store');
    Route::resource('/channels/{channel}/subscriptions', 'SubscriptionController')->only(['store', 'destroy']);

});


