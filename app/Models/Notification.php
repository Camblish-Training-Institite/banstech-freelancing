<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    // protected $fillable = ['id', 'type', 'notifiable', 'data', 'read_at'];
    
    public function readNotifications()
    {
        return $this->whereNotNull('read_at')->get();
    }

    public function unreadNotifications()
    {
        return $this->whereNull('read_at')->get();
    }

    public function markAsRead()
    {
        $this->read_at = now();
        $this->save();
    }

    public function markAsUnread()
    {
        $this->read_at = null;
        $this->save();
    }
}
