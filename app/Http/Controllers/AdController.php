<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Ad;
use App\Models\Product;
use App\Models\ProductAd;
use Illuminate\Http\Request;
use App\Http\Resources\AdResource;
use App\Http\Resources\AdCollection;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\PaginatedAdCollection;

class AdController extends Controller
{

  public function index(){
    $products = Product::pluck('unit_name','id');
    return view('content.ads.list')->with('products',$products);
  }

  public function create(Request $request){
    $validator = Validator::make($request->all(), [
      'name' => 'required|string',
      'image' => 'sometimes|mimetypes:image/*',
      'type' => 'required|in:url,product,static',
      'url' => 'required_if:type,url|nullable|string',
      'product_id' => 'required_if:type,product|nullable|exists:products,id',
    ]);

    if ($validator->fails()) {
      return response()->json([
        'status'=> 0,
        'message' => $validator->errors()->first()
      ]);
    }
    try{


      $ad = Ad::create($request->only('name','type'));

      if($request->type == 'product'){
        ProductAd::create([
            'product_id' => $request->product_id,
            'ad_id' => $ad->id
        ]);
      }else if($request->type == 'url'){
        $ad->url = $request->url;
      }

      if($request->hasFile('image')){
        $path = $request->image->store('/uploads/ads/images','upload');
        $ad->image = $path;
      }

      $ad->save();

      return response()->json([
        'status' => 1,
        'message' => 'success',
        'data' => new AdResource($ad)
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
      'ad_id' => 'required',
      'name' => 'sometimes|string',
      'image' => 'sometimes|mimetypes:image/*',
      'type' => 'sometimes|in:url,product,static',
      'url' => 'required_if:type,url|nullable|string',
      'product_id' => 'required_if:type,product|nullable|exists:products,id',
    ]);

    if ($validator->fails()){
      return response()->json([
          'status' => 0,
          'message' => $validator->errors()->first()
        ]
      );
    }

    try{

      $ad = Ad::findOrFail($request->ad_id);

      $ad->update($request->only('name','type'));

      if($request->type == 'product'){
        $ad->url = null;
        ProductAd::updateOrInsert(
          ['ad_id' => $ad->id],
            ['product_id' => $request->product_id]
          );
      }else if($request->type == 'url'){
        $ad->product_ad()->delete();
        $ad->url = $request->url;
      }else{
        $ad->product_ad()->delete();
        $ad->url = null;
      }

      if($request->hasFile('image')){
        $path = $request->image->store('/uploads/ads/images','upload');
        $ad->image = $path;
      }

      $ad->save();

      return response()->json([
        'status' => 1,
        'message' => 'success',
        'data' => new AdResource($ad)
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
      'ad_id' => 'required',
    ]);

    if ($validator->fails()){
      return response()->json([
          'status' => 0,
          'message' => $validator->errors()->first()
        ]
      );
    }

    try{

      $ad = Ad::findOrFail($request->ad_id);

      $ad->delete();

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
      'ad_id' => 'required',
    ]);

    if ($validator->fails()){
      return response()->json([
          'status' => 0,
          'message' => $validator->errors()->first()
        ]
      );
    }

    try{

      $ad = Ad::withTrashed()->findOrFail($request->ad_id);

      $ad->restore();

      return response()->json([
        'status' => 1,
        'message' => 'success',
        'data' => new AdResource($ad)
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

    $ads = Ad::orderBy('created_at','DESC');

    if($request->has('search')){

      $ads = $ads->where('name', 'like', '%' . $request->search . '%');
    }

    $ads = $ads->paginate(5);

    //return($ads);

    return response()->json([
      'status' => 1,
      'message' => 'success',
      'data' => new PaginatedAdCollection($ads)
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
