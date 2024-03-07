<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SalesapiController;

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

// Route::get('account/tasks/salestasksdata', [SalesapiController::class, 'salestasksdata']);

ApiRoute::group(['namespace' => 'App\Http\Controllers'], function () {
    ApiRoute::get('purchased-module', ['as' => 'api.purchasedModule', 'uses' => 'HomeController@installedModule']);
    
});


