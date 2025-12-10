<?php

namespace App\Http\Controllers\Payments;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProjectFunding;

class ProjectFundingController extends Controller
{

public function index(){

$clientId = Auth::id();
$fundings = ProjectFunding::where('client_id', $clientId)->get();

//Available balance
$availableBalance = $fundings
->where('status', 'deposited')
->sum('amount');

//Escrowed funds
 $fundsInEscrow = $fundings
 ->where('status', 'pending')
 ->sum('amount');

 //Total Spent
 $totalSpent = $fundings
//  ->where('status','deposited') released
 ->sum('amount');  //TotalSpent = TotalSpent - AvailableBalance


 return view('dashboards.client.billing', compact('totalSpent', 'fundsInEscrow', 'availableBalance'));
    
    }
    // this is handles deposits and refunds for project funding
  public function createDeposit(Request $request)
{
    $request->validate([
        'project_id' => 'required|exists:projects,id',
        'user_id' => 'required|exists:users,id',
        'amount' => 'required|numeric|min:1',
    ]);

    $deposit = new ProjectFunding();
    $deposit->project_id = $request->project_id;
    $deposit->user_id = $request->user_id;
    $deposit->amount = $request->amount;
    $deposit->status = 'pending';
    $deposit->save();

    return response()->json([
        'message' => 'Deposit created successfully. Awaiting payment processing.',
        'deposit' => $deposit
    ]);
}

// This is for updating the deposit status after payment confirmation
public function updateDeposit($id)
{
    $deposit = ProjectFunding::findOrFail($id);

    // Only pending deposits can be updated to completed
    if ($deposit->status !== 'pending') {
        return response()->json([
            'message' => 'Only pending deposits can be updated.'
        ], 400);
    }

    try {
        // payment gateway to confirm payment
        // $this->processPayment($deposit);

        $deposit->status = 'completed';
        $deposit->processed_at = now();
        $deposit->save();

        return response()->json([
            'message' => 'Deposit marked as completed.',
            'deposit' => $deposit
        ]);

    } catch (\Exception $e) {
        $deposit->status = 'failed';
        $deposit->processed_at = null;
        $deposit->save();

        return response()->json([
            'message' => 'Deposit failed: ' . $e->getMessage(),
            'deposit' => $deposit
        ], 500);
    }
}
//  This stands for a refund request
public function requestRefund($id)
{
    $deposit = ProjectFunding::findOrFail($id);

    // Only completed deposits can be refunded
    if ($deposit->status !== 'completed') {
        return response()->json([
            'message' => 'Only completed deposits can be refunded.'
        ], 400);
    }

    try {
        // refund gateway
        // $this->processRefund($deposit);

        $deposit->status = 'refunded';
        $deposit->processed_at = now(); 
        
        $deposit->save();

        return response()->json([
            'message' => 'Deposit refunded successfully.',
            'deposit' => $deposit
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Refund failed: ' . $e->getMessage(),
            'deposit' => $deposit
        ], 500);
    }
}

}
