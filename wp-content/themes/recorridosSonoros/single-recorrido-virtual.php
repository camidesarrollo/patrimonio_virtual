<script src="https://localhost/srv/analytics.js"></script>
<script src="https://code.jquery.com/jquery-3.2.1.js"></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>

<?php session_start(); ?>

<?php
/*
 * Template Name: Recorrido virtual (Español)
 * Template Post Type: post
 */
?>
<?php get_header(); ?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <?php
        $url_recorrido = get_field("url_del_recorrido");
        $permalink = get_the_permalink();
        $texto_twitter = get_field("texto_twitter");
        $texto_whatsapp = get_field("texto_whatsapp");
        $img_mobile = get_field("imagen_mobile");
        $subject = get_field("asunto_email");
        $texto_email =get_field("texto_email");

        $url_facebook = "https://www.facebook.com/sharer/sharer.php?u=" . $permalink;
        $url_twitter = "https://twitter.com/intent/tweet?text=" . urlencode($texto_twitter) . $permalink;
        $url_whatsapp = "https://api.whatsapp.com/send?text=" . urlencode($texto_whatsapp) . " " . $permalink;

        $body = $texto_email . " " . $permalink;
        $url_email = "mailto:?subject=" . rawurlencode($subject) . "&body=" . rawurlencode($body);

        $comoRecorrerID = get_field("como_recorrer");


        $paso1 = get_field("paso1", $comoRecorrerID[0]);
        $paso2 = get_field("paso2", $comoRecorrerID[0]);
        $paso3 = get_field("paso3", $comoRecorrerID[0]);

        //$sobreElMuseoID = get_field( "sobre_el_museo" );
        $sobreElMuseoLink = get_field("url_del_sitio_web");
        $sobreElMuseoMap = get_field("url_del_mapa");
        $sobreElMuseoCont = get_field("direccion");


        ?>
        <div id="main">
            <?php if ($url_recorrido != '') : ?>
                <section id="aplicacion-recorrido" data-url-background="/img/marcador-imagen-fondo-aplicacion.jpg">
                    <!-- Aplicacion escritorio -->
                    <div class="container contenedor-aplicacion-recorrido d-none d-lg-block" style="padding:0px;">
                        <iframe src="<?php echo  $url_recorrido; ?>" style="width:100%;height:639px;"></iframe>
                        <div class="col-12 compartir d-flex justify-content-end align-items-center">
                            <p>Comparte</p>
                            
                            <!-- Enlace para compartir en Facebook -->
                            <a href="<?php echo $url_facebook; ?>" id="share-facebook" target="_blank">
                                <span class="fab fa-facebook-f" aria-hidden="true"></span>
                            </a>

                            <!-- Enlace para compartir en Twitter -->
                            <a href="<?php echo $url_twitter; ?>" class="shareX-twitter" id="share-twitter" target="_blank">
                                <span class="fab fa-x-twitter" aria-hidden="true"></span>
                                
                            </a>

                            <!-- Enlace para compartir en WhatsApp -->
                            <a href="<?php echo $url_whatsapp; ?>" class="share-whatsapp" id="share-whatsapp" target="_blank" style="margin-left:5px;">
                                <span class="fab fa-whatsapp" aria-hidden="true"></span>
                            </a>

                            <!-- Enlace para compartir por correo electrónico -->
                            <a href="<?php echo $url_email; ?>" class="share-email" id="share-email" style="margin-left:5px;">
                                <span class="fas fa-envelope" aria-hidden="true"></span>
                            </a>
                        </div>
                    </div>

                    <!-- Imagen con link mobile -->
                    <div class="container  d-block d-lg-none" style="padding:0px;">
                        <a href="<?php echo  $url_recorrido; ?>" target="_blank"><img src="<?php echo $img_mobile; ?>" class="img-aplicacion-mobile"></a>
                    </div>
                </section>
            <?php endif; ?>
            <!-- Titulo y descripcion -->
            <?php //if(!empty(the_content())): 
            ?>
            <section>
                <div class="container main-description">
                    <div class="row container-titulo">
                        <div class="col-12">
                            <h1><?php the_title(); ?></h1>
                        </div>
                    </div>
                    <div class="row description-text">
                        <div class="col-12"><?php the_content(); ?></div>
                    </div>
                </div>
            </section>
            <?php //endif; 
            mostrar_contenidos_asociados();

            ?>

            <!-- Sobre el museo -->

            <section id="sobre-el-museo-limari">
                <div class="container sobre-el-museo">
                    <div class="row container-titulo">
                        <div class="col-12">
                            <h1>M&aacute;s informaci&oacute;n</h1>
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
                            <?php if (!empty($sobreElMuseoLink)) : ?>
                                <div class="row datos-contacto-museo">
                                    <div class="col-datos-contacto-museo col-12 d-block d-lg-flex justify-content-between">
                                        <div class="dato-contacto" id="contacto1">
                                            <a href="<?php echo  $sobreElMuseoLink; ?>" target="_blank">
                                                Visitar sitio web
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
            $recomendados = get_field("recorridos_recomendados");

            ?>
            <?php if (!empty($recomendados)) : $i = 0;  ?>
                <section id="recorridos-recomendados">
                    <div class="container-fluid recorrido-recomendado">
                        <div class="container ">
                            <div class="row container-titulo">
                                <div class="col-12">
                                    <h1>Recorridos recomendados</h1>
                                </div>
                            </div>
                            <?php foreach ($recomendados as $recomendado) : $i++;  ?>
                                <?php
                                $id = "recomendado" . $i;
                                $recomendadolink = get_the_permalink($recomendado);
                                $recomendadoImg = get_the_post_thumbnail_url($recomendado);
                                $recomendadoTit = get_the_title($recomendado);
                                ?>
                                <?php if ($i == 1) : echo '<div class="row recorridos">';
                                endif; ?>
                                <?php if ($i == 4) : echo '<div class="row recorridos" id="mas-recorridos" style="display: none;">';
                                endif; ?>
                                <div class="col-12 col-sm-4 recomendado" id="<?php echo $id; ?>" onclick="window.location='<?php echo $recomendadolink; ?>';">
                                    <img src="<?php echo $recomendadoImg; ?>" alt="<?php echo $recomendadoTit; ?>" class="img-fluid">
                                    <h3 class="subtitulo3"><?php echo $recomendadoTit; ?></h3>
                                </div>
                                <?php if ($i == 3 || $i == count($recomendados)) : echo '</div>';
                                endif; ?>
                            <?php endforeach; ?>
                            <?php if (count($recomendados) > 3) : ?>
                                <div class="row d-flex justify-content-center contenedor-boton-central" style="padding-top: 1.6875rem; padding-bottom: 3rem;">
                                    <div class="col-12  text-center boton-central" onclick="$('#mas-recorridos').slideToggle('slow')">
                                        Ver todos los recorridos&nbsp;<span>(<?php echo count($recomendados); ?>)</span>
                                    </div>
                                </div>
                            <?php endif;  ?>
                        </div>
                    </div>
                </section>
            <?php endif; ?>

        </div>
<?php endwhile;
endif; ?>
<?php get_footer(); ?>

<?php
if (empty($_SESSION['contador']) && !is_user_logged_in()) {
    $_SESSION['contador'] = 1;
} else {
    $_SESSION['contador']++;
}
$test = $_SESSION['contador'];
if ($_SESSION['contador'] >= 3 && !is_user_logged_in()) {
?>
    <span class="overlay_popup">
        <div class="overlay_canvas"></div>
        <div class="popup">
            <a href="#" class="close_btn" onclick="$(this).popupClose();">x</a>
            <div>
                <h3>Gracias por visitar los recorridos de Patrimonio virtual</h3>
            </div>
            <div>
                <p>Te invitamos a ingresar o registrarte.</p>
            </div>
            <div class="row w-100">
                <div class="col-xl-6 col-lg-6 col-12 pb-md-2 pb-sm-2">
                    <div class="Ingresar">
                        <p>Ingresa con RUT o Pasaporte / DNI</p>
                        <a href="<?php echo get_site_url(); ?>/ingreso-pat-virtual/">
                            <input type="button" class='btn button btnIngresa more-link' value="Inicia sesión">
                        </a>
                    </div>
                </div>
                <div class="col-6 col-xl-6 col-lg-6 col-12">
                    <div class="Registrate">
                        <p>Regístrate con RUT o Pasaporte / DNI</p>
                        <a href="<?php echo get_site_url(); ?>/registrate">
                            <input type="button" class='btn button btnRegistrate more-link' value="Regístrate"></a>
                    </div>
                </div>
            </div>
        </div>
    </span>
    <script>
        $(document).ready(function() {
            $('.overlay_popup').delay(2000).queue(function() {
                $('.overlay_popup').addClass('popup-open')
            });
        });

        $.fn.popupClose = function() {
            $(".overlay_popup").removeClass("popup-open");
            return this;
        };
    </script>
<?php } ?>

<!-- partial -->
