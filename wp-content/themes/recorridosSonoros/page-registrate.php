    <?php get_header(); ?>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <div id="main">
      <section id="description-home">
        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
            <div class="container main-description">
              <div class="row container-titulo">
                <div class="col-12">
                  <h1><?php the_title(); ?></h1>


                  <div class="col-12 max-container px-0 m-auto" style="margin-bottom: 2%!important;margin-top: 1%!important;">
                    <div class="contenedor-recaptcha  ml-3">
                      <div class="container d-flex justify-content-center bd-highlight mb-2">
                        <div>
                          <p>Para habilitar tu registro primero debemos verificar que no eres un robot</p>
                        </div>


                      </div>
                      <div class="container d-flex justify-content-center bd-highlight" style="margin-bottom: 10px;">
                        <div class="g-recaptcha" data-sitekey="6Lcho6AjAAAAAM2iIR2KdnwqO9f_Tl3fhMFJj9dR" data-callback="ValidarRecaptcha" data-expired-callback="RecaptchaExpired">
                        </div>
                      </div>
                    </div>

                    <div class="w-100 contenedor-formulario" style="padding-left: 0px;text-align: justify;">
                      <div class="w-100 errorBox" style="margin-bottom: 2%;">
                        <i class="fa fa-info-circle" aria-hidden="true"></i>
                      </div>
                      <div id='formContainer'>
                        
                      </div>
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
    <style>
      .d-none {
        display: none
      }

      .d-flex {
        display: flex;
      }

      .justify-content-center {
        justify-content: center !important;
      }

      .g-recaptcha {
        transform: scale(1.2);
        transform-origin: 0 0;
      }

      @media (max-width:430px) {
        .g-recaptcha {
          transform: scale(0.9);
          transform-origin: 0 0;
          margin-left: 40px;
        }
      }
    </style>
    <?php get_footer(); ?>