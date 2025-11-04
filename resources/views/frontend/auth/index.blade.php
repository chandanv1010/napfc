@extends('frontend.homepage.layout')

@section('content')
    <section class="login-page">
        <div class="login-container">
            <div class="login-card">
            <h2 class="title">Đăng nhập</h2>

            <form action="{{ route('customer.login') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="email">Tên tài khoản / Email</label>
                    <input type="text" id="email" name="email" placeholder="Nhập tên tài khoản hoặc của bạn" >
                    @error('email')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Mật khẩu</label>
                    <input type="password" id="password" name="password" placeholder="Nhập mật khẩu" >
                    @error('password')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-extra">
                <a href="{{ route('customer.password.forgot') }}" class="forgot">Quên mật khẩu?</a>
                </div>

                <button type="submit" class="btn-login">Đăng nhập</button>
            </form>

            <div class="register-hint">
                <p>Chưa có tài khoản? <a href="{{ route('customer.register') }}">Đăng ký ngay</a></p>
            </div>
            </div>
        </div>
    </section>

@endsection