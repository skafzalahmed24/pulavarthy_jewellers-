<?php

use Illuminate\Support\Facades\Route;

/*
 |--------------------------------------------------------------------------
 | Web Routes
 |--------------------------------------------------------------------------
 |
 | Here is where you can register web routes for your application. These
 | routes are loaded by the RouteServiceProvider and all of them will
 | be assigned to the "web" middleware group. Make something great!
 |
 */

Route::get('/', function () {
    $prices = \App\Models\MetalPrice::all()->keyBy('metal_name');
    $terms = \App\Models\Term::all();
    return view('home', compact('prices', 'terms'));
})->name('home');

Route::get('/purchase-plan', function () {
    $plans = \App\Models\InvestmentPlan::all();
    $prices = \App\Models\MetalPrice::all()->keyBy('metal_name');
    return view('purchase-plan', compact('plans', 'prices'));
})->name('purchase-plan');

Route::post('/register', [App\Http\Controllers\RegistrationController::class , 'register'])->name('register');
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class , 'login'])->name('customer.login');
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class , 'logout'])->name('customer.logout');

Route::middleware(['auth'])->group(function () {
    Route::post('/customer/payment/create-order', [App\Http\Controllers\PaymentController::class, 'createOrder'])->name('payment.create_order');
    Route::post('/customer/payment/verify', [App\Http\Controllers\PaymentController::class, 'verifyPayment'])->name('payment.verify');
    Route::post('/customer/request-grace', [App\Http\Controllers\PaymentController::class, 'requestGrace'])->name('customer.request_grace');
});

// Admin Routes
Route::prefix('admin')->group(function () {
    Route::get('/', [App\Http\Controllers\AdminController::class , 'showLoginForm'])->name('admin.login');
    Route::post('/login', [App\Http\Controllers\AdminController::class , 'login'])->name('admin.login.submit');

    Route::middleware(['auth', 'admin'])->group(function () {
            Route::get('/dashboard', [App\Http\Controllers\AdminController::class , 'dashboard'])->name('admin.dashboard');

            // Content Management
            Route::prefix('content')->group(function () {
                    Route::get('/prices', [App\Http\Controllers\AdminController::class , 'pricesIndex'])->name('admin.prices.index');
                    Route::post('/prices', [App\Http\Controllers\AdminController::class , 'pricesUpdate'])->name('admin.prices.update');

                    Route::get('/terms', [App\Http\Controllers\AdminController::class , 'termsIndex'])->name('admin.terms.index');
                    Route::post('/terms', [App\Http\Controllers\AdminController::class , 'termsUpdate'])->name('admin.terms.update');

                    Route::resource('plans', App\Http\Controllers\Admin\PlanController::class)->names([
                        'index' => 'admin.plans.index',
                        'create' => 'admin.plans.create',
                        'store' => 'admin.plans.store',
                        'edit' => 'admin.plans.edit',
                        'update' => 'admin.plans.update',
                        'destroy' => 'admin.plans.destroy',
                    ]);
                }
                );

                Route::get('/approvals', [App\Http\Controllers\CustomerController::class , 'approvals'])->name('admin.approvals');
                
                Route::get('/payments/grace-requests', [App\Http\Controllers\AdminController::class, 'graceRequests'])->name('admin.payments.grace_requests');
                Route::post('/payments/{id}/approve-grace', [App\Http\Controllers\AdminController::class, 'approveGrace'])->name('admin.payments.approve_grace');
                Route::post('/payments/{id}/reject-grace', [App\Http\Controllers\AdminController::class, 'rejectGrace'])->name('admin.payments.reject_grace');

                // Customer Management
                Route::get('/customers/{id}/payments', [App\Http\Controllers\CustomerController::class, 'paymentTerms'])->name('admin.customers.payment_terms');
                Route::post('/payments/{id}/update', [App\Http\Controllers\CustomerController::class, 'updatePayment'])->name('admin.payments.update');
                
                Route::resource('customers', App\Http\Controllers\CustomerController::class)->names([
                    'index' => 'admin.customers.index',
                    'create' => 'admin.customers.create',
                    'store' => 'admin.customers.store',
                    'edit' => 'admin.customers.edit',
                    'update' => 'admin.customers.update',
                    'destroy' => 'admin.customers.destroy',
                ]);

                Route::get('/analytics/status', [App\Http\Controllers\Admin\AdminAnalyticsController::class , 'getStatusData'])->name('admin.analytics.status');
                Route::get('/analytics/growth', [App\Http\Controllers\Admin\AdminAnalyticsController::class , 'getGrowthData'])->name('admin.analytics.growth');
                Route::get('/analytics/payment', [App\Http\Controllers\Admin\AdminAnalyticsController::class , 'getPaymentData'])->name('admin.analytics.payment');

                Route::post('/logout', [App\Http\Controllers\AdminController::class , 'logout'])->name('admin.logout');
            }
            );
        });