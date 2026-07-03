<?php

namespace App\DTOs\Classe;

readonly class UpdateClasseDto
{
    public function __construct(
        public ?string $nom = null,
        public ?string $niveau = null,
        public ?float $frais_scolarite = null,
        public ?string $annee_scolaire = null,
        public ?int $user_id = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            nom: $data['nom'] ?? null,
            niveau: $data['niveau'] ?? null,
            frais_scolarite: $data['frais_scolarite'] ?? null,
            annee_scolaire: $data['annee_scolaire'] ?? null,
            user_id: $data['user_id'] ?? null,
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