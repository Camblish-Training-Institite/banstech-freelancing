<?php

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Job;
use App\Models\Payout;
use App\Models\ProjectFunding;
use App\Services\Payments\CurrencyConversionService;
use App\Services\Payments\EscrowPayoutService;
use App\Services\Payments\PayPalCheckoutService;
use Throwable;

class ProjectFundingController extends Controller
{
    public function __construct(
        protected EscrowPayoutService $escrowPayoutService,
        protected PayPalCheckoutService $payPalCheckoutService,
        protected CurrencyConversionService $currencyConversionService
    ) {}

    public function index()
    {
        $clientId = Auth::id();
        $fundings = ProjectFunding::with(['job.contract.user'])
            ->where('client_id', $clientId)
            ->latest()
            ->get();

        $walletDeposits = (float) $fundings
            ->where('status', 'deposited')
            ->sum('amount');

        $fundsInEscrow = (float) $fundings
            ->where('status', 'pending')
            ->sum('amount');

        $totalSpent = (float) Payout::whereHas('contract.job', function ($query) use ($clientId) {
            $query->where('user_id', $clientId);
        })
            ->whereIn('status', ['pending', 'processed'])
            ->sum('amount');

        $availableBalance = $this->escrowPayoutService->getAvailableBalanceForClient($clientId);

        $jobs = Job::with(['contract.user', 'contract.escrowFunding', 'contract.milestones'])
            ->where('user_id', $clientId)
            ->whereHas('contract')
            ->latest()
            ->get();

        $currencyContext = $this->currencyConversionService->currencyContextForUser(Auth::user());

        return view('dashboards.client.billing', compact(
            'totalSpent',
            'fundsInEscrow',
            'availableBalance',
            'walletDeposits',
            'jobs',
            'fundings',
            'currencyContext'
        ));
    }

    public function storeDeposit(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        $deposit = ProjectFunding::create([
            'client_id' => Auth::id(),
            'amount' => $validated['amount'],
            'source_currency' => $this->currencyConversionService->currencyContextForUser(Auth::user())['local_currency'],
            'payment_gateway' => 'paypal',
            'status' => 'pending',
        ]);

        try {
            $currencyContext = $this->currencyConversionService->currencyContextForUser(Auth::user());
            $conversion = $this->currencyConversionService->convert(
                (float) $validated['amount'],
                $currencyContext['local_currency'],
                $currencyContext['paypal_currency']
            );

            $order = $this->payPalCheckoutService->createOrder(
                (float) $conversion['target_amount'],
                $conversion['target_currency'],
                route('billing.deposits.approve', $deposit),
                route('billing.deposits.cancel', $deposit),
                'Wallet top-up for client balance'
            );

            $deposit->paypal_order_id = $order['order_id'];
            $deposit->paypal_currency = $conversion['target_currency'];
            $deposit->paypal_amount = $conversion['target_amount'];
            $deposit->exchange_rate = $conversion['exchange_rate'];
            $deposit->save();

            return redirect()->away($order['approve_url']);
        } catch (Throwable $e) {
            $deposit->status = 'failed';
            $deposit->save();

            return back()->withErrors([
                'payment' => $e->getMessage(),
            ]);
        }
    }

    public function approveDeposit(Request $request, ProjectFunding $deposit)
    {
        abort_unless($deposit->client_id === Auth::id(), 403);

        if ($deposit->status === 'deposited') {
            return redirect()->route('billing')->with('status', 'This PayPal deposit has already been captured.');
        }

        $token = $request->string('token')->value();
        $orderId = $deposit->paypal_order_id ?: $token;

        if (! $orderId || ($token && $deposit->paypal_order_id && $token !== $deposit->paypal_order_id)) {
            return redirect()->route('billing')->withErrors([
                'payment' => 'The returned PayPal order did not match the expected wallet top-up.',
            ]);
        }

        try {
            $capture = $this->payPalCheckoutService->captureOrder($orderId);

            if (($capture['status'] ?? null) !== 'COMPLETED' && ($capture['capture_status'] ?? null) !== 'COMPLETED') {
                $deposit->status = 'failed';
                $deposit->save();

                return redirect()->route('billing')->withErrors([
                    'payment' => 'PayPal did not complete the wallet top-up.',
                ]);
            }

            $deposit->paypal_order_id = $orderId;
            $deposit->paypal_capture_id = $capture['capture_id'] ?? null;
            $deposit->status = 'deposited';
            $deposit->processed_at = now();
            $deposit->save();

            return redirect()->route('billing')->with('status', 'PayPal payment captured and added to your wallet.');
        } catch (Throwable $e) {
            $deposit->status = 'failed';
            $deposit->save();

            return redirect()->route('billing')->withErrors([
                'payment' => $e->getMessage(),
            ]);
        }
    }

    public function cancelDeposit(ProjectFunding $deposit)
    {
        abort_unless($deposit->client_id === Auth::id(), 403);

        if ($deposit->status === 'pending') {
            $deposit->status = 'failed';
            $deposit->save();
        }

        return redirect()->route('billing')->withErrors([
            'payment' => 'PayPal wallet top-up was cancelled.',
        ]);
    }

    public function fundJob(Request $request, Job $job)
    {
        abort_unless($job->user_id === Auth::id(), 403);

        $validated = $request->validate([
            'amount' => 'nullable|numeric|min:0.01',
        ]);

        $amount = $validated['amount'] ?? null;

        $this->escrowPayoutService->fundJob($job, $amount ? (float) $amount : null);

        return back()->with('status', 'Funds were moved into escrow for this job.');
    }
}
