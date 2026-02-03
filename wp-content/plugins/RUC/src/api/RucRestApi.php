<?php

namespace RUC\api;

use RUC\services\RucSessionValidator;

/**
 * Registra endpoints REST API para RUC
 */
class RucRestApi
{
    protected RucSessionValidator $validator;

    public function __construct(RucSessionValidator $validator)
    {
        $this->validator = $validator;
    }

    public function register(): void
    {
        add_action('rest_api_init', [$this, 'registerRoutes']);
    }

    public function registerRoutes(): void
    {
        register_rest_route('ruc/v1', '/ping', [
            'methods' => 'GET',
            'callback' => [$this, 'handlePing'],
            'permission_callback' => [$this, 'checkUserLoggedIn']
        ]);
    }

    /**
     * Verifica que el usuario esté logueado
     */
    public function checkUserLoggedIn(): bool
    {
        return is_user_logged_in();
    }

    /**
     * Maneja el ping de inactividad
     */
    public function handlePing(\WP_REST_Request $request): \WP_REST_Response
    {
        // Validar sesión RUC
        if (!$this->validator->validarSesionActiva()) {
            
            error_log(sprintf(
                '[RUC] Ping falló - Sesión inválida para usuario %d',
                get_current_user_id()
            ));

            // Invalidar sesión local
            $this->validator->invalidarSesionLocal();

            return new \WP_REST_Response([
                'error' => 'Sesión expirada',
                'redirect' => wp_login_url()
            ], 401);
        }

        return new \WP_REST_Response([
            'status' => 'ok',
            'timestamp' => time(),
            'user_id' => get_current_user_id()
        ], 200);
    }
}