<?php

namespace App\Http\Controllers\Freelancer;

use App\Models\Milestone;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MilestoneController extends Controller
{
    public function request(Milestone $milestone)
    {
        $this->authorizeRequester($milestone);
        $milestone->request();
        return back()->with('status', 'Milestone requested.');
    }

    private function authorizeRequester(Milestone $milestone)
    {
        if ($milestone->project->freelancer_id !== \Illuminate\Support\Facades\Auth::user()->id) {
            abort(403);
        }
    }
}