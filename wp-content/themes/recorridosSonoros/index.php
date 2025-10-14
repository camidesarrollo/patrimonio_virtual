    <?php /* Template Name: inicio */ ?>
    <?php get_header(); ?>
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
    <!-- Modal -->
    <!-- Se agregó la clase dynamic-modal -->
    <div class="modal fade dynamic-modal" id="modalEducacion" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                    <div class="modal-content">
            <button type="button" class="btn-close btn-close-white btnCerrarModal" data-bs-dismiss="modal" aria-label="Close"></button>
            <!-- Se crea el iframe y se le coloca el id que le corresponde en data.json -->
          <iframe id="devppv"  width="100%" ></iframe>
                    </div>
            </div>
    </div>
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

<?php
$video = get_field('video');
$thumbnail = get_the_post_thumbnail_url() ?: "/img/Limarigrande@2x.jpg"; // Use featured image or fallback
?>
        <?php if ($video): ?>
<div id="header-principal-banner-vid" data-url-banner-principal="<?= $video ?: $thumbnail ?>">
    <div id="testvid">
        <div class="overlay"></div>
            <video playsinline="playsinline" autoplay="autoplay" muted="muted" loop="loop">
                <source src="<?= $video ?>" type="video/mp4">
            </video>
    </div>
</div>
        <?php else: ?>
<div id="header-principal-banner-vid" style="height:23rem;" data-url-banner-principal="<?= $video ?: $thumbnail ?>">
    <div id="testvid" style="height:unset;">
        <div class="overlay" style="background-color:unset;"></div>
            <img src="<?= $thumbnail ?>" alt="Fallback Image" style="margin-right:auto;margin-left:auto;width:100%;height:100%;object-fit:cover;object-position:center;">
    </div>
</div>
        <?php endif; ?>


        <!-- div id="header-principal-banner-vid" data-url-banner-pincipal="/img/Limarigrande@2x.jpg">
            <div id="testvid">
                <div class="overlay"></div>
                <video playsinline="playsinline" autoplay="autoplay" muted="muted" loop="loop">
                    <source src="<?php echo get_field('video'); ?>" type="video/mp4">
                </video>
            </div>
        </div -->
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
                <h1>Recorridos Virtuales</h1>
              </div>
            </div>
            <div class="row imagenes-catalogo">
              <?php query_posts(array('category_name'=>'recorridos-virtuales','posts_per_page' => 3) ); 
              $postCount=0;
              while ( have_posts() ) : the_post();  $postCount++;?>
                <div class="col-12 col-sm-6 col-lg-4 <?php if($postCount>3): echo "post-hidden"; endif;  ?>" <?php if($postCount>3): echo "style='display:none;'"; endif;  ?>>
                  <i class="fas fa-vr-cardboard position-absolute"></i>
                  <a href="<?php echo get_permalink(); ?>">
                    <?php if ( has_post_thumbnail() ) {the_post_thumbnail('full', array('class' => 'img-fluid' )); } ?>
                    <h3 class="subtitulo2"><?php the_title(); ?></h3>
                  </a>
                </div>
              <?php endwhile; wp_reset_query(); ?>
            </div>

            <div class="row d-flex justify-content-center contenedor-boton-central" style="padding-top: 1.6875rem; padding-bottom: 3rem;">
              <div class="col-12  text-center boton-central">
                <a href="/recorridos/" style="color: white !important;">Ver todos los recorridos virtuales</a>
              </div>        
            </div>

          </div>
        </div>
      </section>
      <section id="como-recorrer" style="background-color: #fff;">
        <div class="container ">
          <div class="row container-titulo">
            <div class="col-12">
                <h1>Recorridos sonoros</h1>
            </div>
          </div>  
          <div class="row imagenes-catalogo icono">
            <?php query_posts('category_name=recorridos-sonoros&posts_per_page=3' ); 
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
              <a href="/recorridos-sonoros-2/" style="color: white !important;">Ver todos los recorridos sonoros</a>
            </div>        
          </div>
        </div>
      </section>
      <?php query_posts(array('post_type' => 'eventos','posts_per_page' => -1));?>
      <?php
       /* $eventos = get_posts(array(
            'numberposts'    => -1,
            'post_type' => 'Eventos',
        ));*/
        
        if (1 && (have_posts())) :  ?>   
            <section id="participa" style="background-color: #dedede;">
              <div class="container main-description">
                <div class="row container-titulo">
                    <div class="col-12" style="display:none;">
                        <h1>Participa</h1>
                    </div>
                </div>
                <div class="row banners flex-wrap">
                <?php while ( have_posts() ) : the_post();   ?>
                  <?php $boton = get_field( "boton" ); ?>
                 
                      <div class="col-12 col-md-6 col-lg-4 pb-5 d-flex flex-column justify-content-between">
                          <img class="w-100" src="<?php echo get_the_post_thumbnail_url(); ?>" alt="">
                          <div class="titulo py-2"><b><?php echo get_the_title(); ?></b></div>
                          <a class="btn btn-dark w-100" href="<?php echo empty($boton['link'])?'#':$boton['link']; ?>" target="_blank"><?php echo $boton['texto'] ?></a>
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
  jQuery(function($) {
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
     });
        </script>
      </div>
    </div>
    <?php get_footer(); ?>
