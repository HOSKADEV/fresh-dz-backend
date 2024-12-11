<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Version extends Model
{
    use HasFactory;

    protected $fillable = [
      'platform',
      'version_number',
      'build_number',
      'priority',
      'link'
    ];

    public static function android(){
      $version = Version::where('platform' , 'android')->first();

      if(is_null($version)){
        $version = Version::create(['platform' => 'android']);
      }

      return $version;
    }
    public static function ios(){
      $version = Version::where('platform' , 'ios')->first();

      if(is_null($version)){
        $version = Version::create(['platform' => 'ios']);
      }

      return $version;
    }


}
