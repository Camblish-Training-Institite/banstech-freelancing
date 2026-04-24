<?php

namespace App\Http\Controllers\Messaging;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\ConversationMessage;
use App\Models\JobInvite;
use App\Models\User;
use App\Services\Contracts\AssignFreelancerToJobService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use RuntimeException;

class InboxController extends Controller
{
    public function __construct(
        protected AssignFreelancerToJobService $assignFreelancerToJob
    ) {
    }

    public function indexClient(?Conversation $conversation = null): View
    {
        return $this->renderInbox('client', $conversation);
    }

    public function indexFreelancer(?Conversation $conversation = null): View
    {
        return $this->renderInbox('freelancer', $conversation);
    }

    public function startClientConversation(Request $request, User $freelancer): RedirectResponse
    {
        $client = Auth::user();
        $intent = $request->string('intent')->toString();

        abort_if((int) $freelancer->id === (int) $client->id, 403);

        $hasOpenJobs = $client->jobs()->where('status', 'open')->exists();
        if (! $hasOpenJobs) {
            return back()->with('error', 'You need at least one open job before you can message a freelancer.');
        }

        $conversation = Conversation::firstOrCreate([
            'client_id' => $client->id,
            'freelancer_id' => $freelancer->id,
        ], [
            'last_message_at' => now(),
        ]);

        $redirectUrl = route('client.inbox.show', $conversation);
        if ($intent === 'invite') {
            $redirectUrl .= '?intent=invite';
        }

        return redirect($redirectUrl);
    }

    public function sendClientMessage(Request $request, Conversation $conversation): RedirectResponse
    {
        return $this->sendMessage($request, $conversation, 'client');
    }

    public function sendFreelancerMessage(Request $request, Conversation $conversation): RedirectResponse
    {
        return $this->sendMessage($request, $conversation, 'freelancer');
    }

    public function sendInvite(Request $request, Conversation $conversation): RedirectResponse
    {
        $user = Auth::user();
        $this->ensureClientConversation($conversation, $user);

        $validated = $request->validate([
            'job_id' => 'required|integer',
            'message' => 'nullable|string|max:2000',
            'milestone_count' => 'nullable|integer|min:1|max:50',
        ]);

        $job = $user->jobs()
            ->where('status', 'open')
            ->whereDoesntHave('contract')
            ->findOrFail($validated['job_id']);

        $existingPendingInvite = JobInvite::where('conversation_id', $conversation->id)
            ->where('job_id', $job->id)
            ->where('status', 'pending')
            ->first();

        if ($existingPendingInvite) {
            return back()->with('error', 'There is already a pending invite for this job in the conversation.');
        }

        DB::transaction(function () use ($conversation, $user, $validated, $job) {
            $invite = JobInvite::create([
                'conversation_id' => $conversation->id,
                'job_id' => $job->id,
                'client_id' => $user->id,
                'freelancer_id' => $conversation->freelancer_id,
                'message' => $validated['message'] ?? null,
                'milestone_count' => (int) ($validated['milestone_count'] ?? 1),
                'status' => 'pending',
            ]);

            ConversationMessage::create([
                'conversation_id' => $conversation->id,
                'sender_id' => $user->id,
                'job_invite_id' => $invite->id,
                'type' => 'job_invite',
                'body' => $validated['message'] ?? null,
            ]);

            $conversation->touchActivity();
        });

        return back()->with('status', 'Job invite sent.');
    }

    public function respondToInvite(Request $request, JobInvite $invite): RedirectResponse
    {
        $user = Auth::user();
        abort_unless((int) $invite->freelancer_id === (int) $user->id, 403);

        $validated = $request->validate([
            'action' => 'required|in:accept,reject',
        ]);

        if ($invite->status !== 'pending') {
            return back()->with('error', 'This invite has already been handled.');
        }

        $conversation = $invite->conversation()->with(['client.profile', 'freelancer.profile'])->firstOrFail();

        try {
            DB::transaction(function () use ($validated, $invite, $conversation, $user) {
                if ($validated['action'] === 'accept') {
                    $this->assignFreelancerToJob->handle(
                        $invite->job()->firstOrFail(),
                        $user,
                        (float) $invite->job->budget,
                        (int) $invite->milestone_count
                    );

                    $invite->forceFill([
                        'status' => 'accepted',
                        'responded_at' => now(),
                    ])->save();

                    JobInvite::where('job_id', $invite->job_id)
                        ->where('id', '!=', $invite->id)
                        ->where('status', 'pending')
                        ->update([
                            'status' => 'expired',
                            'responded_at' => now(),
                        ]);

                    $systemMessage = 'Invite accepted. The job has been assigned and the project is now active.';
                } else {
                    $invite->forceFill([
                        'status' => 'rejected',
                        'responded_at' => now(),
                    ])->save();

                    $systemMessage = 'Invite declined.';
                }

                ConversationMessage::create([
                    'conversation_id' => $conversation->id,
                    'sender_id' => $user->id,
                    'type' => 'system',
                    'body' => $systemMessage,
                ]);

                $conversation->touchActivity();
            });
        } catch (RuntimeException $exception) {
            return back()->with('error', $exception->getMessage());
        }

        return back()->with('status', $validated['action'] === 'accept'
            ? 'Invite accepted and project created.'
            : 'Invite declined.');
    }

    protected function renderInbox(string $panel, ?Conversation $conversation = null): View
    {
        $user = Auth::user();

        $conversations = Conversation::with(['client.profile', 'freelancer.profile'])
            ->where(function ($query) use ($user) {
                $query->where('client_id', $user->id)
                    ->orWhere('freelancer_id', $user->id);
            })
            ->orderByDesc(DB::raw('COALESCE(last_message_at, updated_at)'))
            ->get();

        $activeConversation = $conversation;
        if ($activeConversation) {
            $this->ensureParticipant($activeConversation, $user);
        } else {
            $activeConversation = $conversations->first();
        }

        if ($activeConversation) {
            $activeConversation->load([
                'client.profile',
                'freelancer.profile',
                'messages.sender.profile',
                'messages.jobInvite.job',
            ]);
        }

        $openJobs = $panel === 'client'
            ? $user->jobs()->where('status', 'open')->whereDoesntHave('contract')->latest()->get()
            : collect();

        return view('messaging.index', [
            'panel' => $panel,
            'conversations' => $conversations,
            'activeConversation' => $activeConversation,
            'openJobs' => $openJobs,
        ]);
    }

    protected function sendMessage(Request $request, Conversation $conversation, string $panel): RedirectResponse
    {
        $user = Auth::user();
        $this->ensureParticipant($conversation, $user);

        if ($panel === 'client') {
            $this->ensureClientConversation($conversation, $user);
        } else {
            abort_unless((int) $conversation->freelancer_id === (int) $user->id, 403);
        }

        $validated = $request->validate([
            'body' => 'required|string|max:5000',
        ]);

        ConversationMessage::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $user->id,
            'type' => 'text',
            'body' => $validated['body'],
        ]);

        $conversation->touchActivity();

        return back()->with('status', 'Message sent.');
    }

    protected function ensureParticipant(Conversation $conversation, User $user): void
    {
        abort_unless(
            (int) $conversation->client_id === (int) $user->id
            || (int) $conversation->freelancer_id === (int) $user->id,
            403
        );
    }

    protected function ensureClientConversation(Conversation $conversation, User $user): void
    {
        abort_unless((int) $conversation->client_id === (int) $user->id, 403);
    }
}
