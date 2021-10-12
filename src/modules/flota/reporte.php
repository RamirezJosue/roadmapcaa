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
	$datatablesjs = 1;
	$datatablesjsresponsive = 0;
	require('../head.php');
	if(isset($_GET['fechaselec'])): 
	$fecha_form = $_GET['fechaselec'];
	$fechars = $_GET['fechaselec'];	
	else:
	$fechars = $fechars; 
	$fecha_form = $fecha;
	endif;
	$puestoaid = 28;
	if (isset($_GET['exa'])){ $exa = $_GET['exa']; } else { $exa = ""; }
function flota_dashboard_cumplimiento($id_tema,$fecha)
{
global $fecha,$idcentro,$aid,$crudex;	
?>	
	<div class="row">
    <div class="col-sm-12 bg-light">
	<div class="p-2"><div class="text-muted text-left font-weight-bolder">Cumplimiento: <?php echo $fecha; ?></div></div>
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
      <th scope="col">Vehiculo</th>
	  <th scope="col">Hora</th>
	  <th scope="col">St</th>
	  <th scope="col">Resultado</th>
	  <th scope="col">Doble check</th>
	  <th scope="col">Seguimiento</th>
	  <th scope="col"></th>
	  <th scope="col"></th>
    </tr>
	</thead>
	<tbody>
  <?php		
		$db=Db::conectar();
		$sql ="
SELECT * FROM (
SELECT a.user_registro, TIME_FORMAT(a.fecha_ini_user, '%H:%i') as Hora,if((sum(a.Ok)+sum(a.Nok))=0,2,a.st) as estado,a.grupo,a.checksupervisor,
sum(a.Ok) as Ok,
sum(a.Nok) as Nok,
(sum(a.Ok)/(sum(a.Ok)+sum(a.Nok)))*100 as resultado
FROM (
SELECT id,user_registro,placa,desc_resp,fecha,id_preguntas,respuesta_user,tipo_pregunta,fecha_ini_user,st,grupo,checksupervisor,
IF(respuesta_user = '',0,if(tipo_pregunta='7',if(CAST(respuesta_user AS SIGNED) >= 3,1,0),if(respuesta_user = 'Si',1,0)))  as Ok, 
IF(respuesta_user = '',0,if(tipo_pregunta='7',if(CAST(respuesta_user AS SIGNED) < 3,1,0),if(respuesta_user = 'No',1,0))) as Nok    
FROM `exa_detalle_user` WHERE fecha=:fecha AND centro=:centro AND id_tema=:id_tema AND tipo_pregunta IN ('4','7')
	 ) AS a GROUP BY a.user_registro,a.fecha_ini_user,a.st,a.grupo,a.checksupervisor
	 ) as b ORDER BY b.user_registro ASC
		";
        $select=$db->prepare($sql);		
		$select->bindValue('centro',$idcentro);
		$select->bindValue('id_tema',$id_tema);
		$select->bindValue('fecha',$fecha);
		$select->execute();
		$n=1;
		while ($rows=$select->fetch()) {
        if($rows['estado'] == 1){ $ss='success'; $sss='Completo'; } else if($rows['estado'] == 0){ $ss='warning'; $sss='Sin enviar'; }else if($rows['estado'] == 2){ $ss='danger'; $sss='No ejecutado'; }
        if($rows['resultado'] < 100){ $cu='danger'; } else { $cu='success'; }		
		if(is_null($rows['checksupervisor'])){ $checks='danger'; $checksdisabled=''; } else { $checks='success'; $checksdisabled='disabled'; }
		?>
		<tr>
		<td><?php echo $n; ?></td>
		<td><?php echo $rows['user_registro']; ?></td>
        <td><?php echo $rows['Hora']; ?></td>	
	    <td><span class="badge badge-<?php echo $ss; ?>"><?php echo $sss; ?></span></td>
		<td class="table-<?php echo $cu; ?>" ><?php echo round($rows['resultado'],2); ?>%</td>	
		<td>
		<form  onsubmit="return confirm('<?php echo $aid; ?>, Confirmaras los resultados del vehiculo <?php echo $rows['user_registro']; ?>');" method='POST' action='reporte?exa=check_supervisor&amp;grupo=<?php echo $rows['grupo']; ?>&amp;vehiculo=<?php echo $rows['user_registro']; ?>'>		
	    <button class="btn btn-<?php echo $checks; ?> btn-sm" <?php echo $checksdisabled; ?> >Check</button>
	    </form>			
		</td>	 
        <td></td>	 		
        <td><button type="button" class="btn btn-primary btn-sm" onclick="location.href='reporte?exa=ver_check_list&id_tema=<?php echo $id_tema; ?>&user_registro=<?php echo $rows['user_registro']; ?>&fecha=<?php echo $fecha; ?>';" >Ver</button></td>
        <td><button type="button" class="btn btn-primary btn-sm" onclick="location.href='backlog?hc=backlogadd&id_tema=<?php echo $id_tema; ?>&user_registro=<?php echo $rows['user_registro']; ?>&fecha=<?php echo $fecha; ?>&grupo=<?php echo $rows['grupo']; ?>';" >BackLog</button></td>
		</tr>	
		<?php 
		$n++;	
		}
		Db::desconectar();
	?>
	</tbody>
	</table>
	</div>	
	</div>	
	</div>
<?php
}
function flota_respuestas($id_tema,$fecha)
{
global $fecha,$idcentro,$fechars,$aid,$crudex;	
?>	
	<div class="row">
    <div class="col-sm-12 bg-light">
	<div class="p-2"><div class="text-muted text-left font-weight-bolder">Preguntas : <?php echo $fecha; ?></div></div>
	</div>
	</div>
	<div class="row">
	<div class="col-12">
	<div class="table-responsive">
	 <table id="example_pregunta"  data-order='[[ 0, "asc" ]]'
          class="display compact cell-border">
	<thead>
    <tr>
	<th scope="col">#</th>
	<th scope="col">Bloque</th>
    <th scope="col">Pregunta</th>
	<th scope="col">Resultado</th>
    </tr>
	</thead>
	<tbody>
  <?php		
		$dbres=Db::conectar();
		$sqlres ="
SELECT * FROM (	 	
SELECT a.id_grupo_preguntas, (SELECT pregunta FROM `exa_preguntas` WHERE id=a.id_preguntas) as bloque   ,a.doble_check,a.desc_resp,a.tipo_pregunta,
sum(a.Ok) as Ok,
sum(a.Nok) as Nok,
(sum(a.Ok)/(sum(a.Ok)+sum(a.Nok)))*100 as resultado
FROM (
SELECT id,desc_resp,id_grupo_preguntas,id_preguntas,doble_check,respuesta_user,tipo_pregunta,
if(tipo_pregunta='7',if(CAST(respuesta_user AS SIGNED) >= 3,1,0),if(respuesta_user = 'Si',1,0)) as Ok,
if(tipo_pregunta='7',if(CAST(respuesta_user AS SIGNED) < 3,1,0),if(respuesta_user = 'No',1,0)) as Nok    
FROM `exa_detalle_user` WHERE fecha = :fecha AND centro=:centro AND id_tema=:id_tema AND respuesta_user <> '' AND tipo_pregunta IN ('4','7')
	 ) AS a GROUP BY a.id_grupo_preguntas,a.id_preguntas,a.doble_check,a.desc_resp,a.tipo_pregunta
	 ) AS b ORDER BY b.bloque ASC
		";
        $selectres=$dbres->prepare($sqlres);
        $selectres->bindValue('fecha',$fecha);		
		$selectres->bindValue('centro',$idcentro);
		$selectres->bindValue('id_tema',$id_tema);
		$selectres->execute();
		$n=1;
		while ($rowp=$selectres->fetch()) {
		if($rowp['doble_check']==1){ $doble_check='class="table-warning"'; }else{ $doble_check=''; };
		 if($rowp['resultado'] < 100){ $cu='danger'; } else { $cu='success';}
		?>
		<tr <?php echo $doble_check; ?> >
		<td><?php echo $n; ?></td>
		<td><?php echo $rowp['bloque']; ?></td>
		<td><?php echo $rowp['desc_resp']; ?></td>
        <td class="table-<?php echo $cu; ?>" ><?php echo round($rowp['resultado'],2); ?>%</td>					
		</tr>	
		<?php 
		$n++;	
		}
		Db::desconectar();
	?>
	</tbody>
	</table>
	</div>	
	</div>	
	</div>
<?php
}
function mondal_flota()
{
global $fecha,$idcentro,$fechars,$aid,$crudex,$db,$puestoaid;	
//$rowtm = $crudex -> sacarmonbredb("descripcion","exa_temas","id='".$id_tema."'");
?>	
   <!-- Modal Inicio-->
	<div class="modal fade" id="Modalflotatema" role="dialog" aria-labelledby="Modalflotatema" aria-hidden="true">
	<div class="modal-dialog" role="document">
	<!-- Modal content-->
	<div class="modal-content">
	<div class="modal-header">
	<h5 class="modal-title">Seleccionar</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
    </button>
	</div>
	<div class="modal-body">
	<form method="post" action="reporte">
	<div class="mb-3">
	<label for="selecttema1" class="form-label">Check list: </label>
    <select name="id_tema" class="form-control" id="selecttema1">
			<?php	
          if (isset($_POST['fechaselec'])){ $fechapostselec=$_POST['fechaselec']; } else { $fechapostselec = $fechars; }
		  $db=Db::conectar();
          $select=$db->prepare("SELECT c.id,c.descripcion FROM (SELECT * FROM exa_temas WHERE estado=1 AND para=:para) AS c WHERE c.multicentro=1 OR c.centro=:centro ORDER BY c.id,c.descripcion");
		  $select->bindValue('centro',$idcentro);
		  $select->bindValue('para',$puestoaid);		  
	      $select->execute();
          while ($regis=$select->fetch()) {
			    $id=$regis['id'];
			    $descripcion=$regis['descripcion'];				
			    if($id == $id) { 
				echo "<option selected='selected' value='".$id."'>".$descripcion."</option>";
				}
				else { 
				echo "<option value='".$id."'>".$descripcion."</option>"; }
			}
			Db::desconectar();
			?>
	 </select>
	</div>	
	<div class="mb-3">
	<label for="fechastema" class="form-label">Fecha: </label>
	<div class="input-group">
	<input  aria-label="First name" id="fechastema" class="form-control" value="<?php echo $fecha; ?>" placeholder="Fecha inicio" type="date" name='fecha'>
	<input id="consulta" name="consulta" type="hidden" value="checklistrep">
	</div>
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
}
function modal_exportar()
{
global $fecha,$idcentro,$fechars,$aid,$crudex,$db,$disableform,$puestoaid;	
?>	
<?php
}
function form_type_resp($type,$id,$for,$classdiv,$classinput,$classlabel,$name,$value,$txlabel,$disabled,$checked,$placeholder,$resp_verifi)
{
	if($type=='text') {
	?>
	<input class="<?php echo  $classinput; ?>" id="<?php echo  $id; ?>" type="<?php echo  $type; ?>" name="<?php echo  $name; ?>"  value="<?php  echo $value; ?>" placeholder="<?php  echo $placeholder; ?>" <?php echo  $disabled;?> required>
    <?php		
	} else if($type=='textarea') {
	?>
    <textarea class="<?php echo  $classinput; ?>" id="<?php echo  $id; ?>" rows="3" name="<?php echo  $name; ?>"  <?php echo  $disabled;?> required ><?php  echo $value; ?></textarea>	
    <?php		
	} else if($type=='range') {
	?>	
	<input  type="text"
          data-provide="slider"
          data-slider-ticks="[1, 2, 3, 4, 5]"
          data-slider-ticks-labels='["bajo", "","medio", "","lleno"]'
          data-slider-min="1"
          data-slider-max="5"
          data-slider-step="1"
		  data-slider-enabled="<?php if($disabled=='disabled'){ echo 'false'; }else{ echo 'true'; }?>"
          data-slider-value="<?php if($value==''){ echo 3; } else { echo $value; } ?>"
          data-slider-tooltip="hide"  name="<?php echo  $name; ?>"/>
	<?php		
	} else {	
     if ($resp_verifi == 'respnok'){
		 $resul = '<span class="badge badge-pill badge-danger">X</span>'; } 
		 else if ($resp_verifi == 'respok') { 
		 $resul = '<span class="badge badge-pill badge-success">✓</span>'; }	
		 else { $resul = ''; }	
	?>
	<div class="<?php echo  $classdiv; ?>">
			<input class="<?php echo  $classinput; ?>" id="<?php echo  $id; ?>" type="<?php echo  $type; ?>" name="<?php echo  $name; ?>"  value="<?php  echo $value; ?>" placeholder="<?php  echo $placeholder; ?>" <?php echo  $disabled.' '.$checked; ?> required>
			<label class="<?php echo  $classlabel; ?>" for="<?php echo  $for; ?>" > <?php  echo $txlabel; ?> <?php  echo $resul; ?> </label>
	</div>
    <?php		
	}	
}
function modal_deralle_respuesta($id_tema,$user_registro,$fecha)
{
global $idcentro,$aid,$crudex,$tipocuestionario,$tipopregunta,$letrasresp,$vehiculo;	
$cuestiontb = $crudex -> sacarmonbredb('descripcion,tipo,estado','exa_temas','id="'.$id_tema.'"'); 
$tipo_tema=$cuestiontb[1];
$desc_tema=$cuestiontb[0]; 	
$estado_tema=$cuestiontb[2]; 
	?>	
	<div>
	<h6 class="modal-title"><?php echo ucfirst($desc_tema); ?><?php echo ' :: '.$user_registro; ?></h6>
	</div>
	<div>
	<?php
          $gp=1;
		  $dbgrup=Db::conectar();
		  $sqlgrup ='SELECT id_grupo_preguntas,placa FROM exa_detalle_user WHERE id_tema=:id_tema AND fecha=:fecha AND user_registro=:user_registro GROUP BY id_grupo_preguntas,placa';
          $selectgrup=$dbgrup->prepare($sqlgrup);
		  $selectgrup->bindValue('id_tema',$id_tema);
		  $selectgrup->bindValue('fecha',$fecha);
		  $selectgrup->bindValue('user_registro',$user_registro);
		  $selectgrup->execute();
          while ($rowgrupo=$selectgrup->fetch()) {	  
		  $id_grupo_preguntas = $rowgrupo['id_grupo_preguntas'];
		  $condutor = $rowgrupo['placa'];
		  $rowgrupodes = $crudex -> sacarmonbredb('descripcion','exa_grupo_preguntas','id="'.$id_grupo_preguntas.'"'); 
	?>	
	<div class="row bg-dark">
    <div class="col-sm-12">
	<div class="p-1"><div class="text-white text-left font-weight-bolder"><?php echo $gp.'.- '.ucfirst($rowgrupodes[0]).' - '.$condutor; ?></div></div>
	</div>
	<div class="col-sm-12 bg-light">
	<?php
	      $pre=1;
		  $dbpre=Db::conectar();
		  $sqlpre ='SELECT id_preguntas,tipo_pregunta,puntos_pregunta FROM exa_detalle_user WHERE id_tema=:id_tema AND id_grupo_preguntas=:id_grupo_preguntas AND fecha=:fecha AND user_registro=:user_registro GROUP BY id_preguntas,tipo_pregunta,puntos_pregunta';
          $selectpre=$dbpre->prepare($sqlpre);
		  $selectpre->bindValue('id_tema',$id_tema);
		  $selectpre->bindValue('id_grupo_preguntas',$id_grupo_preguntas);
		  $selectpre->bindValue('fecha',$fecha);
		  $selectpre->bindValue('user_registro',$user_registro);
		  $selectpre->execute();
          while ($rowpregunta=$selectpre->fetch()) {
		  $id_preguntas = $rowpregunta['id_preguntas'];	
          $tipo_pregunta = $rowpregunta['tipo_pregunta'];
          $puntos_pregunta = $rowpregunta['puntos_pregunta'];		  
		  $rowgrupodes = $crudex -> sacarmonbredb('pregunta','exa_preguntas','id="'.$id_preguntas.'"');
		  $desc_pregunta = $rowgrupodes[0];
		  $CountRespUser = $crudex-> contardb('respuesta_user','exa_detalle_user','id_preguntas = '.$id_preguntas.' AND user_registro = "'.$aid.'" AND centro = "'.$idcentro.'" AND fecha = "'.$fecha.'" AND respuesta_user<>"" '); 
		  if($CountRespUser <= 0){ $disabledpre=''; }else{ $disabledpre='disabled'; }
		  ?>
		<div class="row bg-danger">
		<div class="col-sm-12">
		<div class="p-1"><div class="text-white text-left font-weight-bolder"><?php echo $pre.'.- '.ucfirst($desc_pregunta); if ($tipo_tema==2){ echo ' - Puntos : '.$puntos_pregunta.''; } $pre++;?></div>
		</div>
		</div>
		<div class="col-sm-12 bg-white"> 
		<br>
		<table class="table table-sm table-bordered">
		<tbody>
		<?php
		  $dbres=Db::conectar();
		  $sqlres ='SELECT id,desc_resp,orden_resp,doble_check,respuesta_user,resp_correcta FROM exa_detalle_user WHERE id_tema=:id_tema AND id_preguntas=:id_preguntas AND fecha=:fecha AND user_registro=:user_registro';
          $selectres=$dbres->prepare($sqlres);
		  $selectres->bindValue('id_tema',$id_tema);
		  $selectres->bindValue('id_preguntas',$id_preguntas);
		  $selectres->bindValue('fecha',$fecha);
		  $selectres->bindValue('user_registro',$user_registro);
		  $selectres->execute();
          while ($rowrespuesta=$selectres->fetch()) {
		   if($rowrespuesta['doble_check']==1){ $doble_check='class="table-danger"'; }else{ $doble_check=''; };	  
          $desc_resp = $rowrespuesta['desc_resp'];
          $id_resp_user = $rowrespuesta['id'];
		  $respuesta_user = $rowrespuesta['respuesta_user'];
		  $resp_correcta = $rowrespuesta['resp_correcta'];
		  if($respuesta_user == null){ $checked=''; $disabled=''; } else { $checked='checked'; $disabled='disabled'; }
		  
		  if($tipo_tema==2){ // si es examen 
          if($resp_correcta == 1) { 
          $resp_verifi='respok';
		  } else { if($respuesta_user <= 0){ $resp_verifi = ''; } else { $resp_verifi='respnok'; } }
		  } else { $resp_verifi=''; } 
		  
    switch ($tipo_pregunta):
    case 1: //1=>'Múltiple radio button'	
		?>
		 <tr <?php echo $doble_check; ?> > 
		 <td style="width: 12%;" ><?php echo  $letrasresp[$rowrespuesta['orden_resp']].'.-'; ?></td>
		 <td style="width: 88%;" >
		 <?php 
form_type_resp('radio',$id_preguntas,$id_preguntas,'form-check','form-check-input','form-check-label','nameresparrayradio['.$id_preguntas.']',$id_resp_user,$desc_resp,$disabledpre,$checked,'',$resp_verifi); 
		 ?> 
		 </td>
		 </tr>
		 <?php
         break;
    case 2: //2=>'Casilla de verificación'
		?>
		 <tr <?php echo $doble_check; ?> > 
		 <td style="width: 12%;" ><?php echo  $letrasresp[$rowrespuesta['orden_resp']].'.-'; ?></td>
		 <td style="width: 88%;" >
		 <?php 
form_type_resp('checkbox',$id_preguntas,$id_preguntas,'form-check','form-check-input','form-check-label','nameresparray['.$id_resp_user.']',$desc_resp,$desc_resp,$disabledpre,$checked,'',''); 
		 ?>
		 </td>
		 </tr>
		 <?php
        break;
    case 3: //3=>'Check Si-No'
		?>
		<tr <?php echo $doble_check; ?> > 
		 <td style="width: 12%;" ><?php echo  $letrasresp[$rowrespuesta['orden_resp']].'.-'; ?></td>
		 <td style="width: 88%;" >
		 <?php 
form_type_resp('radio',$id_preguntas,$id_preguntas,'form-check','form-check-input','form-check-label','nameresparrayradio['.$id_preguntas.']',$id_resp_user,$desc_resp,$disabledpre,$checked,'',''); 
		 ?>
		 </td>
		 </tr>
		 <?php
        break;
    case 4: // 4=>'Múltiple Si-No'
		?>
		 <tr <?php echo $doble_check; ?> > 
		 <td style="width: 50%;" ><?php echo  $letrasresp[$rowrespuesta['orden_resp']].'.- '.$desc_resp; ?></td>
		 <td style="width: 50%;" >
		 <?php 
		  if($respuesta_user=='Si'){ $checkedsi='checked'; } else { $checkedsi=''; }
		  if($respuesta_user=='No'){ $checkedno='checked'; } else { $checkedno=''; }		  
form_type_resp('radio',$id_preguntas,$id_preguntas,'form-check form-check-inline','form-check-input','form-check-label','nameresparray['.$id_resp_user.']','Si','Si',$disabled,$checkedsi,'',''); 
form_type_resp('radio',$id_preguntas,$id_preguntas,'form-check form-check-inline','form-check-input','form-check-label','nameresparray['.$id_resp_user.']','No','No',$disabled,$checkedno,'',''); 
		 ?>
		 </td>
		 </tr>
		 <?php
        break;
    case 5: //5=>'Texto'
		?>
		 <tr <?php echo $doble_check; ?> > 
		 <td style="width: 50%;" ><?php echo  $letrasresp[$rowrespuesta['orden_resp']].'.- '.$desc_resp; ?></td>
		 <td style="width: 50%;" >
		 <?php 
form_type_resp('text',$id_preguntas,$id_preguntas,'','form-control','','nameresparray['.$id_resp_user.']',$respuesta_user,'',$disabled,$checked,$desc_resp,''); 
		 ?>
		 </td>
		 </tr>
		 <?php
        break;
    case 6:  //6=>'Textarea'
		?>
		 <tr <?php echo $doble_check; ?> > 
		 <td style="width: 12%;" ><?php echo  $letrasresp[$rowrespuesta['orden_resp']].'.-'; ?></td>
		 <td style="width: 88%;" >
		 <?php 
form_type_resp('textarea',$id_preguntas,$id_preguntas,'','form-control','','nameresparray['.$id_resp_user.']',$respuesta_user,$desc_resp,$disabled,$checked,'',''); 
		 ?>
		 </td>
		 </tr>
		 <?php
        break;
    case 7: // 7=>'Múltiple RangeInputs'
		?>
		 <tr <?php echo $doble_check; ?> >   
		 <td colspan="2" >
		<div class="card card-body mb-1">
          <div class="mb-3"> <?php echo  $letrasresp[$rowrespuesta['orden_resp']].'.- '.$desc_resp; ?></div>
	<?php form_type_resp('range',$id_preguntas,$id_preguntas,'','custom-range','','nameresparray['.$id_resp_user.']',$respuesta_user,$desc_resp,$disabled,$checked,'',''); ?>
        </div>
		 </td>
		 </tr>
		 <?php
        break;		
	endswitch;		   
		   }   
		?>	
         </tbody>
		 </table>			
		 </div>
		 </div>
		  <?php 
		  }
	?>
	</div>
	</div>
	<?php $gp++;			
		  }
		  Db::desconectar();
	?>
	</div>
	<?php	
}
function flota_respuestas_bk($id_tema,$fecha)
{
global $fecha,$idcentro,$fechars,$aid,$crudex;
?>	<div class="row">
    <div class="col-sm-12 bg-light">
	<div class="p-2"><div class="text-muted text-left font-weight-bolder">Resultado : <?php echo $fecha;?></div></div>
	</div>
	</div>
	<div class="row">
	<div class="col-12">
	<div class="table-responsive">
	<table class="table table-sm">
	<thead>
    <tr>
    <th scope="col">#</th>
	<th scope="col">Usuario</th>
	<th scope="col"></th>
	<th scope="col"></th>
	<th scope="col"></th>
    </tr>
	</thead>
	<tbody>
  <?php		
		$dbres=Db::conectar();
		$sqlres ="
SELECT * FROM (
SELECT a.user_registro,
sum(a.Ok) as Ok,
sum(a.Nok) as Nok
FROM (
SELECT id,user_registro,placa,desc_resp,fecha,id_preguntas,respuesta_user,tipo_pregunta,
if(tipo_pregunta='7',if(CAST(respuesta_user AS SIGNED) >= 3,1,0),if(respuesta_user = 'Si',1,0)) as Ok,
if(tipo_pregunta='7',if(CAST(respuesta_user AS SIGNED) < 3,1,0),if(respuesta_user = 'No',1,0)) as Nok    
FROM `exa_detalle_user` WHERE fecha = '".$fecha."' AND centro=:centro AND id_tema=:id_tema AND respuesta_user <> '' AND tipo_pregunta IN ('4','7')
	 ) AS a GROUP BY a.user_registro
	 ) as b ORDER BY b.Nok DESC
		";
        $selectres=$dbres->prepare($sqlres);
		$selectres->bindValue('centro',$idcentro);
		$selectres->bindValue('id_tema',$id_tema);
		$selectres->execute();
		$n=1;
		while ($rowp=$selectres->fetch()) {
		$total = $rowp['Ok']+$rowp['Nok'];
		$NroOk = round(($rowp['Ok']/$total)*100);
		$NroNok = round(($rowp['Nok']/$total)*100);
		?>
		<tr>
		<td><?php echo $n; ?></td>
		<td><?php echo $rowp['user_registro']; ?></td>	
		<td>
	<div class="progress">
	<div class="progress-bar bg-success" role="progressbar" style="width: <?php echo $NroOk; ?>%" aria-valuenow="<?php echo $NroOk; ?>" aria-valuemin="0" aria-valuemax="100"><?php echo $NroOk; ?>%</div>
	<div class="progress-bar bg-danger" role="progressbar" style="width: <?php echo $NroNok; ?>%" aria-valuenow="<?php echo $NroNok; ?>" aria-valuemin="0" aria-valuemax="100"><?php echo $NroNok; ?>%</div>
	</div>	
		</td>	
		<td><button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#id<?php echo $rowp['user_registro']; ?>">Ver</button></td>
		<td><?php    modal_deralle_respuesta($id_tema,$rowp['user_registro'],$fecha);	?></td>
		</tr>
		<?php	
		$n++;	
		}
		Db::desconectar();
	?>
	</tbody>
	</table>
	</div>	
	</div>	
	</div>
<?php
}
switch ($exa):
    case "ver_check_list":
	if(isset($_GET['id_tema'],$_GET['user_registro'],$_GET['fecha'])){
	modal_deralle_respuesta($_GET['id_tema'],$_GET['user_registro'],$_GET['fecha']);
	}
	break;
	case "check_supervisor":
	if(isset($_GET['grupo'],$_GET['vehiculo'])){
	$grupo=$_GET['grupo'];
	$vehiculo=$_GET['vehiculo']; 
	$crud->check_supervisor($aid,$grupo,$vehiculo,$idcentro);
	header('Location: reporte');	
    }
	break;
    default:
	mondal_flota();
	modal_exportar();
	if(isset($_POST['id_tema'],$_POST['fecha'])) { 
	$id_tema = $_POST['id_tema']; 
	$fecha = $_POST['fecha'];
	} else { 
	$id_tema='2147483647';  // Inspección diaria de unidades (salida) default
	$fecha = $fecha;
	} 
	$rowtm = $crudex -> sacarmonbredb("descripcion","exa_temas","id='".$id_tema."'");
	?>
	<div class="row">
    <div class="col-sm-12 bg-danger">
	<div class="d-flex justify-content-center">
	<div class="p-2"><div class="text-white text-center font-weight-bolder"><?php echo $rowtm[0]; ?></div></div>
	<div class="btn-group" role="group" aria-label="Button group with nested dropdown">
	<div class="btn-group" role="group">
    <button id="btnGroupDrop1" type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      Opciones
    </button>
    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
     <button type="button" class="btn btn-light" data-toggle="modal" data-target="#Modalflotatema">Seleccionar Check List </button> 
    </div>
	</div>
	</div>
	</div>
	</div>
	</div>	
	<div class="row border">
    <div class="col-sm-12"><?php echo flota_dashboard_cumplimiento($id_tema,$fecha); ?></div>
	</div>
	<div class="row border">
    <div class="col-sm-12"><?php echo flota_respuestas($id_tema,$fecha); ?></div>
	</div>
	<?php
	endswitch;
	} else {
     echo $html_acceso;		
	}
	}
	require('../footer.php');
	ob_end_flush();	
	?>