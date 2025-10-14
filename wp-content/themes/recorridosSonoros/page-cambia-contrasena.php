<?php get_header(); ?>
<!-- CONTENIDO PAGE.PHP -->
<div id="main">
  <section id="description-home">
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <div class="container main-description">
          <div class="row container-titulo">
            <div class="col-12">
              <h1>
                Cambia tu contraseña

              </h1>
              <?php if (isset($_GET["errorLogin"])) { ?>
                <div class="w-100 errorBox" style="margin-bottom: 2%;display: block;">
                  <i class="fa fa-info-circle" aria-hidden="true"></i> No se ha podido cambiar la contraseña, favor intente denuevo mas tarde
                </div>
              <?php } else { ?>
                <div class="w-100 errorBox" style="margin-bottom: 2%;">
                  <i class="fa fa-info-circle" aria-hidden="true"></i>
                </div>
              <?php } ?>




              <div class="col-12 max-container px-0 m-auto" style="margin-bottom: 2%!important;margin-top: 1%!important;">

                <div class="w-100" style="padding-left: 0px;text-align: justify;">
                  <form id="new_post_inicio_sesion" name="new_post_inicio_sesion" method="post" action="#" enctype="multipart/form-data">
                    <input type="hidden" value="<?php echo $_GET["i"] ?>" name="identificacion" id='identificacion'>
                    <input type="hidden" value="<?php echo $_GET["ih"] ?>" name="idenh" id='idenh'>
                    <?php
                    if (isset($_GET["pasaporte"])) { ?>
                      <input type="hidden" value="1" name="pasaporte" id='pasaporte'>
                    <?php }
                    ?>
                    <div class="field_testimonio "><label>Ingresa tu nueva contraseña: <span style="color: red;">&nbsp *</span></label><br />
                      <input type="password" id="contrasena_persona" name="pass_persona" placeholder="Ingresa contraseña" minLength="4" maxLength="12">
                    </div>
                    <div class="field_testimonio"><label>Re ingresa tu nueva contraseña: <span style="color: red;">&nbsp *</span></label><br />
                      <input type="password" id="recontrasena_persona" name="repass_persona" placeholder="Re escribe tu contraseña" minLength="4" maxLength="12"/>
                    </div>

                    <div class="field_testimonio row" style="margin-left: 0px;">
                      <div class="field_testimonio col-xs-8 col-sm-8 col-md-8 col-lg-8" style="text-align: left;font-size: 16px;padding-left: 0px;">
                        (*) Campos obligatorios
                      </div>
                      <div class="pl-lg-0 col-xs-12 col-sm-12 col-md-2 col-lg-2">
                        <button type="button" class="btnBorrarRutSession btn">Limpia</button>
                      </div>
                      <div class="pr-lg-0 col-xs-12 col-sm-12 col-md-2 col-lg-2">
                        <button type="submit" id="btnEnviarCambioPass" class="btnEnviarRutSession btn">Envía</button>
                      </div>
                    </div>

                    <input type="hidden" name="action" value="form_cambia_pass" />
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