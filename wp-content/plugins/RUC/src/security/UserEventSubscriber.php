<?php

namespace RUC\security;

/**
 * Suscriptor de eventos de usuario
 * Maneja logging y headers de seguridad para RUC
 */
class UserEventSubscriber
{
    /**
     * Registra los hooks de WordPress
     */
    public function register(): void
    {
        // Ejecutar en cada request antes del routing
        add_action('parse_request', [$this, 'onRequest'], 0);
        
        // Ejecutar al enviar headers
        add_action('send_headers', [$this, 'onResponse'], 0);
    }

    /**
     * Se ejecuta en cada request
     */
    public function onRequest(\WP $wp): void
    {
        $route = $_SERVER['REQUEST_URI'] ?? '';

        // Loguear accesos a rutas RUC
        if ($this->esRutaRuc($route)) {
            $this->logRucAccess($route);
        }
    }

    /**
     * Se ejecuta al enviar headers de respuesta
     */
    public function onResponse(): void
    {
        $route = $_SERVER['REQUEST_URI'] ?? '';

        // Deshabilitar caché en rutas de autenticación
        if ($this->esRutaAutenticacion($route)) {
            $this->deshabilitarCache();
        }

        // Agregar headers de seguridad generales
        $this->agregarHeadersSeguridad();
    }

    /**
     * Registra el acceso a una ruta RUC
     */
    protected function logRucAccess(string $route): void
    {
        $userId = get_current_user_id();
        $ip = $this->getClientIp();
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'N/A';

        error_log(sprintf(
            '[RUC ACCESS] Ruta: %s | Usuario: %s | IP: %s | User-Agent: %s',
            $route,
            $userId ?: 'anon',
            $ip,
            substr($userAgent, 0, 100) // Limitar longitud
        ));
    }

    /**
     * Verifica si la ruta es relacionada a RUC
     */
    protected function esRutaRuc(string $route): bool
    {
        $rutasRuc = [
            'centralruc',
            'ruc',
            'pcl/ruc',
            'api/actualizar-ruc',
        ];

        foreach ($rutasRuc as $rutaRuc) {
            if (str_contains($route, $rutaRuc)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Verifica si la ruta es de autenticación
     */
    protected function esRutaAutenticacion(string $route): bool
    {
        $rutasAuth = [
            'centralruc/procesar',
            'centralruc/cerrar-sesion',
            'centralruc/redirigir',
            'pcl/verificar-sesion',
        ];

        foreach ($rutasAuth as $rutaAuth) {
            if (str_contains($route, $rutaAuth)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Deshabilita el caché de la respuesta
     */
    protected function deshabilitarCache(): void
    {
        if (!headers_sent()) {
            header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
            header('Cache-Control: post-check=0, pre-check=0', false);
            header('Pragma: no-cache');
            header('Expires: Thu, 01 Jan 1970 00:00:00 GMT');
        }
    }

    /**
     * Agrega headers de seguridad generales
     */
    protected function agregarHeadersSeguridad(): void
    {
        if (headers_sent()) {
            return;
        }

        // Prevenir clickjacking
        header('X-Frame-Options: SAMEORIGIN');
        
        // Prevenir MIME sniffing
        header('X-Content-Type-Options: nosniff');
        
        // Habilitar protección XSS del navegador
        header('X-XSS-Protection: 1; mode=block');
        
        // Referrer policy
        header('Referrer-Policy: strict-origin-when-cross-origin');
    }

    /**
     * Obtiene la IP del cliente considerando proxies
     */
    protected function getClientIp(): string
    {
        // Lista de headers en orden de prioridad
        $headers = [
            'HTTP_CF_CONNECTING_IP',    // Cloudflare
            'HTTP_X_REAL_IP',           // Nginx
            'HTTP_X_FORWARDED_FOR',     // Proxy estándar
            'REMOTE_ADDR'               // Directo
        ];

        foreach ($headers as $header) {
            if (!empty($_SERVER[$header])) {
                $ip = $_SERVER[$header];
                
                // Si es X-Forwarded-For, tomar la primera IP
                if ($header === 'HTTP_X_FORWARDED_FOR') {
                    $ips = explode(',', $ip);
                    $ip = trim($ips[0]);
                }
                
                // Validar que sea una IP válida
                if (filter_var($ip, FILTER_VALIDATE_IP)) {
                    return $ip;
                }
            }
        }

        return 'Unknown';
    }
}