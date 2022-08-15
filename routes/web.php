<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SearchController;
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
    return view('layouts.index');
});

Route::get('/',[SearchController::class,'showDocument']);
//Search
Route::get('alphabet/{alphabet}/{page}', [SearchController::class,'alphabet']);
Route::get('year/{year}/{page}', [SearchController::class,'year']);
Route::get('search', [SearchController::class,'search']);
Route::get('get_document_based_on_tag/{id}/{page}', [SearchController::class,'get_document_based_on_tag']);
