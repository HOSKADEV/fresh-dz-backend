<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
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
          'id' => $this->id,
          'title' => $this->notice?->title($request->header('Accept-Language', 'ar')),
          'content' => $this->notice?->content($request->header('Accept-Language', 'ar')),
          'type' => $this->notice->type,
          'created_at' => $this->created_at,
          'is_read' => $this->is_read == 'yes' ? true : false,
          'read_at' => $this->read_at,
        ];
    }
}
