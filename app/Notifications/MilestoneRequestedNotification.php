<?php

namespace App\Notifications;

use App\Models\Milestone;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MilestoneRequestedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Milestone $milestone)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail']; // Send via email and save in DB
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Milestone Requested: ' . $this->milestone->title)
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('Your freelancer has requested payment for the following milestone:')
            ->line('**' . $this->milestone->title . '**')
            ->line('Amount: R' . number_format($this->milestone->amount, 2))
            ->line('Due Date: ' . $this->milestone->due_date->format('M d, Y'))
            ->action('Review Milestone', url('/client/projects/' . $this->milestone->project->id))
            ->line('Thank you for using our platform!');
    }

    /**
     * Get the array representation of the notification (for database).
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'milestone_requested',
            'message' => 'Your freelancer requested milestone: "' . $this->milestone->title . '"',
            'url' => '/client/projects/' . $this->milestone->project->id,
            'project_id' => $this->milestone->project->id,
            'milestone_id' => $this->milestone->id,
            'sender_id' => $this->milestone->project->freelancer_id,
        ];
    }
}
