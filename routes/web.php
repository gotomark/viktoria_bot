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


//Route::get('/test', [\App\Http\Controllers\TelegramController::class,'vkPhotos']);

Route::get('/home', function () {
    return redirect(route('user.telegram-sync'));
});


Route::get('/vkontakte-sync', [\App\Http\Controllers\HomeController::class,'vkontakteSync'])->name('user.vkontakte-sync');
Route::post('/update-sync-vkontakte', [\App\Http\Controllers\HomeController::class,'updateSyncVkontakte'])->name('user.update.sync-vkontakte');
Route::get('/delete-album/{userVkontakteAlbum}', [\App\Http\Controllers\HomeController::class,'deleteAlbum'])->name('user.delete.album');
Route::post('/store-album', [\App\Http\Controllers\HomeController::class,'storeAlbum'])->name('user.store.album');


Route::get('/telegram-sync', [\App\Http\Controllers\HomeController::class,'telegramSync'])->name('user.telegram-sync');
Route::post('/hook-main-bot', [\App\Http\Controllers\TelegramController::class,'hook']);
Route::post('/user/{user}', [\App\Http\Controllers\TelegramController::class,'hookUser']);
Route::post('/update-user', [\App\Http\Controllers\HomeController::class,'updateUser'])->name('user.update.token');
Route::post('/update-sync-channels', [\App\Http\Controllers\HomeController::class,'updateSyncChannels'])->name('user.update.sync-channels');





