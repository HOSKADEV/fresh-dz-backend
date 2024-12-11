<?php

namespace App\Http\Controllers;

use App\Http\Resources\CartResource;
use App\Models\Cart;
use App\Models\Item;
use DB;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
  public function update(Request $request){
    $validator = Validator::make($request->all(), [
      'product_id' => 'sometimes|exists:products,id',
      'quantity' => 'sometimes|integer'
    ]);

    if ($validator->fails()) {
      return response()->json([
        'status'=> 0,
        'message' => $validator->errors()->first()
      ]);
    }

    try{

      DB::beginTransaction();

      $user = auth()->user();
      $cart = $user->cart();

      if($request->has('product_id')){
        if($request->quantity == 0 ){
          $item = Item::where('cart_id',$cart->id)
          ->where('product_id',$request->product_id)
          ->first();

          if(!is_null($item)){
            $item->delete();
          }
        }else{
          Item::updateOrInsert(
            ['cart_id' => $cart->id , 'product_id' => $request->product_id],
            ['quantity' => $request->quantity , 'deleted_at' => null]
          );
        }

        /* $item = Item::where('cart_id',$cart->id)
          ->where('product_id',$request->product_id)
          ->first();

        if(!is_null($item)){
          $item->delete();
        }

        if($request->quantity > 0 ){
          $request->merge(['cart_id' => $cart->id]);
          Item::create($request->all());
        } */

      }

      DB::commit();

      $cart->refresh();

      return response()->json([
        'status' => 1,
        'message' => 'success',
        'data' => ['total' => $cart->total()],
      ]);

    }catch(Exception $e){
      DB::rollBack();
      return response()->json([
        'status' => 0,
        'message' => $e->getMessage()
      ]
      );
    }
  }

  public function delete(Request $request){

    try{

      $user = auth()->user();
      $cart = $user->cart();

      $cart->delete();

      Cart::create(['user_id' => $user->id, 'type' => 'current']);


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

  public function get(Request $request){

    try{

      $user = auth()->user();
      $cart = $user->cart();

      return response()->json([
        'status' => 1,
        'message' => 'success',
        'data' => new CartResource($cart)
      ]);

    }catch(Exception $e){

      return response()->json([
        'status' => 0,
        'message' => $e->getMessage()
      ]
      );
    }
  }

  public function products(Request $request){

    try{

      $user = auth()->user();
      $cart = $user->cart();

      $products = Item::where('cart_id',$cart->id)->sum('quantity');

      //dd($products);

      return response()->json([
        'status' => 1,
        'message' => 'success',
        'data' => ['products' => intval($products)],
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
