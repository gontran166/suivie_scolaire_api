<?php

namespace App\DTOs\Paiement;

readonly class CreatePaiementDto
{
    public function __construct(
        public int $eleve_id,
        public float $montant,
        public string $date_paiement,
        public ?string $recu_pdf = null,
        public ?string $observations = null
    ){}

    public static function fromArray(array $data): self
    {
        return new self(
            eleve_id: $data['eleve_id'],
            montant: (float) $data['montant'],
            date_paiement: $data['date_paiement'],
            recu_pdf: $data['recu_pdf'] ?? null,
            observations: $data['observations'] ?? null
        );
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}