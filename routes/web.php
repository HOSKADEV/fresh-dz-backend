<?php

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

$controller_path = 'App\Http\Controllers';

// Main Page Route
Route::get('/', 'App\Http\Controllers\dashboard\Analytics@index')->name('dashboard-analytics')->middleware('auth');
Route::get('/privacy_policy','App\Http\Controllers\DocumentationController@public');

Route::group(['middleware' => ['auth']], function () {
  Route::get('/version', 'App\Http\Controllers\VersionController@index')->name('version');
  Route::get('/stats', 'App\Http\Controllers\dashboard\Analytics@stats')->name('stats');
  Route::get('/category/browse', 'App\Http\Controllers\CategoryController@index')->name('settings-category-browse');
  Route::get('/subcategory/browse', 'App\Http\Controllers\SubcategoryController@index')->name('settings-subcategory-browse');
  Route::get('/family/browse', 'App\Http\Controllers\FamilyController@index')->name('settings-family-browse');
  Route::get('/offer/browse', 'App\Http\Controllers\OfferController@index')->name('settings-offer-browse');
  Route::get('/group/browse', 'App\Http\Controllers\GroupController@index')->name('settings-group-browse');
  Route::get('/product/browse', 'App\Http\Controllers\ProductController@index')->name('product-browse');
  Route::get('/product/{id}/images', 'App\Http\Controllers\ProductImageController@index')->name('product-images');
  Route::get('/product/{id}/videos', 'App\Http\Controllers\ProductVideoController@index')->name('product-videos');
  Route::get('/product/{id}/discounts', 'App\Http\Controllers\DiscountController@index')->name('product-discounts');
  Route::get('/section/browse', 'App\Http\Controllers\SectionController@index')->name('settings-section-browse');
  Route::get('/order/browse', 'App\Http\Controllers\OrderController@index')->name('order-browse');
  Route::get('/order/{id}/items', 'App\Http\Controllers\ItemController@index')->name('order-items');
  Route::get('/driver/browse', 'App\Http\Controllers\DriverController@index')->name('driver-browse');
  Route::get('/user/browse', 'App\Http\Controllers\UserController@index')->name('user-browse');
  Route::get('/notice/browse', 'App\Http\Controllers\NoticeController@index')->name('notice-browse');
  Route::get('/ad/browse', 'App\Http\Controllers\AdController@index')->name('ad-browse');
  Route::get('/coupon/browse', 'App\Http\Controllers\CouponController@index')->name('coupon-browse');
  Route::get('/category/list', 'App\Http\Controllers\DatatablesController@categories')->name('category-list');
  Route::post('/subcategory/list', 'App\Http\Controllers\DatatablesController@subcategories')->name('subcategory-list');
  Route::get('/family/list', 'App\Http\Controllers\DatatablesController@families')->name('family-list');
  Route::get('/offer/list', 'App\Http\Controllers\DatatablesController@offers')->name('offer-list');
  Route::get('/group/list', 'App\Http\Controllers\DatatablesController@groups')->name('group-list');
  Route::post('/product/list', 'App\Http\Controllers\DatatablesController@products')->name('product-list');
  Route::get('/section/list', 'App\Http\Controllers\DatatablesController@sections')->name('section-list');
  Route::post('/order/list', 'App\Http\Controllers\DatatablesController@orders')->name('order-list');
  Route::post('/item/list', 'App\Http\Controllers\DatatablesController@items')->name('item-list');
  Route::get('/driver/list', 'App\Http\Controllers\DatatablesController@drivers')->name('driver-list');
  Route::get('/user/list', 'App\Http\Controllers\DatatablesController@users')->name('user-list');
  Route::get('/notice/list', 'App\Http\Controllers\DatatablesController@notices')->name('notice-list');
  Route::get('/ad/list', 'App\Http\Controllers\DatatablesController@ads')->name('ad-list');
  Route::post('/image/list', 'App\Http\Controllers\DatatablesController@images')->name('image-list');
  Route::post('/video/list', 'App\Http\Controllers\DatatablesController@videos')->name('video-list');
  Route::get('/coupon/list', 'App\Http\Controllers\DatatablesController@coupons')->name('coupon-list');
  Route::post('/discount/list', 'App\Http\Controllers\DatatablesController@discounts')->name('discount-list');
});

Route::group(['middleware' => ['auth']], function () {
  Route::get('/logout','App\Http\Controllers\AuthController@logout');
  Route::post('/user/update','App\Http\Controllers\UserController@update');
  Route::post('/user/change_password','App\Http\Controllers\UserController@change_password');

  Route::post('/category/create','App\Http\Controllers\CategoryController@create');
  Route::post('/category/update','App\Http\Controllers\CategoryController@update');
  Route::post('/category/delete','App\Http\Controllers\CategoryController@delete');
  Route::post('/category/restore','App\Http\Controllers\CategoryController@restore');
  Route::post('/category/get','App\Http\Controllers\CategoryController@get');

  Route::post('/subcategory/create','App\Http\Controllers\SubcategoryController@create');
  Route::post('/subcategory/update','App\Http\Controllers\SubcategoryController@update');
  Route::post('/subcategory/delete','App\Http\Controllers\SubcategoryController@delete');
  Route::post('/subcategory/restore','App\Http\Controllers\SubcategoryController@restore');
  Route::post('/subcategory/get','App\Http\Controllers\SubcategoryController@get');


  Route::post('/family/create','App\Http\Controllers\FamilyController@create');
  Route::post('/family/update','App\Http\Controllers\FamilyController@update');
  Route::post('/family/delete','App\Http\Controllers\FamilyController@delete');
  Route::post('/family/restore','App\Http\Controllers\FamilyController@restore');

  Route::post('/group/create','App\Http\Controllers\GroupController@create');
  Route::post('/group/update','App\Http\Controllers\GroupController@update');
  Route::post('/group/delete','App\Http\Controllers\GroupController@delete');
  Route::post('/group/restore','App\Http\Controllers\GroupController@restore');


  Route::post('/product/create','App\Http\Controllers\ProductController@create');
  Route::post('/product/update','App\Http\Controllers\ProductController@update');
  Route::post('/product/delete','App\Http\Controllers\ProductController@delete');
  Route::post('/product/restore','App\Http\Controllers\ProductController@restore');
  Route::post('/product/get','App\Http\Controllers\ProductController@get');


  Route::post('/discount/create','App\Http\Controllers\DiscountController@create');
  Route::post('/discount/update','App\Http\Controllers\DiscountController@update');
  Route::post('/discount/delete','App\Http\Controllers\DiscountController@delete');
  Route::post('/discount/restore','App\Http\Controllers\DiscountController@restore');


  Route::post('/ad/create','App\Http\Controllers\AdController@create');
  Route::post('/ad/update','App\Http\Controllers\AdController@update');
  Route::post('/ad/delete','App\Http\Controllers\AdController@delete');
  Route::post('/ad/restore','App\Http\Controllers\AdController@restore');


  Route::post('/offer/create','App\Http\Controllers\OfferController@create');
  Route::post('/offer/update','App\Http\Controllers\OfferController@update');
  Route::post('/offer/delete','App\Http\Controllers\OfferController@delete');
  Route::post('/offer/restore','App\Http\Controllers\OfferController@restore');



  Route::post('/section/add','App\Http\Controllers\SectionController@add');
  Route::post('/section/remove','App\Http\Controllers\SectionController@remove');
  Route::post('/section/switch','App\Http\Controllers\SectionController@switch');
  Route::post('/section/insert','App\Http\Controllers\SectionController@insert');
  Route::post('/section/delete','App\Http\Controllers\SectionController@delete');
  Route::post('/section/restore','App\Http\Controllers\SectionController@restore');


  Route::post('/driver/create','App\Http\Controllers\DriverController@create');
  Route::post('/driver/update','App\Http\Controllers\DriverController@update');
  Route::post('/driver/delete','App\Http\Controllers\DriverController@delete');
  Route::post('/driver/restore','App\Http\Controllers\DriverController@restore');

  Route::post('/item/add','App\Http\Controllers\ItemController@add');
  Route::post('/item/edit','App\Http\Controllers\ItemController@edit');
  Route::post('/item/delete','App\Http\Controllers\ItemController@delete');
  Route::post('/item/restore','App\Http\Controllers\ItemController@restore');

  Route::post('/image/add','App\Http\Controllers\ProductImageController@add');
  Route::post('/image/delete','App\Http\Controllers\ProductImageController@delete');

  Route::post('/video/add','App\Http\Controllers\ProductVideoController@add');
  Route::post('/video/delete','App\Http\Controllers\ProductVideoController@delete');

  Route::post('/coupon/create','App\Http\Controllers\CouponController@create');
  Route::post('/coupon/update','App\Http\Controllers\CouponController@update');
  Route::post('/coupon/delete','App\Http\Controllers\CouponController@delete');
  Route::post('/coupon/restore','App\Http\Controllers\CouponController@restore');
  Route::get('/coupon/generate','App\Http\Controllers\CouponController@generate');

  Route::post('/order/update','App\Http\Controllers\OrderController@update');
  Route::post('/order/delete','App\Http\Controllers\OrderController@delete');

  Route::post('/invoice/update','App\Http\Controllers\InvoiceController@update');

  Route::post('/notice/create','App\Http\Controllers\NoticeController@create');
  Route::post('/notice/update','App\Http\Controllers\NoticeController@update');
  Route::post('/notice/delete','App\Http\Controllers\NoticeController@delete');

  Route::get('/documentation/privacy_policy','App\Http\Controllers\DocumentationController@index')->name('documentation_privacy_policy');
  Route::get('/documentation/about','App\Http\Controllers\DocumentationController@index')->name('documentation_about');

  Route::post('/documentation/update','App\Http\Controllers\DocumentationController@update');

  Route::post('/user/delete','App\Http\Controllers\UserController@delete');
  Route::post('/user/restore','App\Http\Controllers\UserController@restore');
  Route::post('/user/update','App\Http\Controllers\UserController@update');

  Route::post('/shipping/switch','App\Http\Controllers\SetController@shipping');

  Route::post('/version/update','App\Http\Controllers\VersionController@update');

});


// pages
Route::group(['middleware' => ['auth']], function () {
  Route::get('/pages/account-settings-account', 'App\Http\Controllers\pages\AccountSettingsAccount@index')->name('pages-account-settings-account');
  Route::get('/pages/account-settings-notifications', 'App\Http\Controllers\pages\AccountSettingsNotifications@index')->name('pages-account-settings-notifications');
  Route::get('/pages/account-settings-connections', 'App\Http\Controllers\pages\AccountSettingsConnections@index')->name('pages-account-settings-connections');
  Route::get('/pages/misc-error', 'App\Http\Controllers\pages\MiscError@index')->name('pages-misc-error');
  Route::get('/pages/misc-under-maintenance', 'App\Http\Controllers\pages\MiscUnderMaintenance@index')->name('pages-misc-under-maintenance');
});
// authentication
Route::get('/auth/login-basic', 'App\Http\Controllers\authentications\LoginBasic@index')->name('login');
//Route::get('/auth/register-basic', 'App\Http\Controllers\authentications\RegisterBasic@index')->name('auth-register-basic');
//Route::post('/auth/register-action', 'App\Http\Controllers\authentications\RegisterBasic@register');
Route::post('/auth/login-action', 'App\Http\Controllers\authentications\LoginBasic@login');
Route::get('/auth/forgot-password-basic', 'App\Http\Controllers\authentications\ForgotPasswordBasic@index')->name('auth-reset-password-basic');
Route::get('/auth/logout', 'App\Http\Controllers\authentications\LogoutBasic@logout')->name('auth-logout');

Route::group(['middleware' => ['auth']], function () {
  Route::get('/theme/{theme}', function($theme){
    Session::put('theme',$theme);
    return redirect()->back();
  });

  Route::get('/lang/{lang}', function($lang){
    Session::put('locale', $lang);
    return redirect()->back();
  });
});



