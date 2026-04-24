<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DashboardTable extends Component
{
    public function __construct(
        public mixed $headers,
        public mixed $rows,
        public mixed $items,
        public array $mobileConfig = [],
        public bool $showId = true,
        public ?string $tableClass = null,
        public ?string $headClass = null,
        public ?string $bodyClass = null,
        public ?string $rowClass = null,
        public mixed $rowUrl = null
    ) {
    }

    public function render(): View|Closure|string
    {
        return view('components.dashboard-table');
    }
}
