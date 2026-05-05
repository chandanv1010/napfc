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

            @if (($product->price ?? 0) < 1000000)
                {{-- Account giá < 1 triệu: Show thông tin tài khoản --}}
                <div class="credentials-section">
                    <h3 class="credentials-title">Thông tin tài khoản của bạn</h3>
                    <div class="credentials-content">
                        {!! nl2br(e($product->account_info)) !!}
                    </div>
                    <p class="warning-text">VUI LÒNG ĐỔI MẬT KHẨU NGAY SAU KHI ĐĂNG NHẬP THÀNH CÔNG!</p>
                </div>
            @else
                {{-- Account giá >= 1 triệu: Chuyển hướng về Facebook --}}
                <div class="credentials-section" id="redirect-section">
                    <h3 class="credentials-title" style="color:#ffab40;">Chuyên viên sẽ cung cấp thông tin cho bạn</h3>
                    <p style="text-align:center;color:#e0e0e0;font-size:15px;line-height:1.8;">
                        Hệ thống sẽ chuyển hướng bạn đến trang hỗ trợ sau
                        <span id="redirect-countdown" style="color:#00e676;font-weight:bold;font-size:28px;">3</span> giây...
                    </p>
                    <div style="text-align:center;margin-top:16px;">
                        <div style="display:inline-block;width:50px;height:50px;border-radius:50%;border:3px solid rgba(255,255,255,0.2);border-top-color:#00e676;animation:spin-countdown 1s linear infinite;"></div>
                    </div>
                </div>
                <style>@keyframes spin-countdown{to{transform:rotate(360deg)}}</style>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        var redirectUrl = "{{ $system['bank_redirect_url'] ?? 'https://www.facebook.com/buiphuongdai.fc' }}";
                        var countdown = 3;
                        var el = document.getElementById('redirect-countdown');
                        var timer = setInterval(function() {
                            countdown--;
                            el.textContent = countdown;
                            if (countdown <= 0) {
                                clearInterval(timer);
                                window.location.href = redirectUrl;
                            }
                        }, 1000);
                    });
                </script>
            @endif

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

