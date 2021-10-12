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
	$fechaselec  = $_GET['fechaselec'];	
	else:
	$fechaselec  = $fechars; 
	$fecha_form = $fecha;
	endif;
    if (isset($_GET['hc'])){ $hc = $_GET['hc']; } else { $hc = ""; }

    function califica_mi_entrega(){
		global $idcentro;
	 ?>	
	<div class="row">
    <div class="col-sm-12 bg-highlight">
	<div class="d-flex justify-content-left">
	<div class="p-2 bd-highlight"><p class="h4 text-danger">Resultados Califica mi Entrega RoadMap</div>
	</div>
	</div>
	</div> 
    <div class="table-responsive">	 
    <table id="example"  data-order='[[ 0, "asc" ]]' data-page-length='20'
          class="table table-sm table-striped table-hover table-bordered">
	<thead>
		  <tr>
          <th scope="col">#</th>
          <th scope="col">Cliente</th>
		  <th scope="col">Ruta</th>
          <th scope="col">Vehiculo</th>
		  <th scope="col">Vj</th>
		  <th scope="col">Fecha</th>
		  <th scope="col">Calificacion</th>
        </tr>
	</thead>
	<tbody>
	<?php
		$db=Db::conectar();
		$sql ="SELECT `id`, `centro`, `codigo`, `nombre`, `ruta`, `vehiculo`, 
				`viaje`, `fechaPlan`, `fecha_registro`, `estrellas`, `comentario_reparto`, `fecha_comentario` 
				FROM `t77_califica_entrega` WHERE centro='$idcentro'";
        $select=$db->prepare($sql);
		$select->execute();
		$n=1;
		while ($row=$select->fetch()){		
		?>
		<tr>
		<td><?php echo $n; ?></td>
		<td><?php echo $row['codigo'].' '.$row['nombre'];; ?></td>
		<td><?php echo $row['ruta']; ?></td>	
		<td><?php echo $row['vehiculo']; ?></td>	
		<td><?php echo $row['viaje']; ?></td>	
		<td><?php echo $row['fechaPlan']; ?></td>	
		<td><?php echo $row['estrellas']; ?></td>	
		</tr>	
		<?php 
		$n++;	
		}
		Db::desconectar();
	?>
	</tbody>
	</table>
	</div>
	<?php
	}

switch ($hc):
    case "cvs-excel":
        break;
    case "asistenciarutinat2":
        break;
	case "ejecuciondereparto":
         break;	 
	case "ModificaParametrosUser":
        break;		
	case "InsertarParametrosUser":
        break;			
	case "InicioParametrosUser":
        break;	
	case "listarcovid":  
        break;		 	
    default: 
	 califica_mi_entrega();
	endswitch;
	} else {
	echo $html_acceso;		
	}
	}
	require('../footer.php');
	ob_end_flush();	
	?>
