<?php
// Set the Current Author Variable $curauth
$curauth = (isset($_GET['author_name'])) ? get_user_by('slug', $author_name) : get_userdata(intval($author));
?>
 <?php get_header(); ?>
<!-- CONTENIDO PAGE.PHP -->
<div id="main">
    <section id="description-home">

        <?php
        
            $all_meta_for_user = get_user_meta($curauth->ID);
        ?>
            <div class="container main-description">
                <div class="row container-titulo">
                    <div class="col-12">
                        <h1>Perfil de persona usuaria</h1>
                        <?php if (isset($_GET["error"])) { ?>
                            <div class="w-100 errorBox" style="margin-bottom: 4%;display: block;">
                                <i class="fa fa-info-circle" aria-hidden="true"></i> No se ha podido editar perfil, favor intentar mas tarde
                            </div>
                        <?php } ?>
                        <?php if (isset($_GET["actualizado"])) { ?>
                            <div class="w-100 successBox" style="margin-bottom: 5%;display: block;">
                                <i class="fa fa-info-circle" aria-hidden="true"></i> Su perfil se ha actualizado exitosamente
                            </div>
                        <?php } ?>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-12 pb-5">
                        <b>Identificación:</b><br>
                        <?php echo $all_meta_for_user["rut_persona"][0] ?>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-12 pb-5">
                        <b>Nombre:</b><br>
                        <?php echo $all_meta_for_user["nombre_persona"][0] ?>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-12 pb-5">
                        <b>Apellido paterno:</b><br>
                        <?php echo ($all_meta_for_user["apellido_paterno"][0]) ?>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-12 pb-5">
                        <b>Apellido materno:</b><br>
                        <?php echo ($all_meta_for_user["apellido_materno"][0]) ?>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-12 pb-5">
                        <b>Fecha nacimiento:</b><br>
                        <?php echo $all_meta_for_user["fecha_nacimiento"][0] ?>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-12 pb-5">
                        <b>Correo electrónico:</b><br>
                        <?php echo $all_meta_for_user["mail_persona"][0] ?>
                    </div>

                </div>
            </div>

        

    </section>
    <a id="catalogo">&nbsp;</a>
    <div id="ir-arriba" style="">
        <div class="container">
            <a class="flotante" href="#"><i class="fas fa-chevron-up ir-arriba" aria-hidden="true"></i></a>
        </div>
        <script>
            $(document).ready(function() {
                $(window).scroll(function() {
                    if ($(this).scrollTop() > 0) {
                        $('#ir-arriba').slideDown(300);
                    } else {
                        $('#ir-arriba').slideUp(300);
                    }
                });

            });
        </script>
    </div>
</div>
<?php get_footer(); ?>