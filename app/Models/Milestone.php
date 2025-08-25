<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Milestone extends Model
{
    protected $fillable = [
        'project_id', 'title', 'description', 'amount', 'due_date', 'status'
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }

    public function request()
    {
        if ($this->status !== 'pending') {
            throw new \Exception('Milestone not pending.');
        }

        $this->update(['status' => 'requested']);
        event(new \App\Events\MilestoneRequested($this));
    }

    public function approve()
    {
        if ($this->status !== 'requested') {
            throw new \Exception('Milestone must be requested first.');
        }

        $this->update(['status' => 'approved']);
        event(new \App\Events\MilestoneApproved($this));
    }
}