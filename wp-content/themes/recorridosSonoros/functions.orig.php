<?php
/*  === Soporte para thumbnail == */
add_theme_support( 'post-thumbnails' );

/* === sacar barra de admin == */
add_filter( 'show_admin_bar', '__return_false' );

/* === menus === */
register_nav_menus ( array(
    'menu_principal' => 'menu_header',
    'menu_footer' => 'menu_abajo'
    ));

/* ==== scripts === */
function load_jQuery()
{
    wp_deregister_script('jquery');
    wp_register_script('jquery', get_bloginfo('template_url') . '/js/jquery-3.5.1.min.js');
    wp_enqueue_script('jquery');
}
add_action('wp_enqueue_scripts', 'load_Query', 1);
function recorridos_enqueue_scripts()
{
    $dependencies = array('jquery');

    wp_enqueue_script('recorridos-js', get_template_directory_uri() . '/js/recorridos.js', $dependencies, '', true);

}
add_action('wp_enqueue_scripts', 'recorridos_enqueue_scripts', 100);
?>