<?php

namespace App\Http\Controllers\Freelancer;

use App\Http\Controllers\Controller;
use App\Models\Contest;
use App\Models\ContestEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class FreelancerContestController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $user = Auth::user();
        $contests = $user->contests;
        dd($contests);
        // $contests = Contest::where('status', 'open')->latest()->get();
        return view('Users.Freelancers.layouts.contest-section', compact('contests'));
    }

    public function show(Contest $contest)
    {
        // You might want to load entries here as well
        return view('Users.Freelancers.contests.show', compact('contest'));
    }

    /**
     * Show the form for creating a new contest entry.
     */
    public function showSubmitForm(Contest $contest)
    {
        // Prevent submission if contest is not open
        if ($contest->status !== 'open' || $contest->closing_date < now()->toDateString()) {
            return redirect()->route('freelancer.contests.show', $contest)->with('error', 'This contest is no longer accepting entries.');
        }

        return view('Users.Freelancers.contests.submit', compact('contest'));
    }

    /**
     * Store a newly created contest entry in storage.
     */
    public function storeEntry(Request $request, Contest $contest)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'licensed_content' => 'required|in:own,not_own',
            'sell_price' => 'nullable|numeric|min:0',
            'entry_files.*' => 'nullable|file|mimes:gif,jpeg,jpg,png|max:5120', // 5MB max per file
            'highlight' => 'nullable|boolean',
            'sealed' => 'nullable|boolean',
        ]);

        // Handle File Uploads
        $filePaths = [];
        if ($request->hasFile('entry_files')) {
            foreach ($request->file('entry_files') as $file) {
                // Store the file in 'public/contest_entries' and get the path
                $path = $file->store('contest_entries', 'public');
                $filePaths[] = $path;
            }
        }

        ContestEntry::create([
            'contest_id' => $contest->id,
            'freelancer_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'is_original' => $request->licensed_content === 'own',
            'sell_price' => $request->sell_price,
            'is_highlighted' => $request->filled('highlight'),
            'is_sealed' => $request->filled('sealed'),
            'files' => $filePaths, // Save paths as JSON
        ]);

        return redirect()->route('freelancer.contests.show', $contest)->with('success', 'Your entry has been submitted successfully!');
    }

    public function withdraw(ContestEntry $entry)
    {
        $this->authorize('withdraw', $entry); // You'll need a Policy for this
        $entry->delete();
        return redirect()->back()->with('success', 'Submission withdrawn.');
    }
}