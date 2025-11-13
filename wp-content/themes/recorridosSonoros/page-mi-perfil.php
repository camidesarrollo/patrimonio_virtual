<?php
if (is_user_logged_in()) {
    $userID = get_current_user_id();
    $all_meta_for_user = get_user_meta($userID);
} else {
    $url = admin_url('admin-post.php?action=redirigir_centralruc&accion=1');
    wp_redirect($url);
}
?>

<?php get_header(); ?>
<!-- CONTENIDO PAGE.PHP -->
<div id="main">
    <section id="description-home">

        <?php
        if (is_user_logged_in()) {
            $userID = get_current_user_id();
            $all_meta_for_user = get_user_meta($userID);
        ?>
            <div class="container main-description">
                <div class="row container-titulo">
                    <div class="col-12">
                        <h1><?php the_title(); ?></h1>
                        <?php if (isset($_REQUEST["error"]) && $_REQUEST["error"] == 1) { ?>
                            <div class="w-100 errorBox" style="margin-bottom: 4%;display: block;">
                                <i class="fa fa-info-circle" aria-hidden="true"></i> No se ha podido editar perfil, favor intentar mas tarde.
                            </div>
                        <?php } else if (isset($_REQUEST["error"]) && $_REQUEST["error"] == -2) { ?>
                            <div class="w-100 errorBox" style="margin-bottom: 4%;display: block;">
                                <i class="fa fa-info-circle" aria-hidden="true"></i> No se ha podido editar perfil, contraseña antigua incorrecta.
                            </div>
                        <?php } ?>
                        <?php if (isset($_GET["acC"])) {
                            if ($_GET["acC"] == "mail") { ?>
                                <div class="w-100 successBox" style="margin-bottom: 5%;display: block;">
                                    <i class="fa fa-info-circle" aria-hidden="true"></i> Se ha enviado un mensaje a su nuevo correo para confirmarlo, en caso de no hacerlo, se mantendrá el anterior.
                                </div>
                            <?php
                            } else if ($_GET["acC"] == "pass") { ?>
                                <div class="w-100 successBox" style="margin-bottom: 5%;display: block;">
                                    <i class="fa fa-info-circle" aria-hidden="true"></i> Se ha actualizado su contraseña.
                                </div>
                            <?php  } else if ($_GET["acC"] == "mailupdate") { ?>
                                <div class="w-100 successBox" style="margin-bottom: 5%;display: block;">
                                    <i class="fa fa-info-circle" aria-hidden="true"></i> Su correo ha sido actualizado exitosamente.
                                </div>
                            <?php } else { ?>
                                <div class="w-100 successBox" style="margin-bottom: 5%;display: block;">
                                    <i class="fa fa-info-circle" aria-hidden="true"></i> Se ha enviado un mensaje a su nuevo correo para confirmarlo, en caso de no hacerlo, se mantendrá el anterior. <br>Se ha actualizado su contraseña.
                                </div>
                            <?php
                            }
                            ?>
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

                    <div class="col-12 text-lg-right text-xl-right">
                        <a href="<?php echo admin_url('admin-post.php?action=redirigir_centralruc&accion=3'); ?>">
                            <button class="button btn btnPerfil">Edita tus datos</button>
                        </a>
                    </div>
                </div>
            </div>

        <?php
        } else {
        }
        ?>



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