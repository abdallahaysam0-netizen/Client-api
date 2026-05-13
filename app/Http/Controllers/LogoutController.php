<?php

namespace App\Http\Controllers;

use App\Services\Logout;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
    protected $logoutService;

    public function __construct(Logout $logoutService)
    {
        $this->logoutService = $logoutService;
    }

    public function logout(Request $request)
    {
        $this->logoutService->execute();

        return response()->json([
            'message' => 'تم تسجيل الخروج بنجاح'
        ], 200);
    }
}
