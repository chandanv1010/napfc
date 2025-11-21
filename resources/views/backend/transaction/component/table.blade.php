<table class="table table-striped table-bordered transaction-table">
    <thead>
        <tr>
            <th>Mã giao dịch</th>
            {{-- <th>Sản phẩm</th> --}}
            <th>Loại</th>
            <th>Thông tin khách</th>
            <th class="text-right">Đơn giá (vnđ)</th>
            <th class="text-center">Số lượng</th>
            <th class="text-right">Thành tiền (vnđ)</th>
            <th>Trạng thái</th>
            <th>Thanh toán</th>
        </tr>
    </thead>
    <tbody>
        @forelse($transactions as $transaction)
            @php
                $product = $transaction->products;
                $productLanguages = optional($product)->languages;
                $language =
                    $productLanguages instanceof \Illuminate\Support\Collection ? $productLanguages->first() : null;
                $productName = $language->pivot->name ?? (optional($product)->code ?? '---');
                $customer = $transaction->customers;
                $customerName = $customer->name ?? __('transaction.guest');
                $customerPhone = $customer->phone ?? null;
                $customerEmail = $customer->email ?? null;
                $quantity = $transaction->quantity ?? 1;
                $unitAmount = (int) str_replace('.', '', (string) $transaction->amount);
                $totalAmount = $unitAmount * $quantity;
                $statusBadge = __('transaction.status_badge')[$transaction->status] ?? 'label-default';
                $statusLabel = __('transaction.status')[$transaction->status] ?? strtoupper($transaction->status);
                $typeLabel = __('transaction.type')[$transaction->type] ?? strtoupper($transaction->type);
                $typeBadge = __('transaction.type_badge')[$transaction->type] ?? 'label-default';
                $paidAt = $transaction->paid_at ? convertDateTime($transaction->paid_at, 'd/m/Y H:i:s') : null;
            @endphp
            <tr>
                <td>
                    <div class="font-bold">{{ $transaction->transaction_code }}</div>
                    <div class="text-muted">{{ convertDateTime($transaction->created_at, 'd/m/Y H:i:s') }}</div>
                </td>
                <td>
                    <div>
                        <span class="label {{ $typeBadge }}">{{ $typeLabel }}</span>
                    </div>
                </td>
                <td>
                    <div class="font-bold">{{ $customerName }}</div>
                    @if ($customerPhone)
                        <div>{{ $customerPhone }}</div>
                    @endif
                    @if ($customerEmail)
                        <div class="text-muted">{{ $customerEmail }}</div>
                    @endif
                </td>
                <td class="text-right">{{ convert_price($unitAmount, true) }}</td>
                <td class="text-center">{{ $quantity }}</td>
                <td class="text-right font-bold">{{ convert_price($totalAmount, true) }}</td>
                <td>
                    <span class="label {{ $statusBadge }}">{{ $statusLabel }}</span>
                    @if ($transaction->description)
                        <div class="text-muted mt5">
                            {{ \Illuminate\Support\Str::limit($transaction->description, 80) }}
                        </div>
                    @endif
                </td>
                <td>
                    <div>
                        {{ $paidAt ? 'Đã thanh toán' : 'Chưa thanh toán' }}
                    </div>
                    @if ($paidAt)
                        <div class="text-muted">{{ $paidAt }}</div>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="9" class="text-center text-muted">
                    {{ __('transaction.empty') }}
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
@if (isset($transactions) && method_exists($transactions, 'links'))
    {{ $transactions->links('pagination::bootstrap-4') }}
@endif
