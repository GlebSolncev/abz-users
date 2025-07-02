<?php

namespace App\Services;

use Firebase\JWT\JWT;

class TokenService
{
    /**
     * @var string
     */
    private string $secret;

    /**
     * @var string
     */
    private string $alg;

    /**
     * @var int
     */
    private int $ttl;

    /**
     * @var string
     */
    private string $issuer;

    public function __construct()
    {
        $cfg = config('jwt');
        $this->secret = $cfg['secret'];
        $this->alg = $cfg['alg'];
        $this->ttl = $cfg['ttl'];
        $this->issuer = $cfg['issuer'];
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        $now = time();
        $exp = $now + $this->ttl;
        return JWT::encode([
            'iss' => $this->issuer,
            'iat' => $now,
            'exp' => $exp,
        ], $this->secret, $this->alg);
    }
}