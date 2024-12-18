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
    'name',
    'name_en',
    //'image',
  ];

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
