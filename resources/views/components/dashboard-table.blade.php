@php
    $desktopTableClass = trim('min-w-full w-full divide-y divide-gray-200 ' . ($tableClass ?? ''));
    $desktopHeadClass = trim(($headClass ?? 'bg-gray-50'));
    $desktopBodyClass = trim(($bodyClass ?? 'bg-white divide-y divide-gray-200'));
    $desktopRowClass = trim('transition-colors text-left duration-200 hover:bg-gray-50 ' . ($rowClass ?? ''));
@endphp

<style>
    .dashboard-table-mobile-card {
        border: 1px solid #e5e7eb;
        border-radius: 0.35rem;
        background: #fff;
        padding: 1rem;
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.06);
        width: 100%;
    }

    .dashboard-table-mobile-pill {
        border-radius: 999px;
        background: #f8fafc;
        padding: 0.25rem 0.7rem;
        font-size: 0.78rem;
        font-weight: 600;
        color: #64748b;
    }

    @media (max-width: 767px) {
        .dashboard-table-mobile-card {
            padding: 1rem 1rem 0.9rem;
            border-radius: 0.75rem;
            box-shadow: 0 14px 32px rgba(15, 23, 42, 0.08);
        }

        .dashboard-table-mobile-card .mobile-table-header {
            gap: 0.75rem;
        }

        .dashboard-table-mobile-card .mobile-table-title {
            font-size: 1rem;
            line-height: 1.4;
        }

        .dashboard-table-mobile-card .mobile-table-value {
            font-size: 1rem;
        }

        .dashboard-table-mobile-card .mobile-table-row {
            display: grid;
            grid-template-columns: minmax(0, 1fr);
            gap: 0.2rem;
            padding: 0.45rem 0;
            border-top: 1px solid #f1f5f9;
        }

        .dashboard-table-mobile-card .mobile-table-row:first-child {
            border-top: none;
            padding-top: 0;
        }

        .dashboard-table-mobile-card .mobile-table-row-label {
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            color: #64748b;
        }

        .dashboard-table-mobile-card .mobile-table-actions > * {
            width: 100%;
        }
    }
</style>

<div class="hidden w-full md:block overflow-x-auto">
    <table class="{{ $desktopTableClass }}">
        <thead class="{{ $desktopHeadClass }}">
            <tr>
                @foreach ($headers as $header)
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                        {{ $header }}
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody class="{{ $desktopBodyClass }}">
            @forelse ($items as $item)
                @php
                    $resolvedRowUrl = is_callable($rowUrl) ? $rowUrl($item) : null;
                @endphp
                <tr
                    class="{{ $desktopRowClass }} {{ $resolvedRowUrl ? 'cursor-pointer' : '' }}"
                    @if ($resolvedRowUrl)
                        onclick="window.location.href='{{ $resolvedRowUrl }}'"
                    @endif
                >
                    @foreach ($rows as $rowCallback)
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            {!! $rowCallback($item) !!}
                        </td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($headers) }}" class="px-6 py-8 text-center text-sm text-gray-500">
                        @isset($empty)
                            {{ $empty }}
                        @else
                            No records found.
                        @endisset
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="w-full space-y-4 md:hidden">
    @forelse ($items as $item)
        @php
            $primaryIndex = $mobileConfig['primaryIndex'] ?? null;
            $statusIndex = $mobileConfig['statusIndex'] ?? null;
            $actionIndex = $mobileConfig['actionIndex'] ?? null;
            $titleIndex = $mobileConfig['titleIndex'] ?? 0;
            $subtitleIndex = $mobileConfig['subtitleIndex'] ?? null;
            $excludeIndices = $mobileConfig['excludeIndices'] ?? [];
            $resolvedRowUrl = is_callable($rowUrl) ? $rowUrl($item) : null;
        @endphp

        <div
            class="dashboard-table-mobile-card {{ $resolvedRowUrl ? 'cursor-pointer' : '' }}"
            @if ($resolvedRowUrl)
                onclick="window.location.href='{{ $resolvedRowUrl }}'"
            @endif
        >
            <div class="mobile-table-header flex items-start justify-between gap-3 border-b border-gray-100 pb-3">
                <div class="min-w-0">
                    @if ($showId)
                        <div class="mb-2">
                            <span class="dashboard-table-mobile-pill">#{{ $item->id }}</span>
                        </div>
                    @endif
                    <div class="mobile-table-title text-base font-semibold text-gray-900">
                        {!! $rows[$titleIndex]($item) !!}
                    </div>
                    @if (!is_null($subtitleIndex) && isset($rows[$subtitleIndex]))
                        <div class="mt-1 text-sm text-gray-500">
                            {!! $rows[$subtitleIndex]($item) !!}
                        </div>
                    @endif
                </div>

                <div class="flex flex-col items-end gap-2 text-right">
                    @if (!is_null($statusIndex) && isset($rows[$statusIndex]))
                        <div>{!! $rows[$statusIndex]($item) !!}</div>
                    @endif
                    @if (!is_null($primaryIndex) && isset($rows[$primaryIndex]))
                        <div class="mobile-table-value text-base font-bold text-gray-900">
                            {!! $rows[$primaryIndex]($item) !!}
                        </div>
                    @endif
                </div>
            </div>

            <div class="mt-3 space-y-2">
                @foreach ($rows as $index => $rowCallback)
                    @if (
                        !in_array($index, $excludeIndices, true)
                        && $index !== $titleIndex
                        && $index !== $subtitleIndex
                        && $index !== $primaryIndex
                        && $index !== $statusIndex
                        && $index !== $actionIndex
                    )
                        <div class="mobile-table-row flex items-start justify-between gap-3 py-1">
                            <span class="mobile-table-row-label text-sm font-medium text-gray-500">{{ $headers[$index] ?? 'Field' }}</span>
                            <span class="text-sm text-right text-gray-900">{!! $rowCallback($item) !!}</span>
                        </div>
                    @endif
                @endforeach
            </div>

            @if (!is_null($actionIndex) && isset($rows[$actionIndex]))
                <div class="mobile-table-actions mt-4 border-t border-gray-100 pt-3">
                    {!! $rows[$actionIndex]($item) !!}
                </div>
            @endif
        </div>
    @empty
        <div class="dashboard-table-mobile-card text-center text-sm text-gray-500">
            @isset($empty)
                {{ $empty }}
            @else
                No records found.
            @endisset
        </div>
    @endforelse
</div>
