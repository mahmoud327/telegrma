<?php

use App\Http\Controllers\Api\CatgoryController;
use App\Http\Controllers\Api\PostController;
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
Route::group(['prefix' => 'v1','middleware' => 'lang'],function ()
{
    Route::apiResource('posts', PostController::class);
    Route::apiResource('categories', CatgoryController::class);
});

Route::get('scores',function($q){
   $score=UserScore::latest()->first();

   return sendJsonResponse([
    'number_question'=>$score->number_question,
    'name'=>$score->name,
    'points'=>$score->points
   ],'scores');
});
