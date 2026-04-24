<?php

namespace App\Notifications;

use App\Models\WithdrawalRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class WithdrawalStatusUpdated extends Notification
{
    use Queueable;

    public function __construct(public int $withdrawalRequestId)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    public function toBroadcast($notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'data' => $this->toArray($notifiable),
        ]);
    }

    public function toArray(object $notifiable): array
    {
        $withdrawal = WithdrawalRequest::with('user')->findOrFail($this->withdrawalRequestId);

        return [
            'withdrawal_request_id' => $withdrawal->id,
            'amount' => $withdrawal->amount,
            'method' => $withdrawal->method,
            'status' => $withdrawal->status,
            'message' => $this->messageFor($withdrawal),
            'url' => route('freelancer.earnings'),
        ];
    }

    protected function messageFor(WithdrawalRequest $withdrawal): string
    {
        $amount = number_format((float) $withdrawal->amount, 2);
        $method = $withdrawal->method === 'paypal' ? 'PayPal' : 'bank transfer';

        return match ($withdrawal->status) {
            'confirmed' => 'Your withdrawal request for R' . $amount . ' via ' . $method . ' has been confirmed and is being processed.',
            'processed' => 'Your withdrawal request for R' . $amount . ' via ' . $method . ' has been completed.',
            'failed' => 'Your withdrawal request for R' . $amount . ' via ' . $method . ' could not be completed.',
            default => 'Your withdrawal request status has been updated.',
        };
    }
}
