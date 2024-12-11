<?php

namespace App\Http\Controllers;

use App\Models\Set;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Kreait\Firebase\Exception\FirebaseException;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\WebPushConfig;
use Laravel\Sanctum\PersonalAccessToken;

class Controller extends BaseController
{
  use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

  public function store($file, $path)
  {

    $filename = md5(time() . $file->getClientOriginalName()) . '.' . $file->getClientOriginalExtension();
    $file->move($path, $filename);
    $filepath = $path . '/' . $filename;

    return $filepath;
  }

  function random()
  {
    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 8; $i++) {
      $n = rand(0, $alphaLength);
      $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
  }

  public function firestore($file, $filename)
  {

    $storage = app('firebase.storage');
    $storageClient = $storage->getStorageClient();
    $defaultBucket = $storage->getBucket();

    $object = $defaultBucket->upload(
      $file,
      [
        'predefinedAcl' => 'publicRead',
        'name' => $filename,
      ]
    );

    $url = 'https://storage.googleapis.com/' . $object->info()['bucket'] . '/' . $object->info()['name'];
    return $url;
  }

  public function calc_distance($p1, $p2)
  {
    $r = 6378137;
    $dLat = deg2rad($p2['lat'] - $p1['lat']);
    $dLong = deg2rad($p2['lng'] - $p1['lng']);

    $a = sin($dLat / 2) * sin($dLat / 2) +
      cos(deg2rad($p1['lat'])) * cos(deg2rad($p2['lat'])) *
      sin($dLong / 2) * sin($dLong / 2);
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    $d = $r * $c;
    return $d;
  }

  public function delivery_price($distance)
  {

    $shipping = Set::where('name', 'shipping')->first();

    if (!is_null($shipping) && $shipping->status == 1) {
      return 0;
    }

    $true_price = ($distance / 1000) * 20;
    $actual_price = min(max($true_price, 100), 500);
    return $actual_price;
  }

  public function get_user_from_token($api_token)
  {

    $api_token = explode('|', $api_token, 2);
    $id = $api_token[0];
    $token = $api_token[1];

    $token = hash('sha256', $token);

    $token = PersonalAccessToken::where('id', $id)->where('token', $token)->first();

    $user = is_null($token) ? null : $token->tokenable;

    return ($user);
  }

  public function send_fcm_device($title, $content, $fcm_token)
  {
    try {
      $messaging = app('firebase.messaging');

      $notification = \Kreait\Firebase\Messaging\Notification::fromArray([
        'title' => $title,
        'body' => $content,
        //'image' => $imageUrl,
      ]);

      $deviceToken = $fcm_token;

      if(!empty($deviceToken)){

        $message = CloudMessage::withTarget('token', $deviceToken)
          ->withNotification($notification) // optional
          //->withData($data) // optional
        ;

        $messaging->send($message);
      }

      return;
    } catch (FirebaseException $e) {
      return $e;
    }


  }
  public function send_fcm_multi($title, $content, $fcm_tokens)
  {
    try {
      $messaging = app('firebase.messaging');

      $notification = \Kreait\Firebase\Messaging\Notification::fromArray([
        'title' => $title,
        'body' => $content,
        //'image' => $imageUrl,
      ]);

      $deviceTokens = $fcm_tokens;

      $message = CloudMessage::new()
        ->withNotification($notification) // optional
        //->withData($data) // optional
      ;

      $messaging->sendMulticast($message, $deviceTokens);

      return;
    } catch (FirebaseException $e) {
      return $e;
    }

  }

}
