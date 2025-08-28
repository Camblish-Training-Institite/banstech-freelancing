<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class ContestSubmission extends Model
{
    use CrudTrait;
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

    public function contest()
    {
        return $this->belongsTo(Contest::class, 'contest_id');
    }
}
