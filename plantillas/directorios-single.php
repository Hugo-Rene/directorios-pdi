<?php
get_header();
echo "<div class='container'>";
echo "<div class='row'>";
echo "<div id='th-twocolumns' class='th-twocolumns'>";
$title = get_the_title();
$pdi_dirs_logo = get_the_post_thumbnail_url();
?>
	<!--Inicio del contenedor general -->
	<div class="col-md-8 col-sm-12 col-sx-12">
		<div id="directorio-individual-contenedor">
		<!--Espacio para la información de inicio-->
		<header id="pdi-directorio-single-cabecera" style="background-image: url(https://exp.cdn-hotels.com/hotels/1000000/120000/118000/117967/117967_199_z.jpg);">
			<div id="pdi-directorio-logo"><div style="background-image: url(<?php echo $pdi_dirs_logo; ?>)"></div></div>
			<div id="pdi-directorio-nombre"><div id="directorio-individual-nombre-lugar"><h1><?php echo $title;?></h1></div></div>
			<div id="directorio-individual-tags">
				<div class="dir-acepta-tarjetas">
					<i class="fa fa-credit-card-alt" aria-hidden="true"></i>
					<span>ACEPTA TARJETAS</span>
				</div>
				<div class="dir-pet-friendly">
					<i class="fa fa-paw" aria-hidden="true"></i>
					<span>PET FRIENDLY</span>
				</div>
				<div class="dir-adecuado">
					<i class="fa fa-wheelchair" aria-hidden="true"></i>
					<span>ADECUADO</span>
				</div>
			</div>
		</header>
		<!--Fin de espacio para la información de inicio-->
		<!--Inicio de la descripción-->
		<div id="directorio-individual-descripcion">
			<header>
				<h2>Acerca de <?php echo $title; ?></h2>
			</header>
			<p><?php echo get_the_content(); ?></p>
		</div>
		<!--Fin de la descripción-->
		<!--Inicio de la lista de servicios-->
		<div id="directorio-individual-servicios">
			<header>
				<i class="fa fa-check-square" aria-hidden="true"></i>
				<h2>Servicios</h2>
			</header>
			<div id="dir-lista-servicios">
				<ul>
					<li><span>Este es el primer servicio de prueba</span></li>
					<li><span>Este es el segundo servicio de prueba</span></li>
					<li><span>Este es el tercer servicio de prueba</span></li>
					<li><span>Este es el cuarto servicio de prueba</span></li>
				</ul>
			</div>
		</div>
		<!--Fin de la lista de servicios-->
		<!--Inicio de los métodos de pago-->
		<div id="directorio-individual-metodos-de-pago">
			<header>
				<i class="fa fa-usd" aria-hidden="true"></i>
				<h2>Métodos de pago</h2>
			</header>
			<div id="dir-metodos-pago">
				<ul>
					<li class="dir-efectivo">Aceptamos Efectivo</li>
					<li class="dir-visa">Aceptamos VISA</li>
					<li class="dir-mastercard">Aceptamos MasterCard</li>
				</ul>
			</div>
		</div>
		<!--Fin de los métodos de pago-->
		<!--Inicio de la tabla de horarios-->
		<div id="directorio-individual-horarios">
			<header>
				<i class="fa fa-clock-o" aria-hidden="true"></i>
				<h2>Horarios de atención</h2>
			</header>
			<div id="dir-horarios-atencion">
				<table id="dir-tabla-horarios">
					<tr>
						<td>Lunes:</td>
						<td>De 9:00 A.M. a 18:00 P.M.</td>
					</tr>
					<tr class="dir-sin-servicio">
						<td>Martes:</td>
						<td>No hay servicio</td>
					</tr>
					<tr>
						<td>Miércoles:</td>
						<td>De 9:00 A.M. a 18:00 P.M.</td>
					</tr>
					<tr>
						<td>Jueves:</td>
						<td>De 9:00 A.M. a 18:00 P.M.</td>
					</tr>
					<tr>
						<td>Viernes:</td>
						<td>De 9:00 A.M. a 18:00 P.M.</td>
					</tr>
					<tr>
						<td>Sábado:</td>
						<td>De 9:00 A.M. a 18:00 P.M.</td>
					</tr>
					<tr>
						<td>Domingo:</td>
						<td>De 9:00 A.M. a 18:00 P.M.</td>
					</tr>
				</table>
			</div>
		</div>
		<!--Fin de la tabla de horarios-->
		<!--Inicio de la información de contacto-->
		<div id="directorio-individual-informacion-contacto">
			<header>
				<i class="fa fa-info-circle" aria-hidden="true"></i>
				<h2>Información de contacto</h2>
			</header>
			<div id="dir-info-contacto">
				<div class="dir-direccion">
					<i class="fa fa-map-marker" aria-hidden="true"></i>
					<span>Calle Inventada #123, Col. Mentiras, Puerto Vallarta, Jalisco.</span>
				</div>
				<div class="dir-telefono">
					<i class="fa fa-phone" aria-hidden="true"></i>
					<span>555 555 5555</span>
				</div>
				<div class="dir-email">
					<i class="fa fa-envelope" aria-hidden="true"></i>
					<span>email@dominio.com</span>
				</div>
				<div class="dir-pagina">
					<i class="fa fa-globe" aria-hidden="true"></i>
					<span><a href="#">www.paginasimulada.com</a></span>
				</div>
			</div>
		</div>
		<!--Fin de la información de contacto-->
		<!--Inicio de la ubicación geográfica-->
		<div id="directorio-individual-ubicacion-geografica">
			<header>
				<i class="fa fa-map-o" aria-hidden="true"></i>
				<h2>Ubicación geográfica</h2>
			</header>
			<div id="dir-ubicacion-mapa">
				<script>
      		function initMap() {
        	var uluru = {lat: 20.6030148, lng: -105.23726829999998};
        	var map = new google.maps.Map(document.getElementById('dir-ubicacion-mapa'), {
          	zoom: 17,
          	center: uluru
        	});
        	var marker = new google.maps.Marker({
          	position: uluru,
          	map: map
        		});
      		}
    		</script>
    		<script async defer
    			src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDk4lbh6c6ajnqydYIJwPcEh3aBF6Gxq28&callback=initMap">
    		</script>
			</div>
		</div>
		<!--Fin de la ubicación geográfica-->
	</div>
	</div>
	<!--Fin del contenedor general-->

<?php
echo "<aside id='th-sidebar' class='th-sidebar col-md-4 col-sm-12 col-xs-12 pull-right'>";
get_sidebar();
echo "</aside>";
echo "</div>";
echo "</div>";
echo "</div>";
get_footer();
?>