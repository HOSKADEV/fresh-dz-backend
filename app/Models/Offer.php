<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \Askedio\SoftCascade\Traits\SoftCascadeTrait;

class Offer extends Model
{
  use HasFactory,SoftDeletes,SoftCascadeTrait;

    protected $fillable = [
      'name_ar',
      'name_en',
      'name_fr',
    ];

    protected $softCascade = ['category_offers'];

  public function name($lang = null)
  {
    $lang = $lang ?? session('locale', app()->getLocale());

        return match($lang) {
            'en' => $this->name_en ?? $this->name_ar,
            'fr' => $this->name_fr ?? $this->name_ar,
            'ar' => $this->name_ar,
            default => $this->name_ar
        };
    }

    public function getNameAttribute()
    {
        return $this->name();
    }

    public function category_offers(){
      return $this->hasMany(CategoryOffer::class);
    }

    public function categories(){
      return Category::whereIn('id',$this->category_offers()->pluck('category_id')->toArray());
    }

    public function section(){
      return Section::withTrashed()->where('type','offer')->where('element',$this->id)->first();
    }
}
