<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens,HasFactory, Notifiable, SoftDeletes;

    public const ROLE_ADMIN = 'gestionnaire';
    public const ROLE_ENSEIGNANT = 'enseignant';
    public const ROLE_PARENT = 'parent';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = ['name', 'email', 'password', 'role','password_changed','fcm_token'];

    protected $hidden = ['password', 'remember_token'];

    // Un enseignant/gestionnaire est responsable de plusieurs classes
    public function classes(): HasMany
    {
        return $this->hasMany(Classe::class);
    }

    // Un enseignant/gestionnaire/parent est responsable de un ou plusieurs élèves(ses enfants)
    public function eleves(): HasMany
    {
        return $this->hasMany(Eleve::class);
    }

    // Helpers pour vérifier le rôle facilement dans les vues et controllers
    public function isGestionnaire(): bool
    {
        return $this->role === 'gestionnaire';
    }

    public function isEnseignant(): bool
    {
        return $this->role === 'enseignant';
    }

    public function isParent(): bool
    {
        return $this->role === 'parent';
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'password_changed' => 'boolean',
        ];
    }

    protected static function booted(){
        static::deleting(function ($user) {
            // Si ce n'est pas une suppression définitive (forceDelete)
            if (! $user->isForceDeleting()) {
                $user->email = $user->email . '_deleted_' . time();
                $user->save();
            }
        });
    }
}
