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

            // Verifica se o array possui a posição 1 antes de acessá-la
            if (isset($meutoken[1])) {
                $tokenPayload = base64_decode($meutoken[1]);

                // Verifica se a descompactação do payload foi bem-sucedida
                if ($tokenPayload !== false) {
                    $decoded = JWT::jsonDecode($tokenPayload);
                    return $decoded;
                }
            }
        } catch (\Exception $e) {
            // Tratamento de erro aqui, se necessário
        }

        return null;
    }


}
