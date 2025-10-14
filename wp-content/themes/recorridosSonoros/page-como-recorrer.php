<?php
/*
 * Template Name: Como recorrer
 * Template Post Type: page
 */
?>
<?php get_header(); ?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    <?php    
        
        $comoRecorrerID = get_field( "como_recorrer" );       
        
        $paso1 = get_field( "paso1" , $comoRecorrerID[0]);
        $paso2 = get_field( "paso2" , $comoRecorrerID[0]);
        $paso3 = get_field( "paso3" , $comoRecorrerID[0]);

        $opciones = get_field( "opciones" );
        $titulo_opciones = get_field( "titulo_opciones" );

    ?>
    <div id="main">
        <!-- Cómo recorrer -->
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
        <?php if(!empty($opciones)): ?>
            <section id="opciones" style="background-color: #EEEEEE;">
                <div class="container ">
                    <div class="row container-titulo">
                        <div class="col-12">
                            <h1><?php echo $titulo_opciones; ?></h1>
                        </div>
                    </div>
                    <div class="row">
                        <?php  foreach($opciones as $opcion): ?>
                            <div class="col-lg-4">
                                <div class="opcion-para-recorrer w-100 text-left">
                                    <div class="opcion-reco">
                                        <?php echo $opcion['titulo'] ?>
                                    </div>
                                </div>
                                <div class="opcion-para-recorrer-desc w-100 text-left">
                                    <div class="descripcion-opcion">
                                        <?php echo $opcion['descripcion'] ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="row d-flex justify-content-center contenedor-boton-central" style="padding-top: 1.6875rem; padding-bottom: 3rem;">
                        <div class="col-12  text-center boton-central" onclick="window.location='/recorridos/';">
                            Ir a recorridos
                        </div>        
                    </div>
                </div>
            </section>
        <?php endif; ?>
        <div class="py-4"></div>
        <section id="como-escuchar" style="background-color: #fff;">
        <div class="container ">
          <div class="row container-titulo">
            <div class="col-12">
              <h1>¿Cómo escuchar los recorridos sonoros?</h1>
            </div>
          </div>  
          <div class="row seccion-pasos">
            <?php query_posts('category_name=como-escuchar&posts_per_page=2' ); while ( have_posts() ) : the_post(); ?>
            <div class="col-12 col-sm-6 pasos">
              <?php if ( has_post_thumbnail() ) {the_post_thumbnail('full', array('class' => 'img-fluid' )); } ?>
              <h3 class="subtitulo3"><?php the_title(); ?></h3>
              <?php the_content(); ?>
            </div>
            <?php endwhile; wp_reset_query(); ?>
          </div>
        </div>
      </section>
    </div>
<?php endwhile; endif; ?>
<?php get_footer(); ?>
