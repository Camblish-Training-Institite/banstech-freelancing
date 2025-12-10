@php
    // Define the full avatar URL using your existing application logic.
    // If $user is not available yet (which it might not be in the initial render), 
    // we use the current authenticated user as a fallback or display a default.
    
    // In Chatify's logic, $id is the currently active chat partner ID
    $activeId = isset($id) ? $id : request()->route('id');
    $infoUser = $activeId ? App\Models\User::find($activeId) : Auth::user();

    $avatarUrl = optional($infoUser->profile)->avatar
        ? asset('storage/' . $infoUser->profile->avatar)
        : 'https://ui-avatars.com/api/?name='. $infoUser->name .'&background=random&size=128';
@endphp

{{-- user info and avatar --}}
<div class="avatar av-l user-info-avatar" style="background-image: url('{{ $avatarUrl }}'); "></div>
<p class="info-name"></p>
<div class="messenger-infoView-btns">
    <a href="#" class="danger delete-conversation">Delete Conversation</a>
</div>
{{-- shared photos --}}
<div class="messenger-infoView-shared">
    <p class="messenger-title"><span>Shared Photos</span></p>
    <div class="shared-photos-list"></div>
</div>
