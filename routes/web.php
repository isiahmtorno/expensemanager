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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', 'HomeController@index');

Route::post('/login', 'HomeController@login');
Route::get('/logout', 'HomeController@logout');

Route::any('/usersapi/{request?}', 'HomeController@users');
Route::any('/rolesapi/{request?}', 'HomeController@roles');
Route::any('/categoryapi/{request?}', 'HomeController@expense_category');
Route::any('/expensesapi/{request?}', 'HomeController@expenses');

Route::get('{any}', 'HomeController@index')->where('any', '.*');