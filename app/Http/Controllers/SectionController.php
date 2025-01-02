<?php

namespace App\Http\Controllers;

use App\Http\Resources\PaginatedSectionCollection;
use App\Http\Resources\SectionCollection;
use App\Http\Resources\SectionResource;
use App\Http\Resources\VersionResource;
use App\Models\Section;
use App\Rules\Moveable;
use Auth;
use DB;
use Exception;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Pluralizer;
use Illuminate\Validation\Rule;
use Session;

class SectionController extends Controller
{

    public function index(){
      return view('content.sections.list');
    }
    public function add(Request $request){
      $validator = Validator::make($request->all(), [
        'type' => 'required|in:offer,family,group,ad',
        'element' => ['numeric', Rule::exists(Pluralizer::plural($request->type),'id')],
      ]);

      if ($validator->fails()){
        return response()->json([
            'status' => 0,
            'message' => $validator->errors()->first()
          ]
        );
      }

      try{
        $request->merge([ 'rank' => Section::withTrashed()->all()->count() + 1 ]);

        $section = Section::create($request->all());


        return response()->json([
          'status' => 1,
          'message' => 'success',
          'data' => new SectionResource($section)
        ]);

      }catch(Exception $e){
        return response()->json([
          'status' => 0,
          'message' => $e->getMessage()
        ]
      );
      }

  }

  public function remove(Request $request){

    $validator = Validator::make($request->all(), [
      'section_id' => ['required','numeric',Rule::exists('sections','id')->where(function (Builder $query) {
        return $query->where('deleteable', 1);
      }),],
    ]);

    if ($validator->fails()){
      return response()->json([
          'status' => 0,
          'message' => $validator->errors()->first()
        ]
      );
    }

    try{

      DB::beginTransaction();

      $section = Section::withTrashed()->findOrFail($request->section_id);

      $rank = $section->rank;

      $section->forceDelete();

      if($rank){
        $sections = Section::withTrashed()->where('rank','>',$rank)->orderBy('rank','ASC')->get();

        foreach($sections as $section){
          $section->rank -= 1;
          $section->save();
        }
      }

      DB::commit();

      return response()->json([
        'status' => 1,
        'message' => 'success',
      ]);

    }catch(Exception $e){
      DB::rollBack();
      return response()->json([
        'status' => 0,
        'message' => $e->getMessage()
      ]
    );
    }

  }

  public function switch(Request $request){

    $validator = Validator::make($request->all(), [
      'section_id' => ['required','numeric',Rule::exists('sections','id')->where(function (Builder $query) {
        return $query->where('moveable', 1);
      }),],
      'rank' => ['required','numeric',Rule::exists('sections','rank')->where(function (Builder $query) {
        return $query->where('moveable', 1);
      }),],
    ]);

    if ($validator->fails()){
      return response()->json([
          'status' => 0,
          'message' => $validator->errors()->first()
        ]
      );
    }

    try{

      DB::beginTransaction();

        $section_1 = Section::withTrashed()->findOrFail($request->section_id);

        $section_2 = Section::withTrashed()->where('rank',$request->rank)->first();

        $rank_1 = $section_1->rank;
        $rank_2 = $section_2->rank;

        $section_1->update(['rank' => null]);
        $section_2->update(['rank' => $rank_1]);
        $section_1->update(['rank' => $rank_2]);

      DB::commit();

      return response()->json([
        'status' => 1,
        'message' => 'success',
      ]);

    }catch(Exception $e){
      DB::rollBack();
      return response()->json([
        'status' => 0,
        'message' => $e->getMessage()
      ]
    );
    }

  }

  public function insert(Request $request){
    $validator = Validator::make($request->all(), [
      'section_id' => 'required|numeric|exists:sections,id',
      'rank' => ['required','numeric','exists:sections,rank', new Moveable($request)],
    ]);

    if ($validator->fails()){
      return response()->json([
          'status' => 0,
          'message' => $validator->errors()->first()
        ]
      );
    }

    //return($request->all());
    try{



      $section = Section::withTrashed()->findOrFail($request->section_id);


     if($section->rank != $request->rank) {

      DB::beginTransaction();

      $offset = ($section->rank > $request->rank)? 1 : -1;
      $order = ($section->rank > $request->rank)? 'DESC' : 'ASC';
      $rank_min = min([$request->rank,$section->rank]);
      $rank_max = max([$request->rank,$section->rank]);

      $sections = Section::withTrashed()->whereBetween('rank',[$rank_min,$rank_max])
      ->whereNot('id',$request->section_id)->orderBy('rank',$order)->get();

      //return ($sections);

      $section->update(['rank' => null]);

      foreach($sections as $sect){
        $sect->rank += $offset;
        $sect->save();
      }


      DB::commit();

      $section->update(['rank' => $request->rank]);

    }




      return response()->json([
        'status' => 1,
        'message' => 'success',
      ]);

    }catch(Exception $e){
      DB::rollBack();
      return response()->json([
        'status' => 0,
        'message' => $e->getMessage()
      ]
    );
    }

  }

  public function delete(Request $request){

    $validator = Validator::make($request->all(), [
      'section_id' => 'required|numeric|exists:sections,id'
    ]);

    if ($validator->fails()){
      return response()->json([
          'status' => 0,
          'message' => $validator->errors()->first()
        ]
      );
    }

    try{

      DB::beginTransaction();

      $section = Section::findOrFail($request->section_id);

      $section->delete();

      DB::commit();

      return response()->json([
        'status' => 1,
        'message' => 'success',
      ]);

    }catch(Exception $e){
      DB::rollBack();
      return response()->json([
        'status' => 0,
        'message' => $e->getMessage()
      ]
    );
    }

  }

  public function restore(Request $request){

    $validator = Validator::make($request->all(), [
      'section_id' => 'required',
    ]);

    if ($validator->fails()){
      return response()->json([
          'status' => 0,
          'message' => $validator->errors()->first()
        ]
      );
    }

    try{

      $section = Section::withTrashed()->findOrFail($request->section_id);

      $section->restore();

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

  public function get(Request $request){  //paginated
    //dd($request->bearerToken());
    try{

      $sections = Section::orderBy('rank','Asc')->paginate(5);

      Session::put('lang', $request->header('Accept-language','ar'));

      return response()->json([
        'status' => 1,
        'message' => 'success',
        'data' => new PaginatedSectionCollection($sections)
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
