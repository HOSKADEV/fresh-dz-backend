<?php

namespace App\Http\Controllers;

use App\Http\Resources\PaginatedSubcategoryCollection;
use App\Http\Resources\SubcategoryCollection;
use App\Http\Resources\SubcategoryResource;
use App\Models\Category;
use App\Models\Subcategory;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubcategoryController extends Controller
{

  public function index(){
    $categories = Category::all();
    return view('content.subcategories.list')
    ->with('categories',$categories);
  }
  public function create(Request $request){
    $validator = Validator::make($request->all(), [
      'category_id' => 'required|exists:categories,id',
      'name' => 'required|string',
    ]);

    if ($validator->fails()) {
      return response()->json([
        'status'=> 0,
        'message' => $validator->errors()->first()
      ]);
    }
    try{

      $subcategory = Subcategory::create($request->all());


      return response()->json([
        'status' => 1,
        'message' => 'success',
        'data' => new SubcategoryResource($subcategory)
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
      'subcategory_id' => 'required',
      'category_id' => 'sometimes|exists:categories,id',
      'name' => 'sometimes|string',
    ]);

    if ($validator->fails()){
      return response()->json([
          'status' => 0,
          'message' => $validator->errors()->first()
        ]
      );
    }

    try{

      $subcategory = Subcategory::findOrFail($request->subcategory_id);

      $subcategory->update($request->except('subcategory_id' ));


      return response()->json([
        'status' => 1,
        'message' => 'success',
        'data' => new SubcategoryResource($subcategory)
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
      'subcategory_id' => 'required',
    ]);

    if ($validator->fails()){
      return response()->json([
          'status' => 0,
          'message' => $validator->errors()->first()
        ]
      );
    }

    try{

      $subcategory = Subcategory::findOrFail($request->subcategory_id);

      $subcategory->delete();

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
      'subcategory_id' => 'required',
    ]);

    if ($validator->fails()){
      return response()->json([
          'status' => 0,
          'message' => $validator->errors()->first()
        ]
      );
    }

    try{

      $subcategory = Subcategory::withTrashed()->findOrFail($request->subcategory_id);

      $subcategory->restore();

      return response()->json([
        'status' => 1,
        'message' => 'success',
        'data' => new SubcategoryResource($subcategory)
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
      'category_id' => 'sometimes',
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

    $subcategories = Subcategory::orderBy('created_at','DESC');

    if($request->has('category_id')){

      $category = Category::findOrFail($request->category_id);
      $category_subs = $category->subcategories()->pluck('id')->toArray();
      $subcategories = $subcategories->whereIn('id',$category_subs);
    }

    if($request->has('search')){

      $subcategories = $subcategories->where('name', 'like', '%' . $request->search . '%');
    }

    if($request->has('all')){
      $subcategories = $subcategories->get();
      return response()->json([
        'status' => 1,
        'message' => 'success',
        'data' => new SubcategoryCollection($subcategories)
      ]);
    }
      $subcategories = $subcategories->paginate(10);




    return response()->json([
      'status' => 1,
      'message' => 'success',
      'data' => new PaginatedSubcategoryCollection($subcategories)
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
