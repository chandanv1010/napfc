@props(['item'])
@php
    $code = $item->code;
    $name = $item->languages->first()->pivot->name;
    $description = cutnchar(strip_tags($item->languages->first()->pivot->description));
    $content = $item->languages->first()->pivot->extraDescription;
    $image = $item->image;
    $logo = $item->logo;
    $rank = $item->rank;
    $rate = $item->rate;
    $canonical = write_url($item->languages->first()->pivot->canonical);
    $catName = $item->school_catalogues->first()->languages->first()->pivot->name;
    $area = $item->school_areas->name ?? null;
    $projects = $item->school_projects ?? null;
@endphp
<div class="school-item">
    <a href="{{ $canonical }}" class="image img-cover">
        <img src="{{ $image }}" alt="{{ $name }}">
        <span class="school-logo">
            <img src="{{ $logo }}" alt="logo">
        </span>
    </a>
    <div class="info">
        <div class="info-content">
            <h3 class="title"><a href="{{ $canonical }}" title="{{ $name }}">{{ $name }}</a></h3>
        </div>
        <div class="extra-description">
            <p>{{ $catName }}</p>
            <p>Khu vực : {{ $area }}</p>
            <p>Mã trường : {{  $code }}</p>
        </div>
        @if(isset($projects) && !is_null($projects) && count($projects))
            <div class="project uk-flex">
                @foreach ($projects as $item)
                    @php
                       $pj_name = $item->name;
                    @endphp
                    <span class="text-box-default">{{ $pj_name }}</span>
                @endforeach
            </div>
        @endif
        <a href="{{ $canonical }}" class="show-more-3">Xem thêm</a>
    </div>
     <div class="overlay">
        <div class="uk-flex uk-flex-middle uk-flex-space-between">
            <div class="school-widget uk-flex uk-flex-middle">
                <div class="heart-icon"><i class="fa fa-heart-o"></i></div>
                <div class="uk-flex uk-flex-middle">
                    <span class="mr10">120 views </span>
                    <div class="star-rating uk-flex uk-flex-right">
                        <div class="stars" style="--star-width: {{ $rate }}%"></div>
                    </div>
                </div>
            </div>
            <div class="rank">#{{ $rank }}</div>
        </div>
    </div>
</div>