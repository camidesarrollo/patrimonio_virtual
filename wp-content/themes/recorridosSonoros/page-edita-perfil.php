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
<div id="main">
  <section id="description-home">
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <div class="container main-description">
          <div class="row container-titulo">
            <div class="col-12">
              <h1>Edita tu perfil</h1>

              <div class="w-100 errorBox" style="margin-bottom: 2%;">
                <i class="fa fa-info-circle" aria-hidden="true"></i>
              </div>

              <div class="col-12 max-container px-0 m-auto" style="margin-bottom: 2%!important;margin-top: 1%!important;">

                <div class="w-100" style="padding-left: 0px;text-align: justify;">
                  <form id="new_post" name="new_post" method="post" action="#" enctype="multipart/form-data">
                    <div class="field_testimonio "><label>Identidad: <span style="color: red;"></span></label><br />
                      <div class="row">

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                          <input disabled value="<?php echo $all_meta_for_user["rut_persona"][0] ?>" type="text" id="identidad_persona" name="identidad_persona" value="">
                        </div>
                      </div>


                      <div class="field_testimonio"><label>Nombres: <span style="color: red;"></span></label><br />
                        <input disabled value="<?php echo $all_meta_for_user["nombre_persona"][0] ?>" type="text" placeholder="Escribe tu nombre" />
                      </div>
                      <div class="field_testimonio"><label>Apellido Paterno: <span style="color: red;"></span></label><br />
                        <input disabled value="<?php echo $all_meta_for_user["apellido_paterno"][0] ?>" type="text" placeholder="Escribe tu apellido paterno " />
                      </div>
                      <div class="field_testimonio"><label>Apellido Materno: <span style="color: red;"></span></label><br />
                        <input disabled value="<?php echo $all_meta_for_user["apellido_materno"][0] ?>" type="text" placeholder="Escribe tu apellido materno" />
                      </div>
                      <div class="field_testimonio"><label>Fecha de nacimiento <span style="color: red;"></span></label><br />
                        <input disabled value="<?php echo $all_meta_for_user["fecha_nacimiento"][0] ?>" type="text" max=<?php echo date('Y-m-d'); ?> />
                      </div>

                      <div class="field_testimonio"><label>Correo electrónico: <span style="color: red;"></span></label><br />
                        <input type="hidden" value="<?php echo $all_meta_for_user["mail_persona"][0] ?>" id="mail_persona_edit_old" name="mail_persona_edit_old" placeholder="Escribe tu correo electrónico" />
                        <input type="mail" value="<?php echo $all_meta_for_user["mail_persona"][0] ?>" id="mail_persona_edit" name="mail_persona_edit" placeholder="Escribe tu correo electrónico" />
                      </div>

                      <div class="field_testimonio"><label>Repite tu correo electrónico: <span style="color: red;"></span></label><br />
                        <input type="mail" value="<?php echo $all_meta_for_user["mail_persona"][0] ?>" id="mail_persona_repite_edit" name="mail_persona_repite_edit" placeholder="Escribe nuevamente tu correo electrónico" />
                        <div class="CU_mensaje_Mail mensaje-invalido labelInvalido" style="color:red" id="CU_mensaje_Mail"></div>
                      </div>
                      <div class="field_testimonio"><label>Antigua contraseña: <span style="color: red;"></span></label><br />
                        <input type="password" id="contrasena_old_persona_edit" name="contrasena_old_persona_edit" placeholder="Escribe tu antigua contraseña"  minlength="4" maxlength="12"/>
                      </div>
                      <div class="field_testimonio"><label>Contraseña: <span style="color: red;"></span></label><br />
                        <input type="password" id="contrasena_persona_edit" name="contrasena_persona_edit" placeholder="Escribe una nueva contraseña en caso de querer actualizarla" minlength="4" maxlength="12" />
                      </div>

                      <div class="field_testimonio"><label>Repite tu contraseña: <span style="color: red;"></span></label><br />
                        <input type="password" id="recontrasena_persona_edit" name="recontrasena_persona_edit" placeholder="Escribe nuevamente tu contraseña"  minlength="4" maxlength="12"/>
                        <div class="CU_mensaje_Pass mensaje-invalido labelInvalido" style="color:red" id="CU_mensaje_Pass"></div>
                      </div>


                      <div class="field_testimonio row" style="margin-left: 0px;">
                        <div class="field_testimonio col-xs-8 col-sm-8 col-md-8 col-lg-8" style="text-align: left;font-size: 16px;padding-left: 0px;">
                          (*) Campos obligatorios
                        </div>
                        <div class="pl-lg-0 col-xs-12 col-sm-12 col-md-2 col-lg-2">
                          <button id='btnBorrarEdit' type="button" class="btnBorrarTest btn ">Limpia</button>
                        </div>
                        <div class="pr-lg-0 col-xs-12 col-sm-12 col-md-2 col-lg-2">
                          <input type="submit" id="btnEnviarEdit" class="btnEnviarTest btn" value="Envía">
                        </div>
                      </div>

                      <input type="hidden" name="action" value="new_post_edit" />
                      <?php wp_nonce_field('new-post'); ?>
                  </form>
                </div>
              </div>

              <div class="row description-text">
                <div class="col-12">
                  <?php the_content(); ?>
                </div>
              </div>
            </div>
        <?php endwhile;
    endif; ?>
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