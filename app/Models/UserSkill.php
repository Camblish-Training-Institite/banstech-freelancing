<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSkill extends Model
{
    /** @use HasFactory<\Database\Factories\UserSkillFactory> */
    use HasFactory;
    protected $fillable = [
       'user_id',
       'skill_id',
    ];

}
