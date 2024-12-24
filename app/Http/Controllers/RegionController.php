<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Region;
use Illuminate\Http\Request;
use App\Http\Resources\RegionCollection;
use Illuminate\Support\Facades\Validator;


class RegionController extends Controller
{

  public function index()
  {
    return view('content.regions.list');
  }
  public function create(Request $request)
  {
    $request->validate([
      'name' => 'required|string|max:255',
      'coordinates' => 'required|array|min:3',
      'coordinates.*' => 'array'
    ]);

    try {

      $region = Region::create([
        'name' => $request->name,
        'boundaries' => json_encode($request->coordinates)
      ]);

      return response()->json([
        'status' => 1,
        'message' => 'success',
      ]);

    } catch (Exception $e) {
      return response()->json([
        'status' => 0,
        'message' => $e->getMessage(),
      ]);

    }
  }

  public function update(Request $request)
  {
    $request->validate([
      'region_id' => 'required|exists:regions,id',
      'name' => 'sometimes|nullable|string|max:255',
      'coordinates' => 'sometimes|nullable|array|min:3',
      'coordinates.*' => 'array'
    ]);

    try {

      $region = Region::find($request->region_id);

      if ($request->coordinates) {
        $region->boundaries = $request->coordinates;
        $region->save();
      }


      return response()->json([
        'status' => 1,
        'message' => 'success',
        'data' => $region
      ]);

    } catch (Exception $e) {
      return response()->json([
        'status' => 0,
        'message' => $e->getMessage(),
      ]);

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

      $regions = Region::orderBy('created_at', 'DESC');

      if ($request->has('search')) {

        $regions = $regions->where('name', 'like', '%' . $request->search . '%');
      }

      $regions = $regions->paginate(5);

      //return($regions);

      return response()->json([
        'status' => 1,
        'message' => 'success',
        'data' => new RegionCollection($regions)
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
