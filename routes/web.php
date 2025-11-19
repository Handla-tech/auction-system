<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuctionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BidController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

// الصفحة الرئيسية
Route::get('/', [HomeController::class, 'index'])->name('home');

// ! مسارات المصادقة
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// ! مسارات تتطلب مصادقة
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // ! لوحة التحكم العامة - حسب دور المستخدم
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');

    // ! مسارات البائعين
    Route::middleware('CheckSeller')->prefix('seller')->group(function () {
        Route::get('/dashboard', [HomeController::class, 'sellerDashboard'])->name('seller.dashboard');

        // مسارات إدارة المنتجات
        Route::get('/products', [ProductController::class, 'index'])->name('seller.products.index');
        Route::get('/products/create', [ProductController::class, 'create'])->name('seller.products.create');
        Route::post('/products', [ProductController::class, 'store'])->name('seller.products.store');
        Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('seller.products.edit');
        Route::put('/products/{product}', [ProductController::class, 'update'])->name('seller.products.update');
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('seller.products.destroy');
    });

    // ! مسارات المشترين
    Route::middleware('CheckBuyer')->prefix('buyer')->group(function () {
        Route::get('/dashboard', [HomeController::class, 'buyerDashboard'])->name('buyer.dashboard');

        // 🎯 مسارات نظام المزايدات
        Route::get('/auction/{productId}', [BidController::class, 'show'])->name('buyer.auction.show');
        Route::post('/bids/{auctionId}', [BidController::class, 'store'])->name('bids.store');
        Route::get('/bids/{auctionId}/history', [BidController::class, 'getBids'])->name('bids.history');
        Route::get('/my-bids', [BidController::class, 'myBids'])->name('buyer.my-bids');

        // مسارات المنتجات للمشتري
        Route::get('/products', [ProductController::class, 'buyerProducts'])->name('buyer.products');
    });

    // ! مسارات عامة للمستخدمين المصادقين
    // 🎯 تم نقل مسارات المزادات خارج مجموعة المشترين
    Route::get('/auctions/active', [AuctionController::class, 'activeAuctions'])->name('auctions.active');
    Route::get('/auctions/ended', [AuctionController::class, 'endedAuctions'])->name('auctions.ended');
    
    // 🎯 إضافة مسار عرض المزاد المفقود
    Route::get('/auctions/{auction}', [AuctionController::class, 'show'])->name('auctions.show');

    // 🎯 مسارات المنتجات العامة (إعادة تسمية لتجنب التعارض)
    Route::get('/public/products', [ProductController::class, 'publicProducts'])->name('products.public');
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

    // ! مسارات المسؤولين
    Route::middleware('CheckAdmin')->prefix('admin')->group(function () {
        // 🎯 المسارات الأساسية
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
        Route::get('/products', [AdminController::class, 'products'])->name('admin.products');
        Route::get('/auctions', [AdminController::class, 'auctions'])->name('admin.auctions');

        // 🎯 المسارات الجديدة لنظام المسؤول المتكامل
        Route::get('/reports', [AdminController::class, 'reports'])->name('admin.reports');
        Route::get('/users/{user}', [AdminController::class, 'userDetails'])->name('admin.user-details');
        Route::get('/products/{product}', [AdminController::class, 'productDetails'])->name('admin.product-details');
        
        // 🎯 إضافة مسار تفاصيل المزاد للمسؤول
        Route::get('/auctions/{auction}', [AdminController::class, 'auctionDetails'])->name('admin.auction-details');

        // 🎯 إدارة المستخدمين
        Route::post('/users/{user}/toggle', [AdminController::class, 'toggleUserStatus'])->name('admin.users.toggle');
        Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');

        // 🎯 إدارة المنتجات
        Route::delete('/products/{product}', [AdminController::class, 'deleteProduct'])->name('admin.products.delete');

        // 🎯 إدارة المزادات
        Route::post('/auctions/{auction}/end', [AdminController::class, 'endAuction'])->name('admin.auctions.end');
        Route::post('/auctions/{auction}/reset', [AdminController::class, 'resetAuction'])->name('admin.auctions.reset');
        Route::post('/auctions/{auction}/toggle-status', [AdminController::class, 'toggleAuctionStatus'])->name('admin.auctions.toggle-status');

        // 🎯 النظام الإضافي
        Route::post('/send-bulk-notification', [AdminController::class, 'sendBulkNotification'])->name('admin.send-bulk-notification');
        Route::post('/export-report', [AdminController::class, 'exportReport'])->name('admin.export-report');
    });
});