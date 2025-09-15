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
    public function __construct(int $proposalId)
    {
        $this->proposal = Proposal::with(['job', 'user'])->findOrFail($proposalId);
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'data' => $this->toArray($notifiable)
        ]);
    }
    
    public function toArray(object $notifiable)
    {
        return [
            'proposal_id' => $this->proposal->id,
            'job_title' => $this->proposal->job->title,
            'freelancer_name' => $this->proposal->user->name,
            'message' => 'Your proposal is ' . $this->proposal->status. ' for job: "' . ($this->proposal->job->title) . '".',
            'url' => route('freelancer.proposal.show', $this->proposal->id),
        ];
    } 

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
}
