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

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('region')->group(function(){
    Route::get('/{id?}','TerritoireController@region');
});

Route::prefix('departement')->group(function(){
    Route::get('/{id?}','TerritoireController@departement');
});


Route::prefix('commune')->group(function(){
    Route::get('/{id?}','TerritoireController@commune');
});

Route::prefix('code_postal')->group(function(){
    Route::get('/{id?}','TerritoireController@codePostal');
});