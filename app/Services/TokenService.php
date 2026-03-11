<?php

namespace App\Services;

use App\Models\ClientAccesToken;
use App\Models\Contact;

class TokenService
{
    public function valider(string $token): ?Contact
    {
        $record = ClientAccesToken::where('token', $token)
                                  ->where('is_active', true)
                                  ->with('contact')
                                  ->first();

        return $record?->contact;
    }

    public function getOrCreateToken(int $contactId): string
    {
        $record = ClientAccesToken::getOrCreateForContact($contactId);
        return $record->token;
    }

    public function revoquer(int $contactId): void
    {
        ClientAccesToken::where('contact_id', $contactId)
                        ->where('is_active', true)
                        ->update(['is_active' => false]);
    }
}
