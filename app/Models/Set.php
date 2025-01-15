<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Set extends Model
{
  use HasFactory;

  protected $fillable = [
    'name',
    'value',
  ];

  public static function calculateDeliveryPrice($start_point, $delivery_point, $purchase_amount = 0)
  {

    if ($purchase_amount >= self::where('name', 'free_threshold')->value('value')) {
        return 0;
    }
      $distance = self::calcDistance($start_point, $delivery_point);

      return self::getDeliveryPrice($distance);
  }

  public static function calcDistance($p1, $p2)
  {
      $r = 6378137;
      $dLat = deg2rad($p2['latitude'] - $p1['latitude']);
      $dLong = deg2rad($p2['longitude'] - $p1['longitude']);

      $a = sin($dLat / 2) * sin($dLat / 2) +
          cos(deg2rad($p1['latitude'])) * cos(deg2rad($p2['latitude'])) *
          sin($dLong / 2) * sin($dLong / 2);
      $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
      return $r * $c;
  }

  public static function getDeliveryPrice($distance)
  {
      $true_price = ($distance / 1000) * self::where('name', 'price_per_km')->value('value');
      return min(
          max($true_price, self::where('name', 'min_price')->value('value')),
          self::where('name', 'max_price')->value('value')
      );
  }

  public static function chargily_credentials(){
    return [
      'mode' => self::where('name','chargily_mode')->value('value'),
      'public' => self::where('name','chargily_pk')->value('value'),
      'secret' => self::where('name','chargily_sk')->value('value'),
    ];
  }

  public static function pusher_credentials(){
    return [
      'instanceId' => self::where('name','pusher_instance_id')->value('value'),
      'secretKey' => self::where('name','pusher_secret_key')->value('value'),
    ];
  }


}
