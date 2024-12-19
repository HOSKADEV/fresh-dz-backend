<?php

namespace App\Models;

use Askedio\SoftCascade\Traits\SoftCascadeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notice extends Model
{
    use HasFactory,SoftDeletes,SoftCascadeTrait;

    protected $fillable = [
      'title_ar',
      'title_en',
      'content_ar',
      'content_en',
      'type',
  ];

  protected $softCascade = ['notifications'];


  public function notifications(){
    return $this->hasMany(Notification::class);
  }

  public function title($lang = 'ar'){
    return match($lang){
      'ar' => $this->title_ar,
      'en' => $this->title_en,
      default => $this->title_ar,
    };
  }

  public function content($lang = 'ar'){
    return match($lang){
      'ar' => $this->content_ar,
      'en' => $this->content_en,
      default => $this->content_ar,
    };
  }
}
