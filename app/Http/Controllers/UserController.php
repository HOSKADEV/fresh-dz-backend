<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller
{

  public function index(){
    return view('content.users.list');
  }
    //
    public function update(Request $request){

      $request->mergeIfMissing(['user_id' => auth()->user()->id]);

      $validator = Validator::make($request->all(), [
        //'firstname' => 'sometimes|string',
        //'lastname' => 'sometimes|string',
        'user_id' => 'required|exists:users,id',
        'name' => 'sometimes|string',
        'phone' => 'sometimes|numeric',
        /* 'phone' => ['sometimes','numeric','digits:10',Rule::unique('users')->ignore($user->id)], */
        //'email' => ['sometimes','email',Rule::unique('users')->ignore($user->id)],
        'image' => 'sometimes|mimetypes:image/*',
        'status' => 'sometimes|in:0,1,2'
      ]);

      if ($validator->fails()){
        return response()->json([
            'status' => 0,
            'message' => $validator->errors()->first()
          ]
        );
      }

      try{

        $user = User::find($request->user_id);

        $user->update($request->except('image'));

        if($request->hasFile('image')){
            $url = $request->image->store('/uploads/users/images','upload');

            /* $file = $request->image;
            $name = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();

            $filename = 'users/' . $user->id . '/' . md5(time().$name) . '.' . $extension;

            $url = $this->firestore($file->get(),$filename); */

            $user->image = $url;
            $user->save();
        }

        return response()->json([
          'status' => 1,
          'message' => 'success',
          'data' => new UserResource($user)
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
        'user_id' => 'required',
      ]);

      if ($validator->fails()){
        return response()->json([
            'status' => 0,
            'message' => $validator->errors()->first()
          ]
        );
      }

      try{

        $user = User::findOrFail($request->user_id);

        $user->delete();

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
        'user_id' => 'required',
      ]);

      if ($validator->fails()){
        return response()->json([
            'status' => 0,
            'message' => $validator->errors()->first()
          ]
        );
      }

      try{

        $user = User::withTrashed()->findOrFail($request->user_id);

        $user->restore();

        return response()->json([
          'status' => 1,
          'message' => 'success',
          'data' => new UserResource($user)
        ]);

      }catch(Exception $e){
        return response()->json([
          'status' => 0,
          'message' => $e->getMessage()
        ]
      );
      }

    }

    public function change_password(Request $request){

      $validator = Validator::make($request->all(), [
        'old_password' => 'required',
        'new_password' => 'required|min:8|confirmed',
      ]);



      if ($validator->fails()) {
        return response()->json([
          'status'=> 0,
          'message' => $validator->errors()->first()
        ]);

      }

        $user = auth()->user();

        if(Hash::check($request->old_password, $user->password)){

          $user->password = Hash::make($request->new_password);
          $user->save();

          return response()->json([
            'status'=> 1,
            'message' => 'password changed'
          ]);

        }else{

          return response()->json([
            'status'=> 0,
            'message' => 'wrong password'
          ]);

        }

    }

    public function deactivate(Request $request){
      try{

        $user = $request->user();

        $user->update(['status' => 2 , 'email' => null , 'fcm_token' => null]);

        $user->tokens()->delete();

        return response()->json([
          'status'=> 1,
          'message' => 'success',
        ]);
      }catch(Exception $e){
        return response()->json([
          'status'=> 0,
          'message' => $e->getMessage(),
        ]);
      }

    }
}
