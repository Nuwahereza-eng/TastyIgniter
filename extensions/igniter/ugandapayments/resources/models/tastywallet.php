<?php

return [
    'fields' => [
        'cashback_percentage' => [
            'label' => 'Cashback Percentage',
            'type' => 'number',
            'default' => 5,
            'span' => 'left',
            'comment' => 'Cashback percentage for wallet payments (0-100)',
        ],
        'min_balance' => [
            'label' => 'Minimum Balance Required',
            'type' => 'currency',
            'default' => 0,
            'span' => 'right',
            'comment' => 'Minimum wallet balance required to use this method',
        ],
        'max_payment' => [
            'label' => 'Maximum Payment Amount',
            'type' => 'currency',
            'default' => 1000000,
            'span' => 'left',
            'comment' => 'Maximum payment amount per transaction',
        ],
        'order_total' => [
            'label' => 'Minimum Order Total',
            'type' => 'currency',
            'default' => 5000,
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
        ['cashback_percentage', 'Cashback Percentage', 'numeric|min:0|max:100'],
        ['order_status', 'admin::lang.payments.label_order_status', 'integer'],
        ['order_total', 'admin::lang.payments.label_order_total', 'numeric'],
    ],
];
