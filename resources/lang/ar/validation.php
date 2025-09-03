<?php

return [
    'required' => 'حقل :attribute مطلوب.',
    'string' => 'يجب أن يكون :attribute نصًا.',
    'max' => [
        'string' => 'يجب ألا يتجاوز :attribute :max حرفًا.',
    ],
    'min' => [
        'string' => 'يجب ألا يقل :attribute عن :min حرفًا.',
    ],
    'confirmed' => 'تأكيد :attribute غير مطابق.',
    'regex' => 'صيغة :attribute غير صحيحة.',
    'email' => 'صيغة البريد الإلكتروني غير صحيحة.',
    'unique' => ':attribute مستخدم بالفعل.',
    'in' => 'القيمة المحددة في :attribute غير صالحة.',
    'date' => 'يجب أن يكون :attribute تاريخًا صالحًا.',
    'exists' => ':attribute غير موجود في قاعدة البيانات.',
    'integer' => 'يجب أن يكون :attribute عددًا صحيحًا.',
    'nullable' => 'حقل :attribute اختياري.',


    'attributes' => [
        'first_name' => 'الاسم الأول',
        'second_name' => 'اسم الأب',
        'third_name' => 'اسم العائلة',
        'email' => 'البريد الإلكتروني',
        'phone' => 'رقم الهاتف',
        'password' => 'كلمة المرور',
        'code' => 'رمز التحقق',
        'type' => 'نوع العملية',


        'category_id' => 'القسم',
        'shipping_date' => 'تاريخ الشحن',
        'service_type' => 'نوع الخدمة',
        'origin_country' => 'بلد المنشأ',
        'destination_country' => 'بلد الوجهة',
        'shipping_method' => 'طريقة الشحن',
        'cargo_weight' => 'وزن الشحنة',
        'containers_size' => 'حجم الحاويات',
        'containers_numbers' => 'عدد الحاويات',
        'customer_notes' => 'ملاحظات العميل',
        'employee_notes' => 'ملاحظات الموظف',


        'shipment_id' => 'رقم الشحنة',
        'answers' => 'الإجابات',
        'answers.*.shipment_question_id' => 'معرّف السؤال',
        'answers.*.answer' => 'الإجابة',

        'message' => 'الرسالة'
]
    ];
