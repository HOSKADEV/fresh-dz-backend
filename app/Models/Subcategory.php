<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \Askedio\SoftCascade\Traits\SoftCascadeTrait;

class Subcategory extends Model
{
    use HasFactory,SoftDeletes,SoftCascadeTrait;

    protected $fillable = [
      'category_id',
      'name',
    ];

    protected $casts = [
      'category_id' => 'integer',
  ];

    protected $softCascade = ['products'];


    public function category(){
      return $this->belongsTo(Category::class);
    }

    public function products(){
      return $this->hasMany(Product::class);
    }

}
