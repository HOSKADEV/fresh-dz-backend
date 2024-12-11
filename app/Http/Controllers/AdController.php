<?php

namespace App\Http\Controllers;

use App\Http\Resources\AdCollection;
use App\Http\Resources\AdResource;
use App\Http\Resources\PaginatedAdCollection;
use App\Models\Ad;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdController extends Controller
{

  public function index(){
    return view('content.ads.list');
  }

  public function create(Request $request){
    $validator = Validator::make($request->all(), [
      'name' => 'required|string',
      'image' => 'sometimes|mimetypes:image/*',
      'url' => 'required|string',
    ]);

    if ($validator->fails()) {
      return response()->json([
        'status'=> 0,
        'message' => $validator->errors()->first()
      ]);
    }
    try{


      $ad = Ad::create($request->except('image'));

      if($request->hasFile('image')){
        $url = $request->image->store('/uploads/ads/images','upload');

        /* $file = $request->image;
        $name = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();

        $filename = 'ads/' . $ad->id . '/' . md5(time().$name) . '.' . $extension;

        $url = $this->firestore($file->get(),$filename); */

        $ad->image = $url;
        $ad->save();
      }


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
      'url' => 'sometimes|string',
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

      $ad->update($request->except('image','ad_id'));

      if($request->hasFile('image')){
        $url = $request->image->store('/uploads/ads/images','upload');

        /* $file = $request->image;
        $name = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();

        $filename = 'ads/' . $ad->id . '/' . md5(time().$name) . '.' . $extension;

        $url = $this->firestore($file->get(),$filename); */

        $ad->image = $url;
        $ad->save();
      }

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
