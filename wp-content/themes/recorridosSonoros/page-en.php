<?php
/*
 * Template Name: Page (en)
 * Template Post Type: page
 */
?>
    <?php get_header('ingles'); ?>
    <!-- CONTENIDO PAGE.PHP -->
    <div id="main">
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
    <?php get_footer('ingles'); ?>