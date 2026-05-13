<?php

namespace App\Http\Controllers;

use App\Services\Login;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class LoginController extends Controller
{
    protected $loginService;

    public function __construct(Login $loginService)
    {
        $this->loginService = $loginService;
    }

    public function login(Request $request): JsonResponse
    {
        return response()->json($this->loginService->execute($request->all()));
    }
}
