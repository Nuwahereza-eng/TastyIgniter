<?php

return [
    'fields' => [
        'public_key' => [
            'label' => 'Public Key',
            'type' => 'text',
            'span' => 'left',
            'comment' => 'Your Flutterwave Public Key',
        ],
        'secret_key' => [
            'label' => 'Secret Key',
            'type' => 'text',
            'span' => 'right',
            'comment' => 'Your Flutterwave Secret Key',
        ],
        'encryption_key' => [
            'label' => 'Encryption Key',
            'type' => 'text',
            'span' => 'left',
            'comment' => 'Your Flutterwave Encryption Key',
        ],
        'test_mode' => [
            'label' => 'Test Mode',
            'type' => 'switch',
            'default' => true,
            'span' => 'right',
            'comment' => 'Use test API keys',
        ],
        'currency' => [
            'label' => 'Currency',
            'type' => 'select',
            'span' => 'left',
            'default' => 'UGX',
            'options' => [
                'UGX' => 'Ugandan Shilling (UGX)',
                'USD' => 'US Dollar (USD)',
                'KES' => 'Kenyan Shilling (KES)',
            ],
            'comment' => 'Transaction currency',
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
        ['public_key', 'Flutterwave Public Key', 'string'],
        ['secret_key', 'Flutterwave Secret Key', 'string'],
        ['order_status', 'admin::lang.payments.label_order_status', 'integer'],
        ['order_total', 'admin::lang.payments.label_order_total', 'numeric'],
    ],
];
