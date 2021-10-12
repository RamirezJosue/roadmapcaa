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
	$datatablesjs = 0;
	require('../head.php');
	if(isset($_GET['fechaselec'])): 
	$fecha_form = $_GET['fechaselec'];
	$fechars = $_GET['fechaselec'];	
	else:
	$fechars = $fechars; 
	$fecha_form = $fecha;
	endif;
if (isset($_GET['exa'])){ $exa = $_GET['exa']; } else { $exa = ""; }	

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
function form_examen_check($id_tema)
{
global $idcentro,$aid,$crudex,$tipocuestionario,$fecha,$tipopregunta,$letrasresp;	
$cuestiontb = $crudex -> sacarmonbredb('descripcion,tipo,estado','exa_temas','id="'.$id_tema.'"'); 
$notacheck = $crudex -> califica_check_flota($idcentro,$id_tema,$aid,$fecha); 
$grupo = $crudex -> sacarmonbredb('grupo,st','exa_detalle_user','centro="'.$idcentro.'" AND id_tema="'.$id_tema.'" AND user_registro="'.$aid.'" AND fecha="'.$fecha.'" GROUP BY grupo,st'); 
$tipo_tema=$cuestiontb[1];
$desc_tema=$cuestiontb[0]; 	
$estado_tema=$cuestiontb[2];   
	?>
	<?php
    if($notacheck[3]!=0) {	
	?>
	<div class="alert alert-warning" role="alert">
    <p class="h5"> El resultado es <?php echo  round($notacheck[3]*100,2); ?>%</p>
		<?php
    if($grupo[1]==0) {		
	?>
	<button type="button" class="btn btn-success" onclick="location.href='checklistT2?exa=enviarchecklist&grupo=<?php echo $grupo[0]; ?>&id=<?php echo $id_tema; ?>';" >Enviar resultado ?</button>
	<?php
    } else { $disablegrabar = 'disabled'; }	
	?>
	</div>	
	<?php
    } else { }
	?>
	<div class="row">
    <div class="col-sm-12">
    <h4 class="text-muted"><?php echo ucfirst($desc_tema); ?><?php echo ' :: '.$tipocuestionario[$tipo_tema].' - '.$aid; ?></h4>
	</div>
	</div>
	<form action="checklistT2?exa=iniciar&id=<?php echo $id_tema; ?>" method="POST">
    <div class="row">
    <div class="col-6">
	<button type="button" class="btn btn-info btn-block" onclick="location.href='checklistT2';" >Cancelar</button>
	</div>
	<div class="col-6">
	<button type="submit" class="btn btn-danger btn-block" <?php echo isset($disablegrabar)? $disablegrabar : ''; ?> >Grabar</button>
	</div>
	</div> 
    <br> 	
	<?php
          $gp=1;
		  $dbgrup=Db::conectar();
		  $sqlgrup ='SELECT id_grupo_preguntas,placa FROM exa_detalle_user WHERE id_tema=:id_tema AND fecha=:fecha AND user_registro=:user_registro GROUP BY id_grupo_preguntas';
          $selectgrup=$dbgrup->prepare($sqlgrup);
		  $selectgrup->bindValue('id_tema',$id_tema);
		  $selectgrup->bindValue('fecha',$fecha);
		  $selectgrup->bindValue('user_registro',$aid);
		  $selectgrup->execute();
          while ($rowgrupo=$selectgrup->fetch()) {	  
		  $id_grupo_preguntas = $rowgrupo['id_grupo_preguntas'];
		  $conductor = $rowgrupo['placa'];
		  $rowgrupodes = $crudex -> sacarmonbredb('descripcion','exa_grupo_preguntas','id="'.$id_grupo_preguntas.'"'); 
	?>
	<div class="card text-white bg-secondary mb-2">
	<h6 class="card-header">
	 <?php echo $gp.'.- '.ucfirst($rowgrupodes[0]).'-'.$conductor; ?>
	</h6>
	<div class="card-body">
	<?php
	      $pre=1;
		  $dbpre=Db::conectar();
		  $sqlpre ='SELECT id_preguntas,tipo_pregunta,puntos_pregunta FROM exa_detalle_user WHERE id_tema=:id_tema AND id_grupo_preguntas=:id_grupo_preguntas AND fecha=:fecha AND user_registro=:user_registro GROUP BY id_preguntas,tipo_pregunta,puntos_pregunta';
          $selectpre=$dbpre->prepare($sqlpre);
		  $selectpre->bindValue('id_tema',$id_tema);
		  $selectpre->bindValue('id_grupo_preguntas',$id_grupo_preguntas);
		  $selectpre->bindValue('fecha',$fecha);
		  $selectpre->bindValue('user_registro',$aid);
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
		 <div class="card text-dark border-light mb-2">
		 <h6 class="card-header">
		 <?php echo $pre.'.- '.ucfirst($desc_pregunta); if ($tipo_tema==2){ echo ' - Puntos : '.$puntos_pregunta.''; } $pre++;?>
		 </h6>
		 <div class="card-body">
		 <table class="table table-sm table-bordered">
		<tbody>
		<?php
		  $dbres=Db::conectar();
		  $sqlres ='SELECT id,desc_resp,orden_resp,doble_check,respuesta_user,resp_correcta,st FROM exa_detalle_user WHERE id_tema=:id_tema AND id_preguntas=:id_preguntas AND fecha=:fecha AND user_registro=:user_registro';
          $selectres=$dbres->prepare($sqlres);
		  $selectres->bindValue('id_tema',$id_tema);
		  $selectres->bindValue('id_preguntas',$id_preguntas);
		  $selectres->bindValue('fecha',$fecha);
		  $selectres->bindValue('user_registro',$aid);
		  $selectres->execute();
          while ($rowrespuesta=$selectres->fetch()) {
          $desc_resp = $rowrespuesta['desc_resp'];
          $id_resp_user = $rowrespuesta['id'];
		  if($rowrespuesta['doble_check']==1){ $doble_check='class="table-danger"'; }else{ $doble_check=''; };
		  $respuesta_user = $rowrespuesta['respuesta_user'];
		  $resp_correcta = $rowrespuesta['resp_correcta'];
		  if($rowrespuesta['st']==0){ $checked=''; $disabled=''; } else { $checked='checked'; $disabled='disabled'; } 
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
          <div class="mb-3"><?php echo  $letrasresp[$rowrespuesta['orden_resp']].'.- '.$desc_resp; ?></div>
<?php form_type_resp('range',$id_preguntas,$id_preguntas,'','custom-range','','nameresparray['.$id_resp_user.']',$respuesta_user,$desc_resp,$disabled,$checked,'','');?>
        </div>
		  
		 </td>
		 </tr>
		 <?php
        break;		
	endswitch;		   
		   }
	Db::desconectar();		   
		?>	
         </tbody>
		 </table>			
		 </div>
		 </div>
		  <?php 
		  }
		  Db::desconectar();
	?>
	</div>
	</div>
	<?php $gp++;			
		  }
		  Db::desconectar();
	?>
    <div class="row">
    <div class="col-6">
	<button type="button" class="btn btn-info btn-block" onclick="location.href='checklistT2';" >Cancelar</button>
	</div>
	<div class="col-6">
	<button type="submit" class="btn btn-danger btn-block" <?php echo isset($disablegrabar)? $disablegrabar : ''; ?> >Grabar</button>
	</div>
	</div> 
	</form>	
    <br>	
	<?php
}
function inicio_user_check_encuestas_examen($idcentro,$aid)
{ global $puestoaid,$Db;	
?>	
<table class="table table-sm">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Descripcion</th>
	  <th scope="col"></th>
      <th scope="col"></th>
    </tr>
  </thead>
  <tbody>
  <?php		
		$dbtema=Db::conectar();
		$sqltema ="SELECT * FROM exa_temas WHERE multicentro=1 AND id_area = 1 AND estado=1 ORDER BY Fecha";
        $selecttema=$dbtema->prepare($sqltema);
		//$selecttema->bindValue('centro',$idcentro);
		//$selecttema->bindValue('para',$puestoaid);
		$selecttema->execute();
		$n=1;
		while ($registema=$selecttema->fetch()) {		
		?>
		<tr>
		<th scope="row"><?php echo $n; ?> </th>
		<td><?php echo $registema['descripcion']; ?></td>
	<form  onsubmit="return confirm('Se iniciara con el conductor seleccionado, no se podra revertir');" method='POST' action='checklistT2?exa=crear_examen_check&amp;idtema=<?php echo $registema['id']; ?>'>		
		<td>
	<div class="input-group">
		<div class="input-group-prepend">
	    <button type="button" class="btn btn-danger btn-sm" onclick="location.href='userhc?nuevo=new&puesto=7';" >Agregar</button>
		</div>	
  	<select class="form-control form-control-sm" name="conductor" required>
	<option value=""><--Conductor--></option>
			<?php		
		    $db=Db::conectar();
		    $sql ="SELECT LOWER(CONCAT (`apellidos`,' ',`nombre`)) AS conductor FROM `usuarios` WHERE `puesto` = 7 AND centro = :centro ORDER BY apellidos ASC";
            $select=$db->prepare($sql);
		    $select->bindValue('centro',$idcentro);
		    $select->execute();
			while ($regis=$select->fetch()) {
			if ($regis['conductor'] == $_GET['conductor']){
			echo '<option  value="'.ucwords($regis['conductor']).'" selected >'.ucwords($regis['conductor']).'</option>';		
			}else {
			echo '<option  value="'.ucwords($regis['conductor']).'" >'.ucwords($regis['conductor']).'</option>';	
			}
			}
			Db::desconectar();
			?>
	</select>
    </div>
	
		</td>
		<td>
	<input type='hidden' name='id' value='2'>
	<button class="btn btn-danger btn-sm" >Iniciar</button>
	</form>
		</td>		
		</tr>	
		<?php 
		$n++;	
		}
		Db::desconectar();
	?>
  </tbody>
</table>
<?php
}
switch ($exa):
    case "crear_examen_check":
	if(isset($_GET['idtema'],$_POST['conductor'])) { $id_tema = $_GET['idtema'];} else { $id_tema = 0; }
    $CountUserExa = $crudex-> contardb('id','exa_detalle_user','id_tema = '.$id_tema.' AND user_registro = "'.$aid.'" AND centro = "'.$idcentro.'" AND fecha = "'.$fecha.'"'); 
	if($CountUserExa <=0 ){
	$cuestiontb = $crudex -> sacarmonbredb('descripcion,tipo,estado','exa_temas','id='.$id_tema.''); 
    $tipo_tema=$cuestiontb[1];
    $desc_tema=$cuestiontb[0]; 	
	$estado_tema=$cuestiontb[2];  
	if($id_tema != 0){
	  	  $dbres=Db::conectar();
		  $sqlres ='SELECT * FROM exa_respuesta WHERE id_tema=:id_tema';
          $selectres=$dbres->prepare($sqlres);
		  $selectres->bindValue('id_tema',$id_tema);
		  //$selectres->bindValue('centro',$idcentro);
		  $selectres->execute();
          while ($rowres=$selectres->fetch()) {
			  $rowpregunta = $crudex -> sacarmonbredb('puntos,doblecheck','exa_preguntas','id='.$rowres['id_pregunta'].''); 
			  $id_grupo_preguntas = $rowres['id_grupo_preguntas'];
			  $id_preguntas = $rowres['id_pregunta'];
			  $tipo_pregunta = $rowres['tipo_pregunta'];
			  $id_respueta = $rowres['id'];
			  $desc_resp = $rowres['respuestas'];
			  $puntos_pregunta = $rowpregunta[0];
			  $doble_check = $rowres['doblecheck'];  
			  $orden_resp = $rowres['orden']; 
			  $resp_correcta = $rowres['res_correcta']; 
			  $respuesta_user = '';
			  $fecha_fin_user = '0000-00-00 00:00:00';
			  $user_creador = $rowres['user'];
     $crudex -> insertar_respuestas_user($id_tema,$tipo_tema,$id_grupo_preguntas,$id_preguntas,$tipo_pregunta,$id_respueta,$desc_resp,$puntos_pregunta,$doble_check,$orden_resp,$resp_correcta,$respuesta_user,$user_creador,$idcentro,$fecha_hora,$fecha_fin_user,$aid,$_POST['conductor'],$aleatorio,$fecha);
		  }
		Db::desconectar();		  
	}
	header('Location: checklistT2?exa=iniciar&id='.$id_tema.'');
	exit();
	} else {
	header('Location: checklistT2?exa=iniciar&id='.$id_tema.'');
	exit();
	}
    break;
    case "enviarchecklist":
	
	if(isset($_GET['grupo'],$_GET['id'])) { $crudex ->grabar_estado_checklist($_GET['grupo'],$fecha); } else {} 
	
    header('Location: checklistT2?exa=iniciar&id='.$_GET['id'].'');
	
    break;	
	
    case "iniciar":
	if(isset($_POST['nameresparray'])){
    $nameresparray = $_POST['nameresparray'];		
	foreach($nameresparray as $id_respuesta => $valor)
	{	
    $crudex ->grabar_respuesta_user($valor,$fecha_hora,$id_respuesta,$idcentro,$aid);
	}
	}
	if(isset($_POST['nameresparrayradio'])){
    $nameresparray = $_POST['nameresparrayradio'];		
	foreach($nameresparray as $id_pregunta => $id_respuesta)  //registradatos del examen tipo radio button 
	{		
	$crudex ->grabar_respuesta_user('1',$fecha_hora,$id_respuesta,$idcentro,$aid);
	}
	}
	$id_tema = $_GET['id'];
	form_examen_check($id_tema);
	break;
    default:
	?>
	<div class="row border">
    <div class="col-sm-12 bg-danger">
	<div class="d-flex justify-content-center">
	<div class="p-2 bd-highlight"><div class="text-white text-md-center font-weight-bolder">Check list (automaticos)</div></div>
	<div class="p-2 bd-highlight"></div>
	</div>
	</div>
	</div>
	<?php 
	inicio_user_check_encuestas_examen($idcentro,$aid);
	endswitch;
	} else {
     echo $html_acceso;		
	}
	}
	require('../footer.php');
	ob_end_flush();	
	?>