<?php

namespace App\DTOs\Note;

readonly class CreateNoteDto
{
    public function __construct(
        public int $eleve_id,
        public int $matiere_id,
        public float $note,
        public int $trimestre,
        public string $annee_scolaire
    ){}

    public static function fromArray(array $data): self
    {
        return new self(
            eleve_id: $data['eleve_id'],
            matiere_id: $data['matiere_id'],
            note: (float) $data['note'],
            trimestre: (int) $data['trimestre'],
            annee_scolaire: $data['annee_scolaire']
        );
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}