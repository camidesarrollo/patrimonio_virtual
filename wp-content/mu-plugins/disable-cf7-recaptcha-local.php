<?php
/**
 * Desactiva reCAPTCHA de Contact Form 7 en localhost.
 */

add_action('plugins_loaded', function () {
  // Verifica si estamos en localhost
  if (
    isset($_SERVER['HTTP_HOST']) &&
    (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false ||
     strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false)
  ) {
    // Remueve el archivo del módulo reCAPTCHA
    remove_action('wpcf7_init', 'wpcf7_recaptcha_register_service', 40);
    remove_action('wp_enqueue_scripts', 'wpcf7_recaptcha_enqueue_scripts', 20);
    remove_filter('wpcf7_form_hidden_fields', 'wpcf7_recaptcha_add_hidden_fields', 100);
    remove_filter('wpcf7_spam', 'wpcf7_recaptcha_verify_response', 9);
    remove_action('wpcf7_init', 'wpcf7_recaptcha_add_form_tag_recaptcha', 10);
  }
}, 5);
