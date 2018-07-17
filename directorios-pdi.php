<?php
/*
* Plugin Name: PDI Companies Business Directory
* Plugin URI: http://pdicompanies.com
* Version: 1.0
* Description: Plugin para gestionar directorios de negocios de PDI Companies
* Author: Hugo René Rodríguez Cruz
* Author URI: http://ilustramultimedia.com
* Text Domain: pdidirlang
*/

/*--
#
# Inicio de las acciones y los filtros
#
--*/

/* Acción para crear custom post type al activar el plugin */
add_action('init','cpt_directorios_pdicompanies'); 

/* Filtro para añadir la plantilla single del custom post type */
add_filter('single_template', 'directorios_plantilla_single');

/* Añadir los estilos personalizados del plugin */
add_action('wp_enqueue_scripts','pdi_directorios_estilos');

/* Registrar Metabox para la imagen de portada del directorio */
add_action('add_meta_boxes_dirs_pdicompanies', 'pdi_directorios_imagen_portada_meta');

/* Registrar Metabox para las etiquetas de servicios adicionales */
add_action('add_meta_boxes_dirs_pdicompanies', 'pdi_directorios_etiquetas_meta'); 

/* Guardar en la DB los datos obtenidos de los fields en los metaboxes */
add_action('save_post_dirs_pdicompanies','pdi_dir_guardar_datos',10,2);

/*--
#
# Fin de las acciones y los filtros
#
--*/

/*--
#
# Registro del Custom Post Type denominado dirs_pdicompanies
#
--*/

/* Creación del Custom Post Type de los directorios PDI Companies */
function cpt_directorios_pdicompanies(){
	register_post_type('dirs_pdicompanies',
						array(
							'labels' => array(
								'name' => __('Directorios','pdidirlang'),
								'singular_name' => __('Registro','pdidirlang'),
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

/*--
#
# Fin de Registro del Custom Post Type denominado dirs_pdicompanies
#
--*/

/*--
#
# Añadiendo las custom templates que diseñamos para los directorios
#
--*/

/* Función para añadir la plantilla single del custom post type */
function directorios_plantilla_single($single){
	global $wp_query, $post;

	/* Checa que el tipo de post sea single y añade la ruta de la plantilla */
	if ($post->post_type == 'dirs_pdicompanies'){
		$single = plugin_dir_path(__FILE__).'plantillas/directorios-single.php';
	}

	return $single;
}

/*--
#
# Fin de Añadiendo las custom templates que diseñamos para los directorios
#
--*/

/*--
#
# Inicio del Registro y Enqueue de los scripts y estilos que requiere el plugin
#
--*/

/* Registro de los estilos */
function pdi_directorios_estilos(){
	wp_register_style('estilos_generales', plugins_url('directorios-pdi/plantillas/css/estilos.css'));

	wp_register_style('font_awesome', plugins_url('directorios-pdi/plantillas/css/font-awesome.min.css'));

	wp_enqueue_style('estilos_generales');
	wp_enqueue_style('font_awesome');
}

/*--
#
# Fin del Registro y Enqueue de los scripts y estilos que requiere el plugin
#
--*/


/*--###################################
#######################################
# Custom Metaboxes y Custom Fields ####
#######################################
#--##################################*/



/*--
#
# Inicio del metabox de la imagen de portada
#
#--*/

/* Registro de los campos que irán en los metaboxes */
function dirs_pdicompanies_meta_field_portada(){
	register_meta('dirs_pdicompanies','pdi-dir-imagen-portada',
		['description' => 'Imagen de portada para la vista single del directorio',
		'single' => true,
		'sanitize_callback' => 'sanitize_text_field',
		'auth_callback' => 'pdi_directorios_imagenes_meta_callback'
		]
	);
}

/* Registro de los metaboxes de imágenes para los directorios */
function pdi_directorios_imagen_portada_meta(){
	add_meta_box(
		'pdi_portada_metabox',
		__('Imagen de portada','pdidirlang'),
		'pdi_portada_callback'
	);
}

/* Callback metabox imagenes */
function pdi_portada_callback($post){
	// Field nonce para aumentar la seguridad al ingresar información a la DB
	wp_nonce_field(basename(__FILE__),'pdi_portada_nonce');

	// Obteniendo el valor de la base de datos
	$portada_actual = get_post_meta($post->ID,'_pdi_dir_portada',true);

	// Field para ingresar el link de la imagen de portada
	?>
	<label for="pdi_dir_portada">Ingresa aquí el link para la imágen de portada</label><br>
	<input type="text" name="pdi_dir_portada" style="width: 300px;" value="<?php echo $portada_actual; ?>"><?php
}

/* Función que guarda los datos en la base de datos */
	function pdi_dir_guardar_datos($post_id){
		//Verificación del Nonce
		if(!isset($_POST['pdi_portada_nonce']) || !wp_verify_nonce($_POST['pdi_portada_nonce'], basename(__FILE__))){
			return;
		}

		//Verificar permisos de ususario
		if(!current_user_can('edit_post',$post_id)){
			return;
		}

		//Guardando los valores introducidos
		if(isset($_REQUEST['pdi_dir_portada'])){
			update_post_meta($post_id,'_pdi_dir_portada',sanitize_text_field($_POST['pdi_dir_portada']));
		}
	}

/*--
#
# Fin del metabox de la imagen de portada
#
#--*/

/*--
#
# Inicio del metabox de las etiquetas de características del negocio
#
#--*/

/*-- Registro del metabox --*/
function dirs_pdicompanies_meta_field_etiquetas(){
	register_meta('dirs_pdicompanies','pdi-dir-etiquetas-adicionales',
		['description' => 'Etiquetas adicionales para el negocio como, Pet Friendly, Metodos de pago y Entrega a domicilio',
		'single' => true,
		'sanitize_callback' => 'sanitize_text_field',
		'auth_callback' => 'pdi_directorios_etiquetas_meta_callback'
		]
	);
}

/*-- Añadiendo el metabox --*/
function pdi_directorios_etiquetas_meta(){
	add_meta_box(
		'pdi_etiquetas_metabox',
		__('Etiquetas de servicios adicionales','pdidirlang'),
		'pdi_etiquetas_callback'
	);
}

/*-- Callback metabox etiquetas servicios adicionales --*/
function pdi_etiquetas_callback($post){
	//Field nonce para aumentar la seguridad al ingresar información a la DB
	wp_nonce_field(basename(__FILE__),'pdi_etiquetas_nonce');

	// Obteniendo el valor de la base de datos
	$pdi_etiqueta_petfriendly_actual = get_post_meta($post->ID,'_pdi_dir_etiquetas', true);

	// Formulario etiquetas
	?>
	<div class="pdi-dir-etiquetas-contenedor">
		<h4>¿Este establecimiento es Pet Friendly?</h4>
		<div><input type="radio" name="pdi_dir_pet_friendly" value="0" <?php checked($current_pdi_dir_pet_friendly,'0');?> /> Si</div>
		<div><input type="radio" name="pdi_dir_pet_friendly" value="1" <?php checked($current_pdi_dir_pet_friendly,'1');?>/> No</div>
	</div><?php
}

/*--
#
# Fin del metabox de las etiquetas de características del negocio
#
#--*/


?>