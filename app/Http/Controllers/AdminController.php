<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Admin;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
  public function index(){
    $regions = Region::pluck('name', 'id')->toArray();
    return view('content.admins.list')
    ->with('regions', $regions);
  }

  public function create(Request $request){

    $validator = Validator::make($request->all(), [
      'name' => 'required|string',
      'phone' => 'sometimes|numeric',
      'email' => 'required|email|unique:admins,email',
      'password' => 'required|string|min:8',
      'image' => 'sometimes|mimetypes:image/*',
      'role' => 'required|in:0,1,2,3,4,5',
      'region_id' => 'required_if:role,3',
    ]);

    if ($validator->fails()){
      return response()->json([
          'status' => 0,
          'message' => $validator->errors()->first()
        ]
      );
    }

    try{

      $request->merge(['password' => Hash::make($request->password)]);

      $admin = Admin::create($request->all());


      if($request->hasFile('image')){
          $url = $request->image->store('/uploads/admins/images','upload');
          $admin->image = $url;
          $admin->save();
      }

      return response()->json([
        'status' => 1,
        'message' => 'success',
      ]);

    }catch(Exception $e){
      return response()->json([
        'status' => 0,
        'message' => $e->getMessage()
      ]
    );
    }

  }

  public function update(Request $request){

    $request->mergeIfMissing(['admin_id' => auth()->admin()->id]);
    $admin = Admin::find($request->admin_id);

    $validator = Validator::make($request->all(), [
      'admin_id' => 'required|exists:admins,id',
      'name' => 'sometimes|string',
      'phone' => 'sometimes|numeric',
      'email' => ['sometimes','email',Rule::unique('admins')->ignore($admin->id)],
      'image' => 'sometimes|mimetypes:image/*',
      'status' => 'sometimes|in:0,1',
      'role' => 'sometimes|in:0,1,2,3,4,5',
      'region_id' => 'required_if:role,3',
    ]);

    if ($validator->fails()){
      return response()->json([
          'status' => 0,
          'message' => $validator->errors()->first()
        ]
      );
    }

    try{

      $admin->update($request->except('image'));

      if($request->hasFile('image')){
          $url = $request->image->store('/uploads/admins/images','upload');
          $admin->image = $url;
          $admin->save();
      }

      return response()->json([
        'status' => 1,
        'message' => 'success',
        'data' => $admin
      ]);

    }catch(Exception $e){
      return response()->json([
        'status' => 0,
        'message' => $e->getMessage()
      ]
    );
    }

  }

  public function delete(Request $request){

    $validator = Validator::make($request->all(), [
      'admin_id' => 'required',
    ]);

    if ($validator->fails()){
      return response()->json([
          'status' => 0,
          'message' => $validator->errors()->first()
        ]
      );
    }

    try{

      $admin = Admin::findOrFail($request->admin_id);

      $admin->delete();

      return response()->json([
        'status' => 1,
        'message' => 'success',
      ]);

    }catch(Exception $e){
      return response()->json([
        'status' => 0,
        'message' => $e->getMessage()
      ]
    );
    }

  }

  public function restore(Request $request){

    $validator = Validator::make($request->all(), [
      'admin_id' => 'required',
    ]);

    if ($validator->fails()){
      return response()->json([
          'status' => 0,
          'message' => $validator->errors()->first()
        ]
      );
    }

    try{

      $admin = Admin::withTrashed()->findOrFail($request->admin_id);

      $admin->restore();

      return response()->json([
        'status' => 1,
        'message' => 'success',
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
