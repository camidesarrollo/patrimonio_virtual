<?php

if (!defined('ABSPATH')) {
    exit; // Salir si se accede directamente.
}

function printlist($metadata_prefix, $idioma)
{
  $count = 1;
  $first = true;
  while (true) {
    
    if ($idioma != '') {
      $metadata = get_post_meta(get_the_base_lang_post_ID(), "_$metadata_prefix-$idioma-$count", true);
    } else {
      $metadata = get_post_meta(get_the_base_lang_post_ID(), "_$metadata_prefix-$count", true);
    }
    if (!$metadata) {
      break;
    }
    

    if ($first) {
      $first = false;
    } else {
      echo ',';
    }
    $metadata = preg_replace('/\r?\n/', '<br>', $metadata);
    $metadata = preg_replace('/"/', '\\"', $metadata);

    if ($metadata_prefix == 'audio') {
      $metadata = getAttachmentUrlByName(preg_replace("/\\.[^\\.]+$/", '', $metadata), 'audio');
    }
    echo '"' . $metadata . '"';
    $count++;
  }
}

function printlist_acf($field_name, $idioma)
{
    $count = 0;
    $first = true;

    // Obtener el ID del post actual
    $post_id = get_the_ID();

    while (true) {
        // Obtener las entradas según el idioma
        if (!empty($idioma)) {
            $entradas = get_field($idioma, $post_id);
        } else {
            $entradas = get_field("es", $post_id);
        }

        // Validar que $entradas sea un array antes de acceder a sus índices
        if (!is_array($entradas) || !isset($entradas[$count])) {
            break;
        }

        // Validar que el campo $field_name exista dentro de $entradas[$count]
        if (!isset($entradas[$count][$field_name])) {
            break;
        }

        $metadata = $entradas[$count][$field_name];

        if ($first) {
            $first = false;
        } else {
            echo ',';
        }

        $metadata = preg_replace('/\r?\n/', '<br>', $metadata);
        $metadata = preg_replace('/"/', '\\"', $metadata);

        echo '"' . $metadata . '"';
        $count++;
    }
}


function printlists($metadata_prefix)
{
  $idiomas = getidiomas();
  $first = true;
  foreach ($idiomas as $code => $idioma) {
    if (!$first) {
      echo ',';
    } else {
      $first = false;
    }
    echo "$code:[";
    //printlist($metadata_prefix, $code);
    printlist_acf($metadata_prefix, $code);
    echo ']';
  }
}

function printimagelists()
{
  $i = 1;
  echo '[';
  $metadata = get_post_meta(get_the_base_lang_post_ID(), "_image-1-" . get_the_base_lang_post_ID() . "-1", true);
  while ($metadata) {
    if ($i > 1) {
      echo ',';
    }
    echo '[';
    $j = 1;
    while ($metadata) {
      if ($j > 1) {
        echo ',';
      }
      $metadata = preg_replace("/\\.[^\\.]+$/", '', $metadata);
      $metadata = getAttachmentUrlByName($metadata, 'image');
      echo '"' . $metadata . '"';
      $j++;
      $metadata = get_post_meta(get_the_base_lang_post_ID(), "_image-$i-" . get_the_base_lang_post_ID() . "-$j", true);
    }
    echo ']';
    $i++;
    $metadata = get_post_meta(get_the_base_lang_post_ID(), "_image-$i-" . get_the_base_lang_post_ID() . "-1", true);
  }
  echo ']';
}

function printimagelists_acf()
{
  $post_id = get_the_ID();

  $metadata = get_field("conjunto_de_imagenes", $post_id);

  echo '[';
  foreach ($metadata as $clave => $valor) {
    if ($clave > 0) {
      echo ',';
    }
    // Verificar si el array de imágenes existe y no está vacío
    if (isset($valor["imagenes"]) && is_array($valor["imagenes"])) {
        // Utilizar array_map para envolver cada elemento en comillas dobles
        $urls = array_map(function ($url) {
            return '"' . $url . '"';
        }, $valor["imagenes"]);

        // Imprimir la cadena resultante
        echo '['.implode(', ', $urls).']';
    }
  }
  echo ']';
}

function printids_acf()
{
  $post_id = get_the_ID();

  $ids = get_field("ids", $post_id);
  $newArray = array();

  foreach ($ids as $item) {
      if (isset($item['id'])) {
          $newArray[] = '"' . $item['id'] . '"';
      }
  }

  echo '[' . implode(', ', $newArray) . ']';

}
function getidiomas()
{
  return allidiomas();
}

function printidiomasoptions($track)
{
  $idiomas = getidiomas();
  foreach ($idiomas as $code => $idioma) {
    $disabled = (get_post_meta(get_the_base_lang_post_ID(), '_audio-' . $code . '-' . $track, true)) ? "" : "disabled";
    echo '                          <option value="' . $code . '"' . $disabled . '>' . $idioma . '</option>' . "\n";
  }
}

function printidiomascodes()
{
  $idiomas = getidiomas();
  $first = true;
  foreach ($idiomas as $code => $idioma) {
    if (!$first) {
      echo ',';
    } else {
      $first = false;
    }
    echo "'$code'";
  }
}


function replaceAccentsAndSpaces($str)
{
  $str = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
  $str = str_replace('\'a', 'a', $str);
  $str = str_replace('\'e', 'e', $str);
  $str = str_replace('\'i', 'i', $str);
  $str = str_replace('\'o', 'o', $str);
  $str = str_replace('\'u', 'u', $str);
  $str = str_replace(' ', '-', $str);
  return $str;
}

function getAttachmentUrlByName($name, $metadata_prefix)
{
  if ($name == '') {
    return '';
  }
  $args = array(
    'post_type' => 'attachment',
    'name' => sanitize_title($name),
    'posts_per_page' => 1,
    'post_status' => 'inherit',
  );
  $posts = get_posts($args);
  $post = $posts ? array_pop($posts) : null;
  if (!$post) {
    return sanitize_title($name);
  }
  if ($metadata_prefix == 'image') {
    $image_alt = get_post_meta($post->ID, '_wp_attachment_image_alt', true);
    if (empty($image_alt)) {
      $image_alt = $post->post_title;
    }
    if (empty($image_alt)) {
      $image_alt = $post->post_excerpt;
    }
    $image_url = wp_get_attachment_url($post->ID);
    return $image_url . '|' . $image_alt;
  } else {
    return wp_get_attachment_url($post->ID);
  }
}