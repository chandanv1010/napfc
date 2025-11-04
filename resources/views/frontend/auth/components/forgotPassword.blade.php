@extends('frontend.homepage.layout')
@section('content')

    <section class="login-page">
        <div class="login-container">
            <div class="login-card">
            <h2 class="title">Quên mật khẩu</h2>
                <form action="{{ route('customer.password.verify') }}" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="email">Nhập vào địa chỉ Email để khôi phục mật khẩu</label>
                        <input type="text" id="email" name="email" placeholder="" >
                        @error('email')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-extra">
                    <a href="{{ route('customer.password.forgot') }}" class="forgot">Quên mật khẩu?</a>
                    </div>

                    <button type="submit" class="btn-login">Đăng nhập</button>
                </form>
            </div>
        </div>
    </section>

@endsection
