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
                            <h2 class="title">THAY ĐỔI MẬT KHẨU</h2>
                            <p class="subtitle">Nhập đầy đủ thông tin bên dưới để tiến hành bảo mật tài khoản</p>
                        </div>
                        <div class="box-body">
                            @include('backend/dashboard/component/formError')
                            <form action="{{ route('customer.password.recovery') }}" method="post" class="uk-form uk-form-horizontal dashboard-form">
                                @csrf
                                
                                <div class="form-group row">
                                    <label class="form-label">Mật khẩu cũ</label>
                                    <div class="form-content">
                                        <input type="password" class="form-input" name="password" placeholder="Nhập mật khẩu hiện tại">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="form-label">Mật khẩu mới</label>
                                    <div class="form-content">
                                        <input type="password" class="form-input" name="new_password" placeholder="Nhập mật khẩu mới">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="form-label">Xác nhận mật khẩu</label>
                                    <div class="form-content">
                                        <input type="password" class="form-input" name="re_new_password" placeholder="Nhập lại mật khẩu mới">
                                    </div>
                                </div>
                                
                                <div class="form-actions">
                                    <button type="submit" class="btn-dashboard-submit">ĐỔI MẬT KHẨU</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
