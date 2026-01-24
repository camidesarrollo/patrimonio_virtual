<?php

namespace RUC\services;

/**
 * Servicio para validación de URLs de redirección.
 */
class UrlValidator
{
    /**
     * Verifica si una URL es segura para redirección.
     *
     * @param string $url
     * @param string $baseUrl
     *
     * @return bool
     */
    public function esUrlSegura(string $url, string $baseUrl): bool
    {
        // URLs relativas (/algo pero no //)
        if (strpos($url, '/') === 0 && strpos($url, '//') !== 0) {
            return true;
        }

        $urlParts  = parse_url($url);
        $baseParts = parse_url($baseUrl);

        if (!isset($urlParts['host']) || !isset($baseParts['host'])) {
            return false;
        }

        // Mismo host
        if ($urlParts['host'] === $baseParts['host']) {
            return true;
        }

        // Subdominios permitidos
        $dominioBase = 'biblioredes.gob.cl';

        if (preg_match(
            '/^[a-z0-9-]+\.' . preg_quote($dominioBase, '/') . '$/i',
            $urlParts['host']
        )) {
            return true;
        }

        // Lista blanca explícita
        $dominiosPermitidos = [
            'pbrwebqa-01.biblioredes.gob.cl',
            'pbrwebqa-08.biblioredes.gob.cl',
        ];

        return in_array($urlParts['host'], $dominiosPermitidos, true);
    }
}
