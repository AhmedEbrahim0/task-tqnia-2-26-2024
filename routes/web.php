<?php

use App\Mail\VerficationMail;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get("/login",function(){
    return "asf";
})->name("login");

Route::get('/', function () {
    
$response = Http::get('https://randomuser.me/api/');

if ($response->successful()) {
    $data = $response->json()["results"][0];
    // Process the fetched data herez
    return $data;
} else {
    // Handle the case where the request was not successful
    $statusCode = $response->status();
    // Log or handle the error
}
    return "asfsa";
});
