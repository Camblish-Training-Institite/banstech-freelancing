@extends('layouts.admin')

@section('content')
<div class="max-w-8xl mx-auto py-10 sm:px-6 lg:px-8">
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

    <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
        <form action="{{ route('admin.withdrawals.index') }}" method="GET" class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-700 uppercase">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Freelancer or batch id..." class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 uppercase">Status</label>
                    <select name="status" class="mt-1 block w-full p-2 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="">All Statuses</option>
                        @foreach (['pending', 'confirmed', 'processed', 'failed'] as $status)
                            <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 uppercase">Method</label>
                    <select name="method" class="mt-1 block w-full p-2 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="">All Methods</option>
                        <option value="paypal" {{ request('method') === 'paypal' ? 'selected' : '' }}>PayPal</option>
                        <option value="bank" {{ request('method') === 'bank' ? 'selected' : '' }}>Bank</option>
                    </select>
                </div>
                <div class="flex items-end space-x-2">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">Filter</button>
                    <a href="{{ route('admin.withdrawals.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300">Reset</a>
                </div>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200">
            <h1 class="text-2xl font-bold text-gray-800">{{ $pagetitle }}</h1>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Freelancer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Method</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Requested</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($withdrawalRequests as $withdrawal)
                        <tr class="hover:bg-gray-50 cursor-pointer" onclick="window.location.href='{{ route('admin.withdrawals.show', $withdrawal->id) }}'">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">#{{ $withdrawal->id }}</td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $withdrawal->user->name }}</div>
                                <div class="text-xs text-gray-500">{{ $withdrawal->user->email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ ucfirst($withdrawal->method) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">R{{ number_format($withdrawal->amount, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $withdrawal->status === 'processed' ? 'bg-green-100 text-green-800' : ($withdrawal->status === 'failed' ? 'bg-red-100 text-red-800' : ($withdrawal->status === 'confirmed' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800')) }}">
                                    {{ ucfirst($withdrawal->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ optional($withdrawal->requested_at)->format('d M Y H:i') ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('admin.withdrawals.show', $withdrawal->id) }}" class="text-indigo-600 hover:text-indigo-900">Manage</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">No withdrawal requests found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
            {{ $withdrawalRequests->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection
