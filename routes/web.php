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

use App\Queue;
use Illuminate\Support\Carbon;

Route::get('/', 'LoginController@index');

Route::get('/login', 'LoginController@login');
Route::post('/login', 'LoginController@authenticate');
Route::get('/logout', 'LogoutController@logout');
Route::post('/logout', 'LogoutController@logout');

Route::get('/admin', 'AdminController@index');
Route::post('/admin', 'AdminController@update');
Route::post('/admin/print_queue', 'AdminController@printQueue');
Route::post('/admin/get/{data}', 'AdminController@getData');

Route::get('registration/', 'RegistrationController@register');
Route::post('registration/', 'RegistrationController@transaction');
Route::get('registration/{menu}', 'RegistrationController@index');

Route::get('queue', 'QueueController@index');

Route::post('queue/get/{data}', 'QueueController@getData');
Route::post('queue/request_call/', 'QueueController@requestCall');
Route::post('queue/call_again/', 'QueueController@callAgain');
Route::post('queue/request_next/', 'QueueController@requestNext');
Route::get('queue/display', 'QueueController@display');

Route::get('calling', 'CallingController@index');
Route::post('calling/get/{data}', 'CallingController@getData');
Route::post('calling/call/', 'CallingController@call');
Route::post('calling/called/', 'CallingController@called');

Route::post('data', 'DataController@getData');