<?php

namespace App\Http\Controllers\Freelancer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EarningsController extends Controller
{
    public function myEarnings()
    {
        return view('dashboards.freelancer.earnings');
    }
}
