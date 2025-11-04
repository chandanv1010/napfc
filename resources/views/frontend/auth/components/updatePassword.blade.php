@extends('frontend.homepage.layout')
@section('content')

    <section class="login-page">
        <div class="login-container">
            <div class="login-card">
            <h2 class="title">Cập nhật mật khẩu</h2>
                <form action="{{ route('customer.password.reset') }}" method="post">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="email" value="{{ $email }}">
                    <input type="hidden" name="token" value="{{ $token }}">
                    <div class="form-group">
                        <label for="email">Mật khẩu mới</label>
                        <input type="password" id="password" name="password" placeholder="" >
                        @error('password')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email">Xác nhận Mật khẩu mới</label>
                        <input type="password" id="confirm_password" name="confirm_password" placeholder="" >
                        @error('confirm_password')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>


                    <button type="submit" class="btn-login">Đổi mật khẩu</button>
                </form>
            </div>
        </div>
    </section>

@endsection
