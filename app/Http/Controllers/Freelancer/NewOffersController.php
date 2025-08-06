<?php

namespace App\Http\Controllers\Freelancer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NewOffersController extends Controller
{
    function newOffers()
    {
        // Fetch new offers from database
        // $offers = auth()->user()->newOffers()->where('status', 'new')->get();

        return view('dashboards.Components.new-offer-card');
    }
}
