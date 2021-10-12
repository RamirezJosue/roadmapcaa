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
	$mapasjs =  0;
	$datatablesjs = 0;
	require('../head.php');
	if(isset($_GET['fechaselec'])): 
	$fecha_form = $_GET['fechaselec'];
	$fechars = $_GET['fechaselec'];	
	else:
	$fechars = $fechars; 
	$fecha_form = $fecha;
	endif;
    if (isset($_GET['hc'])){ $hc = $_GET['hc']; } else { $hc = ""; }
	if(isset($_POST['fechaselec'])){ 
	$fechaselec = $_POST['fechaselec'];
	} else { 
	$fechaselec = $fecha; 
	}
    $tiporeclamo = array(1=>'Actitus descortes - Reparto',
				2=>'Recojo indebido de envases del POS',
				3=>'Pedido no entregado',
				4=>'Producto incompleto segun comprobante',
				5=>'NO entregado de comprobante',
				6=>'Entrega de vuelto inadecuado',
				7=>'NO sabe operar BEES',
				8=>'Dejaron un producto por otro',
				9=>'Daño ocasionado en vivienda y/o  local',
				10=>'No reconoce deuda',
				11=>'Cajas en mal estado',
				12=>'Bonificaciones no planificadas',
				13=>'NO recibe visita de Ventas',
				14=>'No le llega descuento ofrecidos',
				15=>'No recogen PFN',
				16=>'Corregir geoposicion de cliente');
function modal_add_solucion($id)
{ global $fecha,$idcentro,$aid,$crud,$tiporeclamo;
$arrayreclamo = $crud-> arrar_bd_return('tipo_reclamo','t77_reclamos','id = "'.$id.'"');
//$descripcion=$arrayreclamo[0];
$tiporecla=$arrayreclamo[0];	 
	?>
<div class="modal fade" id="modaladdsolucion<?php echo $id; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="exampleModalLabel"><?php echo $tiporeclamo[$tiporecla]; ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
   <table class="table table-sm table-bordered">
   <thead>
    <tr class="bg-danger text-white">
      <th scope="col"><small>Fecha</small></th>
	  <th scope="col"><small>Usuario</small></th>
      <th scope="col"><small>Texto</small></th>
    </tr>
   </thead>
   <tbody>
   <?php 
 		$db=Db::conectar();
		$sql ="SELECT * FROM `t77_reclamos_det` WHERE id_reclamo=$id";
        $select=$db->prepare($sql);	
        //$select->bindValue('centro',$idcentro);	
		//$select->bindValue('fecha',$fechaselec);
		$select->execute();
		$n=1;
		while ($rowrcl=$select->fetch()) {  
    ?>
    <tr>
      <td><small><?php echo $rowrcl['fecha_registro']; ?></small></td>
      <td><small><?php echo $rowrcl['usuario_registro']; ?></small></td>
	  <td><small><?php echo $rowrcl['descripcion']; ?></small></td>
    </tr>
	<?php
		}
		Db::desconectar();		
	?>
  </tbody>
  </table>
   <form method="post" action="reclamos?hc=modaladdreclamosdet">	
  	<div class="form-group">
            <label for="message-text" class="col-form-label">Agregar solucion:</label>
            <textarea class="form-control" id="message-text" required name="rclmmensaje"></textarea>
    </div> 	
	<input type="hidden" id="id" name="id" value="<?php echo $id; ?>">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary">Grabar</button>
      </div>
	  </form>  
    </div>
  </div>
</div>	
	<?php 
}
function add_reclamos_det($id_reclamo,$descripcion)
{ global $fecha_hora,$idcentro,$aid,$crud,$tiporeclamo;
			$db=DB::conectar();
			$insert=$db->prepare('
INSERT INTO `t77_reclamos_det`(`id`, `id_reclamo`, `descripcion`, `fecha_registro`, `usuario_registro`) VALUES 
                          (null,:id_reclamo,:descripcion,:fecha_registro,:usuario_registro)
			');
			$insert->bindValue('id_reclamo',$id_reclamo);
			$insert->bindValue('descripcion',$descripcion);
			$insert->bindValue('fecha_registro',$fecha_hora);
			$insert->bindValue('usuario_registro',$aid);
			$insert->execute();
			Db::desconectar();
}
function cambia_estado_reclamo($id_reclamo)
{ global $fecha_hora,$idcentro,$aid,$crud,$tiporeclamo;
			$db=DB::conectar();
			$insert=$db->prepare('
UPDATE `t77_reclamos` SET  `estado`=:estado WHERE `id`=:id
			');
			$insert->bindValue('id',$id_reclamo);
			$insert->bindValue('estado',1);
			$insert->execute();
			Db::desconectar();
}						
function reclamos_inicio()
{ global $fecha,$idcentro,$aid,$crudex,$tiporeclamo;	
?>	
<div class="table-responsive-sm">
	<table id="reclamoinicio"  data-order='[[ 0, "asc" ]]'
          class="display compact cell-border">
	<thead>
    <tr class="bg-danger text-white">
	  <th><small>#</small></th>
      <th><small>TipoReclamo</small></th>
	  <th><small>Cliente</small></th>
	  <th><small>Supervisor</small></th>
	  <th><small>Agente</small></th>
	  <th><small>Telefonos</small></th>
	  <th><small>DescripcionReclamo</small></th>
	  <th><small>F.Reg.</small></th>
	  <th><small>F.Fin</small></th>
	  <th><small>Estado</small></th>
	  <th><small>Score</small></th>
	  <th></th>
	  <th></th>
    </tr>
	</thead>
	<tbody>
  <?php		
		$db=Db::conectar();
		$sql ="
SELECT 
a.id,
a.tipo_reclamo,
a.cliente,
a.telefono,
a.descripcion_cliente,
a.estado,
a.califica_cliente,
a.fecha_registro,
a.fecha_solucion_fin,
a.usuario_respuesta,
b.centro,
b.nombre,
b.direccion,
b.distrito,
b.zonaac,
b.Telef1,
b.Telef2,
b.diatv,
b.diaac,
b.zonatv,
b.supervisor,
b.agente,
b.RUCoDNI,
b.NRo_DOC
FROM 
(
SELECT * FROM `t77_reclamos`    
)AS a LEFT JOIN 
(
SELECT * FROM `t77_mc`
) AS b ON a.cliente = b.codcli	
		";
        $select=$db->prepare($sql);	
        //$select->bindValue('centro',$idcentro);	
		//$select->bindValue('fecha',$fechaselec);
		$select->execute();
		Db::desconectar();
		$n=1;
		while ($rows=$select->fetch()) {
		//IF($rows['asiste']==1) { $classs='class="table-success"'; } else { $classs='class="table-danger"'; }
		if($rows['estado']==0){
		$st='Sin gestión'; $color='light';
		}elseif($rows['estado']==1){
		$st='En tramite'; $color='warning';	
		}elseif($rows['estado']==2){
		$st='Resuelto'; $color='success';		
		}
		$id=$rows['id'];
		?>
		<tr  class="table-<?php echo $color; ?>" >
		<td><small><?php echo $n; ?></small></td>		
		<td><small><?php echo $tiporeclamo[$rows['tipo_reclamo']]; ?></small></td>
		<td><small><?php echo $rows['cliente'].'-'.$rows['nombre'].'<br>'.$rows['direccion'].'-'.$rows['distrito'].'-'.$rows['telefono']; ?></small></td>
		<td><small><?php echo $rows['supervisor']; ?></small></td>
		<td><small><?php echo $rows['zonaac'].''.$rows['agente']; ?></small></td>
		<td><small><?php echo $rows['Telef1'].''.$rows['Telef2']; ?></small></td>
		<td><small><?php echo $rows['descripcion_cliente']; ?></small></td>
		<td><small><?php echo $rows['fecha_registro']; ?></small></td>
		<td><small><?php echo $rows['fecha_solucion_fin']; ?></small></td>
        <td><span class="badge badge-<?php echo $color; ?>"><?php echo $st; ?></span></td>
		<td><small><?php echo $rows['califica_cliente']; ?></small></td>
		<td>
  <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#modaladdsolucion<?php echo $id; ?>">Soluciones</button>
		</td> 
		<td><?php modal_add_solucion($id); ?></td>
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
    case "modaladdreclamosdet":
	
	if(isset($_POST['id'],$_POST['rclmmensaje'])){
	add_reclamos_det($_POST['id'],$_POST['rclmmensaje']);
    cambia_estado_reclamo($_POST['id']);	
	?>
	<div class="alert alert-success" role="alert"> Registrado !</div>
	<?php
	 header('Refresh: 2; URL=reclamos');	
	}else{
	?>
	<div class="alert alert-danger" role="alert"> No registrado !</div>
	<?php		
	 header('Refresh: 2; URL=reclamos');
	}
        break;
    case "asistenciarutinat2":
        break;
	case "ejecuciondereparto":
         break;
	case "licenciasconducir":
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
     reclamos_inicio();	 
	endswitch;
} else {
 echo $html_acceso;		
}
}
require('../footer.php');
ob_end_flush();	
?>