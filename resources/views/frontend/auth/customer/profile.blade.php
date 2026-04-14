@extends('frontend.homepage.layout')
@section('content')
    <div class="dashboard-page-wrapper pt40 pb40">
        <div class="uk-container uk-container-center">
            <div class="uk-grid uk-grid-medium">
                <div class="uk-width-large-1-4">
                    @include('frontend.auth.customer.components.sidebar')
                </div>
                <div class="uk-width-large-3-4">
                    <div class="dashboard-premium-box">
                        <div class="box-head">
                            <h2 class="title">HỒ SƠ CỦA TÔI</h2>
                            <p class="subtitle">Quản lý thông tin hồ sơ để bảo mật tài khoản</p>
                        </div>
                        <div class="box-body">
                            @include('backend/dashboard/component/formError')
                            <form action="{{ route('customer.profile.update') }}" method="post" class="uk-form uk-form-horizontal dashboard-form">
                                @csrf
                                <div class="form-group row">
                                    <label class="form-label">Tài khoản đăng nhập</label>
                                    <div class="form-content">
                                        <span class="user-email">{{ $buyer->email }}</span>
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label class="form-label">Họ Tên</label>
                                    <div class="form-content">
                                        <input type="text" class="form-input" name="name" value="{{ old('name', $buyer->name) }}" placeholder="Nhập họ tên của bạn">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="form-label">Email</label>
                                    <div class="form-content">
                                        <input type="email" class="form-input" name="email" value="{{ old('email', $buyer->email) }}" placeholder="Nhập địa chỉ email">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="form-label">Số điện thoại</label>
                                    <div class="form-content">
                                        <input type="text" class="form-input" name="phone" value="{{ old('phone', $buyer->phone) }}" placeholder="Nhập số điện thoại">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="form-label">Địa chỉ</label>
                                    <div class="form-content">
                                        <input type="text" class="form-input" name="address" value="{{ old('address', $buyer->address) }}" placeholder="Nhập địa chỉ của bạn">
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <button type="submit" class="btn-dashboard-submit">LƯU THÔNG TIN</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
