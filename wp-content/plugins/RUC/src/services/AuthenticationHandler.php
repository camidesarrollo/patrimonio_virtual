<?php

namespace RUC\services;

use RUC\helpers\TokenVerifier;
use WP_User;
use Exception;
use Throwable;

class AuthenticationHandler
{
    protected TokenVerifier $tokenVerifier;
    protected $userManager;

    public function __construct($tokenVerifier, $userManager)
    {
        $this->tokenVerifier = $tokenVerifier;  // ✅ Usar el parámetro recibido
        $this->userManager   = $userManager;    // ✅ Usar el parámetro recibido

        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }
    public function procesarAutenticacion(array $variables)
    {
        $token = $variables['retorno'] ?? null;

        if (empty($token)) {
            error_log('❌ Token vacío en procesarAutenticacion');
            wp_safe_redirect(home_url());
            exit;
        }

        $usuarioApi = $this->tokenVerifier->extraerUsuario($token);

        if (!$usuarioApi || empty($usuarioApi['usuario'])) {
            error_log('❌ Token inválido o respuesta API vacía');
            wp_safe_redirect(home_url());
            exit;
        }

        $user = $this->userManager->procesarUsuario($usuarioApi);

        if (!$user instanceof WP_User || !$user->ID) {
            error_log('❌ No se pudo obtener WP_User válido');
            wp_safe_redirect(home_url());
            exit;
        }

        // Guardar datos mínimos de sesión ANTES del login
        $_SESSION['codigo_usuario'] = $usuarioApi['usuario']['CodigoUsuario'] ?? null;
        $_SESSION['tipo_persona']   = get_user_meta($user->ID, 'tipo_persona', true);

        return $this->iniciarSesionUsuario($user, $usuarioApi);
    }

    private function iniciarSesionUsuario(WP_User $user, array $usuarioApi)
    {
        try {
            // ==============================
            // Validaciones duras
            // ==============================
            if (!$user->ID) {
                throw new Exception('Usuario WordPress inválido');
            }

            if (empty($usuarioApi['token']) || empty($usuarioApi['tipo_usuario'])) {
                throw new Exception('Datos API incompletos');
            }
            // Asegurar sesión PHP 
            if (session_status() !== PHP_SESSION_ACTIVE) {
                session_start();
            }
            // Cerrar otro usuario si existe 
            $current_user = wp_get_current_user();

            //SI el usuario ya esta logeado 
            if ($current_user->ID && $current_user->ID == $user->ID) {
                    wp_safe_redirect(site_url() . "/mi-perfil");
                exit;
            }else if ($current_user->ID && $current_user->ID !== $user->ID) {
                wp_logout();
                wp_clear_auth_cookie();
            }


            // Regenerar sesión PHP (ANTI FIXATION)
            session_regenerate_id(true);

            // ==============================
            // LOGIN PROGRAMÁTICO REAL (SEGURO)
            // ==============================
            wp_set_current_user($user->ID);
            wp_set_auth_cookie(
                $user->ID,
                true,           // remember
                is_ssl()         // secure cookie
            );

            do_action('wp_login', $user->user_login, $user);

            // ==============================
            // Verificación dura
            // ==============================
            if (!is_user_logged_in()) {
                throw new Exception('WordPress no logró iniciar sesión');
            }

            // ==============================
            // Guardar sesión API
            // ==============================
            $_SESSION['central_ruc_token'] = $usuarioApi['token'];
            $_SESSION['central_ruc_tipo']  = $usuarioApi['tipo_usuario'];

            error_log('✅ LOGIN OK | WP_USER_ID: ' . $user->ID);

            wp_safe_redirect(site_url() . "/mi-perfil");
            exit;
        } catch (Throwable $e) {
            error_log('❌ Error login WP: ' . $e->getMessage());
            wp_safe_redirect(home_url());
            exit;
        }
    }
}
