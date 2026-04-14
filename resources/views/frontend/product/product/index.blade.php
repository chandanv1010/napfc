@php
    $prd_title = $product->name;
    $prd_code = $product->code;
    $prd_model = $product->model ?? '';

    $list_image = [$product->image, ...json_decode($product->album) ?? []];
    $prd_href = write_url($product->canonical ?? '');
    $prd_description = $product->description ?? '';
    $prd_extend_des = $product->content ?? '';
    $price = getPrice($product);
@endphp

@extends('frontend.homepage.layout')

@section('content')
    <div id="prddetail" class="page-body">
        <section class="prddetail">
            <div class="uk-container uk-container-center">
                <x-breadcrumb :breadcrumb="$breadcrumb" :current="$prd_title" />
                <div class="prddetail-premium-box">
                    <div class="uk-grid uk-grid-medium">
                        {{-- LEFT: GALLERY --}}
                        <div class="uk-width-large-1-2">
                            <div class="product-gallery">
                                @if (isset($list_image) && !empty($list_image))
                                    <div class="product-list_image">
                                        <div class="swiper-container big-swiper">
                                            <div class="swiper-wrapper">
                                                @foreach($list_image as $val)
                                                <div class="swiper-slide">
                                                    <a href="{{ $val }}" data-fancybox="gallery" class="image img-cover">
                                                        <img src="{{ image($val) }}" alt="{{ $prd_title }}">
                                                    </a>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="swiper-container-thumbs thumb-swiper">
                                            <div class="swiper-wrapper">
                                                @foreach($list_image as $val)
                                                <div class="swiper-slide">
                                                    <div class="image img-cover">
                                                        <img src="{{ image($val) }}" alt="{{ $prd_title }}">
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- RIGHT: PRODUCT INFO --}}
                        <div class="uk-width-large-1-2">
                            <div class="product-info-premium">
                                <h1 class="prd-name">{{ $prd_title }}</h1>
                                
                                <div class="price-section">
                                    <div class="new-price">{!! $price['html'] !!}</div>
                                    @if(isset($price['old_price']) && $price['old_price'] > 0)
                                        <div class="old-price">{{ number_format($price['old_price'], 0, ',', '.') }} coin</div>
                                    @endif
                                </div>

                                <div class="account-info-list">
                                    <p class="list-title">Thông tin tài khoản:</p>
                                    <ul>
                                        <li>{!! $prd_description !!}</li>
                                        <li>Mã tài khoản: #{{ $prd_code }}</li>
                                    </ul>
                                </div>

                                <div class="buy-section">
                                    @if ($product->status == 'sold' || $product->is_sold == 1)
                                        <button class="btn-sold-large" disabled>TÀI KHOẢN ĐÃ BÁN</button>
                                    @else
                                        <a href="#" class="btn-buy-now-large btn-buy-account" data-id="{{ $product->id }}">
                                            <i class="bi bi-lock-fill"></i> MUA NGAY
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="product-description-block">
                    <h2 class="description-title">Mô Tả:</h2>
                    <div class="content">
                        {!! $prd_extend_des !!}
                    </div>
                </div>

                @if (!is_null($productCatalogue->products))
                <section class="related-products-section">
                    <h2 class="section-title">SẢN PHẨM LIÊN QUAN</h2>
                    <div class="uk-grid uk-grid-small uk-grid-width-1-2 uk-grid-width-medium-1-3 uk-grid-width-large-1-4">
                        @foreach ($productCatalogue->products->take(8) as $valPost)
                            @php
                                $name = $valPost->languages->first()->pivot->name;
                                $image = $valPost->image;
                                $canonical = write_url($valPost->languages->first()->pivot->canonical);
                                $priceRel = getPrice($valPost);
                                $descRel = cutnchar(strip_tags($valPost->languages->first()->pivot->description), 80);
                            @endphp
                            <div class="mb20">
                                <div class="account-item">
                                    <a href="{{ $canonical }}" class="image img-cover">
                                        <img src="{{ $image }}" alt="{{ $name }}">
                                    </a>
                                    <div class="info">
                                        <h3 class="name"><a href="{{ $canonical }}">{{ $name }}</a></h3>
                                        <div class="price">{!! $priceRel['html'] !!}</div>
                                        <div class="description">{!! $descRel !!}</div>
                                    </div>
                                    <button type="button" class="btn-buynow">Mua ngay</button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
                @endif
            </div>
        </section>
    </div>

    <script>
        $(document).ready(function() {
            var galleryThumbs = new Swiper('.thumb-swiper', {
                spaceBetween: 10,
                slidesPerView: 4,
                freeMode: true,
                watchSlidesVisibility: true,
                watchSlidesProgress: true,
            });
            var galleryTop = new Swiper('.big-swiper', {
                spaceBetween: 10,
                thumbs: {
                    swiper: galleryThumbs
                }
            });
        });
    </script>
@endsection
