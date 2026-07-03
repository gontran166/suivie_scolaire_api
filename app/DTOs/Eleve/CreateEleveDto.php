<?php

namespace App\DTOs\Eleve;

use Illuminate\Http\UploadedFile;

readonly class CreateEleveDto
{
    public function __construct(
        public string $nom,
        public string $prenom,
        public string $date_naissance,
        public ?UploadedFile $photo,
        public ?string $nom_parent,
        public ?string $telephone_parent,
        public int $classe_id,
        public ?int $user_id,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            nom: $data['nom'],
            prenom: $data['prenom'],
            date_naissance: $data['date_naissance'],
            photo: $data['photo'] ?? null,
            nom_parent: $data['nom_parent'] ?? null,
            telephone_parent: $data['telephone_parent'] ?? null,
            classe_id: $data['classe_id'],
            user_id: $data['user_id'] ?? null,
        );
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}