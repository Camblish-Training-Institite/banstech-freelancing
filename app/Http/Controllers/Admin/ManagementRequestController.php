<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ManagementRequestController extends Controller
{
    public function accept(ManagementRequest $request)
    {
        abort_if(auth()->user()->hasRole('project_manager') === false, 403);

        $request->markAsAccepted(auth()->user());

        return redirect()->back()->with('success', 'You now manage this project.');
    }

    public function reject(ManagementRequest $request)
    {
        abort_if(auth()->user()->hasRole('project_manager') === false, 403);

        $request->markAsRejected();

        return redirect()->back()->with('info', 'Request rejected.');
    }
}
