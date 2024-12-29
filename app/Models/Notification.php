<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notification extends Model
{
  use HasFactory, SoftDeletes;

  protected $fillable = [
    'notice_id',
    'user_id',
    'is_read',
    'read_at',
  ];

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function notice()
  {
    return $this->belongsTo(Notice::class);
  }
  public static function send($notice, $users = null)
  {

    $data = $users ?? User::query();

    $data = $data->where('users.status', 1)->where('users.role', 1)->pluck('users.fcm_token', 'users.id')->toArray();

    $users = array_keys($data);

    $fcm_tokens = array_filter($data);

    array_walk($users, function (&$value, $key) use ($notice) {
      $value = [
        'user_id' => $value,
        'notice_id' => $notice->id,
        'created_at' => now(),
      ];
    });

    self::insert($users);

    $controller = new \App\Http\Controllers\Controller();

    $controller->send_fcm_multi(
      $notice->title_ar,
      $notice->content_ar,
      $fcm_tokens
    );
  }
}
