@extends('layouts.admin')

@section('content')
<div class="mx-auto py-10 sm:px-6 lg:px-8" style="width: 85%;">
    @if(session('success'))
        <div class="mb-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800">Withdrawal Request #{{ $withdrawal->id }}</h1>
        <a href="{{ route('admin.withdrawals.index') }}" class="bg-white border border-gray-300 rounded-md px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Back to queue</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-4">Freelancer</h2>
            <p class="text-sm font-semibold text-gray-900">{{ $withdrawal->user->name }}</p>
            <p class="text-sm text-gray-600">{{ $withdrawal->user->email }}</p>
            <p class="mt-4 text-sm text-gray-500">Method: <span class="font-semibold text-gray-800">{{ ucfirst($withdrawal->method) }}</span></p>
            <p class="mt-2 text-sm text-gray-500">Amount: <span class="font-semibold text-gray-800">R{{ number_format($withdrawal->amount, 2) }}</span></p>
            <p class="mt-2 text-sm text-gray-500">Status:
                <span class="font-semibold {{ $withdrawal->status === 'processed' ? 'text-green-700' : ($withdrawal->status === 'failed' ? 'text-red-700' : ($withdrawal->status === 'confirmed' ? 'text-blue-700' : 'text-yellow-700')) }}">
                    {{ ucfirst($withdrawal->status) }}
                </span>
            </p>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-4">Timeline</h2>
            <p class="text-sm text-gray-600">Requested: {{ optional($withdrawal->requested_at)->format('d M Y H:i') ?? 'N/A' }}</p>
            <p class="text-sm text-gray-600 mt-2">Confirmed: {{ optional($withdrawal->confirmed_at)->format('d M Y H:i') ?? 'Not yet' }}</p>
            <p class="text-sm text-gray-600 mt-2">Processed: {{ optional($withdrawal->processed_at)->format('d M Y H:i') ?? 'Not yet' }}</p>
            <p class="text-sm text-gray-600 mt-2">Reviewed By: {{ $withdrawal->reviewedByAdmin?->name ?? 'System / not yet assigned' }}</p>
            @if($withdrawal->failure_reason)
                <p class="text-sm text-red-600 mt-4">Failure reason: {{ $withdrawal->failure_reason }}</p>
            @endif
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-4">Destination</h2>
            @if($withdrawal->method === 'paypal')
                <p class="text-sm text-gray-600">Recipient Email: {{ $withdrawal->paypal_recipient_email ?: data_get($withdrawal->destination_details, 'email', 'N/A') }}</p>
                <p class="text-sm text-gray-600 mt-2">PayPal Batch ID: {{ $withdrawal->paypal_batch_id ?: 'Not yet created' }}</p>
                <p class="text-sm text-gray-600 mt-2">PayPal Item ID: {{ $withdrawal->paypal_payout_item_id ?: 'Not yet created' }}</p>
                <p class="mt-4 text-sm text-blue-700">PayPal withdrawals only count as sent once a real PayPal batch ID exists.</p>
            @else
                <p class="text-sm text-gray-600">Bank: {{ data_get($withdrawal->destination_details, 'bank_name', 'N/A') }}</p>
                <p class="text-sm text-gray-600 mt-2">Account Holder: {{ data_get($withdrawal->destination_details, 'account_holder_name', 'N/A') }}</p>
                <p class="text-sm text-gray-600 mt-2">Account Number: ...{{ substr((string) data_get($withdrawal->destination_details, 'account_number', ''), -4) ?: 'N/A' }}</p>
                <p class="text-sm text-gray-600 mt-2">Branch Code: {{ data_get($withdrawal->destination_details, 'branch_code', 'N/A') }}</p>
                <p class="text-sm text-gray-600 mt-2">SWIFT Code: {{ data_get($withdrawal->destination_details, 'swift_code', 'N/A') }}</p>
                <p class="mt-4 text-sm text-amber-700">Bank withdrawals are manual. Confirm first, then mark processed only after the real transfer is complete.</p>
            @endif
        </div>

        <div class="md:col-span-3 bg-white shadow rounded-lg p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-4">Admin Actions</h2>
            <div class="flex flex-wrap gap-3">
                @if(in_array($withdrawal->status, ['pending', 'failed'], true))
                    <form method="POST" action="{{ route('admin.withdrawals.confirm', $withdrawal) }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                            {{ $withdrawal->method === 'paypal' ? 'Confirm / Send PayPal' : 'Confirm Withdrawal' }}
                        </button>
                    </form>
                @endif

                @if($withdrawal->method === 'paypal' && in_array($withdrawal->status, ['pending', 'confirmed', 'failed'], true))
                    <form method="POST" action="{{ route('admin.withdrawals.retry-paypal', $withdrawal) }}">
                        @csrf
                        <button type="submit" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                            Retry PayPal
                        </button>
                    </form>
                @endif

                @if($withdrawal->method === 'bank' && $withdrawal->status === 'confirmed')
                    <form method="POST" action="{{ route('admin.withdrawals.process', $withdrawal) }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="rounded-md bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700">
                            Mark Bank Transfer Processed
                        </button>
                    </form>
                @endif
            </div>

            @if($withdrawal->method === 'paypal')
                <p class="mt-4 text-sm text-gray-500">
                    Use `Confirm / Send PayPal` to create the actual payout. After that, the PayPal sync will move the request to `processed` automatically once PayPal reports completion.
                </p>
            @else
                <p class="mt-4 text-sm text-gray-500">
                    `Confirm Withdrawal` means approved and ready for manual transfer. `Mark Bank Transfer Processed` should only be used after the real transfer has been completed outside the platform.
                </p>
            @endif

            <form method="POST" action="{{ route('admin.withdrawals.fail', $withdrawal) }}" class="mt-6">
                @csrf
                @method('PATCH')
                <label for="failure_reason" class="block text-sm font-medium text-gray-700">Failure Reason</label>
                <textarea id="failure_reason" name="failure_reason" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Optional note explaining why this request failed or was rejected.">{{ old('failure_reason', $withdrawal->failure_reason) }}</textarea>
                <button type="submit" class="mt-3 rounded-md bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700">
                    Mark Failed
                </button>
            </form>
        </div>

        @if($withdrawal->gateway_response)
            <div class="md:col-span-3 bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Gateway Response</h2>
                <pre class="overflow-x-auto rounded-lg bg-gray-900 p-4 text-xs text-green-200">{{ json_encode($withdrawal->gateway_response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
            </div>
        @endif
    </div>
</div>
@endsection
