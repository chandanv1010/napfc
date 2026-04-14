@props(['breadcrumb', 'current'])

<div class="page-breadcrumb">      
    <ul class="uk-list uk-clearfix uk-flex uk-flex-middle">
        <li>
            <a href="/">{{ __('frontend.home') }}</a>
        </li>
        @if(!is_null($breadcrumb))
            @foreach($breadcrumb as $key => $val)
                @php
                    $name = $val->languages->first()->pivot->name;
                    $canonical = write_url($val->languages->first()->pivot->canonical, true, true);
                @endphp
                <li>
                    <span class="slash">/</span>
                </li>
                <li>
                    <a href="{{ $canonical }}" title="{{ $name }}">{{ $name }}</a>
                </li>
            @endforeach
        @endif
        @if(isset($current))
            <li>
                <span class="slash">/</span>
            </li>
            <li class="uk-active">
                <span class="current">{{ $current }}</span>
            </li>
        @endif
    </ul>
</div>