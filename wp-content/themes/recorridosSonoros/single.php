<?php
if (session_status() == PHP_SESSION_NONE) {
session_start();
}
?>
<?php

if (isset($_GET['debug'])) {
  $count = 1;
  echo "get_the_ID()=" . get_the_ID() . "<br>\n";
  echo "get_the_base_lang_post_ID()=" . get_the_base_lang_post_ID() . "<br>\n";
  echo "get_polylang_lang_code()=" . get_polylang_lang_code() . "<br><br>\n";

  // test if the plugin polylang is present
  if (isset($GLOBALS["polylang"])) {
    $translations = $GLOBALS["polylang"]->model->post->get_translations(get_the_ID());
    // $translations contains an array with all translations of the post}
    var_dump($translations);
    echo "<br>\n";
  }

  $postmetas = get_post_meta(get_the_base_lang_post_ID());
  foreach ($postmetas as $meta_key => $meta_value) {
    if (strncmp($meta_key, '_', 1) == 0) {
      if (strlen($meta_value[0]) > 30) {
        $meta_value[0] = substr($meta_value[0], 0, 30) . '...';
      }
      echo $count . ': ' . $meta_key . ' = ' . $meta_value[0] . "<br>\n";
      $count++;
    }
  }
}

?>

<script src="https://localhost/srv/analytics.js"></script>

<script type="application/javascript">
  let firsttime=true
  // For these arrays, index 0 is player on obj1 block, and indexes 1..2 are in main block
  var playerstate = []; // 0=pause, 1=play
  var time_in = []; // DOM object for <i>min:seg
  var time_out = []; // DOM object for <i>min:seg
  var range = []; // DOM object for <input type="range">
  var play_pause_button = []; // DOM object for <a>
  var prev_button; // DOM object for <a>
  var next_button; // DOM object for <a>
  var audioplayer = []; // DOM object for <audio>
  var reproductores;
  var interval;
  var whichPlayerIsPlaying = -1;
  var totalduration = 0;
  var idiomas_codes = [<?php printidiomascodes(); ?>];
  var audiofiles = {
    <?php printlists('audio_acf'); ?>
  };
  var titulos = {
    <?php printlists('title_acf'); ?>
  };
  var textos = {
    <?php printlists('text_acf'); ?>
  };


  var images = <?php printimagelists_acf(); ?>;
  var mapsvgdoc;
  var mapa_svg = <?php echo "'" . get_field("mapa_1", get_the_ID()). "'"; ?>;
  var mapa_svg2 = <?php echo "'" . get_field("mapa_2", get_the_ID()). "'"; ?>;
  var mapa_svg_ids = <?php printids_acf(); ?>;

  var selectedlanguage;
  var selectedlanguageindex;
  var polylang_lang_code = '<?php echo get_polylang_lang_code(); ?>';
  var currenttrack = 0;


  document.addEventListener("DOMContentLoaded", function(event) {
  //window.onload = function() {
    selectedlanguageindex = idiomas_codes.findIndex((element) => element == polylang_lang_code);
    if (selectedlanguageindex == -1) {
      selectedlanguageindex = 0;
    }
    selectedlanguage = idiomas_codes[selectedlanguageindex];

    initlanguage(selectedlanguage);

    var elem = document.getElementById('changelang1');
    for (var i = 0; i < elem.options.length; i++) {
      if (elem.options[i].value === polylang_lang_code) {
        elem.selectedIndex = i;
        elem.options[i].selected = true;
        break;
      }
    }
    elem = document.getElementById('changelang2');
    for (var i = 0; i < elem.options.length; i++) {
      if (elem.options[i].value === polylang_lang_code) {
        elem.selectedIndex = i;
        elem.options[i].selected = true;
        break;
      }
    }


    

    //changetrack(currenttrack, false);




    //  };





    //document.addEventListener("DOMContentLoaded", function(event) {   
    const urlParams = new URLSearchParams(window.location.search); 
    console.log("urlParams: ",urlParams)
    let pista =urlParams.get('pista')
    if(pista == null){
      pista =0
    }
    jQuery(function($) {
      function ocultar() {
      /**
       * cambio de idioma.
       */
      window.localStorage.setItem("estabaPresionado", 1);
      window.localStorage.setItem("pathActualMuseo", window.location.origin + window.location.pathname);
      /*if (!window.location.pathname.includes("/es") &&
        !window.location.pathname.includes("/fr") &&
        !window.location.pathname.includes("/en") &&
        !window.location.pathname.includes("/pt")) {
        window.localStorage.setItem("pathActualMuseo", window.location.pathname.split("/")[2]);
      } else {
        window.localStorage.setItem("pathActualMuseo", window.location.pathname.split("/")[3]);
      }*/
      //window.localStorage.setItem("pathActualMuseo", window.location.pathname);

      if (typeof selectedlanguage == 'undefined') {
        if (window.localStorage.getItem("langSelected") == "ES") {
          selectedlanguage = "es";
        } else if (window.localStorage.getItem("langSelected") == "EN") {
          selectedlanguage = "en";
        } else if (window.localStorage.getItem("langSelected") == "FR") {
          selectedlanguage = "fr";
        } else if (window.localStorage.getItem("langSelected") == "PR") {
          selectedlanguage = "pr";
        }
      }
      /**
       * Bug Fix 
       */
      
      if (selectedlanguage != undefined) {
        if (whichPlayerIsPlaying != -1) {
          playpause(whichPlayerIsPlaying);
        }

        do {

        } while (typeof document.getElementById('obj1') == 'undefined');
        

        document.getElementById('obj1').style.display = 'block';
        document.getElementById('main').style.display = 'none';
        $("html, body").animate({
          scrollTop: 0
        }, "slow");
        currenttrack = 0;
        document.getElementById('changelang').value = selectedlanguage;
        document.getElementById('texto').innerHTML = textos[selectedlanguage][0];
        document.getElementById('titulo0').innerHTML = titulos[selectedlanguage][0];
        

        document.getElementById('mapa-svg').setAttribute('data', mapa_svg);
        if (mapa_svg2 != '') {
          document.getElementById('mapa-svg2').setAttribute('data', mapa_svg2);
        } else {
          var elem = document.getElementById('mapa-svg2');
          elem = elem.parentNode;
          elem.parentNode.removeChild(elem);
          elem = document.getElementsByClassName('carousel-control-prev');
          for (var i = 0; i < elem.length; i++) {
            var tatara = elem[i].parentNode;
            tatara = tatara.parentNode;
            tatara = tatara.parentNode;
            tatara = tatara.parentNode;
            if (tatara.className.startsWith('container')) {
              elem[i].style.display = 'none';
            }
          }
          elem = document.getElementsByClassName('carousel-control-next');
          for (var i = 0; i < elem.length; i++) {
            var tatara = elem[i].parentNode;
            tatara = tatara.parentNode;
            tatara = tatara.parentNode;
            tatara = tatara.parentNode;
            if (tatara.className.startsWith('container')) {
              elem[i].style.display = 'none';
            }
          }
        }

      }
    }





    var yaPresionadoLocal = 0;
    /**
      * Bug Fix
      * Se agrega lectura de parametro desde la url
      */
    
    let activateRecorridoSnoro = urlParams.get("rt");

    /**
      * Bug Fix
      * Se agrega script para presionar boton al cambiar de idioma
      */

    if (window.localStorage.getItem("pathActualMuseo")) {

      var pathActual = window.location.origin + window.location.pathname;
      var pathAnterior = window.localStorage.getItem("pathActualMuseo");

    } else {
      var pathActual = "";
      var pathAnterior = "";
    }

    if (yaPresionadoLocal == 0 && window.localStorage.getItem("estabaPresionado") == 1 && checkIfSamePathName(pathActual, pathAnterior)) { //window.location.pathname.split("/")[3],
      if (activateRecorridoSnoro && activateRecorridoSnoro != "") {
        setTimeout(ocultar_especial(pista), 2000);
      } else {
        //const urlParams = new URLSearchParams(window.location.search);
        const activateButton = urlParams.get("pb");
        if (activateButton == 1) {
          setTimeout(ocultar, 2000);
        }
      }
    }
    /** Bug Fix */


      $(".boton-central").click(function() {
        $("html, body").animate({
          scrollTop: 0
        }, "slow");
        return false;
      })



      //const urlParams = new URLSearchParams(window.location.search);
      activateRecorridoSnoro = 1; //urlParams.get("rt"); //ABC

      if (activateRecorridoSnoro && activateRecorridoSnoro != "") {
        setTimeout(ocultar_especial(pista), 2000);
        yaPresionadoLocal = 1;
      } else {
        console.log("entrara en la otra logica...");
      }


  });
})

</script>
<?php
/**
 * Bug Fix
 * correccion carga header
 */
if (get_polylang_lang_code() == "fr") {
  get_header("frances");
} else if (get_polylang_lang_code() == "en") {
  get_header("ingles");
} else if (get_polylang_lang_code() == "pt") {
  get_header("portugues");
} else {
  get_header();
} ?>
<!-- CONTENIDO SINGLE -->

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

    <div id="main" style="<?php if (isset($_GET['rt']) && $_GET['rt'] == 1 || (1) ) { ?> display:none !important<?php } else { ?> display:block !important <?php } ?>">
      <section>
        <div class="container main-description">
          <div class="row container-titulo">
            <div class="col-12">
              <h1><?php the_title(); ?></h1>
            </div>
          </div>
          <div class="row description-text">
            <div class="col-12">
              <?php the_content(); ?>
            </div>
          </div>
        </div>

      </section>
      <?php
          $permalink = get_the_permalink();
          $texto_twitter = get_field("texto_twitter");
          $texto_whatsapp = get_field("texto_whatsapp");
          $subject = get_field("asunto_email");
          $texto_email =get_field("texto_email");

          $url_facebook = "https://www.facebook.com/sharer/sharer.php?u=" . $permalink;
          $url_twitter = "https://twitter.com/intent/tweet?text=" . urlencode($texto_twitter) . $permalink;
          $url_whatsapp = "https://api.whatsapp.com/send?text=" . urlencode($texto_whatsapp) . " " . $permalink;
          $body = $texto_email . " " . $permalink;
          $url_email = "mailto:?subject=" . rawurlencode($subject) . "&body=" . rawurlencode($body);

      ?>
      <section style="background-color: #dedede;">
        <div class="container ">
          <div class="row container-titulo">
            <div class="col-12">
              <h1 id="textTituloRecorridoVirtual">Recorrido virtual</h1>
            </div>
          </div>
          <div class="row">
            <div class="col-12 pasos">
              <img src="https://via.placeholder.com/2000x200.png?text=IMAGEN" class="img-fluid">
            </div>
          </div>
          <div class="row">

            <div class="col-12 compartir-an d-flex justify-content-end align-items-center share">
                <p id="textTituloComparte2">Comparte</p>
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
          <div class="row d-flex justify-content-center contenedor-boton-central" style="padding-top: 1.6875rem; padding-bottom: 3rem;">
            <div id="textVerMasRecorrido" class="col-12  text-center boton-central" onclick="">
              Ver más sobre este recorrido
            </div>
          </div>
        </div>
      </section>
      <section style="background-color: #fff;">
        <div class="container-fluid catalogo-museos" style="background-color: #fff;">
          <div class="container">
            <div class="row container-titulo">
              <div class="col-12">
                <h1 id="textTituloRecorridoSonoro">Recorrido sonoro</h1>
              </div>
            </div>
            <!-- PRIMER CUADRO -->
            <div class="row">
              <div class="col-sm-12 col-md-12 col-lg-6 reproductor">
                <div class="row">
                  <div class="col-sm-12 col-md-4 col-lg-4 icono">
                    <img src="https://via.placeholder.com/500x500.png?text=IMAGEN" class="img-fluid altura-img">
                  </div>
                  <div class="col-sm-12 col-md-8 col-lg-8 audio">
                    <div class="row">
                      <!-- **Esto solo nivela para los museos de Vicuña y Aysen (Versión Español, Frances y Portugues) 
                      Aysen versión español(104), frances(816), portugues(844);
                      Vicuña versión español(60), frances(810) y portugues (838)
                      -->
                      <?php if (is_single(104) || is_single(816) || is_single(844) || is_single(60) || is_single(810) || is_single(838)) : ?>
                        <div class="col-sm-7 col-md-6 col-lg-7 misma-altura-div-aysen-vicuna">
                          <h6 id="titulo1">1 - Titulo</h6>
                        </div>
                      <?php else : ?>
                        <!-- Carga el resto de los titulos de los museos por defecto -->
                        <div class="col-sm-7 col-md-6 col-lg-7">
                          <h6 id="titulo1">1 - Titulo</h6>
                        </div>
                      <?php endif;  ?>

                      <div class="col-sm-5 col-md-6 col-lg-5">
                        <select id="changelang1" class="form-control form-control-sm rep select-biblio" onchange="changelang(this, 'left');">
                          <?php printidiomasoptions(1); ?>
                        </select>
                      </div>
                    </div>
                    <div class="row controles">
                      <div class="col-12">
                        <a href="javascript:void(0);" class="" role="button" aria-pressed="true">
                          <i class="fas fa-play-circle"></i>
                        </a>
                      </div>
                      <div class="col-12 d-flex">
                        <span class="time-in">0:00</span>
                        <input type="range" class="form-control-range" id="formControlRange">
                        <span class="time-out">-:--</span>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-12 compartir-an d-flex justify-content-end align-items-center share">
                        <p id="textTituloComparte5">Comparte</p>
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
                  </div>
                  <div class="col-12 transcripcion">
                    <p id="texto-1"></p>
                    <a id="buttonTranscripcionCompleta" href="" onclick="var lang = document.getElementById('changelang1').value; document.getElementById('texto-1').innerHTML = textos[lang][0]; this.style.display = 'none'; return false;">
                      Ver transcripcion completa >
                    </a>
                  </div>
                </div>
              </div>

              <!-- SEGUNDO CUADRO -->
              <div class="col-sm-12 col-md-12 col-lg-6 reproductor">
                <div class="row">
                  <div class="col-sm-12 col-md-4 col-lg-4 icono">
                    <img src="https://via.placeholder.com/500x500.png?text=IMAGEN" class="img-fluid altura-img">
                  </div>
                  <div class="col-sm-12 col-md-8 col-lg-8 audio">
                    <div class="row">
                      <!-- **Esto solo nivela para museo de la educacion** -->
                      <?php if (is_single(62)) : ?>
                        <!-- Museo Educación GM, Español -->
                        <div class="col-sm-7 col-md-6 col-lg-7 misma-altura-div">
                          <h6 id="titulo2">2 - Titulo</h6>
                        </div>
                      <?php elseif (is_single(784) || is_single(812) || is_single(840)) : ?>
                        <!--Museo Educación GM versiones para (ingles, frances y portugues) -->
                        <div class="col-sm-7 col-md-6 col-lg-7 otra-altura-div">
                          <h6 id="titulo2">2 - Titulo</h6>
                        </div>
                      <?php else : ?>
                        <!--Este se carga por defecto -->
                        <div class="col-sm-7 col-md-6 col-lg-7">
                          <h6 id="titulo2">2 - Titulo</h6>
                        </div>
                      <?php endif;  ?>

                      <div class="col-sm-5 col-md-6 col-lg-5">
                        <select id="changelang2" class="form-control form-control-sm rep select-biblio" onchange="changelang(this, 'right');">
                          <?php printidiomasoptions(2); ?>
                        </select>
                      </div>
                    </div>
                    <div class="row controles">
                      <div class="col-12">
                        <a href="javascript:void(0);" class="" role="button" aria-pressed="true">
                          <i class="fas fa-play-circle"></i>
                        </a>
                      </div>
                      <div class="col-12 d-flex">
                        <span class="time-in">0:00</span>
                        <input type="range" class="form-control-range" id="formControlRange">
                        <span class="time-out">-:--</span>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-12 compartir-an d-flex justify-content-end align-items-center share">
                        <p id="textTituloComparte">Comparte</p>
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
                  </div>
                  <div class="col-12 transcripcion">
                    <p id="texto-2"></p>
                    <a id="buttonTranscripcionCompleta2" href="" onclick="var lang = document.getElementById('changelang2').value; document.getElementById('texto-2').innerHTML = textos[lang][1]; this.style.display = 'none'; return false;">
                      Ver transcripcion completa >
                    </a>
                  </div>
                </div>
              </div>
            </div>
            <div class="row d-flex justify-content-center contenedor-boton-central" style="padding-top: 1.6875rem; padding-bottom: 3rem;">

              <div id="recorridoBtn" class="col-12  text-center boton-central" onclick="ocultar()">
                Ver todo el recorrido sonoro
              </div>

            </div>
          </div>
        </div>
      </section>

      <section style="background-color: #dedede;">
        <div class="container sobre-el-museo an">
          <div class="row container-titulo">
            <div class="col-12">
              <h1 id="textTituloSobreMuseo">M&aacute;s informaci&oacute;n</h1>

            </div>
          </div>
          <div class="row description-text" style="padding-bottom:0px;">
            <div class="col-12 col-md-6 mapa-museo">
              <?php if (get_post_meta(get_the_ID(), 'iframe', true)) :
                echo get_post_meta(get_the_ID(), 'iframe', true);
              endif; ?>
            </div>
            <div class="col-12 col-md-6">
              <div class="row ">
                <div class="col-12 col-sm-12 direccion-museo">
                  <?php if (get_post_meta(get_the_ID(), 'direccion', true)) :
                    echo get_post_meta(get_the_ID(), 'direccion', true);
                  endif; ?>
                </div>
              </div>
              <div class="row datos-contacto-museo-an">
                <div class="col-datos-contacto-museo col-12 d-block d-lg-flex justify-content-between">
                  <div class="dato-contacto-an" id="contacto1">

                    <a href="<?php if (get_post_meta(get_the_ID(), 'link-museo', true)) :
                                        echo get_post_meta(get_the_ID(), 'link-museo', true);
                                      endif; ?>" target="_blank">
                      Visitar<br>
                      Sitio de <?php the_title(); ?><br>
                      <?php
                      if (get_post_meta(get_the_ID(), 'link-museo', true)) :
                        echo get_post_meta(get_the_ID(), 'link-museo', true);
                      endif;
                      ?>
                    </a>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-12 compartir-an d-flex justify-content-end align-items-center share">
                  <p id="textTituloComparte3">Comparte</p>
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
            </div>
          </div>
        </div>
      </section>

      <section class="separador-fut">
      </section>
      <a id="catalogo">&nbsp;</a>
      <div id="ir-arriba" style="">
        <div class="container">
          <a class="flotante" href="#"><i class="fas fa-chevron-up ir-arriba" aria-hidden="true"></i></a>
        </div>
        <script>
          jQuery(function($) {
          $(document).ready(function() {

            $(window).scroll(function() {
              if ($(this).scrollTop() > 0) {
                $('#ir-arriba').slideDown(300);
              } else {
                $('#ir-arriba').slideUp(300);
              }
            });

          });
        });
        </script>
      </div>
    </div>

    <!-- CONTENIDO SINGLE.php Cuando se ve de manera individual el contenido -->
      <section>
        <div class="container main-description">
          <div class="row container-titulo">
            <div class="col-12">
              <h1><?php the_title(); ?></h1>
            </div>
          </div>
          <div class="row description-text">
            <div class="col-12">
              <?php the_content(); ?>
            </div>
          </div>
        </div>
      </section>

    <div id="obj1" style="<?php if (isset($_GET['rt']) && $_GET['rt'] == 1) { ?> display:none !important<?php } else { ?> display:none !important <?php } ?>">
      <section>
        <div class="container main-description">
          <!-- div class="row container-titulo">
            <div class="col-12">
              <h1><span id="textRecorridoSonoro"></span> <?php the_title(); ?></h1>
            </div>
          </div -->
          <div class="row description-text">
            <div class="col-12  reproductor">
              <div class="row">
                <div class="col-12 col-sm-12 col-lg-6 icono">
                  <!-- SINGLE.php -->
                  <div id="carouselExampleIndicators" class="carousel slide" data-ride="false">
                    <ol class="carousel-indicators">
                      <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                      <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                      <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                    </ol>
                    <div class="carousel-inner">
                      <div class="carousel-item active">
                        <img src="https://via.placeholder.com/552x373.png?text=IMAGEN" class="img-fluid">
                      </div>
                      <div class="carousel-item">
                        <img src="https://via.placeholder.com/552x373.png?text=IMAGEN" class="img-fluid">
                      </div>
                      <div class="carousel-item">
                        <img src="https://via.placeholder.com/552x373.png?text=IMAGEN" class="img-fluid">
                      </div>
                    </div>
                    <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                      <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                      <span class="carousel-control-next-icon" aria-hidden="true"></span>
                      <span class="sr-only">Next</span>
                    </a>
                  </div>

                </div>
                <div class="col-12 col-sm-12 col-lg-6 audio">
                  <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-9">
                      <h3 id="titulo0">1- Titulo</h3>
                    </div>
                    <div class="col-sm-12 col-md-12 col-lg-3">
                      <select id="changelang" class="form-control form-control-sm rep select-biblio" onchange="changelang(this, 'title');">
                        <?php printidiomasoptions(1); ?>
                      </select>
                    </div>
                  </div>
                  <div class="row controles grande">
                    <div class="col-12 d-flex botones">
                      <div class="time">
                        <a href="javascript:void(0);" class="grande" role="button" aria-pressed="true">
                          <i class="fas fa-step-backward"></i>
                        </a>
                      </div>
                      <div>
                        <a href="javascript:void(0);" class="grande-play" role="button" aria-pressed="true">
                          <i class="fas fa-play-circle"></i>
                        </a>
                      </div>
                      <div class="time">
                        <a href="javascript:void(0);" class="" role="button" aria-pressed="true">
                          <i class="fas fa-step-forward"></i>
                        </a>
                      </div>
                    </div>
                    <div class="col-12 d-flex">
                      <span class="time-in">2:00</span>
                      <input type="range" class="form-control-range" id="formControlRange">
                      <span class="time-out">5:00</span>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-12 compartir-an d-flex justify-content-end align-items-center share">
                      <p id="textTituloComparte4">Comparte</p>
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
                </div>
                <div class="col-12 transcripcion">
                  <p id="texto"></p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
      <section>
        <div class="container-fluid lista-sonidos">
          <div class="container">
            <div class="row container-titulo">
              <div class="col-12">
                <h1 id="textTituloListaSonidos">Lista de sonidos</h1>
              </div>
            </div>
            <div id="tracklist" class="row">
              <div class="col-12 col-sm-6 col-lg-6 lista">
                <i class="fas fa-headphones"></i>
                <h6 id="textTituloAudio1">1.- Titulo del audio</h6>
              </div>
              <div class="col-12 col-sm-6 col-lg-6 lista">
                <i class="fas fa-headphones"></i>
                <h6 id="textTituloAudio2">1.- Titulo del audio</h6>
              </div>
              <div class="col-12 col-sm-6 col-lg-6 lista">
                <i class="fas fa-headphones"></i>
                <h6 id="textTituloAudio3">1.- Titulo del audio</h6>
              </div>
            </div>
          </div>
        </div>
      </section>

      <section style="background-color: #fff;">
        <div class="container ">
          <div class="row container-titulo">
            <div class="col-12">
              <h1 id="textTituloVisitaPresencialMuseo">Para tu visita presencial en el museo</h1>
            </div>
          </div>
          <div class="row">
            <div class="col-12 pasos">
              <div id="carouselExampleIndicators2" class="carousel slide" data-ride="false">
                <ol class="carousel-indicators">
                  <li data-target="#carouselExampleIndicators2" data-slide-to="0" class="active"></li>
                  <li data-target="#carouselExampleIndicators2" data-slide-to="1"></li>
                </ol>
                <div class="carousel-inner">
                  <div class="carousel-item active" data-interval="9999999999999">
                    <object id="mapa-svg" type="image/svg+xml" data="" onload="svgMapInit(document.getElementById('mapa-svg'));">
                    </object>
                  </div>
                  <div class="carousel-item" data-interval="99999999999999">
                    <object id="mapa-svg2" type="image/svg+xml" data="" onload="svgMapInit(document.getElementById('mapa-svg2'));">
                    </object>
                  </div>
                </div>
                <a class="carousel-control-prev" href="#carouselExampleIndicators2" role="button" data-slide="prev">
                  <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                  <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#carouselExampleIndicators2" role="button" data-slide="next">
                  <span class="carousel-control-next-icon" aria-hidden="true"></span>
                  <span class="sr-only">Next</span>
                </a>
              </div>
              <style type="text/css">
                .carousel .active {
                  background-color: white !important; }
                #carouselExampleIndicators2 .carousel-control-prev-icon,
                #carouselExampleIndicators2 .carousel-control-next-icon {
                  background-color: black;
                  border: 10px solid black;
                  padding: 1rem; }
                #carouselExampleIndicators2 .carousel-control-prev,
                #carouselExampleIndicators2 .carousel-control-next {
                  width: 0; }
              </style>
            </div>
          </div>
        </div>
      </section>

      <section style="background-color: #dedede;">
        <div class="container sobre-el-museo an">
          <div class="row container-titulo">
            <div class="col-12">
              <h1 id="textTituloSobreMuseo">M&aacute;s informaci&oacute;n</h1>

            </div>
          </div>
          <div class="row description-text" style="padding-bottom:0px;">
            <div class="col-12 col-md-6 mapa-museo">
              <?php if (get_post_meta(get_the_ID(), 'iframe', true)) :
                echo get_post_meta(get_the_ID(), 'iframe', true);
              endif; ?>
            </div>
            <div class="col-12 col-md-6">
              <div class="row ">
                <div class="col-12 col-sm-12 direccion-museo">
                  <?php if (get_post_meta(get_the_ID(), 'direccion', true)) :
                    echo get_post_meta(get_the_ID(), 'direccion', true);
                  endif; ?>
                </div>
              </div>
              <div class="row datos-contacto-museo-an">
                <div class="col-datos-contacto-museo col-12 d-block d-lg-flex justify-content-between">
                  <div class="dato-contacto-an" id="contacto1">

                    <a href="<?php if (get_post_meta(get_the_ID(), 'link-museo', true)) :
                                        echo get_post_meta(get_the_ID(), 'link-museo', true);
                                      endif; ?>" target="_blank">
                      Visitar<br>
                      Sitio de <?php the_title(); ?><br>
                      <?php
                      if (get_post_meta(get_the_ID(), 'link-museo', true)) :
                        echo get_post_meta(get_the_ID(), 'link-museo', true);
                      endif;
                      ?>
                    </a>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-12 compartir-an d-flex justify-content-end align-items-center share">
                  <p id="textTituloComparte3">Comparte</p>
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
          jQuery(function($) {
          $(document).ready(function() {

            $(window).scroll(function() {
              if ($(this).scrollTop() > 0) {
                $('#ir-arriba').slideDown(300);
              } else {
                $('#ir-arriba').slideUp(300);
              }
            });

          });
        });
        </script>
      </div>
    </div>

    <div class="loader">
      <i class="fas fa-spinner fa-spin fa-4x"></i>
    </div>

<?php endwhile;
endif; ?>
<?php
/**
 * Bug Fix
 * correccion carga footer
 */
if (get_polylang_lang_code() == "fr") {
  get_footer("frances");
} else if (get_polylang_lang_code() == "en") {
  get_footer("ingles");
} else if (get_polylang_lang_code() == "pt") {
  get_footer("portugues");
} else {
  get_footer();
}
?>
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
                        <a href="<?php echo admin_url('admin-post.php?action=redirigir_centralruc&accion=1'); ?>">
                            <input type="button" class='btn button btnIngresa more-link' value="Inicia sesión">
                        </a>
                    </div>
                </div>
                <div class="col-6 col-xl-6 col-lg-6 col-12">
                    <div class="Registrate">
                        <p>Regístrate con RUT o Pasaporte / DNI</p>
                        <a href="https://pbrwebqa-08.biblioredes.gob.cl/centralruc?portal=02ppvlocal&accion=2">
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
