<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ManagementRequest extends Model
{
    protected $fillable = ['client_id', 'contract_id', 'project_manager_id', 'status'];

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function project()
    {
        return $this->belongsTo(Contract::class);
    }

    public function projectManager()
    {
        return $this->belongsTo(User::class, 'project_manager_id');
    }

    public function markAsAccepted(User $pm)
    {
        $this->update(['project_manager_id' => $pm->id, 'status' => 'accepted']);
        
        // Update project manager ID on project
        $this->project->update(['project_manager_id' => $pm->id]);

        // Notify PM
        $pm->notify(new ProjectManagementRequested($this));

        return true;
    }

    public function markAsRejected()
    {
        $this->update(['status' => 'rejected']);
        return true;
    }
}
