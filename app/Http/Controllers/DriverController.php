<?php

namespace App\Http\Controllers;

use App\Http\Resources\DriverCollection;
use App\Http\Resources\DriverResource;
use App\Http\Resources\PaginatedDriverCollection;
use App\Models\Driver;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class DriverController extends Controller
{
  public function index(){
    return view('content.drivers.list');
  }
  public function create(Request $request){
    $validator = Validator::make($request->all(), [
      'firstname' => 'required|string',
      'lastname' => 'required|string',
      'phone' => 'required|min:10|unique:drivers,phone',
      'image' => 'sometimes|mimetypes:image/*'
    ]);

    if ($validator->fails()) {
      return response()->json([
        'status'=> 0,
        'message' => $validator->errors()->first()
      ]);
    }
    try{


      $driver = Driver::create($request->except('image'));

      if($request->hasFile('image')){
        $url = $request->image->store('/uploads/drivers/images','upload');

        /* $file = $request->image;
        $name = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();

        $filename = 'drivers/' . $driver->id . '/' . md5(time().$name) . '.' . $extension;

        $url = $this->firestore($file->get(),$filename); */

        $driver->image = $url;
        $driver->save();
      }


      return response()->json([
        'status' => 1,
        'message' => 'success',
        'data' => new DriverResource($driver)
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
      'driver_id' => 'required',
      'firstname' => 'sometimes|string',
      'lastname' => 'sometimes|string',
      'phone' => ['sometimes','min:10',Rule::unique('drivers')->ignore($request->driver_id)],
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

      $driver = Driver::findOrFail($request->driver_id);

      $driver->update($request->except('image','driver_id' ));

      if($request->hasFile('image')){
        $url = $request->image->store('/uploads/drivers/images','upload');

        /* $file = $request->image;
        $name = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();

        $filename = 'drivers/' . $driver->id . '/' . md5(time().$name) . '.' . $extension;

        $url = $this->firestore($file->get(),$filename); */

        $driver->image = $url;
        $driver->save();
      }

      return response()->json([
        'status' => 1,
        'message' => 'success',
        'data' => new DriverResource($driver)
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
      'driver_id' => 'required',
    ]);

    if ($validator->fails()){
      return response()->json([
          'status' => 0,
          'message' => $validator->errors()->first()
        ]
      );
    }

    try{

      $driver = Driver::findOrFail($request->driver_id);

      $driver->delete();

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
      'driver_id' => 'required',
    ]);

    if ($validator->fails()){
      return response()->json([
          'status' => 0,
          'message' => $validator->errors()->first()
        ]
      );
    }

    try{

      $driver = Driver::withTrashed()->findOrFail($request->driver_id);

      $driver->restore();

      return response()->json([
        'status' => 1,
        'message' => 'success',
        'data' => new DriverResource($driver)
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

    $drivers = Driver::orderBy('created_at','DESC');



    if($request->has('search')){

      $drivers = $drivers->where('firstname', 'like', '%' . $request->search . '%')
                          ->orWhere('lastname', 'like', '%' . $request->search . '%');
    }

    $drivers = $drivers->paginate(10);

    return response()->json([
      'status' => 1,
      'message' => 'success',
      'data' => new PaginatedDriverCollection($drivers)
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
