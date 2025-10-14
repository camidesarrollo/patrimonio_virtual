    <?php /* Template Name: recorridos */ ?>
    <?php get_header(); ?>
    <!-- CONTENIDO RECORRIDOS.PHP -->
    <div id="main">
      <section id="description-home">
        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
            <div class="container main-description">
              <div class="row container-titulo">
                <div class="col-12">
                  <h1><?php the_title(); ?></h1>
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
      <section id="catalogo-museos">
        <div class="container-fluid catalogo-museos">
          <div class="container">
            <div class="row container-titulo">
              <div class="col-12">
                <h1>Catálogo</h1>
                <p class="bajada">Selecciona tu museo para escuchar su audioguía</p>
              </div>
            </div>
            <div class="row imagenes-catalogo icono">
              <?php query_posts('category_name=recorridos-sonoros');
              while (have_posts()) : the_post(); ?>
                <div class="col-12 col-sm-6 col-lg-4">
                  <a href="<?php echo get_permalink(); ?>">
                    <?php if (has_post_thumbnail()) {
                      the_post_thumbnail('full', array('class' => 'img-fluid'));
                    } ?>
                    <h3 class="subtitulo2"><?php the_title(); ?></h3>
                  </a>
                </div>
              <?php endwhile;
              wp_reset_query(); ?>
            </div>
          </div>
        </div>
      </section>

      <div id="ir-arriba" style="">
        <div class="container">
          <a class="flotante" href="#"><i class="fas fa-chevron-up ir-arriba" aria-hidden="true"></i></a>
        </div>
        <script>
          /**Bug Fix 
           * Limpieza cache storage "click boton"
           */

          if (window.localStorage.getItem("estabaPresionado")) {
            window.localStorage.removeItem("estabaPresionado");
          }

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
