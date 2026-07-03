<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EleveResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nom' => $this->nom,
            'prenom' => $this->prenom,
            'nom_complet' => $this->nom_complet,
            'date_naissance' => $this->date_naissance?->format('Y-m-d'),
            'photo' => $this->photo,
            'photo_url' => $this->photo
                ? asset('storage/' . $this->photo)
                : null,

            'nom_parent' => $this->nom_parent,
            'telephone_parent' => $this->telephone_parent,

            'classe' => ClasseResource::make(
                $this->whenLoaded('classe')
            ),

            'parent' => UserResource::make(
                $this->whenLoaded('parent')
            ),

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}