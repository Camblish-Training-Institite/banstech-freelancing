<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dispute extends Model
{
    /** @use HasFactory<\Database\Factories\DisputeFactory> */
    use HasFactory;

    protected $fillable = [
        'contract_id',
        'reporter_id',
        'reason',
        'status',
        'resolved_by',
    ];
}
