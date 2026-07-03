<?php

namespace App\DTOs\Annonce;

readonly class UpdateAnnonceDto
{
    public function __construct(
        public ?string $titre = null,
        public ?string $contenu = null,
        public ?string $type = null,
        public ?int $classe_id = null,
        public ?bool $active = null,
        public ?string $date_expiration = null
    ){}

    public static function fromArray(array $data): self
    {
        return new self(
            titre: $data['titre'] ?? null,
            contenu: $data['contenu'] ?? null,
            type: $data['type'] ?? null,
            classe_id: $data['classe_id'] ?? null,
            active: isset($data['active']) ? (bool) $data['active'] : null,
            date_expiration: $data['date_expiration'] ?? null
        );
    }

    public function toArray(): array
    {
        return array_filter(get_object_vars($this), fn($value) => $value !== null);
    }
}