<?php

namespace App\Http\Resources;

use App\Models\Version;
use Illuminate\Http\Resources\Json\JsonResource;

class VersionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $android = Version::android();
        $ios = Version::ios();

        return [
          'android' => [
            'version_number' => $android->version_number,
            'build_number' => $android->build_number,
            'priority' => str($android->priority),
            'link' => $android->link,
          ],

          'ios' => [
            'version_number' => $ios->version_number,
            'build_number' => $ios->build_number,
            'priority' => str($ios->priority),
            'link' => $ios->link,
          ],

        ];
    }
}
