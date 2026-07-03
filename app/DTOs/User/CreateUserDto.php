<?php

namespace App\DTOs\User;

readonly class CreateUserDto
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password,
        public string $role,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            email: $data['email'],
            password: $data['password'],
            role: $data['role'],
        );
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}