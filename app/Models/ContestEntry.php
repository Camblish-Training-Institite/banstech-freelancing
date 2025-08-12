<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContestEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'contest_id',
        'freelancer_id',
        'title',
        'description', // updated from submission_details
        'is_original',
        'sell_price',
        'is_highlighted',
        'is_sealed',
        'files',
        'is_winner',
    ];

    protected $casts = [
        'is_original' => 'boolean',
        'is_highlighted' => 'boolean',
        'is_sealed' => 'boolean',
        'is_winner' => 'boolean',
        'files' => 'array',
    ];

    public function contest()
    {
        return $this->belongsTo(Contest::class);
    }

    public function freelancer()
    {
        return $this->belongsTo(User::class, 'freelancer_id');
    }
}