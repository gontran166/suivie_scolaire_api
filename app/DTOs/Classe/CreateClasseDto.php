<?php

namespace App\DTOs\Classe;

readonly class CreateClasseDto
{
    public function __construct(
        public string $nom,
        public string $niveau,
        public float $frais_scolarite,
        public string $annee_scolaire,
        public ?int $user_id,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            nom: $data['nom'],
            niveau: $data['niveau'],
            frais_scolarite: $data['frais_scolarite'],
            annee_scolaire: $data['annee_scolaire'],
            user_id: $data['user_id'] ?? null,
        );
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}