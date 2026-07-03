<?php

namespace App\DTOs\Note;

readonly class UpdateNoteDto
{
    public function __construct(
        public ?int $eleve_id = null,
        public ?int $matiere_id = null,
        public ?float $note = null,
        public ?int $trimestre = null,
        public ?string $annee_scolaire = null
    ){}

    public static function fromArray(array $data): self
    {
        return new self(
            eleve_id: $data['eleve_id'] ?? null,
            matiere_id: $data['matiere_id'] ?? null,
            note: isset($data['note']) ? (float) $data['note'] : null,
            trimestre: isset($data['trimestre']) ? (int) $data['trimestre'] : null,
            annee_scolaire: $data['annee_scolaire'] ?? null
        );
    }

    public function toArray(): array
    {
        return array_filter(get_object_vars($this), fn($value) => $value !== null);
    }
}