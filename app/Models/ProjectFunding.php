<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectFunding extends Model
{
    protected $casts = [
        'amount' => 'decimal:2',
        'paypal_amount' => 'decimal:2',
        'exchange_rate' => 'decimal:8',
        'processed_at' => 'datetime',
    ];

    protected $fillable = [
        'client_id',
        'amount',
        'source_currency',
        'paypal_currency',
        'paypal_amount',
        'exchange_rate',
        'job_id',
        'contest_id',
        'transaction_id',
        'payment_gateway',
        'paypal_order_id',
        'paypal_capture_id',
        'status',
        'processed_at',
    ];

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }
    public function job()
    {
        return $this->belongsTo(Job::class, 'job_id');
    }
    public function contest()
    {
        return $this->belongsTo(Contest::class, 'contest_id');
    }
    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }
}
