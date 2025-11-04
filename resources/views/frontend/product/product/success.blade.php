@extends('frontend.homepage.layout')

@section('content')
<div class="uk-section uk-section-muted uk-flex uk-flex-center uk-flex-middle" style="min-height: 80vh;">
    <div class="uk-container uk-animation-fade">

        <div class="uk-card uk-card-default uk-card-body uk-width-xlarge uk-box-shadow-large">
            {{-- Header --}}
            <div class="uk-text-center">
                <div class="uk-margin-small-bottom">
                    <span uk-icon="icon: check; ratio: 3" class="uk-text-success"></span>
                </div>
                <h2 class="uk-text-success uk-text-bold">Thanh toán thành công!</h2>
                <p class="uk-text-muted">Cảm ơn bạn đã tin tưởng và mua sản phẩm của chúng tôi.</p>
            </div>

            {{-- Transaction Info --}}
            <div class="uk-margin-top uk-grid-small uk-child-width-1-2@m" uk-grid>
                <div>
                    <p><strong>Mã giao dịch:</strong><br>{{ $transaction->transaction_code }}</p>
                </div>
                <div>
                    <p><strong>Trạng thái:</strong><br>
                        <span class="uk-label uk-label-success">Đã thanh toán</span>
                    </p>
                </div>
                <div>
                    <p><strong>Sản phẩm:</strong><br>{{ $product->name ?? 'Không xác định' }}</p>
                </div>
                <div>
                    <p><strong>Thời gian thanh toán:</strong><br>{{ $transaction->paid_at }}</p>
                </div>
            </div>

            {{-- Account Info --}}
            <div class="uk-margin-top">
                <div class="uk-alert-success" uk-alert>
                    <h4 class="uk-text-bold" style="padding:10px;">Thông tin tài khoản của bạn</h4>
                     
                </div>
                <div style="padding:20px;">
                    {!! nl2br(e($product->account_info)) !!}
                </div>
            </div>

            {{-- Footer --}}
            <div class="uk-margin-top uk-text-center">
                <a href="{{ url('/') }}" class="uk-button uk-button-primary uk-border-rounded">
                    ⬅️ Quay lại trang chủ
                </a>
            </div>
        </div>

    </div>
</div>
@endsection
