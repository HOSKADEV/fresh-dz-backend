<?php

namespace App\Http\Controllers;

use App\Models\Notice;
use App\Models\Notification;
use App\Models\User;
use DB;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NoticeController extends Controller
{
    public function index(){
      return view('content.notices.list');
    }

    public function create(Request $request){
      $validator = Validator::make($request->all(), [
        'title_ar' => 'required|string',
        'title_en' => 'required|string',
        'content_ar' => 'required|string',
        'content_en' => 'required|string',
        'priority' => 'required|in:0,1'
      ]);

      if ($validator->fails()) {
        return response()->json([
          'status'=> 0,
          'message' => $validator->errors()->first()
        ]);
      }

      try{

        $request->mergeIfMissing(['type' => 0]);

        $notice = Notice::create($request->all());

        Notification::send($notice);

        return response()->json([
          'status' => 1,
          'message' => 'success',

        ]);

      }catch(Exception $e){
        DB::rollBack();
        return response()->json([
          'status' => 0,
          'message' => $e->getMessage()
        ]);
      }
    }

    public function update(Request $request){
      $validator = Validator::make($request->all(), [
        'notice_id' => 'required|exists:notices,id',
        'title_ar' => 'sometimes|string',
        'title_en' => 'sometimes|string',
        'content_ar' => 'sometimes|string',
        'content_en' => 'sometimes|string',
        'type' => 'sometimes|in:0,1,2'
      ]);

      if ($validator->fails()) {
        return response()->json([
          'status'=> 0,
          'message' => $validator->errors()->first()
        ]);
      }

      try{

        $notice = Notice::find($request->notice_id);

        $notice->update($request->except('notice_id'));

        return response()->json([
          'status' => 1,
          'message' => 'success',
          'data' => $notice
        ]);

      }catch(Exception $e){
        DB::rollBack();
        return response()->json([
          'status' => 0,
          'message' => $e->getMessage()
        ]);
      }
    }

    public function delete(Request $request){
      $validator = Validator::make($request->all(), [
        'notice_id' => 'required|exists:notices,id',
      ]);

      if ($validator->fails()) {
        return response()->json([
          'status'=> 0,
          'message' => $validator->errors()->first()
        ]);
      }

      try{

        $notice = Notice::find($request->notice_id);

        $notice->delete();

        return response()->json([
          'status' => 1,
          'message' => 'success',
        ]);

      }catch(Exception $e){
        DB::rollBack();
        return response()->json([
          'status' => 0,
          'message' => $e->getMessage()
        ]);
      }
    }
}
