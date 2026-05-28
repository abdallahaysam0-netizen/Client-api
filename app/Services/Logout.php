<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
class Logout
{
    public function execute()
    {
        $user = Auth::user();
        if ($user) {
            $user->token()->revoke();
        }

        return true;
    }
}