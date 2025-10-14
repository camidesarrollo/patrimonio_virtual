    
    <?php 
    /*
 * Template Name: Quienes somos (fr)
 * Template Post Type: page
 */
    
    get_header('frances'); ?>
    <!-- CONTENIDO PAGE.PHP -->
    <div id="main">
      <div id="header-quienes-banner" data-url-banner-pincipal="https://www.youtube.com/embed/38Gkl7vV7Wo?controls=0&amp;autoplay=1&amp;mute=1"><div>
      <!-- div id="banner-principal" class="banner banner2"></div -->
      <section id="description-home">
        <?php if (have_posts()) : while (have_posts()) : the_post();?>
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
              <div class="col-12 d-flex justify-content-center contenedor-boton-central" style="padding-top: 1.6875rem; padding-bottom: 3rem;">
                <div class="col-12  text-center boton-central">
                  <a href="/como-recorrer/" style="color: white !important;">Comment visiter les musées ?</a>
                </div>        
            </div>
          </div>
        </div>
        <?php endwhile; endif; ?>

      </section>
      <a id="catalogo">&nbsp;</a> 
      <div id="ir-arriba" style="">
        <div class="container">
          <a class="flotante" href="#"><i class="fas fa-chevron-up ir-arriba" aria-hidden="true"></i></a>
        </div>
        <script>
          $( document ).ready(function() {

            $(window).scroll(function(){
              if( $(this).scrollTop() > 0 ){
                $('#ir-arriba').slideDown(300);
              } else {
                $('#ir-arriba').slideUp(300);
              }
            });

          });
        </script>
      </div>
    </div>
    <?php get_footer('frances'); ?>
