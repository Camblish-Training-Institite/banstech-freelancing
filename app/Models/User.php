<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Backpack\CRUD\app\Models\Traits\CrudTrait; // Add this line
use Backpack\CRUD\app\Models\Traits\HasBackpackUser;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
    use  HasRoles;
    use CrudTrait;
    // use HasBackpackUser; // Add this trait for Backpack user access logic
  

    public function getRoleNamesAttribute()
    {
        // Pluck the 'name' from each role and join them with a comma.
        return $this->roles->pluck('name')->implode(', ');
    }

    // Relationships
    public function managedProjects()
    {
        return $this->hasMany(Contract::class, 'project_manager_id');
    }

    public function assignedProjects()
    {
        return $this->belongsToMany(Contract::class, 'project_assignments');
    }

    // Notifications for incoming management requests
    public function pendingManagementRequests()
    {
        return $this->morphMany(ManagementRequest::class, 'requester');
    }

    
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'user_type',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }
  
    public function canAccessBackpack(): bool
    {
        // Option 1: Using user_type
        // return $this->user_type === 'admin';

        // Option 2: Using Spatie roles (e.g., only users with 'admin' or 'super_admin' role)
        return $this->hasRole('admin');

        // Option 3: Using Spatie permission (e.g., user needs 'access_admin_panel' permission)
        // return $this->hasPermissionTo('access_admin_panel');

        // Choose the logic that best fits your access control strategy.
        // For this guide, we'll primarily use the user_type, but keep Spatie in mind for granular control.
    }
}
