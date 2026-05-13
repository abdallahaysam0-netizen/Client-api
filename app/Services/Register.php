<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class Register
{
    /**
     * تنفيذ عملية تسجيل مستخدم جديد.
     *
     * @param array $data
     * @return array
     * @throws ValidationException
     */
    public function execute(array $data)
    {
        Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'name.required' => 'يرجى إدخال الاسم.',
            'email.required' => 'يرجى إدخال البريد الإلكتروني.',
            'email.email' => 'البريد الإلكتروني المدخل غير صالح.',
            'email.unique' => 'هذا البريد الإلكتروني مسجل بالفعل.',
            'password.required' => 'يرجى إدخال كلمة المرور.',
            'password.min' => 'كلمة المرور يجب أن لا تقل عن 8 أحرف.',
            'password.confirmed' => 'تأكيد كلمة المرور غير متطابق.',
        ])->validate();

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'admin', // جعل المستخدم مسؤول (Admin) افتراضياً كما هو متبع في لوحة التحكم الحالية
        ]);

        $token = $user->createToken('AdminAuthToken')->accessToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }
}
