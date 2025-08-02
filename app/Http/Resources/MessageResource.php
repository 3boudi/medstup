<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'chat_id' => $this->chat_id,
            'sender_type' => $this->sender_type,
            'sender_id' => $this->sender_id,
            'sender' => $this->when($this->relationLoaded('sender'), function () {
                return $this->sender_type === 'user' 
                    ? new UserResource($this->sender)
                    : new DoctorResource($this->sender);
            }),
            'type' => $this->type,
            'message' => $this->message,
            'file_path' => $this->file_path,
            'sent_at' => $this->sent_at?->toISOString(),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}