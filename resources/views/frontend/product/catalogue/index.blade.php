@extends('frontend.homepage.layout')

@section('content')
<div id="prd-catalogue" class="page-body">
    <x-breadcrumb :breadcrumb="$breadcrumb" />
     <div class="uk-container uk-container-center">
     	<div class="prd-catalogue-wrapper">
     		<div class="prd-catalogue">
                <div class="prd-catalogue_description">
                    <h1>{{ $productCatalogue->name }}</h1>
                    <div class="description">
                        {!! $productCatalogue->description !!}
                    </div>
                </div>
                
                @if (!is_null($products))
                <ul class="uk-list uk-clearfix uk-grid uk-grid-small uk-grid-width-1-2 uk-grid-width-small-1-2 uk-grid-width-medium-1-2 uk-grid-width-large-1-4">
                    @foreach ($products as $keyPost => $valPost)
                    @php
                        $name = $valPost->languages->first()->pivot->name;
                        $image = $valPost->image;
                        $canonical  = write_url($valPost->languages->first()->pivot->canonical);
                        $description = cutnchar(strip_tags($valPost->languages->first()->pivot->description), 100);
                        $price = getPrice($valPost);
                    @endphp

                        <li class="mb10">
                            <div class="account-item">
                                <a href="{{ $canonical }}" class="image img-cover"><img src="{{ $image }}" alt="{{ $name }}"></a>
                                <div class="info">
                                    <div class="price">
                                        {!! $price['html'] !!}
                                    </div>
                                    <div class="description">
                                        {!! $description !!}
                                    </div>
                                    <div class="readmore"><a href="{{ $canonical }}">Kiểm tra thông tin</a></div>
                                    
                                </div>
                                <button type="button" class="btn-buynow">Mua ngay</button>
                            </div>
                        </li>
                    @endforeach
                </ul>
                    

                @endif
                <div class="uk-flex uk-flex-center">
                    @include('frontend.component.pagination', ['model' => $products])
                </div>
            </div>
     	</div>
     </div>
</div>

@endsection