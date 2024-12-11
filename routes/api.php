<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
$controller_path = 'App\Http\Controllers';

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/v1/version','App\Http\Controllers\VersionController@get');
Route::post('/v1/distance','App\Http\Controllers\OrderController@distance');
Route::post('/v1/register','App\Http\Controllers\AuthController@register');
Route::post('/v1/login','App\Http\Controllers\AuthController@login');
Route::post('/v1/home','App\Http\Controllers\SectionController@get');
Route::post('/v1/discounts','App\Http\Controllers\DiscountController@get');
Route::get('/v1/privacy_policy','App\Http\Controllers\DocumentationController@privacy_policy');
Route::get('/v1/about','App\Http\Controllers\DocumentationController@about');

/* Route::group(['middleware' => ['auth:sanctum']], function () {


}); */


  Route::post('/v1/category/get','App\Http\Controllers\CategoryController@get');
  Route::post('/v1/subcategory/get','App\Http\Controllers\SubcategoryController@get');
  Route::post('/v1/family/get','App\Http\Controllers\FamilyController@get');
  Route::post('/v1/product/get','App\Http\Controllers\ProductController@get');
  Route::post('/v1/ad/get','App\Http\Controllers\AdController@get');
  Route::post('/v1/offer/get','App\Http\Controllers\OfferController@get');
 // Route::post('/v1/driver/get','App\Http\Controllers\DriverController@get');

Route::group(['middleware' => ['auth:sanctum']], function () {
  Route::get('/v1/logout','App\Http\Controllers\AuthController@logout');
  Route::post('/v1/user/update','App\Http\Controllers\UserController@update');
  Route::post('/v1/user/change_password','App\Http\Controllers\UserController@change_password');
  Route::get('/v1/user/deactivate','App\Http\Controllers\UserController@deactivate');

  Route::post('/v1/category/create','App\Http\Controllers\CategoryController@create');
  Route::post('/v1/category/update','App\Http\Controllers\CategoryController@update');
  Route::post('/v1/category/delete','App\Http\Controllers\CategoryController@delete');
  Route::post('/v1/category/restore','App\Http\Controllers\CategoryController@restore');


  Route::post('/v1/subcategory/create','App\Http\Controllers\SubcategoryController@create');
  Route::post('/v1/subcategory/update','App\Http\Controllers\SubcategoryController@update');
  Route::post('/v1/subcategory/delete','App\Http\Controllers\SubcategoryController@delete');
  Route::post('/v1/subcategory/restore','App\Http\Controllers\SubcategoryController@restore');


  Route::post('/v1/family/create','App\Http\Controllers\FamilyController@create');
  Route::post('/v1/family/update','App\Http\Controllers\FamilyController@update');
  Route::post('/v1/family/delete','App\Http\Controllers\FamilyController@delete');
  Route::post('/v1/family/restore','App\Http\Controllers\FamilyController@restore');


  Route::post('/v1/product/create','App\Http\Controllers\ProductController@create');
  Route::post('/v1/product/update','App\Http\Controllers\ProductController@update');
  Route::post('/v1/product/delete','App\Http\Controllers\ProductController@delete');
  Route::post('/v1/product/restore','App\Http\Controllers\ProductController@restore');


  Route::post('/v1/discount/create','App\Http\Controllers\DiscountController@create');
  Route::post('/v1/discount/update','App\Http\Controllers\DiscountController@update');
  Route::post('/v1/discount/delete','App\Http\Controllers\DiscountController@delete');
  Route::post('/v1/discount/restore','App\Http\Controllers\DiscountController@restore');


  Route::post('/v1/ad/create','App\Http\Controllers\AdController@create');
  Route::post('/v1/ad/update','App\Http\Controllers\AdController@update');
  Route::post('/v1/ad/delete','App\Http\Controllers\AdController@delete');
  Route::post('/v1/ad/restore','App\Http\Controllers\AdController@restore');


  Route::post('/v1/offer/create','App\Http\Controllers\OfferController@create');
  Route::post('/v1/offer/update','App\Http\Controllers\OfferController@update');
  Route::post('/v1/offer/delete','App\Http\Controllers\OfferController@delete');
  Route::post('/v1/offer/restore','App\Http\Controllers\OfferController@restore');



  Route::post('/v1/section/add','App\Http\Controllers\SectionController@add');
  Route::post('/v1/section/delete','App\Http\Controllers\SectionController@delete');
  Route::post('/v1/section/switch','App\Http\Controllers\SectionController@switch');
  Route::post('/v1/section/insert','App\Http\Controllers\SectionController@insert');

  Route::post('/v1/order/create','App\Http\Controllers\OrderController@create');
  Route::post('/v1/order/accept','App\Http\Controllers\OrderController@accept');
  Route::post('/v1/order/cancel','App\Http\Controllers\OrderController@cancel');
  Route::post('/v1/order/get','App\Http\Controllers\OrderController@get');

  Route::post('/v1/driver/create','App\Http\Controllers\DriverController@create');
  Route::post('/v1/driver/update','App\Http\Controllers\DriverController@update');
  Route::post('/v1/driver/delete','App\Http\Controllers\DriverController@delete');
  Route::post('/v1/driver/restore','App\Http\Controllers\DriverController@restore');

  Route::post('/v1/cart/update','App\Http\Controllers\CartController@update');
  Route::post('/v1/cart/delete','App\Http\Controllers\CartController@delete');
  Route::get('/v1/cart/get','App\Http\Controllers\CartController@get');
  Route::get('/v1/cart/products','App\Http\Controllers\CartController@products');

});
