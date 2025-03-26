<?php

use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\UploadController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontendController; 
use App\Http\Controllers\LoginController;

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

//Login
Route::get('/login_admin', [LoginController::class, 'show_login'])->name('login_admin');
Route::post('/check_login', [LoginController::class, 'check_login']);
//Admin
//phải đi qua middleware auth(middle sẽ tìm trang login) mới được vào trang admin
Route::middleware('auth') -> group(function(){
    Route::prefix('admin') -> group( function(){  //prefix: thêm tiền tố admin vào url
        Route::get('/', function () {return view('admin.home');});
        Route::post('/product/add', [ProductController::class, 'insert_product']);
        Route::get('/product/create', [ProductController::class, 'add_product']);
        Route::get('/product/list', [ProductController::class, 'list_product']);
    });          
});
//product
// Route::post('/admin/product/add', [ProductController::class, 'insert_product']);
// Route::get('/admin/product/create', [ProductController::class, 'add_product']);
// Route::get('/admin/product/list', [ProductController::class, 'list_product']);
Route::get('/admin/product/delete', [ProductController::class, 'delete_product']);
Route::get('/admin/product/edit/{id}', [ProductController::class, 'edit_product']); 
Route::post('/admin/product/edit/{id}', [ProductController::class, 'update_product']); 



Route::get('/admin/order/list', [OrderController::class, 'list_order']);
Route::get('/admin/order/detail/{order_detail}', [OrderController::class, 'detail_order']);
Route::delete('/admin/order/delete/{id}', [OrderController::class, 'delete_order'])->name('order.delete');
//upload
Route::post('/upload',[UploadController::class,'uploadImage']);
Route::post('/uploads',[UploadController::class,'uploadImages']);

//frontend
Route::get('/', [FrontendController::class, 'index']);
Route::get('/product/{id}', [FrontendController::class, 'show_product']);
Route::get('/order/confirm', function () { return view('order.confirm');});
Route::get('/order/success', function () { return view('order.success');});
//cart
Route::post('/cart/add', [FrontendController::class, 'add_cart']);
Route::get('/cart', [FrontendController::class, 'show_cart']);
Route::get('/cart/delete/{id}', [FrontendController::class, 'delete_cart']);
Route::post('/cart/update', [FrontendController::class, 'update_cart']);
Route::post('/cart/send',[FrontendController::class,'send_cart']);

Route::get('/login', [LoginController::class, 'login']);
Route::post('/login_user', [LoginController::class, 'login_user'])->name('login_user');
Route::get('/register', [LoginController::class, 'register']);
Route::post('/create_user', [LoginController::class, 'create_user']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/search', [FrontendController::class, 'search'])->name('search');