<?php

namespace App\Http\Controllers;

use App\Services\Register;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class RegisterController extends Controller
{
    protected $registerService;

    public function __construct(Register $registerService)
    {
        $this->registerService = $registerService;
    }

    /**
     * معالجة طلب تسجيل مستخدم جديد.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        return response()->json($this->registerService->execute($request->all()));
    }
}
