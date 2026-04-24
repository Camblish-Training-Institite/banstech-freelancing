<?php

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\Controller;
use App\Models\Payout;
use App\Models\WithdrawalRequest;
use App\Services\Payments\PayPalPayoutSyncService;
use App\Services\Payments\WithdrawalProcessingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class PayoutController extends Controller
{
    public function __construct(
        protected PayPalPayoutSyncService $payPalPayoutSyncService,
        protected WithdrawalProcessingService $withdrawalProcessingService
    )
    {
    }

    public function index()   {
        $user = Auth::user();

        $this->payPalPayoutSyncService->syncPendingPayoutsForUser($user->id);
        $this->withdrawalProcessingService->syncPayPalRequestsForUser($user->id);

        $payouts = Payout::with(['contract.job.user', 'milestone'])
            ->where('freelancer_id', $user->id)
            ->latest('requested_at')
            ->get();

        $withdrawalRequests = WithdrawalRequest::where('user_id', $user->id)
            ->latest('requested_at')
            ->get();

        $latestPayout = Payout::where('freelancer_id', $user->id)
            ->where('status', 'processed')
            ->latest()
            ->first();

        $TotalEarnings = (float) $payouts->where('status', 'processed')->sum('amount');
        $PendingPayouts = (float) $payouts->where('status', 'pending')->sum('amount');
        $PendingWithdrawals = (float) $withdrawalRequests->whereIn('status', ['pending', 'confirmed'])->sum('amount');
        $CompletedWithdrawals = (float) $withdrawalRequests->where('status', 'processed')->sum('amount');
        $AvailableWithdrawals = $this->calculateAvailableWithdrawals($user->id, $payouts, $withdrawalRequests);

        return view('dashboards.freelancer.earnings', compact(
            'payouts',
            'withdrawalRequests',
            'PendingPayouts',
            'PendingWithdrawals',
            'CompletedWithdrawals',
            'AvailableWithdrawals',
            'TotalEarnings',
            'latestPayout'
        ));
    }

    public function transection(){
    }

    public function storeWithdrawal(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'method' => ['required', Rule::in(['paypal', 'bank'])],
        ]);

        $availableWithdrawals = $this->calculateAvailableWithdrawals($user->id);
        $amount = round((float) $validated['amount'], 2);

        if ($amount > $availableWithdrawals) {
            return back()->withErrors([
                'amount' => 'Withdrawal amount cannot exceed your available balance.',
            ])->withInput();
        }

        $withdrawalRequest = $this->withdrawalProcessingService->submit(
            $user,
            $amount,
            $validated['method']
        );

        $statusMessage = match ($withdrawalRequest->status) {
            'processed' => 'Withdrawal request submitted and completed successfully.',
            'confirmed' => 'Withdrawal request submitted and confirmed. Payment is now in progress.',
            default => 'Withdrawal request submitted successfully.',
        };

        return back()->with('status', $statusMessage);
    }
    
    // This function updates the payout status, typically called after a payout request is made
    public function update(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'method' => 'required|string|max:255',                                                  
        ]);
        // Find the payout record or return 404
        $payout = Payout::findOrFail($id);              
        // Update the payout details
        $payout->amount = $request->input('amount');
        $payout->method = $request->input('method');
        $payout->status = 'pending'; // Set status to pending when a payout is          
        $payout->requested_at = now(); // Set the requested_at timestamp
        $payout->save();
        return redirect()->route('freelancer.earnings')->with('success', 'Payout request submitted successfully.');
    }

  
    public function markAsProcessed($id)
{
    // Find payout or return 404
    $payout = Payout::findOrFail($id);

    // Only pending payouts can be processed
    if ($payout->status !== 'pending') {
        return response()->json([
            'message' => 'Only pending payouts can be processed.'
        ], 400);
    }

    // Update status to processed
    $payout->status = 'processed';
    $payout->processed_at = now();
    $payout->save();

    return response()->json([
        'message' => 'Payout has been marked as processed.',
        'payout'  => $payout
    ]);

}// What makes these status changes is the $payout->processed_at=... if is null, then it changes to fail
//  but if it has a timestamp, then it is processed. this will happen in the frontend using the routes names for each function.

 public function markAsFailed($id){
    // Find payout or return 404
    $payout = Payout::findOrFail($id);

    // Only pending payouts can be marked as failed
    if ($payout->status !== 'pending') {
        return response()->json([
            'message' => 'Only pending payouts can be marked as failed.'
        ], 400);
    }
    // Update status to failed
    $payout->status = 'failed';
    $payout->processed_at = null;
    $payout->save();

    return response()->json([
        'message' => 'Payout has been marked as failed.',
        'payout'  => $payout
    ]);
 }

    protected function calculateAvailableWithdrawals(int $userId, $payouts = null, $withdrawalRequests = null): float
    {
        $payouts = $payouts ?? Payout::where('freelancer_id', $userId)->get();
        $withdrawalRequests = $withdrawalRequests ?? WithdrawalRequest::where('user_id', $userId)->get();

        $processedEarnings = (float) $payouts->where('status', 'processed')->sum('amount');
        $reservedOrPaidOut = (float) $withdrawalRequests
            ->whereIn('status', ['pending', 'confirmed', 'processed'])
            ->sum('amount');

        return round(max($processedEarnings - $reservedOrPaidOut, 0), 2);
    }
}
