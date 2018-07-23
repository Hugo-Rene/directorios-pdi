
// Desactivar horarios si el día es no laboral //
document.getElementById("pdi_dir_horarios_apertura_<?php echo $i;?>").onchange = function comprobarDiaLaboral(){
	
if (document.getElementById("<?php echo 'pdi_dir_horarios_apertura_'.$i; ?>").value == "notrabaja"){
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