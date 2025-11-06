@php
    
    // Chuẩn bị dữ liệu
    $prd_title   = $product->name;
    $prd_code    = $product->code;
    $prd_model   = $product->model ?? '';
    
    
    $list_image = [$product->image, ...(json_decode($product->album) ?? [])];
    $prd_href        = write_url($product->canonical ?? '');
    $prd_description = $product->description ?? '';
    $prd_extend_des  = $product->content ?? '';
    $price = getPrice($product);

@endphp


@extends('frontend.homepage.layout')

@section('content')

<div id="prddetail" class="page-body" style="background:#fff;">
   <x-breadcrumb :breadcrumb="$breadcrumb" />


  <section class="prddetail">
    <div class="uk-container uk-container-center">
      <div class="uk-grid uk-grid-medium uk-grid-width-large-1-2">

        
        <div class="product-gallery">
            @if(isset($list_image) && !empty($list_image) && !is_null($list_image))
                <div class="product-list_image">
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                    <div class="swiper-container">
                        <div class="swiper-wrapper big-pic">
                            <?php foreach($list_image as $key => $val){  ?>
                                <div class="swiper-slide" data-swiper-autoplay="2000">
                                    <a href="{{ $val }}" data-fancybox="my-group" class="image img-cover">
                                        <img src="{{ image($val) }}" alt="<?php echo $val ?>">
                                    </a>
                                </div>
                            <?php }  ?>
                        </div>
                        <div class="swiper-pagination"></div>
                    </div>
                    <div class="swiper-container-thumbs">
                        <div class="swiper-wrapper pic-list">
                            <?php foreach($list_image as $key => $val){  ?>
                            <div class="swiper-slide">
                                <span  class="image img-cover"><img src="{{  image($val) }}" alt="<?php echo $val ?>"></span>
                            </div>
                            <?php }  ?>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- Product info --}}
        <div class="product-info">
          <h1 class="prd-name">{{ $prd_title }}</h1>
          <div class="description">
            {!! $product->description !!}
          </div>
         
          <div class="product-price">
            <div class="uk-flex uk-flex-middle">
                <span>Giá: </span><span class="uk-text-danger">{!! $price['html'] !!}</span>
            </div>
          </div>

          @if (!empty($prd_description))
            <div class="prd-description">
              {!! $prd_description !!}
            </div>
          @endif

          <div class="prd-option">
           
            <div class="option-block">
              <div class="product-stock">
                <div class="uk-grid uk-grid-medium uk-grid-width-large-1-2 uk-flex uk-flex-middle">
                  <div class="prd-btn btn-addtocard">
                        @if($product->status == 'sold' || $product->is_sold == 1)
                            @if($paidTransaction && $paidTransaction->customer_id == $customer->id)
                                {{-- Người mua chính --}}
                                <a href="{{ route('account.success', ['code' => $paidTransaction->transaction_code]) }}"
                                class="btn-view-account">
                                    <span class="title">Đã mua</span>
                                    <span class="sub-title">Xem thông tin tài khoản</span>
                                </a>
                            @else
                                {{-- Người khác hoặc chưa đăng nhập --}}
                                <div class="btn-sold-out" style="pointer-events:none; opacity:0.7;">
                                    <span class="title" style="color:#c00; font-weight:bold;">Tài khoản đã bán</span>
                                    <span class="sub-title" style="color:#777;">Sản phẩm này không còn khả dụng</span>
                                </div>
                            @endif
                        @elseif(!$customer)
                            {{-- Chưa đăng nhập --}}
                            <a href="{{ route('customer.auth') }}" class="btn-login-account">
                                <span class="title">Đăng nhập</span>
                                <span class="sub-title">Đăng nhập để mua sản phẩm</span>
                            </a>
                        @elseif($paidTransaction)
                            {{-- Đã mua (trường hợp tài khoản chưa đánh dấu sold nhưng có giao dịch) --}}
                            <a href="{{ route('account.success', ['code' => $paidTransaction->transaction_code]) }}"
                            class="btn-view-account">
                                <span class="title">Đã mua</span>
                                <span class="sub-title">Xem thông tin tài khoản</span>
                            </a>
                        @else
                            {{-- Chưa mua --}}
                            <a href="#"
                            class="btn-buy-account"
                            data-id="{{ $product->id }}">
                                <span class="title">Mua ngay</span>
                                <span class="sub-title">Mua ngay để nhận ưu đãi</span>
                            </a>
                        @endif
                    </div>
                    <div class="prd-btn btn-installment">
                        <a href="tel:{{ $system['contact_hotline'] ?? '' }}"
                        title="{{ $system['contact_hotline'] ?? '' }}">
                        <span class="title">Liên hệ</span>
                        <span class="sub-title">Liên hệ ngay để có giá tốt nhất</span>
                        </a>
                    </div>
              </div>
            </div>

          </div>
        </div>

      </div>
    </div>
  </section>

  <div class="block-extend">
    <div class="uk-container uk-container-center">
       
        {{-- Bản Desktop --}}
        <section class="prd-block uk-visible-large" id="prd-block">
            <header class="panel-head">
                <ul class="uk-list uk-clearfix nav-tabs" data-uk-switcher="{connect:'#prd-nav-tabs', animation: 'uk-animation-fade'}">
                    <li>
                        <a>Thông tin sản phẩm</a>
                    </li>
                </ul>
            </header>

            <section class="panel-body">
                <ul id="prd-nav-tabs" class="uk-switcher">
                    <li>
                        <article class="prd-shipping-policy">
                            {!! $product->content !!}
                        </article>
                    </li>
                </ul>
            </section>
        </section>

        {{-- Bản Mobile --}}
        <section class="prd-block uk-hidden-large mb20" id="prd-block-mobile">
            <div class="uk-accordion" data-uk-accordion='{collapse: false}'>
               
                <h2 class="uk-accordion-title" style="border: 0">
                    <span>Thông tin sản phẩm</span>
                </h2>
                <div class="uk-accordion-content">
                    <section class="dt-content">
                        {!! $product->content !!}
                    </section>
                </div>
            </div>
        </section>

        {{-- Sản phẩm liên quan --}}
        @if (!is_null($productCatalogue->products))
            <section class="categories-panel related-products">
                <div class="uk-container uk-container-center">
                    <h2 class="heading-1">
                        <a href="#" onclick="return false;" title="Sản phẩm liên quan">Sản phẩm liên quan</a>
                    </h2>

                    <ul class="uk-list uk-clearfix uk-grid uk-grid-small uk-grid-width-1-2 uk-grid-width-small-1-2 uk-grid-width-medium-1-3 uk-grid-width-large-1-4">
                        @foreach ($productCatalogue->products as $valPost)
                            @php
                                if($key > 8) break;
                                $name = $valPost->languages->first()->pivot->name;
                                $image = $valPost->image;
                                $canonical = write_url($valPost->languages->first()->pivot->canonical);
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
                </div>
            </section>
        @endif
    </div>
</div>


 
</div> {{-- #prddetail --}}

@endsection
