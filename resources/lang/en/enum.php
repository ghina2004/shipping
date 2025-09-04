<?php

return [
    'order_payment_status' => [
        'unpaid'  => 'Unpaid',
        'partial' => 'Partially Paid',
        'paid'    => 'Fully Paid',
    ],

    'order_status' => [
        'preparing'        => 'Preparing',
        'with_employee'    => 'With Employee',
        'with_shipment_mgr'=> 'With Shipment Manager',
        'with_accountant'  => 'With Accountant',
        'delivered'        => 'Delivered',
        'not_delivered'    => 'Not Delivered',
    ],

    'shipment_status' => [
        'pending'   => 'Pending',
        'delivered' => 'Delivered',
    ],

    'status' => [
        'new'  => 'New',
        'old'  => 'Old',
    ],

    'complaint_status' => [
        'open'     => 'Open',
        'replied'  => 'Replied',
        'resolved' => 'Resolved',
    ],

    'contract_type' => [
        'service'           => 'Service Agreement',
        'goods_description' => 'Goods Description',
        'bill_of_lading'    => 'Bill of Lading',
    ],
    'contract_status' => [
        'pending_signature' => 'Pending Signature',
        'signed'            => 'Signed',
        'final'             => 'Final',
    ],

];
