<?php

namespace App\Notifications;

use App\Models\Proposal;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class ProposalAcceptedNotification extends Notification
{
    use Queueable;
    public $proposal;

    /**
     * Create a new notification instance.
     */
    public function __construct(Proposal $proposal)
    {
        $this->proposal->$proposal;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable)
    {
        return ['database', 'broadcast'];
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
    public function toArray(object $notifiable)
    {
        return [
            'proposal_id' => $this->proposal?->id,
            'job_title' => $this->proposal?->job?->title ?? 'Unknown Job',
            'freelancer_name' => $this->proposal?->user?->name ?? 'Unknown Freelancer',
            'message' => 'Your proposal is "' . /*($this->proposal?->status ?? 'unknown').*/ '" for job: "' . ($this->proposal?->job?->title ?? 'unknown') . '".',
            'url' => route('freelancer.proposal.show', $this->proposal?->id),
        ];
    }
}
