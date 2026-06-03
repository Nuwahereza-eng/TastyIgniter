---
title: igniter.orange::default.account_orders_title
layout: default
permalink: /account/orders
security: customer
---
@php
    use Igniter\User\Facades\Auth;
    $orders = Auth::customer()
        ? Auth::customer()->orders()
            ->with(['location', 'status'])
            ->whereProcessed(true)
            ->listFrontEnd([
                'page' => request()->input('page', 1),
                'pageLimit' => 20,
                'sort' => 'created_at desc',
            ])
        : collect();

    $statusBadge = function ($name) {
        $n = strtolower((string) $name);
        if (str_contains($n, 'deliver') || str_contains($n, 'complete')) return 'bg-success';
        if (str_contains($n, 'cancel') || str_contains($n, 'fail') || str_contains($n, 'refund')) return 'bg-danger';
        if (str_contains($n, 'way') || str_contains($n, 'pick') || str_contains($n, 'rider')) return 'bg-info text-dark';
        if (str_contains($n, 'prepar') || str_contains($n, 'ready') || str_contains($n, 'kitchen')) return 'bg-warning text-dark';
        if (str_contains($n, 'pend') || str_contains($n, 'await')) return 'bg-secondary';
        return 'bg-primary';
    };
@endphp
<div class="container">
    <div class="row py-5">
        <div class="col-sm-2 pe-0">
            <x-igniter-orange::nav code="account-menu"/>
        </div>

        <div class="col-sm-10">
            <div class="card">
                <div class="card-body">
                    <h4 class="mb-4"><i class="fa fa-receipt me-2 text-warning"></i>My Orders</h4>

                    @if (count($orders))
                        <div class="list-group list-group-flush ugaeats-orders">
                            @foreach ($orders as $order)
                                @php
                                    $formattedId = 'UGA-'.str_pad((string) $order->order_id, 5, '0', STR_PAD_LEFT);
                                    $statusName = $order->status->status_name ?? '—';
                                    $when = $order->order_date
                                        ? $order->order_date->setTimeFromTimeString($order->order_time)
                                        : $order->created_at;
                                @endphp
                                <div class="list-group-item py-3 px-2 px-md-3">
                                    <div class="row align-items-center g-3">
                                        <div class="col-12 col-md-4">
                                            <div class="d-flex align-items-center">
                                                <div class="order-num-pill me-3">
                                                    <i class="fa fa-receipt"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-bold">{{ $formattedId }}</div>
                                                    <small class="text-muted">
                                                        {{ $order->location?->location_name ? $order->location->location_name.' · ' : '' }}{{ $when?->isoFormat('MMM D, h:mm a') }}
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-3">
                                            <span class="badge {{ $statusBadge($statusName) }} px-3 py-2">
                                                {{ $statusName }}
                                            </span>
                                        </div>
                                        <div class="col-6 col-md-2 text-md-start text-end">
                                            <div class="fw-bold">{{ currency_format($order->order_total) }}</div>
                                            <small class="text-muted">{{ $order->total_items }} {{ \Illuminate\Support\Str::plural('item', (int) $order->total_items) }}</small>
                                        </div>
                                        <div class="col-12 col-md-3 d-flex gap-2 justify-content-md-end">
                                            <a href="{{ url('/track-order') }}?order={{ urlencode($order->hash ?: (string) $order->order_id) }}"
                                               class="btn btn-sm"
                                               style="background-color:#ff4900;border-color:#ff4900;color:#fff;">
                                                <i class="fa fa-motorcycle me-1"></i>Track
                                            </a>
                                            <a href="{{ page_url('account.order', ['orderId' => $order->order_id, 'hash' => $order->hash]) }}"
                                               class="btn btn-sm btn-outline-secondary"
                                               title="View order details">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-3">
                            {!! $orders->links() !!}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fa fa-receipt fa-3x text-muted mb-3"></i>
                            <h6 class="text-muted">You haven't placed any orders yet</h6>
                            <a href="{{ url('/local/menus') }}" class="btn mt-2" style="background-color:#ff4900;border-color:#ff4900;color:#fff;">
                                <i class="fa fa-utensils me-1"></i>Browse Menu
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.ugaeats-orders .list-group-item + .list-group-item { border-top: 1px solid #f0f0f0; }
.ugaeats-orders .order-num-pill {
    width: 40px; height: 40px; border-radius: 10px;
    background: linear-gradient(135deg, #ff4900 0%, #ff6b35 100%);
    color: #fff; display: flex; align-items: center; justify-content: center;
    font-size: 1rem; flex-shrink: 0;
}
</style>
