@props(['item'])
@php
    $name = $item->languages->name;
    $canonical = write_url($item->languages->canonical);
    $description = $item->languages->description;
    $image = $item->image;
    $rate = rand(75, 100);
@endphp

<div class="major-item">
    <a href="{{ $canonical }}" class="image img-cover"><img src="{{ $image }}" alt="{{ $name }}"></a>
    <div class="info">
        <h3 class="title"><a href="{{ $canonical }}" title="{{ $name }}">{{ $name }}</a></h3>
        <div class="description">
            {!! $description !!}
        </div>
    </div>
    <div class="overlay">
        <div class="uk-flex uk-flex-middle">
            <span>Đánh giá: </span>
            <div class="star-rating uk-flex uk-flex-right">
                <div class="stars" style="--star-width: {{ $rate }}%"></div>
            </div>
        </div>
    </div>
</div>