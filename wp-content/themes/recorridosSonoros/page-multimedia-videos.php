<?php
/*
 * Template Name: Multimedia - Videos
 * Template Post Type: page
 */
?>
<?php get_header(); ?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

    <div id="main" style="padding-bottom: 4rem;">
    <?php
        mostrar_todos_los_contenidos_por_tipo('video'); 
        
    ?>
    </div>
<?php endwhile; endif; ?>
<?php get_footer(); ?>
