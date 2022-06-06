<?php

use Illuminate\Support\Facades\Route;

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
Route::auth();
Route::get('/', function () {
    return redirect('login');
});
Route::get('/home', [\App\Http\Controllers\HomeController::class,'index'])->name('user.home');

Route::post('/test', [\App\Http\Controllers\TelegramController::class,'hook']);
Route::post('/hook-main-bot', [\App\Http\Controllers\TelegramController::class,'hook']);
Route::post('/user/{user}', [\App\Http\Controllers\TelegramController::class,'hookUser']);
Route::post('/update-user', [\App\Http\Controllers\HomeController::class,'updateUser'])->name('user.update.token');
Route::post('/update-sync-channels', [\App\Http\Controllers\HomeController::class,'updateSyncChannels'])->name('user.update.sync-channels');




