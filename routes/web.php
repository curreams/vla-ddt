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

// Favorites Route


Route::group(
    [
    'middleware' => ['auth'],
    'prefix' => 'saved'
],
    function () {
        Route::post('/store', 'SavedSearchesController@store')
        ->name('saved.store');
        Route::get('/get', 'SavedSearchesController@get')
        ->name('saved.get');
        Route::post('/remove/{savedSearch}', 'SavedSearchesController@remove')
        ->name('saved.destroy')
        ->where('id', '[0-9]+');
    }
);

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

// Search
Route::group(
    [
    'middleware' => ['auth'],
    'prefix' => 'search'
],
    function () {
        Route::post('/getbarlinechart', 'SearchController@getBarLineChart')
        ->name('search.getbarlinechart');
        Route::post('/getpiechart', 'SearchController@getPieChart')
        ->name('search.getpiechart');
        Route::post('/getmapvalues', 'SearchController@getMapValues')
        ->name('search.getmapvalues');
        Route::post('/gettable', 'SearchController@getTable')
        ->name('search.gettable');
    }
);

// NLAS
Route::group(
    [
    'middleware' => ['auth'],
    'prefix' => 'nlas'
],
    function () {
        Route::post('/getnlasmapdata', 'NLASController@getNLASMapData')
        ->name('nlas.getnlasmapdata');
    }
);

// Priority Group
Route::group(
    [
    'middleware' => ['auth'],
    'prefix' => 'priority'
],
    function () {
        Route::post('/getprioritymapdata', 'PriorityController@getNLASMapData')
        ->name('priority.getprioritymapdata');
    }
);

// Filter
Route::group(
    [
    'middleware' => ['auth'],
    'prefix' => 'filter'
],
    function () {
        Route::post('/filterSA2', 'FilterController@filterSA2')
        ->name('filter.filterSA2');
    }
);


