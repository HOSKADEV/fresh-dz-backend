<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderDetailResource;
use App\Http\Resources\OrderResource;
use App\Http\Resources\PaginatedOrderCollection;
use App\Models\Cart;
use App\Models\Delivery;
use App\Models\Driver;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\Order;
use App\Models\Product;
use App\Models\Set;
use App\Models\User;
use Auth;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use Kreait\Firebase\Exception\FirebaseException;

class OrderController extends Controller
{

  public function index(){
    $drivers = Driver::all();
    $shipping = Set::where('name','shipping')->first();
    return view('content.orders.list')
    ->with('drivers',$drivers)
    ->with('shipping',$shipping);
  }

  public function distance(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'longitude' => 'required|string',
      'latitude' => 'required|string',
    ]);

    if ($validator->fails()) {
      return response()->json([
        'status' => 0,
        'message' => $validator->errors()->first()
      ]);
    }
    try {

      $start_point = [
        'lng' => 33.386027,
        'lat' => 6.839005
      ];

      $end_point = [
        'lng' => floatval($request->longitude),
        'lat' => floatval($request->latitude)
      ];

      $distance = $this->calc_distance($start_point,$end_point);
      /* $true_price = ($distance/1000) * 20;
      $actual_price = min(max($true_price,100),500); */
      $price = $this->delivery_price($distance);

      return response()->json(
        [
          'status' => 1,
          'data' => [
            'distance' => number_format($distance/1000,2,'.',','),
            'price' => number_format($price,2,'.',',')
          ]
        ]);

    } catch (Exception $e) {
      return response()->json(
        [
          'status' => 0,
          'message' => $e->getMessage()
        ]
      );
    }
  }
  public function create(Request $request)
  {
    $validator = Validator::make($request->all(), [

      'phone' => 'required|numeric|digits:10',
      'longitude' => 'required|string',
      'latitude' => 'required|string',
      //'products' => 'required|array',
      //'products.*.id' => 'required|distinct|exists:products,id',
      //'products.*.quantity' => 'required|numeric'
    ]);

    if ($validator->fails()) {
      return response()->json([
        'status' => 0,
        'message' => $validator->errors()->first()
      ]);
    }
    try {
      $user = auth()->user();


      $items = $user->cart()->items;

      if($items->count() == 0){
        throw new Exception('empty cart');
      }

      $cart = Cart::create(['user_id' => $user->id , 'type' => 'order']);

      foreach ($items as $item) {
        $quantity = $item->quantity;
        $product = Product::find($item->product_id);
        $discount = is_null($product->discount()) ? 0 : $product->discount()->amount;
        $product->add_to_cart($cart->id,$quantity,$discount);
      }

      $request->merge(['user_id' => $user->id, 'cart_id' => $cart->id]);

      $order = Order::create($request->all());

      $start_point = [
        'lng' => 33.386027,
        'lat' => 6.839005
      ];

      $end_point = [
        'lng' => floatval($request->longitude),
        'lat' => floatval($request->latitude)
      ];

      $distance = $this->calc_distance($start_point,$end_point);
      /* $true_price = ($distance/1000) * 20;
      $actual_price = min(max($true_price,100),500); */
      $price = $this->delivery_price($distance);

      $invoice = Invoice::create([
        'order_id' => $order->id,
        //'tax_type' => $request->tax_type,
        'tax_amount' => $price,
      ]);

      $invoice->total();

      $cart = $user->cart();

      $cart->delete();

      $admin_tokens = User::where('role',0)->whereNotNull('fcm_token')->pluck('fcm_token')->toArray();

      $this->send_fcm_multi(__('New order'),__('There is a new order pending'),$admin_tokens);

      return response()->json([
        'status' => 1,
        'message' => 'success',
        'data' => new OrderResource($order)
      ]);

    } catch (Exception $e) {
      return response()->json(
        [
          'status' => 0,
          'message' => $e->getMessage()
        ]
      );
    }
  }


  public function update(Request $request){

    //dd($request->only(['status','note']));

    $validator = Validator::make($request->all(), [
      'order_id' => 'required|exists:orders,id',
      'driver_id' => 'sometimes|exists:drivers,id',
      'status' => 'sometimes',
      //'tax_type' => 'sometimes|in:1,2',
      'tax_amount' => 'sometimes|numeric',
      'payment_method' => 'sometimes|in:1,2',
      'note' => 'sometimes'
    ]);

    if ($validator->fails()) {
      return response()->json([
        'status' => 0,
        'message' => $validator->errors()->first()
      ]);
    }
    try {

      $order = Order::find($request->order_id);

      if($request->has('status')){

        if($request->status == 'ongoing'){

          Delivery::create([
            'order_id' => $request->order_id,
            'driver_id' => $request->driver_id,
          ]);

          $invoice = $order->invoice;

          $invoice->pdf();
        }

        if($request->status == 'delivered'){

          $now = Carbon::now()->toDateString();
          $invoice = $order->invoice;
          $delivery = $order->delivery;

          $invoice->is_paid = 'yes';
          $invoice->paid_at = $now;
          $invoice->payment_method = $request->payment_method;

          $delivery->delivered_at = $now;

          $invoice->save();
          $delivery->save();

        }


        //$order->status = $request->status;
        //$order->save();

        $this->send_fcm_device(
          __('Order status update'),
          __('Your order is '.$request->status),
          $order->user->fcm_token
        );

      }

      //dd($request->only(['status','note']));

      $order->update($request->only(['status','note']));


      return response()->json([
        'status' => 1,
        'message' => 'success',
        'data' => $order
      ]);

    } catch (Exception $e) {
      return response()->json(
        [
          'status' => 0,
          'message' => $e->getMessage()
        ]
      );
    }

  }


  public function delete(Request $request){

    $validator = Validator::make($request->all(), [
      'order_id' => 'required',
    ]);

    if ($validator->fails()){
      return response()->json([
          'status' => 0,
          'message' => $validator->errors()->first()
        ]
      );
    }

    try{

      $order = Order::findOrFail($request->order_id);
      $cart = $order->cart;

      $order->delete();
      $cart->delete();

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
      'order_id' => 'required',
    ]);

    if ($validator->fails()){
      return response()->json([
          'status' => 0,
          'message' => $validator->errors()->first()
        ]
      );
    }

    try{

      $order = Order::withTrashed()->findOrFail($request->order_id);

      $order->restore();

      return response()->json([
        'status' => 1,
        'message' => 'success',
        'data' => new OrderResource($order)
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

    $validator = Validator::make($request->all(), [
      'order_id' => 'sometimes',
    ]);

    if ($validator->fails()){
      return response()->json([
          'status' => 0,
          'message' => $validator->errors()->first()
        ]
      );
    }

  try{

    $user = Auth::user();

    if($request->has('order_id')){

      $order = Order::where('id',$request->order_id)->where('user_id',$user->id)->first();

      return response()->json([
        'status' => 1,
        'message' => 'success',
        'data' => new OrderDetailResource($order)
      ]);

    }

    $orders = $user->orders()->orderBy('updated_at','DESC')->paginate(10);

    return response()->json([
      'status' => 1,
      'message' => 'success',
      'data' => new PaginatedOrderCollection($orders)
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
