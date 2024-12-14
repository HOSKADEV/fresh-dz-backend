<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Product;
use App\Models\ProductVideo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class ProductVideoController extends Controller
{
  public function index($id){
    $product = Product::findOrFail($id);

    return view('content.products.videos')
    ->with('product',$product);
  }

  public function add(Request $request){
    //dd($request->all());

    $validator = Validator::make($request->all(), [
      'videos' => 'required|array',
      'videos.*.product_id' => 'required|exists:products,id',
      'videos.*.path' => 'required|mimetypes:video/*',
    ]);

    if ($validator->fails()) {
      return response()->json([
        'status'=> 0,
        'message' => $validator->errors()->first()
      ]);
    }

    try{

      DB::beginTransaction();

      $videos = $request->videos;

      array_walk($videos, function(&$item, $key){
        $item['path'] = $item['path']->store('/uploads/products/videos','upload');
      });

      ProductVideo::insert($videos);

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
      'video_id' => 'required|exists:product_videos,id',
    ]);

    if ($validator->fails()){
      return response()->json([
          'status' => 0,
          'message' => $validator->errors()->first()
        ]
      );
    }

    try{

      $video = ProductVideo::findOrFail($request->video_id);

      if(File::exists($video->path)) {
        File::delete($video->path);
      }

      $video->delete();

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
