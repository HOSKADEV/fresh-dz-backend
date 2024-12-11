<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DriverResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
          'firstname' => $this->firstname,
          'lastname' => $this->lastname,
          'phone' => $this->phone(),
          'image' => empty($this->image) ? null : url($this->image),
        ];
    }
}
