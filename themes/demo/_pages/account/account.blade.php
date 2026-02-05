---
title: igniter.orange::default.account_title
permalink: /account
layout: default
security: customer

'[igniter-orange::account-settings]': []
---
<div class="container">
    <div class="row py-5">
        <div class="col-sm-2 pe-0">
            <x-igniter-orange::nav code="account-menu" />
        </div>

        <div class="col-sm-10">
            {{-- Custom Account Dashboard without Cart --}}
            @php
                $customer = \Igniter\User\Facades\Auth::getUser();
                $customerName = $customer->full_name ?? '';
                $hasDefaultAddress = !is_null($customer?->address);
                $formattedAddress = $hasDefaultAddress ? format_address($customer?->address) : '';
            @endphp
            
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="mb-0">{{ sprintf(lang('igniter.user::default.text_welcome'), $customerName) }}</h5>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-body">
                    @if ($hasDefaultAddress)
                        <h5 class="font-weight-normal">
                            @lang('igniter.user::default.text_default_address')
                            <a
                                wire:navigate
                                class="btn btn-outline-secondary edit-address pull-right"
                                href="{{ page_url('account.address') }}"
                            >@lang('igniter.user::default.text_edit')</a>
                        </h5>
                        <address class="text-left text-overflow">{!! $formattedAddress !!}</address>
                    @else
                        <p>@lang('igniter.user::default.text_no_default_address')</p>
                    @endif
                </div>
            </div>
            
            <livewire:igniter-orange::account-settings />
        </div>
    </div>
</div>
