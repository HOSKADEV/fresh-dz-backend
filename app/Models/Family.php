<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \Askedio\SoftCascade\Traits\SoftCascadeTrait;

class Family extends Model
{
  use HasFactory,SoftDeletes,SoftCascadeTrait;

    protected $fillable = [
      'name',
      'name_en',
      //'image',
    ];

    protected $softCascade = ['members'];

    public function members(){
      return $this->hasMany(Member::class);
    }

    public function categories(){
      return Category::whereIn('id',$this->members->pluck('category_id')->toArray());
    }

    public function section(){
      return Section::where('type','family')->where('element',$this->id)->first();
    }
}
