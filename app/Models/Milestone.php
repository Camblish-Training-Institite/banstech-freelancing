<?php
namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Milestone extends Model
{
    use CrudTrait;
    protected $fillable = [
        'project_id', 'title', 'description', 'amount', 'due_date', 'status'
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    public function __toString()
    {
        return $this->title ?? 'Unnamed Project';
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Contract::class, 'project_id');
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