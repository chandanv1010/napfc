<header class="pc-header uk-visible-large"><!-- HEADER -->
	<div class="uk-container uk-container-center">
        <div class="uk-flex uk-flex-middle uk-flex-space-between">
            <div class="logo"><a href="."><img src="{{ $system['homepage_logo'] }}" alt="logo"></a></div>
            @include('frontend.component.navigation')
            <div class="header-widget">
            <div class="uk-flex uk-flex-middle uk-flex-gap">
                @if(Auth::guard('customer')->check())
                    @php
                        $user = Auth::guard('customer')->user();
                    @endphp

                    {{-- Nút 1: Xin chào --}}
                    <a href="" class="btn-welcome">
                        👋 Xin chào, <strong>{{ $user->account ?? $user->email }}</strong>
                    </a>

                   
                    {{-- Nút 3: Đăng xuất --}}
                    <a href="{{ route('customer.logout') }}" class="btn-logout"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        Đăng xuất
                    </a>

                    {{-- Nút 2: Nạp số dư --}}
                    <a href="{{ write_url('nap-so-du') }}" class="btn-topup">
                        💰 Nạp số dư
                    </a>


                    <form id="logout-form" action="{{ route('customer.logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>

                @else
                    <a href="{{ route('customer.auth') }}" class="btn-login">Đăng nhập</a>
                    <a href="{{ write_url('nap-so-du') }}" class="btn-topup">💰 Nạp số dư</a>
                @endif
            </div>
        </div>
        </div>
    </div>
</header><!-- .header -->
@include('frontend.component.header-mobile')

<script>
    window.isAuthenticated = @json(Auth::check())
</script>