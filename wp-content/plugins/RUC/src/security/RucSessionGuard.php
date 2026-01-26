<?php

namespace RUC\security;

use RUC\services\RucSessionValidator;

/**
 * Guardián de sesiones RUC
 * Valida que los usuarios logueados tengan sesiones RUC válidas
 */
class RucSessionGuard
{
    protected RucSessionValidator $validator;

    /**
     * Rutas que NO requieren validación de sesión RUC
     */
    protected array $excludedRoutes = [
        'wp-login.php',
        'wp-admin/admin-ajax.php',
        'centralruc/procesar',
        'centralruc/cerrar-sesion',
        'centralruc/redirigir',
    ];

    public function __construct(RucSessionValidator $validator)
    {
        $this->validator = $validator;
    }

    /**
     * Registra el hook de validación
     */
    public function register(): void
    {
        // Validar sesión antes de cargar cualquier template
        add_action('template_redirect', [$this, 'validateSession'], 1);
    }

    /**
     * Valida la sesión del usuario actual
     */
    public function validateSession(): void
    {
        // Solo validar usuarios logueados
        if (!is_user_logged_in()) {
            return;
        }

        // Excluir rutas que no requieren validación
        if ($this->isExcludedRoute()) {
            return;
        }

        // Validar sesión RUC
        if (!$this->validator->validarSesionActiva()) {
            
            error_log(sprintf(
                '[RUC] Sesión RUC inválida para usuario %d - Cerrando sesión',
                get_current_user_id()
            ));

            // Cerrar sesión local
            $this->validator->invalidarSesionLocal();

            // Redirigir al login con mensaje
            $loginUrl = wp_login_url();
            $redirectUrl = add_query_arg([
                'ruc_expired' => '1',
                'redirect_to' => urlencode($this->getCurrentUrl())
            ], $loginUrl);

            wp_safe_redirect($redirectUrl);
            exit;
        }
    }

    /**
     * Verifica si la ruta actual está excluida de validación
     */
    protected function isExcludedRoute(): bool
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '';

        // Verificar rutas específicas
        foreach ($this->excludedRoutes as $route) {
            if (str_contains($uri, $route)) {
                return true;
            }
        }

        // Excluir panel de administración
        if (is_admin()) {
            return true;
        }

        // Excluir REST API (excepto endpoints RUC)
        if (str_starts_with($uri, '/wp-json') && !str_contains($uri, '/ruc/')) {
            return true;
        }

        // Excluir AJAX requests
        if (defined('DOING_AJAX') && DOING_AJAX) {
            return true;
        }

        // Excluir CRON
        if (defined('DOING_CRON') && DOING_CRON) {
            return true;
        }

        return false;
    }

    /**
     * Obtiene la URL actual
     */
    protected function getCurrentUrl(): string
    {
        $protocol = is_ssl() ? 'https://' : 'http://';
        $host = $_SERVER['HTTP_HOST'] ?? '';
        $uri = $_SERVER['REQUEST_URI'] ?? '';
        
        return $protocol . $host . $uri;
    }
}