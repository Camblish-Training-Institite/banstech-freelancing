<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContestSubmission extends Model
{
    protected $fillable = [
        'contest_id',
        'freelancer_id',
        'title',
        'description',
        'file_path'
    ];

    public function freelancer()
    {
        return $this->belongsTo(User::class, 'freelancer_id');
    }
}
