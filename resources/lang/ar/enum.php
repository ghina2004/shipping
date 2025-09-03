<?php

return [
    'order_payment_status' => [
        'unpaid'  => 'غير مدفوع',
        'partial' => 'مدفوع جزئياً',
        'paid'    => 'مدفوع بالكامل',
    ],

    'order_status' => [
        'preparing'        => 'جارٍ التجهيز',
        'with_employee'    => 'مع الموظف',
        'with_shipment_mgr'=> 'مع مدير الشحن',
        'with_accountant'  => 'مع المحاسب',
        'delivered'        => 'تم التوصيل',
        'in_process'    => 'جارٍ المعالجة',
    ],

    'shipment_status' => [
        'pending'   => 'قيد الشحن',
        'delivered' => 'تم التسليم',
    ],

    'status' => [
        'new'  => 'جديد',
        'old'  => 'قديم',
    ],

    'complaint_status' => [
        'open'     => 'مفتوحة',
        'replied'  => 'تم الرد',
        'resolved' => 'منتهية',
    ],
];
