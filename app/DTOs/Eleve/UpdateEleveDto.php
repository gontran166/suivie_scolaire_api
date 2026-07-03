<?php

namespace App\DTOs\Eleve;

use Illuminate\Http\UploadedFile;

readonly class UpdateEleveDto
{
    public function __construct(
        public ?string $nom = null,
        public ?string $prenom = null,
        public ?string $date_naissance = null,
        public ?UploadedFile $photo = null,
        public ?string $nom_parent = null,
        public ?string $telephone_parent = null,
        public ?int $classe_id = null,
        public ?int $user_id = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            nom: $data['nom'] ?? null,
            prenom: $data['prenom'] ?? null,
            date_naissance: $data['date_naissance'] ?? null,
            photo: $data['photo'] ?? null,
            nom_parent: $data['nom_parent'] ?? null,
            telephone_parent: $data['telephone_parent'] ?? null,
            classe_id: $data['classe_id'] ?? null,
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