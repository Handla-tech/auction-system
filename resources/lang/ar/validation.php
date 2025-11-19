{{-- resources/lang/ar/validation.php --}}
<?php

return [
    'accepted' => 'يجب قبول :attribute.',
    'active_url' => ':attribute ليس عنوان URL صالحًا.',
    // ... إضافة جميع رسائل التحقق العربية
    'required' => 'حقل :attribute مطلوب.',
    'email' => 'يجب أن يكون :attribute عنوان بريد إلكتروني صالح.',
    'unique' => 'هذا :attribute مستخدم بالفعل.',
    'min' => [
        'string' => 'يجب أن يكون :attribute على الأقل :min أحرف.',
    ],
    'confirmed' => 'تأكيد :attribute غير متطابق.',
    
    'attributes' => [
        'name' => 'الاسم',
        'email' => 'البريد الإلكتروني',
        'password' => 'كلمة المرور',
        'phone' => 'الهاتف',
        'address' => 'العنوان',
        'role' => 'الدور',
    ],
];