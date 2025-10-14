<?php
/*
 * Template Name: Preguntas frecuentes (Español)
 * Template Post Type: page
 */
?>
<?php get_header(); ?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    <?php    
        
        $preguntas = get_field( "preguntas" );       
        $preguntasSonoros = get_field( "preguntas_recorridos_sonoros" );
        
        $comoRecorrerID = get_field( "como_recorrer" );       
        
        $paso1 = get_field( "paso1" , $comoRecorrerID[0]);
        $paso2 = get_field( "paso2" , $comoRecorrerID[0]);
        $paso3 = get_field( "paso3" , $comoRecorrerID[0]);

        $opciones = get_field( "opciones" );
        $titulo_opciones = get_field( "titulo_opciones" );
    ?>
    <div id="main">
        <section id="contenido-preg-frec"><div class=" container d-block preguntas-frecuentes-container">
            <div class="row container-titulo">
                <div class="col-12">
                    <h1><?php the_title(); ?></h1>
                </div>
            </div>

            <div id="preguntas-frecuentes-tab">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link active" id="tab-virtuales" aria-current="page" data-toggle="tab" href="" data-pane="#1a" role="tab" aria-controls="virtuales" aria-selected="true">Recorridos virtuales</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" id="tab-sonoros" href="" data-pane="#2a" role="tab" aria-controls="sonoros" aria-selected="false">Recorridos sonoros</a>
                    </li>
                </ul>

                <div class="tab-content clearfix">
                    <div class="tab-pane fade show active" id="1a" role="tabpanel" aria-labelledby="tab-virtuales">
                        <div class="accordion" id="preguntas-frecuentes">
                            <?php $count =0; ?>
                            <?php foreach($preguntas as $pregunta): $count++; ?>
                                <div class="card">
                                    <div class="card-header" id="heading-<?php echo $count; ?>">
                                        <h5 class="mb-0">
                                        <button class="btn btn-link w-100 d-flex justify-content-between" type="button" data-toggle="collapse" data-target="#collapse-<?php echo $count; ?>" aria-expanded="true" aria-controls="collapseOneB">
                                            <?php echo $count.'. '.$pregunta['titulo'] ?><span class="fas fa-chevron-down align-self-center icon-preguntas" aria-hidden="true"></span>
                                        </button>
                                        </h5>
                                    </div>
                                    <div id="collapse-<?php echo $count; ?>" class="collapse" aria-labelledby="heading-<?php echo $count; ?>" data-parent="#preguntas-frecuentes">
                                        <div class="card-body">
                                            <?php echo $pregunta['respuesta'] ?>
                                        </div>
                                    </div>
                                </div>
                            <?php  endforeach; ?>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="2a" role="tabpanel" aria-labelledby="tab-sonoros">
                    <div class="accordion" id="preguntas-frecuentes-sonoros">
                            <?php $count =0; ?>
                            <?php foreach($preguntasSonoros as $pregunta): $count++; ?>
                                <div class="card">
                                    <div class="card-header" id="heading-sonoros-<?php echo $count; ?>">
                                        <h5 class="mb-0">
                                        <button class="btn btn-link w-100 d-flex justify-content-between" type="button" data-toggle="collapse" data-target="#collapse-sonoros-<?php echo $count; ?>" aria-expanded="true" aria-controls="collapseOneB">
                                            <?php echo $count.'. '.$pregunta['titulo'] ?><span class="fas fa-chevron-down align-self-center icon-preguntas" aria-hidden="true"></span>
                                        </button>
                                        </h5>
                                    </div>
                                    <div id="collapse-sonoros-<?php echo $count; ?>" class="collapse" aria-labelledby="heading-sonoros-<?php echo $count; ?>" data-parent="#preguntas-frecuentes-sonoros">
                                        <div class="card-body">
                                            <?php echo $pregunta['respuesta'] ?>
                                        </div>
                                    </div>
                                </div>
                            <?php  endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                $( document ).ready(function() {
                    $('#preguntas-frecuentes .card-header').click(function(){

                        let targetIcon="#"+$(this).attr('id')+" .fa-chevron-down"
                        let targetspan="#"+$(this).attr('id')+" span"

                        if(!$(targetspan).hasClass('pregunta-abierta')){
                            $('#preguntas-frecuentes .fa-chevron-down').removeClass('pregunta-abierta')
                        }
            
                        $(targetIcon).toggleClass('pregunta-abierta')

                        
                    
                    })
                })
            </script>
        </section>
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
