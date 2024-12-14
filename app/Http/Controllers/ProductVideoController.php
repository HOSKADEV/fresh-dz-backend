<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Product;
use App\Models\ProductVideo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Pion\Laravel\ChunkUpload\Handler\DropZoneUploadHandler;
use Pion\Laravel\ChunkUpload\Exceptions\UploadMissingFileException;

class ProductVideoController extends Controller
{
  public function index($id)
  {
    $product = Product::findOrFail($id);

    return view('content.products.videos')
      ->with('product', $product);
  }

  public function add(Request $request)
  {
    try {

      // create the file receiver
      $receiver = new FileReceiver($request->video, $request, DropZoneUploadHandler::class);

      // check if the upload is success, throw exception or return response you need
      if ($receiver->isUploaded() === false) {
        throw new UploadMissingFileException();
      }

      // receive the file
      $save = $receiver->receive();

      // check if the upload has finished (in chunk mode it will send smaller files)
      if ($save->isFinished()) {
        $video = $save->getFile();
        /* $extension = $video->getClientOriginalExtension();
        $filename = $video->getClientOriginalName();
        $basename = basename($filename, '.' . $extension);
        $video_url = $video->move('videos/posts/video', $basename . time() . '.' . $extension); */

        $path = $video->store('/uploads/products/videos', 'upload');
        ProductVideo::create([
          'product_id' => $request->product_id,
          'path' => $path,
        ]);


        return response()->json([
          'status' => 1,
          'message' => 'success',
        ]);

      } else {
        throw new Exception();
      }



    } catch (Exception $e) {
      DB::rollBack();
      return response()->json([
        'status' => 0,
        'message' => $e->getMessage(),
      ]);

    }
  }

  public function delete(Request $request)
  {

    $validator = Validator::make($request->all(), [
      'video_id' => 'required|exists:product_videos,id',
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

      $video = ProductVideo::findOrFail($request->video_id);

      if (File::exists($video->path)) {
        File::delete($video->path);
      }

      $video->delete();

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
}
