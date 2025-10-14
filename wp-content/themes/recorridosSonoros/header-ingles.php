<!-- Fix Google translate -->
<html translate="no" <?php language_attributes(); ?>>
<head>
  <!-- Required meta tags -->
  <meta name="google" content="notranslate" />
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Recorre virtualmente distintos museos y disfruta del patrimonio cultural">
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
  <!-- Custom CSS -->
  <link rel="stylesheet" href="<?php echo get_bloginfo('template_directory'); ?>/style.css">
  <!-- Favicon -->
  <link rel="shortcut icon" type="image/png" href="<?php bloginfo('template_directory'); ?>/img/favicon.png" />
  <title>
    <?php if (is_author()) { ?><?php bloginfo('name'); ?> | Archivo por autor<?php } ?>
    <?php if (is_month()) { ?><?php bloginfo('name'); ?> | Archivo por Mes | <?php the_time('F'); ?><?php } ?>
    <?php if (is_search()) { ?><?php bloginfo('name'); ?> | Resultados<?php } ?>
    <?php if (function_exists('is_tag')) {
      if (is_tag()) { ?><?php bloginfo('name'); ?> | Archivo por Tag | <?php single_tag_title("", true);}} ?>
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
  <link href="https://kit-free.fontawesome.com/releases/latest/css/free-v4-shims.min.css" media="all" rel="stylesheet">
  <link href="https://kit-free.fontawesome.com/releases/latest/css/free-v4-font-face.min.css" media="all" rel="stylesheet">
  <link href="https://kit-free.fontawesome.com/releases/latest/css/free.min.css" media="all" rel="stylesheet">
  <script src="<?php bloginfo('template_directory'); ?>/js/jquery-3.4.1.js"></script>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-7537278-19"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-7537278-19');
</script>
</head>

<body>
  <header>
    <div id="header-principal" data-menu-actual="opc_mnu_recorridos_virtuales">
      <!-- HEADER DESKTOP-->
      <div class="container-max container-header d-none d-lg-block">
        <div class="row">
          <div class="col-lg-6">
            <div class="row container-logo">
              <div class="col-lg-5">
                <img src="<?php echo get_bloginfo('template_directory'); ?>/img/logo-solo.svg" alt="Patrimonio virtual" class="img-fluid logo">
              </div>
              <div class="col-lg-7 d-flex align-items-end">
                <a href="/en/patrimonio-virtual-english/" style="text-decoration:none;">
                  <div class="complemento-logo">Virtual heritage</div>
                </a>
              </div>
            </div>
          </div>
          <div class="col-lg-4 d-flex align-items-center justify-content-center">
            <div class="container-search">
              <div class="input-group md-form form-sm form-2 pl-0 ">
                <!--Bug Fix 2221 !-->
                <input id="buscador" class="form-control my-auto py-1 search-box" type="text" placeholder="Search..." aria-label="Search">
                <div class="input-group-append">
                  <span class="input-group-text lighten-3" id="search" onclick="searchPost(event)"><i class="fas fa-search text-grey" aria-hidden="true"></i></span>
                </div>
              </div>
              <!--Bug Fix 2221 !-->
            </div>
          </div>
          <div class="col-lg-2 d-flex align-items-center justify-content-end">
            <div class="justify-content-start">
              <div class="container-select-language md-form form-sm form-2 pl-0">
                <select id="LangSelector" style="border:0px; outline:0px;" onchange="onChangeLang(this.value)">
                  <option id="ES" value="ES">Es</option>
                  <option id="EN" value="EN">En</option>
                  <option id="FR" value="FR">Fr</option>
                  <option id="PR" value="PR">Pr</option>
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
                <img src="<?php echo get_bloginfo('template_directory'); ?>/img/logo-solo.svg" alt="Patrimonio virtual" class="img-fluid logo">
              </div>
              <div class="col-7 d-flex align-items-end">
                <a href="/en/patrimonio-virtual-english/" style="text-decoration:none;">
                  <div class="complemento-logo">Virtual heritage</div>
                </a>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-10  d-flex align-items-center justify-content-start">
            <div class="container-search">
              <!--Bug Fix 2221 !-->
              <div class="input-group md-form form-sm form-2 pl-0 ">
                <input id="buscador-mob" class="form-control my-auto py-1 search-box" type="text" placeholder="Search..." aria-label="Search">
                <div class="input-group-append">
                  <span class="input-group-text lighten-3" id="search2" onclick="searchPostMobile(event)"><i class="fas fa-search text-grey" aria-hidden="true"></i></span>
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
                  <option id="EN" value="EN">En</option>
                  <option id="FR" value="FR">Fr</option>
                  <option id="PR" value="PR">Pr</option>
                </select>
              </div>
              <div id="language-list" class="d-none">
                <ul>
                  <?php pll_the_languages(); ?>
                </ul>
              </div>
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
                  'items_wrap' => '<ul class="navbar-nav d-flex align-items-start">%3$s</ul>',
                  'theme_location' => 'menu_principal'
                  )); ?>
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
                  )); ?>
      </nav>
    </div>
  </header>
