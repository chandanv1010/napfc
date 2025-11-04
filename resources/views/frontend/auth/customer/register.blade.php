@extends('frontend.homepage.layout')

@section('content')
    <section class="login-page">
        <div class="login-container">
            <div class="login-card">
            <h2 class="title">Đăng ký ngay</h2>

            <form action="{{ route('customer.register.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="Nhập email của bạn" value="{{ old('email') }}">
                    @error('email')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="account">Tên tài khoản</label>
                    <input type="text" id="account" name="account" placeholder="Nhập tên tài khoản của bạn" value="{{ old('account') }}">
                    @error('account')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Mật khẩu</label>
                    <input type="password" id="password" name="password" placeholder="Nhập mật khẩu">
                    @error('password')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="confirm_password">Xác nhận mật khẩu</label>
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Nhập lại mật khẩu">
                    @error('confirm_password')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn-login">Đăng ký</button>
            </form>


            <div class="register-hint">
                <p>Đã có tài khoản? <a href="{{ route('customer.auth') }}">Đăng nhập ngay</a></p>
            </div>
            </div>
        </div>
    </section>

@endsection