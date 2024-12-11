<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategoryOffer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
      'offer_id',
      'category_id',
    ];

    public function offers(){
      return $this->belongsTo(Offer::class);
    }

    public function categories(){
      return $this->belongsTo(Category::class);
    }
}
