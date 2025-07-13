<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Askedio\SoftCascade\Traits\SoftCascadeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Group extends Model
{
  use HasFactory,SoftDeletes;

  protected $fillable = [
    'name_ar',
    'name_en',
    'name_fr',
  ];

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

  public function elements(){
    return $this->hasMany(Element::class);
  }

  public function subcategories(){
    //return Category::whereIn('id',$this->elements->pluck('category_id')->toArray());
    return $this->hasManyThrough(Subcategory::class, Element::class, 'group_id','id','id','subcategory_id');
  }

  public function section(){
    return Section::withTrashed()->where('type','group')->where('element',$this->id)->first();
  }
}
