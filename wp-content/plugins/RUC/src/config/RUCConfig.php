<?php

namespace RUC\config;

/**
 * Configuración centralizada para el sistema RUC (WordPress).
 */
class RUCConfig
{
    /* =========================
     * Valores fallback
     * ========================= */

    private static string $claveSecreta = 'mi_clave_secreta_123';
    private static string $rucBaseUrl   = 'https://pbrwebqa-08.biblioredes.gob.cl';

    private static string $portalLocal      = '02ppvlocal';
    private static string $portalProduccion = '04ppv';

    private static string $rolPorDefecto = 'usuario_autenticado';
    private static int    $duracionToken = 3600;

    /* =========================
     * Configuración por entorno
     * ========================= */

    private static array $configuracionPorEntorno = [
        'local' => [
            'clave_secreta' => 'mi_clave_secreta_123',
            'base_url'      => 'https://pbrwebqa-08.biblioredes.gob.cl',
            'portal'        => '02ppvlocal',
        ],
        'development' => [
            'clave_secreta' => 'mi_clave_secreta_123',
            'base_url'      => 'https://pbrwebqa-08.biblioredes.gob.cl',
            'portal'        => '04ppv',
        ],
        'qa' => [
            'clave_secreta' => 'mi_clave_secreta_123',
            'base_url'      => 'https://pbrwebqa-08.biblioredes.gob.cl',
            'portal'        => '04ppvqa',
        ],
        'production' => [
            'clave_secreta' => 'mi_clave_secreta_123',
            'base_url'      => 'https://pbrwebqa-08.biblioredes.gob.cl',
            'portal'        => '04ppv',
        ],
    ];

    /* =========================
     * Entorno
     * ========================= */

    public static function detectarEntorno(): string
    {

        $host = $_SERVER['HTTP_HOST'] ?? '';

        if (str_contains($host, 'localhost') || str_contains($host, '.test')) {
            return 'local';
        }

        if (str_contains($host, 'qa')) {
            return 'qa';
        }

        return 'production';
    }

    /* =========================
     * Getters públicos
     * ========================= */

    public static function getClaveSecreta(): string
    {
        // Prioridad 2: variable de entorno
        if ($env = getenv('RUC_SECRET_KEY')) {
            return $env;
        }

        $entorno = self::detectarEntorno();

        return self::$configuracionPorEntorno[$entorno]['clave_secreta']
            ?? self::$claveSecreta;
    }

    private static function getBaseUrl(): string
    {
        $entorno = self::detectarEntorno();

        return self::$configuracionPorEntorno[$entorno]['base_url']
            ?? self::$rucBaseUrl;
    }

    public static function getRucBaseUrlCentral(): string
    {
        return rtrim(self::getBaseUrl(), '/') . '/centralruc';
    }

    public static function getRucBaseUrlValidar(): string
    {
        return rtrim(self::getBaseUrl(), '/') . '/validate';
    }

    public static function getPortal(): string
    {
        $entorno = self::detectarEntorno();

        return self::$configuracionPorEntorno[$entorno]['portal']
            ?? ($entorno === 'local'
                ? self::$portalLocal
                : self::$portalProduccion);
    }

    public static function getRolPorDefecto(): string
    {
        return self::$rolPorDefecto;
    }

    public static function getDuracionToken(): int
    {
        return self::$duracionToken;
    }

    public static function isDebugMode(): bool
    {
        return defined('WP_DEBUG') && WP_DEBUG === true;
    }

    /* =========================
     * Utilidades
     * ========================= */

    public static function toArray(): array
    {
        return [
            'entorno'          => self::detectarEntorno(),
            'clave_secreta'    => substr(self::getClaveSecreta(), 0, 10) . '...',
            'ruc_central'      => self::getRucBaseUrlCentral(),
            'ruc_validar'      => self::getRucBaseUrlValidar(),
            'portal'           => self::getPortal(),
            'rol_por_defecto'  => self::getRolPorDefecto(),
            'duracion_token'   => self::getDuracionToken(),
            'debug'            => self::isDebugMode(),
        ];
    }

    public static function validar(): array
    {
        $errores = [];

        if (strlen(self::getClaveSecreta()) < 16) {
            $errores[] = 'La clave secreta debe tener al menos 16 caracteres.';
        }

        if (!filter_var(self::getRucBaseUrlCentral(), FILTER_VALIDATE_URL)) {
            $errores[] = 'La URL del servicio RUC no es válida.';
        }

        if (empty(self::getPortal())) {
            $errores[] = 'El portal no puede estar vacío.';
        }

        return [
            'valid'  => empty($errores),
            'errors' => $errores,
        ];
    }

    /* =========================
     * Métodos para testing
     * ========================= */

    public static function setClaveSecreta(string $clave): void
    {
        self::$claveSecreta = $clave;
    }

    public static function setConfiguracionEntorno(string $entorno, array $config): void
    {
        self::$configuracionPorEntorno[$entorno] = $config;
    }

    public static function getConfiguracionEntorno(string $entorno): ?array
    {
        return self::$configuracionPorEntorno[$entorno] ?? null;
    }
}
