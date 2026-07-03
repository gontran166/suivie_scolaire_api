<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Absence extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'eleve_id',
        'date_absence',
        'motif',
        'statut',
    ];

    /**
     * Relations
     */
    public function eleve(): BelongsTo
    {
        return $this->belongsTo(Eleve::class);
    }
}