<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\VideoLinkController;

// Admin Routes with grouping
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

    // Users
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [AuthController::class, 'listUsers'])->name('index');
        Route::get('{id}/edit', [AuthController::class, 'editUser'])->name('edit');
        Route::put('{id}', [AuthController::class, 'updateUser'])->name('update');
        Route::delete('{id}', [AuthController::class, 'deleteUser'])->name('delete');
    });



    // Change User Role
        Route::get('/change-role', [AuthController::class, 'changeRole'])->name('change-role');
        Route::get('/search-user', [AuthController::class, 'searchUser'])->name('search-user');
        Route::post('/change-user-role/{id}', [AuthController::class, 'changeUserRole'])->name('change-user-role');

});


////// Public Routes //////
Route::get('/', [HomeController::class, 'home'])->name('home');
Route::get('/welcome', [HomeController::class, 'index'])->name('welcome');

Route::controller(AuthController::class)->group(function () {
    // Login
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'login');

    // Register
    Route::get('/register', 'showRegisterForm')->name('register');
    Route::post('/register', 'register')->name('register.post');

    // Logout
    Route::post('/logout', 'logout')->name('logout');

    // Comman Dashboard Route
    Route::get('/dashboard-all', 'dashboardAll')->name('dashboard.all');

    // Profile
    Route::get('/edit_profile', 'editProfile')->name('edit.profile');
    Route::post('/update', 'updateProfile')->name('update.profile');
    Route::get('/{slug}', 'Profile')->name('profile.view');

    // Password
    Route::get('/password/change', 'showChangePassword')->name('change.password.form');
    Route::post('/password/change', 'changePassword')->name('change.password');
});
////// End Public Routes //////

////// Authenticated Dashboard Routes //////
Route::get('/dashboard', [DashboardController::class, 'index'])
            ->middleware('auth')->name('dashboard.index');
Route::middleware(['auth'])->group(function () {
        Route::get('/admin/dashboard', [DashboardController::class, 'adminDashboard'])
            ->name('admin.dashboard');
        Route::get('/promoter/dashboard', [DashboardController::class, 'promoterDashboard'])
            ->name('promoter.dashboard');
        Route::get('/guest/dashboard', [DashboardController::class, 'guestDashboard'])
            ->name('guest.dashboard');
});
////// End Authenticated Dashboard Routes //////

////// Image and Video Links Routes //////
// Image Gallery
Route::get('/media/images/create', [ImageController::class, 'create'])->name('images.create');
Route::post('/media/images/store', [ImageController::class, 'store'])->name('images.store');
Route::delete('/media/images/{id}', [ImageController::class, 'destroy'])->name('images.destroy');
// Video Gallery
Route::get('/media/videos/create', [VideoLinkController::class, 'create'])->name('videos.create');
Route::post('/media/videos/store', [VideoLinkController::class, 'store'])->name('videos.store');
Route::delete('/media/videos/{id}', [VideoLinkController::class, 'destroy'])->name('videos.destroy');
////// End Image and Video Links Routes //////



