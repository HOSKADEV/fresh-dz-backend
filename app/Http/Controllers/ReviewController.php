<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
  public function create(Request $request)
  {
      $validator = Validator::make($request->all(), [
          'order_id' => 'required|exists:orders,id',
          'content' => 'sometimes|string|nullable',
          'score' => 'required|numeric|min:1|max:4'
      ]);

      if ($validator->fails()) {
          return response()->json([
              'status' => 0,
              'message' => $validator->errors()->first()
          ]);
      }

      $review = Review::updateOrCreate(
      ['order_id' => $request->order_id],
      $request->except('order_id')
      );


      return response()->json([
          'status' => 1,
          'message' => 'success',
          'data' => $review
      ]);
  }

  public function delete(Request $request)
  {
      $validator = Validator::make($request->all(), [
          'review_id' => 'required|exists:reviews,id'
      ]);

      if ($validator->fails()) {
          return response()->json([
              'status' => 0,
              'message' => $validator->errors()->first()
          ]);
      }

      $review = Review::find($request->review_id);

      $review->delete();

      return response()->json([
          'status' => 1,
          'message' => 'success'
      ]);
  }
}
