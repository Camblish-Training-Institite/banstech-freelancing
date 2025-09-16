<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProjectManagementRequested extends Notification
{
    use Queueable;
    

    public function __construct(public ManagementRequest $request)
    {
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'New Project Management Request',
            'message' => "Client {$this->request->client->name} wants you to manage project: {$this->request->project->title}",
            'url' => route('admin.management-requests.show', $this->request->id),
            'type' => 'project_management_request',
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastNotification(
            'Project Management Request',
            "You've been requested to manage project: {$this->request->project->title}"
        );
    }
}