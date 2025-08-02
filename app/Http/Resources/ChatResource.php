<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatResource extends JsonResource
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
            'consultation_request_id' => $this->consultation_request_id,
            'is_closed' => $this->is_closed,
            'closed_at' => $this->closed_at?->toISOString(),
            'user' => new UserResource($this->whenLoaded('consultationRequest.user')),
            'doctor' => new DoctorResource($this->whenLoaded('consultationRequest.doctor')),
            'messages' => MessageResource::collection($this->whenLoaded('messages')),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}