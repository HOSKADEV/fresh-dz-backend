<?php

namespace App\Http\Controllers;

use App\Models\Set;
use Illuminate\Http\Request;

class SetController extends Controller
{
  public function index(){
    $settings = Set::pluck('value', 'name')->toArray();

    return view('content.settings.index')
    ->with('settings',$settings);


  }

  public function get(){

    $settings = Set::pluck('value', 'name');

    return response()->json([
        'status' => 1,
        'message' => 'success',
        'data' => $settings
    ]);
  }

  public function update(Request $request){

    foreach ($request->all() as $key => $value) {
      Set::updateOrInsert(['name' => $key], ['value' => $value]);
    }

    return response()->json([
        'status' => 1,
        'message' => 'success',
    ]);
  }
}
