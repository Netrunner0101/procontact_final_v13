<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Note extends Model
{
    use HasFactory;

    protected $fillable = [
        'rendez_vous_id',
        'activite_id',
        'titre',
        'commentaire',
        'date_create',
        'date_update',
    ];

    protected $casts = [
        'date_create' => 'datetime',
        'date_update' => 'datetime',
    ];

    /**
     * Get the appointment that owns the note.
     */
    public function rendezVous(): BelongsTo
    {
        return $this->belongsTo(RendezVous::class);
    }

    /**
     * Get the activity that owns the note.
     */
    public function activite(): BelongsTo
    {
        return $this->belongsTo(Activite::class);
    }
}
