<?php

use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\TagsController;
use App\Http\Controllers\Api\StatsController;
use App\Http\Controllers\Api\VerificationEmailController;

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


Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);

Route::group(['middleware'=>['auth:sanctum','is_verify']]   ,function(){
    Route::post('/logout',[AuthController::class,'logout']);
    
    Route::get("/tags",[TagsController::class,'index']);
    Route::post("/add-tag",[TagsController::class,'add']);
    Route::post("/update-tag",[TagsController::class,'update']);
    Route::post("/delete-tag",[TagsController::class,'delete']);
    
    Route::get("/posts",[PostController::class,'index']);
    Route::get("/post/{post_id}",[PostController::class,'show']);
    Route::get("/deleted-posts",[PostController::class,'deleted_posts']);
    Route::post("/add-post",[PostController::class,'store']);
    Route::post("/update-post",[PostController::class,'update']);
    Route::post("/delete-post",[PostController::class,'delete']);
    Route::post("/restore-post",[PostController::class,'restore_post']);
    Route::post("/pinned-post",[PostController::class,'pinned_post']);
    Route::post("/remove-pinned-post",[PostController::class,'remove_pinned_post']);


    Route::get('/stats',[StatsController::class,'index']);
});


Route::middleware('auth:sanctum')->post('/verifications',[VerificationEmailController::class,"verify"]);