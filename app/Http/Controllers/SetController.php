<?php

namespace App\Http\Controllers;

use App\Models\Set;
use App\Models\Coupon;
use App\Models\Region;
use App\Rules\ValidCoupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SetController extends Controller
{
  public function index()
  {
    $settings = Set::pluck('value', 'name')->toArray();

    return view('content.settings.index')
      ->with('settings', $settings);


  }

  public function get()
  {

    $settings = Set::pluck('value', 'name');

    return response()->json([
      'status' => 1,
      'message' => 'success',
      'data' => $settings
    ]);
  }

  public function update(Request $request)
  {

    foreach ($request->all() as $key => $value) {
      Set::updateOrInsert(['name' => $key], ['value' => $value]);
    }

    return response()->json([
      'status' => 1,
      'message' => 'success',
    ]);
  }

  public function info(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'code' => 'sometimes|nullable',
      'longitude' => 'sometimes|nullable',
      'latitude' => 'sometimes|nullable',
      'region_id' => 'sometimes|nullable|exists:regions,id',
    ]);

    if ($validator->fails()) {
      return response()->json(
        [
          'status' => 0,
          'message' => $validator->errors()->first()
        ]
      );
    }

    $user = auth()->user();
    $cart = $user->cart();

    $purchase_amount = $cart->total();
    if ($request->has('longitude', 'latitude', 'region_id')) {
      $region = Region::where('id', $request->region_id)->first();
      $start_point = $region->only('longitude', 'latitude');
      $end_point = $request->only('longitude', 'latitude');
      $distance_amount = Set::getDeliveryPrice(Set::calcDistance($start_point, $end_point));
      $delivery_amount = Set::calculateDeliveryPrice($start_point, $end_point, $purchase_amount);
    }

    if ($request->has('code')) {

      $valid_coupon = new ValidCoupon();

      if ($valid_coupon->passes('code', $request->code)) {
        $coupon = Coupon::where('code', $request->code)->first();

        $discount_amount = $purchase_amount * ($coupon->discount / 100);

      }
    }

    $total_amount = $purchase_amount + ($delivery_amount ?? 0) - ($discount_amount ?? 0);

    return response()->json([
      'status' => 1,
      'message' => 'success',
      'data' => [
        'purchase_amount' => number_format($purchase_amount, 2),
        'distance_amount' => number_format($distance_amount ?? 0, 2),
        'delivery_amount' => number_format($delivery_amount ?? 0, 2),
        'discount_amount' => number_format($discount_amount ?? 0, 2),
        'total_amount' => number_format($total_amount, 2),
      ],
    ]);
  }
}
