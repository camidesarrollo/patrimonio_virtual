<?php
if (!defined('COOKIE_DOMAIN')) {
  define('COOKIE_DOMAIN', '.biblioredes.gob.cl');
}
require_once get_template_directory() . '/recorridos-sonoros-functions.php';
/*  === Soporte para thumbnail == */
add_theme_support('post-thumbnails');

/* === sacar barra de admin == */
add_filter('show_admin_bar', '__return_false');

/* Disable User Notification of Password Change Confirmation */
add_filter('send_email_change_email', '__return_false');

/* === menus === */
register_nav_menus(array(
  'menu_principal' => 'menu_header',
  'menu_footer' => 'menu_abajo'
));

/* ==== scripts === */
function load_jQuery()
{
  //    wp_deregister_script('jquery');
  //    wp_register_script('jquery', get_bloginfo('template_url') . '/js/jquery-3.5.1.min.js');
  //    wp_enqueue_script('jquery');
}
add_action('wp_enqueue_scripts', 'load_jQuery', 1);
add_filter('rest_authentication_errors', function ($result) {
  $requested_route = !empty($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
  if (strpos($requested_route, '/wp-json/contact-form-7/') !== false) {
    return true;
  }

  if (true === $result || is_wp_error($result)) {
    return $result;
  }

  if (! is_user_logged_in()) {
    return new WP_Error(
      'rest_not_logged_in',
      __('You are not currently logged in.'),
      array('status' => 401)
    );
  }
  return $result;
});


function recorridos_enqueue_scripts()
{
  $dependencies = array('jquery');

  wp_enqueue_script('recorridos-js', get_template_directory_uri() . '/js/recorridos.js', $dependencies, '', true);
  wp_enqueue_script('recorridos-sonoros-js', get_template_directory_uri() . '/js/recorridos-sonoros-scripts.js', $dependencies, '', true);
  wp_enqueue_script('modal-js', get_template_directory_uri() . '/js/modal.js', $dependencies, '', true);
}
add_action('wp_enqueue_scripts', 'recorridos_enqueue_scripts', 100);


function patrimonio_remove_version()
{
  return '';
}
add_filter('the_generator', 'patrimonio_remove_version');


function crear_un_cpt()
{

  $labels = array(
    'name'                => _x('Eventos', 'Post Type General Name', 'bym'),
    'singular_name'       => _x('Evento', 'Post Type Singular Name', 'bym'),
    'menu_name'           => __('Participa', 'bym'),
    'parent_item_colon'   => __('Parent', 'bym'),
    'all_items'           => __('Todos los eventos', 'bym'),
    'view_item'           => __('ver', 'bym'),
    'add_new_item'        => __('Agregar evento', 'bym'),
    'add_new'             => __('Agregar evento', 'bym'),
    'edit_item'           => __('Editar', 'bym'),
    'update_item'         => __('Actualizar', 'bym'),
    'search_items'        => __('Buscar', 'bym'),
    'not_found'           => __('Evento no encontrado', 'bym'),
    'not_found_in_trash'  => __('Evento no encontrado', 'bym'),
  );
  $args = array(
    'public' => true,
    'label' => 'Eventos',
    'labels' => $labels,
    'supports' => array('title', 'thumbnail'),
    'menu_position' => 7,
    'menu_icon' => 'dashicons-megaphone'
  );
  register_post_type('eventos', $args);
}
add_action('init', 'crear_un_cpt');

add_filter('pll_get_post_types', 'add_cpt_to_pll', 10, 2);
function add_cpt_to_pll($post_types, $hide)
{
  if ($hide)
    // hides 'my_cpt' from the list of custom post types in Polylang settings
    unset($post_types['my_cpt']);
  else
    // enables language and translation management for 'my_cpt'
    $post_types['eventos'] = 'eventos';
  return $post_types;
}



function registrar_contenido_multimedia_cpt()
{
  $labels = [
    'name' => 'Contenido Multimedia',
    'singular_name' => 'Contenido Multimedia',
    'menu_name' => 'Multimedia',
    'name_admin_bar' => 'Contenido Multimedia',
    'add_new' => 'Agregar Nuevo',
    'add_new_item' => 'Agregar Nuevo Contenido',
    'new_item' => 'Nuevo Contenido',
    'edit_item' => 'Editar Contenido',
    'view_item' => 'Ver Contenido',
    'all_items' => 'Todos los Contenidos',
    'search_items' => 'Buscar Contenido',
    'not_found' => 'No se encontró contenido',
    'not_found_in_trash' => 'No se encontró contenido en la papelera'
  ];

  $args = [
    'labels' => $labels,
    'public' => true,
    'menu_position' => 5,
    'menu_icon' => 'dashicons-format-video',
    'supports' => array('title', 'thumbnail'),
    'has_archive' => true,
    'rewrite' => array('slug' => 'contenido-multimedia'),
    'show_in_rest' => true,
  ];

  register_post_type('contenido_multimedia', $args);
}
add_action('init', 'registrar_contenido_multimedia_cpt');

// Añadir clase 'current-menu-item' a enlaces personalizados si coinciden con la URL actual
add_filter('nav_menu_css_class', function ($classes, $item) {
  if (is_singular()) {
    $current_url = home_url(add_query_arg([], $_SERVER['REQUEST_URI']));
    $menu_url = $item->url;

    if (untrailingslashit($current_url) == untrailingslashit($menu_url)) {
      $classes[] = 'current-menu-item';
    }
  }
  return $classes;
}, 10, 2);

function mostrar_contenidos_por_tipo($tipo_a_mostrar)
{
  $args = array(
    'post_type'      => 'contenido_multimedia',
    'posts_per_page' => 3,
    'post_status'    => 'publish',
    'meta_query'     => array(
      array(
        'key'     => 'tipo_de_contenido',
        'value'   => $tipo_a_mostrar,
        'compare' => '='
      )
    )
  );

  $query = new WP_Query($args);

  if ($query->have_posts()):
?>
    <section id="<?php echo ucfirst($tipo_a_mostrar) . 's'; ?>">
      <div class="container">
        <div class="contenido-multimedia">
          <div class="row container-titulo">
            <div class="col-12">
              <h1><?php echo ucfirst($tipo_a_mostrar) . 's'; ?></h1>
            </div>
          </div>
          <div class="row imagenes-catalogo">
            <?php
            while ($query->have_posts()): $query->the_post();

              $tipo = get_field('tipo_de_contenido');
              $url = get_field('url_del_contenido');
              $imagen = get_the_post_thumbnail(null, 'full', array('class' => 'img-fluid'));
              $url_imagen = get_the_post_thumbnail_url(null, 'full');
              $permalink = get_permalink(); // <-- el enlace permanente

              if ($tipo && $url):
            ?>
                <div class="col-12 col-sm-6 col-lg-4 ">
                  <div class="imagen-fondo" style="background-image: url('<?php echo esc_url($url_imagen); ?>');"></div>
                  <h3 class="subtitulo3" style="border-bottom: 1px solid #707070; padding-bottom: .5rem;white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?php the_title(); ?></h3>
                  <p>
                    <a href="<?php echo esc_url($permalink); ?>">
                      <b>Ver más <i class="fas fa-chevron-right ir-derecha" aria-hidden="true"></i></b>
                    </a>
                  </p>
                </div>
            <?php
              endif;
            endwhile;
            wp_reset_postdata();
            ?>
          </div>
        </div>
        <div class="row d-flex justify-content-center contenedor-boton-central" style="padding-top: 1.6875rem; padding-bottom: 1rem;">
          <div class="col-12  text-center boton-central" onclick="window.location='/<?php echo strtolower(ucfirst($tipo_a_mostrar)) . 's'; ?>/';">
            Ver más <?php echo ucfirst($tipo_a_mostrar) . 's'; ?>
          </div>
        </div>
      </div>

    </section>

  <?php
  else:
    echo '<div class="container"><p>No se encontraron ' . $tipo_a_mostrar . 's.</p></div>';
  endif;
}

function mostrar_todos_los_contenidos_por_tipo($tipo_a_mostrar)
{
  $paged = max(1, get_query_var('paged'), get_query_var('page'));


  $args = array(
    'post_type'      => 'contenido_multimedia',
    'posts_per_page' => 12,
    'paged'          => $paged,
    'post_status'    => 'publish',
    'meta_query'     => array(
      array(
        'key'     => 'tipo_de_contenido',
        'value'   => $tipo_a_mostrar,
        'compare' => '='
      )
    )
  );

  $query = new WP_Query($args);

  if ($query->have_posts()):
  ?>
    <section id="<?php echo strtolower($tipo_a_mostrar); ?>s">
      <div class="container">
        <div class="contenido-multimedia">
          <div class="row container-titulo">
            <div class="col-12">
              <h1><?php echo ucfirst($tipo_a_mostrar) . 's'; ?></h1>
            </div>
          </div>
          <div class="row imagenes-catalogo">
            <?php
            while ($query->have_posts()): $query->the_post();
              $tipo = get_field('tipo_de_contenido');
              $url = get_field('url_del_contenido');
              $url_imagen = get_the_post_thumbnail_url(null, 'full');
              if ($tipo && $url):
            ?>
                <div class="col-12 col-sm-6 col-lg-4">
                  <div class="imagen-fondo" style="background-image: url('<?php echo esc_url($url_imagen); ?>'); height: 250px; background-size: cover; background-position: center;"></div>
                  <h3 class="subtitulo3" style="border-bottom: 1px solid #707070; padding-bottom: .5rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?php the_title(); ?></h3>
                  <p>
                    <a href="<?php echo esc_url($url); ?>" target="_blank">
                      <b>Ver más <i class="fas fa-chevron-right ir-derecha" aria-hidden="true"></i></b>
                    </a>
                  </p>
                </div>
            <?php
              endif;
            endwhile;
            ?>
          </div>

          <!-- PAGINADOR -->
          <div class="row">
            <div class="col-12 d-flex justify-content-center mt-4">
              <?php
              echo paginate_links(array(
                'total'   => $query->max_num_pages,
                'current' => $paged,
                'prev_text' => '<i class="fas fa-chevron-left"></i>',
                'next_text' => '<i class="fas fa-chevron-right"></i>',
              ));
              ?>
            </div>
          </div>

        </div>
      </div>
    </section>
    <?php
    wp_reset_postdata();
  else:
    echo '<div class="container"><p>No se encontraron ' . esc_html($tipo_a_mostrar) . 's.</p></div>';
  endif;
}

function mostrar_contenidos_asociados()
{
  global $post;

  // Obtiene los posts relacionados
  $contenidos_relacionados = get_field('contenido_multimedia', $post->ID);

  if ($contenidos_relacionados && is_array($contenidos_relacionados)) {
    // Ordenarlos por fecha descendente
    usort($contenidos_relacionados, function ($a, $b) {
      return strcmp($b->post_date, $a->post_date);
    });

    if (!empty($contenidos_relacionados)) :
    ?>
      <section class="contenidos-asociados" style="padding-bottom:1rem;">
        <div class="container">
          <div class="row container-titulo">
            <div class="col-12">
              <h1>Multimedia</h1>
            </div>
          </div>

          <!-- Carrusel -->
          <div class="swiper mySwiper">
            <div class="swiper-wrapper">
              <?php foreach ($contenidos_relacionados as $contenido):
                $url = get_field('url_del_contenido', $contenido->ID);
                $imagen_url = get_the_post_thumbnail_url($contenido->ID, 'full');
                $permalink = get_permalink($contenido->ID);
              ?>
                <div class="swiper-slide">
                  <div class="col-12">
                    <div class="imagen-fondo" style="background-image: url('<?php echo esc_url($imagen_url); ?>'); height: 250px; background-size: cover; background-position: center;"></div>
                    <h3 class="subtitulo3" style="border-bottom: 1px solid #707070; padding-bottom: .5rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                      <?php echo esc_html(get_the_title($contenido->ID)); ?>
                    </h3>
                    <?php if ($url): ?>
                      <p>
                        <a href="<?php echo esc_url($permalink); ?>">
                          <b>Ver más <i class="fas fa-chevron-right ir-derecha" aria-hidden="true"></i></b>
                        </a>
                      </p>
                    <?php endif; ?>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>

            <!-- Botones de navegación -->
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
            <!-- Paginación opcional -->
            <div class="swiper-pagination"></div>
          </div>
        </div>
      </section>

      <script>
        document.addEventListener('DOMContentLoaded', function() {
          var swiper = new Swiper('.mySwiper', {
            slidesPerView: 1, // Default value for desktop
            spaceBetween: 30,
            loop: true,
            navigation: {
              nextEl: '.swiper-button-next',
              prevEl: '.swiper-button-prev',
            },
            pagination: {
              el: '.swiper-pagination',
              clickable: true,
            },
            breakpoints: {
              1280: {
                slidesPerView: 3, // Para tabletas o pantallas medianas
              },
              // Para pantallas más pequeñas
              768: {
                slidesPerView: 2, // Para tabletas o pantallas medianas
              },
              576: {
                slidesPerView: 1, // Para móviles
              }
            }
          });
        });
      </script>

      <!-- Recuerda incluir los archivos de Swiper en tu plantilla -->
      <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/swiper-bundle.min.css" />
      <script src="<?php echo get_template_directory_uri(); ?>/js/swiper-bundle.min.js"></script>
  <?php
    endif;
  }
}



function mostrar_contenido_unico()
{
  $tipo = get_field('tipo_de_contenido'); // Asumiendo que tienes un campo que guarda el tipo: 'video' o 'documento'
  $url = get_field('url_del_contenido');
  $titulo = get_the_title();

  if (!$tipo || !$url) {
    echo '<p>Contenido no disponible.</p>';
    return;
  } ?>
  <div class="breadcrumbs pt-3" style="color:#C0C0C0;font-size: 1.0625rem;">
    <a href="/" style="color:#4A4A4A; text-decoration: none;">Inicio</a> /
    <a href="/multimedia" style="color:#4A4A4A; text-decoration: none;">Multimedia</a> / <?php echo $titulo; ?>
  </div>
  <div class="row container-titulo">
    <div class="col-12">
      <h1><?php echo $titulo; ?></h1>
    </div>
  </div>
<?php
  if ($tipo == 'video') {
    // Extraer ID del video de YouTube
    if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([^\&\?\/]+)/', $url, $matches)) {
      $youtube_id = $matches[1];
      echo '<div style="position:relative;padding-bottom:56.25%;height:0;overflow:hidden;">';
      echo '<iframe src="https://www.youtube.com/embed/' . esc_attr($youtube_id) . '" frameborder="0" allowfullscreen 
          style="position:absolute;top:0;left:0;width:100%;height:100%;"></iframe>';
      echo '</div>';
    } else {
      echo '<p>URL de video no válida.</p>';
    }
  } elseif ($tipo == 'documento') {
    echo '<div style="height:600px;">';
    echo '<iframe src="https://drive.google.com/viewerng/viewer?embedded=true&url=' . urlencode($url) . '" frameborder="0" style="width:100%; height:100%;"></iframe>';
    echo '</div>';
  } else {
    echo '<p>Tipo de contenido no reconocido.</p>';
  }
}

/* VALIDACIONES DEL FORMULARIO */
//Insertar Javascript js y enviar ruta admin-ajax.php
add_action('wp_enqueue_scripts', 'dcms_insertar_js');

function dcms_insertar_js()
{
  //if (!is_home()) return;
  wp_register_script('rut-js', get_template_directory_uri() . '/inscripcionpatvirtual/Js/rut.js', array('jquery'), '1', true);
  wp_enqueue_script('rut-js');

  wp_register_script('validacionCampos-js', get_template_directory_uri() . '/inscripcionpatvirtual/Js/validacionCampos.js', array('jquery'), '1', true);
  wp_enqueue_script('validacionCampos-js');

  //wp_register_script('Recaptcha-js', get_template_directory_uri() . '/inscripcionpatvirtual/Js/Recaptcha.js', array('jquery'), '1', true);
  //wp_enqueue_script('Recaptcha-js');

  wp_register_script('validacionCamposSessionRut-js', get_template_directory_uri() . '/inscripcionpatvirtual/Js/validacionCamposSessionRut.js', array('jquery'), '1', true);
  wp_enqueue_script('validacionCamposSessionRut-js');

  wp_localize_script('rut-js', 'rut_vars', ['ajaxurl' => admin_url('admin-ajax.php')]);
  wp_localize_script('validacionCampos-js', 'valCampos_vars', ['ajaxurl' => admin_url('admin-ajax.php')]);
  wp_localize_script('validacionCamposSessionRut-js', 'valCamposLoginRut_vars', ['ajaxurl' => admin_url('admin-ajax.php')]);

  wp_enqueue_script('recorridos-js', get_template_directory_uri() . '/js/recorridos.js', [], null, true);
  wp_localize_script('recorridos-js', 'datosUsuarioEncriptado', enqueue_recorridos_script());
}
add_action('wp_logout', 'auto_redirect_after_logout');
function auto_redirect_after_logout()
{
  wp_safe_redirect(home_url());
  exit;
}
/* FORMULARIO E INTEGRACION */
if ('POST' == $_SERVER['REQUEST_METHOD'] && !empty($_POST['action']) &&  $_POST['action'] == "new_post") {
  $registroResultado = guardarRegistroNuevoUsuario();
  if ($registroResultado == "UserCreado") {
    //loginUsuarioWordPress($_POST["rut_persona"]);
    $url = site_url() . "/registro-msge?validarRegistro=1";
  } else if ($registroResultado == "UserExist") {
    $url = site_url() . "/registro-msge?errorRegistro=1";
  } else {
    $url = site_url() . "/registro-msge?errorRegistro=1";
  }
  wp_safe_redirect($url);
  exit();
}
/* FORMULARIO DE INICIO SESION CON RUT O PASAPORTE */
if ('POST' == $_SERVER['REQUEST_METHOD'] && !empty($_POST['action']) &&  $_POST['action'] == "new_post_inicio_sesion") {
  $valida = loginUsuarioWordPress($_POST["rut_persona"], $_POST["contrasena_persona"], false);
  if ($valida == true) {
    $url = site_url() . "/mi-perfil";
  } else {
    if (isset($_POST["faltaValidar"])) {
      if (isset($_POST["pasaporte"])) {
        $url = site_url() . "/inicia-sesion?errorLogin='4'&pasaporte=1";
      } else {
        $url = site_url() . "/inicia-sesion?errorLogin='4'";
      }
    } else {
      if (isset($_POST["pasaporte"])) {
        $url = site_url() . "/inicia-sesion?errorLogin='1'&pasaporte=1";
      } else {
        $url = site_url() . "/inicia-sesion?errorLogin='1'";
      }
    }
  }
  wp_safe_redirect($url);
  exit();
}
/* FORMULARIO RECUPERAR CONTRASEÑA CON RUT O PASAPORTE Y FECHA NAC. */
if ('POST' == $_SERVER['REQUEST_METHOD'] && !empty($_POST['action']) &&  $_POST['action'] == "new_post_reccontra") {
  $valida = false;
  // VERIFICA SI IDENTIFICACION Y FECHA DE NAC. COINCIDEN (FILTRA EN CASO DE SER PASAPORTE)
  if (isset($_POST["pasaporte"])) {
    $valida = verificaFechaMaestro($_POST["rut_persona"], $_POST["fecnac_persona"], true);
  } else {
    $valida = verificaFechaMaestro($_POST["rut_persona"], $_POST["fecnac_persona"], false);
  }
  if ($valida == true) {
    // EN CASO DE COINCIDIR, HASHEA EL ID Y LO ENVÍA JUNTO CON ESTE A LA PAGINA PARA MODIFICAR LA CONTRASEÑA
    $idenHash = md5($_POST["rut_persona"]);
    if (isset($_POST["pasaporte"])) {
      $url = site_url() . "/cambia-contrasena?ih=" . $idenHash . "&pasaporte=" . $_POST['pasaporte'] . "&i=" . $_POST["rut_persona"];
    } else {
      $url = site_url() . "/cambia-contrasena?ih=" . $idenHash . "&i=" . $_POST["rut_persona"];
    }
  } else {
    // EN CASO DE ERROR RETORNA A LA PAGINA MOSTRANDO UN MENSAJE
    if (isset($_POST["pasaporte"])) {
      $url = site_url() . "/recupera-contrasena?errorLogin='1'&pasaporte=1";
    } else {
      $url = site_url() . "/recupera-contrasena?errorLogin='1'";
    }
  }
  wp_safe_redirect($url);
  exit();
}
/* FORMULARIO PARA CAMBIAR CONTRASEÑA */
if ('POST' == $_SERVER['REQUEST_METHOD'] && !empty($_POST['action']) &&  $_POST['action'] == "form_cambia_pass") {

  if (isset($_POST["pasaporte"])) {
    $valida = cambiaContraMaestro($_POST["identificacion"], true, $_POST['pass_persona'], $_POST['repass_persona'], $_POST["contrasena_old_persona_edit"]);
  } else {
    $valida = cambiaContraMaestro($_POST["identificacion"], false, $_POST['pass_persona'], $_POST['repass_persona'], $_POST["contrasena_old_persona_edit"]);
  }

  if ($valida == true) {
    if (isset($_POST["pasaporte"])) {
      $url = site_url() . "/inicia-sesion?cambioPass='1'&pasaporte=1";
    } else {
      $url = site_url() . "/inicia-sesion?cambioPass='1'";
    }
  } else {
    // EN CASO DE ERROR RETORNA A LA PAGINA MOSTRANDO UN MENSAJE
    if (isset($_POST["pasaporte"])) {
      $url = site_url() . "/recupera-contrasena?errorLogin='2'&pasaporte=1";
    } else {
      $url = site_url() . "/recupera-contrasena?errorLogin='2'";
    }
  }
  wp_safe_redirect($url);
  exit();
}
/* FORMULARIO PARA CAMBIAR CORREO / CONTRASEÑA */
if ('POST' == $_SERVER['REQUEST_METHOD'] && !empty($_POST['action']) &&  $_POST['action'] == "new_post_edit") {
  $userID = get_current_user_id();
  $all_meta_for_user = get_user_meta($userID);
  $resultado = true;
  $actualizado = "non";
  //EN CASO DE QUE EL USUARIO INGRESE UN CORREO DISTINTO AL REGISTRADO, LO MODIFICA
  if ($_POST["mail_persona_edit"] != $all_meta_for_user["mail_persona"][0]) {
    $actualizado = "mail";
    if ($all_meta_for_user["tipo_identificacion"][0] == "P") {
      $resultado = send_email_validaCorreo($all_meta_for_user["rut_persona"][0], true, $_POST["mail_persona_edit"]);
    } else {
      $resultado = send_email_validaCorreo($all_meta_for_user["rut_persona"][0], false, $_POST["mail_persona_edit"]);
    }
  }
  // EN CASO DE QUE EL USUARIO INGRESE UNA CONTRASEÑA, LA MODIFICA
  if ($_POST["contrasena_persona_edit"] != "" && $_POST["contrasena_persona_edit"] == $_POST["recontrasena_persona_edit"]) {
    $actualizado == "mail" ? $actualizado = "mailpass" : $actualizado = "pass";
    $_POST["idenh"] = md5($_POST["identificacion"]);
    if ($all_meta_for_user["tipo_identificacion"][0] == "P") {
      $resultado = cambiaContraMaestro($all_meta_for_user["rut_persona"][0], true, $_POST["contrasena_persona_edit"], $_POST["recontrasena_persona_edit"], $_POST["contrasena_old_persona_edit"]);
    } else {
      $resultado = cambiaContraMaestro($all_meta_for_user["rut_persona"][0], false, $_POST["contrasena_persona_edit"], $_POST["recontrasena_persona_edit"], $_POST["contrasena_old_persona_edit"]);
    }
  }
  $url = "";
  if ($resultado == 1) {
    $url = site_url() . "/mi-perfil?acC=" . $actualizado;
  } else if ($resultado == -2) {
    $url = site_url() . "/mi-perfil?error=-2";
  } else {
    $url = site_url() . "/mi-perfil?error=1";
  }
  wp_safe_redirect($url);
  exit();
}
function guardarRegistroNuevoUsuario()
{

  $respuesta = "";
  $esPasaporte = false;
  try {
    $persona = [];
    $persona['rut_persona'] = $_POST["rut_persona"];
    $persona['tipo_identificacion'] = $_POST["tipoIden_persona"];
    $persona['nombre_persona'] = $_POST["nombre_persona"];
    $persona['apellido_paterno'] = $_POST["apellido_paterno"];
    $persona['apellido_materno'] = $_POST["apellido_materno"];
    $persona['fecha_nacimiento'] = $_POST["fecha_nacimiento"];
    $persona['region_persona'] = $_POST["region_persona"];
    $persona['comuna_persona'] = $_POST["comuna_persona"];
    $persona['pais_persona'] = $_POST["pais_persona"];
    $persona['mail_persona'] = $_POST["mail_persona"];
    $persona['sexo_persona'] = $_POST["genero_persona"];
    $persona['codigo_nacionalidad'] = $_POST["codNacionalidad"];
    $persona['paisorigen_persona'] = $_POST["paisorigen_persona"];
    $persona['mail_persona_repite'] = $_POST["mail_persona_repite"];
    $persona['contrasena_persona'] = $_POST["contrasena_persona"];
    $persona['recontrasena_persona'] = $_POST["recontrasena_persona"];
    $persona['validado_persona'] = 0;
    if (username_exists($persona['rut_persona'])) {
      $respuesta = "UserExist";
    } else {
      $codValidacion = rand();
      $userWordResult = new_user_with_metadata($persona, $codValidacion);
      $md5Cod = md5($codValidacion);
      if ($userWordResult) {
        $url = site_url() . '/valida-cuenta?cval=' . $md5Cod . "&ciden=" . $persona['rut_persona'];
        send_email_Inscripcion($persona['mail_persona'], $url);
        $respuesta = "UserCreado";
      } else {
        $respuesta = "ErrorWordpress";
      }
    }
    return $respuesta;
  } catch (\Throwable $th) {
    // send_email_Inscripcion($error, 'Error');
    $respuesta = "Error producido al crear usuario <br>" . $th;
    return $respuesta;
  }
}
function registroMaestro($persona, $esPasaporte)
{

  include_once get_template_directory() . '/inscripcionpatvirtual/BO/MaestroUsuarios.php';
  $instanciaMaestro = new MaestroUsuarios();
  if ($persona['tipo_identificacion'] == "P") {
    $esPasaporte = true;
  }

  if ($instanciaMaestro->Insertar_Usuario($persona, $esPasaporte)) {
    return true;
  } else {
    $respuesta = "ErrorMaestro";
    return false;
    //delete_user_by_username($_POST["rut_persona"]);
  }
}

// Función auxiliar para manejar usuario no encontrado
function manejarUsuarioNoEncontrado($identificacionUsuario)
{
  if (username_exists($identificacionUsuario)) {
    $userID = get_user_by('login', $identificacionUsuario);
    $all_meta_for_user = get_user_meta($userID->ID);
    if ($all_meta_for_user["validado_persona"][0] != 0) {
      $_POST["faltaValidar"] = 1;
    }
  }
}

// Función auxiliar para construir el array de datos del usuario
function construirPersona($usuario, $esPasaporte)
{
  return [
    'tipo_identificacion' => $esPasaporte ? "P" : "R",
    'rut_persona' => $esPasaporte ? $usuario->NumeroPasaporte : $usuario->RUN . '-' . $usuario->DV,
    'nombre_persona' => $usuario->Nombre,
    'apellido_paterno' => $usuario->ApellidoPaterno,
    'apellido_materno' => $usuario->ApellidoMaterno,
    'fecha_nacimiento' => $usuario->FechaNacimiento->format('d-m-Y'),
    'region_persona' => '',
    'comuna_persona' => $usuario->CodigoComuna,
    'mail_persona' => $usuario->Correo,
    'contrasena_persona' => '',
    'codigo_nacionalidad' => $usuario->CodigoNacionalidad,
    'paisorigen_persona' => $usuario->CodigoPaisOrigen,
    'codigo_usuario' => (int) (isset($usuario->CodigoUsuario) ? $usuario->CodigoUsuario : $usuario->CodigoExtranjero),
  ];
}

// Función auxiliar para iniciar sesión en WordPress
function iniciarSesionWordPress($identificacionUsuario)
{
  if (is_user_logged_in()) {
    wp_logout();
  }

  add_filter('authenticate', 'allow_programmatic_login', 10, 3);
  $user = wp_signon(['user_login' => $identificacionUsuario]);
  remove_filter('authenticate', 'allow_programmatic_login', 10, 3);

  if (is_a($user, 'WP_User')) {
    wp_set_current_user($user->ID, $user->user_login);
    return is_user_logged_in();
  }

  return false;
}
function allow_programmatic_login($user, $usuarioWordPress, $password)
{
  return get_user_by('login', $usuarioWordPress);
}
//Devolver datos a archivo js
add_action('wp_ajax_nopriv_inscripciones_ajax', 'inscripciones_obtener_json');
add_action('wp_ajax_inscripciones_ajax', 'inscripciones_obtener_json');

function inscripciones_obtener_json($option)
{
  $option = $_POST["option"];
  switch ($option) {
    case 0: // OBTERNER PISEE
      $rut = $_POST["rut"];
      // try {
      //   $client = new SoapClient("http://10.0.1.241:8000/srcei.asmx?WSDL");
      //   $run = substr($rut, 0, -2);
      //   $dv = substr($rut, -1);
      //   $result = $client->Traer(["run" => $run, "dv" => $dv]);

      //   $xml = simplexml_load_string($result->TraerResult->any);
      //   echo $xml;
      //   if($xml == false){
      //     $data = array(
      //       'estado' => 'nok4',  // Persona con datos privados, rut válido pero no existe info o Pisee Desconectado
      //     );
      //     echo json_encode($data, JSON_FORCE_OBJECT);
      //   }

      //   $startDate = time();
      //   /* PREGUNTAR QUE EDAD SERAN VALIDAS PARA PODER REGISTARSE*/
      //   $minDate = date('Y-m-d', strtotime('-4 year', $startDate));
      //   $startDate = date('Y-m-d', strtotime('-120 year', $startDate));

      //   $numero = $xml->datosPersona->run->numero[0];
      //   $dv = $xml->datosPersona->run->dv[0];

      //   if ($xml->glosa[0] == null) {
      //     if ($xml->datosPersona->fechaDefuncion->fechaTruncada == '0000-00-00') {
      //       if (strtotime($xml->datosPersona->fechaNacimiento->fechaValida) >= strtotime($startDate)) {
      //         if (strtotime($xml->datosPersona->fechaNacimiento->fechaValida) <= strtotime($minDate)) {
      //           $data = array(
      //             'estado' => 'ok',
      //             'nombres' => (string)$xml->datosPersona->nombre->nombres[0],
      //             'apellidoPaterno' => (string)$xml->datosPersona->nombre->apellidoPaterno[0],
      //             'apellidoMaterno' => (string)$xml->datosPersona->nombre->apellidoMaterno[0],
      //             'sexo' => (string)$xml->datosPersona->sexo[0],
      //             'fecha' => (string)$xml->datosPersona->fechaNacimiento->fechaValida[0],
      //             'nacionalidad' => (string)$xml->datosPersona->nacionalidad[0],

      //           );
      //         } else {
      //           $data = array(
      //             'estado' => 'nok1',
      //             'nombres' => (string)$xml->datosPersona->nombre->nombres[0],
      //             'apellidoPaterno' => (string)$xml->datosPersona->nombre->apellidoPaterno[0],
      //             'apellidoMaterno' => (string)$xml->datosPersona->nombre->apellidoMaterno[0],
      //             'sexo' => (string)$xml->datosPersona->sexo[0],
      //             'fecha' => (string)$xml->datosPersona->fechaNacimiento->fechaValida[0],
      //             'nacionalidad' => (string)$xml->datosPersona->nacionalidad[0],

      //           );
      //         }
      //       } else {
      //         $data = array(
      //           'estado' => 'nok2',  //Fecha Inválida. La edad debe ser menor a 120 años
      //         );
      //       }
      //     } else {
      //       $data = array(
      //         'estado' => 'nok3',  // Persona Fallecida      
      //       );
      //     }
      //   } else {
      //     $data = array(
      //       'estado' => 'nok4',  // Persona con datos privados, rut válido pero no existe info o Pisee Desconectado
      //     );
      //   }

      // } catch (SoapFault $e) {

      //   $data = array(
      //     'estado' => 'nok4',  // Persona con datos privados, rut válido pero no existe info o Pisee Desconectado
      //   );
      //   echo json_encode($data, JSON_FORCE_OBJECT);
      // }
      try {
        // Inicialización del cliente SOAP
        $client = new SoapClient("http://10.0.1.241:8000/srcei.asmx?WSDL");

        // Procesamiento del RUT
        $run = substr($rut, 0, -2);
        $dv = substr($rut, -1);
        $result = $client->Traer(["run" => $run, "dv" => $dv]);

        // Verificar si el resultado contiene la propiedad 'TraerResult'
        if (!isset($result->TraerResult)) {
          echo json_encode(["estado" => "nok4", "mensaje" => "La respuesta del servicio SOAP no contiene la propiedad 'TraerResult'."], JSON_FORCE_OBJECT);
          exit;
        }

        // Intentar cargar el XML
        $xml = simplexml_load_string($result->TraerResult->any);

        // Verificar si la carga del XML fue exitosa
        if ($xml === false) {
          echo json_encode(["estado" => "nok4", "mensaje" => "Error al procesar el XML de la respuesta."], JSON_FORCE_OBJECT);
          exit;
        }

        // Validación de fechas
        $startDate = time();
        $minDate = date('Y-m-d', strtotime('-7 year', $startDate));
        $startDate = date('Y-m-d', strtotime('-120 year', $startDate));

        // Verificación de datos de la persona en el XML
        if (isset($xml->datosPersona)) {
          $numero = isset($xml->datosPersona->run->numero[0]) ? $xml->datosPersona->run->numero[0] : null;
          $dv = isset($xml->datosPersona->run->dv[0]) ? $xml->datosPersona->run->dv[0] : null;

          if ($xml->glosa[0] == null) {
            if ($xml->datosPersona->fechaDefuncion->fechaTruncada == '0000-00-00') {
              if (strtotime($xml->datosPersona->fechaNacimiento->fechaValida) >= strtotime($startDate)) {
                if (strtotime($xml->datosPersona->fechaNacimiento->fechaValida) <= strtotime($minDate)) {
                  $data = array(
                    'estado' => 'ok',
                    'nombres' => (string)$xml->datosPersona->nombre->nombres[0],
                    'apellidoPaterno' => (string)$xml->datosPersona->nombre->apellidoPaterno[0],
                    'apellidoMaterno' => (string)$xml->datosPersona->nombre->apellidoMaterno[0],
                    'sexo' => (string)$xml->datosPersona->sexo[0],
                    'fecha' => (string)$xml->datosPersona->fechaNacimiento->fechaValida[0],
                    'nacionalidad' => (string)$xml->datosPersona->nacionalidad[0],
                  );
                } else {
                  $data = array(
                    'estado' => 'nok1',  // Fecha Inválida. La edad debe ser mayor a 6 años
                  );
                }
              } else {
                $data = array(
                  'estado' => 'nok2',  // Fecha Inválida. La edad debe ser menor a 120 años
                );
              }
            } else {
              $data = array(
                'estado' => 'nok3',  // Persona Fallecida
              );
            }
          } else {
            $data = array(
              'estado' => 'nok4',  // Persona con datos privados, rut válido pero no existe info o Pisee Desconectado
            );
          }
        } else {
          $data = array(
            'estado' => 'nok4',  // Persona con datos privados, rut válido pero no existe info o Pisee Desconectado
          );
        }

        echo json_encode($data, JSON_FORCE_OBJECT);
      } catch (SoapFault $e) {
        // Captura de excepciones y retorno de error general
        echo json_encode(['estado' => 'nok4', 'mensaje' => 'Error al acceder al servicio SOAP: ' . $e->getMessage()], JSON_FORCE_OBJECT);
      }
      break;


    case 1: // OBTENER REGION
      include_once get_template_directory() . '/inscripcionpatvirtual/Jason/ObtenerDataJason.php';
      try {
        $data = array();
        $instancia_lfiltro = new ObtenerDataJason();
        //$id = $_POST["id"];
        $resultados = $instancia_lfiltro->Lista_Regiones();
        echo "<option value='0'>Seleccione su región</option>";
        while ($fila = sqLsrv_fetch_array($resultados)) {
          $fila = mb_convert_encoding($fila, "UTF-8", "iso-8859-1");
          $CodigoRegion = $fila["CodigoRegion"];
          $NombreRegion = $fila["NombreRegion"];
          echo "<option value='" . $CodigoRegion . "'>" . utf8_decode($NombreRegion) . "</option>";
        }
        echo "</optgroup>";
      } catch (Exception $e) {
        echo $e->getMessage();
      }
      break;
    case 2: // OBTENER COMUNAS
      $idRegion = $_POST["idRegion"];
      include_once get_template_directory() . '/inscripcionpatvirtual/Jason/ObtenerDataJason.php';
      try {
        $data = array();
        $instancia_lfiltro = new ObtenerDataJason();
        $resultados = $instancia_lfiltro->Lista_Comunas($idRegion);
        echo "<option value='0'>Seleccione su comuna</option>";
        while ($fila = sqLsrv_fetch_array($resultados)) {
          $fila = mb_convert_encoding($fila, "UTF-8", "iso-8859-1");
          $CodigoComuna = $fila["CodigoComuna"];
          $NombreComuna = $fila["NombreComuna"];
          echo "<option value='" . $CodigoComuna . "'>" . utf8_decode($NombreComuna) . "</option>";
        }
        echo "</optgroup>";
      } catch (Exception $e) {
        echo $e->getMessage();
      }
      break;
    case 3: // COMPROBAR USUARIO EN MU
      $identificacionUsuario = $_POST["ident"];
      $tipoIdent = $_POST["tipoIdent"];
      include_once get_template_directory() . '/inscripcionpatvirtual/BO/MaestroUsuarios.php';
      $instanciaMaestro = new MaestroUsuarios();
      try {
        $data = array();
        if ($tipoIdent == "P") {
          $usuario = $instanciaMaestro->verificaMaestroPasaporte($identificacionUsuario);
        } else {
          $usuario = $instanciaMaestro->verificaMaestro($identificacionUsuario);
        }
        if ($usuario) {
          echo "true";
        } else {
          if (username_exists($identificacionUsuario)) {
            echo "valida";
          } else {
            echo "false";
          }
        }
      } catch (Exception $e) {
        echo $e->getMessage();
      }
      break;
    case 4: // COMPROBAR CORREO EN MU
      $correo = $_POST["correo"];
      $tipoIdent = $_POST["tipoIdent"];
      include_once get_template_directory() . '/inscripcionpatvirtual/BO/MaestroUsuarios.php';
      $instanciaMaestro = new MaestroUsuarios();
      try {
        $data = array();
        $usuario = $instanciaMaestro->verificaMaestroCorreo($correo);
        if ($usuario) {
          echo "true";
        } else {
          echo "false";
        }
      } catch (Exception $e) {
        echo $e->getMessage();
      }
      break;
    case 5: // OBTENER PAIS
      include_once get_template_directory() . '/inscripcionpatvirtual/Jason/ObtenerDataJason.php';
      try {
        $data = array();
        $instancia_lfiltro = new ObtenerDataJason();
        //$id = $_POST["id"];
        $resultados = $instancia_lfiltro->Lista_Paises();
        echo "<option value='0'>Seleccione su pais</option>";
        while ($fila = sqLsrv_fetch_array($resultados)) {
          $fila = mb_convert_encoding($fila, "UTF-8", "iso-8859-1");
          $CodigoPais = $fila["CodigoPais"];
          $NombrePais = $fila["NombrePais"];
          echo "<option value='" . $CodigoPais . "'>" . utf8_decode($NombrePais) . "</option>";
        }
        echo "</optgroup>";
      } catch (Exception $e) {
        echo $e->getMessage();
      }
      break;
    case 6: //OBTENER FORMULARIO 
      $formHtml = GenerarFormHTML();
      echo $formHtml;
      break;
  }
  wp_die();
}
function delete_user_by_username($username)
{

  //Include the user file with the user administration API
  require_once(ABSPATH . 'wp-admin/includes/user.php');

  //Include the file with the pluggable functions, which includes the get_user_by() function
  require_once(ABSPATH . 'wp-includes/pluggable.php');

  $user = get_user_by('login', $username);

  return wp_delete_user($user->ID);
}

function verificaFechaMaestro($identificacion, $fecha, $esPasaporte)
{
  $resultado = false;
  include_once get_template_directory() . '/inscripcionpatvirtual/BO/MaestroUsuarios.php';
  $instanciaMaestro = new MaestroUsuarios();
  if ($instanciaMaestro->verificaMaestroFecha($identificacion, $fecha, $esPasaporte)) {
    $resultado = true;
  }
  return $resultado;
}
function cambiaContraMaestro($identificacion, $esPasaporte, $pass, $repass, $oldpassword)
{
  $resultado = false;
  // VERIFICA QUE EL HASH Y ID VENGAN CORRECTOS
  $idenHash = md5($_POST["identificacion"]);
  if ($idenHash == $_POST['idenh']) {
    // VERIFICA QUE LAS CONTRASEÑAS COINCIDAN
    if ($pass == $repass) {
      include_once get_template_directory() . '/inscripcionpatvirtual/BO/MaestroUsuarios.php';
      $instanciaMaestro = new MaestroUsuarios();
      $resultado = $instanciaMaestro->Modificar_Clave_Usuario($identificacion, $pass, $esPasaporte, $oldpassword);
    }
  }
  return $resultado;
}
function enviaCambioMailUsuario() {}
function cambiaMailUsuario($identificacion, $esPasaporte, $mailUsuario)
{
  $resultado = false;
  include_once get_template_directory() . '/inscripcionpatvirtual/BO/MaestroUsuarios.php';
  $instanciaMaestro = new MaestroUsuarios();
  if ($instanciaMaestro->Modificar_Mail_Usuario($identificacion, $esPasaporte, $mailUsuario)) {
    $user = get_user_by('login', $identificacion);
    $user_data = wp_update_user(array('ID' => $user->ID, 'user_email' => $mailUsuario));
    $resultado = update_user_meta($user->ID, 'mail_persona', $mailUsuario);
    $resultado = update_user_meta($user->ID, 'mail_persona_temp', 0);
    $resultado = true;
  }
  return $resultado;
}

function send_email_validaCorreo($identificacion, $esPasaporte, $mailUsuario)
{
  $user = get_user_by('login', $identificacion);
  $resultado = update_user_meta($user->ID, 'mail_persona_temp', $mailUsuario);
  $md5Cod = md5($mailUsuario);
  $url = site_url() . '/valida-correo?cval=' . $md5Cod . "&ciden=" . $identificacion . "&cPas=" . $esPasaporte;
  $subject = 'Favor valida tu nuevo correo en Portal Patromonio Virtual';
  $body = file_get_contents(TEMPLATEPATH . '/emailtemplateValida.php');
  $body = str_replace("{{texto}}", $url, $body);
  $headers = array('Content-Type: text/html; charset=UTF-8');
  $sent = wp_mail($mailUsuario, $subject, $body, $headers);
  $sent = true;
  return $sent;
}
function send_email_Inscripcion($correo, $msge)
{

  $subject = 'Favor valida tu cuenta en Portal Patromonio Virtual';
  $body = file_get_contents(TEMPLATEPATH . '/emailtemplateInscrito.php');

  $body = str_replace("{{texto}}", $msge, $body);
  $headers = array('Content-Type: text/html; charset=UTF-8');
  $sent = wp_mail($correo, $subject, $body, $headers);
}


function GenerarFormHTML()
{
  $formHtml = '<form id="new_post" name="new_post" method="post" action="#" enctype="multipart/form-data">
  <div class="field_testimonio "><label>RUT o Pasaporte: <span style="color: red;">&nbsp *</span></label><br />
    <div class="row">
      <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 pr-0">
        <select id="tipoIden_persona" name="tipoIden_persona" class="selectRutPasaporte">
          <option value="R" selected>RUT</option>
          <option value="P">Pasaporte / DNI</option>
        </select>
      </div>
      <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10 pl-0">
        <input type="text" id="rut_persona" name="rut_persona" placeholder="Escribe tu RUT">
      </div>
    </div>


    <div class="field_testimonio"><label>Nombres: <span style="color: red;">&nbsp *</span></label><br />
      <input type="text" id="nombre_persona" name="nombre_persona" placeholder="Escribe tu nombre" />
      
    </div>
    <div class="field_testimonio"><label>Apellido Paterno: <span style="color: red;">&nbsp *</span></label><br />
      <input type="text" id="apellido_paterno" name="apellido_paterno" placeholder="Escribe tu apellido paterno " />
    </div>
    <div class="field_testimonio"><label>Apellido Materno: </label><br />
      <input type="text" id="apellido_materno" name="apellido_materno" placeholder="Escribe tu apellido materno" />
    </div>
    <div class="field_testimonio sexo-contenedor" id="sexo-contenedor"><label>Sexo <span style="color: red;">&nbsp *</span></label><br />
      <select id="genero_persona" name="genero_persona" class="selectTestimonio">
        <option value="0" selected>Selecciona tu sexo</option>
        <option value="M" >Masculino</option>
        <option value="F" >Femenino</option>
      </select>
    </div>
    <div class="field_testimonio"><label>Fecha de nacimiento <span style="color: red;">&nbsp *</span></label><br />
      <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" max=' .  date('Y-m-d') . ' />
    </div>
    <div class="field_testimonio  d-none contenedor-codNacionalidad-div">
      <label>Nacionalidad<span style="color: red;">&nbsp *</span></label><br />
      <select  id="codNacionalidad" name="codNacionalidad" class="selectTestimonio">
          <option value="" selected>Selecciona tu nacionalidad</option>
          <option value="C" >Chileno</option>
          <option value="N" >Nacionalizado</option>
          <option value="E" >Extanjero</option>
      </select>
    </div>
    <div class="field_testimonio paisesOrigen-div" id="paisesOrigen-div"><label>País de origen <span style="color: red;">&nbsp *</span></label><br />
      <select id="paisorigen_persona" name="paisorigen_persona" class="selectTestimonio">
        <option value="0" selected>Seleccione su país</option>
        <option value="0">Cargando paises</option>
      </select>
    </div>
    <div class="field_testimonio divPaises"><label>País de residencia <span style="color: red;">&nbsp *</span></label><br />
      <select id="pais_persona" name="pais_persona" class="selectTestimonio">
        <option value="0" selected>Seleccione su país</option>
        <option value="0">Cargando paises</option>
      </select>
    </div>
    <div class="field_testimonio divRegiones" id="divRegiones"><label>Región de residencia <span style="color: red;">&nbsp *</span></label><br />
      <select id="region_persona" name="region_persona" class="selectTestimonio">
        <option value="0" selected>Seleccione su región</option>
        <option value="0">Cargando regiones</option>
      </select>
    </div>

    <div class="divComuna" id="divComuna">
      <div class="field_testimonio"><label>Cómuna de residencia <span style="color: red;">&nbsp *</span></label><br />
        <select id="comuna_persona" name="comuna_persona" class="selectTestimonio">
          <option value="0" selected>Seleccione su cómuna</option>
          <option value="0">Cargando cómunas</option>
        </select>
      </div>
    </div>

    <div class="field_testimonio"><label>Correo electrónico: <span style="color: red;">&nbsp *</span></label><br />
      <input type="mail" id="mail_persona" name="mail_persona" placeholder="Escribe tu correo electrónico" />
    </div>

    <div class="field_testimonio"><label>Repite tu correo electrónico: <span style="color: red;">&nbsp *</span></label><br />
      <input type="mail" id="mail_persona_repite" name="mail_persona_repite" placeholder="Escribe nuevamente tu correo electrónico" />
      <div class="CU_mensaje_Mail mensaje-invalido labelInvalido" style="color:red" id="CU_mensaje_Mail"></div>
    </div>

    <div class="field_testimonio"><label>Contraseña: <span style="color: red;">&nbsp *</span></label><br />
      <input type="password" id="contrasena_persona" name="contrasena_persona" placeholder="Escribe tu contraseña" minLength="4" maxLength="12"/>
    </div>

    <div class="field_testimonio"><label>Repite tu contraseña: <span style="color: red;">&nbsp *</span></label><br />
      <input type="password" id="recontrasena_persona" name="recontrasena_persona" placeholder="Escribe nuevamente tu contraseña" minLength="4" maxLength="12"/>
      <div class="CU_mensaje_Pass mensaje-invalido labelInvalido" style="color:red" id="CU_mensaje_Pass"></div>
    </div>

    <div class="field_testimonio row" style="margin-left: 0px;">
      <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8" style="text-align: left;font-size: 16px;padding-left: 0px;">
        <label class="containerCheckTest">Acepto los <a target="_blank" href="https://www.biblioredes.gob.cl/terminos-y-condiciones">
            Términos y Condiciones de Uso</a> de Portal Patrimonio Virtual<span style="color: red;">&nbsp *</span>
          <input type="checkbox" id="checkacepto" name="checkacepto">
          <span class="checkmark" id="checkTermMark" value="checkTermMark"></span>
        </label>
      </div>
    </div>

    <div class="field_testimonio row" style="margin-left: 0px;">
      <div class="field_testimonio col-xs-8 col-sm-8 col-md-8 col-lg-8" style="text-align: left;font-size: 16px;padding-left: 0px;">
        (*) Campos obligatorios
      </div>
      <div class="pl-lg-0 col-xs-12 col-sm-12 col-md-2 col-lg-2">
        <button id="btnBorrarRegistro" type="button" class="btnBorrarTest btn">Limpia</button>
      </div>
      <div class="pr-lg-0 col-xs-12 col-sm-12 col-md-2 col-lg-2">
        <input type="submit" id="btnEnviarTest" class="btnEnviarTest btn" value="Envía">
      </div>
    </div>

    <input type="hidden" name="action" value="" id="actionFormSubmit" />
</form>';
  return $formHtml;
}

function enqueue_recorridos_script()
{
  try {
    if (!is_user_logged_in()) {
      return;
    }

    global $post;
    $user_id = get_current_user_id();
    $all_meta_for_user = get_user_meta($user_id);

    $rut_persona = isset($all_meta_for_user["rut_persona"][0]) ? $all_meta_for_user["rut_persona"][0] : '';
    $partes = explode('-', $rut_persona);
    $rut_limpio = $partes[0] ?? '';

    $tipo_identificacion = isset($all_meta_for_user["tipo_identificacion"][0]) ? $all_meta_for_user["tipo_identificacion"][0] : '';
    $numero_tipo = ($tipo_identificacion === 'R') ? 1 : 2;

    if ($post instanceof WP_Post) {
      $url_recorrido = trailingslashit($post->guid) . $post->post_name;
    }

    $post_id = isset($post->ID) ? $post->ID : null;

    $categories = get_the_category($post_id);
    $post_type_prefix = '';
    $tipo_recorrido = '';
    if (!empty($categories) && !is_wp_error($categories)) {
      $post_type_prefix = $categories[0]->name;
      $tipo_recorrido = ($post_type_prefix === 'recorridos-sonoros') ? 'Recorrido Sonoro' : (($post_type_prefix === 'Recorridos Virtuales') ? 'Recorrido Virtual' : '');
    } else {
      $tipo_recorrido = 'Sin definir';
    }
    if (!empty($categories) && !is_wp_error($categories)) {
      $post_type_prefix = $categories[0]->name;
    } else {
      $post_type_prefix = '';
    }

    $urlDirectory = get_template_directory();
    require_once $urlDirectory . '/inscripcionpatvirtual/models/SRVEncrypt.php'; // ajusta ruta según corresponda
    $encrypt = new SRVEncrypt();

    $secret_key = defined('CLAVE_SECRETA_ENCRIPTACION') ? CLAVE_SECRETA_ENCRIPTACION : '';
    $isseker = array();

    $isseker['SRVcodigoUsuario'] = isset($all_meta_for_user["codigo_usuario"][0])
      ? (int) $all_meta_for_user["codigo_usuario"][0]
      : 0; // o null según lo que necesites

    $isseker['SRVficha'] = $post_id;
    $isseker['SRVorigenusuario'] = $numero_tipo;
    $isseker['SRVtipovisita'] = $tipo_recorrido;
    $isseker['url'] = $url_recorrido;
    $isseker['SRVservicio'] = 49;

    $datos_encriptados = $encrypt->_cl_encrypt_api_data($isseker, $secret_key);

    return $datos_encriptados;
  } catch (Exception $e) {
    error_log('Error detallado: ' . print_r($e, true)); // Guarda en wp-content/debug.log
    echo '<pre>';
    var_dump($e); // Muestra en pantalla (solo en entornos locales)
    echo '</pre>';
    wp_die(); // Detiene la ejecución
  }
}
add_action('wp_enqueue_scripts', 'enqueue_recorridos_script');
/**
 * Permitir correos duplicados en WordPress.
 * Este hook se ejecuta antes de crear o actualizar el usuario.
 */
add_filter('pre_user_email', function ($user_email) {
    // Si el correo ya existe en la base de datos, crear un alias temporal único
    if (email_exists($user_email)) {
        $alias = 'duplicado_' . time() . '_' . wp_generate_password(4, false);

        // Mantener el dominio original si tiene formato válido
        if (strpos($user_email, '@') !== false) {
            list($local, $domain) = explode('@', $user_email, 2);
            $user_email = $alias . '@' . $domain;
        } else {
            // Si el correo no tiene dominio, usar uno genérico local
            $user_email = $alias . '@noemail.local';
        }

        // Registrar en log para control
        error_log("[Duplicado Email] Original: {$local}@{$domain} -> Guardado como {$user_email}");
    }

    return $user_email;
}, 10, 1);



/**
 * Crea un nuevo usuario con metadatos personalizados.
 */
function new_user_with_metadata($persona, $codValidacion)
{
  // Preparar login: sanitizar rut_persona (quitar guiones, puntos, espacios)
  $username = $persona['rut_persona'] ?? '';

  // Definir campos meta
  $meta = array(
    'rut_persona'         => $persona['rut_persona'] ?? '',
    'tipo_identificacion' => $persona['tipo_identificacion'] ?? '',
    'nombre_persona'      => $persona['nombre_persona'] ?? '',
    'first_name'          => $persona['nombre_persona'] ?? '',
    'last_name'           => trim(($persona['apellido_paterno'] ?? '') . ' ' . ($persona['apellido_materno'] ?? '')),
    'apellido_paterno'    => $persona['apellido_paterno'] ?? '',
    'apellido_materno'    => $persona['apellido_materno'] ?? '',
    'fecha_nacimiento'    => $persona['fecha_nacimiento'] ?? '',
    'sexo_persona'        => $persona['sexo_persona'] ?? '',
    'region_persona'      => $persona['region_persona'] ?? '',
    'comuna_persona'      => $persona['comuna_persona'] ?? '',
    'codigo_nacionalidad' => $persona['codigo_nacionalidad'] ?? '',
    'pais_persona'        => $persona['pais_persona'] ?? '',
    'paisorigen_persona'  => $persona['paisorigen_persona'] ?? '',
    'mail_persona'        => $persona['mail_persona'] ?? '',
    'contrasena_persona'  => $persona['contrasena_persona'] ?? '',
    'validado_persona'    => $codValidacion,
    'codigo_usuario'      => $persona['codigo_usuario'] ?? '',
  );

  // Crear usuario con password aleatorio
  $password = wp_generate_password(12, true);
  $email = $persona['mail_persona'] ?? '';

  $user_id = wp_create_user($username, $password, $email);

  if (is_wp_error($user_id)) {
    $error_code = $user_id->get_error_code();

    // Si ocurre cualquier error distinto a duplicado
    if ($error_code === 'empty_user_login') {
      error_log('empty_user_login: username usado: ' . $username);
      return null;
    } else {
      error_log('Error al crear usuario WP: ' . $user_id->get_error_message());
      return null;
    }
  }

  // Guardar metadatos
  $user_id_int = (int) $user_id;
  foreach ($meta as $key => $val) {
    update_user_meta($user_id_int, $key, $val);
  }

  return $user_id_int;
}



/**
 * Actualiza usuario existente con metadatos.
 */
function update_user_with_metadata($persona)
{
  // Buscar usuario por login
  $user = get_user_by('login', $persona['rut_persona'] ?? '');
  if (!$user) {
    error_log('Usuario no encontrado para actualizar: ' . ($persona['rut_persona'] ?? ''));
    return null;
  }

  // Actualizar datos básicos
  wp_update_user(array(
    'ID' => $user->ID,
    'user_email' => $persona['mail_persona'] ?? $user->user_email,
    'display_name' => trim(($persona['nombre_persona'] ?? '') . ' ' . ($persona['apellido_paterno'] ?? '')),
  ));

  // Actualizar metadatos
  $meta = array(
    'rut_persona'         => $persona['rut_persona'] ?? '',
    'tipo_identificacion' => $persona['tipo_identificacion'] ?? '',
    'nombre_persona'      => $persona['nombre_persona'] ?? '',
    'first_name'          => $persona['nombre_persona'] ?? '',
    'last_name'           => trim(($persona['apellido_paterno'] ?? '') . ' ' . ($persona['apellido_materno'] ?? '')),
    'apellido_paterno'    => $persona['apellido_paterno'] ?? '',
    'apellido_materno'    => $persona['apellido_materno'] ?? '',
    'fecha_nacimiento'    => $persona['fecha_nacimiento'] ?? '',
    'sexo_persona'        => $persona['sexo_persona'] ?? '',
    'region_persona'      => $persona['region_persona'] ?? '',
    'comuna_persona'      => $persona['comuna_persona'] ?? '',
    'mail_persona'        => $persona['mail_persona'] ?? '',
    'contrasena_persona'  => $persona['contrasena_persona'] ?? '',
    'validado_persona'    => 0,
    'codigo_nacionalidad' => $persona['codigo_nacionalidad'] ?? '',
    'pais_persona'        => $persona['pais_persona'] ?? '',
    'paisorigen_persona'  => $persona['paisorigen_persona'] ?? '',
    'codigo_usuario'      => $persona['codigo_usuario'] ?? '',
  );

  foreach ($meta as $key => $val) {
    update_user_meta($user->ID, $key, $val);
  }

  return $user;
}


function loginUsuarioWordPress($identificacionUsuario, $claveUsuario, $esCU)
{
  include_once get_template_directory() . '/inscripcionpatvirtual/BO/MaestroUsuarios.php';

  $instanciaMaestro = new MaestroUsuarios();
  $esPasaporte = isset($_POST["pasaporte"]);

  // Verificar usuario en el maestro
  $usuario = $esPasaporte
    ? $instanciaMaestro->verificaMaestroPasaporte($identificacionUsuario)
    : $instanciaMaestro->verificaMaestro($identificacionUsuario);

  if (!$usuario) {
    // Usuario no encontrado en el maestro
    handleUsuarioNoEncontrado($identificacionUsuario);
    return false;
  }

  // Verificar clave
  $resultadoClave = $esCU || $instanciaMaestro->verificaMaestroClave($identificacionUsuario, $claveUsuario, $esPasaporte);
  if (!$resultadoClave) return false;

  // Preparar datos del usuario
  $persona = prepararDatosUsuario($usuario, $esPasaporte);

  if (username_exists($identificacionUsuario)) {
    // Usuario existe en WordPress
    update_user_with_metadata($persona);
    $instanciaMaestro->CrearUsuarioRegistro($usuario->CodigoUsuario, $esPasaporte);
    return iniciarSesionUsuario($identificacionUsuario);
  } else {
    // Crear usuario en WordPress
    $codValidacion = rand();
    new_user_with_metadata($persona, $codValidacion);
    if (username_exists($persona['rut_persona'])) {
      return iniciarSesionUsuario($identificacionUsuario);
    }
  }

  return false;
}

// ----------------------------
// Manejo de usuario no encontrado
// ----------------------------
function handleUsuarioNoEncontrado($identificacionUsuario)
{
  if ($user = get_user_by('login', $identificacionUsuario)) {
    $all_meta_for_user = get_user_meta($user->ID);
    if (!empty($all_meta_for_user["validado_persona"]) && $all_meta_for_user["validado_persona"][0] != 0) {
      $_POST["faltaValidar"] = 1;
    }
  }
}

// ----------------------------
// Iniciar sesión programáticamente
// ----------------------------
function iniciarSesionUsuario($identificacionUsuario)
{
  if (!is_user_logged_in()) {
    add_filter('authenticate', 'allow_programmatic_login', 10, 3);
    $user = wp_signon([
      'user_login' => $identificacionUsuario,
      'remember'   => true
    ]);
    remove_filter('authenticate', 'allow_programmatic_login', 10, 3);

    if (is_wp_error($user)) {
      wp_safe_redirect(site_url('/inicio-sesion'));
      exit;
    }

    // Login correcto: establecer usuario y cookies
    wp_set_current_user($user->ID, $user->user_login);
    wp_set_auth_cookie($user->ID, true, false);

    // Contador de sesión
    if (!session_id()) {
      session_start();
    }
    $_SESSION['contador'] = 3;
  }

  // Redirección segura al perfil
  wp_safe_redirect(site_url('/mi-perfil'));
  exit;
}

// ----------------------------
// Preparar datos del usuario
// ----------------------------
function prepararDatosUsuario($usuario, bool $esPasaporte): array
{
  // Convertir a array si es stdClass (para trabajar de forma uniforme)
  if (is_object($usuario)) {
    $usuario = json_decode(json_encode($usuario), true);
  }

  // Validar y preparar fecha de nacimiento
  $fechaNacimiento = null;
  if (!empty($usuario['FechaNacimiento'])) {
    try {
      // Reemplazar la "T" por espacio si viene en formato ISO
      $fecha = str_replace('T', ' ', $usuario['FechaNacimiento']);
      $fechaNacimiento = new DateTime($fecha);
    } catch (Exception $e) {
      $fechaNacimiento = null;
    }
  }

  // Obtener comuna y región (desde el nivel superior o subarray)
  $comuna = $usuario['CodigoComuna'] ?? ($usuario['Comunas']['CodigoComuna'] ?? '');
  $region = $usuario['CodigoRegion'] ?? ($usuario['Comunas']['CodigoRegion'] ?? '');

  // Obtener sexo (M / F / Otro)
  $sexo = $usuario['Sexo']['CodigoSexo'] ?? '';

  // Obtener país de residencia o nombre desde subarray (si existiera)
  $paisResidencia = $usuario['CodigoPaisResidencia'] ?? ($usuario['PaisRecidencia']['CodigoPais'] ?? '');

  // Retornar array listo para creación de usuario
  return [
    'codigo_usuario'        => $usuario['CodigoUsuario'] ?? '',
    'tipo_identificacion'   => $esPasaporte ? "P" : "R",
    'rut_persona'           => $usuario['Identidad'] ?? '',
    'nombre_persona'        => $usuario['Nombre'] ?? '',
    'apellido_paterno'      => $usuario['ApellidoPaterno'] ?? '',
    'apellido_materno'      => $usuario['ApellidoMaterno'] ?? '',
    'fecha_nacimiento'      => $fechaNacimiento ? $fechaNacimiento->format('d-m-Y') : null,
    'sexo_persona'          => $sexo,
    'region_persona'        => $region,
    'comuna_persona'        => $comuna,
    'codigo_nacionalidad'   => $usuario['CodigoNacionalidad'] ?? ($usuario['Nacionalidad']['CodigoNacionalidad'] ?? ''),
    'pais_persona'          => $paisResidencia,
    'paisorigen_persona'    => $usuario['CodigoPaisOrigen'] ?? '',
    'mail_persona'          => $usuario['Correo'] ?? '',
    'contrasena_persona'    => '', // se puede completar luego
  ];
}

// ----------------------------
// Verificar token
// ----------------------------
function verificarToken($token, $claveSecreta)
{
  if (strpos($token, '.') === false) return false;

  list($base64Payload, $firmaRecibida) = explode('.', $token, 2);

  // Recalcular firma
  $firmaCalculada = base64_encode(hash_hmac('sha256', $base64Payload, $claveSecreta, true));

  if (hash_equals($firmaCalculada, $firmaRecibida)) {
    $jsonPayload = base64_decode($base64Payload);
    $objeto = json_decode($jsonPayload, true);
    return $objeto;
  }

  return false;
}

/**
 * Genera un token encodeado compatible con verificarToken
 *
 * @param array $datos Array de datos a enviar
 * @param string $claveSecreta Clave secreta para firmar
 * @return string Token encodeado
 */
function generarToken($datos, $claveSecreta)
{
  // Convertir el array a JSON
  $jsonPayload = json_encode($datos);

  // Base64 del payload
  $base64Payload = base64_encode($jsonPayload);

  // Calcular firma HMAC
  $firma = base64_encode(hash_hmac('sha256', $base64Payload, $claveSecreta, true));

  // Unir payload + firma
  $token = $base64Payload . '.' . $firma;

  return $token;
}

// ----------------------------
// Redirección personalizada
// ----------------------------
add_action('template_redirect', 'mi_redireccion_personalizada');
function mi_redireccion_personalizada()
{
  $x = get_query_var('wp_patrimoniovirtual_ruc');

  // Login vía token
  if (isset($_GET['retorno'])) {
    $clave = "mi_clave_secreta_123";
    $token = $_GET['retorno'];
    $resultado = verificarToken($token, $clave);

    if ($resultado) {
      $usuario = (object) $resultado['usuario_vm'];
      $identificacionUsuario = $usuario->Identidad;
      $esPasaporte = !str_contains($identificacionUsuario, '-');

      $persona = prepararDatosUsuario($usuario, $esPasaporte);

      // Usuario existente
      if (username_exists($identificacionUsuario)) {
        update_user_with_metadata($persona);
        return iniciarSesionUsuario($identificacionUsuario);
        exit;
      } else {
        // Usuario nuevo
        $codValidacion = rand();
        new_user_with_metadata($persona, $codValidacion);

        if (username_exists($persona['rut_persona'])) {
          return iniciarSesionUsuario($persona['rut_persona']);
          exit;
        }
      }
    } else {
      wp_safe_redirect(site_url('/'));
      exit;
    }
    wp_safe_redirect(site_url('/'));
    exit;
  } else if (isset($_GET['mensaje']) && $_GET['mensaje'] === "Cierre de sesión correcto") {
    wp_logout();
    exit;
  }
}
/**
 * Redirige al portal CentralRUC según acción y entorno.
 *
 * @param int $accion La acción a ejecutar: 1=Iniciar, 2=Registrarse, 3=Editar, 6=Cerrar
 */
function redirigir_a_centralruc($accion) {
    // Detectar entorno usando HTTP_HOST
    $host = $_SERVER['HTTP_HOST'] ?? '';
    $is_local = strpos($host, 'local') !== false || strpos($host, 'localhost') !== false;

    // Definir portal según entorno
    $portal = $is_local ? '02ppvlocal' : '02ppv';

    // Definir URLs base según acción
    $urls = [
        1 => "https://pbrwebqa-08.biblioredes.gob.cl/centralruc?portal={$portal}&accion=1", // Iniciar sesión
        2 => "https://pbrwebqa-08.biblioredes.gob.cl/centralruc?portal={$portal}&accion=2", // Registrarse
        3 => "https://pbrwebqa-08.biblioredes.gob.cl/centralruc?portal={$portal}&accion=3", // Editar datos
        6 => "https://pbrwebqa-08.biblioredes.gob.cl/centralruc?portal={$portal}&accion=6", // Cerrar sesión
    ];

    // Si la acción no existe, usar 6 por defecto
    $url = $urls[$accion] ?? $urls[6];

    // Redirigir (antes de enviar HTML)
    wp_redirect($url);
    exit;
}



add_action('admin_post_nopriv_redirigir_centralruc', function () {
  $accion = isset($_GET['accion']) ? intval($_GET['accion']) : 6;
  redirigir_a_centralruc($accion);
});

add_action('admin_post_redirigir_centralruc', function () {
  $accion = isset($_GET['accion']) ? intval($_GET['accion']) : 6;
  redirigir_a_centralruc($accion);
});

//https://tusitio.com/wp-json/api/v1/retorno?retorno=TOKEN 

add_action('rest_api_init', function () {
    register_rest_route('api/v1', '/retorno', [
        'methods'  => WP_REST_Server::READABLE, // SOLO GET
        'callback' => 'handle_retorno_request',
        'permission_callback' => '__return_true'
    ]);
});

function handle_retorno_request(WP_REST_Request $request) {

    // Recibir parámetro GET
    $token = $request->get_param('retorno');

    if (empty($token)) {
        wp_safe_redirect(site_url('/'));
        exit;
    }

    $clave = "mi_clave_secreta_123";

    // Verificamos el token
    $resultado = verificarToken($token, $clave);

    if (!$resultado) {
        wp_safe_redirect(site_url('/'));
        exit;
    }

    // Extraemos usuario
    $usuario = (object) $resultado['usuario_vm'];
    $identificacionUsuario = $usuario->Identidad;

    // Saber si es pasaporte
    $esPasaporte = !str_contains($identificacionUsuario, '-');

    // Normalizar datos
    $persona = prepararDatosUsuario($usuario, $esPasaporte);

    // Si existe → actualizar y loguear
    if (username_exists($identificacionUsuario)) {
        update_user_with_metadata($persona);
        return iniciarSesionUsuario($identificacionUsuario);
    }

    // Si no existe → redirige
    wp_safe_redirect(site_url('/'));
    exit;
}
