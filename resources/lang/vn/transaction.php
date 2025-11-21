<?php

return [
    'status' => [
        'none' => 'Tất cả trạng thái',
        'pending' => 'Chờ thanh toán',
        'paid' => 'Đã thanh toán',
        'expired' => 'Hết hạn',
        'failed' => 'Thất bại',
        'refunded' => 'Hoàn tiền',
    ],
    'type' => [
        'none' => 'Tất cả loại giao dịch',
        'garena' => 'Nạp Garena',
        'account' => 'Mua tài khoản',
    ],
    'status_badge' => [
        'pending' => 'label-warning',
        'paid' => 'label-success',
        'expired' => 'label-default',
        'failed' => 'label-danger',
        'refunded' => 'label-primary',
    ],
    'type_badge' => [
        'garena' => 'label-primary',
        'account' => 'label-info',
    ],
    'empty' => 'Không có giao dịch nào phù hợp.',
    'guest' => 'Khách vãng lai',
];
