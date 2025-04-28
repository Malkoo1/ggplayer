<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ResellerController;
use App\Http\Controllers\ChatController;

// Route::get('/', function () {
//     return view('welcome');
// });


// Authentication routes
Route::get('/', [AdminController::class, 'showLogin'])->name('login');
Route::post('/login/auth', [AdminController::class, 'authLogin']);

// Admin routes
Route::middleware(['admin'])->group(function () {
    Route::group(['prefix' => 'admin'], function () {
        Route::get('/dashboard', [AdminController::class, 'adminHome'])->name('admin.dashboard');
        Route::get('/resellers', [ResellerController::class, 'index'])->name('resellers.index');
        Route::get('/resellers/create', [ResellerController::class, 'create'])->name('resellers.create');
        Route::post('/resellers', [ResellerController::class, 'store'])->name('resellers.store');
        Route::get('/resellers/{reseller}', [ResellerController::class, 'show'])->name('resellers.show');
        Route::get('/resellers/{reseller}/edit', [ResellerController::class, 'edit'])->name('resellers.edit');
        Route::put('/resellers/{reseller}', [ResellerController::class, 'update'])->name('resellers.update');
        Route::delete('/resellers/{reseller}', [ResellerController::class, 'destroy'])->name('resellers.destroy');

        Route::post('/switchupdate', [AdminController::class, 'switchUpdate'])->name('switch.update');
        Route::post('/update_url/{id}', [AdminController::class, 'updateUrl'])->name('update.url');
        Route::get('/userrecord/{id}', [AdminController::class, 'userDelete'])->name('user.delete');
        Route::get('/updatepassword', [AdminController::class, 'updateShowPage']);
        Route::post('/changepassword', [AdminController::class, 'updateLoginCrend'])->name('update.crend');
        Route::get('/settings', [AdminController::class, 'settingsPage']);
        Route::post('/settings/update', [AdminController::class, 'updateSettings'])->name('update.settings');
        Route::get('/licence/{id}', [AdminController::class, 'userLicence'])->name('user.licence');
        Route::post('/licence/{id}', [AdminController::class, 'makeUserLicence'])->name('update.licence');
        Route::post('/licence/remove/{id}', [AdminController::class, 'removeUserLicence'])->name('licence.remove');
        Route::get('/logout', [AdminController::class, 'signOut']);
    });
});

// Reseller routes
Route::middleware(['reseller'])->group(function () {
    Route::prefix('reseller')->name('reseller.')->group(function () {
        Route::get('/dashboard', [ResellerController::class, 'dashboard'])->name('dashboard');
        Route::get('/assign-app', [ResellerController::class, 'assignApp'])->name('assign_app');
        Route::post('/search-app', [ResellerController::class, 'searchApp'])->name('search_app');
        Route::post('/search-app-ajax', [ResellerController::class, 'searchAppAjax'])->name('search_app_ajax');
        Route::get('/assign-app/{id}', [ResellerController::class, 'assignAppDetails'])->name('assign_app_details');
        Route::post('/assign-app/{id}', [ResellerController::class, 'assignAppProcess'])->name('assign_app_process');
        Route::get('/app/{id}/edit', [ResellerController::class, 'editApp'])->name('edit_app');
        Route::post('/app/{id}/update', [ResellerController::class, 'updateApp'])->name('update_app');
        Route::get('/profile', [ResellerController::class, 'profile'])->name('profile');
        Route::post('/profile', [ResellerController::class, 'updateProfile'])->name('update_profile');
        Route::get('/logout', [ResellerController::class, 'logout'])->name('logout');
    });
});

// Chat Routes
Route::middleware(['admin_or_reseller'])->group(function () {
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::post('/chat/group', [ChatController::class, 'createGroup'])->name('chat.createGroup');
    Route::put('/chat/group/{conversation}', [ChatController::class, 'updateGroup'])->name('chat.updateGroup');
    Route::post('/chat/{conversation}/message', [ChatController::class, 'sendMessage'])->name('chat.sendMessage');
    Route::get('/chat/{conversation}/messages', [ChatController::class, 'getMessages'])->name('chat.messages');
    Route::get('/chat/{conversation}/check-latest', [ChatController::class, 'checkLatestMessages'])->name('chat.check-latest');
    Route::get('/chat/check-all', [ChatController::class, 'checkAllConversations'])->name('chat.check-all');
    Route::post('/chat/{conversation}/toggle-favorite', [ChatController::class, 'toggleFavorite'])->name('chat.toggle-favorite');
    Route::post('/chat/{conversation}/mute', [ChatController::class, 'toggleMute'])->name('chat.toggleMute');
    Route::post('/chat/message/{message}/edit', [ChatController::class, 'editMessage'])->name('chat.editMessage');
    Route::delete('/chat/message/{message}', [ChatController::class, 'deleteMessage'])->name('chat.deleteMessage');
    Route::get('/chat/{conversation}', [ChatController::class, 'getConversationDetails'])->name('chat.details');
    Route::delete('/chat/{conversation}', [ChatController::class, 'deleteConversation'])->name('chat.deleteConversation');
    Route::post('/chat/direct', [ChatController::class, 'startDirectChat'])->name('chat.direct');
    Route::post('/chat/upload-image', [ChatController::class, 'uploadImage'])->name('chat.upload.image');
});

// Public routes
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
