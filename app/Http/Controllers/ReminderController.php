<?php

namespace App\Http\Controllers;

use App\Models\Reminder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReminderController extends Controller
{
  public function create(Request $request)
  {
      $validator = Validator::make($request->all(), [
          'product_id' => 'required|exists:products,id',
      ]);

      if ($validator->fails()) {
          return response()->json([
              'status' => 0,
              'message' => $validator->errors()->first()
          ]);
      }

      Reminder::create([
        'user_id' => auth()->id(),
        'product_id' => $request->product_id,
      ],
      );


      return response()->json([
          'status' => 1,
          'message' => 'success',
      ]);
  }

  public function delete(Request $request)
  {
      $validator = Validator::make($request->all(), [
          'reminder_id' => 'required|exists:reminders,id'
      ]);

      if ($validator->fails()) {
          return response()->json([
              'status' => 0,
              'message' => $validator->errors()->first()
          ]);
      }

      $reminder = Reminder::find($request->reminder_id);

      $reminder->delete();

      return response()->json([
          'status' => 1,
          'message' => 'success'
      ]);
  }
}
