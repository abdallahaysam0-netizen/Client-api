<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class Login
{
    public function execute(array $data)
    {
        Validator::make($data, [
            'email' => 'required|email',
            'password' => 'required',
        ])->validate();

        $user = User::where('email', $data['email'])->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => ['عذراً، هذا البريد الإلكتروني غير مسجل لدينا.'],
            ]);
        }

        if (!Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'password' => ['كلمة المرور التي أدخلتها غير صحيحة.'],
            ]);
        }

        if ($user->role !== 'admin') {
            throw ValidationException::withMessages([
                'email' => ['عذراً، هذا الحساب ليس لديه صلاحيات المسؤول.'],
            ]);
        }

        $token = $user->createToken('AdminAuthToken')->accessToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }
}
