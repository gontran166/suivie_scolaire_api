<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ClasseResource;
use App\Http\Resources\EleveResource;


class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'password_changed' => $this->password_changed,
            'classes'    => ClasseResource::collection($this->whenLoaded('classes')),
            'eleves'     => EleveResource::collection($this->whenLoaded('eleves')),

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}