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
                            <h2 class="title">LỊCH SỬ MUA HÀNG</h2>
                            <p class="subtitle">Quản lý danh sách các tài khoản bạn đã mua tại hệ thống</p>
                        </div>
                        <div class="box-body">
                            <div class="uk-overflow-container">
                                <table class="dashboard-table">
                                    <thead>
                                        <tr>
                                            <th>Mã Account</th>
                                            <th>Tên Sản Phẩm</th>
                                            <th>Thời Gian</th>
                                            <th>Trạng Thái</th>
                                            <th>Hành Động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!is_null($construction->products) && count($construction->products) > 0)
                                            @foreach($construction->products as $key => $val)
                                            @php
                                                $name = $val->languages->first()->pivot->name;
                                            @endphp
                                            <tr>
                                                <td class="code-cell">#{{ $val->code }}</td>
                                                <td class="name-cell">{{ $name }}</td>
                                                <td>{{ $val->created_at ?? '---' }}</td>
                                                <td><span class="status-badge success">Thành công</span></td>
                                                <td>
                                                    <a href="{{ route('account.success', ['code' => $val->pivot->transaction_code]) }}" class="btn-table-view">Xem chi tiết</a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        @else
                                        <tr>
                                            <td colspan="5" class="empty-msg">Bạn chưa mua tài khoản nào.</td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
