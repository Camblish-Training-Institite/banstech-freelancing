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
        
//This is for Recent Payouts
        $latestPayout = Payout::where('freelancer_id', $userId->id)->where('status','processed')->latest()->first();
 
        $TotalEarnings =  $payouts->where('status','processed')->sum('amount'); 
        $PendingPayouts = $payouts ->where('status','pending')->sum('amount');
        $AvailableWithdrawals = $payouts->where('status','processed')->sum('amount');
        
        return view('dashboards.freelancer.earnings',compact('payouts','PendingPayouts','AvailableWithdrawals','TotalEarnings'));        
    
    }

    public function transection(){
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
}