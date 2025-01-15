<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
      'user_id',
      'region_id',
      'name',
      'address',
      'longitude',
      'latitude'
      ];

      public function user() {
        return $this->belongsTo(User::class);
      }

      public function region() {
        return $this->belongsTo(Region::class);
      }
}
