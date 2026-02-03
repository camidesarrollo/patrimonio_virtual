<?php

namespace RUC\controllers;

use RUC\config\RUCConfig;
use RUC\services\SessionManager;
use RUC\services\AuthenticationHandler;
use RUC\services\ResponseBuilder;
use RUC\services\UrlValidator;
use RUC\helpers\TokenVerifier;
use RUC\services\UserManager;

class CentralRucController
{
    protected $sessionManager;
    protected $authHandler;
    protected $responseBuilder;
    protected $urlValidator;
    protected $tokenVerifier;
    protected $userManager;

    public function __construct()
    {
        $this->urlValidator = new UrlValidator();

        $this->responseBuilder = new ResponseBuilder(
            new \RUC\services\CookieManager(),
            $this->urlValidator
        );

        $this->sessionManager = new SessionManager(
            $this->responseBuilder
        );

        $this->tokenVerifier = new TokenVerifier();
        $this->userManager   = new UserManager();

        $this->authHandler = new AuthenticationHandler(
            $this->tokenVerifier,
            $this->userManager
        );
    }

    /* ======================================================
     * REDIRECCIÓN A CENTRAL RUC
     * ====================================================== */

    public function redirigir($accion)
    {

        $portal = RUCConfig::getPortal();
        $url = $this->tokenVerifier->retornoCifrado($portal, $accion, null);
        
        wp_redirect($url);
        exit;
    }

    /* ======================================================
     * PROCESAR RETORNO RUC
     * ====================================================== */

    public function procesarRUC()
    {
        try {
            $variables = $_GET;

            if (!$this->validarVariables($variables)) {
                error_log('[RUC] Variables inválidas');
                $this->responseBuilder->redirectToHome();
            }


            if ($this->esCierreSesion($variables)) {
                $this->cerrarSesion($variables);
            }

            $this->authHandler->procesarAutenticacion($variables);

        } catch (\Throwable $e) {
            error_log('[RUC] Error procesarRUC: ' . $e->getMessage());
            $this->responseBuilder->redirectToHome();
        }
    }

    /* ======================================================
     * API ACTUALIZAR USUARIO
     * ====================================================== */

    public function apiActualizarRuc()
    {
        $token = $_GET['retorno'] ?? null;

        if (!$token) {
            wp_send_json_error('Parámetro retorno no especificado', 400);
        }

        $usuarioApi = $this->tokenVerifier->extraerUsuario($token);

        if (!$usuarioApi) {
            wp_send_json_error('Token inválido', 401);
        }

        $user = $this->userManager->procesarUsuario($usuarioApi);

        if ($user) {
            
            wp_send_json_success([
                'user_id' => $user->ID
            ]);
        }

        wp_send_json_error('Usuario no registrado', 404);
    }

    /* ======================================================
     * SESIÓN
     * ====================================================== */

    public function verificarSesion()
    {
        $this->sessionManager->verificarSesion();
    }

    public function cerrarSesion($variables = null)
    {
        $this->sessionManager->cerrarSesion($variables);
    }

    public function ping()
    {
        wp_send_json([
            'status' => 'ok',
            'ts' => time(),
        ]);
    }

    /* ======================================================
     * VALIDACIONES
     * ====================================================== */

    private function validarVariables($variables): bool
    {
        return isset($variables['retorno']) || isset($variables['mensaje']);
    }

    private function esCierreSesion($variables): bool
    {
        return isset($variables['mensaje']) &&
               $variables['mensaje'] === 'Cierre de sesión correcto';
    }
}
