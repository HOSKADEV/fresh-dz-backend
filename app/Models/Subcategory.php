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
      'name_ar',
      'name_en',
      'name_fr',
    ];

    protected $casts = [
      'category_id' => 'integer',
    ];

    protected $softCascade = ['products'];

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

    public function category(){
      return $this->belongsTo(Category::class);
    }

    public function products(){
      return $this->hasMany(Product::class);
    }

    public function elements(){
      return $this->hasMany(Element::class);
    }

}
