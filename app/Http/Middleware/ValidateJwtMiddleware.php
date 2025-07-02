<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\SignatureInvalidException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ValidateJwtMiddleware
{
    protected string $algorithm;

    /**
     * @var string $secret
     */
    protected string $secret;

    public function __construct()
    {
        $this->algorithm = config('jwt.alg', 'HS256');
        $this->secret = config('jwt.secret', config('jwt.secret'));
    }

    /**
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $header = $request->header('Authorization', '');

        if (!preg_match('/^Bearer\s+(.+)$/i', $header, $matches)) {
            return $this->unauthorized('Token not provided');
        }

        try {
            $token = $matches[1];
            $payload = JWT::decode($token, new Key($this->secret, $this->algorithm));
        } catch (ExpiredException $e) {
            return $this->unauthorized('The token expired.');  // ← ваше сообщение
        } catch (Exception $e) {
            return $this->unauthorized('Invalid token');
        }

        $request->attributes->set('jwt_payload', (array)$payload);

        return $next($request);
    }

    /**
     * @param string $message
     * @return JsonResponse
     */
    protected function unauthorized(string $message): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], Response::HTTP_UNAUTHORIZED);
    }
}
