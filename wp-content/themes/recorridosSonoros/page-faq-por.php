<?php
/*
 * Template Name: Preguntas frecuentes (Portugues)
 * Template Post Type: page
 */
?>
<?php get_header('portugues'); ?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    <?php    
        
        $preguntas = get_field( "preguntas" );       
        

    ?>
    <div id="main">
        <section id="contenido-preg-frec"><div class=" container d-block preguntas-frecuentes-container">
            <div class="row container-titulo">
                <div class="col-12">
                    <h1><?php the_title(); ?></h1>
                </div>
            </div>
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
    </div>
<?php endwhile; endif; ?>
<?php get_footer('portugues'); ?>