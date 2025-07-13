<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Group;
use App\Models\Element;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use App\Models\Subsubcategory;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\GroupResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\PaginatedGroupCollection;

class GroupController extends Controller
{
  public function index()
  {
    $subcategories = Subcategory::all();
    return view('content.groups.list')
      ->with('subcategories', $subcategories);
  }
  public function create(Request $request)
  {

    $validator = Validator::make($request->all(), [
      ////'name' => 'required|string',
      'name_ar' => 'required|string',
      'name_en' => 'sometimes|nullable|string',
      'name_fr' => 'sometimes|nullable|string',
      'subcategories' => ['required', 'array'],
      'subcategories.*' => 'distinct'
    ]);

    if ($validator->fails()) {
      return response()->json([
        'status' => 0,
        'message' => $validator->errors()->first()
      ]);
    }

    try {

      DB::beginTransaction();

      $group = Group::create($request->except('subcategories'));

      foreach ($request->subcategories as $subcategory) {
        $subcategory = Subcategory::findOrfail($subcategory);
        Element::create(['group_id' => $group->id, 'subcategory_id' => $subcategory->id]);
      }

      DB::commit();

      return response()->json([
        'status' => 1,
        'message' => 'success',
        'data' => new GroupResource($group)
      ]);

    } catch (Exception $e) {
      DB::rollBack();
      return response()->json(
        [
          'status' => 0,
          'message' => $e->getMessage()
        ]
      );
    }
  }

  public function update(Request $request)
  {

    $validator = Validator::make($request->all(), [
      'group_id' => 'required',
      ////'name' => 'required|string',
      'name_ar' => 'sometimes|string',
      'name_en' => 'sometimes|nullable|string',
      'name_fr' => 'sometimes|nullable|string',
      'subcategories' => ['sometimes', 'array'],
      'subcategories.*' => 'distinct'
    ]);

    if ($validator->fails()) {
      return response()->json(
        [
          'status' => 0,
          'message' => $validator->errors()->first()
        ]
      );
    }

    try {

      $group = Group::findOrFail($request->group_id);

      DB::beginTransaction();

      $group->update($request->except('group_id', 'subcategories'));

      if ($request->has('subcategories')) {
        $group->elements()->delete();
        $elements = $request->subcategories;
        array_walk($elements, function (&$item, $key) use ($group) {
          $item = [
            'group_id' => $group->id,
            'subcategory_id' => $item
          ];
        });

        Element::insert($elements);
      }

      DB::commit();

      return response()->json([
        'status' => 1,
        'message' => 'success',
        'data' => new GroupResource($group)
      ]);

    } catch (Exception $e) {
      DB::rollBack();
      return response()->json(
        [
          'status' => 0,
          'message' => $e->getMessage()
        ]
      );
    }

  }

  public function delete(Request $request)
  {

    $validator = Validator::make($request->all(), [
      'group_id' => 'required',
    ]);

    if ($validator->fails()) {
      return response()->json(
        [
          'status' => 0,
          'message' => $validator->errors()->first()
        ]
      );
    }

    try {

      $group = Group::findOrFail($request->group_id);

      $group->delete();

      return response()->json([
        'status' => 1,
        'message' => 'success',
      ]);

    } catch (Exception $e) {
      return response()->json(
        [
          'status' => 0,
          'message' => $e->getMessage()
        ]
      );
    }

  }

  public function restore(Request $request)
  {

    $validator = Validator::make($request->all(), [
      'group_id' => 'required',
    ]);

    if ($validator->fails()) {
      return response()->json(
        [
          'status' => 0,
          'message' => $validator->errors()->first()
        ]
      );
    }

    try {

      $group = Group::withTrashed()->findOrFail($request->group_id);

      $group->restore();

      return response()->json([
        'status' => 1,
        'message' => 'success',
        'data' => new GroupResource($group)
      ]);

    } catch (Exception $e) {
      return response()->json(
        [
          'status' => 0,
          'message' => $e->getMessage()
        ]
      );
    }

  }

  public function get(Request $request)
  {  //paginated
    $validator = Validator::make($request->all(), [
      'search' => 'sometimes|string',
    ]);

    if ($validator->fails()) {
      return response()->json(
        [
          'status' => 0,
          'message' => $validator->errors()->first()
        ]
      );
    }

    try {

      $groups = Group::orderBy('created_at', 'DESC');

      if ($request->has('search')) {
        $groups = $groups->where('name', 'like', '%' . $request->search . '%')
          ->orwhere('name_en', 'like', '%' . $request->search . '%');
      }

      $groups = $groups->paginate(10);

      return response()->json([
        'status' => 1,
        'message' => 'success',
        'data' => new PaginatedGroupCollection($groups)
      ]);

    } catch (Exception $e) {
      return response()->json(
        [
          'status' => 0,
          'message' => $e->getMessage()
        ]
      );
    }

  }
}
