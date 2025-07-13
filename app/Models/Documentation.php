<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Documentation extends Model
{
  use HasFactory;

  protected $fillable = [
    'name',
    'content_ar',
    'content_en',
    'content_fr',
  ];

  public static function privacy_policy()
  {
    $privacy_policy = Documentation::where('name', 'privacy_policy')->first();
    if (is_null($privacy_policy)) {
      $privacy_policy = Documentation::create(['name' => 'privacy_policy']);
    }

    return $privacy_policy;
  }

  public static function about()
  {
    $about = Documentation::where('name', 'about')->first();
    if (is_null($about)) {
      $about = Documentation::create(['name' => 'about']);
    }

    return $about;
  }
  public function content($lang = null)
  {
    $lang = $lang ?? session('locale', app()->getLocale());

    return match ($lang) {
      'en' => $this->content_en ?? $this->content_ar,
      'fr' => $this->content_fr ?? $this->content_ar,
      'ar' => $this->content_ar,
      default => $this->content_ar
    };
  }

  public function getContentAttribute()
  {
    return $this->content();
  }
}
