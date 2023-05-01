<?php

use App\Http\Controllers\Api\CatgoryController;
use App\Http\Controllers\Api\PostController;
use App\Models\HistoryUserScore;
use App\Models\UserScore;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group(['prefix' => 'v1', 'middleware' => 'lang'], function () {
    Route::apiResource('posts', PostController::class);
    Route::apiResource('categories', CatgoryController::class);
});

Route::get('scores', function () {
    $score = HistoryUserScore::latest()->first();
    $points = UserScore::where('name',$score->name)->first();

    return sendJsonResponse([
        'name' => $score->name,
        'points' => (int)$points->points,

        'type_answer' => $score->type_answer
    ], 'scores');
});
