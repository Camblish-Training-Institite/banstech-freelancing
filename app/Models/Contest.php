<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contest extends Model
{
    use CrudTrait;
    use HasFactory;

    protected $fillable = [
        'client_id',
        'title',
        'description',
        'prize_money',
        'closing_date',
        'required_skills',
        'status',
    ];

    protected $casts = [
        'required_skills' => 'array',
        'closing_date' => 'date',
    ];

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function entries()
    {
        return $this->hasMany(ContestEntry::class);
    }

    public function submissions()
    {
        return $this->hasMany(ContestSubmission::class);
    }


// public function getIsActiveAttribute()
// {
//     return now()->lessThanOrEqualTo($this->end_date);
// }
}