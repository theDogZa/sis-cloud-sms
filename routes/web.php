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

// Example Routes
// Route::view('/', 'landing');
// Route::match(['get', 'post'], '/dashboard', function(){
//     return view('dashboard');
// });
// Route::view('/examples/plugin', 'examples.plugin');
// Route::view('/examples/blank', 'examples.blank');

Route::get('/', 'smsController@index')->name('send-sms');
Route::post('/history', 'smsController@history')->name('log-sms');
Route::get('/history', 'smsController@history')->name('history-sms');
Route::get('/config', 'smsController@configLogin')->name('login-config-sms');
Route::post('/config', 'smsController@config')->name('config-sms');
Route::put('/config', 'smsController@configUpdate')->name('update-config-sms');