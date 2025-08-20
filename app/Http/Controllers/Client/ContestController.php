<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Contest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class ContestController extends Controller
{
    public function index()
    {
        $contests = Contest::where('client_id', Auth::id())->get();
        return view('Users.Clients.layouts.contest-section', compact('contests'));
    }

    public function create()
    {
        return view('Users.Clients.contests.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'prize_money' => 'required|numeric|min:0',
            'closing_date' => 'required|date|after_or_equal:today',
            'required_skills' => 'string',
        ]);

        $req_skills = $request->input('required_skills');
        $skills = is_array($req_skills) ? implode(',', $req_skills) : $req_skills;

        $contest = Contest::create([
            'client_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'prize_money' => $request->prize_money,
            'closing_date' => $request->closing_date,
            'required_skills' => $skills,
        ]);

        return redirect()->route('client.contests.index')->with('success', 'Contest created successfully.');
    }

    public function edit(Contest $contest)
    {
        // $this->authorize('update', $contest);
        if (!Auth::check()) {
        } // Ensure only the client can edit
        return view('Users.Clients.contests.edit', compact('contest'));
    }

    public function update(Request $request, Contest $contest)
    {
        // $this->authorize('update', $contest);
        if (!Auth::check()) {
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'prize_money' => 'required|numeric|min:0',
            'closing_date' => 'required|date|after_or_equal:today',
            'required_skills' => 'nullable|array',
            'required_skills.*' => 'string|max:255',
        ]);

        $contest->update([
            'title' => $request->title,
            'description' => $request->description,
            'prize_money' => $request->prize_money,
            'closing_date' => $request->closing_date,
            'required_skills' => $request->required_skills,
        ]);

        return redirect()->route('Clients.contests.index')->with('success', 'Contest updated successfully.');
    }

    public function show(Contest $contest)
    {
        // Ensure the contest belongs to this client
        if ($contest->client_id !== auth()->id()) {
            abort(403);
        }
        return view('Users.Clients.contests.show', compact('contest'));
    }

    public function destroy(Contest $contest)
    {
        // $this->authorize('delete', $contest);
        if (!Auth::check()) {
        }

        $contest->delete();

        return redirect()->route('Clients.contests.index')->with('success', 'Contest deleted successfully.');
    }
}
