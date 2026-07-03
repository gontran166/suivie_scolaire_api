<?php

namespace App\DTOs\Matiere;

readonly class UpdateMatiereDto
{
    public function __construct(
        public ?string $nom = null,
        public ?int $coefficient = null,
        public ?int $classe_id = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            nom: $data['nom'] ?? null,
            coefficient: $data['coefficient'] ?? null,
            classe_id: $data['classe_id'] ?? null,
        );
    }

    public function toArray(): array
    {
        return array_filter(
            get_object_vars($this),
            fn ($value) => $value !== null
        );
    }
}