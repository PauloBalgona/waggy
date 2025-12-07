<?php

use App\Http\Controllers\locationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FriendRequestController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingsController;


/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES (no login required)
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('landing.landing');
})->name('landing');

Route::get('/signup', [AuthController::class, 'showSignup'])->name('signup');
Route::post('/signup', [AuthController::class, 'register'])->name('signup.post');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

/* PUBLIC PAGES */
Route::get('/discover', function () {
    return view('landing.discover');
})->name('discover');

Route::get('/guidelines', function () {
    return view('landing.guidelines');
})->name('guideline');

Route::get('/helpcenter', function () {
    return view('landing.helpcenter');
})->name('helpcenter');


/*
|--------------------------------------------------------------------------
| PRIVATE ROUTES (requires login)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/location', [LocationController::class, 'index'])->name('location');

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::get('/profile/{userId}', [ProfileController::class, 'show'])->name('profile.show');

    Route::get('/certificate', [CertificateController::class, 'index'])->name('certificate');
    Route::post('/certificate', [CertificateController::class, 'upload'])->name('verifycertificate.verify');

    Route::get('/posting', [PostController::class, 'postingPage'])->name('posting.page');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('/create-post', [PostController::class, 'create'])->name('posts.create');
    Route::post('/set-upload-session', [PostController::class, 'setUploadSession']);
    Route::delete('/posts/{id}', [PostController::class, 'destroy'])->name('posts.destroy');
    Route::post('/posts/{id}/like', [PostController::class, 'like'])->name('posts.like');
    Route::post('/posts/{id}/report', [PostController::class, 'report'])->name('posts.report');
    Route::post('/posts/{user_id}/block', [PostController::class, 'block'])->name('user.block');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markRead'])->name('notifications.read');

    Route::get('/settings', [SettingsController::class, 'index'])->name('setting');
    Route::get('/settings/edit-profile', [SettingsController::class, 'editProfile'])->name('editprofile');
    Route::put('/settings/edit-profile', [SettingsController::class, 'updateProfile'])->name('editprofile.update');

    Route::get('/settings/change-password', [SettingsController::class, 'changePassword'])->name('changepassword');
    Route::put('/settings/change-password', [SettingsController::class, 'updatePassword'])->name('password.update');

    Route::get('/settings/delete-account', [SettingsController::class, 'deleteAccount'])->name('delete-account');
    Route::get('/settings/accounts', [SettingsController::class, 'accounts'])->name('account');

    Route::get('/friend-requests', [FriendRequestController::class, 'index'])->name('friend.requests');
    Route::get('/friend-request/send', function () {
        return redirect()->back()->with('error', 'Invalid request method.');
    });
    Route::post('/friend-request/send', [FriendRequestController::class, 'send'])->name('friend.request.send');
    Route::post('/friend-request/accept', [FriendRequestController::class, 'accept'])->name('friend.request.accept');
    Route::post('/friend-request/decline', [FriendRequestController::class, 'decline'])->name('friend.request.decline');
    Route::post('/friend/accept/{id}', [FriendRequestController::class, 'accept'])->name('friend.accept');
    Route::post('/friend/decline/{id}', [FriendRequestController::class, 'decline'])->name('friend.decline');
    Route::post('/friend-request/cancel/{id}', [ProfileController::class, 'cancelFriend'])->name('friend.request.cancel');
    Route::post('/friend/add/{id}', [ProfileController::class, 'addFriend'])->name('friend.add');
    Route::post('/friend/cancel/{id}', [ProfileController::class, 'cancelFriend'])->name('friend.cancel');
    Route::post('/friend/unfriend/{id}', [ProfileController::class, 'unfriend'])->name('friend.unfriend');

    Route::get('/posts/{post}/comments', [CommentController::class, 'index'])->name('comments.index');
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');

    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{user}', [MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/{user}', [MessageController::class, 'store'])->name('messages.store');

});
