<?php

namespace App\Http\Controllers;

use App\Models\Set;
use Illuminate\Http\Request;

class SetController extends Controller
{
  public function shipping(Request $request){
    $set = Set::where('name','shipping')->first();

    $set->status = $request->status;

    $set->save();

    return response()->json([
      'status' => 1,
      'message' => 'success',
    ]);
  }
}
