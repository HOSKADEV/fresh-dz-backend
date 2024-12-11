<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Models\Order;
use App\Models\Product;
use Exception;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ItemController extends Controller
{
    public function index($id){
      $order = Order::findOrFail($id);
      $categories = Category::all();

      return view('content.orders.items')
      ->with('order',$order)
      ->with('categories',$categories);
    }

    public function add(Request $request){
      $validator = Validator::make($request->all(), [
        'order_id' => ['required', Rule::exists('orders','id')->where(function (Builder $query) {
          return $query->whereIn('status', ['pending','accepted']);
          }),],
        'product_id' => 'required|exists:products,id',
        'quantity' => 'required|integer|min:1'
      ]);

      if ($validator->fails()) {
        return response()->json([
          'status'=> 0,
          'message' => $validator->errors()->first()
        ]);
      }
      try{

        DB::beginTransaction();

          $order = Order::findOrFail($request->order_id);

          $product = Product::findOrFail($request->product_id);

          $quantity = $request->quantity;

          $item = $order->cart->items()->where('product_id',$request->product_id)->first();

          if(is_null($item)){
            $discount = is_null($product->discount()) ? 0 : $product->discount()->amount;

            $product->add_to_cart($order->cart_id,$quantity,$discount);

          }else{

            $unit_item = $order->cart->items()->where('product_id',$request->product_id)
              ->where('type','unit')->first();

            $pack_item = $order->cart->items()->where('product_id',$request->product_id)
              ->where('type','pack')->first();

            $unit_quantity = is_null($unit_item) ? 0 : $unit_item->quantity;
            $pack_quantity = is_null($pack_item) ? 0 : $pack_item->quantity;


            $quantity += $unit_quantity + ($pack_quantity * $item->pack_units);
            $discount = $item->discount;

            $product->add_to_cart($order->cart_id,$quantity,$discount);

            if(!is_null($unit_item)){$unit_item->delete();}
            if(!is_null($pack_item)){$pack_item->delete();}

          }

          $invoice = $order->invoice;
          $invoice->total();

        DB::commit();

        return response()->json([
          'status' => 1,
          'message' => 'success',

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

    public function edit(Request $request){

      $validator = Validator::make($request->all(), [
        'order_id' => ['required', Rule::exists('orders','id')->where(function (Builder $query) {
          return $query->where('status', 'pending');
          }),],
        'item_id' => 'required|exists:items,id',
        'quantity' => 'required|integer|min:1'
      ]);

      if ($validator->fails()) {
        return response()->json([
          'status'=> 0,
          'message' => $validator->errors()->first()
        ]);
      }

      try{

        DB::beginTransaction();

          $order = Order::findOrFail($request->order_id);

          $item = Item::findOrFail($request->item_id);

          if($item->type == 'pack'){

            $item->quantity = $request->quantity;

            $item->amount = $request->quantity * ($item->price() * (1 - ($item->discount / 100)));

            $item->save();
          }else{

            $product = $item->product;


            $pack_item = Item::where('cart_id', $item->cart_id)
                              ->where('product_id',$item->product_id)
                              ->where('type','pack')->first();

            $unit_quantity = $request->quantity;
            $pack_quantity = is_null($pack_item) ? 0 : $pack_item->quantity;


            $quantity = $unit_quantity + ($pack_quantity * $item->pack_units);
            $discount = $item->discount;

            $product->add_to_cart($item->cart_id,$quantity,$discount);


            if(!is_null($pack_item)){$pack_item->delete();}
            $item->delete();

          }

          $invoice = $order->invoice;
          $invoice->total();

        DB::commit();

        return response()->json([
          'status' => 1,
          'message' => 'success',
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

      $validator = Validator::make($request->all(), [
        'item_id' => 'required',
      ]);

      if ($validator->fails()){
        return response()->json([
            'status' => 0,
            'message' => $validator->errors()->first()
          ]
        );
      }

      try{

        $item = Item::findOrFail($request->item_id);

        $item->delete();

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
        'item_id' => 'required',
      ]);

      if ($validator->fails()){
        return response()->json([
            'status' => 0,
            'message' => $validator->errors()->first()
          ]
        );
      }

      try{

        $item = Item::findOrFail($request->item_id);

        $item->restore();

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
