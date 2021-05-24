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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/v1/invitations', 'App\Http\Controllers\V1\InvitationController@create');
Route::post('/v1/invitations/{reference}/cancel', 'App\Http\Controllers\V1\InvitationController@cancel');
Route::post('/v1/invitations/{reference}/respond', 'App\Http\Controllers\V1\InvitationController@respond');
