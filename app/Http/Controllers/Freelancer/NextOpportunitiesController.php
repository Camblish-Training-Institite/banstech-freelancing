<?php

namespace App\Http\Controllers\Freelancer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NextOpportunitiesController extends Controller
{
   function nextOpportunities()
    {
        // Fetch next opportunities from database
        // $opportunities = auth()->user()->nextOpportunities()->where('status', 'upcoming')->get();

        return view('dashboards.Components.find-next-opportunity');
    }
}
