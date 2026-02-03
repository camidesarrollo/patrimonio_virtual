<?php

namespace RUC\services;

use RUC\config\RUCConfig;
use RUC\services\SessionManager;
use RUC\helpers\TokenVerifier;

/**
 * Servicio para validar sesiones contra Central RUC (WordPress).
 */
class RucSessionValidator
{
    protected SessionManager $sessionManager;
    protected TokenVerifier $tokenVerifier;

    const VALIDATION_TTL = 300; // 5 minutos
    const TIMEOUT = 3; // 3 segundos
    const EXCLUDED_USER = 'Administrador Contenidos Locales';

    /**
     * Constructor con dependencias opcionales
     * Si no se proporcionan, se crearán instancias por defecto
     */
    public function __construct(
        ?SessionManager $sessionManager = null,
        ?TokenVerifier $tokenVerifier = null
    ) {
        // Si no se proporcionan dependencias, crearlas
        if ($sessionManager === null) {
            $cookieManager = new \RUC\services\CookieManager();
            $urlValidator = new \RUC\services\UrlValidator();
            $responseBuilder = new \RUC\services\ResponseBuilder($cookieManager, $urlValidator);
            $sessionManager = new SessionManager($responseBuilder);
        }
        
        $this->sessionManager = $sessionManager;
        $this->tokenVerifier = $tokenVerifier ?? new TokenVerifier();
    }

    /**
     * Valida si la sesión del usuario sigue activa en RUC.
     */
    public function validarSesionActiva(): bool
    {
        // 1. Verificar que el usuario esté logueado
        if (!is_user_logged_in()) {
            error_log('[RUC] Usuario no logueado');
            return false;
        }

        // 2. Verificar si debe validarse (según TTL y usuario)
        if (!$this->debeValidar()) {
            error_log('[RUC] No requiere validación (TTL o usuario excluido)');
            return true;
        }

        // 3. Obtener token de sesión
        $token = $this->obtenerToken();

        if (!$token) {
            error_log(sprintf(
                '[RUC] Token SSO no encontrado para usuario %d',
                get_current_user_id()
            ));
            return false;
        }

        // 4. Validar token contra RUC
        try {
            $valida = $this->isSessionValid($token);

            if ($valida) {
                $this->actualizarUltimaValidacion();
                error_log(sprintf(
                    '[RUC] Sesión válida para usuario %d',
                    get_current_user_id()
                ));
            } else {
                error_log(sprintf(
                    '[RUC] Sesión inválida o expirada para usuario %d',
                    get_current_user_id()
                ));
            }

            return $valida;

        } catch (\Throwable $e) {
            error_log(sprintf(
                '[RUC] Error validando sesión para usuario %d: %s',
                get_current_user_id(),
                $e->getMessage()
            ));
            
            // Fail-open: en caso de error de red/servidor, permitir acceso
            // Esto evita bloquear usuarios por problemas temporales
            return true;
        }
    }

    /**
     * Valida el token contra el endpoint RUC.
     */
    public function isSessionValid(string $token): bool
    {
        if (empty($token)) {
            error_log('[RUC] Token vacío');
            return false;
        }

        // Cifrar el token para envío
        try {
            $tokenCifrado = $this->tokenVerifier->CrearCifrado($token);
        } catch (\Throwable $e) {
            error_log('[RUC] Error cifrando token: ' . $e->getMessage());
            return false;
        }

        if (!$tokenCifrado) {
            error_log('[RUC] Token cifrado vacío');
            return false;
        }

        // Obtener URL de validación desde configuración
        try {
            $validationUrl = RUCConfig::getRucBaseUrlValidar();
        } catch (\Throwable $e) {
            error_log('[RUC] Error obteniendo URL de validación: ' . $e->getMessage());
            return false;
        }

        if (!$validationUrl) {
            error_log('[RUC] URL de validación no configurada');
            return false;
        }

        // Realizar petición HTTP
        $response = wp_remote_get(
            $validationUrl,
            [
                'timeout' => self::TIMEOUT,
                'headers' => [
                    'Authorization' => 'Bearer ' . $tokenCifrado,
                    'Accept'        => 'application/json',
                    'User-Agent'    => 'WordPress-RUC-Plugin/' . RUC_VERSION,
                ],
                'sslverify' => true, // Verificar SSL en producción
            ]
        );

        // Verificar errores HTTP
        if (is_wp_error($response)) {
            error_log(sprintf(
                '[RUC] Error HTTP validando token: %s',
                $response->get_error_message()
            ));
            return false;
        }

        // Verificar código de respuesta
        $status = wp_remote_retrieve_response_code($response);

        if ($status !== 200) {
            error_log(sprintf(
                '[RUC] Respuesta inválida del servidor RUC: HTTP %d',
                $status
            ));
            return false;
        }

        // Decodificar respuesta JSON
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        // Verificar que la decodificación fue exitosa
        if (json_last_error() !== JSON_ERROR_NONE) {
            error_log(sprintf(
                '[RUC] Error decodificando JSON: %s',
                json_last_error_msg()
            ));
            return false;
        }

        // Verificar que la sesión esté activa
        $isActive = !empty($data['active']) && $data['active'] === true;

        if (!$isActive) {
            error_log('[RUC] Servidor RUC indica sesión no activa');
        }

        return $isActive;
    }

    /**
     * Determina si debe validarse según TTL y usuario.
     */
    protected function debeValidar(): bool
    {
        // 1. Excluir usuario específico
        $user = wp_get_current_user();
        if ($user && $user->user_login === self::EXCLUDED_USER) {
            return false;
        }

        // 2. Verificar que la sesión PHP esté activa
        if (session_status() !== PHP_SESSION_ACTIVE) {
            error_log('[RUC] Sesión PHP no activa');
            return false;
        }

        return true;

        // // 3. Verificar TTL de última validación
        // $ultimaValidacion = $_SESSION['ruc_last_validation'] ?? null;

        // // Si nunca se ha validado, debe validarse
        // if (empty($ultimaValidacion)) {
        //     return true;
        // }

        // // Si pasó el TTL, debe validarse
        // $tiempoTranscurrido = time() - (int)$ultimaValidacion;
        
        // return $tiempoTranscurrido > self::VALIDATION_TTL;
    }

    /**
     * Obtiene el token SSO desde sesión.
     */
    protected function obtenerToken(): ?string
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            return null;
        }

        return $_SESSION['central_ruc_token'] ?? null;
    }

    /**
     * Actualiza timestamp de última validación.
     */
    protected function actualizarUltimaValidacion(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            $_SESSION['ruc_last_validation'] = time();
        }
    }

    /**
     * Invalida sesión local.
     */
    public function invalidarSesionLocal(): void
    {
        // Cerrar sesión de WordPress
        wp_logout();
        
        // Limpiar sesión PHP si existe
        if (session_status() === PHP_SESSION_ACTIVE) {
            $_SESSION = [];
            
            // Destruir la cookie de sesión
            if (ini_get("session.use_cookies")) {
                $params = session_get_cookie_params();
                setcookie(
                    session_name(),
                    '',
                    time() - 42000,
                    $params["path"],
                    $params["domain"],
                    $params["secure"],
                    $params["httponly"]
                );
            }
            
            session_destroy();
        }
        
        error_log(sprintf(
            '[RUC] Sesión local invalidada para usuario %d',
            get_current_user_id()
        ));
    }
}