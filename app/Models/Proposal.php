<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proposal extends Model
{
    /** @use HasFactory<\Database\Factories\ProposalFactory> */
    use HasFactory;
    protected $fillable = [ 

        'user_id',
        'job_id',
        'bid_amount',
        'cover_letter',
        'status',
        'submitted_at'

    ];

// Relationships
// public function user()
// {
//     return $this->belongsTo(User::class);
// }

// public function job()
// {
//     return $this->belongsTo(Job::class);
// }

}
