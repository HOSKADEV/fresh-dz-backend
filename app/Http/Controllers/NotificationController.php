<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\NotificationResource;
use App\Http\Resources\NotificationCollection;
use App\Http\Resources\PaginatedNotificationCollection;

class NotificationController extends Controller
{
  public function read(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'notification_id' => 'required|integer|exists:notifications,id',
    ]);

    if ($validator->fails()) {
      return response()->json(
        [
          'status' => false,
          'message' => $validator->errors()->first()
        ]
      );
    }

    try {

      $notification = Notification::find($request->notification_id);

      $notification->is_read = 1;
      $notification->read_at = now();
      $notification->save();

      return response()->json([
        'status' => true,
        'message' => 'success',
        'data' => new NotificationResource($notification)
      ]);

    } catch (Exception $e) {
      return response()->json(
        [
          'status' => false,
          'message' => $e->getMessage()
        ]
      );
    }
  }
  public function get(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'is_read' => 'sometimes|in:0,1',
      'type' => 'sometimes|in:0,1,2,3,4',
      'priority' => 'sometimes|in:0,1',
      'all' => 'sometimes',
    ]);

    if ($validator->fails()) {
      return response()->json(
        [
          'status' => false,
          'message' => $validator->errors()->first()
        ]
      );
    }

    try {

    $user = auth()->user();

      $notifications = $user->notifications()->orderBy('created_at', 'DESC');

      if ($request->has('is_read')) {
        $notifications = $notifications->where('is_read', $request->is_read);
      }

      if ($request->has('type')) {
        $notifications = $notifications->whereHas('notice', function($query) use ($request){
          $query->where('type', $request->type);
        });
      }

      if ($request->has('priority')) {
        $notifications = $notifications->whereHas('notice', function($query) use ($request){
          $query->where('priority', $request->priority);
        });
      }

      if ($request->has('all')) {
        $notifications = new NotificationCollection($notifications->get());
      } else {
        $notifications = new PaginatedNotificationCollection($notifications->paginate(10));
      }

      return response()->json([
        'status' => true,
        'message' => 'success',
        'data' => $notifications
      ]);

    } catch (Exception $e) {
      return response()->json(
        [
          'status' => false,
          'message' => $e->getMessage()
        ]
      );
    }

  }
}
