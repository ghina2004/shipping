<?php

return [
    'required' => 'The :attribute field is required.',
    'string' => 'The :attribute must be a string.',
    'max' => [
        'string' => 'The :attribute may not be greater than :max characters.',
    ],
    'min' => [
        'string' => 'The :attribute must be at least :min characters.',
    ],
    'confirmed' => 'The :attribute confirmation does not match.',
    'regex' => 'The :attribute format is invalid.',
    'email' => 'The email format is invalid.',
    'unique' => 'The :attribute has already been taken.',
    'in' => 'The selected :attribute is invalid.',
    'date' => 'The :attribute must be a valid date.',
    'exists' => 'The selected :attribute does not exist.',
    'integer' => 'The :attribute must be an integer.',
    'nullable' => 'The :attribute field is optional.',

    'attributes' => [
        'first_name' => 'first name',
        'second_name' => 'second name',
        'third_name' => 'third name',
        'email' => 'email',
        'phone' => 'phone number',
        'password' => 'password',
        'code' => 'verification code',
        'type' => 'type',


        'category_id' => 'category',
        'shipping_date' => 'shipping date',
        'service_type' => 'service type',
        'origin_country' => 'origin country',
        'destination_country' => 'destination country',
        'shipping_method' => 'shipping method',
        'cargo_weight' => 'cargo weight',
        'containers_size' => 'containers size',
        'containers_numbers' => 'containers count',
        'customer_notes' => 'customer notes',
        'employee_notes' => 'employee notes',


        'shipment_id' => 'shipment ID',
        'answers' => 'answers',
        'answers.*.shipment_question_id' => 'question ID',
        'answers.*.answer' => 'answer',
    ],

];
