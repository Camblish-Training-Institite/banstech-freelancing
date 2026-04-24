<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class Qualification extends Model
{
    /** @use HasFactory<\Database\Factories\QualificationFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'degree',
        'institution',
        'year_of_completion',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
