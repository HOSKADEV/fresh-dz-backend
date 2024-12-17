<?php

namespace App\Http\Controllers;

use Str;
use Exception;
use App\Models\Coupon;
use App\Rules\ValidCoupon;
use Illuminate\Http\Request;
use App\Http\Resources\CouponResource;
use Illuminate\Support\Facades\Validator;

class CouponController extends Controller
{
  public function index()
  {
    return view('content.coupons.list');
  }

  public function generate()
  {

    do {
      $code = Str::random(8);
    } while (Coupon::where('code', $code)->count());

    return response()->json([
      'status' => 1,
      'message' => 'success',
      'data' => $code
    ]);
  }

  public function create(Request $request)
  {

    $validator = Validator::make($request->all(), [
      'name' => 'required|string',
      'code' => 'required|unique:coupons',
      'discount' => 'required|numeric|min:0|max:100',
      'start_date' => 'sometimes|nullable|date',
      'end_date' => 'sometimes|nullable|date|after:start_date',
      'max_uses' => 'sometimes|nullable|integer|min:1',
    ]);

    if ($validator->fails()) {
      return response()->json([
        'status' => 0,
        'message' => $validator->errors()->first()
      ]);
    }
    try {


      $coupon = Coupon::create($request->all());


      return response()->json([
        'status' => 1,
        'message' => 'success',
      ]);

    } catch (Exception $e) {
      return response()->json(
        [
          'status' => 0,
          'message' => $e->getMessage()
        ]
      );
    }
  }

  public function update(Request $request)
  {

    $validator = Validator::make($request->all(), [
      'coupon_id' => 'required',
      'name' => 'sometimes|string',
    ]);

    if ($validator->fails()) {
      return response()->json(
        [
          'status' => 0,
          'message' => $validator->errors()->first()
        ]
      );
    }

    try {

      $coupon = Coupon::findOrFail($request->coupon_id);

      $coupon->update($request->only('name'));


      return response()->json([
        'status' => 1,
        'message' => 'success',
        'data' => new CouponResource($coupon)
      ]);

    } catch (Exception $e) {
      return response()->json(
        [
          'status' => 0,
          'message' => $e->getMessage()
        ]
      );
    }

  }

  public function delete(Request $request)
  {

    $validator = Validator::make($request->all(), [
      'coupon_id' => 'required',
    ]);

    if ($validator->fails()) {
      return response()->json(
        [
          'status' => 0,
          'message' => $validator->errors()->first()
        ]
      );
    }

    try {

      $coupon = Coupon::findOrFail($request->coupon_id);

      $coupon->delete();

      return response()->json([
        'status' => 1,
        'message' => 'success',
      ]);

    } catch (Exception $e) {
      return response()->json(
        [
          'status' => 0,
          'message' => $e->getMessage()
        ]
      );
    }

  }

  public function restore(Request $request)
  {

    $validator = Validator::make($request->all(), [
      'coupon_id' => 'required',
    ]);

    if ($validator->fails()) {
      return response()->json(
        [
          'status' => 0,
          'message' => $validator->errors()->first()
        ]
      );
    }

    try {

      $coupon = Coupon::withTrashed()->findOrFail($request->coupon_id);

      $coupon->restore();

      return response()->json([
        'status' => 1,
        'message' => 'success',
      ]);

    } catch (Exception $e) {
      return response()->json(
        [
          'status' => 0,
          'message' => $e->getMessage()
        ]
      );
    }

  }

  public function check(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'code' => ['required','exists:coupons', new ValidCoupon()],
    ]);

    if ($validator->fails()) {
      return response()->json(
        [
          'status' => 0,
          'message' => $validator->errors()->first()
        ]
      );
    } else {

      return response()->json([
        'status' => 1,
        'message' => 'valid',
      ]);
    }
  }
}
