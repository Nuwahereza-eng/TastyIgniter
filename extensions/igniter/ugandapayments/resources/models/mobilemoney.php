<?php

return [
    'fields' => [
        'mtn_api_key' => [
            'label' => 'MTN API Key',
            'type' => 'text',
            'span' => 'left',
            'comment' => 'Your MTN MoMo API Primary Key',
        ],
        'mtn_api_secret' => [
            'label' => 'MTN API Secret',
            'type' => 'text',
            'span' => 'right',
            'comment' => 'Your MTN MoMo API Secret',
        ],
        'airtel_api_key' => [
            'label' => 'Airtel API Key',
            'type' => 'text',
            'span' => 'left',
            'comment' => 'Your Airtel Money API Key',
        ],
        'airtel_api_secret' => [
            'label' => 'Airtel API Secret',
            'type' => 'text',
            'span' => 'right',
            'comment' => 'Your Airtel Money API Secret',
        ],
        'test_mode' => [
            'label' => 'Test Mode',
            'type' => 'switch',
            'default' => true,
            'span' => 'left',
            'comment' => 'Use sandbox/test API endpoints',
        ],
        'order_total' => [
            'label' => 'Minimum Order Total',
            'type' => 'currency',
            'default' => 1000,
            'span' => 'right',
            'comment' => 'Minimum order total for this payment method',
        ],
        'order_status' => [
            'label' => 'Order Status',
            'type' => 'select',
            'span' => 'left',
            'options' => [\Igniter\Admin\Models\Status::class, 'getDropdownOptionsForOrder'],
            'comment' => 'Order status after successful payment',
        ],
    ],
    'rules' => [
        ['order_status', 'admin::lang.payments.label_order_status', 'integer'],
        ['order_total', 'admin::lang.payments.label_order_total', 'numeric'],
    ],
];
