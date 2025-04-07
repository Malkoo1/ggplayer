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

        Route::get('/settings', [AdminController::class, 'settingsPage']);
        Route::post('/settings/update', [AdminController::class, 'updateSettings'])->name('update.settings');

        Route::get('/userrecord/{id}', [AdminController::class, 'userDelete'])->name('user.delete');

        Route::get('/licence/{id}', [AdminController::class, 'userLicence'])->name('user.licence');

        Route::post('/licence/{id}', [AdminController::class, 'makeUserLicence'])->name('update.licence');

        Route::post('/licence/remove/{id}', [AdminController::class, 'removeUserLicence'])->name('licence.remove');



        Route::get('/logout', [AdminController::class, 'signOut']);
    });
});


Route::get('/privacy-policy', function () {
    return view('privacy_policy');
});

Route::get('/terms-and-conditions', function () {
    return view('terms');
});

Route::get('/disclaimer', function () {
    return view('disclaimer');
});
Route::get('/copyright-complaints', function () {
    return view('complaints');
});

Route::get('/contact-us', function () {
    return view('contact_us');
});
