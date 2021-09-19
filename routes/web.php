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
Route::middleware('Auth')->get('/List', [MainController::class, 'list']);

//ID pattern for {id} = '[0-9]+'

//view inside a list
Route::middleware('Auth')->get('/ImageList/{id}', [MainController::class, 'imagelist']);

//view single image
Route::middleware('Auth')->get('/SingleImage/{id}', [MainController::class, 'singleimage']);

//delete temp folder
Route::middleware('Auth')->get('/Finish', [MainController::class, 'finish']);

//logout
Route::middleware('Auth')->get('/Logout', [MainController::class, 'logout']);
