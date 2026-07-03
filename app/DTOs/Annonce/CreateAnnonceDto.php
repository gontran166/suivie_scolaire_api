<?php

namespace App\DTOs\Annonce;

readonly class CreateAnnonceDto
{
    public function __construct(
        public string $titre,
        public string $contenu,
        public string $type = 'annonce',
        public ?int $classe_id = null,
        public bool $active = true,
        public ?string $date_expiration = null
    ){}

    public static function fromArray(array $data): self
    {
        return new self(
            titre: $data['titre'],
            contenu: $data['contenu'],
            type: $data['type'] ?? 'annonce',
            classe_id: $data['classe_id'] ?? null,
            active: isset($data['active']) ? (bool) $data['active'] : true,
            date_expiration: $data['date_expiration'] ?? null
        );
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}