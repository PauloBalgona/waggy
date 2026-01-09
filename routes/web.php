<?php

use App\Http\Controllers\locationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\FriendRequestController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CommentController;


Route::post('/broadcasting/auth', function (\Illuminate\Http\Request $request) {
    if (auth()->check()) {
        return response()->json(['authenticated' => true]);
    }
    return response()->json(['message' => 'Forbidden'], 403);
})->name('broadcasting.auth');
// Load broadcasting channel definitions (so broadcasting.auth route is available)
if (file_exists(__DIR__ . '/channels.php')) {
    require __DIR__ . '/channels.php';
}


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

/* ADMIN AUTH ROUTES */
Route::get('/admin/login', [AdminController::class, 'showAdminLogin'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'adminLogin'])->name('admin.login.post');
Route::get('/admin/register', [AdminController::class, 'showAdminRegister'])->name('admin.register');
Route::post('/admin/register', [AdminController::class, 'registerAdmin'])->name('admin.register.post');
Route::post('/admin/logout', [AdminController::class, 'adminLogout'])->name('admin.logout');

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
    Route::get('/posts/{id}', [PostController::class, 'show'])->name('posts.show');
    Route::delete('/posts/{id}', [PostController::class, 'destroy'])->name('posts.destroy');
    Route::get('/posts/{id}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{id}', [PostController::class, 'update'])->name('posts.update');
    Route::post('/posts/{id}/like', [PostController::class, 'like'])->name('posts.like');
    Route::post('/posts/{id}/report', [PostController::class, 'report'])->name('posts.report');
    Route::post('/posts/{user_id}/block', [PostController::class, 'block'])->name('user.block');
    Route::post('/posts/{user_id}/unblock', [PostController::class, 'unblock'])->name('user.unblock');

    Route::get('/posts/{post}/comments', [CommentController::class, 'index'])->name('comments.index');
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{id}', [CommentController::class, 'destroy'])->name('comments.destroy');
    Route::put('/comments/{id}', [CommentController::class, 'update'])->name('comments.update');

    Route::get('/messages', [MessageController::class, 'index'])->name('messages');
    Route::post('/comments/{commentId}/replies', [CommentController::class, 'storeReply'])->name('replies.store');
    Route::delete('/replies/{replyId}', [CommentController::class, 'destroyReply'])->name('replies.destroy');

    Route::get('/messages', [MessageController::class, 'index'])->name('messages');
    Route::get('/messages/{userId}', [MessageController::class, 'conversation'])->name('messages.conversation');
    Route::get('/api/messages/{userId}', [MessageController::class, 'getConversation'])->name('messages.getConversation');

    Route::post('/messages/send', [MessageController::class, 'send'])->name('messages.send');
    Route::post('/messages/send-request', [MessageController::class, 'sendRequest'])->name('messages.sendRequest');
    Route::post('/messages/accept/{messageId}', [MessageController::class, 'acceptRequest'])->name('messages.accept');
    Route::post('/messages/reject/{messageId}', [MessageController::class, 'rejectRequest'])->name('messages.reject');
    Route::delete('/messages/{messageId}', [MessageController::class, 'deleteMessage'])->name('messages.delete');
    Route::delete('/conversations/{userId}', [MessageController::class, 'deleteConversation'])->name('conversations.delete');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications');
    Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount'])->name('notifications.unreadCount');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markRead'])->name('notifications.read');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');

    Route::get('/settings', [SettingsController::class, 'index'])->name('setting');
    Route::get('/settings/edit-profile', [SettingsController::class, 'editProfile'])->name('editprofile');
    Route::put('/settings/edit-profile', [SettingsController::class, 'updateProfile'])->name('editprofile.update');

    Route::get('/settings/change-password', [SettingsController::class, 'changePassword'])->name('changepassword');
    Route::put('/settings/change-password', [SettingsController::class, 'updatePassword'])->name('password.update');

    Route::get('/settings/delete-account', [SettingsController::class, 'deleteAccount'])->name('delete-account');
    Route::get('/settings/accounts', [SettingsController::class, 'accounts'])->name('account');
    Route::get('/settings/blocked-users', [SettingsController::class, 'blockedUsers'])->name('blocked-users');

    Route::get('/friend-requests', [FriendRequestController::class, 'index'])->name('friend.requests');
    Route::get('/friend-request/send', function () {
        return redirect()->back()->with('error', 'Invalid request method.');
    });
    Route::post('/friend-request/send', [FriendRequestController::class, 'send'])->name('friend.request.send');
    Route::post('/friend-request/accept', [FriendRequestController::class, 'accept'])->name('friend.request.accept');
    Route::post('/friend-request/decline', [FriendRequestController::class, 'decline'])->name('friend.request.decline');
    Route::post('/friend-request/cancel/{id}', [ProfileController::class, 'cancelFriend'])->name('friend.request.cancel');
    Route::post('/friend/add/{id}', [ProfileController::class, 'addFriend'])->name('friend.add');
    Route::post('/friend/cancel/{id}', [ProfileController::class, 'cancelFriend'])->name('friend.cancel');
    Route::post('/friend/unfriend/{id}', [ProfileController::class, 'unfriend'])->name('friend.unfriend');

    /*
    |--------------------------------------------------------------------------
    | API ROUTES (JSON responses)
    |--------------------------------------------------------------------------
    */
    Route::get('/api/users/search', [ProfileController::class, 'apiSearchUsers'])->name('api.users.search');
    Route::get('/api/unread-counts', [NotificationController::class, 'counts'])->name('api.unreadCounts');

    /*
    |--------------------------------------------------------------------------
    | ADMIN ROUTES (requires admin privilege)
    |--------------------------------------------------------------------------
    */
    Route::middleware('is_admin')->prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

        // Users Management
        Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
        Route::get('/users/{id}', [AdminController::class, 'showUser'])->name('admin.users.show');
        Route::get('/users/{id}/edit', [AdminController::class, 'editUser'])->name('admin.users.edit');
        Route::put('/users/{id}', [AdminController::class, 'updateUser'])->name('admin.users.update');
        Route::delete('/users/{id}', [AdminController::class, 'destroyUser'])->name('admin.users.destroy');

        // Posts Management
        Route::get('/posts', [AdminController::class, 'posts'])->name('admin.posts');
        Route::get('/posts/{id}', [AdminController::class, 'showPost'])->name('admin.posts.show');
        Route::delete('/posts/{id}', [AdminController::class, 'destroyPost'])->name('admin.posts.destroy');

        // Dogs Management
        Route::get('/dogs', [AdminController::class, 'dogs'])->name('admin.dogs');
        Route::get('/dogs/{id}', [AdminController::class, 'showDog'])->name('admin.dogs.show');

        // Certificates Management
        Route::get('/certificates', [AdminController::class, 'certificates'])->name('admin.certificates');
        Route::get('/certificates/{id}', [AdminController::class, 'showCertificate'])->name('admin.certificates.show');
        Route::post('/certificates/{id}/verify', [AdminController::class, 'verifyCertificate'])->name('admin.certificates.verify');
        Route::post('/certificates/{id}/reject', [AdminController::class, 'rejectCertificate'])->name('admin.certificates.reject');

        // Settings
        Route::get('/settings', [AdminController::class, 'settings'])->name('admin.settings');
        Route::get('/settings/admins', [AdminController::class, 'manageAdmins'])->name('admin.settings.admins');
        Route::post('/settings/admins', [AdminController::class, 'createAdmin'])->name('admin.settings.admins.store');
        Route::delete('/settings/admins/{id}', [AdminController::class, 'deleteAdmin'])->name('admin.settings.admins.delete');
        Route::get('/logs', [AdminController::class, 'logs'])->name('admin.logs');
    });

});
