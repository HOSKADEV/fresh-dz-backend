<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryCollection;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\PaginatedCategoryCollection;
use App\Models\Category;
use App\Models\Family;
use App\Models\Offer;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index(){
      return view('content.categories.list');
    }
    public function create(Request $request){
      $validator = Validator::make($request->all(), [
        'name' => 'required|string',
        'image' => 'sometimes|mimetypes:image/*'
      ]);

      if ($validator->fails()) {
        return response()->json([
          'status'=> 0,
          'message' => $validator->errors()->first()
        ]);
      }
      try{


        $category = Category::create($request->except('image'));

        if($request->hasFile('image')){
          $url = $request->image->store('/uploads/categories/images','upload');

          /* $file = $request->image;
          $name = $file->getClientOriginalName();
          $extension = $file->getClientOriginalExtension();

          $filename = 'categories/' . $category->id . '/' . md5(time().$name) . '.' . $extension;

          $url = $this->firestore($file->get(),$filename); */

          $category->image = $url;
          $category->save();
        }


        return response()->json([
          'status' => 1,
          'message' => 'success',
          'data' => new CategoryResource($category)
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
        'category_id' => 'required',
        'name' => 'sometimes|string',
        'image' => 'sometimes|mimetypes:image/*'
      ]);

      if ($validator->fails()){
        return response()->json([
            'status' => 0,
            'message' => $validator->errors()->first()
          ]
        );
      }

      try{

        $category = Category::findOrFail($request->category_id);

        $category->update($request->except('image','category_id' ));

        if($request->hasFile('image')){
          $url = $request->image->store('/uploads/categories/images','upload');

          /* $file = $request->image;
          $name = $file->getClientOriginalName();
          $extension = $file->getClientOriginalExtension();

          $filename = 'categories/' . $category->id . '/' . md5(time().$name) . '.' . $extension;

          $url = $this->firestore($file->get(),$filename); */

          $category->image = $url;
          $category->save();
        }

        return response()->json([
          'status' => 1,
          'message' => 'success',
          'data' => new CategoryResource($category)
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
        'category_id' => 'required',
      ]);

      if ($validator->fails()){
        return response()->json([
            'status' => 0,
            'message' => $validator->errors()->first()
          ]
        );
      }

      try{

        $category = Category::findOrFail($request->category_id);

        if($category->members()->count() != 0){
          throw new Exception(__('This category belongs to a family'));
        }

        $category->delete();

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
        'category_id' => 'required',
      ]);

      if ($validator->fails()){
        return response()->json([
            'status' => 0,
            'message' => $validator->errors()->first()
          ]
        );
      }

      try{

        $category = Category::withTrashed()->findOrFail($request->category_id);

        $category->restore();

        return response()->json([
          'status' => 1,
          'message' => 'success',
          'data' => new CategoryResource($category)
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
        'family_id' => 'sometimes',
        'offer_id' => 'sometimes',
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

      $categories = Category::orderBy('created_at','DESC');

      if($request->has('family_id')){

        $family = Family::findOrFail($request->family_id);
        $family_categories = $family->categories()->pluck('id')->toArray();
        $categories = $categories->whereIn('id',$family_categories);
      }

      if($request->has('offer_id')){

        $offer = Offer::findOrFail($request->offer_id);
        $offer_categories = $offer->categories()->pluck('id')->toArray();
        $categories = $categories->whereIn('id',$offer_categories);
      }

      if($request->has('search')){

        $categories = $categories->where('name', 'like', '%' . $request->search . '%');
      }

      if($request->has('all')){
        $categories = $categories->get();
        return response()->json([
          'status' => 1,
          'message' => 'success',
          'data' => new CategoryCollection($categories)
        ]);
      }

      $categories = $categories->paginate(10);

      return response()->json([
        'status' => 1,
        'message' => 'success',
        'data' => new PaginatedCategoryCollection($categories)
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
