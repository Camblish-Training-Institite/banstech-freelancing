<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use CrudTrait;
    /** @use HasFactory<\Database\Factories\JobFactory> */
    use HasFactory;

    protected $fillable = [ 
        'user_id',
        'title',
        'description',
        'budget',
        'status',
        'deadline',
        'skills',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function proposals()
    {
        return $this->hasMany(Proposal::class, 'job_id');
    }

    // Function to get the minimum bid amount
    public function lowestBid()
    {
        return $this->proposals()->min('bid_amount');
    }

    // Function to get the latest submission date
    public function latestSubmissionDate()
    {
        return $this->proposals()->max('created_at');
    }

    // Function to get the latest proposal object
    public function latestSubmission()
    {
        return $this->proposals()->latest('created_at')->first();
    }

    protected $casts=[
        'skills' => 'array',
    ];
}
