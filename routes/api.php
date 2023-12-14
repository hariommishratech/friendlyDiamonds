<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('filter/min-max','Api\Frontend\Loose\LooseFilterController@getLooseFilter');
Route::get('filter/loose', 'Api\Frontend\Loose\LooseFilterController@getLooseFilter');
Route::get('detail/{slug}', 'Api\Frontend\Loose\LooseDetailController@getLooseDetail');
Route::get('detailbyid/{id}', 'Api\Frontend\Loose\LooseDetailController@getLooseDetailById');
Route::get('filter/blousy', 'Api\Frontend\Blousy\BlousyFilterController@getBlousyFilter');
Route::get('setting/list', 'Api\Frontend\SettingList\SettingListController@getSetting');



