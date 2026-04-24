<?php

namespace App\Http\Controllers\BanstechAdmin;

use App\Http\Controllers\Controller;
use App\Models\WithdrawalRequest as WithdrawalRequestModel;
use App\Services\Payments\WithdrawalProcessingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WithdrawalRequestController extends Controller
{
    public function __construct(protected WithdrawalProcessingService $withdrawalProcessingService)
    {
    }

    public function index(Request $request)
    {
        $query = WithdrawalRequestModel::with(['user', 'reviewedByAdmin']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('method')) {
            $query->where('method', $request->method);
        }

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($builder) use ($search) {
                $builder->whereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%');
                })->orWhere('paypal_batch_id', 'like', '%' . $search . '%');
            });
        }

        $withdrawalRequests = $query->latest('requested_at')->paginate(15);

        $this->withdrawalProcessingService->syncPayPalRequests($withdrawalRequests->getCollection());

        $pagetitle = 'Withdrawal Requests';

        return view('admin.withdrawals.index', compact('withdrawalRequests', 'pagetitle'));
    }

    public function show(WithdrawalRequestModel $withdrawal)
    {
        $withdrawal->load(['user', 'reviewedByAdmin']);

        if ($withdrawal->method === 'paypal' && in_array($withdrawal->status, ['pending', 'confirmed'], true)) {
            $this->withdrawalProcessingService->syncPayPalRequests(collect([$withdrawal]));
            $withdrawal->refresh()->load(['user', 'reviewedByAdmin']);
        }

        return view('admin.withdrawals.show', [
            'withdrawal' => $withdrawal,
            'pagetitle' => 'Withdrawal Request Details',
        ]);
    }

    public function confirm(WithdrawalRequestModel $withdrawal)
    {
        $admin = Auth::guard('admin')->user();

        $this->withdrawalProcessingService->confirm($withdrawal, $admin);

        return back()->with('success', 'Withdrawal request confirmed successfully.');
    }

    public function process(WithdrawalRequestModel $withdrawal)
    {
        $admin = Auth::guard('admin')->user();

        $this->withdrawalProcessingService->markProcessed($withdrawal, $admin);

        return back()->with('success', 'Withdrawal request marked as processed.');
    }

    public function fail(Request $request, WithdrawalRequestModel $withdrawal)
    {
        $validated = $request->validate([
            'failure_reason' => ['nullable', 'string', 'max:1000'],
        ]);

        $admin = Auth::guard('admin')->user();

        $this->withdrawalProcessingService->markFailed(
            $withdrawal,
            $admin,
            $validated['failure_reason'] ?? null
        );

        return back()->with('success', 'Withdrawal request marked as failed.');
    }

    public function retryPayPal(WithdrawalRequestModel $withdrawal)
    {
        abort_unless($withdrawal->method === 'paypal', 404);

        $admin = Auth::guard('admin')->user();
        $this->withdrawalProcessingService->confirm($withdrawal, $admin);

        return back()->with('success', 'PayPal withdrawal retry submitted successfully.');
    }
}
