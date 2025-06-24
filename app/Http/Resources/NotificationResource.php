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
          'priority' => $this->notice->priority,
          'meteadata' => json_decode($this->notice->metadata),
          'created_at' => $this->created_at?->format('Y-m-d\TH:i:s.uP'),
          'is_read' => $this->is_read,
          'read_at' => $this->read_at?->format('Y-m-d\TH:i:s.uP'),
        ];
    }
}
