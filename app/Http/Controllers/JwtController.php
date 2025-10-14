<?php

namespace App\Http\Controllers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtController
{
    public static function generateToken($data, $expiration = 60)
    {
        $issuedAt = time();
        $expirationTime = $issuedAt + $expiration;

        $token = JWT::encode([   // Hora de expiración del token
            'data' => $data,
        ], env("JWT_SECRET_KEY"), 'HS256');

        return $token;
    }

    public static function decodeToken($token)
    {
        try {
            JWT::$leeway = 60;
            $decoded = JWT::decode($token, new Key(env("JWT_SECRET_KEY"), 'HS256'));
            return $decoded->data;
        } catch (\Exception $e) {
            // Manejar la excepción (token inválido, expirado, etc.)
            return $e->getMessage();
        }
    }
}
