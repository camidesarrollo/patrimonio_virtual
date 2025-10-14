<?php
/*
 * Template Name: Recorrido virtual (Inglés)
 * Template Post Type: post
 */
?>
<?php get_header(); ?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    <?php 
        $url_recorrido = get_field( "url_del_recorrido" );
        $permalink= get_the_permalink();
        $texto_twitter = get_field( "texto_twitter" );
        $img_mobile = get_field( "imagen_mobile" );
        
        $url_facebook = "https://www.facebook.com/sharer/sharer.php?u=".$permalink;  
        $url_twitter ="https://twitter.com/intent/tweet?text=".urlencode($texto_twitter).$permalink;
        
        $comoRecorrerID = get_field( "como_recorrer" );
        
        
        $paso1 = get_field( "paso1" , $comoRecorrerID[0]);
        $paso2 = get_field( "paso2" , $comoRecorrerID[0]);
        $paso3 = get_field( "paso3" , $comoRecorrerID[0]);

        //$sobreElMuseoID = get_field( "sobre_el_museo" );
        $sobreElMuseoLink = get_field( "url_del_sitio_web");
        $sobreElMuseoMap = get_field( "url_del_mapa" );
        $sobreElMuseoCont = get_field( "direccion" );
        

    ?>
    <div id="main">
        <?php if($url_recorrido!=''): ?>
            <section id="aplicacion-recorrido" data-url-background="/img/marcador-imagen-fondo-aplicacion.jpg">
            <!-- Aplicacion escritorio -->
                <div class="container contenedor-aplicacion-recorrido d-none d-lg-block" style="padding:0px;">
                    <iframe src="<?php echo  $url_recorrido; ?>" style="width:100%;height:639px;"></iframe>
                    <div class="col-12 compartir d-flex justify-content-end align-items-center">
                        <p>Comparte</p>
                        <a href="<?php echo $url_facebook; ?>" id="share-facebook" target="_blank"><span class="fab fa-facebook-f" aria-hidden="true"></span></a>
                        <a href="<?php echo $url_twitter; ?>" id="share-twitter" target="_blank"><span class="fab fa-twitter" aria-hidden="true"></span></a>
                    </div>
                </div>

                <!-- Imagen con link mobile -->
                <div class="container  d-block d-lg-none" style="padding:0px;">
                    <a href="<?php echo  $url_recorrido; ?>" target="_blank"><img src="<?php echo $img_mobile; ?>" class="img-aplicacion-mobile"></a>
                </div>
            </section>
        <?php endif; ?>
        <!-- Titulo y descripcion -->
        <?php //if(!empty(the_content())): ?>
            <section>
                <div class="container main-description">
                    <div class="row container-titulo">
                        <div class="col-12"><h1><?php the_title(); ?></h1></div>
                    </div>
                    <div class="row description-text">
                        <div class="col-12"><?php the_content(); ?></div>
                    </div>
                </div>      
            </section>
        <?php //endif; ?>
        <!-- Cómo recorrer -->
        <?php if(!empty($paso1['descripcion'])): ?>
        <section id="como-recorrer" style="background-color: #EEEEEE;">
            <div class="container ">
                <div class="row container-titulo">
                    <div class="col-12">
                        <h1>How to get around?</h1>
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
        <!-- Sobre el museo -->

        <section id="sobre-el-museo-limari">
            <div class="container sobre-el-museo">
                <div class="row container-titulo">
                    <div class="col-12">
                        <h1>About the museum</h1>
                    </div>
                </div> 
                <div class="row description-text" style="padding-bottom:0px;">
                    <div class="col-12 col-md-6 mapa-museo">
                    <iframe src="<?php echo $sobreElMuseoMap; ?>" width="600" height="450" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
                </div>
                    <div class="col-12 col-md-6">
                        <div class="row ">
                            <div class="col-1">
                                <img src="<?php echo get_template_directory_uri(); ?>/img/map-marker-alt-solid.svg" alt="">
                            </div>
                            <div class="col-10 col-sm-11 direccion-museo">
                                <?php echo  $sobreElMuseoCont; ?>
                            </div>
                        </div>
                        <?php if(!empty($sobreElMuseoLink)): ?>
                            <div class="row datos-contacto-museo">
                                <div class="col-datos-contacto-museo col-12 d-block d-lg-flex justify-content-between">
                                    <div class="dato-contacto" id="contacto1">
                                        <a href="<?php echo  $sobreElMuseoLink; ?>" target="_blank">
                                            Visit the museum website
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div> 
            </div>
        </section>
        <!-- Recorridos recomendados -->
        <?php  
            //$recomendadosID = get_field( "recorridos_recomendados" );
            $recomendados = get_field( "recorridos_recomendados" );  
            
        ?>
        <?php if(!empty($recomendados)): $i=0;  ?>
            <section id="recorridos-recomendados">
                <div class="container-fluid recorrido-recomendado">
                    <div class="container ">
                        <div class="row container-titulo">
                            <div class="col-12">
                                <h1>Recommended tours</h1>
                            </div>
                        </div>  
                        <?php foreach($recomendados as $recomendado): $i++;  ?>
                            <?php
                                $id="recomendado".$i;
                                $recomendadolink= get_the_permalink($recomendado);   
                                $recomendadoImg = get_the_post_thumbnail_url($recomendado); 
                                $recomendadoTit = get_the_title( $recomendado ); 
                            ?>
                            <?php if($i==1): echo '<div class="row recorridos">';  endif;?>   
                            <?php if($i==4): echo '<div class="row recorridos" id="mas-recorridos" style="display: none;">';  endif;?>       
                                <div class="col-12 col-sm-4 recomendado" id="<?php echo $id; ?>" onclick="window.location='<?php echo $recomendadolink; ?>';">
                                    <img src="<?php echo $recomendadoImg; ?>" alt="<?php echo $recomendadoTit; ?>" class="img-fluid">
                                    <h3 class="subtitulo3"><?php echo $recomendadoTit; ?></h3>
                                </div> 
                            <?php if($i==3 || $i==count($recomendados)): echo'</div>'; endif; ?>
                        <?php endforeach; ?>
                        <?php if(count($recomendados)>3): ?>
                        <div class="row d-flex justify-content-center contenedor-boton-central" style="padding-top: 1.6875rem; padding-bottom: 3rem;">
                            <div class="col-12  text-center boton-central" onclick="$('#mas-recorridos').slideToggle('slow')">
                                Ver todos los recorridos&nbsp;<span>(<?php echo count($recomendados); ?>)</span>
                            </div>        
                        </div>  
                        <?php endif;  ?>
                    </div>
                </div>
            </section>
        <?php  endif; ?>

    </div>
<?php endwhile; endif; ?>
<?php get_footer( 'ingles'); ?>
