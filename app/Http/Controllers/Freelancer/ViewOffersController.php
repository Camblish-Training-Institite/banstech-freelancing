<?php

namespace App\Http\Controllers\Freelancer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ViewOffersController extends Controller
{
    public function viewOffers()
    {
        // Fetch proposals from database
        // $proposals = auth()->user()->appliedJobs()->where('status', 'active')->get();

        return view('dashboards.Components.view-offer');
    }
}
