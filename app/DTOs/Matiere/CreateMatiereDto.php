<?php

namespace App\DTOs\Matiere;

readonly class CreateMatiereDto
{
    public function __construct(
        public string $nom,
        public int $coefficient,
        public int $classe_id
    ){}

    public static function fromArray(array $data): self
    {
        return new self(
            nom: $data['nom'],
            coefficient: $data['coefficient'],
            classe_id: $data['classe_id']
        );
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}