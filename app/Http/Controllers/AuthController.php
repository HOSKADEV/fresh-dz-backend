<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Kreait\Firebase\Auth\UserQuery;
use Kreait\Firebase\Exception\Auth\FailedToVerifyToken;
use Kreait\Laravel\Firebase\Facades\Firebase;
use Kreait\Firebase\JWT\Error\IdTokenVerificationFailed;
use Kreait\Firebase\JWT\IdTokenVerifier;

class AuthController extends Controller
{
    //
    public function register(Request $request){

      $validator = Validator::make($request->all(), [
        'uid' => 'required',
      ]);

      if ($validator->fails()){
        return response()->json([
            'status' => 0,
            'message' => $validator->errors()->first()
          ]
        );
      }


      $auth = Firebase::auth();

      try {
        //$firebase_user = $auth->getUser($request->uid);

        //$firebase_token = $auth->verifyIdToken($request->firebase_token);

        //$uid = $firebase_token->claims()->get('sub');

        $firebase_user = $auth->getUser($request->uid);

        $user = User::create([
          'name' => $firebase_user->displayName,
          'email' => $firebase_user->email,
          'phone' => $firebase_user->phoneNumber,
          'image' => $firebase_user->photoUrl,
        ]);

      $token = $user->createToken($this->random())->plainTextToken;

        return response()->json([
          'status'=> 1,
          'message' => 'success',
          'token' => $token,
          'data' => new UserResource($user),
        ]);

      } catch (Exception $e) {
          //dd($e->getMessage());

          return response()->json([
          'status'=> 0,
          'message' => $e->getMessage(),
        ]);
      }
    }

    public function login(Request $request){

      $validator = Validator::make($request->all(), [
        'uid' => 'required',
        'fcm_token' => 'sometimes',
      ]);

      if ($validator->fails()){
        return response()->json([
            'status' => 0,
            'message' => $validator->errors()->first()
          ]
        );
      }

      $auth = Firebase::auth();

      try {

        //$firebase_user = $auth->getUser($request->uid);

        //$firebase_token = $auth->verifyIdToken(request()->bearerToken());

        //$uid = $firebase_token->claims()->get('sub');

        $firebase_user = $auth->getUser($request->uid);

        $user = User::where('email',$firebase_user->email)->first();

        if(is_null($user)){
          $user = User::create([
            'name' => $firebase_user->displayName,
            'email' => $firebase_user->email,
            'phone' => $firebase_user->phoneNumber,
            'image' => $firebase_user->photoUrl,
          ]);
        }

        /* User::withTrashed()->updateOrInsert(
          ['email' => $firebase_user->email],
          [
              'name' => $firebase_user->displayName,
              'email' => $firebase_user->email,
              'phone' => $firebase_user->phoneNumber,
              'image' => $firebase_user->photoUrl,
              'deleted_at' => null
          ]
      ); */

      $user = User::where('email',$firebase_user->email)->first();

      switch($user->status){
        case 0 : throw new Exception('blocked account');
        case 2 : throw new Exception('deactivated account');
      }

      if($request->has('fcm_token')){
        $user->fcm_token = $request->fcm_token;
        $user->save();
      }

      if(empty($user->name)){
        $user->name = 'user#'.$user->id;
        $user->save();
      }

      $token = $user->createToken($this->random())->plainTextToken;

        return response()->json([
          'status'=> 1,
          'message' => 'success',
          'token' => $token,
          'data' => new UserResource($user),
        ]);

      } catch (Exception $e) {
          //dd($e->getMessage());

          return response()->json([
          'status'=> 0,
          'message' => $e->getMessage(),
        ]);
      }


    }

    public function logout(Request $request){
      try{

        $request->user()->currentAccessToken()->delete();

        return response()->json([
          'status'=> 1,
          'message' => 'logged out',
        ]);
      }catch(Exception $e){
        return response()->json([
          'status'=> 0,
          'message' => $e->getMessage(),
        ]);
      }

    }
}
