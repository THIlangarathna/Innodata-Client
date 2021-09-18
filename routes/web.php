<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;

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

//load page
Route::get('/Auth', [MainController::class, 'index']);

//send the bearer token
Route::post('/Auth', [MainController::class, 'auth']);

//view submission list
Route::get('/List', [MainController::class, 'list']);

//view inside a list
Route::get('/ImageList/{id}', [MainController::class, 'imagelist']);

//view single image
Route::get('/SingleImage/{id}', [MainController::class, 'singleimage']);

//delete temp folder
Route::get('/Finish', [MainController::class, 'finish']);
