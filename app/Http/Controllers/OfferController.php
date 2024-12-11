<?php

namespace App\Http\Controllers;

use App\Http\Resources\OfferCollection;
use App\Http\Resources\OfferResource;
use App\Http\Resources\PaginatedOfferCollection;
use App\Models\Category;
use App\Models\CategoryOffer;
use App\Models\Offer;
use DB;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OfferController extends Controller
{

  public function index(){
    $categories = Category::all();
    return view('content.offers.list')
    ->with('categories',$categories);
  }
  public function create(Request $request){
    $validator = Validator::make($request->all(), [
      'name' => 'required|string',
      'name_en' => 'sometimes|string',
      'categories' => 'required|array',
      'categories.*' => 'distinct'
    ]);

    if ($validator->fails()) {
      return response()->json([
        'status'=> 0,
        'message' => $validator->errors()->first()
      ]);
    }

    try{

      DB::beginTransaction();

        $offer = Offer::create($request->except('categories'));

        foreach($request->categories as $category){
          $category = Category::findOrfail($category);
          CategoryOffer::create(['offer_id' => $offer->id, 'category_id' => $category->id]);
        }

      DB::commit();

      return response()->json([
        'status' => 1,
        'message' => 'success',
        'data' => new OfferResource($offer)
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

  public function update(Request $request){

    $validator = Validator::make($request->all(), [
      'offer_id' => 'required',
      'name' => 'sometimes|string',
      'name_en' => 'sometimes|string',
      'categories' => 'sometimes|array',
      'categories.*' => 'distinct'
    ]);

    if ($validator->fails()){
      return response()->json([
          'status' => 0,
          'message' => $validator->errors()->first()
        ]
      );
    }

    try{

      $offer = Offer::findOrFail($request->offer_id);

      DB::beginTransaction();

        $offer->update($request->except('offer_id' ));

        if($request->has('categories')){
          foreach($offer->category_offers as $category_offer){
            $category_offer->delete();
          }
          foreach($request->categories as $category){
            $category = Category::findOrfail($category);
            CategoryOffer::create(['offer_id' => $offer->id, 'category_id' => $category->id]);
          }
        }

      DB::commit();

      return response()->json([
        'status' => 1,
        'message' => 'success',
        'data' => new OfferResource($offer)
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
      'offer_id' => 'required',
    ]);

    if ($validator->fails()){
      return response()->json([
          'status' => 0,
          'message' => $validator->errors()->first()
        ]
      );
    }

    try{

      $offer = Offer::findOrFail($request->offer_id);

      $offer->delete();

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
      'offer_id' => 'required',
    ]);

    if ($validator->fails()){
      return response()->json([
          'status' => 0,
          'message' => $validator->errors()->first()
        ]
      );
    }

    try{

      $offer = Offer::withTrashed()->findOrFail($request->offer_id);

      $offer->restore();

      return response()->json([
        'status' => 1,
        'message' => 'success',
        'data' => new OfferResource($offer)
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

    $offers = Offer::orderBy('created_at','DESC');

    if($request->has('search')){
      $offers = $offers->where('name', 'like', '%' . $request->search . '%')
                      ->orwhere('name_en', 'like', '%' . $request->search . '%');
    }

    $offers = $offers->paginate(10);

    return response()->json([
      'status' => 1,
      'message' => 'success',
      'data' => new PaginatedOfferCollection($offers)
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
