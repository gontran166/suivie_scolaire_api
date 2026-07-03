<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NoteResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'note' => $this->note,
            'trimestre' => $this->trimestre,
            'annee_scolaire' => $this->annee_scolaire,

            'eleve' => EleveResource::make(
                $this->whenLoaded('eleve')
            ),

            'matiere' => MatiereResource::make(
                $this->whenLoaded('matiere')
            ),

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}