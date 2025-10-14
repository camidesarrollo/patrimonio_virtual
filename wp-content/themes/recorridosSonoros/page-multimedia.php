<?php
/*
 * Template Name: Multimedia
 * Template Post Type: page
 */
?>
<?php get_header(); ?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

    <div id="main" style="padding-bottom: 4rem;">
        <div class="container breadcrumbs pt-3" style="color:#C0C0C0;font-size: 1.0625rem;"><a href="/" style="color:#4A4A4A; text-decoration: none;">Inicio</a> / Multimedia</div>
        <?php
            mostrar_contenidos_por_tipo('video');
            mostrar_contenidos_por_tipo('documento');
        ?>
    </div>
<?php endwhile; endif; ?>
<?php get_footer(); ?>
