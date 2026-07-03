<?php

namespace App\DTOs\Absence;

readonly class UpdateAbsenceDto
{
    public function __construct(
        public ?int $eleve_id = null,
        public ?string $date_absence = null,
        public ?string $motif = null,
        public ?string $statut = null
    ){}

    public static function fromArray(array $data): self
    {
        return new self(
            eleve_id: $data['eleve_id'] ?? null,
            date_absence: $data['date_absence'] ?? null,
            motif: $data['motif'] ?? null,
            statut: $data['statut'] ?? null
        );
    }

    public function toArray(): array
    {
        return array_filter(get_object_vars($this), fn($value) => $value !== null);
    }
}