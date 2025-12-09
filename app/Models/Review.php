<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    /** @use HasFactory<\Database\Factories\ReviewFactory> */
    use HasFactory;

    protected $fillable = [
        'contract_id',
        'job_id',
        'user_id',
        'rating',
        'comment',
    ];

    public function project()
    {
        return $this->belongsTo(Contract::class, 'contract_id');
    }

    public function job()
    {
        return $this->belongsTo(Job::class, 'job_id');
    }

    public function freelancer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function averageRating()
    {
        return $this->avg('rating');
    }
}
