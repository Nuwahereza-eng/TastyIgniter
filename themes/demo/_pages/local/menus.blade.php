---
title: igniter.orange::default.menus_title
permalink: '/:location?local/menus/:category?'
description: ''
layout: default
hideFooter: 1

'[igniter-orange::local-header]': []
'[igniter-orange::fulfillment]': []
'[igniter-orange::category-list]': []
'[igniter-orange::menu-item-list]':
    showThumb: true
    menuThumbWidth: 120
    menuThumbHeight: 120
    itemsPerPage: 20
'[igniter-orange::cart-box]': []
'[igniter-orange::fulfillment-modal]': []
---
<div class="bg-white border-bottom border-1">
    <div class="container py-4">
        <div class="mb-3" wire:ignore>
            <a
                class="text-decoration-none"
                href="{{page_url('locations')}}"
            >
                <i class="fa fa-arrow-left-long"></i>&nbsp;&nbsp;
                @lang('igniter.orange::default.button_back')
            </a>
        </div>
        <div class="row align-items-start">
            <div class="col-lg-8">
                <livewire:igniter-orange::local-header />
            </div>
            <div class="col-lg-4 mt-4 mt-lg-0">
                <livewire:igniter-orange::cart-box />
            </div>
        </div>
    </div>
</div>

<div class="bg-white">
    <div class="container pt-5 pb-2">
        <div class="d-flex align-items-center mb-4">
            <h2>@lang('igniter.orange::default.menus_heading')</h2>
        </div>

        <div class="row">
            <div class="col-lg-3 mb-4">
                <livewire:igniter-orange::category-list />
            </div>
            <div class="col-lg-9 mb-4">
                <livewire:igniter-orange::menu-item-list />
            </div>
        </div>
    </div>
</div>

<livewire:igniter-orange::fulfillment-modal />
