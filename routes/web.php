<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;

// Route::get('/', function () {
//     return view('welcome');
// });


Route::get('/', [AdminController::class, 'showLogin'])->name('login');
Route::post('/login/auth', [AdminController::class, 'authLogin']);

Route::middleware(['auth'])->group(function () {

    Route::group(['prefix' => 'admin'], function () {
        Route::get('/home', [AdminController::class, 'adminHome'])->name('home');

        Route::post('/switchupdate', [AdminController::class, 'switchUpdate'])->name('switch.update');

        Route::post('/update_url/{id}', [AdminController::class, 'updateUrl'])->name('update.url');

        Route::get('/userrecord/{id}', [AdminController::class, 'userDelete'])->name('user.delete');

        Route::get('/updatepassword', [AdminController::class, 'updateShowPage']);
        Route::post('/changepassword', [AdminController::class, 'updateLoginCrend'])->name('update.crend');


        Route::get('/logout', [AdminController::class, 'signOut']);
    });
});
