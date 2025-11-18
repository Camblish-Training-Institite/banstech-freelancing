<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use CrudTrait;
    /** @use HasFactory<\Database\Factories\ContractFactory> */
    use HasFactory;

    protected $fillable = [
        'job_id',
        'user_id',
        'agreed_amount',
        'start_date',
        'end_date',
        'status',
        'project_manager_id',
    ];

    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

     public function projectManager()
    {
        return $this->belongsTo(User::class, 'project_manager_id');
    }

    public function freelancer(){
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function payouts(){
       return $this->hasMany(Payout::class,'contract_id');
    }
    public function milestones(){
        return $this->hasMany(Milestone::class, 'project_id');
    }
  
    public function sumReleased(){
        return $this->milestones()
        ->where('status', '=', 'released')
        ->sum('amount');
    }

    public function files()
    {
        return $this->hasMany(File::class, 'project_id');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'project_id');

    }
}
