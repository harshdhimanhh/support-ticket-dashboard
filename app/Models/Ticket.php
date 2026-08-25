<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;

class Ticket extends Model
{
 protected $fillable = [
    'customer_id',
    'customer_name',
    'email',
    'subject',
    'status',
];

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function customer(): BelongsTo
{
    return $this->belongsTo(User::class, 'customer_id');
}

    /**
     * Use an encrypted, URL-safe route key so database IDs never appear in URLs.
     */
    public function getRouteKey(): string
    {
        return rtrim(
            strtr(base64_encode(Crypt::encryptString((string) $this->getKey())), '+/', '-_'),
            '='
        );
    }

    public function resolveRouteBinding($value, $field = null): ?self
    {
        $encoded = strtr($value, '-_', '+/');
        $encoded .= str_repeat('=', (4 - strlen($encoded) % 4) % 4);

        try {
            $id = Crypt::decryptString(base64_decode($encoded, true));
        } catch (DecryptException | \TypeError) {
            return null;
        }

        return $this->where($field ?? $this->getRouteKeyName(), $id)->first();
    }
}
