<?php
$facebookMetas = get_field('facebook-metas');
$pageUrl = get_permalink();
/*
$pageTitle = $facebookMetas['titulo'];
$pageDesc = $facebookMetas['descripcion'];
$pageImg = $facebookMetas['imagen'];
*/

$pageTitle = isset($facebookMetas['titulo']) ? $facebookMetas['titulo'] : 'Patrimonio Virtual';
$pageDesc  = isset($facebookMetas['descripcion']) ? $facebookMetas['descripcion'] : 'Recorre virtualmente distintos museos y disfruta del patrimonio cultural';
$pageImg   = isset($facebookMetas['imagen']) ? $facebookMetas['imagen'] : 'https://www.patrimoniovirtual.gob.cl/wp-content/themes/recorridosSonoros/img/biblioredes.png';

?>

<!-- Fix Google translate -->
<html translate="no" <?php language_attributes(); ?>>

<head>
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-XX"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-XX');
</script>

  <!-- Required meta tags -->
  <meta name="google" content="notranslate" />
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Recorre virtualmente distintos museos y disfruta del patrimonio cultural">

  <!-- Metas para facebook -->

  <?php if (!empty($pageTitle) && !empty($pageDesc) && !empty($pageImg)) : ?>
    <meta property="og:url" content="<?php echo $pageUrl; ?>" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="<?php echo $pageTitle; ?>" />
    <meta property="og:description" content="<?php echo $pageDesc; ?>" />
    <meta property="og:image" content="<?php echo $pageImg; ?>" />
  <?php endif; ?>

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
  <!-- Custom CSS -->
  <link rel="stylesheet" href="<?php echo get_bloginfo('template_directory'); ?>/style.css">
  <link rel="stylesheet" href="<?php echo get_bloginfo('template_directory'); ?>/css/modal.css">
  <!-- Favicon -->
  <link rel="shortcut icon" type="image/png" href="<?php bloginfo('template_directory'); ?>/img/favicon.png" />
  <title>
    <?php if (is_author()) { ?><?php bloginfo('name'); ?> | Archivo por autor<?php } ?>
    <?php if (is_month()) { ?><?php bloginfo('name'); ?> | Archivo por Mes | <?php the_time('F'); ?><?php } ?>
    <?php if (is_search()) { ?><?php bloginfo('name'); ?> | Resultados<?php } ?>
    <?php if (function_exists('is_tag')) {
      if (is_tag()) { ?><?php bloginfo('name'); ?> | Archivo por Tag | <?php single_tag_title("", true);} } ?>

<?php if (is_home()) {
  echo bloginfo('name');
  echo ' | ';
  bloginfo('description');
} elseif (is_category()) {
  single_cat_title();
  echo ' | ';
  echo bloginfo('name');
} elseif (is_single() || is_page()) {
  single_post_title();
  echo ' | ';
  echo bloginfo('name');
} else {
  wp_title('', true);
} ?>
  </title>
  <?php wp_head(); ?>
  <!--link href="https://kit-free.fontawesome.com/releases/latest/css/free-v4-shims.min.css" media="all" rel="stylesheet">
  <link href="https://kit-free.fontawesome.com/releases/latest/css/free-v4-font-face.min.css" media="all" rel="stylesheet">
  <link href="https://kit-free.fontawesome.com/releases/latest/css/free.min.css" media="all" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet"-->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

  <script src="<?php bloginfo('template_directory'); ?>/js/jquery-3.5.1.js"></script>
  <!-- script src="<?php bloginfo('template_directory'); ?>/js/jquery-3.4.1.js"></script -->
</head>

<body>
  <header>
    <div id="header-principal" data-menu-actual="opc_mnu_recorridos_virtuales">

      <!-- HEADER DESKTOP-->
      <div class="container-max container-header d-none d-lg-block">
        <div class="row">
          <div class="col-lg-5">
            <div class="row container-logo">
              <div class="col-lg-6 pr-0">
                <img src="<?php echo get_bloginfo('template_directory'); ?>/img/logo_biblioredes_new.svg" alt="Patrimonio virtual" class="img-fluid logo">
              </div>
              <div class="col-lg-6 d-flex align-items-end">
                <a href="/" style="text-decoration:none;">
                  <div class="complemento-logo">Patrimonio virtual</div>
                </a>
              </div>
            </div>
          </div>
          <div class="col-lg-3 d-flex align-items-center justify-content-center">
            <div class="container-search">
              <div class="input-group md-form form-sm form-2 pl-0 ">
                <!--Bug Fix 2221 !-->
                <input id="buscador" class="form-control my-auto py-1 search-box" type="text" placeholder="Buscador..." aria-label="Buscador">
                <div class="input-group-append">
                  <span class="input-group-text lighten-3" id="search" onclick="searchPost(event)">
                  <svg class="svg-inline--fa fa-search fa-w-16 text-grey" style="width: 16px;height: 16px;"  aria-hidden="true" focusable="false" data-prefix="fas" data-icon="search" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M505 442.7L405.3 343c-4.5-4.5-10.6-7-17-7H372c27.6-35.3 44-79.7 44-128C416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c48.3 0 92.7-16.4 128-44v16.3c0 6.4 2.5 12.5 7 17l99.7 99.7c9.4 9.4 24.6 9.4 33.9 0l28.3-28.3c9.4-9.4 9.4-24.6.1-34zM208 336c-70.7 0-128-57.2-128-128 0-70.7 57.2-128 128-128 70.7 0 128 57.2 128 128 0 70.7-57.2 128-128 128z"></path></svg>
                        <!--i class="fas fa-search text-grey" aria-hidden="true"></i-->
                      
                  </span>
                </div>
              </div>
              <!--Bug Fix 2221 !-->
            </div>
          </div>
          <div class="col-lg-3 d-flex align-items-center justify-content-end">
            <div class="justify-content-start linksHeader" style="">
              <?php
              if (is_user_logged_in()) { ?>
                <a href="<?php echo site_url() . "/mi-perfil" ?>">Mi perfil</a> | <a href="/ingreso-pat-virtual?action=logout">Cerrar sesión</a>
              <?php } else { ?>
                <a href="<?php echo site_url() . "/ingreso-pat-virtual" ?>">Inicia sesión</a> | <a href="<?php echo site_url() . "/registrate" ?>">Regístrate</a>
              <?php }
              ?>
            </div>
          </div>
          <div class="col-lg-1 d-flex align-items-center justify-content-end">
            <div class="justify-content-start">
              <div class="container-select-language md-form form-sm form-2 pl-0">
                <select id="LangSelector" style="border:0px; outline:0px;" onchange="onChangeLang(this.value)">
                  <option id="ES" value="ES">Es</option>
                  <!-- option id="EN" value="EN">En</option>
                  <option id="FR" value="FR">Fr</option>
                  <option id="PR" value="PR">Pr</option -->
                </select>
              </div>
              <div id="language-list" class="d-none">
                <ul><?php pll_the_languages(); ?></ul>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- HEADER MOBILE-->
      <div class="container-header d-block d-lg-none">
        <div class="row">
          <div class="col-12">
            <div class="row container-logo">
              <div class="col-5 ">
                <img src="<?php echo get_bloginfo('template_directory'); ?>/img/logo_biblioredes_new.svg" alt="Patrimonio virtual" class="img-fluid logo">
              </div>
              <div class="col-7 d-flex align-items-end">
                <a href="/" style="text-decoration:none;">
                  <div class="complemento-logo">Patrimonio virtual</div>
                </a>
              </div>
            </div>
          </div>
        </div>
        
        <div class="row pt-4">
          <div class="col-10  d-flex align-items-center justify-content-start">
            <div class="container-search">
              <!--Bug Fix 2221 !-->
              <div class="input-group md-form form-sm form-2 pl-0 ">
                <input id="buscador-mob" class="form-control my-auto py-1 search-box" type="text" placeholder="Buscador..." aria-label="Buscador">
                <div class="input-group-append">
                  <span class="input-group-text lighten-3" id="search2" onclick="searchPostMobile(event)">
                  <svg class="svg-inline--fa fa-search fa-w-16 text-grey" style="width: 16px;height: 16px;" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="search" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M505 442.7L405.3 343c-4.5-4.5-10.6-7-17-7H372c27.6-35.3 44-79.7 44-128C416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c48.3 0 92.7-16.4 128-44v16.3c0 6.4 2.5 12.5 7 17l99.7 99.7c9.4 9.4 24.6 9.4 33.9 0l28.3-28.3c9.4-9.4 9.4-24.6.1-34zM208 336c-70.7 0-128-57.2-128-128 0-70.7 57.2-128 128-128 70.7 0 128 57.2 128 128 0 70.7-57.2 128-128 128z"></path></svg>
                    <!--i class="fas fa-search text-grey" aria-hidden="true"></i-->
                  </span>
                </div>
              </div>
              <!--Bug Fix 2221 !-->
            </div>
          </div>




          <div class="col-2 d-flex align-items-center justify-content-end">
            <div class="justify-content-start">
              <div class="container-select-language md-form form-sm form-2 pl-0">
                <select id="LangSelector" style="border:0px; outline:0px;" onchange="onChangeLang(this.value)">
                  <option id="ES" value="ES">Es</option>
                  <!-- option id="EN" value="EN">En</option>
                  <option id="FR" value="FR">Fr</option>
                  <option id="PR" value="PR">Pr</option -->
                </select>
              </div>
              <!-- Se corrige problema de no llamado a la funcion poly language para carga correcta de menu superior, en caso de cambio de lenguaje -->
              <div id="language-list" class="d-none">
                <ul><?php pll_the_languages(); ?></ul>
              </div>
            </div>
          </div>
        </div>
        <div class="row pb-3 pt-3">
          <div class="col-12">
            <div class="justify-content-start linksHeader" style="">
              <?php
              if (is_user_logged_in()) { ?>
                <a href="<?php echo site_url() . "/mi-perfil" ?>">Mi perfil</a> | <a href="/ingreso-pat-virtual?action=logout">Cerrar sesión</a>
              <?php } else { ?>
                <a href="<?php echo site_url() . "/ingreso-pat-virtual" ?>">Inicia sesión</a> | <a href="<?php echo site_url() . "/registrate" ?>">Regístrate</a>
              <?php }
              ?>
            </div>
          </div>
        </div>

      </div>
      <!--  MENU DESKTOP-->
      <div class="container-fluid container-fluid-nav">
        <div class="container-menu navbar navbar-expand-md navbar-dark bg-dark mb-4" role="navigation">
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <div class="col nav-bar-col">
              <?php wp_nav_menu(
                array(
                  'container' => false,
                  'items_wrap' => '<ul class="navbar-nav d-flex align-items-start justify-content-around">%3$s</ul>',
                  'theme_location' => 'menu_principal'
                )
              ); ?>
            </div>
          </div>
        </div>
      </div>
      <!-- MENU MOBILE -->
      <div id="menu-mobile">
        <nav class="d-md-none">
          <?php wp_nav_menu(
            array(
              'container' => false,
              'items_wrap' => '<ul class="nav-cell menu-mov">%3$s</ul>',
              'theme_location' => 'menu_principal'
            )
          ); ?>
        </nav>
      </div>
  </header>
