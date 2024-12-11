<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Session;

class SectionResource extends JsonResource
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
          'type' => $this->type,
          'name' => $this->name(Session::get('lang')),
          'data' => $this->data()
        ];
    }
}
