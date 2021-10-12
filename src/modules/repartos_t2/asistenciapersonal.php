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
	$datatablesjs = 1;
	require('../head.php');
	if(isset($_GET['fechaselec'])): 
	$fecha_form = $_GET['fechaselec'];
	$fechars = $_GET['fechaselec'];	
	else:
	$fechars = $fechars; 
	$fecha_form = $fecha;
	endif;
	$hora = date("H:i:s",$time = time());
    if (isset($_GET['hc'])){ $hc = $_GET['hc']; } else { $hc = ""; }	
	if(isset($_GET['fechaselec'])){ 
	$fechaselec = $_GET['fechaselec'];
	} else { 
	$fechaselec = $fecha; 
	}
	if(isset($_GET['empresa'])){ 
	$empresa = $_GET['empresa'];
	} else { 
	$empresa = null; 
	}
function tabla_usuarios($fechaselec,$empresa)
{ global $db,$idcentro,$aid,$fecha_hora;
    IF(IS_NULL($empresa)){ $empresawr = ''; }ELSE { $empresawr = 'AND id_empresa='.$empresa.'';}
	 ?>	
	<div class="row">
    <div class="col-sm-12 bg-highlight">
	<div class="d-flex justify-content-left">
	<div class="p-2 bd-highlight"><p class="h4 text-danger">Tiempos personal T2 : <?php echo $idcentro; ?></p> </div>
	</div>
	</div>
	</div> 
    <div class="table-responsive">	 
	 <table id="asistenciarutinat2"  data-order='[[ 0, "asc" ]]'
          class="display compact cell-border">
	<thead>
    <tr class="bg-danger text-white">
	      <th scope="col">#</th>
          <th scope="col">Empresa</th>
          <th scope="col">Nombre</th>
		  <th scope="col">Llega</th>
		  <th scope="col">Sale</th>
		  <th scope="col">Min</th>
    </tr>
	</thead>
	<tbody>
	<?php
$db=Db::conectar();
$sql ='
SELECT * FROM (
SELECT hc.nombre,hc.apellidos,hc.dni,hc.centro,hc.empresa,hc.id_empresa,tp.id,tp.fecha,tp.llegada,tp.salida,tp.minutos,IF(tp.excluye IS NULL,0,tp.excluye) AS excluye FROM 
(
SELECT 
Id,nombre,apellidos,dni,centro,id_empresa,
(SELECT descripcion FROM usuario_empresa WHERE id=id_empresa) AS empresa
FROM `usuarios`	WHERE centro=:centro AND estado=1 AND puesto IN (6,7,12,15) '.$empresawr.' ORDER BY Id DESC
) AS hc LEFT JOIN 
(
SELECT * FROM `t77_tiempos_personal` WHERE centro=:centro AND fecha=:fecha
) AS tp 
ON hc.dni = tp.dni 
) AS v ORDER BY v.empresa,v.apellidos ASC
		';
        $select=$db->prepare($sql);
		$select->bindValue('centro',$idcentro);
		$select->bindValue('fecha',$fechaselec);
		$select->execute();
		$n=1;
		while ($row=$select->fetch()){
			
		?>
		<tr>
		<td><?php echo $n; ?></td>
		<td><?php echo $row['empresa']; ?></td>
		<td><?php echo $row['apellidos'].' '.$row['nombre']; ?></td>  
		<td>
		<?php
		if($row['llegada'] == null){
		$disabled='disabled';	
		?>
<button  class="btn btn-primary btn-sm" onclick="location.href='asistenciapersonal?hc=inicio&dni=<?php echo $row['dni']; ?>&fechaselec=<?php echo $fechaselec; ?>&empresa=<?php echo $row['id_empresa']; ?>';" >Ini</button>	
		<?php
		}else {
		echo substr ($row['llegada'],11,8);
		$disabled='';		
		}
		?>
		</td>	
		<td>
		<?php
		if($row['salida'] == '0000-00-00 00:00:00'){
		?>
<button  class="btn btn-primary btn-sm" onclick="location.href='asistenciapersonal?hc=fin&id=<?php echo $row['id']; ?>&fechaselec=<?php echo $fechaselec; ?>&empresa=<?php echo $row['id_empresa']; ?>';" <?php echo $disabled; ?> >Fin</button>		
		<?php
		}else {
		echo substr ($row['salida'],11,8);	
		}
		?>
		</td>		
		<td>
		<?php
		if($row['excluye'] == 0){
		?>
<button  class="btn btn-primary btn-sm" onclick="location.href='asistenciapersonal?hc=excluye&id=<?php echo $row['id']; ?>&fechaselec=<?php echo $fechaselec; ?>&empresa=<?php echo $row['id_empresa']; ?>';" >Vacaciones</button>		
		<?php
		}else {
		echo "excluido";	
		}
		?>
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
function insertar_hora_bd($dni,$fechaselec,$empresa)
{ global $db,$idcentro,$aid,$fecha,$fecha_hora,$hora;
            $fecha_mod = $fecha.' '.$hora;
			$db=DB::conectar();
			$insert=$db->prepare('
INSERT INTO `t77_tiempos_personal`(`id`, `dni`, `fecha`, `llegada`, `salida`, `minutos`, `centro`) 
						   VALUES (null,:dni,:fecha,:llegada,:salida,:minutos,:centro)
			');
			$insert->bindValue('dni',$dni);
			$insert->bindValue('fecha',$fechaselec);
		    $insert->bindValue('llegada',$fecha_mod);
			$insert->bindValue('salida','0000-00-00 00:00:00');
			$insert->bindValue('minutos',0);
			$insert->bindValue('centro',$idcentro);
			$insert->execute();	
			Db::desconectar();			
  header('Location: asistenciapersonal?fechaselec='.$fechaselec.'&empresa='.$empresa.'');
}
function modificar_hora_bd($id,$fechaselec,$empresa)
{ global $db,$crud,$idcentro,$aid,$fecha,$fecha_hora,$hora;
$llegada = $crud->sacarmonbre_db('llegada','t77_tiempos_personal','id="'.$id.'"');
$minutos = $crud->minutosTranscurridos($llegada[0],$fecha_hora);
			$db=DB::conectar();
    		$insert=$db->prepare('
 UPDATE `t77_tiempos_personal` SET `salida`=:salida,`minutos`=:minutos WHERE `id`=:id	
			');
			$insert->bindValue('id',$id);
			$insert->bindValue('salida',$fecha_hora);
			$insert->bindValue('minutos',$minutos);
			$insert->execute();
			Db::desconectar();
   header('Location: asistenciapersonal?fechaselec='.$fechaselec.'&empresa='.$empresa.'');
}
function excluye_hora_bd($id,$fechaselec,$empresa)
{ global $db,$crud,$idcentro,$aid,$fecha,$fecha_hora;
$llegada = $crud->sacarmonbre_db('llegada','t77_tiempos_personal','id="'.$id.'"');
$minutos = $crud->minutosTranscurridos($llegada[0],$fecha_hora);
			$db=DB::conectar();
    		$insert=$db->prepare('
 UPDATE `t77_tiempos_personal` SET `excluye`=:excluye WHERE `id`=:id	
			');
			$insert->bindValue('id',$id);
			$insert->bindValue('excluye',1);
			$insert->execute();
			Db::desconectar();
   header('Location: asistenciapersonal?fechaselec='.$fechaselec.'&empresa='.$empresa.'');
}

switch ($hc):
    case "inicio":
	if(isset($_GET['dni']) && $aid){
	insertar_hora_bd($_GET['dni'],$fechaselec,$empresa);
	}
        break;
    case "fin":
	modificar_hora_bd($_GET['id'],$fechaselec,$empresa);
        break;
	case "excluye":
	excluye_hora_bd($_GET['id'],$fechaselec,$empresa);
         break;
	case "ModificaParametrosUser":
        break;		
	case "InsertarParametrosUser":
        break;			
	case "InicioParametrosUser":	
        break;	
	case "EliminarRespuesta":  
        break;	
	case "EliminarPregunta":  
        break;	
	case "EliminarGrupoPregunta":  
        break;			
    case "registragrupo":
        break;
    case "modificagrupo":     	
        break;		
    case "RegistraPregunta":
        break;	
 	case "registraespuesta": 
        break;	 	
    default:  
		?>
	<div class="row border">
    <div class="col-sm-12 bg-danger">
	<div class="d-flex justify-content-center">
	<div class="p-2 bd-highlight"><div class="text-white text-md-center font-weight-bolder">Asistencia <?php echo $idcentro.'-'.$fechaselec; ?></div></div>
	<div class="p-2 bd-highlight"><button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#myModalFecha">Filtro</button></div>
	</div>
	</div>
	</div>	
   <!-- Modal Inicio-->
	<div class="modal fade" id="myModalFecha" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
	<form method="GET" action="asistenciapersonal" >
	<div class="form-group">
	<label for="formGroupExampleInput">Fecha</label>
	<input  aria-label="First name" id="fechastema" class="form-control" value="<?php echo $fechaselec; ?>" placeholder="Fecha inicio" type="date" name='fechaselec' required> 
	</div>
    <div class="form-group">
    <label for="formGroupExampleInput">Empresa</label>
	<select class="form-control form-control-sm" name="empresa" required>
	<option  value="">Seleccionar...</option>
			<?php
  			$db=Db::conectar();
			$selectcentro=$db->prepare('SELECT * FROM usuario_empresa WHERE centro=:centro');
			$selectcentro->bindValue('centro',$idcentro);
			$selectcentro->execute();
			while ($rowcentro=$selectcentro->fetch()) {
			if ($rowcentro['id'] == $empresa){
			echo '<option  value="'.$rowcentro['id'].'" selected >'.$rowcentro['descripcion'].'</option>';		
			}else {
			echo '<option  value="'.$rowcentro['id'].'" >'.$rowcentro['descripcion'].'</option>';	
			}
			}
			Db::desconectar();
	?>
	</select>  	
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
	 tabla_usuarios($fechaselec,$empresa);
	endswitch;
} else {
 echo $html_acceso;		
}
}
require('../footer.php');
ob_end_flush();	
?>
