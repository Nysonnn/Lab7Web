<?php

namespace App\Libraries;

final class ApiToken
{
    private const LIFETIME = 28800;

    public static function issue(int $userId, string $username): string
    {
        $payload = self::encode(json_encode([
            'sub'      => $userId,
            'username' => $username,
            'iat'      => time(),
            'exp'      => time() + self::LIFETIME,
        ], JSON_THROW_ON_ERROR));

        $signature = self::encode(hash_hmac('sha256', $payload, self::secret(), true));

        return $payload . '.' . $signature;
    }

    public static function validate(string $token): ?array
    {
        $parts = explode('.', $token);
        if (count($parts) !== 2) {
            return null;
        }

        [$payload, $signature] = $parts;
        $expected = self::encode(hash_hmac('sha256', $payload, self::secret(), true));

        if (! hash_equals($expected, $signature)) {
            return null;
        }

        $decoded = self::decode($payload);
        if ($decoded === false) {
            return null;
        }

        $claims = json_decode($decoded, true);
        if (! is_array($claims) || ! isset($claims['sub'], $claims['exp']) || (int) $claims['exp'] < time()) {
            return null;
        }

        return $claims;
    }

    private static function secret(): string
    {
        return (string) (getenv('API_TOKEN_SECRET') ?: 'lab11-web-development-token-secret');
    }

    private static function encode(string $value): string
    {
        return rtrim(strtr(base64_encode($value), '+/', '-_'), '=');
    }

    private static function decode(string $value): string|false
    {
        $padding = strlen($value) % 4;
        if ($padding !== 0) {
            $value .= str_repeat('=', 4 - $padding);
        }

        return base64_decode(strtr($value, '-_', '+/'), true);
    }
}
