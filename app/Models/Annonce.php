<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Annonce extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'titre',
        'contenu',
        'type',
        'classe_id',
        'active',
        'date_expiration',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function classe(): BelongsTo
    {
        return $this->belongsTo(Classe::class);
    }
}