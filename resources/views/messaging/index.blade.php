@extends($panel === 'client' ? 'Users.Clients.layouts.body.dashboard-body' : 'Users.Freelancers.layouts.body.dashboard-body')

@section('active-tab')
    @php
        $isClientPanel = $panel === 'client';
        $showInviteComposer = $isClientPanel && $activeConversation && $openJobs->isNotEmpty();
        $intent = request()->query('intent');
    @endphp

    <style>
        .inbox-shell {
            display: grid;
            grid-template-columns: 320px minmax(0, 1fr);
            gap: 1.5rem;
            padding: 1.5rem;
        }

        .inbox-panel {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 1rem;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.06);
        }

        .thread-list {
            max-height: 75vh;
            overflow-y: auto;
        }

        .thread-link {
            display: block;
            padding: 1rem 1.1rem;
            border-bottom: 1px solid #eef2f7;
            text-decoration: none;
            color: #1f2937;
        }

        .thread-link.active {
            background: #ecfeff;
            border-left: 4px solid #0f766e;
            padding-left: calc(1.1rem - 4px);
        }

        .thread-link:hover {
            background: #f8fafc;
        }

        .messages-area {
            display: flex;
            flex-direction: column;
            min-height: 75vh;
        }

        .messages-feed {
            flex: 1;
            padding: 1.25rem;
            background: linear-gradient(180deg, #f8fafc 0%, #ffffff 100%);
            overflow-y: auto;
        }

        .message-row {
            display: flex;
            margin-bottom: 1rem;
        }

        .message-row.mine {
            justify-content: flex-end;
        }

        .message-bubble {
            max-width: 75%;
            padding: 0.9rem 1rem;
            border-radius: 1rem;
            background: #fff;
            border: 1px solid #e5e7eb;
        }

        .message-row.mine .message-bubble {
            background: #0f766e;
            color: #fff;
            border-color: #0f766e;
        }

        .system-note {
            text-align: center;
            color: #64748b;
            font-size: 0.9rem;
            margin: 1rem 0;
        }

        .invite-card {
            border: 1px solid #cbd5e1;
            background: #fff;
            border-radius: 1rem;
            padding: 1rem;
        }

        .invite-status {
            display: inline-flex;
            align-items: center;
            border-radius: 999px;
            padding: 0.3rem 0.7rem;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
        }

        .invite-status.pending { background: #fef3c7; color: #92400e; }
        .invite-status.accepted { background: #dcfce7; color: #166534; }
        .invite-status.rejected, .invite-status.expired { background: #fee2e2; color: #991b1b; }

        .composer {
            border-top: 1px solid #e5e7eb;
            padding: 1rem 1.25rem 1.25rem;
            background: #fff;
        }

        .composer textarea,
        .composer select,
        .composer input {
            width: 100%;
            border: 1px solid #cbd5e1;
            border-radius: 0.85rem;
            padding: 0.8rem 0.9rem;
            margin-top: 0.5rem;
        }

        .composer-actions {
            display: flex;
            gap: 0.75rem;
            margin-top: 0.9rem;
            flex-wrap: wrap;
        }

        .btn-primary,
        .btn-secondary,
        .btn-danger {
            border: none;
            border-radius: 0.85rem;
            padding: 0.8rem 1.1rem;
            font-weight: 600;
        }

        .btn-primary { background: #0f766e; color: #fff; }
        .btn-secondary { background: #e2e8f0; color: #1f2937; }
        .btn-danger { background: #991b1b; color: #fff; }

        @media (max-width: 960px) {
            .inbox-shell {
                grid-template-columns: 1fr;
            }

            .messages-area,
            .thread-list {
                max-height: none;
            }

            .message-bubble {
                max-width: 100%;
            }
        }
    </style>

    <div class="inbox-shell">
        <aside class="inbox-panel">
            <div class="p-5 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-900">Inbox</h2>
                <p class="mt-1 text-sm text-slate-500">
                    {{ $isClientPanel ? 'Your freelancer conversations and invites.' : 'Your client conversations and invite history.' }}
                </p>
            </div>

            <div class="thread-list">
                @forelse ($conversations as $conversation)
                    @php
                        $otherUser = $conversation->otherParticipantFor(Auth::user());
                        $isActive = $activeConversation && $activeConversation->id === $conversation->id;
                        $threadRoute = $isClientPanel
                            ? route('client.inbox.show', $conversation)
                            : route('freelancer.inbox.show', $conversation);
                    @endphp
                    <a href="{{ $threadRoute }}" class="thread-link {{ $isActive ? 'active' : '' }}">
                        <div class="flex items-center gap-3">
                            @include('components.user-avatar', ['user' => $otherUser, 'width' => '2.75rem', 'height' => '2.75rem'])
                            <div class="min-w-0">
                                <div class="font-semibold text-slate-800 truncate">{{ $otherUser?->name ?? 'Unknown user' }}</div>
                                <div class="text-sm text-slate-500 truncate">
                                    {{ $conversation->last_message_at ? $conversation->last_message_at->diffForHumans() : 'New conversation' }}
                                </div>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="p-5 text-sm text-slate-500">
                        {{ $isClientPanel ? 'Start from a freelancer profile or a proposal to open your first thread.' : 'No client has contacted you yet.' }}
                    </div>
                @endforelse
            </div>
        </aside>

        <section class="inbox-panel messages-area">
            @if ($activeConversation)
                @php
                    $otherUser = $activeConversation->otherParticipantFor(Auth::user());
                    $messageRoute = $isClientPanel
                        ? route('client.inbox.messages.store', $activeConversation)
                        : route('freelancer.inbox.messages.store', $activeConversation);
                @endphp

                <div class="flex items-center justify-between gap-4 border-b border-slate-200 px-5 py-4">
                    <div class="flex items-center gap-3">
                        @include('components.user-avatar', ['user' => $otherUser, 'width' => '3rem', 'height' => '3rem'])
                        <div>
                            <h3 class="text-lg font-bold text-slate-900">{{ $otherUser?->name ?? 'Unknown user' }}</h3>
                            <p class="text-sm text-slate-500">
                                {{ $isClientPanel ? 'Freelancer thread' : 'Client thread' }}
                            </p>
                        </div>
                    </div>
                    @if ($isClientPanel && $openJobs->isEmpty())
                        <div class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-800">
                            No open jobs available to invite from
                        </div>
                    @endif
                </div>

                <div class="messages-feed">
                    @forelse ($activeConversation->messages as $message)
                        @php
                            $isMine = (int) $message->sender_id === (int) Auth::id();
                            $invite = $message->jobInvite;
                        @endphp

                        @if ($message->type === 'system')
                            <div class="system-note">{{ $message->body }}</div>
                        @elseif ($message->type === 'job_invite' && $invite)
                            <div class="message-row {{ $isMine ? 'mine' : '' }}">
                                <div class="message-bubble">
                                    <div class="invite-card">
                                        <div class="flex items-start justify-between gap-4">
                                            <div>
                                                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Job Invite</p>
                                                <h4 class="mt-1 text-lg font-bold text-slate-900">{{ $invite->job?->title ?? 'Job unavailable' }}</h4>
                                                <p class="mt-1 text-sm text-slate-600">
                                                    Budget: R{{ number_format((float) ($invite->job?->budget ?? 0), 2) }}
                                                    • {{ max(1, (int) $invite->milestone_count) }} milestone{{ (int) $invite->milestone_count === 1 ? '' : 's' }}
                                                </p>
                                            </div>
                                            <span class="invite-status {{ $invite->status }}">{{ ucfirst($invite->status) }}</span>
                                        </div>

                                        @if ($invite->message)
                                            <p class="mt-3 text-sm text-slate-700">{{ $invite->message }}</p>
                                        @endif

                                        @if ($panel === 'freelancer' && $invite->status === 'pending')
                                            <div class="composer-actions">
                                                <form method="POST" action="{{ route('freelancer.inbox.invites.respond', $invite) }}">
                                                    @csrf
                                                    <input type="hidden" name="action" value="accept">
                                                    <button type="submit" class="btn-primary">Accept Invite</button>
                                                </form>
                                                <form method="POST" action="{{ route('freelancer.inbox.invites.respond', $invite) }}">
                                                    @csrf
                                                    <input type="hidden" name="action" value="reject">
                                                    <button type="submit" class="btn-danger">Decline</button>
                                                </form>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="message-row {{ $isMine ? 'mine' : '' }}">
                                <div class="message-bubble">
                                    <div class="text-xs {{ $isMine ? 'text-teal-100' : 'text-slate-500' }}">
                                        {{ $message->sender?->name ?? 'System' }} • {{ $message->created_at->format('M d, Y H:i') }}
                                    </div>
                                    <div class="mt-2 whitespace-pre-wrap">{{ $message->body }}</div>
                                </div>
                            </div>
                        @endif
                    @empty
                        <div class="system-note">No messages yet. Start the conversation below.</div>
                    @endforelse
                </div>

                <div class="composer">
                    <form method="POST" action="{{ $messageRoute }}">
                        @csrf
                        <label class="block text-sm font-semibold text-slate-700">
                            Message
                            <textarea name="body" rows="3" placeholder="Type your message here. Emoji from your keyboard will work too."></textarea>
                        </label>
                        <div class="composer-actions">
                            <button type="submit" class="btn-primary">Send Message</button>
                        </div>
                    </form>

                    @if ($showInviteComposer)
                        <div id="invite-panel" class="mt-6 rounded-2xl border border-teal-100 bg-teal-50 p-4">
                            <h4 class="text-lg font-bold text-slate-900">Send Job Invite</h4>
                            <p class="mt-1 text-sm text-slate-600">
                                Invite this freelancer to one of your open jobs. If they accept, the project is created immediately.
                            </p>
                            <form method="POST" action="{{ route('client.inbox.invites.store', $activeConversation) }}" class="mt-4">
                                @csrf
                                <label class="block text-sm font-semibold text-slate-700">
                                    Choose Job
                                    <select name="job_id" {{ $intent === 'invite' ? 'autofocus' : '' }}>
                                        @foreach ($openJobs as $job)
                                            <option value="{{ $job->id }}">{{ $job->title }} • R{{ number_format((float) $job->budget, 2) }}</option>
                                        @endforeach
                                    </select>
                                </label>
                                <label class="mt-3 block text-sm font-semibold text-slate-700">
                                    Milestones
                                    <input type="number" name="milestone_count" min="1" max="50" value="1">
                                </label>
                                <label class="mt-3 block text-sm font-semibold text-slate-700">
                                    Invite Note
                                    <textarea name="message" rows="3" placeholder="Add a short note about the scope, timeline, or fit."></textarea>
                                </label>
                                <div class="composer-actions">
                                    <button type="submit" class="btn-secondary">Send Job Invite</button>
                                </div>
                            </form>
                        </div>
                    @endif
                </div>
            @else
                <div class="flex h-full min-h-[40vh] items-center justify-center p-8 text-center text-slate-500">
                    <div>
                        <h3 class="text-xl font-bold text-slate-800">No conversation selected</h3>
                        <p class="mt-2">
                            {{ $isClientPanel ? 'Start by opening a freelancer profile or viewing a proposal.' : 'Your conversations will appear here once a client reaches out.' }}
                        </p>
                    </div>
                </div>
            @endif
        </section>
    </div>
@endsection
