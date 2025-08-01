<?php

namespace App\Http\Controllers\Freelancer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ContestController extends Controller
{
    public function contests()
    {
        // Fetch active contests from database
        // $contests = auth()->user()->appliedContests()->where('status', 'active')->get();

        return view('dashboards.freelancer.contests');
    }
}
