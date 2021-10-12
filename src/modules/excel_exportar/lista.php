<?php 
    ob_start();	
    $accesos = basename(dirname(__FILE__));
	require_once('../../includes/ini.php');
	require_once('../../bd/crud_usuario.php');
	$crud=new CrudUsuario();
    if ($usuarioestado==0){
	echo $html_bloqueo;
	}else{
    $arraruser = explode ( ',', $usuarioaccesos);	
	if (in_array($accesos, $arraruser)) {
	if ($usuariotipo==0): $aid_super = 0; else: $aid_super = 1; endif;
	$bootstrapjs = 1;
	require('../head.php');
	if(isset($_GET['fechaselec'])): 
	$fecha_form = $_GET['fechaselec'];
	$fechars = $_GET['fechaselec'];	
	else:
	$fechars = $fechars; 
	$fecha_form = $fecha;
	endif;
	if (isset($_GET['rs'])): $rs = $_GET['rs']; else: $rs = ""; endif;
	function lista(){
	global $url_excel_exportar;
				echo '<div class="p-3 mb-2 bg-light text-dark"><h4>Exportar</h4></div>';
					echo '<div class="list-group">';	
					foreach ($url_excel_exportar as $Exportar3 => $ExpoVal3) {
						if (is_array($ExpoVal3)){
						echo '<a href="#" class="list-group-item list-group-item-action list-group-item-light"><h6 class="mb-0">'.$Exportar3.'</h6></a>';	
							foreach ($ExpoVal3 as $Exportar4 => $ExpoVal4){
							echo '<button class="list-group-item list-group-item-action list-group-item-light"  data-toggle="modal" data-target="#'.$ExpoVal4.'" ><small class="text-muted">'.$Exportar4.'</small></button>';								
							}
						} else {
						echo '<a class="list-group-item list-group-item-action list-group-item-light" data-toggle="modal" data-target="#'.$ExpoVal3.'" ><h6 class="mb-0">'.$Exportar3.'</h6></a>';		
						}
					}
					echo '</div>';
	}
	require_once('../../bd/array/configsitio.php');
	function modal_centro_fecha($titulo,$idmodal,$action,$id,$tipofecha){
		global $fecha,$centroAR,$idcentro,$aid_super;	
    ?>
	<div class="modal fade" id="<?php echo $idmodal; ?>" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
	<div class="modal-content">
	<div class="modal-header">
	<h5 class="modal-title"><?php echo $titulo; ?></h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
    </button>
	</div>
	<div class="modal-body">
	<form id="excel" method="POST" action="<?php echo $action; ?>" >
	<div class="form-group">
    <label for="fecha_inicio">Fecha Inicio:</label>	
	<input  aria-label="First name" id="fecha_inicio" class="form-control" value="<?php echo $fecha; ?>"  type="date" name="excel[fecha_ini]">
	</div>
	<div class="form-group">
    <label for="fecha_fin">Fecha Fin:</label>	
	<input  aria-label="First name" id="fecha_fin" class="form-control" value="<?php echo $fecha; ?>"  type="date" name="excel[fecha_fin]">
	</div>	
	<div class="form-group">
	<label for="Centro">Centro</label>	
    <select class="form-control" name="excel[centro]" required >
	<option value="ALL" <?php  if ($aid_super==0): echo 'disabled'; else: echo ''; endif;	 ?>>Todos</option>
			<?php		
	foreach($centroAR as $value)
	{
			if ($value[0] == $idcentro){
			echo '<option  value="'.$value[0].'" selected >'.$value[1].'</option>';		
			}else {
			echo '<option  value="'.$value[0].'" >'.$value[1].'</option>';	
			}
	}
			?>
	</select>
	</div>
	<input type="hidden" name="excel[id]" value="<?php echo $id; ?>">
	</div>
	<div class="modal-footer">
	<input type="submit" onclick="
		this.disabled=true;this.value='Espere por favor...';this.form.submit();
		setTimeout(() => {
			this.value = 'Guardar';
			this.disabled= false;
		}, 10000);
	" class="btn btn-secondary btn-lg btn-block" id="btnsubmit" value="Guardar">
	<button type="button"  class="btn btn-danger btn-lg btn-block" data-dismiss="modal">Cerrar</button>
	</div>
	</form>
	</div>
	</div>
	</div>
	</div>	
	<?php 	
	}
	switch ($rs):
    case "listasegura":
        break;
    case "buscar_clientes_rs":
		break;	 	
    default:
    if(isset($_GET['msj'])) {
  echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">'
  		.$_GET['msj'].
		'<button type="button" class="close" data-dismiss="alert" aria-label="Close">
    	<span aria-hidden="true">&times;</span></button></div>';
	}
	lista();
	modal_centro_fecha('Hoja de Ruta Modulaciones','ModalRSexportar','csv-excel.php','RSrcahuapp',1);
	modal_centro_fecha('Poc critico T2','ModalPOCritico','csv-excel.php','POCcritico',1);
	modal_centro_fecha('Multi SMS Sender (MSS) - MPILCOSA xls','myMSJ1','csv-excel.php','MsjClientesMPILCOSA',1);
	modal_centro_fecha('SA Group Text Lite - MPILCOSA csv-excel csv','myMSJ2','csv-excel.php','Msj2ClientesMPILCOSA',1);
	modal_centro_fecha('Csv-Sf-Dia-TodosCDs','myModalFechaSFObed','csv-excel.php','AlertarSalesForceObed',1);
	modal_centro_fecha('Salida de Camiones','ModalCheckListSalida','csv-excel.php','FlotaCkLST2salida',1);
	modal_centro_fecha('Llegada de Camiones','ModalCheckListLlegada','csv-excel.php','FlotaCkLST2retorno',1);
	modal_centro_fecha('Usuarios RM','myModalUsuariosRM','csv-excel.php','usuariosRM',1);
	modal_centro_fecha('Resultados COVID - ELOPEMAM','myModalCoviD','csv-excel.php','pruevasCovid',1);
	modal_centro_fecha('Lista Asistencia T2 Rutina','myModalListaAsiT2','csv-excel.php','listaasistenciaT2',2);
	modal_centro_fecha('npsmpilcosa','myModalnpsmpilcosa','csv-excel.php','npsmpilcosa',2);
	modal_centro_fecha('Demoras Atencion T2','myModalDemoras','csv-excel.php','demoras-atencion-t2',2);
	endswitch;
	} else {
     echo $html_acceso;		
	}
	}
	require('../footer.php');
	ob_end_flush();		

?>