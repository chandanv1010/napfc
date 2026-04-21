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
                                            $prodLang = $valPost->languages;
                                            if (is_object($prodLang) && method_exists($prodLang, 'first')) {
                                                $prodLang = $prodLang->first();
                                            } elseif (is_array($prodLang)) {
                                                $prodLang = $prodLang[0] ?? null;
                                            }
                                            $name = $prodLang->pivot->name ?? $prodLang->name ?? '';
                                            $description = $prodLang->pivot->description ?? $prodLang->description ?? '';
                                            $canonical = write_url($prodLang->pivot->canonical ?? $prodLang->canonical ?? '#');
                                            $price = getPrice($valPost);
                                            $image = $valPost->image;
                                        @endphp
                                        <div class="uk-width-1-1 uk-width-small-1-2 uk-width-medium-1-3 uk-width-large-1-4 mb20 product-grid-item"
                                             data-price="{{ $valPost->price }}"
                                             data-name="{{ strtolower($name) }}"
                                             data-code="{{ strtolower($valPost->code) }}"
                                             data-created="{{ $valPost->id }}">
                                            <div class="account-item">
                                                <a href="{{ $canonical }}" class="image img-cover uk-display-block">
                                                    <img src="{{ $image }}" alt="{{ $name }}">
                                                </a>
                                                <div class="info">
                                                    <h3 class="name"><a href="{{ $canonical }}">{{ $name }}</a></h3>
                                                    <div class="price-row">
                                                        <div class="badge-wrapper">
                                                            <img src="{{ asset('vendor/frontend/resources/img/project/badge_code.png') }}" alt="coin" class="coin-icon">
                                                            <span class="badge-code">#{{ substr($product->code, 0, 4) }}</span>
                                                        </div>
                                                        <div class="price">
                                                            {!! $price['html'] !!}
                                                            <div class="readmore"><a href="{{ $canonical }}">KIỂM TRA THÔNG TIN</a></div>
                                                        </div>
                                                    </div>
                                                    <div class="description">
                                                        {!! $description !!}
                                                    </div>
                                                </div>
                                                @if ($valPost->status == 'sold' || $valPost->is_sold == 1)
                                                    <button type="button" class="btn-buynow" disabled>TÀI KHOẢN ĐÃ BÁN</button>
                                                @else
                                                    <button type="button" class="btn-buynow btn-buy-account" data-id="{{ $valPost->id }}">MUA NGAY</button>
                                                @endif
                                            </div>
                                        </div>
                        @endforeach
                    </div>
                </section>
                @endif
            </div>
        </section>
    </div>

    <div id="order-payment-modal" class="uk-modal">
        <div class="uk-modal-dialog payment-modal-dark">
            <button type="button" class="uk-modal-close uk-close"></button>
            <div class="uk-modal-header">
                <h2 class="uk-modal-title">Thông tin thanh toán chi tiết</h2>
            </div>
            <div class="uk-modal-body">
                <div class="payment-info-box">
                    <div class="uk-grid uk-grid-medium" data-uk-grid-margin>
                        <div class="uk-width-medium-1-2">
                            <div class="bank-card blue-gradient h100">
                                <div class="bank-header">
                                    <i class="fa fa-bank"></i> Thông tin chuyển khoản
                                </div>
                                <div class="bank-details mt10">
                                    <div class="bank-row">
                                        <div class="bank-label">Ngân hàng</div>
                                        <div class="bank-value-line">
                                            <span class="bank-value">ACB</span>
                                            <i class="fa fa-copy btn-copy"></i>
                                        </div>
                                    </div>
                                    <div class="bank-row">
                                        <div class="bank-label">Số tài khoản</div>
                                        <div class="bank-value-line">
                                            <span class="bank-value bold">{{ $system['bank_account'] }}</span>
                                            <i class="fa fa-copy btn-copy"></i>
                                        </div>
                                    </div>
                                    <div class="bank-row">
                                        <div class="bank-label">Chủ tài khoản</div>
                                        <div class="bank-value-line">
                                            <span class="bank-value bold uppercase">{{ $system['bank_name'] }}</span>
                                            <i class="fa fa-copy btn-copy"></i>
                                        </div>
                                    </div>
                                    <div class="bank-row">
                                        <div class="bank-label">Số tiền</div>
                                        <div class="bank-value-line">
                                            <span class="bank-value bold text-yellow"><span class="transfer-amount-val">0</span> VND</span>
                                            <i class="fa fa-copy btn-copy"></i>
                                        </div>
                                    </div>
                                    <div class="bank-row">
                                        <div class="bank-label">Nội dung</div>
                                        <div class="bank-value-line">
                                            <span class="bank-value bold text-yellow"><span class="transfer-content-val">---</span></span>
                                            <i class="fa fa-copy btn-copy"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="uk-width-medium-1-2">
                            <div class="qr-card dark-glass h100">
                                <div class="qr-header">
                                    <i class="fa fa-qrcode"></i> Quét mã QR
                                </div>
                                <div class="qr-image mt10">
                                    <img src="" alt="QR Code VietQR" loading="lazy">
                                </div>
                                <div class="qr-footer mt10">
                                    Quét mã QR bằng ứng dụng ngân hàng để chuyển khoản nhanh chóng
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="payment-guide-box mt20">
                    <div class="guide-title"><i class="fa fa-warning"></i> Hướng dẫn thanh toán</div>
                    <ol class="guide-list mt10">
                        <li>Chuyển khoản đúng số tiền và nội dung như trên</li>
                        <li>Sau khi chuyển khoản, hệ thống sẽ tự động xác nhận trong vòng 1-2 phút</li>
                        <li>Bạn sẽ nhận được link truy cập thông tin tài khoản sau khi thanh toán thành công</li>
                        <li>Link truy cập chỉ có hiệu lực trong 1 giờ</li>
                    </ol>
                </div>
            </div>
        </div>
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
