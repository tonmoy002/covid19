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


Route::get('', 'Covid19Controller@homePage')->name('home_page');

Route::post('get_all_covid_data',                  ['as' => 'covid-19-all-data',                    'uses' => 'Covid19Controller@getAllCovid19Data']);
Route::get('covid19/{name}',                       ['as' => 'covid19.by.country',                   'uses' => 'Covid19Controller@getCovidDataBasedOnCountry']);
Route::post('covid19_map_data',                    ['as' => 'covid19.map.data.by.country',          'uses' => 'Covid19Controller@getCovid19DataByCountryForChart']);


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::post('/upload', 'AdminController@importExcelData')->name('upload-data-admin');


