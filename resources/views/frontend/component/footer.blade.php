<footer class="footer">
    <div class="copyright">
        {{ $system['homepage_copyright'] ?? 'Copyright 2025 &copy; NAPFCO.COM - Nạp FCONLINE, Mua Thẻ Garena, Giờ Reset Cầu Thủ, Review Mua Bán Cầu Thủ FC Online Thiết kế và vận hành bởi DANGHIEU.COM' }}
    </div>
</footer>


<div class="uk-modal qrcodeModal">
    <div class="uk-modal-dialog">
        <div class="qrcode_gradient">
            <img decoding="async" alt="" src="{{ asset('userfiles/image/qrcode-gradient-mb.png') }}"
                loading="lazy" class="jsx-d22f6bd0771ae323 img-fluid">
        </div>
        <p class="notice">Mỗi giao dịch quét QR code sẽ tự động cộng tiền. Để nạp thêm, vui lòng tạo mã QR code mới. Lưu
            ý: Không quét cùng một mã QR nhiều lần.
            <br>
            <span class="uk-text-danger">KHÔNG THAY ĐỔI NỘI DUNG CHUYỂN KHOẢN</span>
            <br>
            {!! isset($showSubDescription) ? '<div class="uk-text-center">NẾU ĐÃ THANH TOÁN KHÔNG ĐÓNG POPUP NÀY</div>' : '' !!}
        </p>
        <img id="qr_image" src="" alt="QR Code">
        <div class="qr-processing-note uk-text-center">
            <p>Hệ thống đang xử lý giao dịch</p>
            <p>Quý khách vui lòng đợi và đừng thoát khỏi màn hình.</p>
        </div>
    </div>
</div>

<div class="uk-modal accountInfoModal">
    <div class="uk-modal-dialog" style="max-width: 600px; border-redius: 10px">
        <a class="uk-modal-close uk-close"></a>
        <div class="modal-content" style="padding: 30px;">
            <h2 class="heading-2" style="margin-bottom: 20px;">
                <span>Thông tin tài khoản</span>
            </h2>
            <hr>
            <div class="uk-alert uk-alert-warning" style="margin-top: 20px; padding: 15px;">
                <p style="margin: 0; font-weight: 500;">
                    <span uk-icon="icon: warning; ratio: 1"></span>
                    <strong>Lưu ý:</strong> Thông tin chỉ hiển thị một lần duy nhất, vui lòng lưu lại thông tin trước
                    khi đóng.
                </p>
            </div>
            <div class="account-info-content" style="margin-top: 20px;">
                <div id="account_info_text"
                    style="width: 100%; min-height: 200px; padding: 20px;#ddd; border-radius: 5px; font-family: monospace; white-space: pre-wrap; background-color: #f9f9f9; line-height: 1.6;">
                </div>
            </div>
            <div style="margin-top: 25px; text-align: center;">
                <button class="uk-button uk-button-primary uk-modal-close">Đóng</button>
            </div>
        </div>
    </div>
</div>


<script>
    window.isCustomerLoggedIn = {{ Auth::guard('customer')->check() ? 'true' : 'false' }};
    window.loginUrl = "{{ route('customer.auth') }}";
    window.customerId = {{ Auth::guard('customer')->check() ? Auth::guard('customer')->user()->id : 'null' }};
</script>
