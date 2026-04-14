<aside class="profile-sidebar">
    <div class="aside-panel">
        <div class="user-brief">
            <div class="avatar"><i class="fa fa-user-circle"></i></div>
            <div class="name">{{ Auth::guard('customer')->user()->account ?? Auth::guard('customer')->user()->email }}</div>
        </div>
        <ul class="uk-list profile-nav">
            <li class="{{ Route::currentRouteName() == 'customer.profile' ? 'active' : '' }}">
                <a href="{{ route('customer.profile') }}"><i class="fa fa-user"></i> Hồ sơ của tôi</a>
            </li>
            <li class="{{ Route::currentRouteName() == 'customer.password.change' ? 'active' : '' }}">
                <a href="{{ route('customer.password.change') }}"><i class="fa fa-key"></i> Đổi mật khẩu</a>
            </li>
            <li class="{{ Route::currentRouteName() == 'customer.construction' ? 'active' : '' }}">
                <a href="{{ route('customer.construction') }}"><i class="fa fa-shopping-cart"></i> Lịch sử mua hàng</a>
            </li>
            <li>
                <a href="{{ route('customer.logout') }}" class="logout-link"><i class="fa fa-sign-out"></i> Đăng xuất</a>
            </li>
        </ul>
    </div>
</aside>