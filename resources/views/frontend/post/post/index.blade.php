
@extends('frontend.homepage.layout')
@section('content')

    <div id="art-detail" class="page-body">
        
        <x-breadcrumb :breadcrumb="$breadcrumb" />
        <div class="art-catalogue-wrapper">
            <div class="uk-container uk-container-center">
                <div class="art-detail">
                    <h1><span><?php echo $post->name ?></span></h1>
                    <div class="description">
                        {!! $post->description !!}
                    </div>
                    <div class="description content">
                        {!! $post->content !!}
                    </div>
                    @if (isset($postCatalogue->posts) && !is_null($postCatalogue->posts))
                        <div class="artdetail-relate style-1 mt30">
                            <div class="heading-1 mb10">
                            <span>Bài viết liên quan</span>
                            </div>

                            <ul class="uk-list uk-clearfix uk-grid uk-grid-medium uk-grid-width-medium-1-2 uk-grid-width-large-1-3 list-related">
                                @foreach ($postCatalogue->posts as $key => $val)
                                @php
                                    if($val->id === $post->id) continue; 
                                    $title = $val->languages->first()->pivot->name;
                                    $image = $val->image;
                                    $href  = write_url($val->languages->first()->pivot->canonical);
                                    $description = cutnchar(strip_tags($val->languages->first()->pivot->description), 100);
                                @endphp

                                <li class="mb10">
                                    <article class="article">
                                    <div class="thumb">
                                        <a href="{{ $href }}" title="{{ $title }}" class="image img-cover img-zoomin">
                                        <img src="{{ $image }}" alt="{{ $title }}" />
                                        </a>
                                    </div>
                                    <h3 class="title">
                                        <a href="{{ $href }}" title="{{ $title }}">
                                        {{ $title }}
                                        </a>
                                    </h3>
                                    </article>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                </div>
            </div>
        </div>

    </div><!-- #prd-detail  -->



@endsection
