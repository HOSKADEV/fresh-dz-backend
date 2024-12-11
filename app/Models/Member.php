<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Member extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
      'family_id',
      'category_id',
    ];

    public function family(){
      return $this->belongsTo(Family::class);
    }

    public function categoryy(){
      return $this->belongsTo(Category::class);
    }
}
