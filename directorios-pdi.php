<?php
/*
* Plugin Name: PDI Companies Business Directory
* Plugin URI: http://pdicompanies.com
* Version: 1.0
* Description: Plugin para gestionar directorios de negocios de PDI Companies
* Author: Hugo René Rodríguez Cruz
* Author URI: http://ilustramultimedia.com
*/

/* Acción para crear custom post type al activar el plugin */
add_action('init','cpt_directorios_pdicompanies'); 

/* Filtro para añadir la plantilla single del custom post type */
add_filter('single_template', 'directorios_plantilla_single');

/* Añadir los estilos personalizados del plugin */
add_action('wp_enqueue_scripts','pdi_directorios_estilos');

/* Registrar Metaboxes para las imágenes del directorio */
add_action('add_meta_boxes', 'pdi_directorios_imagenes_meta');


/* Creación del Custom Post Type de los directorios PDI Companies */
function cpt_directorios_pdicompanies(){
	register_post_type('dirs_pdicompanies',
						array(
							'labels' => array(
								'name' => __('Directorios'),
								'singular_name' => __('Registro'),
							),
							'public' => true,
							'has_archive' => true,
							'menu_icon' => plugin_dir_url(__FILE__).'recursos/imagenes/pdi-dir-icon.png',
							'rewrite' => array(
								'slug' => 'directorios',
								),
							'supports' => array(
								'title',
								'editor',
								'thumbnail',
								),
						)
					);
}

/* Función para añadir la plantilla single del custom post type */
function directorios_plantilla_single($single){
	global $wp_query, $post;

	/* Checa que el tipo de post sea single y añade la ruta de la plantilla */
	if ($post->post_type == 'dirs_pdicompanies'){
		$single = plugin_dir_path(__FILE__).'plantillas/directorios-single.php';
	}

	return $single;
}

/* Registro de los estilos */
function pdi_directorios_estilos(){
	wp_register_style('estilos_generales', plugins_url('directorios-pdi/plantillas/css/estilos.css'));

	wp_register_style('font_awesome', plugins_url('directorios-pdi/plantillas/css/font-awesome.min.css'));

	wp_enqueue_style('estilos_generales');
	wp_enqueue_style('font_awesome');
}

/* Registro de los metaboxes de imágenes para los directorios */
function pdi_directorios_imagenes_meta(){
	add_meta_box(
		'pdi-dir-imagenes',
		__('Imágenes del negocio','pdidirlang'),
		'pdi_directorios_imagenes_meta_callback',
		'dirs_pdicompanies'
	);
}

/* Callback metabox imagenes */
function pdi_directorios_imagenes_meta_callback($post){
	//Campo Nonce
	wp_nonce_field('pdidir_imagen_portada_nonce','pdidir_imagen_portada_nonce');

	$portada = get_post_meta($post->ID,'_pdidir_imagen_portada[image]', true);

	echo '<p>
				<label for="pdidir_imagen_portada[image]"> Subir Portada</label><br>
				<input type="text" name="pdidir_imagen_portada[image]" id="pdidir_imagen_portada[image]" class="meta-image regular-text" value="'.$meta["image"].'">
				<input type="button" class="button image-upload" value="Buscar Imagen">
		  </p>
		  <div class="image-preview"><img src="'.$meta["image"].'" style="max-width: 250px;"></div>';
}
?>