<?php

get_header(); ?>


<div class="content">
    <div class="container main-description">
        <div class="row container-titulo">
            <?php if (isset($_GET["correoActualizado"]) || isset($_GET["errorCorreoActualizado"])) { ?>
                <div class="col-12">
                    <h1>Actualizar perfil en Portal Patrimonio Virtual</h1>
                </div>
            <?php  } else { ?>
                <div class="col-12">
                    <h1>Registro en Portal Patrimonio Virtual</h1>
                </div>
            <?php }    ?>

        </div>
        <?php if (isset($_GET["correoActualizado"])) { ?>
            <div class="row description-text">
                <div class="col-12 pb-3">
                    <p>Su correo ha sido actualizado.</br></br></p>
                </div>
                <div class="col-xl-6 col-lg-6 col-12">
                    <a href="<?php echo get_site_url(); ?>/mi-perfil/">
                        <input type="button" class='btn button btnRegistrate more-link w-100' value="Mi Perfil"></a>
                </div>
                <div class="col-xl-6 col-lg-6 col-12">
                    <a href="<?php echo get_site_url(); ?>">
                        <input type="button" class='btn button btnRegistrate more-link w-100' value="Visita los recorridos"></a>
                </div>
            </div>
        <?php } else if (isset($_GET["errorCorreoActualizado"])) {  ?>
            <div class="row description-text">
                <div class="col-12">
                    <p>Este link ha caducado.</br></br></p>
                </div>
            </div>
        <?php
        }
        if (isset($_GET["errorRegistro"])) { ?>
            <div class="row description-text">
                <div class="col-12">
                    <p>Su registro no ha sido realizado, favor volver a intentarlo mas tarde.</br></br></p>
                </div>
            </div>
        <?php }
        if (isset($_GET["errorValidacion"])) { ?>
            <div class="row description-text">
                <div class="col-12">
                    <p>Ha ocurrido un error al validar su cuenta, favor volver a intentarlo mas tarde.</br></br></p>
                </div>
            </div>
        <?php } else if (isset($_GET["successRegistro"])) { ?>
            <div class="row description-text">
                <div class="col-12 pb-3">
                    <p>Su registro ha finalizado, ahora podrá iniciar su sesión en el portal Patrimonio Virtual.</br></br></p>
                </div>
                <div class="col-xl-6 col-lg-6 col-12">
                    <a href="<?php echo get_site_url(); ?>/ingreso-pat-virtual/">
                        <input type="button" class='btn button btnRegistrate more-link w-100' value="Inicia sesión"></a>
                </div>
                <div class="col-xl-6 col-lg-6 col-12">
                    <a href="<?php echo get_site_url(); ?>">
                        <input type="button" class='btn button btnRegistrate more-link w-100' value="Visita los recorridos"></a>
                </div>
            </div>
        <?php } else if (isset($_GET["validarRegistro"])) { ?>
            <div class="row description-text">
                <div class="col-12">
                    <p>Su registro ha sido creado, se ha enviado un correo electronico para verificar su cuenta</br></br></p>
                </div>
            </div>
        <?php  } ?>

    </div>
</div>

</div>

<?php get_footer(); ?>