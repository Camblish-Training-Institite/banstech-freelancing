<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\belongsToMany;



class Profile extends Model
{
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'title',
        'bio',
        'address',
        'city',
        'zip_code',
        'state',
        'country',
        'company',
        'location',
        'timezone',
        'avatar',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    // Full name accessor
    public function getFullNameAttribute()
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    public function skills(): belongsToMany
    {
        return $this->belongsToMany(Skill::class, '[profile_skill');
    }

    public function qualifications(): hasMany
    {
        return $this->hasMany(Qualification::class);
    }
}
