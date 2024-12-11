<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notification extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
      'notice_id',
      'user_id',
      'is_read',
      'read_at',
  ];

  public function user(){
    return $this->belongsTo(User::class);
  }

  public function notice(){
    return $this->belongsTo(Notice::class);
  }
}
