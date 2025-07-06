<?php

namespace App\Enums\Status;

enum OrderStatus: int
{
    case Pending = 0;                // تم إنشاء الطلب من قبل العميل
    case UnderReview = 1;           // قيد المراجعة الأولية من الموظف
    case UnderShippingReview = 2;   // قيد المراجعة من مدير الشحن
    case UnderAccountingReview = 3; // قيد المراجعة من المحاسب
    case AwaitingApproval = 4;      // بانتظار موافقة العميل
    case Approved = 5;              // تمت الموافقة
    case InTransit = 6;             // قيد الشحن
    case Delivered = 7;             // تم التوصيل
    case Cancelled = 8;             // ملغاة
    case OnHold = 9;                // معلّقة
}
