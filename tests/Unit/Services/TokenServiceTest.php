<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\TokenService;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class TokenServiceTest extends TestCase
{
    public function testGetTokenGeneratesValidJwt(): void
    {
        config()->set('jwt.secret', 'test-secret-key');
        config()->set('jwt.alg',    'HS256');
        config()->set('jwt.ttl',    120);
        config()->set('jwt.issuer', 'http://example.com');

        $service = new TokenService();

        $token = $service->getToken();
        $this->assertIsString($token, 'Token should be a string');
        $this->assertCount(3, explode('.', $token), 'JWT must consist of three parts');

        $decoded = JWT::decode($token, new Key('test-secret-key', 'HS256'));
        $payload = (array) $decoded;

        $this->assertArrayHasKey('iss', $payload);
        $this->assertEquals('http://example.com', $payload['iss']);

        $this->assertArrayHasKey('iat', $payload);
        $this->assertIsInt($payload['iat']);

        $this->assertArrayHasKey('exp', $payload);
        $this->assertIsInt($payload['exp']);

        $this->assertEquals(
            120,
            $payload['exp'] - $payload['iat'],
            'exp minus iat must equal ttl'
        );
    }
}
