@extends('frontend.homepage.layout')
@section('content')
    <div id="homepage" class="page-wrapper">
        <div class="uk-container uk-container-center">
            @include('frontend.component.slide')
        </div>

        @if(!is_null($widgets['garena-card']))
        @foreach($widgets['garena-card']->object as $key => $val) 
        <div class="panel-garena">
            <div class="panel-head uk-text-center">
                <h2 class="heading-1"><span>{{ $val->languages->name }}</span></h2>
            </div>
            @if(!is_null($val->products) && $val->products->count() > 0)
            <div class="panel-body">
                <div class="uk-container uk-container-center">
                    <div class="page-heading">Nạp thẻ</div>
                    <div class="description">Chọn mệnh giá thẻ</div>
                    <div class="card-wrapper">
                        <div class="uk-grid uk-grid-medium">
                            <div class="uk-width-small-1-2 uk-width-large-2-3">
                                <div class="card-list">
                                    <div class="uk-grid uk-grid-medium">
                                        
                                        @foreach($val->products as $product)
                                        @php
                                            $image = $product->image;
                                            $price = $product->price;
                                        @endphp
                                        <div  class="uk-width-1-2 uk-width-small-1-2 uk-width-medium-1-3 uk-width-large-1-4 mb25">
                                            <div data-card="{{ json_encode($product) }}" class="garena-item">
                                                <span class="image img-scaledown"><img src="{{ $image }}" alt="{{ $product->languages[0]->name }}"></span>
                                                <h3 class="title uk-text-center"><span>{{ convert_price($price, true, true) }}</span> đ</h3>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                           
                            <div class="uk-width-small-1-2 uk-width-large-1-3">
                                <div class="card-description">
                                    {!! $val->languages->description !!}
                                    {{-- <div class="card-order">
                                        <h2 class="heading-1">Chi tiết đơn hàng</h2>
                                        <div class="order-info">
                                            <div class="label">
                                                <span class="text">Tên sản phẩm: </span>
                                                <span class="value">Mua mã thẻ Garena 20</span>
                                            </div>
                                            <div class="label">
                                                <span class="text">Đơn giá: </span>
                                                <span class="value">19.200 ₫</span>
                                            </div>
                                            <div class="label">
                                                <span class="text">Số lượng</span>
                                                <span class="value">1</span>
                                            </div>
                                            <div class="label">
                                                <span class="text">Tổng tiền</span>
                                                <span class="value">19.200 ₫</span>
                                            </div>
                                            <div class="account-input">
                                                <input type="text" class="input-text" value="" placeholder="Nhập vào tài khoản muốn nạp..">
                                            </div>
                                            <a href="" class="buy-or-login">
                                                <span class="main-text">Đăng nhập ngay</span>
                                                <span class="sub-text">Vui lòng đăng nhập để tiếp tục</span>
                                            </a>
                                            <div class="notice">Nếu bạn muốn nạp số dư nhiều hơn để sử dụng cho những lần mua hàng tiếp theo, vui lòng truy cập trang Nạp số dư <a href="{{ write_url('nap-so-du') }}">tại đây</a>.</div>
                                        </div>
                                    </div> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
        @endforeach
        @endif

        @if(!is_null($widgets['doi-hinh']))
        @foreach($widgets['doi-hinh']->object as $key => $val)
        <div class="panel-account">
            <div class="panel-head uk-text-center">
                <div class="heading-1"><span>{{ $val->languages->name }}</span></div>
            </div>
            <div class="panel-body">
                <div class="uk-container uk-container-center">
                    <div class="heading">
                        <span>Acc đội hình</span>
                    </div>
                    @if(!is_null($val->products))
                    <div class="uk-grid uk-grid-medium">
                        @foreach($val->products as $product)
                        @php
                            $name = $product->languages[0]->name;
                            $description = $product->languages[0]->description;
                            $canonical = write_url($product->languages[0]->canonical);
                            $price = getPrice($product);
                            $image = $product->image;
                        @endphp
                        <div class="uk-width-1-1 uk-width-small-1-2 uk-width-medium-1-3 uk-width-large-1-4 mb20">
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
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
        @endif

        @if(isset($widgets['why']))
        @foreach($widgets['why']->object as $key => $val)
        <div class="panel-whyus">
            <div class="panel-head uk-text-center">
                <div class="heading-1"><span>{{ $val->languages->name }}</span></div>
            </div>
            <div class="panel-body">
                <div class="uk-container uk-container-center">
                    <div class="uk-grid uk-grid-medium mb20">
                        @foreach($val->posts as $post)
                        @php
                            $name = $post->languages[0]->name;
                            $description = $post->languages[0]->description;
                            $image = $post->image;
                        @endphp
                        <div class="uk-width-large-1-3">
                            <div class="whyus-item">
                                <div class="icon"><img src="{{ $image }}" alt="{{ $name }}"></div>
                                <div class="title">{{ $name }}</div>
                                <div class="description">{!! $description !!}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="panel-number">
                        <div class="uk-grid uk-grid-medium">
                            <div class="uk-width-large-1-3">
                                <div class="number-item">
                                    <div class="number">8000</div>
                                    Giao dịch hàng tháng
                                </div>
                            </div>
                            <div class="uk-width-large-1-3">
                                <div class="number-item">
                                    <div class="number">100%</div>
                                    Độ tin cậy
                                </div>
                            </div>
                            <div class="uk-width-large-1-3">
                                <div class="number-item">
                                    <div class="number">5%</div>
                                    Chiết khấu bonus
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
        @endif

        @if(isset($widgets['news-wrapper']))
        @foreach($widgets['news-wrapper']->object as $key => $val)
        <div class="panel-news">
            <div class="uk-container uk-container-center">
                <div class="panel-head uk-text-center">
                    <h2 class="heading-1"><span>{{ $val->languages->name }}</span></h2>
                </div>
                @if(isset($val->posts) && $val->posts->count() > 0)
                <div class="panel-body">
                    <div class="uk-grid uk-grid-medium">
                        @foreach($val->posts as $post)
                        @php
                            $name = $post->languages[0]->name;
                            $canonical = write_url($post->languages[0]->canonical);
                            $description = $post->languages[0]->description;
                            $image = $post->image;
                            $created_at = $post->created_at;
                        @endphp
                        <div class="uk-width-1-1 uk-width-small-1-2 uk-width-medium-1-3 uk-width-large-1-4 mb20">
                            <div class="news-item">
                                <a href="" class="image img-cover"><img src="{{ $image }}" alt="{{ $name }}"></a>
                                <div class="info">
                                    <div class="created_at">{!! $created_at !!}</div>
                                    <h3 class="title"><a href="{{ $canonical }}" title="{{ $name }}">{{ $name }}</a></h3>
                                    <div class="description">
                                        {!! $description !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endforeach
        @endif

    </div>

   
@endsection
