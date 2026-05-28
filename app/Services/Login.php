<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Gate;
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

        // Remove hardcoded role check to allow both Admin and Manager
        // if ($user->role !== 'admin') { ... }

        // Clear cache to ensure we get fresh permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $token = $user->createToken('AuthToken')->accessToken;

        // Force load roles and permissions to ensure they are available
        $user->load('roles.permissions');

        return [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role, // Legacy role support
                'roles' => $user->getRoleNames()->toArray(),
                'permissions' => $user->getAllPermissions()->pluck('name')->toArray(),
            ],
            'token' => $token,
        ];
    }
}
