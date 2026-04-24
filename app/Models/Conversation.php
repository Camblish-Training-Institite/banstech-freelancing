<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'freelancer_id',
        'last_message_at',
    ];

    protected function casts(): array
    {
        return [
            'last_message_at' => 'datetime',
        ];
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function freelancer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'freelancer_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(ConversationMessage::class)->orderBy('created_at');
    }

    public function touchActivity(?\DateTimeInterface $at = null): void
    {
        $this->forceFill([
            'last_message_at' => $at ?? now(),
        ])->save();
    }

    public function otherParticipantFor(User $user): ?User
    {
        if ((int) $this->client_id === (int) $user->id) {
            return $this->freelancer;
        }

        if ((int) $this->freelancer_id === (int) $user->id) {
            return $this->client;
        }

        return null;
    }
}
