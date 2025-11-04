<footer class="footer">
    <div class="uk-container uk-container-center">
        <div class="uk-grid uk-grid-medium">
            <div class="uk-width-large-1-4">
                <div class="footer-infor">
                    <a href="" class="image"><img src="{{ $system['homepage_logo'] }}" alt="logo"></a>
                    <div class="footer-address mt20">
                        <p>Địa chỉ: {{ $system['contact_address'] }}</p>
                        <p>Hotline: {{ $system['contact_hotline'] }}</p>
                        <p>Email: {{ $system['contact_email'] }}</p>
                    </div>
                    <div class="footer-social uk-flex uk-flex-middle mt40">
                        <a href="{!! $system['social_facebook'] !!}"><i class="fa fa-facebook"></i></a>
                        <a href="{!! $system['social_facebook'] !!}"><i class="fa fa-google"></i></a>
                        <a href="{!! $system['social_facebook'] !!}"><i class="fa fa-youtube"></i></a>
                    </div>
                </div>
            </div>
            <div class="uk-width-large-2-4">
                @if(isset($menu['footer-menu']))
                @foreach($menu['footer-menu'] as $key => $val)
                @php
                    $name = $val['item']->languages->first()->pivot->name;
                @endphp
                <div class="footer-menu">
                    <h2 class="footer-heading">{{ $name }}</h2>
                    @if(isset($val['children']))
                    <ul class="uk-list uk-clearfix uk-grid uk-grid-medium uk-grid-width-large-1-2">
                        @foreach($val['children'] as $children)
                        @php
                            $nameC = $children['item']->languages->first()->pivot->name;
                            $canonical = $children['item']->languages->first()->pivot->canonical;
                        @endphp
                        <li><a href="{{ $canonical }}">{{ $nameC }}</a></li>
                        @endforeach
                    </ul>
                    @endif
                </div>
                @endforeach
                @endif
            </div>
            <div class="uk-width-large-1-4">
                <div class="footer-menu">
                    <div class="footer-heading">Liên Kết Nhanh</div>
                    <ul class="uk-list ">
                        <li><a href="{{ write_url('dang-nhap') }}">Đăng nhập</a></li>
                        <li><a href="{{ write_url('dang-ky') }}">Đăng ký</a></li>
                        <li><a href="{{ write_url('tai-khoan') }}">Tài khoản</a></li>
                        <li><a href="{{ write_url('huong-dan') }}">Hướng dẫn</a></li>
                    </ul>
                </div>
            </div>
        </div>    
    </div>   
   
</footer>
<div class="copyright">
    {{ $system['homepage_copyright'] }}    
</div> 

 <div class="uk-modal qrcodeModal">
    <div class="uk-modal-dialog">
        <div class="qrcode_gradient"> 
            <img decoding="async" alt="" src="{{ asset('userfiles/image/qrcode-gradient-mb.png') }}" loading="lazy" class="jsx-d22f6bd0771ae323 img-fluid"> 
        </div>
        <p class="notice">Mỗi giao dịch quét QR code sẽ tự động cộng tiền. Để nạp thêm, vui lòng tạo mã QR code mới. Lưu ý: Không quét cùng một mã QR nhiều lần.
             <br> 
             <span class="uk-text-danger">KHÔNG THAY ĐỔI NỘI DUNG CHUYỂN KHOẢN</span>
             <br>
            {!! isset($showSubDescription) ? '<div class="uk-text-center">NẾU ĐÃ THANH TOÁN KHÔNG ĐÓNG POPUP NÀY</div>' : '' !!}
        </p>
        <img id="qr_image" src="" alt="QR Code">
    </div>
</div>


<script>
    window.isCustomerLoggedIn = {{ Auth::guard('customer')->check() ? 'true' : 'false' }};
    window.loginUrl = "{{ route('customer.auth') }}";
    window.customerId = {{ Auth::guard('customer')->check() ? Auth::guard('customer')->user()->id : 'null' }};
</script>