    <?php /* Template Name: HOME FRANCES */ ?>
    <?php get_header( 'frances'); ?>
    <style>
    #testvid {
    position: relative;
    background-color: black;
    height: 23.688rem;
    min-height: 25rem;
    width: 100%;
    overflow: hidden;
    }

    #testvid video {
    position: absolute;
    top: 50%;
    left: 50%;
    min-width: 100%;
    min-height: 100%;
    width: auto;
    height: auto;
    z-index: 0;
    -ms-transform: translateX(-50%) translateY(-52%);
    -moz-transform: translateX(-50%) translateY(-52%);
    -webkit-transform: translateX(-50%) translateY(-52%);
    transform: translateX(-50%) translateY(-52%);
    }
    #testvid .overlay {
    position: absolute;
    top: 0;
    left: 0;
    height: 100%;
    width: 100%;
    background-color: black;
    opacity: 0.2;
    z-index: 1;
    }

    #testvid .container {
    position: relative;
    z-index: 2;
    }
</style>
    <!-- CONTENIDO -->
    <div id="main">
      <section id="description-home">
        <?php if (have_posts()) : while (have_posts()) : the_post();?>
        <!-- div class="container-fluid">
          <div class="row">
              <div class="col-12 p-0">                
                <?php if ( has_post_thumbnail() ) {the_post_thumbnail('full', array('class' => 'img-fluid' )); } ?>
              </div>
            </div>
        </div -->
        <div id="header-principal-banner-vid" data-url-banner-pincipal="/img/Limarigrande@2x.jpg">
            <div id="testvid">
                <div class="overlay"></div>
                <video playsinline="playsinline" autoplay="autoplay" muted="muted" loop="loop">
                    <source src="<?php echo get_field('video'); ?>" type="video/mp4">
                </video>
            </div>
         </div>
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
      <section id="catalogo-museos">
        <div class="container-fluid catalogo-museos">
          <div class="container">
            <div class="row container-titulo">
              <div class="col-12">
                <h1>Visites Virtuelles</h1>
              </div>
            </div>
            <div class="row imagenes-catalogo">
              <?php query_posts(array('post_type' => 'post','lang' => 'fr','category_name' => 'visites-virtuelles','posts_per_page' => 3)); 
              $postCount=0;
              while ( have_posts() ) : the_post();  $postCount++;?>
                <div class="col-12 col-sm-6 col-lg-4 <?php if($postCount>3): echo "post-hidden"; endif;  ?>" <?php if($postCount>3): echo "style='display:none;'"; endif;  ?>>
                  <a href="<?php echo get_permalink(); ?>">
                    <?php if ( has_post_thumbnail() ) {the_post_thumbnail('full', array('class' => 'img-fluid' )); } ?>
                    <h3 class="subtitulo2"><?php the_title(); ?></h3>
                  </a>
                </div>
              <?php endwhile; wp_reset_query(); ?>
            </div>
            <div class="row d-flex justify-content-center contenedor-boton-central" style="padding-top: 1.6875rem; padding-bottom: 3rem;">
              <div class="col-12  text-center boton-central">
                <a href="/visites-virtuelles/" style="color: white !important;">Voir toutes les visites virtuelles</a>
              </div>        
            </div>
          </div>
        </div>
      </section>
      <section id="como-recorrer" style="background-color: #fff;">
        <div class="container ">
          <div class="row container-titulo">
            <div class="col-12">
                <h1>Visites sonores</h1>
            </div>
          </div>  
          <div class="row imagenes-catalogo icono">
            <?php query_posts(array('post_type' => 'post','lang' => 'fr','posts_per_page' => 3,'category_name' => 'visites-sonores-frances')); 
            while ( have_posts() ) : the_post(); ?>
            <div class="col-12 col-sm-6 col-lg-4">
              <a href="<?php echo get_permalink(); ?>">
                <?php if ( has_post_thumbnail() ) {the_post_thumbnail('full', array('class' => 'img-fluid' )); } ?>
                <h3 class="subtitulo2"><?php the_title(); ?></h3>
              </a>
            </div>
            <?php endwhile; wp_reset_query(); ?>
          </div>
          <div class="row d-flex justify-content-center contenedor-boton-central" style="padding-top: 1.6875rem; padding-bottom: 3rem;">
            <div class="col-12  text-center boton-central">
              <a href="/fr/visites-sonores/" style="color: white !important;">Voir toutes les visites sonores</a>
            </div>        
          </div>
        </div>
      </section>
      <?php query_posts(array('post_type' => 'eventos','lang' => 'fr','posts_per_page' => -1));?>
      <?php if (0 && (have_posts())) :  ?>   
            <section id="participa" style="background-color: #dedede;">
              <div class="container main-description">
                <div class="row container-titulo">
                    <div class="col-12">
                        <h1>Participe</h1>
                    </div>
                </div>
                <div class="row banners flex-wrap">
                <?php while ( have_posts() ) : the_post();   ?>
                  <?php $boton = get_field( "boton" ); ?>
                 
                      <div class="col-12 col-md-6 col-lg-4 pb-5 d-flex flex-column justify-content-between">
                          <img class="w-100" src="<?php echo get_the_post_thumbnail_url(); ?>" alt="">
                          <div class="titulo py-2"><b><?php echo get_the_title(); ?></b></div>
                          <a class="btn btn-dark w-100" href="<?php echo empty($boton['link'])?'#':$boton['link']; ?>"><?php echo $boton['texto'] ?></a>
                      </div>
                  
                <?php endwhile; wp_reset_query(); ?>
                </div>
              </div>
            </section>
      <?php endif; ?>
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

            $('#catalogo-museos .contenedor-boton-central').click(function(){
              $('#catalogo-museos .post-hidden').slideToggle()
            })

          });
        </script>
      </div>
    </div>
    <?php get_footer( 'frances'); ?>
