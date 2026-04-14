@extends('frontend.homepage.layout')

@section('content')
<div class="success-page-wrapper uk-flex uk-flex-center uk-flex-middle">
    <div class="uk-container">
        <div class="success-premium-box">
            {{-- Header --}}
            <div class="header-section uk-text-center">
                <div class="success-icon-glow">
                    <i class="fa fa-check-circle"></i>
                </div>
                <h2 class="title">THANH TOÁN THÀNH CÔNG!</h2>
                <p class="subtitle">Cảm ơn bạn đã tin tưởng và sử dụng dịch vụ của chúng tôi.</p>
            </div>

            {{-- Transaction Details --}}
            <div class="info-grid">
                <div class="info-item">
                    <label>Mã giao dịch</label>
                    <span>{{ $transaction->transaction_code }}</span>
                </div>
                <div class="info-item">
                    <label>Trạng thái</label>
                    <span class="status-badge">Đã thanh toán</span>
                </div>
                <div class="info-item">
                    <label>Sản phẩm</label>
                    <span>{{ $product->name ?? 'Không xác định' }}</span>
                </div>
                <div class="info-item">
                    <label>Thời gian</label>
                    <span>{{ $transaction->paid_at }}</span>
                </div>
            </div>

            {{-- Credentials Area --}}
            <div class="credentials-section">
                <h3 class="credentials-title">Thông tin tài khoản của bạn</h3>
                <div class="credentials-content">
                    {!! nl2br(e($product->account_info)) !!}
                </div>
                <p class="warning-text">VUI LÒNG ĐỔI MẬT KHẨU NGAY SAU KHI ĐĂNG NHẬP THÀNH CÔNG!</p>
            </div>

            {{-- Footer --}}
            <div class="footer-actions uk-text-center">
                <a href="{{ url('/') }}" class="btn-return-home">
                    <i class="fa fa-home"></i> QUAY LẠI TRANG CHỦ
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
