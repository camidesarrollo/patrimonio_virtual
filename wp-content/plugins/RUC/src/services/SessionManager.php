<?php

namespace RUC\services;

use RUC\services\ResponseBuilder;

/**
 * Servicio para gestión de sesiones RUC en WordPress.
 */
class SessionManager
{
    protected ResponseBuilder $responseBuilder;

    public function __construct(ResponseBuilder $responseBuilder)
    {
        $this->responseBuilder = $responseBuilder;
    }

    /* =========================
     * VERIFICAR SESIÓN
     * ========================= */

    public function verificarSesion(): void
    {
        if (!is_user_logged_in()) {
            wp_send_json([
                'authenticated'   => false,
                'session_active'  => false,
            ]);
        }

        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $user = wp_get_current_user();

        wp_send_json([
            'authenticated'   => true,
            'session_active'  => true,
            'user_id'         => $user->ID,
            'username'        => $user->user_login,
            'session_id'      => substr(session_id(), 0, 10) . '...',
        ]);
    }

    /* =========================
     * CERRAR SESIÓN
     * ========================= */

    public function cerrarSesion(array $variables = []): void
    {
        try {
            if (!is_user_logged_in()) {
                $this->responseBuilder->redirectToHome();
            }

            if (session_status() !== PHP_SESSION_ACTIVE) {
                session_start();
            }

            $user = wp_get_current_user();
            $userId = $user->ID;

            error_log('[SessionManager] Cerrando sesión usuario ' . $userId);


            // Destruir sesión PHP
            $_SESSION = [];

            if (ini_get('session.use_cookies')) {
                $params = session_get_cookie_params();
                setcookie(
                    session_name(),
                    '',
                    time() - 42000,
                    $params['path'],
                    $params['domain'],
                    $params['secure'],
                    $params['httponly']
                );
            }

            session_destroy();

            // Logout WP
            wp_logout();

            // Limpiar cache WP
            wp_cache_flush();

            // Respuesta HTML
            $url = home_url('/');
            $html = $this->responseBuilder->buildLogoutHtml($url);

            $this->responseBuilder->createLogoutResponse($html);
        } catch (\Throwable $e) {
            error_log('[SessionManager] Error cerrarSesion: ' . $e->getMessage());
            $this->responseBuilder->redirectToHome();
        }
    }
}
