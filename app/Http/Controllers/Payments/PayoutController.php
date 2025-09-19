<?php

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\Controller;
use App\Models\Payout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PayoutController extends Controller
{

    public function index()   {
        $userId = Auth::user();

        $payouts = Payout::where('freelancer_id', $userId->id)->get();
        // dd($payouts);
 
        $TotalEarnings =  $payouts->sum('amount'); //nullable to be recheck
        $PendingPayouts = $payouts ->where('status','pending')->sum('amount');
        $AvailableWithdrawals = $payouts->where('status','processed')->sum('amount');
        
        return view('dashboards.freelancer.earnings',compact('payouts','PendingPayouts','AvailableWithdrawals','TotalEarnings'));        
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
}