<?php

namespace App\DTOs\Absence;

readonly class CreateAbsenceDto
{
    public function __construct(
        public int $eleve_id,
        public string $date_absence,
        public ?string $motif = null,
        public string $statut = 'en attente'
    ){}

    public static function fromArray(array $data): self
    {
        return new self(
            eleve_id: $data['eleve_id'],
            date_absence: $data['date_absence'],
            motif: $data['motif'] ?? null,
            statut: $data['statut'] ?? 'en attente'
        );
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}