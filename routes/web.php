<?php

use App\Http\Controllers\Auth\SocialLoginController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Front\Auth\TwoFactorAuthenticationController;
use App\Http\Controllers\Front\CartController;
use App\Http\Controllers\Front\CheckOutController;
use App\Http\Controllers\Front\CurrencyConverterController;
use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\Front\ProductsController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Laravel\Socialite\Facades\Socialite;


Route::group([
    'prefix' => LaravelLocalization::setLocale(),
],function (){
    Route::get('/', [HomeController::class, 'index'])->name('home');

    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('products', [ProductsController::class, 'index'])->name('products.index');
    Route::get('products/{product:slug}', [ProductsController::class, 'show'])->name('products.show');

    Route::resource('cart', CartController::class);

    Route::get('checkout',[CheckOutController::class , 'create'])->name('checkout');
    Route::post('checkout',[CheckOutController::class , 'store']);

    Route::get('auth/user/2fa',[TwoFactorAuthenticationController::class , 'index'])->name('front.2fa');

    Route::get('currency',[CurrencyConverterController::class , 'store'])->name('currency.store');

    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });
});


Route::get('auth/{provider}/redirect', [SocialLoginController::class, 'redirect'])->name('auth.social.redirect');

Route::get('auth/{provider}/callback', [SocialLoginController::class, 'handleCallback'])->name('auth.social.callback');



//require __DIR__.'/auth.php';
require __DIR__.'/dashboard.php';

