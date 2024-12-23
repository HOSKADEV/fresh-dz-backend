<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Region;
use Illuminate\Http\Request;


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
}
