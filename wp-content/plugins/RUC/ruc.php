<?php

/**
 * Plugin Name: RUC - Registro Usuario Centralizado
 * Plugin URI: https://biblioredes.gob.cl
 * Description: Integración con sistema RUC (Registro Usuario Centralizado) para autenticación
 * Version: 1.0.0
 * Author: Biblioredes
 * Author URI: https://biblioredes.gob.cl
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: ruc
 * Domain Path: /languages
 * Requires at least: 6.0
 * Requires PHP: 8.0
 */

// Evitar acceso directo
if (!defined('ABSPATH')) {
    exit;
}

// Definir constantes del plugin
define('RUC_VERSION', '1.0.0');
define('RUC_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('RUC_PLUGIN_URL', plugin_dir_url(__FILE__));
define('RUC_PLUGIN_BASENAME', plugin_basename(__FILE__));

/**
 * Autoloader para las clases del plugin
 */
spl_autoload_register(function ($class) {
    // Prefijo del namespace
    $prefix = 'RUC\\';

    // Verificar si la clase usa el namespace del plugin
    if (strpos($class, $prefix) !== 0) {
        return;
    }

    // Obtener la clase relativa al namespace
    $relative_class = substr($class, strlen($prefix));

    // Convertir namespace a ruta de archivo
    // Importante: los archivos están en src/, no en la raíz
    $file = RUC_PLUGIN_DIR . 'src/' . str_replace('\\', '/', $relative_class) . '.php';

    // Si el archivo existe, cargarlo
    if (file_exists($file)) {
        require_once $file;
    }
});

/**
 * Clase principal del plugin RUC
 */
class RUC_Plugin
{
    private static $instance = null;
    private $controller;

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct()
    {
        $this->initHooks();
    }

    private function initHooks()
    {
        register_activation_hook(__FILE__, [$this, 'activate']);
        register_deactivation_hook(__FILE__, [$this, 'deactivate']);

        add_action('init', [$this, 'init']);
        add_action('init', [$this, 'registerSessionStart'], 1);
        add_action('init', [$this, 'registerRoutes']);
        add_action('template_redirect', [$this, 'handleRoutes']);
    }

    public function activate()
    {
        if (version_compare(PHP_VERSION, '8.0', '<')) {
            deactivate_plugins(RUC_PLUGIN_BASENAME);
            wp_die('Este plugin requiere PHP 8.0 o superior.');
        }
        
        if (!get_role('usuario_autenticado')) {
            add_role(
                'usuario_autenticado',
                'Usuario Autenticado RUC',
                ['read' => true]
            );
        }
        
        // IMPORTANTE: Registrar las rutas antes de hacer flush
        $this->registerRoutes();
        flush_rewrite_rules();
        
        error_log('[RUC] Plugin activado - Rutas registradas');
    }
    public function deactivate()
    {
        flush_rewrite_rules();
        error_log('[RUC] Plugin desactivado');
    }

    public function init()
    {
        load_plugin_textdomain('ruc', false, dirname(RUC_PLUGIN_BASENAME) . '/languages');

        // Verificar que la clase del controlador existe antes de instanciarla
        if (class_exists('RUC\Controllers\CentralRucController')) {
            $this->controller = new RUC\Controllers\CentralRucController();
        } else {
            error_log('[RUC ERROR] No se pudo cargar CentralRucController');
            add_action('admin_notices', function () {
                echo '<div class="error"><p>RUC Plugin: Error al cargar el controlador principal.</p></div>';
            });
        }
    }

    public function registerSessionStart()
    {
        if (!session_id() && !headers_sent()) {
            session_start();
        }
    }

    public function registerRoutes()
    {
        // Registrar las reglas de reescritura
        add_rewrite_rule(
            '^centralruc/redirigir/([0-9]+)/?$',
            'index.php?ruc_action=redirect_to_ruc&accion=$matches[1]',
            'top'
        );

        add_rewrite_rule(
            '^centralruc/procesar/?$',
            'index.php?ruc_action=process_token',
            'top'
        );

        add_rewrite_rule(
            '^centralruc/cerrar-sesion/?$',
            'index.php?ruc_action=logout',
            'top'
        );

        add_rewrite_rule(
            '^api/actualizar-ruc/?$',
            'index.php?ruc_action=api_update',
            'top'
        );

        add_rewrite_rule(
            '^pcl/verificar-sesion/?$',
            'index.php?ruc_action=verify_session',
            'top'
        );

        add_rewrite_rule(
            '^pcl/ruc/ping/?$',
            'index.php?ruc_action=ping',
            'top'
        );

        // Registrar query vars
        add_rewrite_tag('%ruc_action%', '([^&]+)');
        add_rewrite_tag('%accion%', '([0-9]+)');
    }

    public function handleRoutes()
    {
        $action = get_query_var('ruc_action');
        
        if (empty($action)) {
            return;
        }
        
        if (!$this->controller) {
            wp_die('Error: Controlador RUC no inicializado. Verifica los logs para más detalles.');
        }
        
        switch ($action) {
            case 'redirect_to_ruc':
                $accion = get_query_var('accion');
                if (!empty($accion)) {
                    $this->controller->redirigir((int)$accion);
                } else {
                    wp_die('Parámetro acción requerido');
                }
                break;
                
            case 'process_token':
                $this->controller->procesarRUC();
                break;
                
            case 'logout':
                $this->controller->cerrarSesion();
                break;
                
            case 'verify_session':
                $this->controller->verificarSesion();
                break;
                
            case 'api_update':
                // Verificar que sea POST o GET
                if (!in_array($_SERVER['REQUEST_METHOD'], ['POST', 'GET'])) {
                    wp_die('Método no permitido', 405);
                }
                $this->controller->apiActualizarRuc();
                break;
                
            case 'ping':
                $this->controller->ping();
                break;
                
            default:
                wp_die('Acción RUC no válida', 404);
        }
    }
}

// Inicializar el plugin
function ruc_init()
{
    return RUC_Plugin::getInstance();
}

add_action('plugins_loaded', 'ruc_init');

// Helper global
function ruc()
{
    return RUC_Plugin::getInstance();
}
