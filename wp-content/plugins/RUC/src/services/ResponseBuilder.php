<?php

namespace RUC\services;

use RUC\services\CookieManager;
use RUC\services\UrlValidator;
use RUC\helpers\ResponseHelper;

/**
 * Servicio para construcción de respuestas HTTP (WordPress).
 */
class ResponseBuilder
{
    /**
     * @var CookieManager
     */
    protected $cookieManager;

    /**
     * @var UrlValidator
     */
    protected $urlValidator;

    /**
     * Constructor.
     */
    public function __construct(
        CookieManager $cookieManager,
        UrlValidator $urlValidator
    ) {
        $this->cookieManager = $cookieManager;
        $this->urlValidator  = $urlValidator;
    }

    /* =========================
     * RESPUESTAS SIMPLES
     * ========================= */

    /**
     * Respuesta JSON de error.
     */
    public function jsonError(string $message, int $code = 400)
    {
        status_header($code);
        wp_send_json([
            'status'  => 'error',
            'message' => $message,
        ]);
        exit;
    }

    /**
     * Redirección al home.
     */
    public function redirectToHome()
    {
        wp_safe_redirect(home_url('/'));
        exit;
    }

    /**
     * Redirección al login.
     */
    public function redirectToLogin(array $query = [])
    {
        $loginUrl = wp_login_url();

        if (!empty($query)) {
            $loginUrl = add_query_arg($query, $loginUrl);
        }

        wp_safe_redirect($loginUrl);
        exit;
    }

    /* =========================
     * REDIRECCIÓN CON VALIDACIÓN
     * ========================= */

    /**
     * Redirige con cookies y validaciones.
     */
    public function redirigirConCookies($mensaje = 'Redirigiendo...')
    {
        // 1. Validar usuario
        if (!is_user_logged_in()) {
            error_log('[RUC] Usuario no autenticado');
            $this->redirectToLogin();
        }

        $currentUser = wp_get_current_user();

        // 2. Validar token externo
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $token = $_SESSION['central_ruc.token'] ?? null;

        if ($token === null) {
            error_log('[RUC] Token externo inexistente');
            $this->redirectToLogin();
        }

        // 3. Determinar URL destino
        $targetUrl = $this->determinarUrlDestino($currentUser);

        error_log(sprintf(
            '[RUC] Redirigiendo usuario %d a %s',
            $currentUser->ID,
            $targetUrl
        ));

        // 4. Construir respuesta HTML
        $html = ResponseHelper::buildLoadingModalHtml(
            $targetUrl,
            $mensaje,
            1500
        );

        // Headers no cache
        nocache_headers();

        echo $html;
        exit;
    }

    /**
     * Determina URL destino con validación.
     */
    protected function determinarUrlDestino($user): string
    {
        $urlRedirect = $_GET['urlRedirect'] ?? $_POST['urlRedirect'] ?? null;
        $baseUrl     = home_url();

        if (!empty($urlRedirect) && $this->urlValidator->esUrlSegura($urlRedirect, $baseUrl)) {
            return esc_url_raw($urlRedirect);
        }

        if (!empty($urlRedirect)) {
            error_log('[RUC] URL rechazada: ' . $urlRedirect);
        }

        return get_author_posts_url($user->ID);
    }

    /* =========================
     * LOGOUT
     * ========================= */

    /**
     * Respuesta de logout.
     */
    public function createLogoutResponse(string $html)
    {
        nocache_headers();
        $this->cookieManager->eliminarCookiesSesion();
        echo $html;
        exit;
    }

    /* =========================
     * HTML HELPERS
     * ========================= */

    public function buildLoginRedirectHtml(string $url): string
    {
        return ResponseHelper::buildRedirectHtml($url, 'Iniciando sesión...');
    }

    public function buildLogoutHtml(string $url): string
    {
        return ResponseHelper::buildLogoutHtml($url);
    }
}
