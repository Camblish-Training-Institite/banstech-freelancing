<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use App\Models\Milestone;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class MilestoneReleased extends Notification
{
    use Queueable;
    
    public $milestone;

    /**
     * Create a new notification instance.
     */
    public function __construct(int $milestoneId)
    {
        $this->milestone = Milestone::findOrFail($milestoneId);
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['broadcast', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    // public function toMail(object $notifiable): MailMessage
    // { 
    //     return (new MailMessage)
    //         ->line('The introduction to the notification.')
    //         ->action('Notification Action', url('/'))
    //         ->line('Thank you for using our application!');
    // }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'data' => $this->toArray($notifiable)
        ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'milestone_id' => $this->milestone->id,
            'job_title' => $this->milestone->project->job->title,
            'freelancer_name' => $this->milestone->project->user->name,
            'message' => 'Funds for milestone "'. $this->milestone->title .'" have been ' . $this->milestone->status. '.',
            'url' => route('freelancer.milestone.show', $this->milestone->id),
        ];
    }
}
