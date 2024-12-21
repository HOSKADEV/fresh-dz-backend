<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Kreait\Firebase\Auth\UserQuery;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use Chargily\ChargilyPay\ChargilyPay;
use Kreait\Firebase\JWT\IdTokenVerifier;
use Illuminate\Support\Facades\Validator;
use Chargily\ChargilyPay\Auth\Credentials;
use Kreait\Laravel\Firebase\Facades\Firebase;
use Kreait\Firebase\Exception\Auth\FailedToVerifyToken;
use Kreait\Firebase\JWT\Error\IdTokenVerificationFailed;

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

        $firebase_user = $auth->getUser($request->uid);

        $user = User::firstOrCreate(
          ['email' => $firebase_user->email],
          [
            'name' => $firebase_user->displayName ?? 'user#'.uuid_create(),
            'phone' => $firebase_user->phoneNumber,
            'image' => $firebase_user->photoUrl,
          ]
        );

        switch($user->status){
          case 0 : throw new Exception('blocked account');
          case 2 : throw new Exception('deactivated account');
        }

        if (empty($user->customer_id) && $user->phone) {
          $chargily_pay = new ChargilyPay(new Credentials(config('chargily.credentials')));
          $customer = $chargily_pay->customers()->create([
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone
          ]);

          $user->customer_id = $customer->getId();
        }

        if($request->has('fcm_token')){
          $user->fcm_token = $request->fcm_token;
        }

        $token = $user->createToken($this->random())->plainTextToken;
        $user->save();

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
