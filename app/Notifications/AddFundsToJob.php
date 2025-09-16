<?php

namespace App\Notifications;

use App\Models\Job;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification; 
use Illuminate\Notifications\Messages\BroadcastMessage; 

class AddFundsToJob extends Notification
{
    use Queueable;

    public $job;

    /**
     * Create a new notification instance.
     */
    public function __construct(int $jobId)
    {
        $this->job = Job::findOrFail($jobId);
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
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
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
            'job_id' => $this->job->id,
            'job_title' => $this->job->title,
            'client_name' => $this->job->user->name,
            'message' => 'Don\'t forget to add funds for your job: "' . $this->job->title. '".',
            'url' => route('billing'),
        ];
    } 
}
