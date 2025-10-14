<?php
/*
 * Template Name: Recorridos Virtuales (eng)
 * Template Post Type: page
 */
?>
<?php get_header('ingles'); ?>
<?php    
        
    $comoRecorrerID = get_field( "como_recorrer" );       
    
    $paso1 = get_field( "paso1" , $comoRecorrerID[0]);
    $paso2 = get_field( "paso2" , $comoRecorrerID[0]);
    $paso3 = get_field( "paso3" , $comoRecorrerID[0]);

    $eventos = get_field( "participa" );

?>
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
<!-- div id="header-principal-banner-vid" data-url-banner-pincipal="/img/Limarigrande@2x.jpg">
    <div id="testvid">
        <div class="overlay"></div>
        <video playsinline="playsinline" autoplay="autoplay" muted="muted" loop="loop">
            <source src="<?php echo get_field('video'); ?>" type="video/mp4">
        </video>
    </div>
</div -->
<div id="main">
    <section id="description-home">
        <div class="container main-description">
            <div class="row container-titulo">
                <div class="col-12">
                    <h1><?php the_title(); ?></h1>
                </div>
            </div>
            <div class="row description-text">
                <div class="col-12">
                    <p><?php the_content(); ?></p>
                </div>
            </div>    
        </div>
    </section>
     <a id="catalogo">&nbsp;</a> 
     <section id="catalogo-museos">
        <div class="container-fluid catalogo-museos">
          <div class="container">
            <div class="row container-titulo">
              <div class="col-12">
                <h1>Catálogo</h1>
              </div>
            </div>
            <div class="row imagenes-catalogo">
              <?php query_posts(array('category_name'=>'recorridos-virtuales','posts_per_page' => -1) ); 
              $postCount=0;
              while ( have_posts() ) : the_post();  $postCount++;?>
                <div class="col-12 col-sm-6 col-lg-4 " >
                  <i class="fas fa-vr-cardboard position-absolute"></i>
                  <a href="<?php echo get_permalink(); ?>">
                    <?php if ( has_post_thumbnail() ) {the_post_thumbnail('full', array('class' => 'img-fluid' )); } ?>
                    <h3 class="subtitulo2"><?php the_title(); ?></h3>
                  </a>
                </div>
              <?php endwhile; wp_reset_query(); ?>
            </div>
          </div>
        </div>
      </section>
      <?php if(!empty($paso1['descripcion'])): ?>
            <section id="como-recorrer">
                <div class="container ">
                    <div class="row container-titulo">
                        <div class="col-12">
                            <!--h1><?php //the_title(); ?></h1-->
                            <h1>¿Cómo navegar en el recorrido virtual?</h1>
                        </div>
                    </div>  
                    <div class="row seccion-pasos">          
                        <div class="col-12 col-sm-4 pasos">
                            <img src="<?php echo $paso1['imagen']; ?>"  class="img-fluid">
                            <h3 class="subtitulo3"><?php echo $paso1['titulo']; ?></h3>
                            <p>
                                <?php echo $paso1['descripcion']; ?>
                            </p>
                        </div>  
                        <div class="col-12 col-sm-4 pasos">
                            <img src="<?php echo $paso2['imagen']; ?>"  class="img-fluid">
                            <h3 class="subtitulo3"><?php echo $paso2['titulo']; ?></h3>
                            <p>
                                <?php echo $paso2['descripcion']; ?>
                            </p>
                        </div>  
                        <div class="col-12 col-sm-4 pasos">
                            <img src="<?php echo $paso3['imagen']; ?>" class="img-fluid">
                            <h3 class="subtitulo3"><?php echo $paso3['titulo']; ?></h3>
                            <p>
                                <?php echo $paso3['descripcion']; ?>
                            </p>
                        </div>  
                    </div>
                </div>
            </section>
      <?php endif; ?>
      <?php query_posts(array('post_type' => 'eventos','lang' => 'pt','posts_per_page' => -1));?>
        <?php
        
        if (0 && (have_posts())) :  ?>   
            <section id="participa" style="background-color: #dedede;">
                <div class="container main-description">
                <div class="row container-titulo">
                    <div class="col-12">
                        <h1>Participates</h1>
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
      <div id="ir-arriba" style=""><div class="container">
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
</script></div>
    </div>
<script>
    $( document ).ready(function() {
        $('#banner-principal').addClass("banner1")
    })
</script>
<?php get_footer('ingles'); ?>
