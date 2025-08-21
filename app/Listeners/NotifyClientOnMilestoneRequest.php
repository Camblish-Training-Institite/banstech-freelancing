<?php

namespace App\Listeners;

use App\Events\MilestoneRequested;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;
use App\Notifications\MilestoneRequestedNotification;

class NotifyClientOnMilestoneRequest
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(MilestoneRequested $event): void
    {
        $client = $event->milestone->project->client;

         // Send notification
        $client->notify(new MilestoneRequestedNotification($event->milestone));
    }
}
