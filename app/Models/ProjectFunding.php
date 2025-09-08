<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectFunding extends Model
{
    protected $fillable=['client_id',
     'amount', 
     'job_id', 
     'contest_id', 
     'transaction_id',
      'status'];

    public function client(){
        return $this->belongsTo(User::class, 'client_id');
    }
    public function job(){        
        return $this->belongsTo(Job::class, 'job_id');
    }

    public function contest(){
        return $this->belongsTo(Contest::class, 'contest_id');
    }
      public function Transaction(){
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }
}
