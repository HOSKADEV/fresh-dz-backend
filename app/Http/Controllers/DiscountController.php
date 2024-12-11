<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryDiscountCollection;
use App\Http\Resources\DiscountCollection;
use App\Http\Resources\DiscountResource;
use App\Http\Resources\PaginatedCategoryDiscountCollection;
use App\Http\Resources\PaginatedProductDiscountCollection;
use App\Http\Resources\ProductDiscountCollection;
use App\Http\Resources\ProductDiscountResource;
use App\Models\Category;
use App\Models\Discount;
use App\Models\Product;
use App\Models\Subcategory;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Session;

class DiscountController extends Controller
{

  public function create(Request $request){

    $product = Product::findOrFail($request->product_id);

    if($request->type == '1'){
      $request->merge(['amount' => floatval($request->amount)*100/$product->unit_price]);
    }

    //dd($request->all());

    $validator = Validator::make($request->all(), [
      'product_id' => 'required|exists:products,id',
      //'name' => 'required|string',
      'type' => 'required|in:1,2',
      'amount' => 'required|numeric|min:0|max:100',
      'start_date' => 'required|date_equals:today',
      'end_date' => 'required|after:start_date'
    ]);

    if ($validator->fails()) {
      return response()->json([
        'status'=> 0,
        'message' => $validator->errors()->first()
      ]);
    }
    try{

      if(is_null($product->discount())){
        $discount = Discount::create($request->all());
      }

      return response()->json([
        'status' => 1,
        'message' => 'success',
        'data' => new DiscountResource($discount)
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

    //dd($request->all());i
    if($request->has('product_id') && $request->has('type')){
      $product = Product::findOrFail($request->product_id);
      if($request->type == '1'){
        $request->merge(['amount' => floatval($request->amount)*100/$product->unit_price]);
      }
    }

    $validator = Validator::make($request->all(), [
      'discount_id' => 'required',
      'product_id' => 'sometimes|exists:products,id',
      //'name' => 'sometimes|string',
      'type' => 'sometimes|in:1,2',
      'amount' => 'sometimes|numeric|min:0|max:100',
      'start_date' => 'required_with:end_date',
      'end_date' => 'required_with:start_date|after:today'
    ]);

    if ($validator->fails()){
      return response()->json([
          'status' => 0,
          'message' => $validator->errors()->first()
        ]
      );
    }

    try{

      $discount = Discount::findOrFail($request->discount_id);

      $discount->update($request->except('discount_id'));


      return response()->json([
        'status' => 1,
        'message' => 'success',
        'data' => new DiscountResource($discount)
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
      'discount_id' => 'required',
    ]);

    if ($validator->fails()){
      return response()->json([
          'status' => 0,
          'message' => $validator->errors()->first()
        ]
      );
    }

    try{

      $discount = Discount::findOrFail($request->discount_id);

      $discount->delete();

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
      'discount_id' => 'required',
    ]);

    if ($validator->fails()){
      return response()->json([
          'status' => 0,
          'message' => $validator->errors()->first()
        ]
      );
    }

    try{

      $discount = Discount::withTrashed()->findOrFail($request->discount_id);

      $discount->restore();

      return response()->json([
        'status' => 1,
        'message' => 'success',
        'data' => new DiscountResource($discount)
      ]);

    }catch(Exception $e){
      return response()->json([
        'status' => 0,
        'message' => $e->getMessage()
      ]
    );
    }

  }

  public function get(Request $request){  //paginated
    $validator = Validator::make($request->all(), [
      'category_id' => 'sometimes|exists:categories,id',
    ]);

    if ($validator->fails()){
      return response()->json([
          'status' => 0,
          'message' => $validator->errors()->first()
        ]
      );
    }

    try{

      if(!is_null($request->bearerToken())){
        Session::put('user_id', $this->get_user_from_token($request->bearerToken())->id);
      }

      if($request->has('category_id')){

        $category = Category::findOrFail($request->category_id);
        $products_discounts = $category->discounts()->paginate(10);

        return response()->json([
          'status' => 1,
          'message' => 'success',
          'data' => new PaginatedProductDiscountCollection($products_discounts)
        ]);
      }


    $discounts = DB::table('discounts')
    ->WhereRaw('? between start_date and end_date', Carbon::now()->toDateString())
    ->join('products','discounts.product_id','products.id')
    ->join('subcategories','products.subcategory_id','subcategories.id')
    ->join('categories','subcategories.category_id','categories.id')
    ->orderBy('categories.created_at','DESC')->groupBy(DB::raw('categories.id'))
    ->select('categories.id')->get()->pluck('id')->toArray();
    //return($discounts);
    $categories = Category::whereIn('id',$discounts)->paginate(5);
      //return($categories);
    $categories_discounts = new PaginatedCategoryDiscountCollection($categories);

    return response()->json([
      'status' => 1,
      'message' => 'success',
      'data' => $categories_discounts
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
