<?php

get_header(); ?>


<div class="content">
    <div class="container main-description">
            <div class="row container-titulo">
                <div class="col-12">
                    <h1>Registro en Portal Patrimonio Virtual</h1>
                </div>
            </div>
            <div class="row description-text">
                <div class="col-12 pb-3">
                    <p>Su registro ha finalizado, ahora podrá iniciar su sesión en el portal Patrimonio Virtual.</br></br></p>
                </div>
                <div class="col-xl-6 col-lg-6 col-12">
                <a href="<?php echo get_site_url(); ?>/registrate">
                        <input type="button" class='btn button btnRegistrate more-link' value="Inicia sesión"></a>
                </div>
                <div class="col-xl-6 col-lg-6 col-12">
                <a href="<?php echo get_site_url(); ?>/recorridos/">
                        <input type="button" class='btn button btnRegistrate more-link' value="Visita los recorridos"></a>
                </div>
            </div>
        </div>
</div>

<?php get_footer(); ?>