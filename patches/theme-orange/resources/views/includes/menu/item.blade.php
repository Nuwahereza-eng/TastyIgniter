<div
    id="menu{{ $menuItemData->id }}"
    @class([
        'menu-card bg-white h-100 border rounded shadow-sm overflow-hidden',
        'shadow-hover cursor-pointer' => $menuItemData->mealtimeIsAvailable(),
        'opacity-50 cursor-no-drop' => !$menuItemData->mealtimeIsAvailable(),
    ])
    @if($menuItemData->mealtimeIsAvailable())
        data-toggle="orange-modal"
        data-component="igniter-orange::cart-item-modal"
        data-arguments='{"menuId": {{ $menuItemData->id }}}'
    @endif
>
    {{-- Large image at top - always show --}}
    <div class="menu-item-image position-relative" style="height: 180px; overflow: hidden;">
        @if($menuItemData->hasThumb())
            <img 
                src="{{ $menuItemData->getThumb(['width' => 400, 'height' => 180]) }}" 
                alt="{{ $menuItemData->name }}"
                class="w-100 h-100"
                style="object-fit: cover;"
                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
            >
            <div style="display:none; align-items:center; justify-content:center; height:100%; background: linear-gradient(135deg, #FF6B35 0%, #FF4900 100%); font-size:3rem; color:white;">🍽️</div>
        @else
            <div style="display:flex; align-items:center; justify-content:center; height:100%; background: linear-gradient(135deg, #FF6B35 0%, #FF4900 100%); font-size:3rem; color:white;">🍽️</div>
        @endif
        @if ($menuItemData->specialIsActive())
            <span class="position-absolute top-0 end-0 m-2 badge bg-danger">SALE</span>
        @endif
        @if (!$menuItemData->mealtimeIsAvailable())
            <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" style="background: rgba(0,0,0,0.5);">
                <span class="badge bg-secondary fs-6"><i class="far fa-clock me-1"></i>{{ $menuItemData->mealtimeTitles() }}</span>
            </div>
        @endif
    </div>

    {{-- Name and Price --}}
    <div class="p-3">
        <div class="d-flex justify-content-between align-items-start">
            <h6 class="menu-name fw-bold mb-0 flex-grow-1 pe-2">{{ $menuItemData->name }}</h6>
            <span class="menu-price fw-bold text-nowrap" style="color: #FF4900;">
                @if ($menuItemData->specialIsActive())
                    <s class="text-muted small d-block">{!! currency_format($menuItemData->priceBeforeSpecial) !!}</s>
                @endif
                {!! $menuItemData->price() > 0 ? currency_format($menuItemData->price()) : lang('igniter::main.text_free') !!}
            </span>
        </div>
        @if ($menuItemData->specialIsActive() && $menuItemData->specialDaysRemaining())
            <small class="text-warning">
                {!! sprintf(lang('igniter.local::default.text_end_elapsed'), $menuItemData->specialDaysRemaining()) !!}
            </small>
        @endif
    </div>
</div>
