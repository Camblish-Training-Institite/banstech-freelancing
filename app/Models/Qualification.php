<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


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

    public function user()
    {
        return $this->belongsTo(App\Models\User::class);
    }

}
