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
function form_examen_check($id_tema,$user_registro,$fecha_registro)
{
global $idcentro,$crudex,$tipocuestionario,$tipopregunta,$letrasresp;
$aid = $user_registro;
$fecha = $fecha_registro;	
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
	<button type="button" class="btn btn-primary btn-sm" onclick="location.href='cklsflota?exa=enviarchecklist&grupo=<?php echo $grupo[0]; ?>&id=<?php echo $id_tema; ?>&user_registro=<?php echo $aid; ?>&fecha_registro=<?php echo $fecha; ?>';" >Enviar resultado ?</button>
    <button type="button" class="btn btn-success btn-sm" onclick="location.href='cklsflota?id_tema=<?php echo $id_tema; ?>&fecha_value=<?php echo $fecha; ?>&fecha_fin=<?php echo $fecha; ?>';" >Ok</button>
	<?php
    } else {
    //header('Refresh: 3; URL=http://www.bk77.co/cklsflota?id_tema='.$id_tema.'&fecha_value='.$fecha.'&fecha_fin='.$fecha.'');		
	$disablegrabar = 'disabled'; 
	}	
	?>
	</div>	
	<?php
    } else { }
	?>
	<div class="row">
    <div class="col-sm-12">
    <h4>
    <small class="text-muted"><?php echo ucfirst($desc_tema); ?><?php echo ' :: '.$tipocuestionario[$tipo_tema].' - '.$aid.' | '.$fecha; ?></small>
    </h4>
	</div>
	</div>
	<form action="cklsflota?exa=iniciar&id=<?php echo $id_tema; ?>&user_registro=<?php echo $aid; ?>&fecha_registro=<?php echo $fecha; ?>" method="POST">
    <div class="row">
    <div class="col-12">
	<button type="button" class="btn btn-primary btn-sm" onclick="location.href='<?php echo 'cklsflota?id_tema='.$id_tema.'&fecha_value='.$fecha.'&fecha_fin='.$fecha.'';?>';" >Cancelar</button>
	<button type="submit" class="btn btn-danger btn-sm" <?php echo isset($disablegrabar)? $disablegrabar : ''; ?> >Grabar</button>
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
    <div class="col-12">
	<button type="button" class="btn btn-primary btn-sm" onclick="location.href='cklsflota';" >Cancelar</button>
	<button type="submit" class="btn btn-danger btn-sm" <?php echo isset($disablegrabar)? $disablegrabar : ''; ?> >Grabar</button>
	</div>
	</div> 
	</form>	
    <br>	
	<?php
}
function modificar_resultados($id_tema,$grupo,$user_registro,$fecha_registro)
{
global $idcentro,$crudex,$tipocuestionario,$tipopregunta,$letrasresp;
$aid = $user_registro;
$fecha = $fecha_registro;	
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
	<button type="button" class="btn btn-primary btn-sm" onclick="location.href='cklsflota?exa=enviarchecklist&grupo=<?php echo $grupo[0]; ?>&id=<?php echo $id_tema; ?>&user_registro=<?php echo $aid; ?>&fecha_registro=<?php echo $fecha; ?>';" >Enviar resultado ?</button>
	<button type="button" class="btn btn-success btn-sm" onclick="location.href='cklsflota?id_tema=<?php echo $id_tema; ?>&fecha_value=<?php echo $fecha; ?>&fecha_fin=<?php echo $fecha; ?>';" >Ok</button>	
	<?php
    } else {
	echo '<button type="button" class="btn btn-primary btn-sm" disabled>Resultado enviado</button>';
	?><button type="button" class="btn btn-success btn-sm" onclick="location.href='cklsflota?id_tema=<?php echo $id_tema; ?>&fecha_value=<?php echo $fecha; ?>&fecha_fin=<?php echo $fecha; ?>';" >Ok</button><?php
	$disablegrabar = ''; 
	}	
	?>
	</div>	
	<?php
    } else { }
	?>
	<div class="row">
    <div class="col-sm-12">
    <h4>
    <small class="text-muted"><?php echo ucfirst($desc_tema); ?><?php $aid.' | '.$fecha; ?></small>
    </h4>
	</div>
	</div>
	<form action="cklsflota?exa=resultwatchflota&grupo=<?php echo $grupo[0]; ?>&id_tema=<?php echo $id_tema; ?>&user_registro=<?php echo $aid; ?>&fecha_registro=<?php echo $fecha; ?>" method="POST">
    <div class="row">
    <div class="col-12">
	<button type="button" class="btn btn-primary btn-sm" onclick="location.href='<?php echo 'cklsflota?id_tema='.$id_tema.'&fecha_value='.$fecha.'&fecha_fin='.$fecha.'';?>';" >Cancelar</button>
	<button type="submit" class="btn btn-danger btn-sm" <?php echo isset($disablegrabar)? $disablegrabar : ''; ?> >Grabar</button>
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
          while ($rowgrupo=$selectgrup->fetch()){	  
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
		  if($CountRespUser <= 0){ $disabledpre=''; }else{ $disabledpre=''; }
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
		  if($rowrespuesta['st']==0){ $checked=''; $disabled=''; } else { $checked='checked'; $disabled=''; } 
		  
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
    <div class="col-12">
	<button type="button" class="btn btn-primary btn-sm" onclick="location.href='cklsflota';" >Cancelar</button>
	<button type="submit" class="btn btn-danger btn-sm" <?php echo isset($disablegrabar)? $disablegrabar : ''; ?> >Grabar</button>
	</div>
	</div> 
	</form>	
    <br>	
	<?php
}
function inicio_user_check_encuestas_examen($idcentro,$aid,$fecha_value)
{ global $puestoaid,$Db;	
?>	
<div class="table-responsive">
<table class="table table-sm">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Descripcion</th>
	  <th scope="col">Conductor</th>
      <th scope="col">Vehiculo</th>
	  <th scope="col">Fecha</th>
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
		<td><a href="cklsflota?id_tema=<?php echo $registema['id']; ?>" class="btn btn-danger btn-sm"><?php echo $registema['descripcion']; ?></a></td>
		
	<form  onsubmit="return confirm('Se iniciara con el conductor seleccionado, no se podra revertir');" method='POST' action='cklsflota?exa=crear_examen_check&amp;idtema=<?php echo $registema['id']; ?>'>		
		<td>
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
		</td>
			<td>
  	<select class="form-control form-control-sm" name="placa" required>
	<option value=""><--Vehiculo--></option>
			<?php		
		    $db=Db::conectar();
		    $sqlplc ="SELECT dni AS placa FROM `usuarios` WHERE centro=:centro AND puesto=28 GROUP BY dni";
            $selectplc=$db->prepare($sqlplc);
		    $selectplc->bindValue('centro',$idcentro);
		    $selectplc->execute();
			while ($regisplc=$selectplc->fetch()) {
			if ($regisplc['conductor'] == 'x'){
			echo '<option  value="'.$regisplc['placa'].'" selected >'.$regisplc['placa'].'</option>';		
			}else {
			echo '<option  value="'.$regisplc['placa'].'" >'.$regisplc['placa'].'</option>';	
			}
			}
			Db::desconectar();
			?>
	</select>		
		  </td>
			<td>
    <input type='date' name='fecha_registro' value='<?php echo $fecha_value; ?>' > 		
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
</div>
<?php
}
function resultcklsflota($id,$fecha_value,$fecha_fin)
{ global $puestoaid,$Db,$cuestionarioarea,$idcentro;	
?>	
	<div class="row">
    <div class="col-sm-12">
	<div class="p-2"><div class="text-muted text-md-center font-weight-bolder">Resultados Check list flota</div></div>
	</div>
	</div>
	<form method="GET">
	<input type="hidden" name="id_tema" value="<?php echo $id; ?>">
	<input type="date" id="date" name="fecha_value" value='<?php echo $fecha_value; ?>'>
	<input type="date" id="date" name="fecha_fin" value='<?php echo $fecha_fin; ?>'>
	<button class="btn btn-danger btn-sm" >Filtar</button>
	</form>
	<div class="table-responsive">
	 <table id="resultckldtflota"  data-order='[[ 0, "asc" ]]' data-page-length='30'
     class="display compact cell-border">
	<thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Fecha</th>
	  <th scope="col">CheckList</th>
	  <th scope="col">Area</th>
      <th scope="col">Conductor</th>
	  <th scope="col">Puntaje</th>
	  <th scope="col"></th>
	  <th></th>
    </tr>
  </thead>
  <tbody>
  <?php	
  	$sqlcklsflota ="
	SELECT (SELECT descripcion FROM exa_temas WHERE id=b.id_tema) as tema, b.id_tema,b.user_registro,b.placa,b.fecha,b.grupo,b.Ok,b.Nok, round(((b.Ok/(b.Nok+b.Ok)))*100,2) AS resultado FROM (	 	
SELECT 
a.id_tema,
a.user_registro,
a.placa,
a.fecha,
a.grupo,
sum(a.Ok) as Ok,
sum(a.Nok) as Nok
FROM (
SELECT 
id,
id_tema,
placa,
fecha,
user_registro,
desc_resp,
id_grupo_preguntas,
id_preguntas,
doble_check,
respuesta_user,
tipo_pregunta,
grupo,
if(tipo_pregunta='7',if(CAST(respuesta_user AS SIGNED) >= 3,1,0),if(respuesta_user = 'Si',1,0)) as Ok,
if(tipo_pregunta='7',if(CAST(respuesta_user AS SIGNED) < 3,1,0),if(respuesta_user = 'No',1,0)) as Nok    
FROM `exa_detalle_user` 
WHERE fecha >= '$fecha_value' AND fecha <= '$fecha_fin' 
AND centro=:centro
AND id_tema=:id_tema
AND tipo_pregunta IN ('4','7')
	 ) AS a GROUP BY a.id_tema,a.user_registro,a.placa,a.fecha,a.grupo
	 ) AS b ORDER BY b.fecha ASC
";
		$dbtema=Db::conectar();
        $selecttema=$dbtema->prepare($sqlcklsflota);
		$selecttema->bindValue('centro',$idcentro);
		$selecttema->bindValue('id_tema',$id);
		$selecttema->execute();
		$n=1;
		while ($registema=$selecttema->fetch()) {
$actioneliminar = "cklsflota?exa=elimiarckslflota&amp;id_tema=".$registema['id_tema']."&amp;grupo=".$registema['grupo']."&amp;user_registro=".$registema['user_registro']."&amp;fecha_value=$fecha_value&amp;fecha_fin=$fecha_fin";	
		?>
		<tr>
		<td scope="row"><?php echo $n; ?> </td>
		<td><?php echo $registema['fecha']; ?></td>
		<td><?php echo $registema['tema']; ?></td>
        <td><?php echo $registema['user_registro']; ?></td>
		<td><?php echo $registema['placa']; ?></td>
		<td><?php echo $registema['resultado']; ?></td>
		<td>  
	<button  type="button" class="btn btn-danger btn-sm" onclick="location.href='cklsflota?exa=resultwatchflota&amp;grupo=<?php echo $registema['grupo']; ?>&amp;id_tema=<?php echo $registema['id_tema']; ?>&amp;user_registro=<?php echo $registema['user_registro']; ?>&amp;fecha_registro=<?php echo $registema['fecha']; ?>';" >Modificar</button>
		</td>
		<td>
	<form  onsubmit="return confirm('Estas seguro de eliminar el registro <?php echo $registema['grupo']; ?> resultado <?php echo $registema['resultado']; ?>');" method="POST"  action="<?php echo $actioneliminar; ?>">
	<input type='hidden' name='id' value='2'>
	<button type="submit" class="btn btn-danger btn-sm" >Eliminar</button>
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
	</div>
<?php
}
function eliminar_chlsflota($grupo,$user_registro)
{ global $aid,$idcentro;
			$db=DB::conectar();
			$insert=$db->prepare('
		DELETE FROM `exa_detalle_user` WHERE grupo=:grupo AND user_registro=:user_registro AND centro=:centro
			');
			$insert->bindValue('grupo',$grupo);
			$insert->bindValue('user_registro',$user_registro);
			$insert->bindValue('centro',$idcentro);
			$insert->execute();
			Db::desconectar();
}
switch ($exa):
    case "crear_examen_check":
	if(isset($_GET['idtema'],$_POST['conductor'],$_POST['placa'],$_POST['fecha_registro'])) { 
	$id_tema = $_GET['idtema'];
	$conductor = $_POST['conductor'];
	$placa = $_POST['placa'];
	$fecha_registro = $_POST['fecha_registro'];
	} else { 
	$id_tema = 0; 
	}
    $CountUserExa = $crudex-> contardb('id','exa_detalle_user','id_tema = '.$id_tema.' AND user_registro = "'.$placa.'" AND centro = "'.$idcentro.'" AND fecha = "'.$fecha_registro.'"'); 
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
     $crudex -> insertar_respuestas_user($id_tema,$tipo_tema,$id_grupo_preguntas,$id_preguntas,$tipo_pregunta,$id_respueta,$desc_resp,$puntos_pregunta,$doble_check,$orden_resp,$resp_correcta,$respuesta_user,$user_creador,$idcentro,$fecha_hora,$fecha_fin_user,$placa,$conductor,$aleatorio,$fecha_registro);
		  }	
			Db::desconectar();		  
	}
	
	header('Location: cklsflota?exa=iniciar&id='.$id_tema.'&user_registro='.$placa.'&fecha_registro='.$fecha_registro.'');
	exit();
	} else {
	header('Location: cklsflota?exa=iniciar&id='.$id_tema.'&user_registro='.$placa.'&fecha_registro='.$fecha_registro.'');
	exit();
	}
    break;
    case "enviarchecklist":
	if(isset($_GET['grupo'],$_GET['id'])) { $crudex ->grabar_estado_checklist($_GET['grupo'],$_GET['fecha_registro']); } else {} 
    header('Location: cklsflota?exa=iniciar&id='.$_GET['id'].'&user_registro='.$_GET['user_registro'].'&fecha_registro='.$_GET['fecha_registro'].'');
    break;	
    case "elimiarckslflota":
	if (isset($_GET['grupo'],$_GET['user_registro']) && (isset($aid))) {  
	eliminar_chlsflota($_GET['grupo'],$_GET['user_registro']);
	header('Refresh: 3; URL=http://www.bk77.co/cklsflota?id_tema='.$_GET['id_tema'].'&fecha_value='.$_GET['fecha_value'].'&fecha_fin='.$_GET['fecha_fin'].'');		 
	echo "eliminado";
	} else {
	header('Refresh: 3; URL=http://www.bk77.co/cklsflota?id_tema='.$_GET['id_tema'].'&fecha_value='.$_GET['fecha_value'].'&fecha_fin='.$_GET['fecha_fin'].'');	
	echo "ocurrio un error";
	}
    break;
    case "resultwatchflota":
	$id_tema = $_GET['id_tema'];
	$user_registro = $_GET['user_registro'];
	$fecha_registro = $_GET['fecha_registro'];
	if(isset($_POST['nameresparray'])){
    $nameresparray = $_POST['nameresparray'];		
	foreach($nameresparray as $id_respuesta => $valor)
	{	
    $crudex ->grabar_respuesta_user($valor,$fecha_hora,$id_respuesta,$idcentro,$user_registro);
	}
	}
	if(isset($_POST['nameresparrayradio'])){
    $nameresparray = $_POST['nameresparrayradio'];		
	foreach($nameresparray as $id_pregunta => $id_respuesta)  //registradatos del examen tipo radio button 
	{		
	$crudex ->grabar_respuesta_user('1',$fecha_hora,$id_respuesta,$idcentro,$user_registro);
	}
	}
    if(isset($_GET['id_tema'],$_GET['grupo'])){
	modificar_resultados($_GET['id_tema'],$_GET['grupo'],$_GET['user_registro'],$_GET['fecha_registro']);
	} else {
	header('Location: cklsflota');	
	}
	break;
    case "iniciar":
	$id_tema = $_GET['id'];
	$user_registro = $_GET['user_registro'];
	$fecha_registro = $_GET['fecha_registro'];
	if(isset($_POST['nameresparray'])){
    $nameresparray = $_POST['nameresparray'];		
	foreach($nameresparray as $id_respuesta => $valor)
	{	
    $crudex ->grabar_respuesta_user($valor,$fecha_hora,$id_respuesta,$idcentro,$user_registro);
	}
	}
	if(isset($_POST['nameresparrayradio'])){
    $nameresparray = $_POST['nameresparrayradio'];		
	foreach($nameresparray as $id_pregunta => $id_respuesta)  //registradatos del examen tipo radio button 
	{		
	$crudex ->grabar_respuesta_user('1',$fecha_hora,$id_respuesta,$idcentro,$user_registro);
	}
	}
	form_examen_check($id_tema,$user_registro,$fecha_registro);
	break;
    default:
	?>
	<div class="row border">
    <div class="col-sm-12 bg-danger">
	<div class="d-flex justify-content-center">
	<div class="p-2 bd-highlight">
	<div class="text-white text-md-center font-weight-bolder">Admin check list</div>
	<small class="text-white" >Aqui se puede borrar, modificar y enviar , los check list de T2</small>
	</div>
	<div class="p-2 bd-highlight"></div>
	</div>
	</div>
	</div>
	<?php 
	isset($_GET['fecha_value'])? $fecha_value=$_GET['fecha_value'] : $fecha_value=$fecha ;
	isset($_GET['fecha_fin'])? $fecha_fin=$_GET['fecha_fin'] : $fecha_fin=$fecha ;
	isset($_GET['id_tema'])? $id_tema=$_GET['id_tema'] : $id_tema=2147483647;
	inicio_user_check_encuestas_examen($idcentro,$aid,$fecha_value);
	resultcklsflota($id_tema,$fecha_value,$fecha_fin);
endswitch;
ob_end_flush();
		} else { echo "no tienes permiso para acceder a esta seccion ".$accesos.'-'.$aid.'<br><a  href="index">Inicio</a>'; }
	}
?>
  </main>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script>window.jQuery || document.write('<script src="../assets/js/vendor/jquery.slim.min.js"><\/script>')</script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.9.0/feather.min.js"></script>
	<script src="../../js/bootstrap-slider.js"></script>
  <!--datatables-->
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css" />
  <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.18/datatables.min.js"></script>
  <!--datatables-->
  <script>
  $('#resultckldtflota').DataTable( {
    responsive: true,
	 "searching": true,
        "paging":   true,
        "info":     true,
		"autoWidth": false	
} );
  $('#iniciochecklist').DataTable( {
    responsive: true,
	 "searching": true,
        "paging":   true,
        "info":     true,
		"autoWidth": true
} );
  </script>
</body>
</html>