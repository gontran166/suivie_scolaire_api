<?php

namespace App\DTOs\Paiement;

readonly class UpdatePaiementDto
{
    public function __construct(
        public ?int $eleve_id = null,
        public ?float $montant = null,
        public ?string $date_paiement = null,
        public ?string $recu_pdf = null,
        public ?string $observations = null
    ){}

    public static function fromArray(array $data): self
    {
        return new self(
            eleve_id: $data['eleve_id'] ?? null,
            montant: isset($data['montant']) ? (float) $data['montant'] : null,
            date_paiement: $data['date_paiement'] ?? null,
            recu_pdf: $data['recu_pdf'] ?? null,
            observations: $data['observations'] ?? null
        );
    }

    public function toArray(): array
    {
        return array_filter(get_object_vars($this), fn($value) => $value !== null);
    }
}