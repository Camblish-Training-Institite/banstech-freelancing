<div class="favorite-list-item">
    @if($user)
        @php
            // Check if profile exists and avatar path is not null/empty
            $avatarPathFromDB = optional($user->profile)->avatar; 

            // CORRECTED LOGIC: Prepend 'avatars/' to the asset path if needed
            $avatarUrl = $avatarPathFromDB 
                ? asset('storage/' . $avatarPathFromDB) 
                : 'https://ui-avatars.com/api/?name='. $user->name .'&background=random&size=128';
        @endphp
        <div data-id="{{ $user->id }}" data-action="0" class="avatar av-m"
            style="background-image: url('{{ $avatarUrl }}');">
        </div>
        <p>{{ strlen($user->name) > 5 ? substr($user->name,0,6).'..' : $user->name }}</p>
    @endif
</div>
