<style>
    .user-avatar{
        max-height: 3rem;
        max-width: 3rem;
        margin-right:0.5rem;
        overflow: hidden;
    }
</style>

@php
    $width = $size ?? '4rem';
    $height = $size ?? '4rem';
@endphp

<div class="flex flex-col items-center justify-center rounded-full bg-white overflow-hidden object-cover mr-2" style="height: {{$height ?? '3rem'}}; width: {{$width ?? '3rem'}};">
    <img class="w-full h-full rounded-full object-cover" width="{{$width}}" height="{{$height}}"
        src="{{ $user->profile ? asset('storage/' . $user->profile->avatar) : 'https://ui-avatars.com/api/?name= '.$user->name .'&background=random&size=128' }}"
        alt="{{ $user->name }}">
</div>