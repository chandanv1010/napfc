@extends('frontend.homepage.layout')
@section('content')
<div id="homepage" class="page-wrapper">
        <div class="hero-section">
            <div class="hero-badge hero-badge-left">
                <img src="{{ asset('vendor/frontend/resources/img/project/hoamai.png') }}" alt="Hoa Mai">
            </div>

            <div class="uk-container uk-container-center mt10">
                @include('frontend.component.slide')
            </div>

            <div class="hero-badge hero-badge-right">
                <img src="{{ asset('vendor/frontend/resources/img/project/hoadao.png') }}" alt="Hoa Đào">
            </div>
        </div>
    @if(!is_null($widgets['garena-card']))
        @foreach($widgets['garena-card']->object as $key => $val) 
        <div class="panel-garena">
            <div class="panel-head uk-text-center">
                <h2 class="account-section-title"><span>{{ $val->languages->name }}</span></h2>
            </div>
            @if(!is_null($val->products) && $val->products->count() > 0)
            <div class="panel-body">
                <div class="uk-container uk-container-center">
                    <div class="page-heading">Nạp thẻ</div>
                    <div class="description">Chọn mệnh giá thẻ</div>
                    
                    <div class="card-wrapper">
                        <div class="uk-grid uk-grid-medium">
                            <div class="uk-width-small-1-2 uk-width-large-2-3">
                                <div class="quantity">
                                    <input type="number" id="card-quantity" value="1" class="form-control input-text">
                                </div>
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
                                <div class="card-description garena-checkout-sidebar">
                                    <!-- STEP 0: Instructions (Default) -->
                                    <div class="checkout-step step-0 active">
                                        <h3 class="instruction-title">Làm thế nào để mua thẻ Garena</h3>
                                        <ul class="instruction-list">
                                            <li>
                                                <div class="step-number"><span>1</span></div>
                                                <div class="step-content">Chọn mệnh giá thẻ bạn muốn mua (ví dụ: 20.000đ, 50.000đ, 100.000đ...).</div>
                                            </li>
                                            <li>
                                                <div class="step-number"><span>2</span></div>
                                                <div class="step-content">Nhập số lượng thẻ cần mua.</div>
                                            </li>
                                            <li>
                                                <div class="step-number"><span>3</span></div>
                                                <div class="step-content">Xác nhận và tiến hành thanh toán.</div>
                                            </li>
                                            <li>
                                                <div class="step-number"><span>4</span></div>
                                                <div class="step-content">Chờ vài giây, hệ thống sẽ hiển thị thẻ Garena ngay trên màn hình của bạn.</div>
                                            </li>
                                        </ul>
                                        <div class="instruction-notice mt20">
                                            <p><strong>Lưu ý :</strong> Hệ thống nạp tiền tự động theo nội dung chuyển khoản nên mỗi mã QR chỉ thanh toán được 1 lần.</p>
                                        </div>
                                    </div>

                                    <!-- STEP 1: Consent (Image 2) -->
                                    <div class="checkout-step step-1">
                                        <div class="consent-container uk-text-center">
                                            <div class="info-icon">i</div>
                                            <h2 class="step-title">Mua không cần đăng nhập</h2>
                                            
                                            <div class="price-summary uk-flex uk-flex-middle uk-flex-space-between mt20">
                                                <span class="label">Bạn đang mua tài khoản với giá:</span>
                                                <span class="value text-blue total-price-val">0 ₫</span>
                                            </div>

                                            <div class="warning-box mt20">
                                                <div class="warning-title"><i class="fa fa-warning"></i> Lưu ý*:</div>
                                                <ul class="warning-list">
                                                    <li>Đơn hàng chỉ có hiệu lực trong 15 phút</li>
                                                    <li>Sau khi thanh toán, bạn chỉ có 1 giờ để truy cập thông tin tài khoản</li>
                                                    <li>Vui lòng lưu lại link truy cập sau khi thanh toán</li>
                                                </ul>
                                            </div>

                                            <div class="consent-check mt20">
                                                <p>Bạn có muốn tiếp tục?</p>
                                                <label><input type="checkbox" id="consent-agree"> Bạn có đồng ý với <a href="#" class="text-blue">chính sách</a></label>
                                            </div>

                                            <div class="error-msg-consent mt15 uk-hidden">
                                                <div class="alert alert-danger"><i class="fa fa-info-circle"></i> Bạn cần đồng ý trước khi tiếp tục</div>
                                            </div>

                                            <div class="action-buttons uk-grid uk-grid-small mt25">
                                                <div class="uk-width-1-2">
                                                    <button type="button" class="btn-checkout btn-next-step-1">TIẾP TỤC</button>
                                                </div>
                                                <div class="uk-width-1-2">
                                                    <button type="button" class="btn-checkout btn-cancel">HỦY BỎ</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- STEP 2: Order Details (Image 3) -->
                                    <div class="checkout-step step-2">
                                        <h2 class="step-title order-step-title">
                                            <span class="main">Chi tiết đơn hàng</span>
                                        </h2>
                                        <div class="order-details-table mt20">
                                            <div class="detail-row uk-flex uk-flex-space-between">
                                                <span class="label">Tên sản phẩm:</span>
                                                <span class="value product-name-val">Thẻ Garena</span>
                                            </div>
                                            <div class="detail-row uk-flex uk-flex-space-between">
                                                <span class="label">Đơn giá:</span>
                                                <span class="value unit-price-val">0 đ</span>
                                            </div>
                                            <div class="detail-row uk-flex uk-flex-space-between">
                                                <span class="label">Số lượng:</span>
                                                <span class="value quantity-val">1</span>
                                            </div>
                                            <div class="detail-row total uk-flex uk-flex-space-between">
                                                <span class="label">Tổng tiền:</span>
                                                <span class="value text-red total-price-val">0 đ</span>
                                            </div>
                                        </div>

                                        <div class="account-input-group mt25">
                                            <input type="text" id="target-account" class="form-control" placeholder="Nhập tài khoản cần nạp">
                                            <div class="input-notice mt10">Lưu ý*: Vui lòng nhập đúng tên tài khoản cần nạp để tránh rắc rối về sau!</div>
                                        </div>

                                        <button type="button" class="btn-checkout btn-pay-now mt25">
                                            <span class="main">THANH TOÁN NGAY</span>
                                            <span class="sub">Thanh toán số tiền: <span class="total-price-val">0đ</span></span>
                                        </button>

                                        <div class="security-notice mt20 uk-text-center text-green">
                                            <i class="fa fa-lock text-green"></i> Thông tin được bảo mật tuyệt đối, bạn có thể yên tâm thanh toán!
                                        </div>
                                    </div>

                                    <div class="checkout-step step-3">
                                        <h2 class="step-title">Chi tiết đơn hàng</h2>
                                        <div class="order-details-table concise mt10">
                                            <div class="detail-row uk-flex uk-flex-space-between">
                                                <span class="label">Tên sản phẩm:</span>
                                                <span class="value product-name-val">Thẻ Garena</span>
                                            </div>
                                            <div class="detail-row uk-flex uk-flex-space-between">
                                                <span class="label">Đơn giá:</span>
                                                <span class="value unit-price-val">0 đ</span>
                                            </div>
                                            <div class="detail-row uk-flex uk-flex-space-between">
                                                <span class="label">Số lượng:</span>
                                                <span class="value quantity-val">1</span>
                                            </div>
                                            <div class="detail-row total uk-flex uk-flex-space-between">
                                                <span class="label">Tổng tiền:</span>
                                                <span class="value text-red total-price-val">0 đ</span>
                                            </div>
                                        </div>

                                        <h2 class="step-title mt20">Thông tin thanh toán</h2>

                                        <div class="payment-info-box mt10">
                                            <div class="qr-card dark-glass">
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
                                            
                                            <div class="uk-text-center mt15">
                                                <a href="#order-payment-modal" data-uk-modal class="btn-show-payment-detail">Xem chi tiết thông tin thanh toán</a>
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
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
        @endforeach
        @endif
    </div>

    <!-- Modal Chi tiết thanh toán -->
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
                                            <span class="bank-value bold">{{  $system['bank_account'] ?? '-'  }}</span>
                                            <i class="fa fa-copy btn-copy"></i>
                                        </div>
                                    </div>
                                    <div class="bank-row">
                                        <div class="bank-label">Chủ tài khoản</div>
                                        <div class="bank-value-line">
                                            <span class="bank-value bold uppercase">{{  $system['bank_name'] ?? '-'  }}</span>
                                            <i class="fa fa-copy btn-copy"></i>
                                        </div>
                                    </div>
                                    <div class="bank-row">
                                        <div class="bank-label">Số tiền</div>
                                        <div class="bank-value-line">
                                            <span class="bank-value bold text-yellow"><span class="transfer-amount-val total-price-val">0</span> VND</span>
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

@endsection
