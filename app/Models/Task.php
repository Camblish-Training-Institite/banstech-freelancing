<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'title',
        'description',
        'status',
        'project_id',
        'user_id',
        'due_date'
    ];

    public function project()
    {
        return $this->belongsTo(Contract::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
