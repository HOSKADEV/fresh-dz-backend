<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Element extends Model
{
  use HasFactory;

  protected $fillable = [
    'group_id',
    'subcategory_id',
  ];

  public function group(){
    return $this->belongsTo(Group::class);
  }

  public function subcategory(){
    return $this->belongsTo(Subcategory::class);
  }
}
