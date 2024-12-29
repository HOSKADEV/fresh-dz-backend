<?php

namespace App\Models;

use Askedio\SoftCascade\Traits\SoftCascadeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notice extends Model
{
  use HasFactory, SoftDeletes, SoftCascadeTrait;

  protected $fillable = [
    'title_ar',
    'title_en',
    'content_ar',
    'content_en',
    'type',
    'priority',
    'metadata'
  ];

  protected $softCascade = ['notifications'];


  public function notifications()
  {
    return $this->hasMany(Notification::class);
  }

  public function title($lang = 'ar')
  {
    return match ($lang) {
      'ar' => $this->title_ar,
      'en' => $this->title_en,
      default => $this->title_ar,
    };
  }

  public function content($lang = 'ar')
  {
    return match ($lang) {
      'ar' => $this->content_ar,
      'en' => $this->content_en,
      default => $this->content_ar,
    };
  }

  public static function ProfileNotice(string $action, string $value): self
  {
    $key = "notices.profile.{$action}.{$value}";

    return self::create([
      'title_en' => trans("{$key}.title", [], 'en'),
      'title_ar' => trans("{$key}.title", [], 'ar'),
      'content_en' => trans("{$key}.content", [], 'en'),
      'content_ar' => trans("{$key}.content", [], 'ar'),
      'type' => 1,
      'priority' => 0,
      'metadata' => json_encode([$action => $value])
    ]);
  }

  public static function CouponNotice(string $code, int $discount): self
  {
    return self::create([
      'title_en' => trans('notices.coupon.title', [], 'en'),
      'title_ar' => trans('notices.coupon.title', [], 'ar'),
      'content_en' => trans('notices.coupon.content', ['discount' => $discount], 'en'),
      'content_ar' => trans('notices.coupon.content', ['discount' => $discount], 'ar'),
      'type' => 2,
      'priority' => 0,
      'metadata' => json_encode(['code' => $code])
    ]);
  }

  public static function OrderNotice(int $orderId, string $status): self
  {
    $key = "notices.order.{$status}";

    return self::create([
      'title_en' => trans("{$key}.title", [], 'en'),
      'title_ar' => trans("{$key}.title", [], 'ar'),
      'content_en' => trans("{$key}.content", ['order_id' => $orderId], 'en'),
      'content_ar' => trans("{$key}.content", ['order_id' => $orderId], 'ar'),
      'type' => 3,
      'priority' => $status === 'delivered' ? 1 : 0,
      'metadata' => json_encode([
        'order_id' => $orderId,
        'status' => $status
      ])
    ]);
  }

  public static function ProductNotice(int $productId, string $productName, string $image, string $status): self
  {
    $key = "notices.product.{$status}";

    return self::create([
      'title_en' => trans("{$key}.title", [], 'en'),
      'title_ar' => trans("{$key}.title", [], 'ar'),
      'content_en' => trans("{$key}.content", ['product_name' => $productName], 'en'),
      'content_ar' => trans("{$key}.content", ['product_name' => $productName], 'ar'),
      'type' => 4,
      'priority' => 1,
      'metadata' => json_encode([
        'product_id' => $productId,
        'product_name' => $productName,
        'image' => $image,
        'status' => $status
      ])
    ]);
  }
}
