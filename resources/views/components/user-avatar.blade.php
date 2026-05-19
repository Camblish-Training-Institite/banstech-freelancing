<style>
    .user-avatar{
        max-height: 3rem;
        max-width: 3rem;
        margin-right:0.5rem;
        overflow: hidden;
    }
</style>

@php
    $width = $width ?? $size ?? '4rem';
    $height = $height ?? $size ?? '4rem';
    $avatar = $user->profile?->avatar;
@endphp

<div class="flex flex-col items-center justify-center rounded-full bg-white overflow-hidden object-cover mr-2" style="height: {{$height ?? '3rem'}}; width: {{$width ?? '3rem'}};">
    <img class="w-full h-full rounded-full object-cover" width="{{$width}}" height="{{$height}}"
        src="{{ $avatar ? asset('storage/' . $avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=random&size=128' }}"
        alt="{{ $user->name }}">
</div>
