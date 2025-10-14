<?php

/**
 * Plugin Name: Biblioredes
 * Plugin URI: https://www.genesys.cl/
 * Description: Plugin para el dearrrollo de cursos sonoros para el Ministerio de las Artes, la Cultura y el Patrimonio.
 * Version: 1.0
 * Author: Genesys
 * Author URI: https://www.genesys.cl/
 */

if (!defined('ABSPATH')) {
	exit;
}


add_action('admin_menu', 'biblioredes_init_options');
function biblioredes_init_options()
{
	add_options_page('Configuración Biblioredes', 'Biblioredes', 'manage_options', 'biblioredes-options', 'biblioredes_options');
}

add_action('admin_init', 'biblioredes_register_settings');
function biblioredes_register_settings()
{
	$count = 1;
	while (true) {
		$n1 = 'biblioredes_idioma_' . $count;
		$n2 = 'biblioredes_idioma_code_' . $count;
		$a1 = get_option($n1);
		$a2 = get_option($n2);
		//echo "$n1: |$a1|<br>\n";
		//echo "$n2: |$a2|<br>\n";
		if ($a1 == '' || $a2 == '') {
			break;
		}
		register_setting('biblioredes_options_group', $n1);
		register_setting('biblioredes_options_group', $n2);
		$count++;
	}
}

function biblioredes_options()
{
	add_action('admin_footer', 'ajax_javascript_plugin_options');
?>
	<div class="wrap">
		<h1>Biblioredes</h1>
		<form method="post" action="options.php">
			<?php settings_fields('biblioredes_options_group'); ?>
			<h3>Idiomas</h3>

			<table style="border: 1px solid #ddd; background: #f1f1f1; width: 100%;">
				<thead style="background: #f1f1f1;">
					<tr style="padding-bottom: 12px;">
						<th class="left" style="width: 70%;">Idioma</th>
						<th style="width: 30%;">Código</th>
					</tr>
				</thead>
				<tbody id="language_tbody" style="border: 1px solid black; background: #f9f9f9;">
					<?php
					$count = 1;
					while (true) {
						if (
							trim(get_option('biblioredes_idioma_' . $count)) == '' ||
							trim(get_option('biblioredes_idioma_code_' . $count)) == ''
						) {
							break;
						}
						echo '<tr id="language_tr_' . $count . '" style="border: 1px solid black;">' . "\n";
						echo "<td>\n";
						echo '<input type="text" id="biblioredes_idioma_' . $count . '" ';
						echo 'name="biblioredes_idioma_' . $count . '" value="';
						echo get_option('biblioredes_idioma_' . $count);
						echo '" style="padding: 5px; margin: 5px; width: -webkit-calc(100% - 10px); width: -moz-calc(100% - 10px); -webkit-box-sizing: border-box; -moz-box-sizing: border-box; -o-box-sizing: border-box; -ms-box-sizing: border-box; box-sizing: border-box;" />' . "\n";
						echo "</td>\n";
						echo '<td>';
						echo '<input type="text" id="biblioredes_idioma_code_' . $count;
						echo '" name="biblioredes_idioma_code_' . $count . '" value="';
						echo get_option('biblioredes_idioma_code_' . $count);
						echo '" />' . "\n";
						echo "</td>\n";
						echo '<td><span onclick="delete_language(event, ' . $count . ');"><font size="+1"><b>X</b></font></span></td>' . "\n";
						echo "</tr>\n";
						$count++;
					}
					?>
					<tr>
						<td style="padding: 10px;">
							<input type="submit" name="delete-language" value="<?php echo __('Add'); ?>" onclick="add_language(event, <?php echo $count; ?>);"></input>
						</td>
					</tr>
				</tbody>
			</table>
			<?php submit_button(); ?>
		</form>
	</div>
<?php
}

/* Permitir subir archivos .svg */
function add_svg_to_upload_mimes($upload_mimes)
{
	$upload_mimes['svg'] = 'image/svg+xml';
	$upload_mimes['svgz'] = 'image/svg+xml';
	return $upload_mimes;
}
add_filter('upload_mimes', 'add_svg_to_upload_mimes', 10, 1);


function post_metaboxes()
{
	if (get_post_type() == 'post') {
		$category_names = array();
		foreach (get_the_category() as $wpterm) {
			array_push($category_names, $wpterm->name);
		}
		if (
			in_array('recorridos-sonoros', $category_names) ||
			in_array('sound-tour-english', $category_names) ||
			in_array('visites-sonores-frances', $category_names) ||
			in_array('tours-de-som-portugues', $category_names)
		) {
			remove_meta_box('postexcerpt', 'post', 'normal');
			remove_meta_box('trackbacksdiv', 'post', 'normal');
			remove_meta_box('commentstatusdiv', 'post', 'normal');
			remove_meta_box('slugdiv', 'post', 'normal');
			remove_meta_box('authordiv', 'post', 'normal');

			$idiomas = allidiomas();
			foreach ($idiomas as $key => $value) {
				add_meta_box('recorrido_sonoro_' . $key, 'Audio y Textos ' . $value, 'metabox_language_content', 'post', 'normal', 'high', [$key, $value]);
			}

			add_meta_box('recorrido_sonoro_images', 'Lista de Imágenes', 'metabox_images_content', 'post', 'normal', 'high', [count($idiomas)]);

			add_meta_box('recorrido_sonoro_mapa', 'Mapa', 'metabox_map_content', 'post', 'normal', 'high');

			if (isset($_GET['debug'])) {
				add_meta_box('recorrido_sonoro_all_metadata', 'All Metadata', 'metabox_all_metadata', 'post', 'normal', 'high');
			}

			add_action('admin_footer', 'ajax_javascript_post');
		}
	}
}
add_action('add_meta_boxes', 'post_metaboxes');

// Write our JS below here
function ajax_javascript_plugin_options()
{
?>
	<div id="loading-animation" class="overlay" style="display: none; position: fixed; width: 100%; height: 100%; top: 0; left: 0; z-index: 999; background: rgba(255,255,255,0.8); center no-repeat;">
		LOADING...
	</div>
	<script>
		jQuery(document).ready(function($) {
			tinymce.init({
				mode: "exact",
				elements: 'pre-details',
				theme: "modern",
				skin: "lightgray",
				menubar: false,
				statusbar: false,
				toolbar: [
					"bold italic | alignleft aligncenter alignright | bullist numlist outdent indent | undo redo"
				],
				plugins: "paste",
				paste_auto_cleanup_on_paste: true,
				paste_postprocess: function(pl, o) {
					o.node.innerHTML = o.node.innerHTML.replace(/&nbsp;+/ig, " ");
				}
			});
		});
	</script>
	<script type="text/javascript">
		function delete_language(e, count) {
			//				console.log('delete_language: ' + count);
			e.preventDefault();
			document.getElementById('loading-animation').style.display = 'block';
			jQuery.ajax({
				type: "post",
				url: ajaxurl,
				data: {
					action: 'delete_language',
					count: count
				},
				success: function(response) {
					// console.log('Got this from the server: ' + response);
					location.reload();
				}
			}); //close jQuery.ajax(
		}

		function add_language(e, count) {
			//				console.log('add_language: ' + count);
			e.preventDefault();
			let new_tbody = '';
			for (i = 1; i < count; i++) {
				let elem = document.getElementById('language_tr_' + i);
				new_tbody = new_tbody + elem.outerHTML;
			}
			//				console.log('new_tbody: ' + new_tbody);
			let tbody = document.getElementById('language_tbody');
			tbody.innerHTML = new_tbody +
				'<tr id="language_tr_' + count + '" style="border: 1px solid black;">' + "\n" +
				"<td>\n" +
				'<input type="text" id="biblioredes_idioma_' + count + '" name="biblioredes_idioma_' + count + '" value="" style="padding: 5px; margin: 5px; width: -webkit-calc(100% - 10px); width: -moz-calc(100% - 10px); -webkit-box-sizing: border-box; -moz-box-sizing: border-box; -o-box-sizing: border-box; -ms-box-sizing: border-box; box-sizing: border-box;" />' + "\n" +
				"</td>\n" +
				"<td>\n" +
				'<input type="text" id="biblioredes_idioma_code_' + count + '" name="biblioredes_idioma_code_' + count + '" value="" />' + "\n" +
				"</td>\n<td>\n" +
				'<span onclick="delete_language(event, ' + count + ');"><font size="+1"><b>X</b></font></span>' +
				"</td>\n</tr>\n" +
				'<tr><td style="padding: 10px;">' + "\n" +
				'<input type="submit" name="delete-language" value="<?php echo __('Add'); ?>" onclick="add_language(event, ' + (count + 1) + ');"></input>' + "\n" +
				"</td></tr>\n";


			//				document.getElementById('loading-animation').style.display = 'block';
			jQuery.ajax({
				type: "post",
				url: ajaxurl,
				data: {
					action: 'add_language',
					count: count
				},
				success: function(response) {
					// console.log('Got this from the server: ' + response);
					//						location.reload();
				}
			}); //close jQuery.ajax(

		}
	</script>
<?php
}

function ajax_javascript_post()
{
?>
	<div id="loading-animation" class="overlay" style="display: none; position: fixed; width: 100%; height: 100%; top: 0; left: 0; z-index: 999; background: rgba(255,255,255,0.8); center no-repeat;">
		LOADING...
	</div>
	<script type="text/javascript">
		function update_lang_entry(e, postid, suffix) {
			//					console.log('update_lang_entry: ' + postid + ' ' + suffix);
			e.preventDefault();
			jQuery.ajax({
				type: "post",
				url: ajaxurl,
				data: {
					action: 'update_lang_entry',
					postid: postid,
					suffix: suffix,
					title: document.getElementById('title-' + suffix).value,
					audio: document.getElementById('audio-' + suffix).value,
					text: document.getElementById('text-' + suffix).value
				} //,
				//						beforeSend: function() {jQuery("#loading").show("slow");}, // show loading
				//						complete: function() { jQuery("#loading").hide("fast");}, // stop showing loading
				//						success: function(response) { console.log('Got this from the server: ' + response); }
			}); //close jQuery.ajax(
		}

		function delete_lang_entry(e, postid, suffix) {
			document.getElementById('loading-animation').style.display = 'block';
			//					console.log('delete_lang_entry: ' + postid + ' ' + suffix);
			jQuery.ajax({
				type: "post",
				url: ajaxurl,
				data: {
					action: 'delete_lang_entry',
					postid: postid,
					suffix: suffix
				},
				success: function(response) {
					// console.log('Got this from the server: ' + response);
					location.reload();
				}
			}); //close jQuery.ajax(
		}

		function add_lang_entry(e, postid, suffix) {
			document.getElementById('loading-animation').style.display = 'block';
			//					console.log('add_lang_entry: ' + postid + ' ' + suffix);
			jQuery.ajax({
				type: "post",
				url: ajaxurl,
				data: {
					action: 'add_lang_entry',
					postid: postid,
					suffix: suffix
				},
				success: function(response) {
					// console.log('Got this from the server: ' + response);
					location.reload();
				}
			}); //close jQuery.ajax(
		}

		function delete_image(e, postid, count, i) {
			document.getElementById('loading-animation').style.display = 'block';
			//					console.log('Deleting image ' + count + ', ' + i + ' (postid=' + postid + ')');
			let suffix = '' + count + '-' + postid + '-' + i;
			jQuery.ajax({
				type: "post",
				url: ajaxurl,
				data: {
					action: 'delete_image',
					postid: postid,
					suffix: suffix
				},
				success: function(response) {
					// console.log('Got this from the server: ' + response);
					location.reload();
				}
			}); //close jQuery.ajax(
		}

		//Cristian Diaz 04022021 : Se modifica ID de la imagen
		function add_image(e, postid, count, i) {
			document.getElementById('loading-animation').style.display = 'block';
			//					console.log('Adding image ' + count + ', ' + i + ' (postid=' + postid + ')');
			let suffix = '' + count + '-' + postid + '-' + i;
			jQuery.ajax({
				type: "post",
				url: ajaxurl,
				data: {
					action: 'add_image',
					postid: postid,
					suffix: suffix
				},
				success: function(response) {
					console.log('Got this from the server: ' + response);
					location.reload();
				}
			}); //close jQuery.ajax(
		}

		function update_image_entry(e, postid, count, i) {
			console.log(ajaxurl);
			console.log('Updating image entry ' + count + ', ' + i + ' (postid=' + postid + ')');
			e.preventDefault();
			let images = '';
			//					console.log("i = " + i);
			for (j = 1; j < i; j++) {
				id = 'image-' + count + '-' + postid + '-' + j;
				console.log(document.getElementById(id).value);
				images = images + document.getElementById(id).value;
				if (j < i - 1) {
					images = images + '|';
				}
			}
			console.log("images = " + images);
			jQuery.ajax({
				type: "post",
				url: ajaxurl,
				data: {
					action: 'update_image_entry',
					postid: postid,
					count: count,
					i: i,
					images: images
				},
				success: function(response) {
					console.log('Got this from the server: ' + response);
				}
			}); //close jQuery.ajax(
		}

		//Cristian Diaz 04022021 : Se modifica ID de la imagen
		function add_image_entry(e, postid, count) {
			document.getElementById('loading-animation').style.display = 'block';
			console.log('Adding to image entry ' + count + ' (postid=' + postid + ')');
			let suffix = '' + count + '-' + postid + '-1';
			jQuery.ajax({
				type: "post",
				url: ajaxurl,
				data: {
					action: 'add_image',
					postid: postid,
					suffix: suffix
				},
				success: function(response) {
					console.log('Got this from the server: ' + response);
					location.reload();
				}
			}); //close jQuery.ajax(
		}





		function delete_map_entry(e, postid, count) {
			document.getElementById('loading-animation').style.display = 'block';
			//					console.log('Deleting map entry ' + count + ' (postid=' + postid + ')');
			jQuery.ajax({
				type: "post",
				url: ajaxurl,
				data: {
					action: 'delete_map_entry',
					postid: postid,
					count: count
				},
				success: function(response) {
					// console.log('Got this from the server: ' + response);
					location.reload();
				}
			}); //close jQuery.ajax(
		}

		function add_map_entry(e, postid, count) {
			document.getElementById('loading-animation').style.display = 'block';
			//					console.log('Adding to map entry ' + count + ' (postid=' + postid + ')');
			jQuery.ajax({
				type: "post",
				url: ajaxurl,
				data: {
					action: 'add_map_entry',
					postid: postid,
					count: count
				},
				success: function(response) {
					// console.log('Got this from the server: ' + response);
					location.reload();
				}
			}); //close jQuery.ajax(
		}

		function update_map_entry(e, postid, count) {
			//					console.log('Updating map entry ' + count + ' (postid=' + postid + ')');
			e.preventDefault();
			let svg_file = document.getElementById('map-svg-file').value;
			let svg_file2 = document.getElementById('map-svg-file2').value;
			let svg_elem = '';
			for (i = 1; i < count; i++) {
				id = 'map-elem-' + i;
				svg_elem = svg_elem + document.getElementById(id).value;
				if (i < count - 1) {
					svg_elem = svg_elem + '|';
				}
			}
			//					console.log("svg_file = " + svg_file);
			//					console.log("svg_file2 = " + svg_file2);
			//					console.log("svg_elem = " + svg_elem);
			jQuery.ajax({
				type: "post",
				url: ajaxurl,
				data: {
					action: 'update_map_entry',
					postid: postid,
					count: count,
					svg_file: svg_file,
					svg_file2: svg_file2,
					svg_elem: svg_elem
				},
				success: function(response) {
					// console.log('Got this from the server: ' + response);
				}
			}); //close jQuery.ajax(
		}
	</script>
<?php
}


// PHP functions for AJAX
add_action('wp_ajax_delete_language', 'delete_language');
function delete_language()
{
	global $wpdb; // this is how you get access to the database
	$count = $_POST['count'];
	$n1 = 'biblioredes_idioma_' . $count;
	$n2 = 'biblioredes_idioma_code_' . $count;
	delete_option($n1);
	delete_option($n2);
	//echo "get_option('$n1')=|" . get_option($n1) . "|\n";
	//echo "get_option('$n2')=|" . get_option($n2) . "|\n";
	do {
		$nn1 = 'biblioredes_idioma_' . ($count + 1);
		$nn2 = 'biblioredes_idioma_code_' . ($count + 1);
		$a1 = get_option($nn1);
		$a2 = get_option($nn2);
		update_option($n1, $a1);
		update_option($n2, $a2);
		$n1 = $nn1;
		$n2 = $nn2;
		$count++;
	} while (trim($a1) != '' || trim($a2) != '');
	wp_die(); // this is required to terminate immediately and return a proper response
}

add_action('wp_ajax_add_language', 'add_language');
function add_language()
{
	global $wpdb; // this is how you get access to the database
	$count = $_POST['count'];
	$n1 = 'biblioredes_idioma_' . $count;
	$n2 = 'biblioredes_idioma_code_' . $count;
	register_setting('biblioredes_options_group', $n1);
	register_setting('biblioredes_options_group', $n2);
	update_option($n1, ' ');
	update_option($n2, ' ');
	//echo "get_option('$n1')=|" . get_option($n1) . "|\n";
	//echo "get_option('$n2')=|" . get_option($n2) . "|\n";
	wp_die(); // this is required to terminate immediately and return a proper response
}

add_action('wp_ajax_update_lang_entry', 'update_lang_entry');
function update_lang_entry()
{
	global $wpdb; // this is how you get access to the database
	$postid = $_POST['postid'];
	$suffix = $_POST['suffix'];
	update_post_meta($postid, '_title-' . $suffix, $_POST['title']);
	update_post_meta($postid, '_audio-' . $suffix, $_POST['audio']);
	update_post_meta($postid, '_text-' . $suffix, $_POST['text']);
	wp_die(); // this is required to terminate immediately and return a proper response
}

add_action('wp_ajax_delete_lang_entry', 'delete_lang_entry');
function delete_lang_entry()
{
	global $wpdb; // this is how you get access to the database
	$postid = $_POST['postid'];
	$suffix = $_POST['suffix'];
	delete_post_meta($postid, '_title-' . $suffix);
	delete_post_meta($postid, '_audio-' . $suffix);
	delete_post_meta($postid, '_text-' . $suffix);

	// Shift all remaining meta
	$lang_count = explode('-', $suffix);
	$lang = $lang_count[0];
	$count = $lang_count[1];
	$i = $count;
	while (metadata_exists('post', $postid, '_title-' . $lang . '-' . ($i + 1))) {
		update_meta_key('_title-' . $lang . '-' . ($i + 1), '_title-' . $lang . '-' . $i, $postid);
		$i++;
	}
	$i = $count;
	while (metadata_exists('post', $postid, '_audio-' . $lang . '-' . ($i + 1))) {
		update_meta_key('_audio-' . $lang . '-' . ($i + 1), '_audio-' . $lang . '-' . $i, $postid);
		$i++;
	}
	$i = $count;
	while (metadata_exists('post', $postid, '_text-' . $lang . '-' . ($i + 1))) {
		update_meta_key('_text-' . $lang . '-' . ($i + 1), '_text-' . $lang . '-' . $i, $postid);
		$i++;
	}

	wp_die(); // this is required to terminate immediately and return a proper response
}

add_action('wp_ajax_add_lang_entry', 'add_lang_entry');
function add_lang_entry()
{
	global $wpdb; // this is how you get access to the database
	$postid = $_POST['postid'];
	$suffix = $_POST['suffix'];
	add_post_meta($postid, '_title-' . $suffix, 'Título');
	add_post_meta($postid, '_audio-' . $suffix, 'audio.mp3');
	add_post_meta($postid, '_text-' . $suffix, 'Texto del recorrido');
	wp_die(); // this is required to terminate immediately and return a proper response
}


add_action('wp_ajax_delete_image', 'delete_image');
function delete_image()
{
	global $wpdb; // this is how you get access to the database
	$postid = $_POST['postid'];
	$suffix = $_POST['suffix'];
	//echo "delete_post_meta($postid, '_image-' . $suffix);";
	delete_post_meta($postid, '_image-' . $suffix);

	// Shift all remaining meta
	$lang_count = explode('-', $suffix);
	$lang = $lang_count[0];
	$count = $lang_count[1];
	$i = $count;
	while (metadata_exists('post', $postid, '_image-' . $lang . '-' . ($i + 1))) {
		update_meta_key('_image-' . $lang . '-' . ($i + 1), '_image-' . $lang . '-' . $i, $postid);
		$i++;
	}

	wp_die(); // this is required to terminate immediately and return a proper response
}

//Cristian Diaz 04022021 : Se modifica funcion de guardado, al agregar una imagen.
add_action('wp_ajax_add_image', 'add_image');
function add_image()
{
	global $wpdb; // this is how you get access to the database
	$postid = $_POST['postid'];
	$suffix = $_POST['suffix'];
	print_r($_POST);
	//exit();
	//echo "add_post_meta($postid, '_image-' . $suffix, 'image.jpg');";
	update_post_meta($postid, '_image-' . $suffix, 'image.jpg', true);
	wp_die(); // this is required to terminate immediately and return a proper response
}

add_action('wp_ajax_update_image_entry', 'update_image_entry');
function update_image_entry()
{
	global $wpdb; // this is how you get access to the database
	$postid = $_POST['postid'];
	$count = $_POST['count'];
	$i = $_POST['i'];
	$images = explode('|', $_POST['images']);
	//echo "i=$i\n";
	//echo "images=" . $_POST['images'] . "\n";
	for ($j = 1; $j < $i; $j++) {
		//echo "update_post_meta($postid, '_image-" . $count . "-" . $j . "', '" . $images[$j - 1] . "');\n";
		//Cristian Diaz 04022021 :Se modifica ID de la imagen
		update_post_meta($postid, '_image-' . $count . '-' . $postid . '-' . $j, $images[$j - 1]);
	}
	wp_die(); // this is required to terminate immediately and return a proper response
}




add_action('wp_ajax_delete_map_entry', 'delete_map_entry');
function delete_map_entry()
{
	global $wpdb; // this is how you get access to the database
	$postid = $_POST['postid'];
	$count = $_POST['count'];
	//echo "delete_post_meta($postid, '_map-elem-' . $count);";
	delete_post_meta($postid, '_map-elem-' . $count);

	// Shift all remaining meta
	$i = $count;
	while (metadata_exists('post', $postid, '_map-elem-' . ($i + 1))) {
		update_meta_key('_map-elem-' . ($i + 1), '_map-elem-' . $i, $postid);
		$i++;
	}

	wp_die(); // this is required to terminate immediately and return a proper response
}

add_action('wp_ajax_add_map_entry', 'add_map_entry');
function add_map_entry()
{
	global $wpdb; // this is how you get access to the database
	$postid = $_POST['postid'];
	$count = $_POST['count'];
	//echo "add_post_meta($postid, '_map-elem-' . $count, 'polygonXX');";
	if (get_post_meta($postid, '_map-elem-' . $count) != '') {
		update_post_meta($postid, '_map-elem-' . $count, 'polygonXX');
	} else {
		add_post_meta($postid, '_map-elem-' . $count, 'polygonXX');
	}
	wp_die(); // this is required to terminate immediately and return a proper response
}


add_action('wp_ajax_update_map_entry', 'update_map_entry');
function update_map_entry()
{
	global $wpdb; // this is how you get access to the database
	$postid = $_POST['postid'];
	$count = $_POST['count'];
	$svg_file = $_POST['svg_file'];
	$svg_file2 = $_POST['svg_file2'];
	//echo "svg_file=" . $_POST['svg_file'] . "\n";
	if (get_post_meta($postid, '_map_svg_file') != '') {
		update_post_meta($postid, '_map_svg_file', $svg_file);
	} else {
		add_post_meta($postid, '_map_svg_file', $svg_file);
	}
	if (get_post_meta($postid, '_map_svg_file2') != '') {
		update_post_meta($postid, '_map_svg_file2', $svg_file2);
	} else {
		add_post_meta($postid, '_map_svg_file2', $svg_file2);
	}

	$svg_elem = explode('|', $_POST['svg_elem']);
	//echo "svg_elem=" . $_POST['svg_elem'] . "\n";
	for ($i = 1; $i < $count; $i++) {
		//echo "update_post_meta($postid, '_map-elem-' . $i, " . $svg_elem[$i - 1] . ");\n";
		update_post_meta($postid, '_map-elem-' . $i, $svg_elem[$i - 1]);
	}
	wp_die(); // this is required to terminate immediately and return a proper response
}



function get_the_base_lang_post_ID()
{
	$this_post_id = get_the_ID();
	// test if the plugin polylang is present
	if (isset($GLOBALS["polylang"])) {
		$translations = $GLOBALS["polylang"]->model->post->get_translations(get_the_ID());
		// $translations contains an array with all translations of the post}
		$min_post_id = 1000000;
		$found_this_post_id = false;
		foreach ($translations as $lang_code => $post_id) {
			if ($post_id < $min_post_id) {
				$min_post_id = $post_id;
			}
			if ($post_id == $this_post_id) {
				$found_this_post_id = true;
			}
		}
		if ($found_this_post_id) {
			return $min_post_id;
		}
	}
	return $this_post_id;
}

function get_polylang_lang_code()
{
	$this_post_id = get_the_ID();
	$rtv = 'es';
	// test if the plugin polylang is present
	if (isset($GLOBALS["polylang"])) {
		$translations = $GLOBALS["polylang"]->model->post->get_translations(get_the_ID());
		// $translations contains an array with all translations of the post}
		$found_this_post_id = false;
		foreach ($translations as $lang_code => $post_id) {
			if ($post_id == $this_post_id) {
				$found_this_post_id = true;
				$rtv = $lang_code;
			}
		}
	}
	return $rtv;
}



function allidiomas()
{
	$idiomas = array();
	$count = 1;
	while (true) {
		$idioma = get_option('biblioredes_idioma_' . $count);
		$idioma_code = get_option('biblioredes_idioma_code_' . $count);
		if (trim($idioma) == '' || trim($idioma_code) == '') {
			break;
		}
		$idiomas[$idioma_code] = $idioma;
		$count++;
	}
	/*
	    $idiomas_meta = get_post_meta(104, 'idioma', false);
	    if ($idiomas_meta) {
		    foreach ($idiomas_meta as $idioma_meta) {
	            $pair = explode(",", $idioma_meta);
	            $i[$pair[0]] = $pair[1];
	        }
	    }
*/
	return $idiomas;
}

function metabox_language_content($post, $args)
{
	global $wp_meta_boxes;
	$idioma_code = $args['args'][0];
	$idioma = $args['args'][1];

	wp_nonce_field(basename(__FILE__), "meta-box-nonce");
	$metadata = has_meta($post->ID);
?>
	<div class="wrap">
		<?php
		$count = 1;
		$id = get_the_base_lang_post_ID();
		while (true) {
			$title_meta = get_post_meta($id, "_title-$idioma_code-$count", true);
			$audio_meta = get_post_meta($id, "_audio-$idioma_code-$count", true);
			$text_meta = get_post_meta($id, "_text-$idioma_code-$count", true);
			if ($title_meta == '' && $audio_meta == '' && $text_meta == '') {
				break;
			}
			print_audio_text_item($idioma_code, $count, $title_meta, $audio_meta, $text_meta);
			$count++;
		}
		$suffix = $idioma_code . '-' . $count;
		?>
		<input type="submit" name="add-lang-entry" value="<?php echo __('Add') . ' ' . __('Post'); ?>" onclick="add_lang_entry(event, '<?php echo get_the_base_lang_post_ID() . "', '" . $suffix; ?>');"></input>
	</div>
<?php
}

function print_audio_text_item($idioma_code, $count, $title_meta, $audio_meta, $text_meta)
{
	$suffix = $idioma_code . '-' . $count;
?>
	<table style="border-spacing: 0px; border: 2px solid #f1f1f1; background: #f1f1f1; width: 100%; margin-top: 20px; margin-bottom: 7px;">
		<thead>
			<tr style="padding-bottom: 20px;">
				<th class="left" style="width: auto;">
					<?php echo "Entrada $count"; ?>
				</th>
			</tr>
		</thead>
		<tbody style="background: #f9f9f9;">
			<tr>
				<td style="width: 20%;">Título</td>
				<td style="width: 80%;">
					<input id="title-<?php echo $suffix; ?>" size="30" value="<?php echo $title_meta; ?>" style="padding: 5px; margin: 5px; width: -webkit-calc(100% - 10px); width: -moz-calc(100% - 10px); -webkit-box-sizing: border-box; -moz-box-sizing: border-box; -o-box-sizing: border-box; -ms-box-sizing: border-box; box-sizing: border-box;"></input>
				</td>
			</tr>
			<tr>
				<td style="width: 20%;">Audio</td>
				<td style="width: 80%;">
					<input id="audio-<?php echo $suffix; ?>" size="30" value="<?php echo $audio_meta; ?>" style="padding: 5px; margin: 5px; width: -webkit-calc(100% - 10px); width: -moz-calc(100% - 10px); -webkit-box-sizing: border-box; -moz-box-sizing: border-box; -o-box-sizing: border-box; -ms-box-sizing: border-box; box-sizing: border-box;"></input>
				</td>
			</tr>
			<tr>
				<td style="width: 20%;">Texto</td>
				<td style="width: 80%;">

					<!--Cristian Diaz 03022021 : Se modifica el campo de texto y se cambia por la funcion de wordpress wp_editor(con sus correspondientes campos)-->
					<?php wp_editor($text_meta, "text-" . $suffix, array('media_buttons' => false, 'quicktags' => true, 'name' => "text-" . $suffix, 'textarea_rows' => 1, 'tinymce' => array(
						'theme_advanced_disable' => 'bold,italic,underline'
					))); ?>
				</td>
			</tr>
			<tr>
				<td></td>
				<td style="padding-bottom: 10px;">
					<input type="submit" name="delete-lang-entry" value="<?php echo __('Delete'); ?>" onclick="delete_lang_entry(event, '<?php echo get_the_base_lang_post_ID() . "', '" . $suffix; ?>');"></input>
					<input type="submit" name="update-lang-entry" value="<?php echo __('Update'); ?>" onclick="update_lang_entry(event, '<?php echo get_the_base_lang_post_ID() . "', '" . $suffix; ?>');"></input>
				</td>
			</tr>
		</tbody>
	</table>
<?php
}
//Cristian Diaz 04022021 : Se modifica ID de la imagen
function metabox_images_content($post, $args)
{
	//$meta = get_post_meta(102);
	//print_r($meta);
?>
	<div class="wrap">

		<?php
		$postId = get_the_base_lang_post_ID();
		$count = 1;
		$image = get_post_meta(get_the_base_lang_post_ID(), "_image-$count-$postId-1", true);
		while ($image != '') {
			print_images_entry($count++);
			$image = get_post_meta(get_the_base_lang_post_ID(), "_image-$count-$postId-1", true);
		}

		?>
	</div>
	<input type="submit" name="add-image-entry" value="<?php echo __('Add') . ' ' . __('Post'); ?>" onclick="add_image_entry(event, <?php echo get_the_base_lang_post_ID() . ', ' . $count; ?>);"></input>
<?php
}

//Cristian Diaz 04022021 : Se modifica ID de la imagen
function print_images_entry($count)
{
?>
	<table style="border-spacing: 0px; border: 2px solid #f1f1f1; background: #f1f1f1; width: 100%; margin-top: 20px; margin-bottom: 7px;">
		<thead>
			<tr style="padding-bottom: 20px;">
				<th class="left" style="width: auto;">
					<?php echo "Entrada $count\n"; ?>
				</th>
			</tr>
		</thead>
		<tbody style="background: #f9f9f9;">
			<?php
			$i = 1;
			$postId = get_the_base_lang_post_ID();
			$image = get_post_meta(get_the_base_lang_post_ID(), "_image-$count-$postId-$i", true);
			print_r($image);
			if ($image != '') {
				do {

					print '<tr>' . "\n" . '<td style="width: 20%;">Imagen ' . $count . "</td>\n";
					print '<td style="width: 80%;">' . "\n";
					print '<input id="image-' . $count . '-' . $postId . '-' . $i . '" size="30" value="' . $image . '" style="padding: 5px; margin: 5px; width: -webkit-calc(100% - 50px); width: -moz-calc(100% - 50px); -webkit-box-sizing: border-box; -moz-box-sizing: border-box; -o-box-sizing: border-box; -ms-box-sizing: border-box; box-sizing: border-box;"></input>' . "\n";
					$id = $count . '-' . $i;
					print '<span id="image-delete-' . $postId . '-' . $id . '" onclick="delete_image(event, ' . get_the_base_lang_post_ID() . ', ' . $count . ', ' . $i . ');"><font size="+1"><b>X</b></font></span>' . "\n";
					print "</td>\n</tr>\n";
					$i++;
					$image = get_post_meta(get_the_base_lang_post_ID(), "_image-$count-$postId-$i", true);
				} while ($image != '');
			}
			?>
			<tr>
				<td></td>
				<td style="padding: 7px; padding-bottom: 10px;">
					<input type="submit" name="add-image" value="<?php echo __('Add'); ?>" onclick="add_image(event, <?php echo get_the_base_lang_post_ID() . ', ' . $count . ', ' . $i; ?>);"></input>
					<input type="submit" name="update-image-entry" value="<?php echo __('Update'); ?>" onclick="update_image_entry(event, <?php echo get_the_base_lang_post_ID() . ', ' . $count . ', ' . $i; ?>);"></input>
				</td>
			</tr>
		</tbody>
	</table>

<?php
}

function metabox_map_content($post, $args)
{
?>
	<div class="wrap">
		<table style="border-spacing: 0px; border: 2px solid #f1f1f1; background: #f1f1f1; width: 100%; margin-top: 20px; margin-bottom: 7px;">
			<thead>
				<tr style="padding-bottom: 20px;">
					<th class="left" style="width: auto;">
						<?php echo 'Elementos SVG'; ?>
					</th>
				</tr>
			</thead>
			<tbody style="background: #f9f9f9;">
				<tr>
					<?php
					print '<tr>' . "\n" . '<td style="width: 20%;">Archivo SVG' . "</td>\n";
					print '<td style="width: 80%;">' . "\n";
					$svg_file = get_post_meta(get_the_base_lang_post_ID(), "_map_svg_file", true);
					print '<input id="map-svg-file" size="30" value="' . $svg_file . '" style="padding: 5px; margin: 5px; width: -webkit-calc(100% - 50px); width: -moz-calc(100% - 50px); -webkit-box-sizing: border-box; -moz-box-sizing: border-box; -o-box-sizing: border-box; -ms-box-sizing: border-box; box-sizing: border-box;"></input>' . "\n";

					print '<tr>' . "\n" . '<td style="width: 20%;">Archivo SVG secundario' . "</td>\n";
					print '<td style="width: 80%;">' . "\n";
					$svg_file2 = get_post_meta(get_the_base_lang_post_ID(), "_map_svg_file2", true);
					print '<input id="map-svg-file2" size="30" value="' . $svg_file2 . '" style="padding: 5px; margin: 5px; width: -webkit-calc(100% - 50px); width: -moz-calc(100% - 50px); -webkit-box-sizing: border-box; -moz-box-sizing: border-box; -o-box-sizing: border-box; -ms-box-sizing: border-box; box-sizing: border-box;"></input>' . "\n";

					$count = 1;
					$map_elem = get_post_meta(get_the_base_lang_post_ID(), "_map-elem-1", true);
					while ($map_elem != '') {
						print '<tr>' . "\n" . '<td style="width: 20%;">ID elemento ' . $count . "</td>\n";
						print '<td style="width: 80%;">' . "\n";
						print '<input id="map-elem-' . $count . '" size="30" value="' . $map_elem . '" style="padding: 5px; margin: 5px; width: -webkit-calc(100% - 50px); width: -moz-calc(100% - 50px); -webkit-box-sizing: border-box; -moz-box-sizing: border-box; -o-box-sizing: border-box; -ms-box-sizing: border-box; box-sizing: border-box;"></input>' . "\n";
						print '<span id="image-delete-' . $count . '" onclick="delete_map_entry(event, ' . get_the_base_lang_post_ID() . ', ' . $count . ');"><font size="+1"><b>X</b></font></span>' . "\n";
						print "</td>\n</tr>\n";
						$count++;
						$map_elem = get_post_meta(get_the_base_lang_post_ID(), "_map-elem-" . $count, true);
					}
					?>
				<tr>
					<td></td>
					<td style="padding: 7px; padding-bottom: 10px;">
						<input type="submit" name="add-map-entry" value="<?php echo __('Add'); ?>" onclick="add_map_entry(event, <?php echo get_the_base_lang_post_ID() . ', ' . $count; ?>);"></input>
						<input type="submit" name="update-map-entry" value="<?php echo __('Update'); ?>" onclick="update_map_entry(event, <?php echo get_the_base_lang_post_ID() . ', ' . $count; ?>);"></input>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
<?php
}

function metabox_all_metadata($post, $args)
{
?>
	<div class="wrap">
		<table style="border-spacing: 0px; border: 2px solid #f1f1f1; background: #f1f1f1; width: 100%; margin-top: 20px; margin-bottom: 7px;">
			<thead>
				<tr style="padding-bottom: 20px;">
					<th class="left" style="width: auto;">
						<?php echo 'All Metadata'; ?>
					</th>
					<th class="left" style="width: auto;">
						This Post ID: <?php echo get_the_ID(); ?> - Orig Post ID: <?php echo get_the_base_lang_post_ID(); ?>
					</th>
				</tr>
			</thead>
			<tbody style="background: #f9f9f9;">
				<?php
				$postmetas = get_post_meta(get_the_base_lang_post_ID());
				foreach ($postmetas as $meta_key => $meta_value) {
					if (strncmp($meta_key, '_', 1) == 0) {
						if (strlen($meta_value[0]) > 30) {
							$meta_value[0] = substr($meta_value[0], 0, 30) . '...';
						}
						echo "                <tr><td>" . $meta_key . '</td><td>' . $meta_value[0] . "</td><tr>\n";
					}
				}
				?>
			</tbody>
		</table>
	</div>
<?php
}


/**
 * Rename meta keys
 * Usage: update_meta_key( 'old_key', 'new_key');
 */
function update_meta_key($old_key, $new_key, $post_id)
{
	global $wpdb;
	//echo "updating |$old_key| to |$new_key|\n";
	$query = "UPDATE " . $wpdb->prefix . "postmeta " .
		"SET meta_key = '" . $new_key . "' " .
                "WHERE meta_key = '" . $old_key . "' AND post_id = '".$post_id."'";
	//echo "SQL: $query\n";
	$results = $wpdb->get_results($query, ARRAY_A);
	//echo "Result: ";
	//var_dump($results);
	return $results;
}





?>
