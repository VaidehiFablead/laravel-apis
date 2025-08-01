<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StripePaymentController;
use App\Http\Controllers\ViewCustomerController;
use App\Models\Category;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
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



Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login']);


// profile
Route::get('/profile', function () {
    return view('profile');
});
Route::post('/profile', [ProfileController::class, 'showprofile'])->name('profile');


// update Profile
Route::post('/profile', [ProfileController::class, 'updateProfile'])->name('profile.update');


// tables
Route::get('/tables', [ProductController::class, 'showTable'])->name('tables');

// Show form 
Route::get('/add-product', [ProductController::class, 'create'])->name('add-product');

// Handle form submit 
Route::post('/add-product', [ProductController::class, 'store'])->name('add-product.store');
Route::get('/edit-product/{id}', [ProductController::class, 'edit']);
Route::delete('/delete-product/{id}', [ProductController::class, 'destroy']);


// change password
Route::get('/change-password', [ProfileController::class, 'showChangePasswordForm'])->name('password.form');
Route::post('/change-password', [ProfileController::class, 'changePassword'])->name('password.change');


// Show the form (GET)
Route::get('/addproductform', [ProductController::class, 'create'])->name('product.create');

// Handle the form submission (POST)
Route::post('/addproductform', [ProductController::class, 'store'])->name('product.store');
Route::get('/addproductform', [ProductController::class, 'create'])->name('addProductForm');


// addcategory

Route::get('/addcategoryForm', [CategoryController::class, 'create'])->name('addcategoryForm');
Route::post('/addcategoryForm', [CategoryController::class, 'store'])->name('addcategory.store');

// viewcategory

Route::get('/viewcategory', [CategoryController::class, 'index'])->name('viewcategory');
Route::post('/category/store', [CategoryController::class, 'store'])->name('category.store');
Route::get('/category/edit/{id}', [CategoryController::class, 'edit'])->name('category.edit');
Route::post('/category/update/{id}', [CategoryController::class, 'update'])->name('category.update');
Route::post('/category/delete/{id}', [CategoryController::class, 'destroy'])->name('category.delete');


// Logout
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');


// add new product
Route::post('/product/store', [ProductController::class, 'store'])->name('product.store');
Route::post('/product/delete/{id}', [ProductController::class, 'deleteProduct'])->name('product.delete');
Route::get('/edit-product/{id}', [ProductController::class, 'edit'])->name('product.edit');
Route::post('/product/update/{id}', [ProductController::class, 'update'])->name('product.update');
Route::get('/product/view/{id}', [ProductController::class, 'view'])->name('product.view');

// view product
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/product', [ProductController::class, 'index'])->name('product');


Route::get('/addcustomer', [CustomerController::class, 'index'])->name('addcustomer');
Route::post('/addcustomer', [CustomerController::class, 'store'])->name('addcustomer.store');


// view Customer
Route::get('/viewcustomer', [ViewCustomerController::class, 'show'])->name('viewcustomer');
Route::get('/viewcustomer', [ViewCustomerController::class, 'showTable'])->name('viewcustomer');
Route::post('/customer/delete/{id}', [ViewCustomerController::class, 'deleteCustomer'])->name('customer.delete');
Route::get('/customer/edit/{id}', [ViewCustomerController::class, 'edit'])->name('customer.edit');
Route::post('/customer/update/{id}', [ViewCustomerController::class, 'update'])->name('customer.update');



// orders
Route::get('/orders', [OrdersController::class, 'showOrder'])->name('orders');
Route::post('/place-order', [OrdersController::class, 'store'])->name('orders.store');


Route::get('/viewOrder', [OrdersController::class, 'viewOrder'])->name('viewOrder');

// stripe payment route
Route::get('/stripe-checkout/{order_id}', [OrdersController::class, 'stripeCheckout'])->name('stripe.checkout');
Route::post('/stripe/payment-intent', [StripePaymentController::class, 'stripePaymentIntent'])->name('stripe.intent');


// google Login
Route::get('/auth/google', [GoogleController::class, 'redirectGoogle'])->name('google.login');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

// success page
Route::get('/thank-you/{order_id}', function ($order_id) {
    $order = \App\Models\Orders::findOrFail($order_id);
    return view('thank-you', compact('order'));
});

Route::post('/payment-success', [StripePaymentController::class, 'paymentSuccess']);

// middleware
Route::middleware(['checkLogin'])->group(function () {
    Route::get('/tables', [ProductController::class, 'showTable'])->name('tables');
    Route::get('/orders', [OrdersController::class, 'showOrder'])->name('orders');
    Route::get('/profile', function () {return view('profile');});
    Route::get('/add-product', [ProductController::class, 'create'])->name('add-product');
    Route::get('/change-password', [ProfileController::class, 'showChangePasswordForm'])->name('password.form');
    Route::get('/addproductform', [ProductController::class, 'create'])->name('product.create');
});

