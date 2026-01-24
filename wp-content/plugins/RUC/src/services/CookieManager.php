<?php

namespace RUC\services;

use RUC\helpers\CookieHelper;

/**
 * Manejo centralizado de cookies de sesión en WordPress.
 */
class CookieManager
{
    /**
     * Asegura la cookie de sesión.
     *
     * @param string $sessionId
     * @param string|null $sessionName
     */
    public function asegurarCookieSesion(
        string $sessionId,
        ?string $sessionName = null
    ): void {
        $sessionName = $sessionName ?? session_name();

        CookieHelper::createSecureCookie(
            $sessionName,
            $sessionId
        );

        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log(sprintf(
                '[CookieManager] Cookie creada: %s=%s',
                $sessionName,
                substr($sessionId, 0, 10) . '...'
            ));
        }
    }

    /**
     * Elimina todas las cookies de sesión.
     */
    public function eliminarCookiesSesion(): void
    {
        $cookiesToDelete = $this->getCookiesParaEliminar();

        foreach (array_unique($cookiesToDelete) as $cookieName) {
            // Eliminar para host actual
            CookieHelper::deleteCookie($cookieName);

            // Eliminar para dominio base (si aplica)
            CookieHelper::deleteCookie($cookieName, true);

            if (defined('WP_DEBUG') && WP_DEBUG) {
                error_log('[CookieManager] Cookie eliminada: ' . $cookieName);
            }
        }
    }

    /**
     * Obtiene cookies de sesión detectadas.
     */
    protected function getCookiesParaEliminar(): array
    {
        $cookies = [];

        foreach ($_COOKIE as $name => $value) {
            if (str_starts_with($name, 'SESS') || str_starts_with($name, 'SSESS')) {
                $cookies[] = $name;
            }
        }

        // Cookie de sesión PHP
        if (session_name()) {
            $cookies[] = session_name();
        }

        return $cookies;
    }
}
