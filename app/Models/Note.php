<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Note extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'rendez_vous_id',
        'activite_id',
        'titre',
        'commentaire',
        'is_shared_with_client',
        'priorite',
        'contact_id',
        'date_create',
        'date_update',
    ];

    protected $casts = [
        'date_create' => 'datetime',
        'date_update' => 'datetime',
        'is_shared_with_client' => 'boolean',
    ];

    /**
     * Get the user that owns the note.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the contact associated with the note.
     */
    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }

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
