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
      'name',
      'name_en',
    ];

    protected $softCascade = ['category_offers'];

    public function category_offers(){
      return $this->hasMany(CategoryOffer::class);
    }

    public function categories(){
      return Category::whereIn('id',$this->category_offers()->pluck('category_id')->toArray());
    }

    public function section(){
      return Section::where('type','offer')->where('element',$this->id)->first();
    }
}
