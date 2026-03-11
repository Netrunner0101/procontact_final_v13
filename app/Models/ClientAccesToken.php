<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class ClientAccesToken extends Model
{
    protected $table = 'client_acces_tokens';

    public $timestamps = false;

    protected $fillable = [
        'contact_id',
        'token',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'date_creation' => 'datetime',
    ];

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }

    public static function generateToken(): string
    {
        return (string) Str::uuid();
    }

    public static function getOrCreateForContact(int $contactId): self
    {
        $existing = self::where('contact_id', $contactId)
                        ->where('is_active', true)
                        ->first();

        if ($existing) {
            return $existing;
        }

        return self::create([
            'contact_id' => $contactId,
            'token' => self::generateToken(),
            'is_active' => true,
        ]);
    }

    public function revoke(): void
    {
        $this->update(['is_active' => false]);
    }
}
