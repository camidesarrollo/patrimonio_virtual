<?php if ($_GET["action"] == "logout") { wp_logout(); } ?>
<?php get_header(); ?>
<!-- CONTENIDO PAGE.PHP -->
<div id="main">
  <section id="description-home">
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <div class="container main-description">
          <div class="row container-titulo">
            <div class="col-12">
              <h1><?php the_title(); ?></h1>
              <div class="texto-Inicio-Session">
                Te invitamos a explorar cada uno de estos recorridos ingresando con tu Clave Única, Rut o pasaporte.
              </div>
              <?php if (isset($_GET["errorLogin"]) && $_GET["errorLogin"] == 3) { ?>
                <div class="w-100 errorBox" style="margin-bottom: 2%;display: block;">
                  <i class="fa fa-info-circle" aria-hidden="true"></i> No se ha podido iniciar sesión con clave única, favor registrate en la plataforma para continuar
                </div>
              <?php } ?>
              <div class="row">
                <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12 pb">
                  <div class="divMarco">
                    <p class="textoMarco">Ingresa con Clave Única</p>
                    <a class="btn-cu btn-m btn-color-estandar" href="http://10.83.216.138:8182/cxf/clave-unica/v1/authorize?state=90C5D7&id=436948">
                    <!-- a class="btn-cu btn-m btn-color-estandar" href="https://fuse.patrimoniocultural.gob.cl/cxf/clave-unica/v1/authorize?state=90C5D7&id=496344" -->
                      <span class=""><img src="<?php echo get_site_url(); ?>/wp-content/uploads/2022/08/claveunica.svg"></span>
                      <span class="texto">Iniciar sesión</span>
                    </a>
                  </div>
                </div>
                <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12 pb">
                  <div class="divMarco">
                    <p class="textoMarco">Inicia sesión con RUT</p>
                    <a href="<?php echo get_site_url(); ?>/inicia-sesion">
                      <input type="button" class='btn button btnRut more-link ' value="Ingresa con RUT">
                    </a>
                  </div>
                </div>
                <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12 pb">
                  <div class="divMarco">
                    <p class="textoMarco">Inicia sesión con Pasaporte / DNI</p>
                    <a href="<?php echo get_site_url(); ?>/inicia-sesion/?pasaporte=1">
                      <input type="button" class='btn button btnPasaporte more-link' value="Ingresa con Pasaporte / DNI">
                    </a>
                  </div>
                </div>
              </div>

              <div class="text-Secundario-Sesion">
                Si aún no te has registrado puedes <a style="text-decoration: underline !important;" href="<?php echo get_site_url(); ?>/registrate">crear tu cuenta aquí.</a>
              </div>
            </div>
          </div>
          <div class="row description-text">
            <div class="col-12">
              <?php the_content(); ?>
            </div>
          </div>
        </div>
    <?php endwhile;
    endif; ?>
  </section>
  <a id="catalogo">&nbsp;</a>
  <div id="ir-arriba" style="">
    <div class="container">
      <a class="flotante" href="#"><i class="fas fa-chevron-up ir-arriba" aria-hidden="true"></i></a>
    </div>
    <script>
      $(document).ready(function() {

        $(window).scroll(function() {
          if ($(this).scrollTop() > 0) {
            $('#ir-arriba').slideDown(300);
          } else {
            $('#ir-arriba').slideUp(300);
          }
        });

      });
    </script>
  </div>
</div>
<?php get_footer(); ?>
