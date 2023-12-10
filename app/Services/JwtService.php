<?php
namespace App\Services;

use Firebase\JWT\JWT;

class JwtService
{
    protected $key;

    public function __construct()
    {
        $this->key = env('JWT_SECRET');
    }

    public function generateToken($data, $expiryMinutes = 60)
    {
        $issuedAt = time();
        $expiration = $issuedAt + ($expiryMinutes * 60);

        $token = JWT::encode([
            'iat' => $issuedAt,
            'exp' => $expiration,
            'data' => $data,
        ], $this->key, 'HS256');

        return $token;
    }

    public function verifyToken($token)
    {
        try {
            $decoded = JWT::decode($token, $this->key, ['HS256']);
            return json_decode(json_encode($decoded->data), true);
        } catch (\Exception $e) {
            return null;
        }
    }


}
