<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proposal extends Model
{
    use CrudTrait;
    /** @use HasFactory<\Database\Factories\ProposalFactory> */
    use HasFactory;
    protected $fillable = [ 

        'user_id',
        'job_id',
        'bid_amount',
        'cover_letter',
        'status',

    ];

    protected $casts = [
        'bid_amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function job()
    {
        return $this->belongsTo(Job::class, 'job_id');
    }

    public function accept()
    {
        $this->update(['status' => 'accepted']);
        event(new \App\Events\ProposalAccepted($this));
    }

     public function reject()
    {
        $this->update(['status' => 'rejected']);
    }

}
