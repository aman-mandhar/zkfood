<?php

use Illuminate\Support\Facades\Route;

// =========================
// Controllers (Public/Auth)
// =========================
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\VideoLinkController;

// =========================
// Customer / Ordering
// =========================
use App\Http\Controllers\SearchController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AddressController;

// =========================
// Webhooks / Payments
// =========================
use App\Http\Controllers\Webhook\RazorpayWebhookController;

// =========================
// Merchant / Rider / Admin
// =========================
use App\Http\Controllers\Merchant\DashboardController as MerchantDashboard;
use App\Http\Controllers\Merchant\MenuItemController as MerchantMenuItemController;
use App\Http\Controllers\Merchant\OrderController as MerchantOrderController;
use App\Http\Controllers\Rider\DashboardController as RiderDashboard;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\RestaurantController as AdminRestaurantController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;

// =====================================================
// Public Routes
// =====================================================

// Global Home Page
Route::get('/', [HomeController::class, 'home'])->name('home');

// City-wise Home Page (if you use city context via query or session)
Route::get('/welcome', [HomeController::class, 'index'])->name('welcome');

// SEO/static pages (optional)
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact', [HomeController::class, 'submitContactForm'])->name('contact.submit');

// Restaurant discovery (public)
Route::get('/restaurants',             [RestaurantController::class, 'index'])->name('restaurants.index');
Route::get('/restaurants/{slug}',      [RestaurantController::class, 'show'])->name('restaurants.show');

// Menu browsing (public)
Route::get('/restaurants/{slug}/menu', [MenuController::class, 'showForRestaurant'])->name('menu.restaurant');

// Search (public)
Route::get('/search', [SearchController::class, 'index'])->name('search');

// =====================================================
// Auth: Login / Register / Logout (YOUR GIVEN CODE)
// =====================================================
Route::controller(AuthController::class)->group(function () {
    // Login
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'login');

    // Register
    Route::get('/register', 'showRegisterForm')->name('register');
    Route::post('/register', 'register')->name('register.post');

    // Logout
    Route::post('/logout', 'logout')->name('logout');

    // Common Dashboard landing (can redirect role-wise inside)
    Route::get('/dashboard-all', 'dashboardAll')->name('dashboard.all');
});

// =====================================================
// Cart (session-based; add/remove public, but checkout requires auth)
// =====================================================
Route::prefix('cart')->group(function () {
    Route::get('/',        [CartController::class, 'show'])->name('cart.show');
    Route::post('/add',    [CartController::class, 'add'])->name('cart.add');
    Route::post('/update', [CartController::class, 'update'])->name('cart.update');
    Route::post('/remove', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/clear',  [CartController::class, 'clear'])->name('cart.clear');
});

// =====================================================
// Authenticated: Customer Area
// =====================================================
Route::middleware(['auth'])->group(function () {

    // Customer dashboard (role check optional if same route reused)
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');

    // Profile
    Route::prefix('profile')->group(function () {
        Route::get('/',         [ProfileController::class, 'index'])->name('profile');
        Route::get('/edit',     [ProfileController::class, 'edit'])->name('profile.edit');
        Route::post('/update',  [ProfileController::class, 'update'])->name('profile.update');
        Route::post('/avatar',  [ProfileController::class, 'updateAvatar'])->name('profile.avatar.update');
    });

    // Addresses
    Route::resource('addresses', AddressController::class)->except(['show']);

    // Checkout + Payment
    Route::prefix('checkout')->group(function () {
        Route::get('/',                 [CheckoutController::class, 'index'])->name('checkout.index');
        Route::post('/apply-coupon',    [CheckoutController::class, 'applyCoupon'])->name('checkout.coupon');
        Route::post('/place-order',     [CheckoutController::class, 'placeOrder'])->name('checkout.place');
        Route::get('/success',          [CheckoutController::class, 'success'])->name('checkout.success');
        Route::get('/failed',           [CheckoutController::class, 'failed'])->name('checkout.failed');

        // Razorpay capture/verify (client return)
        Route::post('/razorpay/verify', [CheckoutController::class, 'verifyRazorpay'])->name('checkout.razorpay.verify');
    });

    // Orders (customer)
    Route::prefix('orders')->group(function () {
        Route::get('/',                [OrderController::class, 'index'])->name('orders.index');
        Route::get('/{order}',         [OrderController::class, 'show'])->name('orders.show');
        Route::post('/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
        Route::get('/{order}/track',   [OrderController::class, 'track'])->name('orders.track');
        // Reorder (adds to cart)
        Route::post('/{order}/reorder',[OrderController::class, 'reorder'])->name('orders.reorder');
    });
});

// =====================================================
// Webhooks (Payment gateways etc.) — CSRF exempt via middleware group in Kernel
// =====================================================
// Route::prefix('webhooks')->group(function () {
//     Route::post('/razorpay', [RazorpayWebhookController::class, 'handle'])->name('webhooks.razorpay');
// });

// =====================================================
// Merchant (Restaurant Owner) Area
// =====================================================
Route::middleware(['auth', 'can:merchant'])   // or ->middleware(['auth','role:Merchant'])
    ->prefix('merchant')
    ->as('merchant.')
    ->group(function () {

        Route::get('/', [MerchantDashboard::class, 'index'])->name('dashboard');

        // Menu items CRUD
        Route::resource('menu-items', MerchantMenuItemController::class);

        // Orders management (incoming orders, status updates)
        Route::get('/orders',                   [MerchantOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}',           [MerchantOrderController::class, 'show'])->name('orders.show');
        Route::post('/orders/{order}/accept',   [MerchantOrderController::class, 'accept'])->name('orders.accept');
        Route::post('/orders/{order}/reject',   [MerchantOrderController::class, 'reject'])->name('orders.reject');
        Route::post('/orders/{order}/ready',    [MerchantOrderController::class, 'markReady'])->name('orders.ready');
        Route::post('/orders/{order}/handover', [MerchantOrderController::class, 'handover'])->name('orders.handover');
    });

// =====================================================
// Rider Area
// =====================================================
Route::middleware(['auth', 'can:rider']) // or role:Rider
    ->prefix('rider')
    ->as('rider.')
    ->group(function () {
        Route::get('/', [RiderDashboard::class, 'index'])->name('dashboard');

        // Pickup/Delivery flow
        Route::get('/jobs',                   [RiderDashboard::class, 'jobs'])->name('jobs');
        Route::post('/jobs/{order}/accept',   [RiderDashboard::class, 'accept'])->name('jobs.accept');
        Route::post('/jobs/{order}/picked',   [RiderDashboard::class, 'picked'])->name('jobs.picked');
        Route::post('/jobs/{order}/delivered',[RiderDashboard::class, 'delivered'])->name('jobs.delivered');
        Route::post('/jobs/{order}/failed',   [RiderDashboard::class, 'failed'])->name('jobs.failed');
    });

// =====================================================
// Admin Area
// =====================================================
Route::middleware(['auth', 'can:access-admin']) // or role:Admin
    ->prefix('admin')
    ->as('admin.')
    ->group(function () {

        Route::get('/', [AdminDashboard::class, 'index'])->name('dashboard');

        Route::resource('restaurants', AdminRestaurantController::class);
        Route::resource('users',       AdminUserController::class)->only(['index','show','edit','update','destroy']);

        // Admin order overview
        Route::get('/orders',                 [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}',         [AdminOrderController::class, 'show'])->name('orders.show');
        Route::post('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');
    });

// =====================================================
// Fallback / 404
// =====================================================
Route::fallback(function () {
    return app()->make(HomeController::class)->notFound();
});


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



