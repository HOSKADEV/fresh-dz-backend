<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UnitController extends Controller
{
  public function index(){
    return view('content.units.list');
  }

  public function create(Request $request){
    $validator = Validator::make($request->all(), [
      'name_ar' => 'required|string',
      'name_en' => 'required|string',
    ]);

    if ($validator->fails()) {
      return response()->json([
        'status'=> 0,
        'message' => $validator->errors()->first()
      ]);
    }
    try{


      $unit = Unit::create($request->except('image'));

      return response()->json([
        'status' => 1,
        'message' => 'success',
        'data' => $unit
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
      'unit_id' => 'required',
      'name_ar' => 'sometimes|string',
      'name_en' => 'sometimes|string',
    ]);

    if ($validator->fails()){
      return response()->json([
          'status' => 0,
          'message' => $validator->errors()->first()
        ]
      );
    }

    try{

      $unit = Unit::findOrFail($request->unit_id);

      $unit->update($request->except('unit_id'));

      return response()->json([
        'status' => 1,
        'message' => 'success',
        'data' => $unit
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
      'unit_id' => 'required',
    ]);

    if ($validator->fails()){
      return response()->json([
          'status' => 0,
          'message' => $validator->errors()->first()
        ]
      );
    }

    try{

      $unit = Unit::findOrFail($request->unit_id);

      if($unit->products()->count()){
        throw new Exception(__('Prohibited action'));
      }

      $unit->delete();

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
      'unit_id' => 'required',
    ]);

    if ($validator->fails()){
      return response()->json([
          'status' => 0,
          'message' => $validator->errors()->first()
        ]
      );
    }

    try{

      $unit = Unit::withTrashed()->findOrFail($request->unit_id);

      $unit->restore();

      return response()->json([
        'status' => 1,
        'message' => 'success',
        'data' => $unit
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

    $units = Unit::orderBy('created_at','DESC');

    if($request->has('search')){

      $units = $units->where('name_ar', 'like', '%' . $request->search . '%')
                    ->orwhere('name_en', 'like', '%' . $request->search . '%');
    }

    $units = $units->get();

    //return($units);

    return response()->json([
      'status' => 1,
      'message' => 'success',
      'data' => $units
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
