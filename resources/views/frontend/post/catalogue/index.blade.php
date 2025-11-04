@extends('frontend.homepage.layout')

@section('content')
    <div id="art-catalogue" class="page-body">
        {{-- Breadcrumb --}}
        <x-breadcrumb :breadcrumb="$breadcrumb" />

        <div class="art-catalogue-wrapper">
            <div class="uk-container uk-container-center">
                <div class="uk-grid uk-grid-small">
                    {{-- Sidebar bên trái --}}
                    <div class="uk-width-large-1-4 uk-visible-large">
                        @include('frontend.component.sidebar')
                    </div>

                    {{-- Danh sách bài viết --}}
                    <div class="uk-width-large-3-4">
                        <div class="art-catalogue">
                            @if(!is_null($posts) && $posts->count() > 0)
                                <ul class="uk-list uk-clearfix">
                                    @foreach($posts as $key => $val)
                                        @php
                                            $title = $val->languages->first()->pivot->name;
                                            $image = $val->image;
                                            $href = write_url($val->languages->first()->pivot->canonical);
                                            $description = cutnchar(strip_tags($val->languages->first()->pivot->description), 450);
                                        @endphp

                                        <li class="mb15">
                                            <div class="art-item uk-clearfix">
                                                <a href="{{ $href }}" class="image img-cover" title="{{ $title }}">
                                                    <img src="{{ $image }}" alt="{{ $title }}">
                                                </a>
                                                <div class="info">
                                                    <h3 class="title">
                                                        <a href="{{ $href }}" title="{{ $title }}">{{ $title }}</a>
                                                    </h3>
                                                    <div class="description">
                                                        {!! $description !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
