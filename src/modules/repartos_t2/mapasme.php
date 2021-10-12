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
	/*inicio vefifia si tiene permisos de adminrepartos */
	if (in_array("adminrepartos", $arraruser)): $adminrepartos = 1; else: $adminrepartos = 0; endif;
    if ($aid_super==1 || $adminrepartos==1): $disableform = ''; else: $disableform = 'disabled'; endif;
	/*fin vefifia si tiene permisos de adminrepartos */
	$bootstrapjs =  1;	
	$datatablesjs = 1;
	require('../head.php');
	if(isset($_POST['fechaselec'])): 
	$fecha_form = $_POST['fechaselec'];
	$fechars = $_POST['fechaselec'];	
	else:
	$fechars = $fechars; 
	$fecha_form = $fecha;
	endif;
	if (isset($_GET['exa'])){ $exa = $_GET['exa']; } else { $exa = ""; }
function flota_masp_me()
{
global $fechars, $idcentro, $aid;	
?>	
<div class="table-responsive-sm">
<table class="table table-sm">
  <thead>
    <tr>
      <th scope="col">Ruta|Viaje|Vehiculo|Fecha</th>
	  <th scope="col"></th>
    </tr>
  </thead>
  <tbody>
  <?php		
		$db=Db::conectar();
		$sql ="SELECT Ruta,Viaje,Vehiculo,Fecha FROM `t77_rs` WHERE Fecha=:Fecha AND centro=:centro GROUP BY Ruta,Viaje,Vehiculo,Fecha ORDER BY Ruta ASC";
        $select=$db->prepare($sql);
		$select->bindValue('Fecha',$fechars);		
		$select->bindValue('centro',$idcentro);
		$select->execute();
		$n=1;
		while ($rows=$select->fetch()) {		
		?>
		<tr>
		<td><?php echo $rows['Ruta'].'|'.$rows['Viaje'].'|'.$rows['Vehiculo'].'|'.$rows['Fecha']; ?></td>
		<td>
		<div class="btn-group" role="group" aria-label="Basic example">
        <a class="btn btn-primary btn-sm" href="dronemaps?id=<?php echo $rows['Ruta']; ?>&amp;fecha=<?php echo $rows['Fecha']; ?>&amp;vj=<?php echo $rows['Viaje']; ?>" target="_blank" >Google</a>
		<button type="button" class="btn btn-danger btn-sm" onclick="location.href='kml_rep?id=<?php echo $rows['Ruta']; ?>&amp;fecha=<?php echo $rows['Fecha']; ?>&amp;vj=<?php echo $rows['Viaje']; ?>';" >kml</button>
		</div>
		</td>
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


switch ($exa):
    case "iniciarxxxx":
	break;
    default:
	?>
	<a href="https://play.google.com/store/apps/details?id=com.mapswithme.maps.pro&hl=es_PE&gl=US" class="badge badge-success">Descarga aqui la APP MAPS.ME – Mapas sin conexión</a>
	<div class="row">
    <div class="col-sm-12 bg-highlight">
	<div class="d-flex justify-content-left">
	<div class="p-2 bd-highlight"><p class="h4 text-danger">Maps ME : <?php echo $fechars; ?></p> </div>
	<div class="p-2 bd-highlight"><button type="button" class="btn btn-danger" data-toggle="modal" data-target="#myModalMapsME">Fecha</button></div>
	</div>
	</div>
	</div>
	
	    <!-- Modal Inicio-->
	<div class="modal fade" id="myModalMapsME" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
	<!-- Modal content-->
	<div class="modal-content">
	<div class="modal-header">
	<h5 class="modal-title">Seleccionar Fecha</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
    </button>
	</div>
	<div class="modal-body">
	<form method="post" action="mapasme">
	<div class="form-row">	
	<input  aria-label="First name" id="fechastema" class="form-control" value="<?php echo $fecha_form; ?>" placeholder="Fecha inicio" type="date" name='fechaselec'> 
	</div>
	<div class="modal-footer">
	<button type="submit" class="btn btn-secondary btn-lg btn-block">Guardar</button>
	<button type="button"  class="btn btn-danger btn-lg btn-block" data-dismiss="modal">Cerrar</button>
	</div>
	</form>
	</div>
	</div>
	</div>
	</div>
	<!-- Modal Fin-->  
	<?php 
	flota_masp_me();
	endswitch;
	} else {
	echo $html_acceso;		
	}
	}
	require('../footer.php');
	ob_end_flush();	
	?>
