<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProfileJSController;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::prefix('admin')->middleware(['auth', 'is_admin'])->group(function () {
    Route::get('/', "App\Http\Controllers\AdminController@admin")->name('admin');
    Route::get('/users', [AdminController::class, 'users'])->name('adminUsers');
    Route::get('/products', [AdminController::class, 'products'])->name('adminProducts');
    //Route::post('/products', [AdminController::class, 'exportProducts'])->name('exportProducts');
    
    Route::get('/categories', [AdminController::class, 'categories'])->name('adminCategories');
    Route::get('/enterAsUser/{id}', [AdminController::class, 'enterAsUser'])->name('enterAsUser');
    Route::prefix('roles')->group(function() {
        Route::post('/add', [AdminController::class, 'addRole'])->name('addRole');
        Route::post('/addRoleToUser', [AdminController::class, 'addRoleToUser'])->name('addRoleToUser');
    });
});

Route::prefix('cart')->group(function () {
    Route::get('/', [CartController::class, 'cart'])->name('cart');
    Route::post('/removeFromCart', [CartController::class, 'removeFromCart'])->name('removeFromCart');
    Route::post('/addToCart', [CartController::class, 'addToCart'])->name('addToCart');
    Route::post('/createOrder', [CartController::class, 'createOrder'])->name('createOrder');
    Route::get('/addInOrder', [CartController::class,'addOrderToCart']);  
});

Route::get('/category/{category}', [HomeController::class, 'category'])->name('category');
Route::post('/admin/categories', [AdminController::class, 'saveCategory'])->name('saveCategory');

Route::prefix('profile')->group(function () {
    Route::get('/{user}', [ProfileController::class, 'profile'])->name('profile');
    Route::post('/save', [ProfileController::class, 'save'])->name('saveProfile');
    
});

Route::prefix('profilejs')->group(function () {
    Route::get('/{user}', [ProfileJSController::class, 'profileJS'])->name('profilevjs');
    Route::get('/savemainadrs', [ProfileJSController::class, 'SaveIdMainAddress'])->name('savemainadrs');
    Route::get('/saveprofilejs', [ProfileJSController::class, 'SaveJS']);
});

Route::post('/admin/products', [AdminController::class, 'listCategory'])->name('listCategory');
//Route::post('/admin/products', [AdminController::class, 'saveProduct'])->name('saveProduct');
    
Auth::routes();