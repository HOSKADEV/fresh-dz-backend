<?php

namespace App\Http\Controllers;

use App\Http\Resources\VersionResource;
use App\Models\Version;
use Auth;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VersionController extends Controller
{
    public function index(){
      $android = Version::android();
      $ios = Version::ios();

      return view('content.version.index')
      ->with('android',$android)
      ->with('ios',$ios);


    }

    public function update(Request $request){
      $validator = Validator::make($request->all(), [
        'android_version_number' => 'required',
        'android_build_number' => 'required',
        'android_priority' => 'required',
        'android_link' => 'required',
        'ios_version_number' => 'required',
        'ios_build_number' => 'required',
        'ios_priority' => 'required',
        'ios_link' => 'required',
      ]);



      if ($validator->fails()) {
        return response()->json([
          'status'=> 0,
          'message' => $validator->errors()->first()
        ]);
      }

      $android = Version::android();
      $ios = Version::ios();

      $android->version_number = $request->android_version_number;
      $android->build_number = $request->android_build_number;
      $android->priority = $request->android_priority;
      $android->link = $request->android_link;

      $ios->version_number = $request->ios_version_number;
      $ios->build_number = $request->ios_build_number;
      $ios->priority = $request->ios_priority;
      $ios->link = $request->ios_link;

      $android->save();
      $ios->save();


      return response()->json([
        'status'=> 1,
        'message' => 'success'
      ]);

    }

    public function get(){

      try{

        $version = new VersionResource(null);

        return response()->json([
          'status' => 1,
          'message' => 'success',
          'data' => $version,
        ]);

      }catch(Exception $e){
        return response()->json([
          'status' => 0,
          'message' => $e->getMessage()
        ]
      );
      }

    }
}
