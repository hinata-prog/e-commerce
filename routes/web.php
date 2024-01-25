<?php

use App\Http\Controllers\admin\AdminLoginController;
use App\Http\Controllers\admin\BrandController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\DiscountCodeController;
use App\Http\Controllers\admin\HomeController;
use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\admin\OrderController;
use App\Http\Controllers\admin\BannerImageController;
use App\Http\Controllers\admin\PageController;
use App\Http\Controllers\admin\ProductController;
use App\Http\Controllers\admin\ProductImageController;
use App\Http\Controllers\admin\ProductSubCategoryController;
use App\Http\Controllers\admin\SettingController;
use App\Http\Controllers\admin\ShippingController;
use App\Http\Controllers\admin\SubCategoryController;
use App\Http\Controllers\admin\TempImagesController;
use App\Http\Controllers\admin\ContactController;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProvinceDistrictController;
use App\Http\Controllers\ShopController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

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

Route::get('/cache-clear', function () {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');
        Artisan::call('clear-compiled');
        $output = Artisan::output();
        return $output;


});
Route::get('/migrate-database', function () {
        Artisan::call('migrate');
        $output = Artisan::output();
        return $output;
});

Route::get('/seed-database', function () {
        Artisan::call('db:seed');
        $output = Artisan::output();
        return $output;
});

Route::get('/migrate-rollback', function () {
        Artisan::call('migrate:rollback --step=1');
        $output = Artisan::output();
        return $output;
});


Route::get('/',[FrontController::class, 'index'])->name('home');
Route::get('/shop/{categorySlug?}/{subCategorySlug?}',[ShopController::class, 'index'])->name('front.shop');
Route::get('/product/{slug}', [ShopController::class, 'product'])->name('front.product');
Route::get('/cart', [CartController::class, 'cart'])->name('front.cart');
Route::post('/add-to-cart', [CartController::class, 'addToCart'])->name('front.addToCart');
Route::post('/update-cart', [CartController::class, 'updateCart'])->name('front.updateCart');
Route::post('/delete-cart-item', [CartController::class, 'deleteItem'])->name('front.deleteItem.cart');

Route::get('/invoice/{orderId}', [InvoiceController::class,'show'])->name('front.invoice');
Route::post('/add-to-wish-list', [FrontController::class, 'addToWishList'])->name('front.addToWishList');
Route::get('/page/{slug}', [FrontController::class, 'page'])->name('front.page');
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('account.showForgotPasswordForm');
Route::post('/process-forgot-password', [AuthController::class, 'sendcode'])->name('account.sendcode');
Route::get('/reset-password', [AuthController::class, 'showResetPasswordForm'])->name('account.showResetPasswordForm');
Route::post('/process-reset-password', [AuthController::class, 'resetPassword'])->name('account.resetPassword');
Route::post('/save-rating/{productId}', [ShopController::class, 'saveRating'])->name('front.saveRating');
Route::get('/thanks/{orderId}', [CartController::class,'thankyou'])->name('front.thankyou');
Route::post('/get-order-summary', [CartController::class,'getOrderSummary'])->name('front.getOrderSummary');
Route::post('/apply-discount', [CartController::class,'applyDiscount'])->name('front.applyDiscount');
Route::post('/remove-discount', [CartController::class,'removeCoupon'])->name('front.removeCoupon');
Route::get('/my-orders', [AuthController::class,'orders'])->name('account.orders');
Route::get('/checkout', [CartController::class,'checkout'])->name('front.checkout');
Route::post('/process-checkout', [CartController::class,'processCheckout'])->name('front.processCheckout');
Route::get('/province-districts', [ProvinceDistrictController::class,'index'])->name('province-districts.index');

//Payment Routes
Route::post('/verify-khalti-payment', [PaymentController::class,'verifyKhaltiPayment'])->name('front.verifyKhaltiPayment');
Route::post('/verify-esewa-payment', [PaymentController::class,'verifyEsewaPayment'])->name('front.verifyEsewaPayment');


Route::get('/esewa-submit-form/{transactionId}', [PaymentController::class,'esewaPaymentForm'])->name('esewaPaymentForm');
Route::get('/esewa-success', [PaymentController::class,'esewaSuccess'])->name('esewaSuccess');
Route::get('/payment-failure', [PaymentController::class,'esewaFailure'])->name('esewaFailure');





Route::group(['prefix'=> 'account'], function () {
    Route::group(['middleware'=> 'guest'], function () {
        Route::get('/register', [AuthController::class,'register'])->name('account.register');
        Route::post('/process-register', [AuthController::class,'processRegister'])->name('account.processRegister');
        Route::get('/login', [AuthController::class,'login'])->name('account.login');
        Route::post('/process-login', [AuthController::class,'authenticate'])->name('account.authenticate');

    });

    Route::group(['middleware'=> 'auth'], function () {
        Route::get('/profile', [AuthController::class,'profile'])->name('account.profile');
        Route::post('/update-profile', [AuthController::class,'updateProfile'])->name('account.updateProfile');
        Route::post('/update-address', [AuthController::class,'updateAddress'])->name('account.updateAddress');
        Route::get('/logout', [AuthController::class,'logout'])->name('account.logout');
        Route::delete('/deleteAccount', [AuthController::class,'deleteAccount'])->name('account.deleteAccount');


        Route::get('/order-detail/{orderId}', [AuthController::class,'orderDetail'])->name('account.orderDetail');
        Route::get('/my-wishlist', [AuthController::class,'wishlist'])->name('account.wishlist');
        Route::post('/remove-product-from-wishlist', [AuthController::class,'removeProductFromWishlist'])->name('account.removeProductFromWishlist');
        Route::get('/change-password', [AuthController::class, 'showChangePasswordForm'])->name('account.showChangePasswordForm');
        Route::post('/process-change-password', [AuthController::class, 'changePassword'])->name('account.changePassword');
    });

});




Route::group(['prefix'=> 'admin'], function () {
    Route::group(['middleware'=> 'admin.guest'], function () {
        Route::get('/login', [AdminLoginController::class,'index'])->name('admin.login');
        Route::post('/authenticate', [AdminLoginController::class,'authenticate'])->name('admin.authenticate');
        Route::get('/forgot-password', [AdminLoginController::class, 'showForgotPasswordForm'])->name('admin.showForgotPasswordForm');
        Route::post('/process-forgot-password', [AdminLoginController::class, 'sendcode'])->name('admin.sendcode');
        Route::get('/reset-password', [AdminLoginController::class, 'showResetPasswordForm'])->name('admin.showResetPasswordForm');
        Route::post('/process-reset-password', [AdminLoginController::class, 'resetPassword'])->name('admin.resetPassword');
    });

    Route::group(['middleware'=> 'admin.auth'], function () {

        
        
        Route::get('/dashboard', [HomeController::class,'index'])->name('admin.dashboard');
        Route::get('/logout', [HomeController::class,'logout'])->name('admin.logout');

        //Category Routes
        Route::get('/categories', [CategoryController::class,'index'])->name('categories.index');
        Route::get('/categories/create', [CategoryController::class,'create'])->name('categories.create');
        Route::post('/categories/store', [CategoryController::class,'store'])->name('categories.store');
        Route::get('/categories/{category}/edit', [CategoryController::class,'edit'])->name('categories.edit');
        Route::put('/categories/{category}', [CategoryController::class,'update'])->name('categories.update');
        Route::delete('/categories/{category}', [CategoryController::class,'destroy'])->name('categories.destroy');

        //Temp Images Create
        Route::post('/upload-temp-image', [TempImagesController::class,'create'])->name('temp-images.create');
        Route::delete('/delete-temp-image', [TempImagesController::class,'delete'])->name('temp-images.delete');

        //Sub Category Routess
        Route::get('/sub-categories', [SubCategoryController::class,'index'])->name('sub-categories.index');
        Route::get('/sub-categories/create', [SubCategoryController::class,'create'])->name('sub-categories.create');
        Route::post('/sub-categories/store', [SubCategoryController::class,'store'])->name('sub-categories.store');
        Route::get('/sub-categories/{subCategory}/edit', [SubCategoryController::class,'edit'])->name('sub-categories.edit');
        Route::put('/sub-categories/{subCategory}', [SubCategoryController::class,'update'])->name('sub-categories.update');
        Route::delete('/sub-categories/{subCategory}', [SubCategoryController::class,'destroy'])->name('sub-categories.destroy');

        

        //Product Routes
        Route::get('/products', [ProductController::class,'index'])->name('products.index');
        Route::get('/products/create', [ProductController::class,'create'])->name('products.create');
        Route::post('/products/store', [ProductController::class,'store'])->name('products.store');
        Route::get('/products/{product}/edit', [ProductController::class,'edit'])->name('products.edit');
        Route::put('/products/{product}', [ProductController::class,'update'])->name('products.update');
        Route::delete('/products/{product}', [ProductController::class,'destroy'])->name('products.destroy');
        Route::get('/product-subcategories', [ProductSubCategoryController::class,'index'])->name('product-subcategories.index');


        //Temp Images Create
        Route::post('/product-images/update', [ProductImageController::class,'update'])->name('product-images.update');
        Route::delete('/product-images/delete', [ProductImageController::class,'delete'])->name('product-images.delete');

        Route::get('/shipping/create', [ShippingController::class,'create'])->name('shipping.create');
        Route::post('/shipping', [ShippingController::class,'store'])->name('shipping.store');
        Route::get('/shipping/{item}/edit', [ShippingController::class,'edit'])->name('shipping.edit');
        Route::put('/shipping/{item}', [ShippingController::class,'update'])->name('shipping.update');
        Route::delete('/shipping/{item}', [ShippingController::class,'destroy'])->name('shipping.destroy');

        //Coupon Discount Routes
        Route::get('/coupons', [DiscountCodeController::class,'index'])->name('coupons.index');
        Route::get('/coupons/create', [DiscountCodeController::class,'create'])->name('coupons.create');
        Route::post('/coupons/store', [DiscountCodeController::class,'store'])->name('coupons.store');
        Route::get('/coupons/{item}/edit', [DiscountCodeController::class,'edit'])->name('coupons.edit');
        Route::put('/coupons/{item}', [DiscountCodeController::class,'update'])->name('coupons.update');
        Route::delete('/coupons/{item}', [DiscountCodeController::class,'destroy'])->name('coupons.destroy');


        //Order routes
        Route::get('/orders', [OrderController::class,'index'])->name('orders.index');
        Route::get('/pending-orders', [OrderController::class,'pendingOrders'])->name('orders.pendingOrders');
        Route::get('/shipped-orders', [OrderController::class,'shippedOrders'])->name('orders.shippedOrders');
        Route::get('/delivered-orders', [OrderController::class,'deliveredOrders'])->name('orders.deliveredOrders');
        Route::get('/orders/{item}', [OrderController::class,'detail'])->name('orders.detail');
        Route::post('/orders/change-status/{item}', [OrderController::class,'changeOrderStatus'])->name('orders.changeOrderStatus');
        Route::post('/orders/send-invoice-sms/{item}', [OrderController::class,'sendInvoiceSMS'])->name('orders.sendInvoiceSMS');


        //User routes
        Route::get('/users', [UserController::class,'index'])->name('users.index');
        Route::get('/users/create', [UserController::class,'create'])->name('users.create');
        Route::post('/users/store', [UserController::class,'store'])->name('users.store');
        Route::get('/users/{id}/edit', [UserController::class,'edit'])->name('users.edit');
        Route::put('/users/{id}', [UserController::class,'update'])->name('users.update');
        Route::delete('/users/{id}', [UserController::class,'destroy'])->name('users.destroy');
        
        //Admin routes
        Route::get('/admins', [AdminController::class,'index'])->name('admins.index');
        Route::get('/admins/create', [AdminController::class,'create'])->name('admins.create');
        Route::post('/admins/store', [AdminController::class,'store'])->name('admins.store');
        Route::get('/admins/{id}/edit', [AdminController::class,'edit'])->name('admins.edit');
        Route::put('/admins/{id}', [AdminController::class,'update'])->name('admins.update');
        Route::delete('/admins/{id}', [AdminController::class,'destroy'])->name('admins.destroy');

        //Page routes
        Route::get('/pages', [PageController::class,'index'])->name('pages.index');
        Route::get('/pages/create', [PageController::class,'create'])->name('pages.create');
        Route::post('/pages/store', [PageController::class,'store'])->name('pages.store');
        Route::get('/pages/{id}/edit', [PageController::class,'edit'])->name('pages.edit');
        Route::put('/pages/{id}', [PageController::class,'update'])->name('pages.update');
        Route::delete('/pages/{id}', [PageController::class,'destroy'])->name('pages.destroy');

        //Change Password routes
        Route::get('/change-password', [SettingController::class, 'showChangePasswordForm'])->name('admin.showChangePasswordForm');
        Route::post('/process-change-password', [SettingController::class, 'changePassword'])->name('admin.changePassword');
        //profile
        Route::get('/profile', [SettingController::class,'profile'])->name('admin.profile');
        Route::post('/update-profile', [SettingController::class,'updateProfile'])->name('admin.updateProfile');
        Route::delete('/deleteAccount', [SettingController::class,'deleteAccount'])->name('admin.deleteAccount');
        

        Route::get('/ratings', [ProductController::class,'productRatings'])->name('products.productRatings');
        Route::get('/change-rating-status', [ProductController::class,'changeRatingStatus'])->name('products.changeRatingStatus');
        
        //Banner Images Routes
        Route::get('/banner-images', [BannerImageController::class,'index'])->name('banner-images.index');
        Route::get('/banner-images/create', [BannerImageController::class,'create'])->name('banner-images.create');
        Route::post('/banner-images/store', [BannerImageController::class,'store'])->name('banner-images.store');
        Route::delete('/banner-images/{ID}', [BannerImageController::class,'destroy'])->name('banner-images.destroy');
        
        //Contact Routes
        Route::get('/contact', [ContactController::class,'edit'])->name('contact.index');
        Route::put('/update-contact', [ContactController::class,'update'])->name('contact.update');



        Route::get('/getSlug', function (Request $request) {
            $slug = '';
            if(!empty($request->title)){
                $slug = Str::slug($request->title);
            }
            return response()->json([
                'status'=>true,
                'slug'=>$slug
            ]);
        })->name('getSlug');
    });



});
