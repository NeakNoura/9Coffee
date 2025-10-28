<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Products\ProductsController;
use App\Http\Controllers\Admins\AdminsController;
use App\Http\Controllers\Users\UsersController;
use App\Http\Controllers\HomeController;
use App\Exports\SalesReportExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Admins\AdminPaymentController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 🟢 Default Laravel auth
Auth::routes();

// 🟢 Public user pages
Route::get('/', function () {return redirect()->route('view.login'); // your admin login route
});

Route::get('products/contact', [ProductsController::class, 'contact'])->name('product.contact');
Route::get('products/service', [ProductsController::class, 'service'])->name('product.service');
Route::get('products/menu', [ProductsController::class, 'menu'])->name('product.menu');
Route::get('products/about', [ProductsController::class, 'about'])->name('product.about');
Route::get('products/product-single/{id}', [ProductsController::class, 'singleProduct'])->name('product.single');
Route::get('/welcome', [HomeController::class, 'welcome'])->name('welcome');

// 🛒 Cart & checkout
// Add product to cart
Route::post('products/product-single/{id}', [ProductsController::class, 'addCart'])->name('add.cart');

// View cart
Route::get('products/cart', [ProductsController::class, 'cart'])->name('cart')->middleware('auth:web');

// Delete product from cart
Route::get('products/cart-delete/{id}', [ProductsController::class, 'deleteProductCart'])->name('cart.product.delete');

// Prepare checkout (optional)
Route::post('products/prepare-checkout', [ProductsController::class, 'prepareCheckout'])->name('prepare.checkout');

// Show checkout form

Route::get('products/checkout', [ProductsController::class, 'checkout'])->name('checkout')->middleware('auth:web');
Route::post('products/store-checkout', [ProductsController::class, 'storeCheckout'])->name('store.checkout');

// Process checkout form
Route::post('products/checkout', [ProductsController::class, 'proccessCheckout'])->name('proccess.checkout')->middleware('auth:web');

// Success page
Route::get('products/success', [ProductsController::class, 'success'])->name('products.success');

// 💳 Payment routes
Route::get('products/paypal', [ProductsController::class, 'paywithpaypal'])->name('products.paypal')->middleware('check.for.price');
Route::post('products/paypal', [ProductsController::class, 'paywithpaypal'])->name('products.paypal')->middleware('check.for.price');
// web.php
Route::get('admin/staff-qr-pay/{order_ref}', [AdminsController::class, 'qrPay'])->name('staff.qr-pay');

  Route::middleware(['auth:admin'])->group(function () {
    Route::get('/admin/paypal', [AdminsController::class, 'paywithPaypal'])->name('admin.paypal');
    Route::get('/admin/paypal-success', [AdminsController::class, 'paypalSuccess'])->name('admin.paypal.success');
});


Route::get('products/success', [ProductsController::class, 'success'])
    ->name('products.success')
    ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
        Route::get('/admin/qr-payment', [AdminsController::class, 'showQrPayment'])->name('admin.qr.payment');

// Receipt view
Route::get('receipt/{id}', [ProductsController::class, 'showReceipt'])
    ->name('receipt.show');

// 📅 Booking
Route::post('products/booking', [ProductsController::class, 'BookingTables'])->name('booking.tables');

// 👤 User actions
Route::get('users/menu', [UsersController::class, 'displayOrders'])->name('users.orders')->middleware('auth:web');
Route::get('users/bookings', [UsersController::class, 'displayBookings'])->name('users.bookings')->middleware('auth:web');
Route::get('users/write-reviews', [UsersController::class, 'writeReviews'])->name('write.reviews')->middleware('auth:web');
Route::post('users/write-reviews', [UsersController::class, 'proccesswriteReviews'])->name('proccess.write.reviews')->middleware('auth:web');
// web.php
Route::get('/staff-qr-pay/{order_ref}', [AdminsController::class, 'qrPay'])->name('staff.qr-pay');

// ---------------------------
// Admin Routes
// ---------------------------


Route::middleware('guest:admin')->group(function () {
    Route::post('/login-user', [ProductsController::class, 'loginUser'])->name('login.user');

    Route::get('admin/login', [AdminsController::class, 'viewLogin'])->name('view.login');
    Route::post('admin/login', [AdminsController::class, 'checkLogin'])->name('check.login');
});


// 🔒 Protected admin routes
Route::middleware(['auth:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminsController::class, 'index'])->name('admins.dashboard');
    Route::post('/logout', [AdminsController::class, 'logout'])->name('admin.logout');
    Route::get('/all-users', [AdminsController::class, 'DisplayAllUsers'])->name('all.users');
    Route::get('/all-admins', [AdminsController::class, 'DisplayAllAdmins'])->name('all.admins');
    Route::get('/create-admins', [AdminsController::class, 'createAdmins'])->name('create.admins');
    Route::post('/create-admins', [AdminsController::class, 'storeAdmins'])->name('store.admins');
    Route::get('/edit-admin/{id}', [AdminsController::class, 'editAdmin'])->name('edit.admin');
    Route::post('/update-admin/{id}', [AdminsController::class, 'updateAdmin'])->name('update.admins');
    Route::delete('/delete-admin/{id}', [AdminsController::class, 'deleteAdmin'])->name('delete.admin');
    Route::get('/admin/stock', [AdminsController::class, 'viewStock'])->name('admin.stock');
    Route::post('/admin/stock/{id}', [AdminsController::class, 'updateStock'])->name('admin.stock.update');
    // Admin Reports & Analytics
    Route::get('admin/reports/sales', [AdminsController::class, 'salesReport'])->name('admin.sales.report');
    Route::get('admin/low-stock', [AdminsController::class, 'lowStock'])->name('admin.low.stock');
    Route::get('admin/expenses', [AdminsController::class, 'viewExpenses'])->name('admin.expenses');
    Route::post('admin/expenses', [AdminsController::class, 'storeExpense'])->name('admin.expenses.store');
    Route::get('admin/logs', [AdminsController::class, 'adminLogs'])->name('admin.logs');
    Route::get('admin/reports/sales/download', function () {return Excel::download(new SalesReportExport, 'sales_report.xlsx');})->name('admin.sales.report.download');


    // Orders management
    Route::get('/all-orders', [AdminsController::class, 'DisplayAllOrders'])->name('all.orders');
    Route::get('/edit-orders/{id}', [AdminsController::class, 'EditOrders'])->name('edit.orders');
    Route::post('/edit-orders/{id}', [AdminsController::class, 'UpdateOrders'])->name('update.orders');
    Route::delete('/delete-orders/{id}', [AdminsController::class, 'DeleteOrders'])->name('delete.orders');
    Route::delete('/delete-all-orders', [AdminsController::class, 'DeleteAllOrders'])->name('delete.all.orders');


    // Products management
    Route::get('/all-products', [AdminsController::class, 'DisplayProducts'])->name('all.products');
    Route::get('/create-products', [AdminsController::class, 'CreateProducts'])->name('create.products');
Route::post('/edit-products/{id}', [AdminsController::class, 'AjaxUpdateProducts'])->name('ajax.edit.products');
    Route::post('/update-products/{id}', [AdminsController::class, 'UpdateProducts'])->name('update.products');
    Route::post('/store-products', [AdminsController::class, 'StoreProducts'])->name('store.products');
Route::delete('/delete-products/{id}', [AdminsController::class, 'DeleteProducts'])->name('ajax.delete.products');

    // Bookings management
    Route::get('/all-bookings', [AdminsController::class, 'DisplayBookings'])->name('all.bookings');
    Route::get('/edit-bookings/{id}', [AdminsController::class, 'EditBookings'])->name('edit.bookings');
    Route::post('/update-bookings/{id}', [AdminsController::class, 'UpdateBookings'])->name('update.bookings');
    Route::delete('/delete-bookings/{id}', [AdminsController::class, 'DeleteBookings'])->name('delete.bookings');
    Route::get('/create-bookings', [AdminsController::class, 'CreateBookings'])->name('create.bookings');
    Route::post('/store-bookings', [AdminsController::class, 'StoreBookings'])->name('store.bookings');

    // Payments
    Route::get('/paypal', [AdminsController::class, 'paywithPaypal'])->name('admin.paypal');
    Route::get('/paypal-success', [AdminsController::class, 'paypalSuccess'])->name('admin.paypal.success');
    Route::get('/qr-payment', [AdminsController::class, 'showQrPayment'])->name('admin.qr.payment');

    // Other tools
    Route::get('/help', [AdminsController::class, 'Help'])->name('admins.help');
    Route::get('/staff-sell', [AdminsController::class, 'StaffSellForm'])->name('staff.sell.form');
    Route::post('/staff-sell', [AdminsController::class, 'StaffSellProduct'])->name('staff.sell');
    Route::post('/staff-checkout', [AdminsController::class, 'staffCheckout'])->name('staff.checkout');
    Route::get('/staff-checkout', [AdminsController::class, 'staffCheckout'])->name('staff.checkout');
});

    Route::post('/store-bookings', [AdminsController::class, 'StoreBookings'])->name('store.bookings');
