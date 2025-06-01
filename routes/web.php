<?php

use App\Models\Set;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdController;
use App\Http\Controllers\SetController;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\pages\MiscError;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\FamilyController;
use App\Http\Controllers\NoticeController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ChargilyController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\dashboard\Analytics;
use App\Http\Controllers\DatatablesController;
use App\Http\Controllers\SubcategoryController;
use App\Http\Controllers\ProductImageController;
use App\Http\Controllers\ProductVideoController;
use App\Http\Controllers\DocumentationController;
use App\Http\Controllers\authentications\LoginBasic;
use App\Http\Controllers\pages\MiscUnderMaintenance;
use App\Http\Controllers\authentications\LogoutBasic;
use App\Http\Controllers\pages\AccountSettingsAccount;
use App\Http\Controllers\pages\AccountSettingsConnections;
use App\Http\Controllers\pages\AccountSettingsNotifications;
use App\Http\Controllers\authentications\ForgotPasswordBasic;

Route::get('/', [Analytics::class, 'landing'])->name('landing-page');
Route::get('/privacy_policy', [DocumentationController::class, 'privacy']);
Route::get('/delete_account', [DocumentationController::class, 'delete_account']);
Route::get('/downloadApp', function() {
    return view('redirect');
})->name('');

Route::get('/theme/{theme}', function($theme) {
    Session::put('theme', $theme);
    return redirect()->back();
});
Route::get('/lang/{lang}', function($lang) {
    Session::put('locale', $lang);
    return redirect()->back();
});

Route::prefix('chargily')->group(function () {
    Route::get('/callback', [ChargilyController::class, 'callback'])->name('chargily-callback');
    Route::get('/success', [ChargilyController::class, 'success'])->name('chargily-success');
    Route::get('/failed', [ChargilyController::class, 'failed'])->name('chargily-failed');
});

Route::prefix('auth')->group(function () {
    Route::get('/login-basic', [LoginBasic::class, 'index'])->name('login');
    Route::post('/login-action', [LoginBasic::class, 'login']);
    Route::get('/forgot-password-basic', [ForgotPasswordBasic::class, 'index'])->name('auth-reset-password-basic');
    Route::get('/logout', [LogoutBasic::class, 'logout'])->name('auth-logout');
});


Route::group(['middleware' => ['auth']], function () {

      Route::get('/dashboard', [Analytics::class, 'index'])->name('dashboard-analytics');
      Route::get('/analytics', [Analytics::class, 'analytics'])->name('analytics')->middleware('role:0,1,4,5');
      Route::get('/stats', [Analytics::class, 'stats'])->name('stats')->middleware('role:0,1,4,5');


      Route::prefix('settings')->middleware('role:0')->group(function () {
        Route::get('/', [SetController::class, 'index'])->name('settings');
        Route::post('/update', [SetController::class, 'update']);
      });

      Route::prefix('account')->group(function () {
        Route::get('/', [AdminController::class, 'account'])->name('account');
        Route::post('/update', [AdminController::class, 'update']);
        Route::post('/delete', [AdminController::class, 'delete']);
        Route::post('/password/change', [AdminController::class, 'change_password']);
      });

    Route::prefix('pages')->group(function () {
        Route::prefix('account-settings')->group(function () {
            Route::get('/account', [AccountSettingsAccount::class, 'index'])->name('pages-account-settings-account');
            Route::get('/notifications', [AccountSettingsNotifications::class, 'index'])->name('pages-account-settings-notifications');
            Route::get('/connections', [AccountSettingsConnections::class, 'index'])->name('pages-account-settings-connections');
        });
        Route::prefix('misc')->group(function () {
            Route::get('/error', [MiscError::class, 'index'])->name('pages-misc-error');
            Route::get('/under-maintenance', [MiscUnderMaintenance::class, 'index'])->name('pages-misc-under-maintenance');
        });
    });

    Route::prefix('category')->middleware('role:0,1,2')->group(function () {
        Route::get('/browse', [CategoryController::class, 'index'])->name('settings-category-browse');
        Route::get('/list', [DatatablesController::class, 'categories'])->name('category-list');
        Route::post('/create', [CategoryController::class, 'create']);
        Route::post('/update', [CategoryController::class, 'update']);
        Route::post('/delete', [CategoryController::class, 'delete']);
        Route::post('/restore', [CategoryController::class, 'restore']);
    });

    Route::prefix('subcategory')->middleware('role:0,1,2')->group(function () {
        Route::get('/browse', [SubcategoryController::class, 'index'])->name('settings-subcategory-browse');
        Route::post('/list', [DatatablesController::class, 'subcategories'])->name('subcategory-list');
        Route::post('/create', [SubcategoryController::class, 'create']);
        Route::post('/update', [SubcategoryController::class, 'update']);
        Route::post('/delete', [SubcategoryController::class, 'delete']);
        Route::post('/restore', [SubcategoryController::class, 'restore']);
    });

    Route::prefix('unit')->middleware('role:0,1,2')->group(function () {
      Route::get('/browse', [UnitController::class, 'index'])->name('settings-unit-browse');
      Route::get('/list', [DatatablesController::class, 'units'])->name('unit-list');
      Route::post('/create', [UnitController::class, 'create']);
      Route::post('/update', [UnitController::class, 'update']);
      Route::post('/delete', [UnitController::class, 'delete']);
      Route::post('/restore', [UnitController::class, 'restore']);
  });

    Route::prefix('family')->middleware('role:0,1,5')->group(function () {
        Route::get('/browse', [FamilyController::class, 'index'])->name('settings-family-browse');
        Route::get('/list', [DatatablesController::class, 'families'])->name('family-list');
        Route::post('/create', [FamilyController::class, 'create']);
        Route::post('/update', [FamilyController::class, 'update']);
        Route::post('/delete', [FamilyController::class, 'delete']);
        Route::post('/restore', [FamilyController::class, 'restore']);
    });

    Route::prefix('offer')->middleware('role:0,1,5')->group(function () {
        Route::get('/browse', [OfferController::class, 'index'])->name('settings-offer-browse');
        Route::get('/list', [DatatablesController::class, 'offers'])->name('offer-list');
        Route::post('/create', [OfferController::class, 'create']);
        Route::post('/update', [OfferController::class, 'update']);
        Route::post('/delete', [OfferController::class, 'delete']);
        Route::post('/restore', [OfferController::class, 'restore']);
    });

    Route::prefix('group')->middleware('role:0,1,5')->group(function () {
        Route::get('/browse', [GroupController::class, 'index'])->name('settings-group-browse');
        Route::get('/list', [DatatablesController::class, 'groups'])->name('group-list');
        Route::post('/create', [GroupController::class, 'create']);
        Route::post('/update', [GroupController::class, 'update']);
        Route::post('/delete', [GroupController::class, 'delete']);
        Route::post('/restore', [GroupController::class, 'restore']);
    });

    Route::prefix('section')->middleware('role:0,1,5')->group(function () {
        Route::get('/browse', [SectionController::class, 'index'])->name('settings-section-browse');
        Route::get('/list', [DatatablesController::class, 'sections'])->name('section-list');
        Route::post('/add', [SectionController::class, 'add']);
        Route::post('/remove', [SectionController::class, 'remove']);
        Route::post('/switch', [SectionController::class, 'switch']);
        Route::post('/insert', [SectionController::class, 'insert']);
        Route::post('/delete', [SectionController::class, 'delete']);
        Route::post('/restore', [SectionController::class, 'restore']);
    });

    Route::prefix('driver')->middleware('role:0,1,3')->group(function () {
        Route::get('/browse', [DriverController::class, 'index'])->name('driver-browse');
        Route::get('/list', [DatatablesController::class, 'drivers'])->name('driver-list');
        Route::post('/create', [DriverController::class, 'create']);
        Route::post('/update', [DriverController::class, 'update']);
        Route::post('/delete', [DriverController::class, 'delete']);
        Route::post('/restore', [DriverController::class, 'restore']);
    });

    Route::prefix('notice')->middleware('role:0,1,5')->group(function () {
        Route::get('/browse', [NoticeController::class, 'index'])->name('notice-browse');
        Route::get('/list', [DatatablesController::class, 'notices'])->name('notice-list');
        Route::post('/create', [NoticeController::class, 'create']);
        Route::post('/update', [NoticeController::class, 'update']);
        Route::post('/delete', [NoticeController::class, 'delete']);
        Route::post('/send', [NoticeController::class, 'send']);
    });

    Route::prefix('ad')->middleware('role:0,1,5')->group(function () {
        Route::get('/browse', [AdController::class, 'index'])->name('ad-browse');
        Route::get('/list', [DatatablesController::class, 'ads'])->name('ad-list');
        Route::post('/create', [AdController::class, 'create']);
        Route::post('/update', [AdController::class, 'update']);
        Route::post('/delete', [AdController::class, 'delete']);
        Route::post('/restore', [AdController::class, 'restore']);
    });

    Route::prefix('coupon')->middleware('role:0,1,5')->group(function () {
        Route::get('/browse', [CouponController::class, 'index'])->name('coupon-browse');
        Route::get('/list', [DatatablesController::class, 'coupons'])->name('coupon-list');
        Route::post('/create', [CouponController::class, 'create']);
        Route::post('/update', [CouponController::class, 'update']);
        Route::post('/delete', [CouponController::class, 'delete']);
        Route::post('/restore', [CouponController::class, 'restore']);
        Route::get('/generate', [CouponController::class, 'generate']);
    });

    Route::prefix('user')->middleware('role:0,1')->group(function () {
      Route::get('/browse', [UserController::class, 'index'])->name('user-browse');
      Route::get('/list', [DatatablesController::class, 'users'])->name('user-list');
      Route::post('/update', [UserController::class, 'update']);
      Route::post('/change_password', [UserController::class, 'change_password']);
      Route::post('/delete', [UserController::class, 'delete']);
      Route::post('/restore', [UserController::class, 'restore']);
  });

  Route::prefix('admin')->middleware('role:0,1')->group(function () {
      Route::get('/browse', [AdminController::class, 'index'])->name('admin-browse');
      Route::get('/list', [DatatablesController::class, 'admins'])->name('admin-list');
      Route::post('/create', [AdminController::class, 'create']);
      Route::post('/update', [AdminController::class, 'update']);
      Route::post('/delete', [AdminController::class, 'delete']);
      Route::post('/restore', [AdminController::class, 'restore']);
  });

    Route::prefix('region')->middleware('role:0')->group(function () {
        Route::get('/browse', [RegionController::class, 'index'])->name('settings-region-browse');
        Route::get('/list', [DatatablesController::class, 'regions'])->name('region-list');
        Route::post('/create', [RegionController::class, 'create']);
        Route::post('/update', [RegionController::class, 'update']);
        Route::post('/delete', [RegionController::class, 'delete']);
        Route::post('/restore', [RegionController::class, 'restore']);
    });

    Route::prefix('product')->group(function () {
      Route::middleware('role:0,1,2,5')->group(function () {
        Route::post('/create', [ProductController::class, 'create']);
        Route::post('/update', [ProductController::class, 'update']);
        Route::post('/delete', [ProductController::class, 'delete']);
        Route::post('/restore', [ProductController::class, 'restore']);
        Route::get('/{id}/images', [ProductImageController::class, 'index'])->name('product-images');
        Route::get('/{id}/videos', [ProductVideoController::class, 'index'])->name('product-videos');
        Route::get('/{id}/discounts', [DiscountController::class, 'index'])->name('product-discounts');
      });

      Route::get('/browse', [ProductController::class, 'index'])->name('product-browse');
      Route::post('/list', [DatatablesController::class, 'products'])->name('product-list');
    });

    Route::prefix('image')->middleware('role:0,1,2,5')->group(function () {
        Route::post('/list', [DatatablesController::class, 'images'])->name('image-list');
        Route::post('/add', [ProductImageController::class, 'add']);
        Route::post('/delete', [ProductImageController::class, 'delete']);
    });

    Route::prefix('video')->middleware('role:0,1,2,5')->group(function () {
        Route::post('/list', [DatatablesController::class, 'videos'])->name('video-list');
        Route::post('/add', [ProductVideoController::class, 'add']);
        Route::post('/delete', [ProductVideoController::class, 'delete']);
    });

    Route::prefix('discount')->middleware('role:0,1,2,5')->group(function () {
        Route::post('/list', [DatatablesController::class, 'discounts'])->name('discount-list');
        Route::post('/create', [DiscountController::class, 'create']);
        Route::post('/update', [DiscountController::class, 'update']);
        Route::post('/delete', [DiscountController::class, 'delete']);
        Route::post('/restore', [DiscountController::class, 'restore']);
    });

    Route::prefix('order')->middleware('role:0,1,3,4,6')->group(function () {
        Route::get('/browse', [OrderController::class, 'index'])->name('order-browse');
        Route::get('/{id}/items', [ItemController::class, 'index'])->name('order-items');
        Route::post('/list', [DatatablesController::class, 'orders'])->name('order-list');
        Route::post('/update', [OrderController::class, 'update']);
        Route::post('/delete', [OrderController::class, 'delete']);
    });

    Route::prefix('item')->middleware('role:0,1,3,4,6')->group(function () {
        Route::post('/list', [DatatablesController::class, 'items'])->name('item-list');
        Route::post('/add', [ItemController::class, 'add']);
        Route::post('/edit', [ItemController::class, 'edit']);
        Route::post('/delete', [ItemController::class, 'delete']);
        Route::post('/restore', [ItemController::class, 'restore']);
    });

    Route::prefix('invoice')->middleware('role:0,1,3,4,6')->group(function () {
        Route::post('/update', [InvoiceController::class, 'update']);
    });

    Route::prefix('documentation')->middleware('role:0,1')->group(function () {
        Route::get('/privacy_policy', [DocumentationController::class, 'index'])->name('documentation_privacy_policy');
        Route::get('/about', [DocumentationController::class, 'index'])->name('documentation_about');
        Route::post('/update', [DocumentationController::class, 'update']);
    });

    Route::get('/pusher/beams-auth', function (Request $request) {

      $userID = $request->user()->id;
      $userIDInQueryParam = $request->user_id;

      $beamsClient = new \Pusher\PushNotifications\PushNotifications(Set::pusher_credentials());

      if ($userID != $userIDInQueryParam) {
          return response('Inconsistent request', 401);
      } else {
          $beamsToken = $beamsClient->generateToken($userID);
          return response()->json($beamsToken);
      }
  });

});
