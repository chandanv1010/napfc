@extends('frontend.homepage.layout')
@section('content')
    <div id="homepage" class="page-wrapper">
        <div class="hero-section">
            <div class="hero-badge hero-badge-left"></div>

            <div class="uk-container uk-container-center mt10">
                @include('frontend.component.slide')
            </div>

            <div class="hero-badge hero-badge-right"></div>
        </div>

        @if($accountCategories->count() > 0)
            <div class="panel-account">
                <div class="panel-body">
                    <div class="uk-container uk-container-center">
                        <div class="account-layout">
                            <div class="account-sidebar">
                                <div class="category-nav">
                                    @foreach($accountCategories as $key => $cat)
                                        @php
                                            $language = $cat->languages;
                                            if (is_object($language) && method_exists($language, 'first')) {
                                                $language = $language->first();
                                            } elseif (is_array($language)) {
                                                $language = (object) ($language[0] ?? []);
                                            }

                                            $catName = (isset($language->pivot->name)) ? $language->pivot->name : (isset($language->name) ? $language->name : $cat->short_name);
                                            $catImage = $cat->image ?? '';
                                        @endphp
                                        <div class="category-nav-item {{ $key === 0 ? 'active' : '' }}"
                                            data-target="cat-{{ $cat->id }}">
                                            <div class="cat-image-wrapper">
                                                <img src="{{ $catImage }}" alt="{{ $catName }}">
                                            </div>
                                            <span>{{ $catName }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="account-content">
                                <h2 class="account-section-title">TÀI KHOẢN</h2>

                                <div class="account-filter-bar">
                                    <div class="filter-group">
                                        <select class="filter-select" id="filter-price">
                                            <option value="">TẤT CẢ MỨC GIÁ</option>
                                            <option value="0-500000">Dưới 500K</option>
                                            <option value="500000-1000000">500K - 1 Triệu</option>
                                            <option value="1000000-2000000">1 Triệu - 2 Triệu</option>
                                            <option value="2000000-5000000">2 Triệu - 5 Triệu</option>
                                            <option value="5000000-999999999">Trên 5 Triệu</option>
                                        </select>
                                        <select class="filter-select" id="filter-sort">
                                            <option value="">SẮP XẾP</option>
                                            <option value="price_asc">Giá tăng dần</option>
                                            <option value="price_desc">Giá giảm dần</option>
                                            <option value="newest">Mới nhất</option>
                                        </select>
                                    </div>
                                    <div class="filter-search">
                                        <input type="text" placeholder="Nhập mã tài khoản hoặc tên sản phẩm"
                                            class="filter-input" id="filter-keyword">
                                        <button class="filter-search-btn" id="btn-filter-search"><i
                                                class="fa fa-search"></i></button>
                                    </div>
                                    <button class="filter-reset-btn" id="btn-filter-reset" title="Reset bộ lọc"><i
                                            class="fa fa-refresh"></i></button>
                                </div>

                                {{-- Tab Panes --}}
                                <div class="tab-container">
                                    @foreach($accountCategories as $key => $cat)
                                        <div class="tab-pane {{ $key === 0 ? 'active' : '' }}" id="cat-{{ $cat->id }}">
                                            @if($cat->products->count() > 0)
                                                <div class="uk-grid uk-grid-medium product-grid-list">
                                                    @foreach($cat->products as $product)
                                                        @php
                                                            $prodLang = $product->languages;
                                                            if (is_object($prodLang) && method_exists($prodLang, 'first')) {
                                                                $prodLang = $prodLang->first();
                                                            } elseif (is_array($prodLang)) {
                                                                $prodLang = $prodLang[0] ?? null;
                                                            }
                                                            $name = $prodLang->pivot->name ?? $prodLang->name ?? '';
                                                            $description = $prodLang->pivot->description ?? $prodLang->description ?? '';
                                                            $canonical = write_url($prodLang->pivot->canonical ?? $prodLang->canonical ?? '#');
                                                            $price = getPrice($product);
                                                            $image = $product->image;
                                                        @endphp
                                                        <div class="uk-width-1-1 uk-width-small-1-2 uk-width-medium-1-3 mb20 product-grid-item"
                                                            data-price="{{ $product->price }}" data-name="{{ strtolower($name) }}"
                                                            data-code="{{ strtolower($product->code) }}" data-created="{{ $product->id }}">
                                                            <div class="account-item">
                                                                <a href="{{ $canonical }}" class="image img-cover uk-display-block">
                                                                    <img src="{{ $image }}" alt="{{ $name }}">
                                                                </a>
                                                                <div class="info">
                                                                    <h3 class="name"><a href="{{ $canonical }}">{{ $name }}</a></h3>
                                                                    <div class="price-row">
                                                                        <div class="badge-wrapper">
                                                                            <img src="{{ asset('vendor/frontend/resources/img/project/badge_code.png') }}"
                                                                                alt="coin" class="coin-icon">
                                                                            <span
                                                                                class="badge-code">#{{ substr($product->code, 0, 4) }}</span>
                                                                        </div>
                                                                        <div class="price">
                                                                            {!! $price['html'] !!}
                                                                            <div class="readmore"><a href="{{ $canonical }}">KIỂM TRA THÔNG
                                                                                    TIN</a></div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="description">
                                                                        {!! $description !!}
                                                                    </div>
                                                                </div>
                                                                @if ($product->status == 'sold' || $product->is_sold == 1)
                                                                    <button type="button" class="btn-buynow" disabled>TÀI KHOẢN ĐÃ BÁN</button>
                                                                @else
                                                                    <a href="#" class="btn-buynow btn-buy-account"
                                                                        data-id="{{ $product->id }}">MUA NGAY</a>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <div class="uk-alert uk-alert-warning uk-text-center">Hiện chưa có tài khoản nào trong
                                                    danh mục này.</div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>

                                <div class="uk-text-center" style="margin-top: 25px;">
                                    <a href="#" class="btn-loadmore">XEM THÊM</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

@endsection