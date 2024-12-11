<?php

namespace App\Http\Controllers;

use App\Http\Resources\FamilyCollection;
use App\Http\Resources\FamilyResource;
use App\Http\Resources\PaginatedFamilyCollection;
use App\Models\Category;
use App\Models\Family;
use App\Models\Member;
use App\Rules\SizeIn;
use DB;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class FamilyController extends Controller
{

  public function index(){
    $categories = Category::all();
    return view('content.families.list')
    ->with('categories',$categories);
  }
  public function create(Request $request){

    $validator = Validator::make($request->all(), [
      'name' => 'required|string',
      'name_en' => 'sometimes|string',
      'categories' => ['required', 'array', new SizeIn([4,6])],
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

        $family = Family::create($request->except('categories'));

        foreach($request->categories as $category){
          $category = Category::findOrfail($category);
          Member::create(['family_id' => $family->id, 'category_id' => $category->id]);
        }

      DB::commit();

      return response()->json([
        'status' => 1,
        'message' => 'success',
        'data' => new FamilyResource($family)
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
      'family_id' => 'required',
      'name' => 'sometimes|string',
      'name_en' => 'sometimes|string',
      'categories' => ['sometimes', 'array', new SizeIn([4,6])],
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

      $family = Family::findOrFail($request->family_id);

      DB::beginTransaction();

        $family->update($request->except('family_id' ));

        if($request->has('categories')){
          foreach($family->members as $member){
            $member->forceDelete();
          }
          foreach($request->categories as $category){
            $category = Category::findOrfail($category);
            Member::create(['family_id' => $family->id, 'category_id' => $category->id]);
          }
        }

      DB::commit();

      return response()->json([
        'status' => 1,
        'message' => 'success',
        'data' => new FamilyResource($family)
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
      'family_id' => 'required',
    ]);

    if ($validator->fails()){
      return response()->json([
          'status' => 0,
          'message' => $validator->errors()->first()
        ]
      );
    }

    try{

      $family = Family::findOrFail($request->family_id);

      $family->delete();

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
      'family_id' => 'required',
    ]);

    if ($validator->fails()){
      return response()->json([
          'status' => 0,
          'message' => $validator->errors()->first()
        ]
      );
    }

    try{

      $family = Family::withTrashed()->findOrFail($request->family_id);

      $family->restore();

      return response()->json([
        'status' => 1,
        'message' => 'success',
        'data' => new FamilyResource($family)
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

    $families = Family::orderBy('created_at','DESC');

    if($request->has('search')){
      $families = $families->where('name', 'like', '%' . $request->search . '%')
                          ->orwhere('name_en', 'like', '%' . $request->search . '%');
    }

    $families = $families->paginate(10);

    return response()->json([
      'status' => 1,
      'message' => 'success',
      'data' => new PaginatedFamilyCollection($families)
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
