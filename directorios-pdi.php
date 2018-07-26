<?php
/*
* Plugin Name: Directorio de Negocios PDI Now!
* Plugin URI: http://pdicompanies.com
* Version: 1.0
* Description: Plugin para gestionar directorios de negocios
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

/* Añadir los estilos personalizados de los templates */
add_action('wp_enqueue_scripts','pdi_directorios_estilos');

/* Añadir los estilos personalizados del panel admin */
add_action('admin_print_styles-post-new.php','pdi_directorios_estilos_admin',11);
add_action('admin_print_styles-post.php','pdi_directorios_estilos_admin',11);

/* Registrar Metabox para la imagen de portada del directorio */
add_action('add_meta_boxes_dirs_pdicompanies', 'pdi_directorios_imagen_portada_meta');

/* Registrar Metabox para las etiquetas de servicios adicionales */
add_action('add_meta_boxes_dirs_pdicompanies', 'pdi_directorios_etiquetas_meta');

/* Registrar Metabox para los horarios de apertura del establecimiento */
add_action('add_meta_boxes_dirs_pdicompanies', 'pdi_directorios_horarios_meta');

/* Registrar Metabox para los métodos de pago que acepta el establecimiento */
add_action('add_meta_boxes_dirs_pdicompanies', 'pdi_directorios_metodosdepago_meta');

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

/* Registro de los estilos de los templates */
function pdi_directorios_estilos(){
	wp_register_style('estilos_generales', plugins_url('directorios-pdi/plantillas/css/estilos.css'));

	wp_register_style('font_awesome', plugins_url('directorios-pdi/plantillas/css/font-awesome.min.css'));

	wp_enqueue_style('estilos_generales');
	wp_enqueue_style('font_awesome');
}

/* Registro de los estilos del panel admin */
function pdi_directorios_estilos_admin(){
	global $post_type;
	if('dirs_pdicompanies' == $post_type){
		wp_register_style('pdidirs_admin_estilos', plugins_url('directorios-pdi/plantillas/css/pdidirs-admin-estilos.css'));

		wp_enqueue_style('pdidirs_admin_estilos');
	}
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
	<label for="pdi_dir_portada"><?php _e("Ingresa aquí el link para la imagen de portada","pdidirlang"); ?></label><br>
	<input type="text" name="pdi_dir_portada" style="width: 253px;" value="<?php echo $portada_actual; ?>"><?php
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

	// Obteniendo el valor de la base de datos de Acepta Tarjetas
	$pdi_etiqueta_acepta_tarjetas_actual = get_post_meta($post->ID,'_pdi_dir_acepta_tarjetas',true);

	// Obteniendo el valor de la base de datos de Pet Friendly
	$pdi_etiqueta_pet_friendly_actual = get_post_meta($post->ID,'_pdi_dir_pet_friendly',true);

	// Obteniendo el valor de la base de datos de Servicio a Domicilio
	$pdi_etiqueta_servicio_domicilio_actual = get_post_meta($post->ID,'_pdi_dir_servicio_domicilio',true);

	// Obteniendo el valor de la base de datos de Establecimiento Adecuado
	$pdi_etiqueta_instalaciones_adecuadas_actual = get_post_meta($post->ID,'_pdi_dir_instalaciones_adecuadas',true);
	?>
	<!-- Contenedor etiquetas -->
	<div class="pdi-dir-etiquetas-contenedor">
		<!-- Etiqueta Pet Friendly -->
		<div id="pdi-dir-pet-friendly-admin">
			<h4>¿Este establecimiento es Pet Friendly?</h4>
			<div><input type="radio" name="pdi_dir_pet_friendly" value="0" <?php checked($pdi_etiqueta_pet_friendly_actual,'0');?>/> No</div>
			<div><input type="radio" name="pdi_dir_pet_friendly" value="1" <?php checked($pdi_etiqueta_pet_friendly_actual,'1');?>/> Si</div>
		</div>
		<!-- Etiqueta Acepta Tarjetas -->
		<div id="pdi-dir-acepta-tarjetas-admin">
			<h4>¿Este establecimiento Acepta Tarjetas de Crédito?</h4>
			<div><input type="radio" name="pdi_dir_acepta_tarjetas" value="0" <?php checked($pdi_etiqueta_acepta_tarjetas_actual,'0');?>/> No</div>
			<div><input type="radio" name="pdi_dir_acepta_tarjetas" value="1" <?php checked($pdi_etiqueta_acepta_tarjetas_actual,'1');?>/> Si</div>
		</div>
		<!-- Etiqueta Envíos a Domicilio -->
		<div id="pdi-dir-servicio-domicilio-admin">
			<h4>¿Este establecimiento ofrece Servicio a Domicilio?</h4>
			<div><input type="radio" name="pdi_dir_servicio_domicilio" value="0" <?php checked($pdi_etiqueta_servicio_domicilio_actual,'0');?>/> No</div>
			<div><input type="radio" name="pdi_dir_servicio_domicilio" value="1" <?php checked($pdi_etiqueta_servicio_domicilio_actual,'1');?>/> Si</div>
		</div>
		<!-- Etiqueta Instalaciones Adecuadas -->
		<div id="pdi-dir-instalaciones-adecuadas-admin">
			<h4>¿Las instalaciones de este establecimiento son adecuadas para personas con capacidades diferentes?</h4>
			<div><input type="radio" name="pdi_dir_instalaciones_adecuadas" value="0" <?php checked($pdi_etiqueta_instalaciones_adecuadas_actual,'0');?>/> No</div>
			<div><input type="radio" name="pdi_dir_instalaciones_adecuadas" value="1" <?php checked($pdi_etiqueta_instalaciones_adecuadas_actual,'1');?>/> Si</div>
		</div>
	</div><?php
}

/*--
#
# Fin del metabox de las etiquetas de características del negocio
#
#--*/

/*--
#
# Inicio del metabox de los horarios de apertura del establecimiento
#
#--*/

/*-- Registro del metabox --*/
function dirs_pdicompanies_meta_field_horarios(){
	register_meta('dirs_pdicompanies','pdi-dir-horarios-apertura',
		['description' => 'Horarios de apertura del establecimiento',
		'single' => true,
		'sanitize_callback' => 'sanitize_text_field',
		'auth_callback' => 'pdi_directorios_horarios_meta_callback'
		]
	);
}

/*-- Añadiendo el metabox --*/
function pdi_directorios_horarios_meta(){
	add_meta_box(
		'pdi_horarios_metabox',
		__('Horarios y días de apertura del establecimiento','pdidirlang'),
		'pdi_horarios_callback'
	);
}

/* Callback metabox horarios de apertura */
function pdi_horarios_callback($post){
	// Field nonce para aumentar la seguridad al ingresar información a la DB
	wp_nonce_field(basename(__FILE__),'pdi_horarios_nonce');

	// Obteniendo el valor de la base de datos
	$horarios_actual = (array) get_post_meta($post->ID,'_pdi_dir_horarios',true);

	// Field para ingresar el link de la imagen de portada
	?>
	<div id="pdi-dir-horarios-admin">
		<table>
			<tr>
				<th>Día de la semana:</th>
				<th>Horario de apertura:</th>
				<th>Horario de cierre:</th>
			</tr>
			<?php
			$pdi_dir_semana = array("Lunes","Martes","Miércoles","Jueves","Viernes","Sábado","Domingo");
			for ($i=0; $i < 7; $i++) {
			?>
			<tr>
				<td><?php echo $pdi_dir_semana[$i].":";?></td>
				<!-- Horario de apertura -->
				<td>Horario de apertura: <select id="<?php echo 'pdi_dir_horarios_apertura_'.$i;?>" name="<?php echo "pdi_dir_horarios[".$i."][pdi_dir_horap]"; ?>"><option value="notrabaja">No trabaja</option><?php for($e=1; $e <= 12; $e++){if($horarios_actual[$i][pdi_dir_horap] == $e){ echo "<option value='".$e."' selected>".$e."</option>"; } else {echo "<option value='".$e."'>".$e."</option>";}}?></select><select <?php if ($horarios_actual[$i][pdi_dir_horap] == "notrabaja" || $horarios_actual[$i][pdi_dir_horap] == ""){echo " disabled";}?> id="<?php echo 'pdi_dir_meridianos_apertura_'.$i;?>" name="<?php echo "pdi_dir_horarios[".$i."][pdi_dir_ap_ampm]"; ?>"><option value="A.M." <?php if(isset($horarios_actual[$i][pdi_dir_ap_ampm]) && $horarios_actual[$i][pdi_dir_ap_ampm] == "A.M."){echo " selected";}?>>A.M.</option><option value="P.M."<?php if(isset($horarios_actual[$i][pdi_dir_ap_ampm]) && $horarios_actual[$i][pdi_dir_ap_ampm] == "P.M."){echo " selected";}?>>P.M.</option></select></td>
				<!-- Horario de cierre -->
				<td>Horario de cierre: <select <?php if ($horarios_actual[$i][pdi_dir_horap] == "notrabaja" || $horarios_actual[$i][pdi_dir_horap] == ""){echo " disabled";}?> id="<?php echo 'pdi_dir_horarios_cierre_'.$i;?>" name="<?php echo 'pdi_dir_horarios['.$i.'][pdi_dir_horci]'; ?>"><?php for($o=1; $o <= 12; $o++){if($horarios_actual[$i][pdi_dir_horci] == $o){ echo "<option value='".$o."' selected>".$o."</option>"; } else {echo "<option value='".$o."'>".$o."</option>";}}?></select><select <?php if ($horarios_actual[$i][pdi_dir_horap] == "notrabaja" || $horarios_actual[$i][pdi_dir_horap] == ""){echo " disabled";}?> id="<?php echo 'pdi_dir_meridianos_cierre_'.$i;?>" name="<?php echo 'pdi_dir_horarios['.$i.'][pdi_dir_ci_ampm]'; ?>"><option value="A.M." <?php if(isset($horarios_actual[$i][pdi_dir_ci_ampm]) && $horarios_actual[$i][pdi_dir_ci_ampm] == "A.M."){echo " selected";}?>>A.M.</option><option value="P.M."<?php if(isset($horarios_actual[$i][pdi_dir_ci_ampm]) && $horarios_actual[$i][pdi_dir_ci_ampm] == "P.M."){echo " selected";}?>>P.M.</option></select></td>
				<!-- Desactivar horarios si el día es no laboral -->
				<?php
				if (isset($horarios_actual)) {
				?>
				<script type="text/javascript">
					var pdi_apertura_dia_<?php echo $i; ?> = document.getElementById("pdi_dir_horarios_apertura_<?php echo $i;?>");
					pdi_apertura_dia_<?php echo $i; ?>.onchange = function comprobarDiaLaboral(){
						if (pdi_apertura_dia_<?php echo $i; ?>.value == "notrabaja" || pdi_apertura_dia_<?php echo $i; ?> == ""){
							document.getElementById("pdi_dir_meridianos_apertura_<?php echo $i; ?>").setAttribute("disabled","disabled");
							document.getElementById("pdi_dir_horarios_cierre_<?php echo $i; ?>").setAttribute("disabled","disabled");
							document.getElementById("pdi_dir_meridianos_cierre_<?php echo $i; ?>").setAttribute("disabled","disabled");
						}
						if (this.value !== "notrabaja") {
							document.getElementById("pdi_dir_meridianos_apertura_<?php echo $i; ?>").removeAttribute("disabled");
							document.getElementById("pdi_dir_horarios_cierre_<?php echo $i; ?>").removeAttribute("disabled");
							document.getElementById("pdi_dir_meridianos_cierre_<?php echo $i; ?>").removeAttribute("disabled");
						}
					}
				<?php
				echo "</script>";
				}?>
			</tr>
			<?php	
			}
			?>
		</table>
			<!-- Variables y funciones para debugging-->
	<div id="pdi-dirs-cuadro-debugging">
		<h3>Ventana de debugging</h3>
		<?php
		$todos_los_campos = get_post_meta($post->ID);
		print_r($todos_los_campos); ?>
		<h4>Horarios Actual</h4>
		<?php print_r($horarios_actual[0][pdi_dir_horap]); ?>
		<?php if ($horarios_actual[0][pdi_dir_horap] == $variable_horario_apertura){echo "no esta definido horarios_actual";} else {echo "si esta definido horarios_actual";} ?>
	</div>

	</div>
<?php
}

/*--
#
# Fin del metabox de los horarios de apertura del establecimiento
#
#--*/

/*--
#
# Inicio del metabox de los métodos de pago
#
#--*/

/*-- Registro del metabox --*/
function dirs_pdicompanies_meta_field_metodos_de_pago(){
	register_meta('dirs_pdicompanies','pdi-dir-metodos-pago',
		['description' => 'Métodos de pago que acepta el establecimiento',
		'single' => true,
		'sanitize_callback' => 'sanitize_text_field',
		'auth_callback' => 'pdi_metodosdepago_meta_callback'
		]
	);
}

/*-- Añadiendo el metabox --*/
function pdi_directorios_metodosdepago_meta(){
	add_meta_box(
		'pdi_metodosdepago_metabox',
		__('Métodos de pago que acepta el establecimiento','pdidirlang'),
		'pdi_metodosdepago_callback'
	);
}

/* Callback metabox horarios de apertura */
function pdi_metodosdepago_callback($post){
	// Field nonce para aumentar la seguridad al ingresar información a la DB
	wp_nonce_field(basename(__FILE__),'pdi_metodosdepago_nonce');

	// Obteniendo el valor de la base de datos
	$pdi_dir_pago_amex_actual = get_post_meta($post->ID,'_pdi_pago_amex',true);
	$pdi_dir_pago_efectivo_actual = get_post_meta($post->ID,'_pdi_pago_efectivo',true);
	$pdi_dir_pago_visa_actual = get_post_meta($post->ID,'_pdi_pago_visa',true);
	$pdi_dir_pago_mastercard_actual = get_post_meta($post->ID,'_pdi_pago_mastercard',true);
	$pdi_dir_pago_paypal_actual = get_post_meta($post->ID,'_pdi_pago_paypal',true);
	?>
	<div id="pdi-dir-metodos-pago-container">
		<div class="pdi-dirs-contenedor-metodo-pago"><input type="checkbox" id="pdi_pago_amex" name="pdi_pago_amex" value="pdi_pago_amex" <?php if($pdi_dir_pago_amex_actual == "pdi_pago_amex"){echo "checked";}?>/><label for="pdi_pago_amex">Se acepta Amex</label></div>
		<div class="pdi-dirs-contenedor-metodo-pago"><input type="checkbox" id="pdi_pago_visa" name="pdi_pago_visa" value="pdi_pago_visa" <?php if($pdi_dir_pago_visa_actual == "pdi_pago_visa"){echo "checked";}?>/><label for="pdi_pago_visa">Se acepta Visa</label></div>
		<div class="pdi-dirs-contenedor-metodo-pago"><input type="checkbox" id="pdi_pago_mastercard" name="pdi_pago_mastercard" value="pdi_pago_mastercard" <?php if($pdi_dir_pago_mastercard_actual == "pdi_pago_mastercard"){echo "checked";}?>/><label for="pdi_pago_mastercard">Se acepta MasterCard</label></div>
		<div class="pdi-dirs-contenedor-metodo-pago"><input type="checkbox" id="pdi_pago_paypal" name="pdi_pago_paypal" value="pdi_pago_paypal" <?php if($pdi_dir_pago_paypal_actual == "pdi_pago_paypal"){echo "checked";}?>/><label for="pdi_pago_paypal">Se acepta Paypal</label></div>
	</div>
<?php
}
/*--
#
# Fin del metabox de los métodos de pago
#
#--*/


/* Función que guarda los metadatos en la base de datos */
	function pdi_dir_guardar_datos($post_id){
		//Verificación del Nonce de imagen de portada
		if(!isset($_POST['pdi_portada_nonce']) || !wp_verify_nonce($_POST['pdi_portada_nonce'], basename(__FILE__))){
			return;
		}

		//Verificación del Nonce de etiquetas servicios adicionales
		if(!isset($_POST['pdi_etiquetas_nonce']) || !wp_verify_nonce($_POST['pdi_etiquetas_nonce'], basename(__FILE__))){
			return;
		}

		//Verificación del Nonce de los horarios
		if(!isset($_POST['pdi_horarios_nonce']) || !wp_verify_nonce($_POST['pdi_horarios_nonce'], basename(__FILE__))){
			return;
		}

		//Verificar permisos de usuario
		if(!current_user_can('edit_post',$post_id)){
			return;
		}

		//Guardando los valores introducidos
		
		//Imagen de portada
		if (isset($_REQUEST['pdi_dir_portada'])){
			update_post_meta($post_id,'_pdi_dir_portada',sanitize_text_field($_POST['pdi_dir_portada']));
		}

		//Etiquetas de servicios adicionales
		//Pet Friendly
		if (isset($_REQUEST['pdi_dir_pet_friendly'])){
			update_post_meta($post_id,'_pdi_dir_pet_friendly',
			sanitize_text_field($_POST['pdi_dir_pet_friendly']));
		}
		//Acepta Tarjetas
		if (isset($_REQUEST['pdi_dir_acepta_tarjetas'])) {
			update_post_meta($post_id,'_pdi_dir_acepta_tarjetas',
				sanitize_text_field($_POST['pdi_dir_acepta_tarjetas']));
		}
		//Servicio a Domicilio
		if (isset($_REQUEST['pdi_dir_servicio_domicilio'])) {
			update_post_meta($post_id,'_pdi_dir_servicio_domicilio',
			sanitize_text_field($_POST['pdi_dir_servicio_domicilio']));
		}
		//Instalaciones Adecuadas
		if (isset($_REQUEST['pdi_dir_instalaciones_adecuadas'])) {
			update_post_meta($post_id,'_pdi_dir_instalaciones_adecuadas',
				sanitize_text_field($_POST['pdi_dir_instalaciones_adecuadas'])
			);
		}
		//Valores del calendario
		if (isset($_POST['pdi_dir_horarios'])){
			$directorio_horarios = $_POST['pdi_dir_horarios'];
			update_post_meta($post_id,'_pdi_dir_horarios',$directorio_horarios);
		}
		//Métodos de pago aceptados
		//Pago con American Express
		if (isset($_POST['pdi_pago_amex'])){
			update_post_meta($post_id,'_pdi_pago_amex',
				sanitize_text_field($_POST['pdi_pago_amex']));
		} else {
			update_post_meta($post_id,'_pdi_pago_amex',
				sanitize_text_field($_POST['']));
		}
		//Pago con Visa
		if (isset($_POST['pdi_pago_visa'])){
			update_post_meta($post_id,'_pdi_pago_visa',
				sanitize_text_field($_POST['pdi_pago_visa']));
		} else {
			update_post_meta($post_id,'_pdi_pago_visa',
				sanitize_text_field($_POST['']));
		}
		//Pago con MasterCard
		if (isset($_POST['pdi_pago_mastercard'])){
			update_post_meta($post_id,'_pdi_pago_mastercard',
				sanitize_text_field($_POST['pdi_pago_mastercard']));
		} else {
			update_post_meta($post_id,'_pdi_pago_mastercard',
				sanitize_text_field($_POST['']));
		}
		//Pago con Paypal
		if (isset($_POST['pdi_pago_paypal'])){
			update_post_meta($post_id,'_pdi_pago_paypal',
			sanitize_text_field($_POST['pdi_pago_paypal']));
		} else {
			update_post_meta($post_id,'_pdi_pago_paypal',
			sanitize_text_field($_POST['']));
		}
	}
?>