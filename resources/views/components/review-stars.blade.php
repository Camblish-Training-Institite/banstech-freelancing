<div class="flex">
    @php
        $fullStars = floor($averageRating);
        $hasHalfStar = ($averageRating - $fullStars) >= 0.5;
        $totalDisplayStars = $fullStars + ($hasHalfStar ? 1 : 0);
    @endphp
    @for ($i = 0; $i < $fullStars; $i++)
        <i class="fas fa-star"></i>
    @endfor

    @if ($hasHalfStar)
        <i class="fas fa-star-half-alt"></i>
    @endif

    @for ($i = 0; $i < (5 - $totalDisplayStars); $i++)
        <i class="far fa-star"></i>
    @endfor
</div>