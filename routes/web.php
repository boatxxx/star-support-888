<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EmployeeController;

use App\Http\Controllers\LoginControllers;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\WorkRecordController;

use App\Http\Controllers\QuotationController;


use App\Http\Controllers\ShopVisitController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\shopVisit1;


use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ReturnController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductLoadingController;
use App\Http\Controllers\ProductReservationController;
Route::get('/product-loading', [ProductLoadingController::class, 'index'])->name('product_loading.index');
// เพิ่มเส้นทางอื่น ๆ ที่เกี่ยวข้องกับการขนสินค้า (เช่น create, store)
Route::get('/product_loadings', [ProductLoadingController::class, 'index'])->name('product_loadings.index');
Route::get('/product_loadings/search', [ProductLoadingController::class, 'search'])->name('product_loadings.search');

// เส้นทางสำหรับสินค้าขึ้นรถ
Route::get('/product-loading/create/{workRecord}', [ProductLoadingController::class, 'create'])->name('product_loading.create');
Route::post('/product-loading/store', [ProductLoadingController::class, 'store'])->name('product_loading.store');
Route::get('reservations', [ProductReservationController::class, 'index'])->name('reservations.index');

// เส้นทางสำหรับจองสินค้า
Route::get('product_reservation/create/{id}', [ProductReservationController::class, 'create'])->name('product_reservation.create');
Route::post('/product-reservation/store', [ProductReservationController::class, 'store'])->name('product_reservation.store');

Route::resource('returns', ReturnController::class);

Route::get('reservations/create/{shop_id}', [ReservationController::class, 'create'])->name('reservations.create');
Route::post('reservations/store/{shop_id}', [ReservationController::class, 'store'])->name('reservations.store');

Route::resource('shops', ShopController::class);
Route::get('/shops', [ShopController::class, 'index'])->name('shops.index');
// web.php
Route::get('/shops/{shop}/edit', [ShopController::class, 'edit'])->name('shops.edit');
Route::put('/shops/{shop}', [ShopController::class, 'update'])->name('shops.update');

Route::get('users/create', [UserController::class, 'create'])->name('users.create');
Route::post('users', [UserController::class, 'store'])->name('users.store');
Route::get('work_records/review', [WorkRecordController::class, 'review'])->name('work_records.review');
Route::resource('shop_visits', ShopVisitController::class);
Route::get('/customer-visits', [ShopVisitController::class, 'index2'])->name('customer_visits.index');
Route::get('/shop_visits/create', [ShopVisitController::class, 'create1'])->name('shop_visits.create1');
Route::get('/shop_visits/create1', [ShopVisitController::class, 'create'])->name('shop_visits.create');
Route::get('/shopVisit1/createuser', [shopVisit1::class, 'createuser'])->name('shopVisit1.createuser');
Route::get('shop-visits/111', [ShopVisitController::class, 'shopVisits333'])->name('shopVisits333');
Route::get('sales/by-shop', [SalesController::class, 'salesByShop'])->name('sales.by_shop');
Route::get('sales/by-employee', [SalesController::class, 'salesByEmployee'])->name('sales.by_employee');
Route::post('/shop_visits/store', [ShopVisitController::class, 'store1'])->name('shop_visits.store1');
Route::get('/', function () {
    return view('login');
});
Route::post('/sales/store', [SalesController::class, 'store'])->name('sales.store');
Route::get('/sales/index', [SalesController::class, 'index'])->name('sales.index');

// เส้นทางสำหรับฟอร์มเข้าสู่ระบบ (GET)
// แสดงฟอร์มเข้าสู่ระบบ (GET)
Route::get('/login', [App\Http\Controllers\LoginControllers::class, 'show'])->name('login');

// ส่งข้อมูลเข้าสู่ระบบ (POST)
Route::post('/login', [App\Http\Controllers\LoginControllers::class, 'store'])->name('login.store');

// แสดงหน้าใบเสนอราคาจากออเดอร์ที่เลือก (GET)
Route::get('/quotation/{id}', [App\Http\Controllers\QuotationController::class, 'show'])->name('quotation.show');

// ส่งข้อมูลที่ผู้ใช้กรอก (POST)
Route::post('/quotation/{id}', [App\Http\Controllers\QuotationController::class, 'store'])->name('quotation.store');

// เส้นทางสำหรับการเข้าสู่ระบบ (POST)

Route::get('/admin', [HomeController::class, 'index'])->name('admin');

Route::get('/user', [HomeController::class, 'index1'])->name('user');
Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');


// ตัวอย่าง role_id สำหรับผู้ใช้
Route::get('/login1', function () {
    return view('login');
})->name('login1');

Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');
Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
Route::put('users/{id}', [UserController::class, 'update'])->name('users.update');
Route::resource('work_records', WorkRecordController::class);
Route::get('work_records/{id}', [WorkRecordController::class, 'show'])->name('work_records.show');

Route::resource('products', ProductController::class);

// web.php
Route::get('products/{id}', [ProductController::class, 'show'])->name('products.show');

Route::resource('warehouses', WarehouseController::class);
// Route to show the form for creating a new promotion
Route::get('/promotions/create', [PromotionController::class, 'create'])->name('promotions.create');

// Route to handle the form submission
Route::post('/promotions', [PromotionController::class, 'store'])->name('promotions.store');
Route::resource('promotions', PromotionController::class);
Route::post('/logout', [LoginControllers::class, 'destroy'])->name('logout');
