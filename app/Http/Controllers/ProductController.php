<?php

namespace App\Http\Controllers;

use Session;
use Exception;
use App\Models\Unit;
use App\Models\Product;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ProductCollection;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\PaginatedProductCollection;

class ProductController extends Controller
{

  public function index(){
    $categories = Category::all();
    $units = Unit::all();
    return view('content.products.list')
    ->with('categories',$categories)
    ->with('units',$units);
  }
  public function create(Request $request){
    //dd($request->all());
    $validator = Validator::make($request->all(), [

      'subcategory_id' => 'required|exists:subcategories,id',
      'unit_id' => 'required|exists:units,id',
      'unit_name' => 'required|string',
      'pack_name'=> 'sometimes|string',
      'image' => 'sometimes|mimetypes:image/*',
      'unit_price' => 'required|numeric',
      'pack_price' => 'required_with:pack_units|nullable|numeric',
      'pack_units' => 'required_with:pack_price|nullable|integer',
      'status' => 'required|in:1,2',
      'description'=> 'sometimes|nullable|string',
    ]);

    if ($validator->fails()) {
      return response()->json([
        'status'=> 0,
        'message' => $validator->errors()->first()
      ]);
    }
    try{


      $product = Product::create($request->except('image'));

      if($request->hasFile('image')){
        $url = $request->image->store('/uploads/products/images','upload');

        /* $file = $request->image;
        $name = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();

        $filename = 'products/' . $product->id . '/' . md5(time().$name) . '.' . $extension;

        $url = $this->firestore($file->get(),$filename); */

        $product->image = $url;
        $product->save();
      }


      return response()->json([
        'status' => 1,
        'message' => 'success',
        'data' => new ProductResource($product)
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

    $validator = Validator::make($request->all(), [
      'product_id' => 'required|exists:products,id',
      'unit_id' => 'sometimes|exists:units,id',
      'unit_name' => 'sometimes|string',
      'pack_name'=> 'sometimes|string',
      'image' => 'sometimes|mimetypes:image/*',
      'unit_price' => 'sometimes|numeric',
      'pack_price' => 'required_with:pack_units|nullable|numeric',
      'pack_units' => 'required_with:pack_price|nullable|integer',
      'status' => 'sometimes|in:1,2',
      'description'=> 'sometimes|nullable|string',
    ]);

    if ($validator->fails()){
      return response()->json([
          'status' => 0,
          'message' => $validator->errors()->first()
        ]
      );
    }

    try{

      $product = Product::findOrFail($request->product_id);

      $product->update($request->except('image','product_id'));

      if($request->hasFile('image')){
        $url = $request->image->store('/uploads/products/images','upload');

        /* $file = $request->image;
        $name = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();

        $filename = 'products/' . $product->id . '/' . md5(time().$name) . '.' . $extension;

        $url = $this->firestore($file->get(),$filename); */

        $product->image = $url;
        $product->save();
      }

      if($request->has('status')){
        $product->notify($request->status == '1' ? 'available':'unavailable');
      }

      return response()->json([
        'status' => 1,
        'message' => 'success',
        'data' => new ProductResource($product)
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
      'product_id' => 'required',
    ]);

    if ($validator->fails()){
      return response()->json([
          'status' => 0,
          'message' => $validator->errors()->first()
        ]
      );
    }

    try{

      $product = Product::findOrFail($request->product_id);

      $product->delete();

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
      'product_id' => 'required',
    ]);

    if ($validator->fails()){
      return response()->json([
          'status' => 0,
          'message' => $validator->errors()->first()
        ]
      );
    }

    try{

      $product = Product::withTrashed()->findOrFail($request->product_id);

      $product->restore();

      return response()->json([
        'status' => 1,
        'message' => 'success',
        'data' => new ProductResource($product)
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
      'category_id' => 'sometimes|missing_with:subcategory_id|exists:categories,id',
      'subcategory_id' => 'sometimes|exists:subcategories,id',
      'search' => 'sometimes|string',

    ]);

    if ($validator->fails()){
      return response()->json([
          'status' => 0,
          'message' => $validator->errors()->first()
        ]
      );
    }

    try{

    $products = Product::whereNotNull('image')->orderBy('created_at','DESC');

    if($request->has('category_id')){

      $category = Category::findOrFail($request->category_id);
      $category_subs = $category->subcategories()->pluck('id')->toArray();
      $products = $products->whereIn('subcategory_id',$category_subs);
    }

    if($request->has('subcategory_id')){

      $subcategory = Subcategory::findOrFail($request->subcategory_id);
      $sub_products = $subcategory->products()->pluck('id')->toArray();
      $products = $products->whereIn('id',$sub_products);
    }

    if($request->has('search')){

      $products = $products->where('unit_name', 'like', '%' . $request->search . '%');
                            //->orWhere('pack_name', 'like', '%' . $request->search . '%');
    }

    if($request->has('all')){
      $products = $products->get();
      return response()->json([
        'status' => 1,
        'message' => 'success',
        'data' => $products
      ]);

    }
      $products = $products->paginate(10);


    return response()->json([
      'status' => 1,
      'message' => 'success',
      'data' => new PaginatedProductCollection($products)
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
