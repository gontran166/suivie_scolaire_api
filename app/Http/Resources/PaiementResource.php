<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaiementResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'montant' => $this->montant,
            'date_paiement' => $this->date_paiement,
            'recu_pdf' => $this->recu_pdf,
            'observations' => $this->observations,

            'eleve' => EleveResource::make(
                $this->whenLoaded('eleve')
            ),
            'recu_url' => $this->recu_pdf
                ? asset(
                    'storage/' . $this->recu_pdf
                )
                : null,

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}