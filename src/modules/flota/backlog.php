<?php 
    ob_start();	
    $accesos = basename(dirname(__FILE__));
	require_once('../../includes/ini.php');
	require_once('../../bd/crud_usuario.php');
	require_once('../../bd/crud_examen.php');
	require_once('../../bd/array/configexamen.php');
	$crudex=new CrudExamen();
	$crud=new CrudUsuario();
	$aleatorio = uniqid();
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
    if (isset($_GET['hc'])){ $hc = $_GET['hc']; } else { $hc = ""; }
	$sistema = array(0=>'', 1=>'Cabina', 2=>'Carrocería', 3=>'Chasis', 4=>'Dirección',5=>'Eléctrico',6=>'Electrónico',7=>'Ejes',8=>'Escape',9=>'Frenos',10=>'Hidráulico',11=>'Implementos',12=>'Llantas',13=>'Motor',14=>'Suspensión',15=>'Transmisión');
function form_agregar_backlog($fecha,$id_tema,$grupo,$user_registro)
{
global $fecha,$idcentro,$aid,$crudex;	
?>	
	<div class="row">
    <div class="col-sm-12 bg-danger">
	<div class="p-2"><div class="text-white text-left font-weight-bolder">Respuestas observadas</div></div>
	</div>
	</div>	
	<div class="row">
	<div class="col-12">
	<div class="table-responsive">
	 <table id="example_estado"  data-order='[[ 0, "asc" ]]'
          class="display compact cell-border">
	<thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">user_registro</th>
	  <th scope="col">pregunta</th>
	  <th scope="col">desc_resp</th>
	  <th scope="col">respuesta_user</th>
	  <th scope="col">fecha_fin_user</th>
	  <th scope="col">resultado</th>
	  <th scope="col"></th>
    </tr>
	</thead>
	<tbody>
  <?php		
		$db=Db::conectar();
		$sql ="
SELECT * FROM (
SELECT a.id,a.user_registro,a.id_respueta,a.pregunta,a.id_preguntas,a.desc_resp,a.respuesta_user,a.fecha_fin_user,a.fecha,if((sum(a.Ok)+sum(a.Nok))=0,2,a.st) as estado,a.grupo,
sum(a.Ok) as Ok,
sum(a.Nok) as Nok,
round(IF((sum(a.Ok)/(sum(a.Ok)+sum(a.Nok))) IS NULL,0,(sum(a.Ok)/(sum(a.Ok)+sum(a.Nok)))*100),2) as resultado 
FROM (
SELECT id,user_registro,id_respueta,desc_resp,fecha,id_preguntas,(SELECT pregunta FROM `exa_preguntas` WHERE id=id_preguntas) as pregunta ,respuesta_user,tipo_pregunta,fecha_fin_user,st,grupo,
IF(respuesta_user = '',0,if(tipo_pregunta='7',if(CAST(respuesta_user AS SIGNED) >= 3,1,0),if(respuesta_user = 'Si',1,0)))  as Ok, 
IF(respuesta_user = '',0,if(tipo_pregunta='7',if(CAST(respuesta_user AS SIGNED) < 3,1,0),if(respuesta_user = 'No',1,0))) as Nok    
FROM `exa_detalle_user` WHERE fecha=:fecha AND centro=:centro AND id_tema=:id_tema AND tipo_pregunta IN ('4','7','6') AND grupo=:grupo AND user_registro=:user_registro AND respuesta_user <> ''
	 ) AS a GROUP BY a.id,a.user_registro,a.id_respueta,a.pregunta,a.id_preguntas,a.desc_resp,a.respuesta_user,a.fecha,a.fecha_fin_user,a.st,a.grupo
	 ) as b WHERE b.resultado < 100
		";
        $select=$db->prepare($sql);	
        $select->bindValue('fecha',$fecha);
        $select->bindValue('centro',$idcentro);
        $select->bindValue('id_tema',$id_tema);	
	    $select->bindValue('grupo',$grupo);
        $select->bindValue('user_registro',$user_registro);		
		$select->execute();
		$n=1;
		while ($rows=$select->fetch()) {
        $user_registro = $rows['user_registro']; // vehiculo 		
		$id_respueta = $rows['id_respueta'];
		$desc_resp = $rows['desc_resp'];
		$respuesta_user = $rows['respuesta_user'];
		$fecha_fin_user = $rows['fecha_fin_user'];		
		?>
		<tr>
		<td><?php echo $n; ?></td>
		<td><?php echo $rows['user_registro']; ?></td>
		<td><?php echo $rows['pregunta']; ?></td>
        <td><?php echo $rows['desc_resp']; ?></td>
	    <td><?php echo $rows['respuesta_user']; ?></td>
		<td><?php echo $rows['fecha_fin_user']; ?></td>	
	    <td><?php echo $rows['resultado']; ?></td>                                           	
        <td><button type="button" class="btn btn-danger" onclick="location.href='backlog?hc=backlogadd&id_tema=<?php echo $id_tema; ?>&user_registro=<?php echo $user_registro; ?>&fecha=<?php echo $fecha; ?>&grupo=<?php echo $rows['grupo']; ?>&id=<?php echo $rows['id']; ?>';" >Agregar</button></td>
		</tr>	
		<?php 
		$n++;	
		}
		Db::desconectar();
	?>
	</tbody>
	</table>
	</div>	
	<button type="button" class="btn btn-primary" onclick="location.href='backlog?hc=backlogcrud';" >Cancelar</button>
	<button type="button" class="btn btn-primary" onclick="location.href='reporte';" >Reporte</button>
	</div>	
	</div>
	<br>
<?php
}
function backlogcrud()
{
global $fecha,$idcentro,$aid,$crudex,$fecha_hora,$crud;	
?>	
	<div class="row mb-2">
    <div class="col-sm-12 bg-danger">
	<div class="p-2">
	<div class="text-white text-left font-weight-bolder">Back Log registrados
	<button type="button" class="btn btn-dark btn-sm" onclick="location.href='reporte';" >Ir a reporte</button>
	</div>
	</div>
	</div>
	</div>	
	<div class="card-columns">
  <?php		
		$db=Db::conectar();
		$sql ="
     SELECT 
	  `id`, `indx`, `vehiculo`, `tipo_flota`, `empresa`, `anomalia_checklist`, `descripcion_anomalia`, 
	  `sistema`, `sub_sistema`, `nivel_correctivo`, `meta_atencion_Hrs`, `fecha_reporte_falla`, `fecha_inicio_reparacion`, 
	  `fecha_fin_reparacion`, `minimo_vital`, `tiempo_atencion_Hrs`, `plan_de_accion`, `centro`	 
	 FROM `t77_back_log` WHERE centro=:centro ORDER BY fecha_reporte_falla DESC
		";
        $select=$db->prepare($sql);	
        $select->bindValue('centro',$idcentro);	
		$select->execute();
		$n=1;
		while ($rows=$select->fetch()) {
		if($rows['fecha_fin_reparacion']=='0000-00-00 00:00:00'){	
				$fechahora_fin=$fecha_hora;		
		}else { 
				$fechahora_fin = $rows['fecha_fin_reparacion']; 
		}	
		if($rows['fecha_inicio_reparacion']=='0000-00-00 00:00:00' && $rows['fecha_fin_reparacion']=='0000-00-00 00:00:00'){
			$desactivar = '';
			$color='danger';
		}else if ($rows['fecha_inicio_reparacion']!='0000-00-00 00:00:00' && $rows['fecha_fin_reparacion']=='0000-00-00 00:00:00'){
			$desactivar = '';
			$color='warning';
		}else if ($rows['fecha_inicio_reparacion']!='0000-00-00 00:00:00' && $rows['fecha_fin_reparacion']!='0000-00-00 00:00:00'){
			$desactivar = 'disabled';
			$color='success';
		}	
		?>
	<div class="card mb-2 border-<?php echo $color; ?>" >
		<div class="card-body">
		<h6 class="card-subtitle"><?php echo $rows['vehiculo'].' '.$rows['tipo_flota']; ?></h6>
		<div class="dropdown-divider"></div>
		<p class="card-text">
		<b>Empresa:</b> <?php echo $rows['empresa']; ?><br>
	    <b>Anomalia:</b>  <?php echo $rows['anomalia_checklist']; ?><br>
	    <b>Descripción:</b> <?php echo $rows['descripcion_anomalia']; ?><br>
	    <b>Sistema:</b>  <?php echo $rows['sistema']; ?><br>
	    <b>Sub sistema:</b> <?php echo $rows['sub_sistema']; ?><br> 
	    <b>Nivel correctivo:</b> <?php echo $rows['nivel_correctivo']; ?><br>
	    <b>Meta atención Hrs:</b> <?php echo $rows['meta_atencion_Hrs']; ?><br>
	    <b>Fecha reporte falla:</b> <?php echo $rows['fecha_reporte_falla']; ?><br>
	    <b>Inicio reparación:</b> <?php echo $rows['fecha_inicio_reparacion']; ?><br>
	    <b>Fin reparación:</b> <?php echo $rows['fecha_fin_reparacion']; ?><br>
	    <b>Criticidad:</b> <?php echo $rows['minimo_vital']; ?><br>
		<span class="text-<?php echo $color; ?>">
	    <b>Tiempo atención :</b> <?php echo $crud -> tiempoTranscurridoFechas($rows['fecha_reporte_falla'],$fechahora_fin); ?><br>
		</span>
	    <b>Plan de acción:</b> <?php echo $rows['plan_de_accion']; ?>
		</p>
		</div>
		<div class="card-footer text-muted">
		<div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
		<form  onsubmit="return confirm('Estas seguro de eliminar el resgistro <?php echo $rows['vehiculo']; ?> inicio de reparacion <?php echo $rows['fecha_inicio_reparacion']; ?>');" method='POST' action='backlog?hc=eliminarbacklog&amp;id=<?php echo $rows['id']; ?>&amp;vehiculo=<?php echo $rows['vehiculo']; ?>'>		
	    <button class="btn btn-danger btn-sm" <?php echo $desactivar; ?> >Eliminar</button>
	    </form>	
		<button type="button" class="btn btn-danger btn-sm" onclick="location.href='backlog?hc=finalizarbacklog&amp;id=<?php echo $rows['id']; ?>&amp;vehiculo=<?php echo $rows['vehiculo']; ?>';" <?php echo $desactivar; ?> >Finalizar</button>		
		</div>
		</div>
	</div>
		<?php 
		$n++;	
		}
		Db::desconectar();
	?>
	</div>	
<?php
}
function form_agregar_backlog_pos($fecha,$id_tema,$grupo,$user_registro,$id){
	global $idcentro,$aid,$crud,$tipocuestionario,$fecha,$tipopregunta,$letrasresp;
	$respuestausuario = $crud-> sacarmonbre_db('desc_resp,respuesta_user,fecha_fin_user,id_respueta','exa_detalle_user','id="'.$id.'"');
	?>
	<div class="row">
    <div class="col-sm-12 bg-danger">
	<div class="p-2"><div class="text-white text-left font-weight-bolder">Formulario BackLog agregar</div></div>
	</div>
	</div>	
    <div class="row">
	<div class="col-12">
	<form name="f1" class="w3-container" method="post" >
	<div class="form-group">
    <label for="formGroupExampleInput">Vehiculo</label>
    <input type="text" class="form-control form-control-sm" id="formGroupExampleInput" value="<?php echo $user_registro.' | '.$respuestausuario[2];?>" placeholder="Example input placeholder" required disabled>
    </div>
    <div class="form-group">
    <label for="formGroupExampleInput">Pregunta respuesta</label>
	<textarea class="form-control form-control-sm" id="validationTextarea" placeholder="Ingrese una descripción de la falla" name="comentario" required disabled><?php echo $respuestausuario[0].' | operativo : '.$respuestausuario[1];?></textarea>
    </div>
    <div class="form-group">
    <label for="validationTextarea">Descripción anomalia / falla </label>
    <textarea class="form-control form-control-sm" id="validationTextarea" placeholder="Ingrese una descripción de la falla" name="comentariofalla" required></textarea>
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
			if ($rowcentro['descripcion'] == $value){
			echo '<option  value="'.$rowcentro['descripcion'].'" selected >'.$rowcentro['descripcion'].'</option>';		
			}else {
			echo '<option  value="'.$rowcentro['descripcion'].'" >'.$rowcentro['descripcion'].'</option>';	
			}
			}
			Db::desconectar();
	?>
	</select>  	
    </div>	
    <div class="form-group">
    <label for="validationTextarea">Tipo flota</label>
	<select class="form-control form-control-sm" name="tipoflota" required > 
    <option value="" selected>Seleccione... 
    <option value="FLT">FLT 
    <option value="T2 Motriz">T2 Motriz    
    </select>
    </div>	
    <div class="form-group">
    <label for="validationTextarea">Sistema</label>
	<select class="form-control form-control-sm" name="sistema" onchange="cambia_sistema()" required > 
    <option value="" selected>Seleccione... 
    <option value="1">Cabina 
    <option value="2">Carrocería 
    <option value="3">Chasis 
    <option value="4">Dirección 
	<option value="5">Eléctrico 
	<option value="6">Electrónico 
	<option value="7">Ejes 
	<option value="8">Escape 
	<option value="9">Frenos 
	<option value="10">Hidráulico 
	<option value="11">Implementos 
	<option value="12">Llantas 
	<option value="13">Motor 
	<option value="14">Suspensión 
	<option value="15">Transmisión   
    </select>
    </div>
    <div class="form-group">
    <label for="validationTextarea">Sub sistema</label>
    <select class="form-control form-control-sm" id='opciones' name="subsistema" onchange='cambioOpciones();' required> 
    <option value=""> 
    </select> 
    </div>	
	<div class="form-group">
    <label for="formGroupExampleInput">Nivel de correctivo</label>
    <input class="form-control form-control-sm" type="text" name="nivelcorrectivo" id="nivelcorrectivo">
    </div>
	<div class="form-group">
    <label for="formGroupExampleInput">Tiempo de atencion Hras</label>
    <input class="form-control form-control-sm" type="text" name="tiempo" id="tiempo" >
    </div>
	<div class="form-group">
    <label for="formGroupExampleInput">Nivel de criticidad</label>
    <input class="form-control form-control-sm" type="text" name="criticidad" id="criticidad" >
    </div>	
	<button type="submit" class="btn btn-danger btn-lg btn-block">Guardar</button>
	<input name="vehiculo"  	 type="hidden" value="<?php echo $user_registro; ?>">  
	<input name="desc_resp" 	 type="hidden" value="<?php echo $respuestausuario[0].' '.$respuestausuario[1]; ?>">
	<input name="grupo"     	 type="hidden" value="<?php echo $grupo; ?>">
	<input name="indx"        	 type="hidden" value="<?php echo $id; ?>">
	<input name="fecha_fin_user" type="hidden" value="<?php echo $respuestausuario[2]; ?>">
	</form>
    </div>	
	</div>
	<br>
	<?php 
}
switch ($hc):
    case "cvs-msj":
	?>
	<div class="list-group">
	<li class="list-group-item list-group-item-action active disabled" aria-current="true" >CSV</li>
	<a href="csv" class="list-group-item list-group-item-action">Generar CSV clientes reparto</a>
	<a href="#" class="list-group-item list-group-item-action disabled">A third link item</a>
	<a href="#" class="list-group-item list-group-item-action disabled">A fourth link item</a>
	<a href="#" class="list-group-item list-group-item-action disabled" tabindex="-1" aria-disabled="true">A disabled link item</a>
	</div>
	<?php 
        break;
    case "backlogadd":
	if(isset($_GET['fecha'],$_GET['id_tema'],$_GET['grupo'],$_GET['user_registro'])){
	 $fecha=$_GET['fecha'];
	 $id_tema=$_GET['id_tema'];
	 $grupo=$_GET['grupo'];	 
	 $user_registro=$_GET['user_registro'];
	form_agregar_backlog($fecha,$id_tema,$grupo,$user_registro);
	}
	if(isset($_GET['id'])){
    $id = $_GET['id'];
	form_agregar_backlog_pos($fecha,$id_tema,$grupo,$user_registro,$id);
	}
	if(isset($_POST['sistema'],$_POST['subsistema'],$_POST['nivelcorrectivo'],$_POST['tiempo'],$_POST['criticidad'])){
	$indx=$_POST['indx'];
	$sistema=$sistema[$_POST['sistema']];
	$sub_sistema=$_POST['subsistema'];
	$nivel_correctivo=$_POST['nivelcorrectivo'];
	$meta_atencion_Hrs=$_POST['tiempo'];
	$minimo_vital=$_POST['criticidad'];		
	$vehiculo=$_POST['vehiculo'];
	$tipo_flota=$_POST['tipoflota'];
	$empresa=$_POST['empresa'];
	$anomalia_checklist=$_POST['desc_resp'];
	$descripcion_anomalia=$_POST['comentariofalla'];
	$fecha_reporte_falla=$_POST['fecha_fin_user'];
	if($crud->BuscarRegistro("SELECT id FROM `t77_back_log` WHERE `indx`=$indx")==false)
	{
	?>
	<div class="alert alert-danger" role="alert">El registro ya existe</div>
	<?php 
	}else {
	$crud->Insertarbacklog($indx,$vehiculo,$tipo_flota,$empresa,$anomalia_checklist,$descripcion_anomalia,$sistema,$sub_sistema,$nivel_correctivo,$meta_atencion_Hrs,$fecha_reporte_falla,$fecha_hora,$minimo_vital,$idcentro);
	header('Location: backlog?hc=backlogcrud');
	}
	}
    break;
	case "backlogcrud":
	backlogcrud();
         break;
	case "eliminarbacklog":
	if(isset($_GET['id'],$_GET['vehiculo'])){
	$id=$_GET['id'];
	$vehiculo=$_GET['vehiculo']; 
	$crud->eliminar_registro("DELETE FROM `t77_back_log` WHERE id=$id AND vehiculo='$vehiculo' AND centro='$idcentro' AND estado <> 1");
	header('Location: backlog?hc=backlogcrud');
	} 
         break;	 
	case "finalizarbacklog":	
	if(isset($_GET['id'],$_GET['vehiculo'])){
	$id=$_GET['id'];
	$vehiculo=$_GET['vehiculo'];	
	$respuestausuario = $crud-> sacarmonbre_db(' * ','t77_back_log','id="'.$id.'"');
	if($respuestausuario['estado']==0){ $disabled=''; }else{ $disabled='disabled'; }
	if(isset($_POST['comentariosolucion'])){
	$comentariosolucion=$_POST['comentariosolucion'];
	IF($respuestausuario['estado']==0){
	$horas = (($crud->minutosTranscurridos($respuestausuario['fecha_reporte_falla'],$fecha_hora))/60);
	$crud->comcluir_backlog($fecha_hora,$horas,$comentariosolucion,$id,$idcentro);
	header('Location: backlog?hc=backlogcrud');
	} else { }
	}
	?>
	<div class="row">
    <div class="col-sm-12 bg-danger">
	<div class="p-2"><div class="text-white text-left font-weight-bolder">Comcluir reparacion de vehiculo</div></div>
	</div>
	</div>
	<div class="row">
	<div class="col-12">
	<form action="backlog?hc=finalizarbacklog&amp;id=<?php echo $id; ?>&amp;vehiculo=<?php echo $vehiculo; ?>" class="w3-container" method="post" >
	<div class="form-group">
    <label for="formGroupExampleInput">Vehiculo</label>
    <input type="text" class="form-control form-control-sm" id="formGroupExampleInput" value="<?php echo $vehiculo.$id.' - '.$respuestausuario['fecha_reporte_falla'];?>"  required disabled>
    </div>
    <div class="form-group">
    <label for="formGroupExampleInput">Pregunta / respuesta / check list</label>
	<textarea class="form-control form-control-sm" id="validationTextarea"  name="comentario" required disabled><?php echo $respuestausuario['anomalia_checklist'];?></textarea>
    </div>
    <div class="form-group">
    <label for="validationTextarea">Descripción anomalia / falla </label>
    <textarea class="form-control form-control-sm" id="validationTextarea"  name="comentariofalla" required disabled><?php echo $respuestausuario['descripcion_anomalia'];?></textarea>
    </div>
	<div class="form-group">
    <label for="validationTextarea">Comentario de final</label>
    <textarea class="form-control" id="validationTextarea" placeholder="Ingrese un comentario final" name="comentariosolucion" required <?php echo $disabled; ?> ><?php echo $respuestausuario['plan_de_accion']; ?></textarea>
    </div>
	<button type="button" class="btn btn-primary" onclick="location.href='backlog?hc=backlogcrud';" >Cancelar</button>
	<button type="submit" class="btn btn-primary" <?php echo $disabled; ?> >Comcluir</button>
	<input name="vehiculo"  	 type="hidden" value="<?php echo $id; ?>">  
	<input name="desc_resp" 	 type="hidden" value="<?php echo $vehiculo; ?>">
	</form>
    </div>	
	</div>
	<?php 
	}
        break;		
	case "InsertarParametrosUser":
        break;			
	case "InicioParametrosUser":
        break;	
	case "listarcovid":  
        break;		 	
    default: 
	
	echo "DEFAUL";
	
	endswitch;
	} else {
     echo $html_acceso;		
	}
	}
	require('../footer.php');
	ob_end_flush();	
	?>