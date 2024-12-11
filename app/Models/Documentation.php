<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Documentation extends Model
{
    use HasFactory;

    protected $fillable = [
      'name',
      'content_ar',
      'content_en',
    ];

    public static function privacy_policy(){
      $privacy_policy = Documentation::where('name' , 'privacy_policy')->first();
      if(is_null($privacy_policy)){
        $privacy_policy = Documentation::create(['name' => 'privacy_policy']);
      }

      return $privacy_policy;
    }

    public static function about(){
      $about = Documentation::where('name' , 'about')->first();
      if(is_null($about)){
        $about = Documentation::create(['name' => 'about']);
      }

      return $about;
    }

    public function content($lang = 'ar'){
      if($lang == 'en'){
        return $this->content_en;
      }

      return $this->content_ar;
    }
}
