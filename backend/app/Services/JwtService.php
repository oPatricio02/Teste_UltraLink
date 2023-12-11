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
            $meutoken = explode('.', $token);


            if (isset($meutoken[1])) {
                $tokenPayload = base64_decode($meutoken[1]);


                if ($tokenPayload !== false) {
                    $decoded = JWT::jsonDecode($tokenPayload);
                    return $decoded;
                }
            }
        } catch (\Exception $e) {

        }

        return null;
    }


}
