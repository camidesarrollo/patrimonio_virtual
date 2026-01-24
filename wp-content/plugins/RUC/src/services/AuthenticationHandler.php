<?php

namespace RUC\services;

use RUC\helpers\TokenVerifier;
use WP_User;
use Exception;
use Throwable;

class AuthenticationHandler {

    protected $tokenVerifier;
    protected $userManager;

    public function __construct($tokenVerifier, $userManager) {
        $this->tokenVerifier   = new TokenVerifier();
        $this->userManager   = $userManager;

        if (!session_id()) {
            session_start();
        }
    }

    public function procesarAutenticacion($variables) {

        $token = $variables['retorno'] ?? '';

        if (empty($token)) {
            error_log('Token vacío en procesarAutenticacion');
            wp_redirect(home_url());
            exit;
        }

        $usuarioApi = $this->tokenVerifier->extraerUsuario($token);

        if (!$usuarioApi) {
            error_log('Token inválido');
            wp_redirect(home_url());
            exit;
        }

        $user = $this->userManager->procesarUsuario($usuarioApi);

        if (!$user instanceof WP_User) {
            wp_redirect(home_url());
            exit;
        }

        $_SESSION['codigo_usuario'] = $usuarioApi['usuario']['CodigoUsuario'] ?? null;
        $_SESSION['tipo_persona']   = get_user_meta($user->ID, 'tipo_persona', true);

        return $this->iniciarSesionUsuario($user, $usuarioApi);
    }

    private function iniciarSesionUsuario(WP_User $user, array $usuarioApi)
    {
        try {

            if (!$user || !$user->ID) {
                throw new Exception('Usuario WP inválido');
            }

            // Asegurar sesión PHP
            if (session_status() !== PHP_SESSION_ACTIVE) {
                session_start();
            }

            // Cerrar otro usuario si existe
            $current_user = wp_get_current_user();
            if ($current_user->ID && $current_user->ID !== $user->ID) {
                wp_logout();
                wp_clear_auth_cookie();
            }

            // Login WordPress REAL
            wp_clear_auth_cookie();
            wp_set_current_user($user->ID);
            wp_set_auth_cookie($user->ID, true, is_ssl());

            do_action('wp_login', $user->user_login, $user);

            // Verificación dura (MUY IMPORTANTE)
            if (!is_user_logged_in()) {
                throw new Exception('WordPress no logró iniciar sesión');
            }

            // Validar datos API
            if (empty($usuarioApi['token']) || empty($usuarioApi['tipo_usuario'])) {
                throw new Exception('Datos API incompletos');
            }

            $_SESSION['central_ruc_token'] = $usuarioApi['token'];
            $_SESSION['central_ruc_tipo']  = $usuarioApi['tipo_usuario'];

            // DEBUG opcional
            error_log('LOGIN OK WP USER ID: ' . $user->ID);

            wp_safe_redirect(home_url());
            exit;

        } catch (Throwable $e) {
            error_log('❌ Error login WP: ' . $e->getMessage());
            wp_safe_redirect(home_url());
            exit;
        }
    }

}
