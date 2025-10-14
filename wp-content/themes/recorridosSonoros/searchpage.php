<?php
/*
Template Name: Search Page
*/
?>
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
<?php
global $wp_query;
$avoid = ["Preguntas frecuentes"];
$meta_query = array();
$querySearch = $_GET['search'];
$paged = get_query_var('paged', 1);
$count = 1;
$my_query = new WP_Query('s=' . $querySearch);

if (get_polylang_lang_code() == "fr") {
  $textLinkSearch = "Voir plus...";
} else if (get_polylang_lang_code() == "en") {
  $textLinkSearch = "See more...";
} else if (get_polylang_lang_code() == "pt") {
  $textLinkSearch = "Ver mais...";
} else {
  $textLinkSearch = "Ver Más...";
}
?>

<div id="main">
  <section id="description-home">
    <div class="container main-description">
      <div class="row container-titulo">
        <div class="col-12">
          <h2 id="searchTitle"> Resultados:</h2>
        </div>
      </div>

      <?php
      if ($my_query->have_posts() &&  $_GET['search'] && $querySearch != "") {
        if (!empty($my_query->posts)) {
      ?>
          <?php foreach ($my_query->posts as $post) {

            if (!in_array($post->post_title, $avoid)) {
          ?>
              <div class="row description-text">
                <div class="col-12">
                  <h3><?= $count . " - " . sanitize_title($post->post_title) ?></h3>
                  <p><?= $post->post_content ?></p>
                  <p><a id="searchVinculo" href="<?= $post->guid ?>" target="_blank"><?= $textLinkSearch ?></a></p>
                </div>
              </div>

          <?php }
            $count++;
          }
        } else {
          ?>

          <p id="notFoundMessage">No se encontraron resultados.</p>
        <?php
        }
      } else {
        ?><p id="notFoundMessage">No se encontraron resultados.</p>
      <?php
      }
      ?>
    </div>
  </section>
</div>


<?php /**
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
} ?>