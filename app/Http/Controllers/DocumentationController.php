<?php

namespace App\Http\Controllers;

use App\Models\Documentation;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Kreait\Firebase\Messaging\CloudMessage;

class DocumentationController extends Controller
{

    public function index(Request $request){

      if($request->route()->getName() == 'documentation_privacy_policy'){
        return view('content.documentation.index')->with('documentation',Documentation::privacy_policy());
      }elseif($request->route()->getName() == 'documentation_about'){
        return view('content.documentation.index')->with('documentation',Documentation::about());
      }else{
        return redirect()->route('pages-misc-error');
      }

    }

    public function privacy()
    {
      $privacy_policy = Documentation::privacy_policy();

      $data = $privacy_policy->content(session('locale'));

      $title = __($privacy_policy->name);

      return view('content.pages.privacy-policy',compact('data','title'));
    }

    public function delete_account()
    {
      return view('content.pages.delete-account');
    }

    public function update(Request $request){

      //dd($request->all());

      $validator = Validator::make($request->all(), [
        'name' => 'required|string',
        'content_ar' => 'sometimes|string',
        'content_en' => 'sometimes|string',
      ]);

      if ($validator->fails()){
        return response()->json([
            'status' => 0,
            'message' => $validator->errors()->first()
          ]
        );
      }

      try{

        Documentation::updateOrInsert(
          ['name' => $request->name],
          $request->all()
        );

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
    public function privacy_policy(Request $request){
      try{

       $lang = $request->header('Accept-language','ar');
        $privacy_policy = Documentation::privacy_policy();

        return response()->json([
          'status' => 1,
          'message' => 'success',
          'data' => ['content' => $privacy_policy->content($lang)]
        ]);

      }catch(Exception $e){
        return response()->json([
          'status' => 0,
          'message' => $e->getMessage()
        ]
      );
      }
    }

    public function about(Request $request){
      try{

       $lang = $request->header('Accept-language','ar');
        $about = Documentation::about();

        return response()->json([
          'status' => 1,
          'message' => 'success',
          'data' => ['content' => $about->content($lang)]
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
