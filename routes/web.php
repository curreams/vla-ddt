<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/','HomeController@index')->name('home');

Auth::routes(['register' => false]);

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => ['auth']], function() {
    Route::resource('roles', 'RoleController');
    Route::resource('users', 'UserController');
    Route::resource('filters', 'FilterController');
    Route::resource('filter_types', 'FilterTypeController');
    Route::resource('class', 'ClassController');
});

// Vue JS routes
Route::group(
    [
    'middleware' => ['auth'],
    'prefix' => 'class'
],
    function () {
        Route::post('/', 'ClassController@storeFilters')
        ->name('class.storeFilters');
    }
);


