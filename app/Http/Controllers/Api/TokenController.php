<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\TokenService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class TokenController extends Controller
{
    public function make(TokenService $service): JsonResponse
    {
        return response()->json([
            'success' => true,
            'token'   => $service->getToken(),
        ], Response::HTTP_OK);
    }

}