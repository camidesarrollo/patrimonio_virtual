<?php

namespace RUC\helpers;

/**
 * Helper para construcción y configuración de respuestas HTTP en WordPress.
 */
class ResponseHelper
{
    /**
     * Aplica headers de no-cache.
     */
    public static function aplicarHeadersNoCache(): void
    {
        // Helper nativo de WP
        if (function_exists('nocache_headers')) {
            nocache_headers();
        }

        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Pragma: no-cache');
        header('Expires: Thu, 01 Jan 1970 00:00:00 GMT');
    }

    /**
     * Construye HTML de redirección genérico.
     */
    public static function buildRedirectHtml(
        string $url,
        string $mensaje = 'Redirigiendo...'
    ): string {
        $urlEscaped = esc_url($url);
        $mensajeEscaped = esc_html($mensaje);

        return <<<HTML
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta http-equiv="refresh" content="1;url={$urlEscaped}">
<title>{$mensajeEscaped}</title>
</head>
<body>
<p>{$mensajeEscaped}</p>
<script>
setTimeout(() => location.href = '{$urlEscaped}', 500);
</script>
</body>
</html>
HTML;
    }

    /**
     * HTML específico para logout con limpieza de storage.
     */
    public static function buildLogoutHtml(string $url): string
    {
        $urlEscaped = esc_url($url);

        return <<<HTML
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta http-equiv="refresh" content="1;url={$urlEscaped}">
<title>Cerrando sesión</title>
</head>
<body>
<p>Cerrando sesión...</p>
<script>
localStorage.clear();
sessionStorage.clear();
setTimeout(() => location.href = '{$urlEscaped}', 500);
</script>
</body>
</html>
HTML;
    }

    /**
     * Modal de carga simple (sin Bootstrap externo).
     */
    public static function buildLoadingModalHtml(
        string $url,
        string $mensaje = 'Cargando',
        int $delay = 1000
    ): string {
        $urlEscaped = esc_url($url);
        $mensajeEscaped = esc_html($mensaje);

        return <<<HTML
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>{$mensajeEscaped}</title>
<style>
body {
    margin: 0;
    font-family: system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
    background: rgba(0,0,0,.5);
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
}
.loading {
    background: #fff;
    border-radius: 8px;
    padding: 40px;
    text-align: center;
    box-shadow: 0 4px 10px rgba(0,0,0,.15);
}
.spinner {
    width: 48px;
    height: 48px;
    border: 4px solid #e9ecef;
    border-top-color: #0d6efd;
    border-radius: 50%;
    animation: spin .8s linear infinite;
    margin: 0 auto 20px;
}
@keyframes spin {
    to { transform: rotate(360deg); }
}
</style>
</head>
<body>
<div class="loading">
    <div class="spinner"></div>
    <h4>{$mensajeEscaped}</h4>
    <p>Por favor espera…</p>
</div>

<script>
setTimeout(function () {
    window.location.href = '{$urlEscaped}';
}, {$delay});
</script>
</body>
</html>
HTML;
    }

    /**
     * Modal de carga para logout (con limpieza de storage).
     */
    public static function buildLoadingLogoutModalHtml(string $url): string
    {
        $urlEscaped = esc_url($url);

        return <<<HTML
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Cerrando sesión</title>
<style>
body {
    margin: 0;
    font-family: system-ui, sans-serif;
    background: rgba(0,0,0,.6);
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
}
.box {
    background: #fff;
    border-radius: 8px;
    padding: 40px;
    text-align: center;
}
.spinner {
    width: 48px;
    height: 48px;
    border: 4px solid #ddd;
    border-top-color: #0d6efd;
    border-radius: 50%;
    animation: spin .8s linear infinite;
    margin: 0 auto 20px;
}
@keyframes spin {
    to { transform: rotate(360deg); }
}
</style>
</head>
<body>
<div class="box">
    <div class="spinner"></div>
    <h4>Cerrando sesión</h4>
    <p>Por favor espera…</p>
</div>

<script>
localStorage.clear();
sessionStorage.clear();
setTimeout(function () {
    window.location.href = '{$urlEscaped}';
}, 1000);
</script>
</body>
</html>
HTML;
    }
}
