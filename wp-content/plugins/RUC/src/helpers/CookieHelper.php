<?php

namespace RUC\helpers;

class CookieHelper
{
    public static function getCookieDomain(bool $base = false): ?string
    {
        $host = $_SERVER['HTTP_HOST'] ?? '';

        if (strpos($host, 'biblioredes.gob.cl') !== false) {
            return $base ? '.biblioredes.gob.cl' : 'biblioredes.gob.cl';
        }

        return null;
    }

    public static function createSecureCookie(
        string $name,
        string $value,
        int $expire = 0
    ): void {
        $secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');

        setcookie($name, $value, [
            'expires'  => $expire,
            'path'     => '/',
            'domain'   => self::getCookieDomain(),
            'secure'   => $secure,
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
    }

    public static function deleteCookie(string $name, bool $baseDomain = false): void
    {
        setcookie($name, '', [
            'expires' => time() - 3600,
            'path'    => '/',
            'domain'  => self::getCookieDomain($baseDomain),
        ]);
    }
}
