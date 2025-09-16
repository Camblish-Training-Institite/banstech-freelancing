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
        'job_funded',
    ];

    protected $casts = [
        'facilities' => 'json',
        'completion_date' => 'date',
        'skills' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function proposals()
    {
        return $this->hasMany(Proposal::class, 'job_id');
    }

    public function project(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Project::class);
    }

    public function getIsOpenAttribute(): bool
    {
        return $this->status === 'open';
    }

    // Function to get the minimum bid amount
    public function lowestBid()
    {
        return $this->proposals()->where('status','pending')->min('bid_amount');
    }

    // Function to get the latest submission date
    public function latestSubmissionDate()
    {
        return $this->proposals()->max('created_at');
    }

    // // Function to get the latest proposal object
    // public function latestSubmission()
    // {
    //     return $this->proposals()->latest('created_at')->first();
    // }
    
    
}
