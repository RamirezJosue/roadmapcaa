<?php 
    ob_start();	
    $accesos = basename(dirname(__FILE__));
	require_once('../../includes/ini.php');
	require_once('../../bd/crud_usuario.php');
	$crud=new CrudUsuario();
    if ($usuarioestado==0){
	echo $html_bloqueo;
	} else {
    $arraruser = explode ( ',', $usuarioaccesos);	
	if (in_array($accesos, $arraruser)) {
	if ($usuariotipo==0): $aid_super = 0; else: $aid_super = 1; endif;
	/*inicio vefifia si tiene permisos de adminrepartos */
	if (in_array("adminrepartos", $arraruser)): $adminrepartos = 1; else: $adminrepartos = 0; endif;
    if ($aid_super==1 || $adminrepartos==1): $disableform = ''; else: $disableform = 'disabled'; endif;
	/*fin vefifia si tiene permisos de adminrepartos */
	$bootstrapjs =  1;	
	$datatablesjs = 1;
	require('../head.php');
	if(isset($_GET['fechaselec'])): 
	$fecha_form = $_GET['fechaselec'];
	$fechars = $_GET['fechaselec'];	
	else:
	$fechars = $fechars; 
	$fecha_form = $fecha;
	endif;

	if ($idcentro == 'BK77'){
?>
<div class="card border-secondary mb-3">
  <h5 class="card-header bg-danger text-white">Documentos delivery BK77 - Juliaca</h5>
  <div class="card-body">
    <h5 class="card-title text-danger">Manuales</h5>
	<ul class="list-group">
	<a href="..\..\doc_delivery\BK77\2020\0_Manual_juliaca_marzo_2020.pdf" target="_blank" class="list-group-item list-group-item-action list-group-item-light">1. Manual de tripulantes de reparto</a>
	<a href="..\..\doc_delivery\BK77\2020\Capacitacion PFN.pdf" target="_blank" class="list-group-item list-group-item-action list-group-item-light">2. Capacitacion PFN</a>
	<a href="..\..\doc_delivery\BK77\2020\Clasificacion de Envases.pdf" target="_blank" class="list-group-item list-group-item-action list-group-item-light">3. Clasificacion de Envases</a>
	<a href="..\..\doc_delivery\BK77\2021\9.2 Política de Rechazos . Completo.pdf" target="_blank"  class="list-group-item list-group-item-action list-group-item-light">4. Política de Rechazos</a>
	<a href="..\..\doc_delivery\BK77\2021\iRep_Operarios_Peru_REPARTOS.pdf" target="_blank" class="list-group-item list-group-item-action list-group-item-light">5. Irep</a>
	</ul>
    <h5 class="card-title text-danger">SOP</h5>
	<ul class="list-group">
	<a href="..\..\doc_delivery\BK77\2021\__1_SOP Salida de Camiones - Juliaca 2021.pdf" target="_blank" class="list-group-item list-group-item-action list-group-item-light">1. SOP Salida de camiones 2021 <span class="badge badge-danger">Nuevo</span></a>
	<a href="..\..\doc_delivery\BK77\2021\__2_SOP Ejecución de Entrega al cliente JULIACA 2021.pdf"  target="_blank" class="list-group-item list-group-item-action list-group-item-light">2. SOP Ejecución de entrega al cliente 2021 <span class="badge badge-danger">Nuevo</span></a></a>
	<a href="..\..\doc_delivery\BK77\2021\3_SOP CALIDAD EN RUTA - Juliaca 2021.pdf" target="_blank" class="list-group-item list-group-item-action list-group-item-light">3. SOP Calidad de ruta 2021</a>
	<a href="..\..\doc_delivery\BK77\2021\4_SOP Modulaciones -  Juliaca 2021.pdf" target="_blank" class="list-group-item list-group-item-action list-group-item-light">4. SOP Modulaciones 2021</a>
	<a href="..\..\doc_delivery\BK77\2021\5_SOP Recargas  -  Juliaca 2021.pdf" target="_blank" class="list-group-item list-group-item-action list-group-item-light">5. SOP Recargas 2021</a>
	<a href="..\..\doc_delivery\BK77\2021\6_SOP Llegada de camiones - Juliaca 2021.pdf" target="_blank" class="list-group-item list-group-item-action list-group-item-light">6. SOP Llegada de camiones 2021</a>
    <a href="..\..\doc_delivery\BK77\2021\8_SOP SIC - SALIDA LA MERCADO.pdf" target="_blank" class="list-group-item list-group-item-action list-group-item-light">7. SOP SIC Salida al mercado 2021</a>
	<a href="..\..\doc_delivery\BK77\2021\9_SOP - Asignación de doble conductor y doble tripulación v3.pdf" target="_blank" class="list-group-item list-group-item-action list-group-item-light">8. SOP Asignación de doble conductor 2021</a>
	<a href="..\..\doc_delivery\BK77\2021\10_SOP Requisitos minimos Flota SOT  -  Juliaca 2021.pdf" target="_blank"  class="list-group-item list-group-item-action list-group-item-light">9. SOP Requisitos minimos Flota 2021</a>
	<a href="..\..\doc_delivery\BK77\2021\12_SOP - SPOT Baranderos CD Julica 2021.pdf" target="_blank" class="list-group-item list-group-item-action list-group-item-light">10. SOP - SPOT Baranderos CD Julica 2021</a>
	<a href="..\..\doc_delivery\BK77\2021\__11_SOP SIC Entregas - CD Juliaca 2021.pdf" target="_blank" class="list-group-item list-group-item-action list-group-item-light">11. SOP SIC Entregas 2021 <span class="badge badge-danger">Nuevo</span></a></a>	
	</ul>
    <h5 class="card-title text-danger">OPL</h5>
	<ul class="list-group">
	<a href="..\..\doc_delivery\BK77\2021\OPLs Delivery 2021_CD JULIACA.pdf" target="_blank" class="list-group-item list-group-item-action list-group-item-light">1. OPLs Delivery 2021</a>
	<a href="..\..\doc_delivery\BK77\2020\OPL - IDENTIFICACION DE RIESGOS PUNTO DE VENTA POC.pdf" target="_blank" class="list-group-item list-group-item-action list-group-item-light">2. OPL Identificacion de riesgos punto de venta POC</a>
	</ul>
	<h5 class="card-title text-danger">Rutas Criticas</h5>
	<ul class="list-group">
	<a href="..\..\doc_delivery\BK77\2020\A. RUTAS CRITICAS - PREVENCION DE VIOLENCIA CD JULIACA.pdf" target="_blank" class="list-group-item list-group-item-action list-group-item-light">1. Prevencion de violencia</a>
		<a href="..\..\doc_delivery\BK77\2020\B. RUTAS CRÍTICAS - TELEMETRÍA ACTUALIZADO CD JULIACA.pdf" target="_blank" class="list-group-item list-group-item-action list-group-item-light">2. Telemetria</a>
	</ul>
  </div>    
</div>
<?php
} else if ($idcentro == 'BK79') {  
?>
<div class="card border-secondary mb-3">
  <h5 class="card-header bg-danger text-white">Documentos delivery BK79</h5>
  <div class="card-body">
    <h5 class="card-title text-danger">SOP</h5>
	<ul class="list-group">
	<a href="..\..\doc_delivery\BK79\2021\SOP Auditorías de 5S.pdf" class="list-group-item list-group-item-action list-group-item-light">1. SOP Auditoría 5S 2021</a>
	<a href="..\..\doc_delivery\BK79\2021\SOP Ejecución de Entrega al cliente - 2021 COVID.pdf" class="list-group-item list-group-item-action list-group-item-light">2. SOP Ejecución de entrega al cliente 2021</a>
	<a href="..\..\doc_delivery\BK79\2021\SOP Llegada de camiones - 2021 COVID.pdf" class="list-group-item list-group-item-action list-group-item-light">3. SOP Llegada de camiones 2021</a>
	<a href="..\..\doc_delivery\BK79\2021\SOP Salida de Camiones - 2021 Covid.pdf" class="list-group-item list-group-item-action list-group-item-light">4. SOP Salida de camiones 2021</a>
	</ul>
  </div>    
</div>
<?php
} else if ($idcentro == 'BK41') {
?>
<div class="card border-secondary mb-3">
  <h5 class="card-header bg-danger text-white">Documentos delivery BK41</h5>
  <div class="card-body">
    <h5 class="card-title text-danger">SOP</h5>
	<ul class="list-group">
	<a href="..\..\doc_delivery\BK41\2021\01 SOP PRE RUTA (salida) - CD Ayacucho 2021.pdf" class="list-group-item list-group-item-action list-group-item-light">1. SOP PRE RUTA (salida) - CD Ayacucho 2021</a>
	<a href="..\..\doc_delivery\BK41\2021\02 SOP Ejecución en Ruta - CD Ayacucho 2021.pdf" class="list-group-item list-group-item-action list-group-item-light">2. SOP Ejecución en Ruta - CD Ayacucho 2021</a>
	<a href="..\..\doc_delivery\BK41\2021\03 SOP Llegada de Unidades - CD Ayacucho 2021.pdf" class="list-group-item list-group-item-action list-group-item-light">3. SOP Llegada de Unidades - CD Ayacucho 2021</a>
	<a href="..\..\doc_delivery\BK41\2021\04 SOP Calidad en Ruta-CD Ayacucho 2021.pdf" class="list-group-item list-group-item-action list-group-item-light">4. SOP Calidad en Ruta - CD Ayacucho 2021</a>
	</ul>
  </div>    
</div>
<?php		
} else if ($idcentro == 'BK76') {
?>
<div class="card border-secondary mb-3">
  <h5 class="card-header bg-danger text-white">Documentos delivery BK76</h5>
  <div class="card-body">
    <h5 class="card-title text-danger">SOP - OPLs - Politicas...</h5>
	<ul class="list-group">
	<a href="..\..\doc_delivery\BK76\2021\SOP PROCESO DE POST ENTREGA V1.7.pdf" class="list-group-item list-group-item-action list-group-item-light">1. SOP PROCESO DE POST ENTREGA</a>
	<a href="..\..\doc_delivery\BK76\2021\SOP PRE RUTA V1.7.pdf" class="list-group-item list-group-item-action list-group-item-light">2. SOP PRE RUTA</a>
	<a href="..\..\doc_delivery\BK76\2021\SOP EJECUCION DE ENTREGA Tacna V1.7.pdf" class="list-group-item list-group-item-action list-group-item-light">3. SOP EJECUCION DE ENTREGA</a>
	<a href="..\..\doc_delivery\BK76\2021\SOP CALIDAD EN RUTA TACNA V1.3.pdf" class="list-group-item list-group-item-action list-group-item-light">4. SOP CALIDAD EN RUTA</a>
	<a href="..\..\doc_delivery\BK76\2021\P5-SOP Programa de reconocimiento.pdf" class="list-group-item list-group-item-action list-group-item-light">5. SOP Programa de reconocimiento</a>
	<a href="..\..\doc_delivery\BK76\2021\P3.7-SOP Proceso de Ausentismo.pdf" class="list-group-item list-group-item-action list-group-item-light">6. SOP Proceso de Ausentismo</a>
	<a href="..\..\doc_delivery\BK76\2021\P3.6-SOP de inducción Onboarding y funcional.pdf" class="list-group-item list-group-item-action list-group-item-light">7. SOP de inducción Onboarding y funcional</a>
	<a href="..\..\doc_delivery\BK76\2021\P3.5-SOP Ingreso de personal nuevo.pdf" class="list-group-item list-group-item-action list-group-item-light">8. SOP Ingreso de personal nuevo</a>
	<a href="..\..\doc_delivery\BK76\2021\P3.4-SOP de reclutamiento y selección del personal.pdf" class="list-group-item list-group-item-action list-group-item-light">9. SOP de reclutamiento y selección del personal</a>
	<a href="..\..\doc_delivery\BK76\2021\P3.3-SOP Contratación de personal Temporal Temporada alta.pdf" class="list-group-item list-group-item-action list-group-item-light">10. SOP Contratación de personal Temporal Temporada alta</a>
	<a href="..\..\doc_delivery\BK76\2021\P3.2-SOP Actualización de base de datos.pdf" class="list-group-item list-group-item-action list-group-item-light">11. SOP Actualización de base de datos</a>
	<a href="..\..\doc_delivery\BK76\2021\P2-SOP-Proceso de Nómina Reclamos.pdf" class="list-group-item list-group-item-action list-group-item-light">12. SOP Proceso de Nómina Reclamos</a>
	<a href="..\..\doc_delivery\BK76\2021\P2-SOP-Compensación Variable.pdf" class="list-group-item list-group-item-action list-group-item-light">13. SOP-Compensación Variable</a>
	<a href="..\..\doc_delivery\BK76\2021\OPLs PEOPLE.pdf" class="list-group-item list-group-item-action list-group-item-light">14. OPLs PEOPLE</a>
	<a href="..\..\doc_delivery\BK76\2021\11.OPLs Delivery 2021_CD TACNA.pdf" class="list-group-item list-group-item-action list-group-item-light">15. OPLs Delivery 2021</a>
	<a href="..\..\doc_delivery\BK76\2021\Politicas Tacna.pdf" class="list-group-item list-group-item-action list-group-item-light">16. Politicas Tacna</a>
	<a href="..\..\doc_delivery\BK76\2021\POLITICA PARA PREVENIR EL ACOSO SEXUAL.pdf" class="list-group-item list-group-item-action list-group-item-light">17. POLITICA PARA PREVENIR EL ACOSO SEXUAL</a>
	<a href="..\..\doc_delivery\BK76\2021\POLITICA DE VIOLENCIA DOMESTICA.pdf" class="list-group-item list-group-item-action list-group-item-light">18. POLITICA DE VIOLENCIA DOMESTICA</a>
	<a href="..\..\doc_delivery\BK76\2021\POLITICA DE DIVERSIDAD E INCLUSIÓN.pdf" class="list-group-item list-group-item-action list-group-item-light">19. POLITICA DE DIVERSIDAD E INCLUSIÓN</a>
	<a href="..\..\doc_delivery\BK76\2021\POLITICA CONTRA EL ACOSO.pdf" class="list-group-item list-group-item-action list-group-item-light">20. POLITICA CONTRA EL ACOSO</a>
	<a href="..\..\doc_delivery\BK76\2021\P3.1-Manual de Roles y Funciones.pdf" class="list-group-item list-group-item-action list-group-item-light">21. Manual de Roles y Funciones</a>	
	</ul>
  </div>    
</div>
<?php		
}else{
?>
<div class="card">
  <h5 class="card-header bg-danger text-white">Documentos delivery</h5>
  <div class="card-body">
   No hay nada que mostrar...
  </div>    
</div>
<?php		
}
	} else {
	echo $html_acceso;		
	}
	}
	require('../footer.php');
	ob_end_flush();	
	?>
