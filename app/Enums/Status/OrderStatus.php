<?php

namespace App\Enums\Status;

enum OrderStatus: int
{
    case Pending = 0;                // تم إنشاء الطلب من قبل العميل
    case UnderEmployeeReview = 1;           // قيد المراجعة الأولية من الموظف
    case UnderShippingReview = 2;   // قيد المراجعة من مدير الشحن
    case UnderAccountingReview = 3; // قيد المراجعة من المحاسب
}
