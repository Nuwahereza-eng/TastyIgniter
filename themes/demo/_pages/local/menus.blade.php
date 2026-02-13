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
                class="text-decoration-none back-button d-inline-flex align-items-center px-3 py-2 rounded"
                href="{{page_url('locations')}}"
                style="background: #f8f9fa; color: #495057; font-weight: 500;"
            >
                <i class="fa fa-arrow-left-long me-2" style="color: #FF4900;"></i>
                @lang('igniter.orange::default.button_back')
            </a>
        </div>
        <x-igniter-orange::local-header />
    </div>
</div>

<div class="bg-white">
    <div class="container pt-5 pb-2">
        <div class="d-flex align-items-center mb-4">
            <h2>Our Menu</h2>
        </div>

        <div class="row">
            <div class="col-lg-8 mb-4">
                <div class="mb-3">
                    <x-igniter-orange::category-list />
                </div>
                <livewire:igniter-orange::menu-item-list />
            </div>
            <div class="col-lg-4 mb-4">
                <div class="sticky-top" style="top: 80px; z-index: 100;">
                    <livewire:igniter-orange::cart-box />
                </div>
            </div>
        </div>
    </div>
</div>

<livewire:igniter-orange::fulfillment-modal />
