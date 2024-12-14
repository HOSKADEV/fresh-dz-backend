<?php

namespace App\Http\Controllers;

use Storage;
use Exception;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class ProductImageController extends Controller
{
  public function index($id){
    $product = Product::findOrFail($id);

    return view('content.products.images')
    ->with('product',$product);
  }

  public function add(Request $request){
    //dd($request->all());

    $validator = Validator::make($request->all(), [
      'product_id' => 'required|exists:products,id',
      'images' => 'required|array',
      'images.*' => 'file|mimetypes:image/*',
    ]);

    if ($validator->fails()) {
      return response()->json([
        'status'=> 0,
        'message' => $validator->errors()->first()
      ]);
    }

    try{
//dd($request->images);
      DB::beginTransaction();

      $images = $request->images;

      array_walk($images, function(&$item, $key) use ($request){
        $item = [
        'path' => $item->store('/uploads/products/images','upload'),
        'product_id' => $request->product_id,
        'created_at' => now()
        ];
      });

      ProductImage::insert($images);

      DB::commit();

      return response()->json([
        'status'=> 1,
        'message' => 'success',
      ]);

    }catch(Exception $e){
      DB::rollBack();
      return response()->json([
        'status'=> 0,
        'message' => $e->getMessage(),
      ]);

    }
  }

  public function delete(Request $request){

    $validator = Validator::make($request->all(), [
      'image_id' => 'required|exists:product_images,id',
    ]);

    if ($validator->fails()){
      return response()->json([
          'status' => 0,
          'message' => $validator->errors()->first()
        ]
      );
    }

    try{

      $image = ProductImage::findOrFail($request->image_id);

      if(File::exists($image->path)) {
        File::delete($image->path);
      }

      $image->delete();

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
