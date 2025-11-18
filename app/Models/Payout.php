<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payout extends Model
{
    /** @use HasFactory<\Database\Factories\PayoutFactory> */
    use HasFactory;

    protected $fillable = [
    'freelancer_id',
    'contract_id',
    'contest_id', 
    'amount',
    'status',
     'requested_at',
      'processed_at'];

//not sure if this won't affect
public function contract()
{
    return $this->belongsTo(Contract::class, 'contract_id');
}
public function contest()
{
    return $this->belongsTo(Contest::class, 'contest_id');
}
 }