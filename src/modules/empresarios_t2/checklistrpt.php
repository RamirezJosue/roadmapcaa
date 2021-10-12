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
	require('../head.php');
	if(isset($_GET['fechaselec'])): 
	$fecha_form = $_GET['fechaselec'];
	$fechars = $_GET['fechaselec'];	
	else:
	$fechars = $fechars; 
	$fecha_form = $fecha;
	endif;
if (isset($_GET['exa'])){ $exa = $_GET['exa']; } else { $exa = ""; }
if (isset($_GET['ckl'])){ $ckl = $_GET['ckl']; } else { $ckl = ""; }		
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
	} else if($type=='date'){
	?>
	<input class="<?php echo  $classinput; ?>" id="<?php echo  $id; ?>" type="<?php echo  $type; ?>" name="<?php echo  $name; ?>"  value="<?php  echo $value; ?>" placeholder="<?php  echo $placeholder; ?>" <?php echo  $disabled;?> required >
    <?php			
	} else if($type=='checkbox'){
     if ($resp_verifi == 'respnok'){
		 $resul = '<span class="badge badge-pill badge-danger">X</span>'; } 
		 else if ($resp_verifi == 'respok') { 
		 $resul = '<span class="badge badge-pill badge-success">✓</span>'; }	
		 else { $resul = ''; }			
	?>
	<div class="<?php echo  $classdiv; ?>">
			<input class="<?php echo  $classinput; ?>" id="<?php echo  $id; ?>" type="<?php echo  $type; ?>" name="<?php echo  $name; ?>"  value="<?php  echo $value; ?>" placeholder="<?php  echo $placeholder; ?>" <?php echo  $disabled.' '.$checked; ?> >
			<label class="<?php echo  $classlabel; ?>" for="<?php echo  $for; ?>" > <?php  echo $txlabel; ?> <?php  echo $resul; ?> </label>
	</div>
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
function form_select($sql,$value,$name,$disabled)
{ global $idcentro;
?>
	<select class="form-control" name="<?php echo  $name; ?>" <?php echo  $disabled;?> required >
	<option value="">Seleccionar</$valueoption>
			<?php		
		    $db=Db::conectar();
            $select=$db->prepare($sql);
		    $select->execute();
			while ($regis=$select->fetch()) {
			if ($regis[0] == $value){
			echo '<option  value="'.$regis[0].'" selected >'.$regis[1].'</option>';		
			}else {
			echo '<option  value="'.$regis[0].'" >'.$regis[1].'</option>';	
			}
			}
			Db::desconectar();
			?>
	</select>
<?php
}
function form_examen_check($id_tema)
{
global $idcentro,$aid,$crudex,$tipocuestionario,$fecha,$tipopregunta,$letrasresp;	
$cuestiontb = $crudex -> sacarmonbredb('descripcion,tipo,estado','exa_temas','id="'.$id_tema.'"'); 
$tipo_tema=$cuestiontb[1];
$desc_tema=$cuestiontb[0]; 	
$estado_tema=$cuestiontb[2];
$sqlchecklist="
SELECT 
sum(b.st) as st,
sum(b.respuesta_resp) as respuesta_resp, 
sum(b.contarpreguntas) as contarpreguntas, 
b.grupo, b.id_tema 
FROM (
SELECT SUM(a.st) AS st, SUM(a.respuesta_resp) AS respuesta_resp, a.id_pregunta_grupo, 1 AS contarpreguntas,a.grupo,a.id_tema FROM (
SELECT id,st,respuesta_user,IF(respuesta_user='',0,1) as respuesta_resp ,id_pregunta_grupo,grupo,id_tema FROM `exa_detalle_checklist` WHERE centro='$idcentro' AND user_registro='$aid' AND id_tema=$id_tema AND st <> 1
    ) AS a GROUP BY a.id_pregunta_grupo, a.grupo, a.id_tema
    ) AS b GROUP BY b.grupo,b.id_tema 
	   ";
    $statusckl = $crudex -> consultabd($sqlchecklist);        
	if (($statusckl[1]-$statusckl[2]) < 0 ){ $disabledEnviar='disabled'; $disabledGrabar=''; } else { $disabledEnviar='';  $disabledGrabar='disabled'; }
	?>	
	<form action="checklistrpt?exa=iniciar&id=<?php echo $id_tema; ?>" method="POST">
    <div class="row">
    <div class="col-12">
	<input type="button" class="btn btn-danger" value="Volver" onClick="history.go(-1);">
	<button type="submit" class="btn btn-dark"  <?php echo $disabledGrabar; ?> >Grabar</button>
	<button type="button" class="btn btn-dark" onclick="location.href='checklistrpt?exa=enviarchecklist&amp;grupo=<?php echo $statusckl['grupo']; ?>&amp;id_tema=<?php echo $id_tema; ?>';"  <?php echo $disabledEnviar; ?> >Enviar</button>
	</div>
	</div>
	<div class="row">
    <div class="col-sm-12">
    <div class="p-1"><div class="bg-light text-danger text-left font-weight-bolder"><?php echo ucfirst($desc_tema); ?><?php echo ' | '.$tipocuestionario[$tipo_tema].' | '.$aid; ?></div></div>
	</div>
	</div> 	
	<?php
          $gp=1;
		  $dbgrup=Db::conectar();
		  $sqlgrup ='SELECT id_grupo_preguntas,grupo FROM exa_detalle_checklist WHERE id_tema=:id_tema AND user_registro=:user_registro AND st=0 GROUP BY id_grupo_preguntas,grupo';
          $selectgrup=$dbgrup->prepare($sqlgrup);
		  $selectgrup->bindValue('id_tema',$id_tema);
		  $selectgrup->bindValue('user_registro',$aid);
		  $selectgrup->execute();
          while ($rowgrupo=$selectgrup->fetch()) {	  
		  $id_grupo_preguntas = $rowgrupo['id_grupo_preguntas'];
		  $grupo = $rowgrupo['grupo'];
		  $rowgrupodes = $crudex -> sacarmonbredb('descripcion','exa_grupo_preguntas','id="'.$id_grupo_preguntas.'"'); 
	?>
	<div class="row bg-light text-dark">
    <div class="col-sm-12">
	<div class="p-1"><div class="bg-light text-danger text-left font-weight-bolder"> <?php echo ucfirst($rowgrupodes[0]); ?></div></div>
	</div>
	<br>
	<div class="col-sm-12 bg-light">
	<?php
	      $pre=1;
		  $dbpre=Db::conectar();
		  $sqlpre ='
		  SELECT
          a.id_preguntas,a.tipo_pregunta,a.puntos_pregunta,a.txt_actions,a.txt_comentario,a.id_pregunta_grupo,sum(a.respuesta_user_select) AS respuesta_user_select	  
		  FROM(
		  SELECT id_preguntas,tipo_pregunta,puntos_pregunta,txt_actions,txt_comentario,id_pregunta_grupo, CAST(respuesta_user AS INTEGER) as respuesta_user_select  
		  FROM exa_detalle_checklist 
		  WHERE id_tema=:id_tema AND id_grupo_preguntas=:id_grupo_preguntas AND user_registro=:user_registro AND st=0
		  GROUP BY id_preguntas,tipo_pregunta,puntos_pregunta,txt_actions,txt_comentario,id_pregunta_grupo,respuesta_user
		  ) AS a GROUP BY a.id_preguntas,a.tipo_pregunta,a.puntos_pregunta,a.txt_actions,a.txt_comentario,a.id_pregunta_grupo 
		  ';
          $selectpre=$dbpre->prepare($sqlpre);
		  $selectpre->bindValue('id_tema',$id_tema);
		  $selectpre->bindValue('id_grupo_preguntas',$id_grupo_preguntas);
		  $selectpre->bindValue('user_registro',$aid);
		  $selectpre->execute();
          while ($rowpregunta=$selectpre->fetch()) {
		  $id_preguntas = $rowpregunta['id_preguntas'];	
		  $id_pregunta_grupo = $rowpregunta['id_pregunta_grupo'];
		  $respuesta_user_select = $rowpregunta['respuesta_user_select'];	
          $tipo_pregunta = $rowpregunta['tipo_pregunta'];
          $puntos_pregunta = $rowpregunta['puntos_pregunta'];		  
		  $rowgrupodes = $crudex -> sacarmonbredb('pregunta,descripcion','exa_preguntas','id="'.$id_preguntas.'"');
		  $desc_pregunta = $rowgrupodes['pregunta'];
		  $subtitulo = str_replace(";","<br>", $rowgrupodes['descripcion']);
		  $CountRespUser = $crudex-> contardb('respuesta_user','exa_detalle_checklist','id_preguntas = '.$id_preguntas.' AND user_registro = "'.$aid.'" AND centro = "'.$idcentro.'" AND respuesta_user <> "" AND st=0'); 
		  if($CountRespUser <= 0){ $disabledpre=''; }else{ $disabledpre='disabled'; }
		  $txt_actions=$rowpregunta['txt_actions'];
		  $txt_comentario=$rowpregunta['txt_comentario'];
		  
		  ?>
		 <div class="card border-secondary mb-3">
		 <div class="card-body">
		 <h6 class="card-title text-primary"><?php echo ucfirst($desc_pregunta); if ($tipo_tema==2){ echo ' - Puntos : '.$puntos_pregunta.''; }?></h6>
		 <h6 class="card-subtitle mb-2 text-muted"><?php echo $subtitulo; ?></h6>
		 <table class="table table-sm">
		 <tbody>
		<?php
		  $dbres=Db::conectar();
		  $sqlres ='SELECT id,desc_resp,orden_resp,doble_check,respuesta_user,resp_correcta,st,tipo_pregunta 
		            FROM exa_detalle_checklist 
					WHERE id_tema=:id_tema AND id_preguntas=:id_preguntas AND user_registro=:user_registro AND st=0';
          $selectres=$dbres->prepare($sqlres);
		  $selectres->bindValue('id_tema',$id_tema);
		  $selectres->bindValue('id_preguntas',$id_preguntas);
		  $selectres->bindValue('user_registro',$aid);
		  $selectres->execute();
		  $n=1;
          while ($rowrespuesta=$selectres->fetch()) {
          $desc_resp = $rowrespuesta['desc_resp'];
		  $tipo_pregunta = $rowrespuesta['tipo_pregunta'];
          $id_resp_user = $rowrespuesta['id'];
		  if($rowrespuesta['doble_check']==1){ $doble_check='class="table-danger"'; }else{ $doble_check=''; };
		  $respuesta_user = $rowrespuesta['respuesta_user'];
		  $resp_correcta = $rowrespuesta['resp_correcta'];
		  if(($rowrespuesta['st']==0) && ($respuesta_user == '')){ $checked=''; $disabled=''; } else { $checked='checked'; $disabled='disabled'; } 
		  if($tipo_tema==2){ // si es examen 
          if($resp_correcta == 1) { 
          $resp_verifi='respok';
		  } else { if($respuesta_user <= 0){ $resp_verifi = ''; } else { $resp_verifi='respnok'; } }
		  } else { $resp_verifi=''; } 
		  
    switch ($tipo_pregunta):
    case 1: // Múltiple radio button	
		?>
		 <tr <?php echo $doble_check; ?> >
		 <td style="width: 12%;" ><?php echo  $letrasresp[$rowrespuesta['orden_resp']].'.-'; ?></td>
		 <td style="width: 88%;" >
		 <?php 
form_type_resp('radio',$id_preguntas,$id_preguntas,'form-check','form-check-input','form-check-label','nameradiocheck['.$tipo_pregunta.']['.$id_preguntas.']',$id_resp_user,$desc_resp,$disabledpre,$checked,'',$resp_verifi); 
		 ?> 
		 </td>
		 </tr>
		 <?php
         break;
    case 2: // Casilla de verificación Check box
		?>
		 <tr <?php echo $doble_check; ?> >
		 <td style="width: 12%;" ><?php echo  $letrasresp[$rowrespuesta['orden_resp']].'.-'; ?></td>
		 <td style="width: 88%;" >
		 <?php 
form_type_resp('checkbox',$id_preguntas,$id_preguntas,'form-check','form-check-input','form-check-label','nameradiocheck['.$tipo_pregunta.']['.$id_resp_user.']',$id_preguntas,$desc_resp,$disabledpre,$checked,'',''); 
		 ?>
		 </td>
		 </tr>
		 <?php
        break;
    case 3: // Check Si-No
		?>
		 <tr <?php echo $doble_check; ?> >
		 <td style="width: 12%;" ><?php echo  $letrasresp[$rowrespuesta['orden_resp']].'.-'; ?></td>
		 <td style="width: 88%;" >
		 <?php 
form_type_resp('radio',$id_preguntas,$id_preguntas,'form-check','form-check-input','form-check-label','nameradiocheck['.$tipo_pregunta.']['.$id_preguntas.']',$id_resp_user,$desc_resp,$disabledpre,$checked,'',''); 
		 ?>
		 </td>
		 </tr>
		 <?php
        break;
    case 4: // Múltiple Si-No
		?>
		 <tr <?php echo $doble_check; ?> >
		 <td style="width: 50%;" ><?php echo  $n.'.- '.$desc_resp; ?></td>
		 <td style="width: 50%;" >
		 <?php 
		  if($respuesta_user==''){ $checkedsi=''; } else if($respuesta_user==1){ $checkedsi='checked'; } else { $checkedsi=''; }  //cambiar despues
		  if($respuesta_user==''){ $checkedno=''; } else if($respuesta_user==0){ $checkedno='checked'; } else { $checkedno=''; }		  
form_type_resp('radio',$id_preguntas,$id_preguntas,'form-check form-check-inline','form-check-input','form-check-label','nameradiocheck['.$tipo_pregunta.']['.$id_resp_user.']',1,'Si',$disabled,'checked','',''); 
form_type_resp('radio',$id_preguntas,$id_preguntas,'form-check form-check-inline','form-check-input','form-check-label','nameradiocheck['.$tipo_pregunta.']['.$id_resp_user.']',0,'No',$disabled,$checkedno,'',''); 
		 ?>
		 </td>
		 </tr>
		 <?php
        break;
    case 5: // Texto
		?>
		 <tr <?php echo $doble_check; ?> >
		 <td style="width: 50%;" ><?php echo  $letrasresp[$rowrespuesta['orden_resp']].'.- '.$desc_resp; ?></td>
		 <td style="width: 50%;" >
		 <?php 
form_type_resp('text',$id_preguntas,$id_preguntas,'','form-control','','nameradiocheck['.$tipo_pregunta.']['.$id_resp_user.']',$respuesta_user,'',$disabled,$checked,$desc_resp,''); 
		 ?>
		 </td>
		 </tr>
		 <?php
        break;
    case 6:  // Textarea
		?>
		 <tr <?php echo $doble_check; ?> >
		 <td style="width: 12%;" ><?php echo  $letrasresp[$rowrespuesta['orden_resp']].'.-'; ?></td>
		 <td style="width: 88%;" >
		 <?php 
form_type_resp('textarea',$id_preguntas,$id_preguntas,'','form-control','','nameradiocheck['.$tipo_pregunta.']['.$id_resp_user.']',$respuesta_user,$desc_resp,$disabled,$checked,'',''); 
		 ?>
		 </td>
		 </tr>
		 <?php
        break;
    case 7: // Múltiple RangeInputs
		?> 
		 <tr <?php echo $doble_check; ?> >   
		 <td colspan="2" >
		 <div class="card card-body mb-1">
          <div class="mb-3"><?php echo  $letrasresp[$rowrespuesta['orden_resp']].'.- '.$desc_resp; ?></div>
<?php form_type_resp('range',$id_preguntas,$id_preguntas,'','custom-range','','nameradiocheck['.$tipo_pregunta.']['.$id_resp_user.']',$respuesta_user,$desc_resp,$disabled,$checked,'','');?>
        </div>
		  
		 </td>
		 </tr>
		 <?php
        break;
    case 8: //  Fecha - datos head
		?> 
		 <tr <?php echo $doble_check; ?> >   
		 <td colspan="2" >
		 <div class="card card-body mb-0">
         <div class="mb-0"><?php echo  $letrasresp[$rowrespuesta['orden_resp']].'.- '.$desc_resp; ?></div>
<?php form_type_resp('date',$id_preguntas,$id_preguntas,'','form-control','','nameradiocheck['.$tipo_pregunta.']['.$id_resp_user.']',$respuesta_user,'',$disabled,$checked,$desc_resp,'');?>
         </div>
		 </td>
		 </tr>
		 <?php
        break;
    case 9: // 'Select empresa - datos head
		?> 
		 <tr <?php echo $doble_check; ?> >   
		 <td colspan="2" >
		 <div class="card card-body mb-1">
         <div class="mb-3"><?php echo  $letrasresp[$rowrespuesta['orden_resp']].'.- '.$desc_resp; ?></div>
<?php form_select("SELECT `descripcion`,`descripcion` FROM `usuario_empresa` WHERE centro='$idcentro'",$respuesta_user,'nameradiocheck['.$tipo_pregunta.']['.$id_resp_user.']',$disabled); ?>
         </div>
		 </td>
		 </tr>
		 <?php
        break;
    case 10: // Select BK - datos head
		?> 
		 <tr <?php echo $doble_check; ?> >   
		 <td colspan="2" > 
		 <div class="card card-body mb-1">
         <div class="mb-3"><?php echo  $letrasresp[$rowrespuesta['orden_resp']].'.- '.$desc_resp; ?></div>
<?php form_select("SELECT dni,dni FROM usuarios WHERE centro='$idcentro' AND puesto=29 GROUP BY dni",$respuesta_user,'nameradiocheck['.$tipo_pregunta.']['.$id_resp_user.']',$disabled); ?>
         </div>
		 </td>
		 </tr>
		 <?php
        break;	
    case 11: // Puesto trabajo - datos head
		?> 
		 <tr <?php echo $doble_check; ?> >   
		 <td colspan="2" >
		 <div class="card card-body mb-1">
         <div class="mb-3"><?php echo  $letrasresp[$rowrespuesta['orden_resp']].'.- '.$desc_resp; ?></div>
<?php form_select("SELECT descripcion,descripcion FROM usuario_puesto",$respuesta_user,'nameradiocheck['.$tipo_pregunta.']['.$id_resp_user.']',$disabled); ?>
         </div> 
		 </td>
		 </tr>
		 <?php
        break;	
    case 12: // Satisfaccion 1 al 5
		?>
		 <tr <?php echo $doble_check; ?> >
		 <td style="width: 12%;" ><?php echo  $letrasresp[$rowrespuesta['orden_resp']].'.-'; ?></td>
		 <td style="width: 88%;" >
		 <?php 
form_type_resp('radio',$id_preguntas,$id_preguntas,'form-check','form-check-input','form-check-label','nameradiocheck['.$tipo_pregunta.']['.$id_preguntas.']',$id_resp_user,$desc_resp,$disabledpre,$checked,'',$resp_verifi); 
		 ?> 
		 </td>
		 </tr>
		 <?php
        break;	
    case 13: // Codigo cliente - datos head
		?>
		 <tr <?php echo $doble_check; ?> >
		 <td style="width: 50%;" ><?php echo  $letrasresp[$rowrespuesta['orden_resp']].'.- '.$desc_resp; ?></td>
		 <td style="width: 50%;" >
		 <?php 
form_type_resp('text',$id_preguntas,$id_preguntas,'','form-control','','nameradiocheck['.$tipo_pregunta.']['.$id_resp_user.']',$respuesta_user,'',$disabled,$checked,$desc_resp,''); 
		 ?>
		 </td>
		 </tr>
		 <?php
        break;	
    case 14: // Area Oficinas - datos head
		?> 
		 <tr <?php echo $doble_check; ?> >   
		 <td colspan="2" >
		  
		<div class="card card-body mb-1">
          <div class="mb-3"><?php echo  $letrasresp[$rowrespuesta['orden_resp']].'.- '.$desc_resp; ?></div>
<?php form_select("SELECT descripcion,descripcion FROM exa_area_5s WHERE area=2",$respuesta_user,'nameradiocheck['.$tipo_pregunta.']['.$id_resp_user.']',$disabled); ?>
        </div>
		  
		 </td>
		 </tr>
		 <?php
        break;	
    case 15: // Area Almacen - datos head
		?> 
		 <tr <?php echo $doble_check; ?> >   
		 <td colspan="2" >
		  
		<div class="card card-body mb-1">
          <div class="mb-3"><?php echo  $letrasresp[$rowrespuesta['orden_resp']].'.- '.$desc_resp; ?></div>
<?php form_select("SELECT descripcion,descripcion FROM exa_area_5s WHERE area=1",$respuesta_user,'nameradiocheck['.$tipo_pregunta.']['.$id_resp_user.']',$disabled); ?>
        </div>
		  
		 </td>
		 </tr>
		 <?php
        break;
    case 16: // Usuariso HC - datos head
		?> 
		 <tr <?php echo $doble_check; ?> >   
		 <td colspan="2" >
		  
		<div class="card card-body mb-1">
          <div class="mb-3"><?php echo  $letrasresp[$rowrespuesta['orden_resp']].'.- '.$desc_resp; ?></div>
<?php form_select("SELECT CONCAT(apellidos,' ',nombre) as usuarios ,CONCAT(apellidos,' ',nombre) as usuario FROM usuarios WHERE centro='$idcentro' AND puesto NOT IN (29,28) AND estado=1 ORDER BY apellidos",$respuesta_user,'nameradiocheck['.$tipo_pregunta.']['.$id_resp_user.']',$disabled); ?>
        </div>
		 </td>
		 </tr>
		 <?php
        break;
    case 17: // Flota - datos head
		?> 
		 <tr <?php echo $doble_check; ?> >   
		 <td colspan="2" >
		<div class="card card-body mb-1">
          <div class="mb-3"><?php echo  $letrasresp[$rowrespuesta['orden_resp']].'.- '.$desc_resp; ?></div>
<?php form_select("SELECT dni,dni FROM usuarios WHERE centro='$idcentro' AND puesto=28 GROUP BY dni",$respuesta_user,'nameradiocheck['.$tipo_pregunta.']['.$id_resp_user.']',$disabled); ?>
        </div>
		 </td>
		 </tr>
		 <?php
        break;
    case 18: // Select list
        break;		
	endswitch;	
	$n++;	   
		   }
		Db::desconectar();		   
		?>	
         </tbody>
		 </table>
		<?php 
		if($tipo_pregunta==18){
	form_select("SELECT id,desc_resp FROM `exa_detalle_checklist` WHERE `id_pregunta_grupo`='$id_pregunta_grupo' AND id_tema=$id_tema",$respuesta_user_select,'nameradiocheck['.$tipo_pregunta.']['.$id_preguntas.']',$disabledpre); 
		echo '<br>';
		} else { } 
		if($txt_actions == 1){?> 
		<div class="form-group">
		<label for="comentario1">Acciones  / Comentario</label>
		<input type="text" class="form-control" id="comentario1" name="nameradiocheck[100][<?php echo $id_pregunta_grupo; ?>]" value="<?php echo $txt_comentario; ?>"  <?php echo $disabledpre; ?> >
		<small id="emailHelp" class="form-text text-muted">Anotación</small>
		</div>
		<?php } else { } ?> 
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
    <div class="col-12">
	<input type="button" class="btn btn-danger" value="Volver" onClick="history.go(-1);">
	<button type="submit" class="btn btn-dark"  <?php echo $disabledGrabar; ?> >Grabar</button>
	<button type="button" class="btn btn-dark" onclick="location.href='checklistrpt?exa=enviarchecklist&amp;grupo=<?php echo $statusckl['grupo']; ?>&amp;id_tema=<?php echo $id_tema; ?>';"  <?php echo $disabledEnviar; ?> >Enviar</button>
	</div>
	<input name="grupo" type="hidden" value="<? echo $grupo; ?>">
	</form>	
    <br>	
	<?php
}
function form_examen_check_watch($id_tema,$clave)
{
global $idcentro,$aid,$crudex,$tipocuestionario,$fecha,$tipopregunta,$letrasresp;	
$cuestiontb = $crudex -> sacarmonbredb('descripcion,tipo,estado','exa_temas','id="'.$id_tema.'"'); 
$tipo_tema=$cuestiontb[1];
$desc_tema=$cuestiontb[0]; 	
$estado_tema=$cuestiontb[2];
$sqlchecklist="
SELECT 
sum(b.st) as st,
sum(b.respuesta_resp) as respuesta_resp, 
sum(b.contarpreguntas) as contarpreguntas, 
b.grupo, b.id_tema 
FROM (
SELECT SUM(a.st) AS st, SUM(a.respuesta_resp) AS respuesta_resp, a.id_pregunta_grupo, 1 AS contarpreguntas,a.grupo,a.id_tema FROM (
SELECT id,st,respuesta_user,IF(respuesta_user='',0,1) as respuesta_resp ,id_pregunta_grupo,grupo,id_tema FROM `exa_detalle_checklist` WHERE centro='$idcentro' AND id_tema=$id_tema AND st=1 AND grupo='$clave'
    ) AS a GROUP BY a.id_pregunta_grupo, a.grupo, a.id_tema
    ) AS b GROUP BY b.grupo,b.id_tema 
	   ";
    $statusckl = $crudex -> consultabd($sqlchecklist);        
	if (($statusckl[1]-$statusckl[2]) < 0 ){ $disabledEnviar='disabled'; $disabledGrabar=''; } else { $disabledEnviar='';  $disabledGrabar='disabled'; }
	?>	
	<form action="checklistrpt?exa=iniciar&id=<?php echo $id_tema; ?>" method="POST">
    <div class="row">
    <div class="col-12">
	<button type="button" class="btn btn-danger" onclick="location.href='checklistrpt?exa=result&amp;id=<?php echo $id_tema; ?>';" >Cancelar</button>
    <input type="button" class="btn btn-danger" value="Volver" onClick="history.go(-1);">	
	<button type="submit" class="btn btn-dark"  <?php echo $disabledGrabar; ?> >Grabar</button>
	<button type="button" class="btn btn-dark" onclick="location.href='checklistrpt?exa=enviarchecklist&amp;grupo=<?php echo $statusckl['grupo']; ?>&amp;id_tema=<?php echo $id_tema; ?>';"  <?php echo $disabledGrabar; ?> >Enviar</button>
	
	</div>
	</div>
	<div class="row">
    <div class="col-sm-12">
    <div class="p-1"><div class="bg-light text-danger text-left font-weight-bolder"><?php echo ucfirst($desc_tema); ?><?php echo ' | '.$tipocuestionario[$tipo_tema].' | '.$aid; ?></div></div>
	</div>
	</div> 	
	<?php
          $gp=1;
		  $dbgrup=Db::conectar();
		  $sqlgrup ='SELECT id_grupo_preguntas,grupo FROM exa_detalle_checklist WHERE id_tema=:id_tema AND grupo=:grupo AND st=1 GROUP BY id_grupo_preguntas,grupo';
          $selectgrup=$dbgrup->prepare($sqlgrup);
		  $selectgrup->bindValue('id_tema',$id_tema);
		  $selectgrup->bindValue('grupo',$clave);
		  $selectgrup->execute();
          while ($rowgrupo=$selectgrup->fetch()) {	  
		  $id_grupo_preguntas = $rowgrupo['id_grupo_preguntas'];
		  $grupo = $rowgrupo['grupo'];
		  $rowgrupodes = $crudex -> sacarmonbredb('descripcion','exa_grupo_preguntas','id="'.$id_grupo_preguntas.'"'); 
	?>
	<div class="row bg-light text-dark">
    <div class="col-sm-12">
	<div class="p-1"><div class="bg-light text-danger text-left font-weight-bolder"> <?php echo ucfirst($rowgrupodes[0]); ?></div></div>
	</div>
	<br>
	<div class="col-sm-12 bg-light">
	<?php
	      $pre=1;
		  $dbpre=Db::conectar();
		  $sqlpre ='
		  SELECT
          a.id_preguntas,a.tipo_pregunta,a.puntos_pregunta,a.txt_actions,a.txt_comentario,a.id_pregunta_grupo,sum(a.respuesta_user_select) AS respuesta_user_select	  
		  FROM(
		  SELECT id_preguntas,tipo_pregunta,puntos_pregunta,txt_actions,txt_comentario,id_pregunta_grupo, CAST(respuesta_user AS INTEGER) as respuesta_user_select  
		  FROM exa_detalle_checklist 
		  WHERE id_tema=:id_tema AND id_grupo_preguntas=:id_grupo_preguntas AND grupo=:grupo AND st=1
		  GROUP BY id_preguntas,tipo_pregunta,puntos_pregunta,txt_actions,txt_comentario,id_pregunta_grupo,respuesta_user
		  ) AS a GROUP BY a.id_preguntas,a.tipo_pregunta,a.puntos_pregunta,a.txt_actions,a.txt_comentario,a.id_pregunta_grupo 
		  ';
          $selectpre=$dbpre->prepare($sqlpre);
		  $selectpre->bindValue('id_tema',$id_tema);
		  $selectpre->bindValue('id_grupo_preguntas',$id_grupo_preguntas);
		  $selectpre->bindValue('grupo',$clave);
		  $selectpre->execute();
          while ($rowpregunta=$selectpre->fetch()) {
		  $id_preguntas = $rowpregunta['id_preguntas'];	
		  $id_pregunta_grupo = $rowpregunta['id_pregunta_grupo'];
		  $respuesta_user_select = $rowpregunta['respuesta_user_select'];	
          $tipo_pregunta = $rowpregunta['tipo_pregunta'];
          $puntos_pregunta = $rowpregunta['puntos_pregunta'];		  
		  $rowgrupodes = $crudex -> sacarmonbredb('pregunta,descripcion','exa_preguntas','id="'.$id_preguntas.'"');
		  $desc_pregunta = $rowgrupodes['pregunta'];
		  $subtitulo = str_replace(";","<br>", $rowgrupodes['descripcion']);
		  $CountRespUser = $crudex-> contardb('respuesta_user','exa_detalle_checklist','id_preguntas = '.$id_preguntas.' AND grupo = "'.$clave.'" AND centro = "'.$idcentro.'" AND respuesta_user <> "" AND st=1'); 
		  if($CountRespUser <= 0){ $disabledpre=''; }else{ $disabledpre='disabled'; }
		  $txt_actions=$rowpregunta['txt_actions'];
		  $txt_comentario=$rowpregunta['txt_comentario'];
		  
		  ?>
		 <div class="card border-secondary mb-3">
		 <div class="card-body">
		 <h6 class="card-title text-primary"><?php echo ucfirst($desc_pregunta); if ($tipo_tema==2){ echo ' - Puntos : '.$puntos_pregunta.''; }?></h6>
		 <h6 class="card-subtitle mb-2 text-muted"><?php echo $subtitulo; ?></h6>
		 <table class="table table-sm">
		 <tbody>
		<?php
		  $dbres=Db::conectar();
		  $sqlres ='SELECT id,desc_resp,orden_resp,doble_check,respuesta_user,resp_correcta,st,tipo_pregunta 
		            FROM exa_detalle_checklist 
					WHERE id_tema=:id_tema AND id_preguntas=:id_preguntas AND grupo=:grupo AND st=1';
          $selectres=$dbres->prepare($sqlres);
		  $selectres->bindValue('id_tema',$id_tema);
		  $selectres->bindValue('id_preguntas',$id_preguntas);
		  $selectres->bindValue('grupo',$clave);
		  $selectres->execute();
          $n=1;
          while ($rowrespuesta=$selectres->fetch()) {
          $desc_resp = $rowrespuesta['desc_resp'];
		  $tipo_pregunta = $rowrespuesta['tipo_pregunta'];
          $id_resp_user = $rowrespuesta['id'];
		  if($rowrespuesta['doble_check']==1){ $doble_check='class="table-danger"'; }else{ $doble_check=''; };
		  $respuesta_user = $rowrespuesta['respuesta_user'];
		  $resp_correcta = $rowrespuesta['resp_correcta'];
		  if(($rowrespuesta['st']==0) && ($respuesta_user == '')){ $checked=''; $disabled=''; } else { $checked='checked'; $disabled='disabled'; } 
		  if($tipo_tema==2){ // si es examen 
          if($resp_correcta == 1) { 
          $resp_verifi='respok';
		  } else { if($respuesta_user <= 0){ $resp_verifi = ''; } else { $resp_verifi='respnok'; } }
		  } else { $resp_verifi=''; } 
		  
    switch ($tipo_pregunta):
    case 1: // Múltiple radio button	
		?>
		 <tr <?php echo $doble_check; ?> >
		 <td style="width: 12%;" ><?php echo  $letrasresp[$rowrespuesta['orden_resp']].'.-'; ?></td>
		 <td style="width: 88%;" >
		 <?php 
form_type_resp('radio',$id_preguntas,$id_preguntas,'form-check','form-check-input','form-check-label','nameradiocheck['.$tipo_pregunta.']['.$id_preguntas.']',$id_resp_user,$desc_resp,$disabledpre,$checked,'',$resp_verifi); 
		 ?> 
		 </td>
		 </tr>
		 <?php
         break;
    case 2: // Casilla de verificación Check box
		?>
		 <tr <?php echo $doble_check; ?> >
		 <td style="width: 12%;" ><?php echo  $letrasresp[$rowrespuesta['orden_resp']].'.-'; ?></td>
		 <td style="width: 88%;" >
		 <?php 
form_type_resp('checkbox',$id_preguntas,$id_preguntas,'form-check','form-check-input','form-check-label','nameradiocheck['.$tipo_pregunta.']['.$id_resp_user.']',$id_preguntas,$desc_resp,$disabledpre,$checked,'',''); 
		 ?>
		 </td>
		 </tr>
		 <?php
        break;
    case 3: // Check Si-No
		?>
		 <tr <?php echo $doble_check; ?> >
		 <td style="width: 12%;" ><?php echo  $letrasresp[$rowrespuesta['orden_resp']].'.-'; ?></td>
		 <td style="width: 88%;" >
		 <?php 
form_type_resp('radio',$id_preguntas,$id_preguntas,'form-check','form-check-input','form-check-label','nameradiocheck['.$tipo_pregunta.']['.$id_preguntas.']',$id_resp_user,$desc_resp,$disabledpre,$checked,'',''); 
		 ?>
		 </td>
		 </tr>
		 <?php
        break;
    case 4: // Múltiple Si-No
		?>
		 <tr <?php echo $doble_check; ?> >
		 <td style="width: 50%;" ><?php echo  $n.'.- '.$desc_resp; ?></td>
		 <td style="width: 50%;" >
		 <?php 
		  if($respuesta_user==''){ $checkedsi=''; } else if($respuesta_user==1){ $checkedsi='checked'; } else { $checkedsi=''; }  
		  if($respuesta_user==''){ $checkedno=''; } else if($respuesta_user==0){ $checkedno='checked'; } else { $checkedno=''; }		  
form_type_resp('radio',$id_preguntas,$id_preguntas,'form-check form-check-inline','form-check-input','form-check-label','nameradiocheck['.$tipo_pregunta.']['.$id_resp_user.']',1,'Si',$disabled,$checkedsi,'',''); 
form_type_resp('radio',$id_preguntas,$id_preguntas,'form-check form-check-inline','form-check-input','form-check-label','nameradiocheck['.$tipo_pregunta.']['.$id_resp_user.']',0,'No',$disabled,$checkedno,'',''); 
		 ?>
		 </td>
		 </tr>
		 <?php
        break;
    case 5: // Texto
		?>
		 <tr <?php echo $doble_check; ?> >
		 <td style="width: 50%;" ><?php echo  $letrasresp[$rowrespuesta['orden_resp']].'.- '.$desc_resp; ?></td>
		 <td style="width: 50%;" >
		 <?php 
form_type_resp('text',$id_preguntas,$id_preguntas,'','form-control','','nameradiocheck['.$tipo_pregunta.']['.$id_resp_user.']',$respuesta_user,'',$disabled,$checked,$desc_resp,''); 
		 ?>
		 </td>
		 </tr>
		 <?php
        break;
    case 6:  // Textarea
		?>
		 <tr <?php echo $doble_check; ?> >
		 <td style="width: 12%;" ><?php echo  $letrasresp[$rowrespuesta['orden_resp']].'.-'; ?></td>
		 <td style="width: 88%;" >
		 <?php 
form_type_resp('textarea',$id_preguntas,$id_preguntas,'','form-control','','nameradiocheck['.$tipo_pregunta.']['.$id_resp_user.']',$respuesta_user,$desc_resp,$disabled,$checked,'',''); 
		 ?>
		 </td>
		 </tr>
		 <?php
        break;
    case 7: // Múltiple RangeInputs
		?> 
		 <tr <?php echo $doble_check; ?> >   
		 <td colspan="2" >
		 <div class="card card-body mb-1">
          <div class="mb-3"><?php echo  $letrasresp[$rowrespuesta['orden_resp']].'.- '.$desc_resp; ?></div>
<?php form_type_resp('range',$id_preguntas,$id_preguntas,'','custom-range','','nameradiocheck['.$tipo_pregunta.']['.$id_resp_user.']',$respuesta_user,$desc_resp,$disabled,$checked,'','');?>
        </div>
		  
		 </td>
		 </tr>
		 <?php
        break;
    case 8: //  Fecha - datos head
		?> 
		 <tr <?php echo $doble_check; ?> >   
		 <td colspan="2" >
		 <div class="card card-body mb-0">
         <div class="mb-0"><?php echo  $letrasresp[$rowrespuesta['orden_resp']].'.- '.$desc_resp; ?></div>
<?php form_type_resp('date',$id_preguntas,$id_preguntas,'','form-control','','nameradiocheck['.$tipo_pregunta.']['.$id_resp_user.']',$respuesta_user,'',$disabled,$checked,$desc_resp,'');?>
         </div>
		 </td>
		 </tr>
		 <?php
        break;
    case 9: // 'Select empresa - datos head
		?> 
		 <tr <?php echo $doble_check; ?> >   
		 <td colspan="2" >
		 <div class="card card-body mb-1">
         <div class="mb-3"><?php echo  $letrasresp[$rowrespuesta['orden_resp']].'.- '.$desc_resp; ?></div>
<?php form_select("SELECT empresa,empresa FROM t77_em WHERE centro='$idcentro' GROUP BY empresa",$respuesta_user,'nameradiocheck['.$tipo_pregunta.']['.$id_resp_user.']',$disabled); ?>
         </div>
		 </td>
		 </tr>
		 <?php
        break;
    case 10: // Select BK - datos head
		?> 
		 <tr <?php echo $doble_check; ?> >   
		 <td colspan="2" > 
		 <div class="card card-body mb-1">
         <div class="mb-3"><?php echo  $letrasresp[$rowrespuesta['orden_resp']].'.- '.$desc_resp; ?></div>
<?php form_select("SELECT ruta,ruta FROM t77_em WHERE centro='$idcentro' GROUP BY ruta",$respuesta_user,'nameradiocheck['.$tipo_pregunta.']['.$id_resp_user.']',$disabled); ?>
         </div>
		 </td>
		 </tr>
		 <?php
        break;	
    case 11: // Puesto trabajo - datos head
		?> 
		 <tr <?php echo $doble_check; ?> >   
		 <td colspan="2" >
		 <div class="card card-body mb-1">
         <div class="mb-3"><?php echo  $letrasresp[$rowrespuesta['orden_resp']].'.- '.$desc_resp; ?></div>
<?php form_select("SELECT descripcion,descripcion FROM usuario_puesto",$respuesta_user,'nameradiocheck['.$tipo_pregunta.']['.$id_resp_user.']',$disabled); ?>
         </div> 
		 </td>
		 </tr>
		 <?php
        break;	
    case 12: // Satisfaccion 1 al 5
		?>
		 <tr <?php echo $doble_check; ?> >
		 <td style="width: 12%;" ><?php echo  $letrasresp[$rowrespuesta['orden_resp']].'.-'; ?></td>
		 <td style="width: 88%;" >
		 <?php 
form_type_resp('radio',$id_preguntas,$id_preguntas,'form-check','form-check-input','form-check-label','nameradiocheck['.$tipo_pregunta.']['.$id_preguntas.']',$id_resp_user,$desc_resp,$disabledpre,$checked,'',$resp_verifi); 
		 ?> 
		 </td>
		 </tr>
		 <?php
        break;	
    case 13: // Codigo cliente - datos head
		?>
		 <tr <?php echo $doble_check; ?> >
		 <td style="width: 50%;" ><?php echo  $letrasresp[$rowrespuesta['orden_resp']].'.- '.$desc_resp; ?></td>
		 <td style="width: 50%;" >
		 <?php 
form_type_resp('text',$id_preguntas,$id_preguntas,'','form-control','','nameradiocheck['.$tipo_pregunta.']['.$id_resp_user.']',$respuesta_user,'',$disabled,$checked,$desc_resp,''); 
		 ?>
		 </td>
		 </tr>
		 <?php
        break;	
    case 14: // Area Oficinas - datos head
		?> 
		 <tr <?php echo $doble_check; ?> >   
		 <td colspan="2" >
		  
		<div class="card card-body mb-1">
          <div class="mb-3"><?php echo  $letrasresp[$rowrespuesta['orden_resp']].'.- '.$desc_resp; ?></div>
<?php form_select("SELECT descripcion,descripcion FROM exa_area_5s WHERE area=2",$respuesta_user,'nameradiocheck['.$tipo_pregunta.']['.$id_resp_user.']',$disabled); ?>
        </div>
		  
		 </td>
		 </tr>
		 <?php
        break;	
    case 15: // Area Almacen - datos head
		?> 
		 <tr <?php echo $doble_check; ?> >   
		 <td colspan="2" >
		  
		<div class="card card-body mb-1">
          <div class="mb-3"><?php echo  $letrasresp[$rowrespuesta['orden_resp']].'.- '.$desc_resp; ?></div>
<?php form_select("SELECT descripcion,descripcion FROM exa_area_5s WHERE area=1",$respuesta_user,'nameradiocheck['.$tipo_pregunta.']['.$id_resp_user.']',$disabled); ?>
        </div>
		  
		 </td>
		 </tr>
		 <?php
        break;
    case 16: // Usuariso HC - datos head
		?> 
		 <tr <?php echo $doble_check; ?> >   
		 <td colspan="2" >
		  
		<div class="card card-body mb-1">
          <div class="mb-3"><?php echo  $letrasresp[$rowrespuesta['orden_resp']].'.- '.$desc_resp; ?></div>
<?php form_select("SELECT CONCAT(apellidos,' ',nombre) as usuarios ,CONCAT(apellidos,' ',nombre) as usuario FROM usuarios WHERE centro='$idcentro' AND puesto NOT IN (29,28) AND estado=1 ORDER BY apellidos",$respuesta_user,'nameradiocheck['.$tipo_pregunta.']['.$id_resp_user.']',$disabled); ?>
        </div>
		 </td>
		 </tr>
		 <?php
        break;
    case 17: // Flota - datos head
		?> 
		 <tr <?php echo $doble_check; ?> >   
		 <td colspan="2" >
		<div class="card card-body mb-1">
          <div class="mb-3"><?php echo  $letrasresp[$rowrespuesta['orden_resp']].'.- '.$desc_resp; ?></div>
<?php form_select("SELECT placa,placa FROM t77_vehiculos WHERE centro='$idcentro'",$respuesta_user,'nameradiocheck['.$tipo_pregunta.']['.$id_resp_user.']',$disabled); ?>
        </div>
		 </td>
		 </tr>
		 <?php
        break;
    case 18: // Select list
        break;		
	endswitch;	
            $n++;	   
		   }
	Db::desconectar();		   
		?>	
         </tbody>
		 </table>
		<?php 
		if($tipo_pregunta==18){
	form_select("SELECT id,desc_resp FROM `exa_detalle_checklist` WHERE `id_pregunta_grupo`='$id_pregunta_grupo' AND id_tema=$id_tema",$respuesta_user_select,'nameradiocheck['.$tipo_pregunta.']['.$id_preguntas.']',$disabledpre); 
		echo '<br>';
		} else { } 
		if($txt_actions == 1){?> 
		<div class="form-group">
		<label for="comentario1">Acciones  / Comentario</label>
		<input type="text" class="form-control" id="comentario1" name="nameradiocheck[100][<?php echo $id_pregunta_grupo; ?>]" value="<?php echo $txt_comentario; ?>"  <?php echo $disabledpre; ?> >
		<small id="emailHelp" class="form-text text-muted">Anotación</small>
		</div>
		<?php } else { } ?> 
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
	<button type="button" class="btn btn-danger" onclick="location.href='checklistrpt?exa=result&amp;id=<?php echo $id_tema; ?>';" >Cancelar</button>
	<button type="submit" class="btn btn-dark"  <?php echo $disabledGrabar; ?> >Grabar</button>
	<button type="button" class="btn btn-dark" onclick="location.href='checklistrpt?exa=enviarchecklist&amp;grupo=<?php echo $statusckl[3]; ?>&amp;id_tema=<?php echo $statusckl[3]; ?>';"  <?php echo $disabledEnviar; ?> >Enviar</button>
	</div>
	</div> 	
	<input name="grupo" type="hidden" value="<? echo $grupo; ?>">
	</form>	
    <br>	
	<?php
}
function inicio_user_check_encuestas_examen($idcentro,$aid,$ckl)
{ global $puestoaid,$Db,$cuestionarioarea;	
?>	
	<div class="table-responsive">
	 <table id="iniciochecklist"  data-order='[[ 0, "asc" ]]'
     class="display compact cell-border">
	<thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Descripcion</th>
	  <th scope="col">Area</th>
      <th scope="col"></th>
	    <th scope="col"></th>
		  <th scope="col"></th>
    </tr>
  </thead>
  <tbody>
  <?php	
  
		$dbtema=Db::conectar();
		$sqltema ="SELECT * FROM (SELECT * FROM exa_temas WHERE estado=1 AND para NOT IN (28,2) AND id_area=:id_area) AS c WHERE c.multicentro=1 OR c.centro=:centro ORDER BY c.descripcion";
        $selecttema=$dbtema->prepare($sqltema);
		$selecttema->bindValue('centro',$idcentro);
		$selecttema->bindValue('id_area',$ckl);
		$selecttema->execute();
		$n=1;
		while ($registema=$selecttema->fetch()) {
		$id_area=$registema['id_area'];	
		?>
		<tr>
		<th scope="row"><?php echo $n; ?> </th>
		<td><?php echo $registema['descripcion']; ?></td>	
	<form  onsubmit="return confirm('Esta seguro de empezar...');" name="conductor" method='POST' action='checklistrpt?exa=crear_examen_check&amp;idtema=<?php echo $registema['id']; ?>'>		
		<td>
	<?php echo $cuestionarioarea[$id_area]; ?>  
		</td>
		<td>
	<input type='hidden' name='id' value='2'>
	<button class="btn btn-danger btn-sm" >Iniciar</button>
	</form>
		</td>
		<td>  
	<button  type="button" class="btn btn-danger btn-sm" onclick="location.href='checklistrpt?exa=result&amp;id=<?php echo $registema['id']; ?>';" >Resultados</button>
		</td>
		<td>   
	<button  type="button" class="btn btn-danger btn-sm" onclick="location.href='../excel_exportar/csv-excel?id=checklist&amp;id_tema=<?php echo $registema['id']; ?>';" >xls</button>  
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
function resultckl($id)
{ global $puestoaid,$Db,$cuestionarioarea,$idcentro;	
?>	
	<div class="row">
    <div class="col-sm-12">
	<div class="p-2"><div class="text-muted text-md-center font-weight-bolder">Resultados Check list</div></div>
	</div>
	</div>
	<div class="table-responsive">
	 <table id="resultckldt"  data-order='[[ 0, "asc" ]]'
     class="display compact cell-border">
	<thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Fecha</th>
	  <th scope="col">CheckList</th>
	  <th scope="col">Area</th>
      <th scope="col">Auditor</th>
	  <th scope="col">Puntaje</th>
	  <th scope="col">Ver</th>
	  <th scope="col">Eliminar</th>
    </tr>
  </thead>
  <tbody>
  <?php	
  
  
switch ($id):
	case 769341408:
		$sqltema ="
SELECT 
a.grupo,
a.id_tema,
a.user_registro,
a.fecha_registro,
a.tema,
a.area_almacen as area,
a.usuario_hc as auditor,
sum(a.respuesta_usuario) as respuesta_usuario,
sum(a.countcheck)*4 as countcheck,
round (sum(a.respuesta_usuario)/(sum(a.countcheck)*4)*100,2) as resultado
FROM 
(
SELECT
1 as countcheck, 
`id`, 
`id_grupo_preguntas`,
`tipo_pregunta`, 
`id_respueta`,
`id_tema`,
`desc_resp`,
`respuesta_user`, 
`user_registro`, 
`grupo`, 
`fecha`, 
`empresa`, 
`fecha_registro`, 
`bk`, 
`puesto_trabajo`, 
`codigo_cliente`, 
`area_oficina`, 
`area_almacen`, 
`usuario_hc`, 
`flota`, 
`txt_actions`, 
`id_pregunta_grupo`, 
`txt_comentario`,
(SELECT descripcion FROM exa_temas WHERE id=`id_tema`) as tema,
(SELECT descripcion FROM exa_grupo_preguntas WHERE id=`id_grupo_preguntas`) as grupo_preguntas,
(SELECT descripcion FROM exa_preguntas WHERE id=`id_preguntas`) as preguntas,
(CASE
    WHEN `desc_resp` = '0 = No ha empezado' THEN 0
    WHEN `desc_resp` = '1 = Muy Pobre' THEN 1
    WHEN `desc_resp` = '2 = Mas o menos' THEN IF(id_grupo_preguntas=70,2*1.25,IF(id_grupo_preguntas=71,2*1.3,2))
	WHEN `desc_resp` = '3 = Bueno' THEN IF(id_grupo_preguntas=67,3*1.25,IF(id_grupo_preguntas=68,3*1.22,IF(id_grupo_preguntas=69,3*1.25,3))) 
	WHEN `desc_resp` = '4 = Excelente' THEN 4
    ELSE ''
END) AS respuesta_usuario
FROM `exa_detalle_checklist` 
WHERE id_tema=769341408 AND centro=:centro AND `st`=1 AND `respuesta_user`<>'' AND id_grupo_preguntas <> 66
) AS a group by 
a.grupo,
a.id_tema,
a.user_registro,
a.fecha_registro,
a.tema,
a.area_almacen,
a.usuario_hc
ORDER BY a.fecha_registro DESC
";	
	break;
	case 854630120:
	$sqltema ="
SELECT 
a.tema,
a.id_tema,
a.user_registro,
a.user_registro as auditor,
a.grupo,
a.fecha_registro,
a.flota as area,
sum(a.countcheck) as countpreguntas,
sum(a.respuesta_usuario) as respuesta_usuario,
sum(a.countcheck)*3 as countcheck,
round (sum(a.respuesta_usuario)/(sum(a.countcheck)*3)*100,2) as resultado
FROM 
(
SELECT
1 as countcheck, 
`id`, 
`id_tema`, 
`id_grupo_preguntas`,
`tipo_pregunta`, 
`id_respueta`,
`desc_resp`,
`respuesta_user`, 
`user_registro`, 
`grupo`, 
`fecha`, 
`empresa`, 
`fecha_registro`, 
`bk`, 
`puesto_trabajo`, 
`codigo_cliente`, 
`area_oficina`, 
`area_almacen`, 
`usuario_hc`, 
`flota`, 
`txt_actions`, 
`id_pregunta_grupo`, 
`txt_comentario`,
(SELECT descripcion FROM exa_temas WHERE id=`id_tema`) as tema,
(SELECT descripcion FROM exa_grupo_preguntas WHERE id=`id_grupo_preguntas`) as grupo_preguntas,
(SELECT descripcion FROM exa_preguntas WHERE id=`id_preguntas`) as preguntas,
(CASE
    WHEN (SELECT respuestas FROM exa_respuesta WHERE id=`id_respueta`) = '0 = No ok. Hay GAPs' THEN 0
    WHEN (SELECT respuestas FROM exa_respuesta WHERE id=`id_respueta`) = '1 = Necesita acciones para mejorar' THEN 1
	WHEN (SELECT respuestas FROM exa_respuesta WHERE id=`id_respueta`) = '3 = Ok.' THEN 3
    ELSE ''
END) AS respuesta_usuario
FROM `exa_detalle_checklist` 
WHERE id_tema=854630120 AND centro=:centro AND `st`=1 AND `respuesta_user`<>'' AND id_grupo_preguntas <> 56
) AS a group by 
a.tema,
a.id_tema,
a.user_registro,
a.grupo,
a.fecha_registro,
a.flota  
ORDER BY `respuesta_usuario`  DESC
	";	
	break;
    case 197358123:
	$sqltema ="
SELECT 
b.tema,
b.id_tema,
b.user_registro,
b.grupo,
b.fecha_registro,
b.area_oficina as area,
b.usuario_hc as auditor,
round ((2*(sum(b.Puntaje1)*0.2))+(2*(sum(b.Puntaje2)*0.8)),2)  as resultado
FROM 
(
SELECT 
a.tema,
a.id_tema,
a.id_grupo_preguntas,
a.grupo_preguntas,
a.id_preguntas, 
a.user_registro,
a.grupo,
a.fecha_registro,
a.area_oficina,
a.usuario_hc,
if(a.id_preguntas IN(319,320,321,322,323),a.respuesta_usuario,0) AS Puntaje1, 
if(a.id_preguntas IN(324,325,326,327,328),a.respuesta_usuario,0) AS Puntaje2, 
a.respuesta_usuario,
a.countcheck
FROM 
(
SELECT
1 as countcheck, 
`id`,
`id_tema`,
`id_grupo_preguntas`,
`id_preguntas`,
`tipo_pregunta`, 
`id_respueta`,
`desc_resp`,
`respuesta_user`, 
`user_registro`, 
`grupo`, 
`fecha`, 
`empresa`, 
`fecha_registro`, 
`bk`, 
`puesto_trabajo`, 
`codigo_cliente`, 
`area_oficina`, 
`area_almacen`, 
`usuario_hc`, 
`flota`, 
`txt_actions`, 
`id_pregunta_grupo`, 
`txt_comentario`,
(SELECT descripcion FROM exa_temas WHERE id=`id_tema`) as tema,
(SELECT descripcion FROM exa_grupo_preguntas WHERE id=`id_grupo_preguntas`) as grupo_preguntas,
(SELECT pregunta FROM exa_preguntas WHERE id=`id_preguntas`) as preguntas,
(CASE
    WHEN `desc_resp` = '0 = No ha empezado' THEN 0
    WHEN `desc_resp` = '1 = Muy Pobre' THEN 1
    WHEN `desc_resp` = '5 = Mas o menos' THEN 5
	WHEN `desc_resp` = '10 = Excelente' THEN 10
    ELSE ''
END) AS respuesta_usuario
FROM `exa_detalle_checklist` 
WHERE id_tema=197358123 AND centro=:centro AND `st`=1 AND `respuesta_user`<>'' AND id_grupo_preguntas <> 63 
) AS a 
) b GROUP BY
b.tema,
b.id_tema, 
b.user_registro,
b.grupo,
b.fecha_registro,
b.area_oficina,
b.usuario_hc	
	";	
	break;	
    case 446336597:
	$sqltema ="
SELECT `id_tema`, (SELECT descripcion FROM exa_temas WHERE id=`id_tema`) as tema, `fecha` as fecha_registro, `user_registro` as auditor , `codigo_cliente` as area , `grupo`,  'NA' as resultado  FROM `exa_detalle_checklist` 
WHERE id_tema=446336597 AND centro=:centro AND st = 1
GROUP BY  `id_tema`, `fecha`, `user_registro` , `codigo_cliente` , `grupo` 	
	";	
	break;
        // salida de camiones 
	case 143482211:
	$sqltema ="SELECT 
	s.id_tema,s.tema,s.centro,s.grupo,s.empresa as area,s.fecha_registro,s.user_registro,(SELECT CONCAT(apellidos,' ',nombre) FROM usuarios WHERE dni=s.user_registro) as auditor,
	round ((SUM(s.respuesta_user) / SUM(s.contador))*100,2) AS resultado
	FROM 
	(
	SELECT 
	(SELECT descripcion FROM `exa_temas` WHERE id=`id_tema`) as tema, id_preguntas,id_tema,
	(SELECT pregunta FROM `exa_preguntas` WHERE id=`id_preguntas`) as preguntas, 
	(SELECT respuestas FROM `exa_respuesta` WHERE id=`id_respueta`) as respuestas, 
	1 as contador,
	`respuesta_user`, `centro`, `placa`, `grupo`, `fecha`, `st`, `empresa`, `fecha_registro`, `bk`, 
	`puesto_trabajo`, `codigo_cliente`, `area_oficina`, `area_almacen`, `usuario_hc`, 
	`flota`, `txt_actions`, `txt_comentario`, `user_registro` 
	FROM `exa_detalle_checklist` 
	WHERE id_tema=143482211 
	AND id_grupo_preguntas <> 87 AND respuesta_user <> '' AND centro=:centro
	) AS s 
	GROUP BY 
	s.id_tema,s.tema,s.centro,s.grupo,s.fecha,s.empresa,s.fecha_registro,s.user_registro ORDER BY s.fecha_registro DESC 
	";	
	break;
        // llegada de camiones 
	case 777823101:
	$sqltema ="SELECT 
	s.id_tema,s.tema,s.centro,s.grupo,s.empresa as area,s.fecha_registro,s.user_registro,(SELECT CONCAT(apellidos,' ',nombre) FROM usuarios WHERE dni=s.user_registro) as auditor,
	round ((SUM(s.respuesta_user) / SUM(s.contador))*100,2) AS resultado
	FROM 
	(
	SELECT 
	(SELECT descripcion FROM `exa_temas` WHERE id=`id_tema`) as tema, id_preguntas,id_tema,
	(SELECT pregunta FROM `exa_preguntas` WHERE id=`id_preguntas`) as preguntas, 
	(SELECT respuestas FROM `exa_respuesta` WHERE id=`id_respueta`) as respuestas, 
	1 as contador,
	`respuesta_user`, `centro`, `placa`, `grupo`, `fecha`, `st`, `empresa`, `fecha_registro`, `bk`, 
	`puesto_trabajo`, `codigo_cliente`, `area_oficina`, `area_almacen`, `usuario_hc`, 
	`flota`, `txt_actions`, `txt_comentario`, `user_registro` 
	FROM `exa_detalle_checklist` 
	WHERE id_tema=777823101 
	AND id_grupo_preguntas <> 85 AND respuesta_user <> '' AND centro=:centro
	) AS s 
	GROUP BY 
	s.id_tema,s.tema,s.centro,s.grupo,s.fecha,s.empresa,s.fecha_registro,s.user_registro ORDER BY s.fecha_registro DESC 
	";		
		break;

	case 863144920:
    $sqltema ="SELECT 
    s.id_tema,s.tema,s.centro,s.grupo,s.empresa as area,s.fecha_registro,s.user_registro,(SELECT CONCAT(apellidos,' ',nombre) FROM usuarios WHERE dni=s.user_registro) as auditor,
    round ((SUM(s.respuesta_user) / SUM(s.contador))*100,2) AS resultado
    FROM 
    (
    SELECT 
    (SELECT descripcion FROM `exa_temas` WHERE id=`id_tema`) as tema, id_preguntas,id_tema,
    (SELECT pregunta FROM `exa_preguntas` WHERE id=`id_preguntas`) as preguntas, 
    (SELECT respuestas FROM `exa_respuesta` WHERE id=`id_respueta`) as respuestas, 
    1 as contador,
    `respuesta_user`, `centro`, `placa`, `grupo`, `fecha`, `st`, `empresa`, `fecha_registro`, `bk`, 
    `puesto_trabajo`, `codigo_cliente`, `area_oficina`, `area_almacen`, `usuario_hc`, 
    `flota`, `txt_actions`, `txt_comentario`, `user_registro` 
    FROM `exa_detalle_checklist` 
    WHERE id_tema=863144920 
    AND id_grupo_preguntas <> 83 AND respuesta_user <> '' AND centro=:centro
    ) AS s 
    GROUP BY 
    s.id_tema,s.tema,s.centro,s.grupo,s.fecha,s.empresa,s.fecha_registro,s.user_registro ORDER BY s.fecha_registro DESC 
    ";			
	break;
		

/////
    default:	
	$sqltema ="SELECT 
r.id_tema,r.tema,r.fecha_registro,r.user_registro,r.grupo,
(SELECT CONCAT(apellidos,' ',nombre) FROM usuarios WHERE dni=r.auditor) as auditor,
r.area,r.grupo,r.resultado 
FROM (
SELECT `id_tema`, (SELECT descripcion FROM exa_temas WHERE id=`id_tema`) as tema, `fecha` as fecha_registro, user_registro ,user_registro as auditor , `codigo_cliente` as area , `grupo`,  'NA' as resultado  FROM `exa_detalle_checklist` 
WHERE id_tema=$id AND centro=:centro AND st = 1
GROUP BY  `id_tema`, `fecha`, `user_registro` , `codigo_cliente` , `grupo` 
	) AS r ORDER BY r.fecha_registro DESC";				 
endswitch; 
		$dbtema=Db::conectar();
        $selecttema=$dbtema->prepare($sqltema);
		$selecttema->bindValue('centro',$idcentro);
		$selecttema->execute();
		$n=1;
		while ($registema=$selecttema->fetch()) {
$actioneliminar = "checklistrpt?exa=eliminarchecklist&amp;id_tema=".$registema['id_tema']."&amp;grupo=".$registema['grupo']."&amp;user_registro=".$registema['user_registro']."";			
		?>
		<tr>
		<td scope="row"><?php echo $n; ?> </td>
		<td><?php echo $registema['fecha_registro']; ?></td>
		<td><?php echo $registema['tema']; ?></td>
        <td><?php echo $registema['area']; ?></td>
		<td><?php echo $registema['auditor']; ?></td>
		<td><?php echo $registema['resultado']; ?></td>
		<td>  
	<button  type="button" class="btn btn-danger btn-sm" onclick="location.href='checklistrpt?exa=resultwatch&amp;id=<?php echo $registema['id_tema']; ?>&amp;clave=<?php echo $registema['grupo']; ?>';" >Ver</button>
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
function eliminar_ckl($grupo,$user_registro)
{ global $aid,$idcentro;
			$db=DB::conectar();
			$insert=$db->prepare('
		DELETE FROM `exa_detalle_checklist` WHERE grupo=:grupo AND user_registro=:user_registro AND centro=:centro
			');
			$insert->bindValue('grupo',$grupo);
			$insert->bindValue('user_registro',$user_registro);
			$insert->bindValue('centro',$idcentro);
			$insert->execute();
			Db::desconectar();
}
switch ($exa):
    case "crear_examen_check":
  //if(isset($_GET['idtema'],$_POST['conductor'])) { $id_tema = $_GET['idtema'];} else { $id_tema = 0; }	
	if(isset($_GET['idtema'])) { $id_tema = $_GET['idtema'];} else { $id_tema = 0; }
	$CountUserExa = $crudex -> contardb('id','exa_detalle_checklist','id_tema = '.$id_tema.' AND user_registro = "'.$aid.'" AND centro = "'.$idcentro.'" AND st = 0');		
	if($CountUserExa <= 0 ){
	$cuestiontb = $crudex -> sacarmonbredb('descripcion,tipo,estado','exa_temas','id='.$id_tema.''); 
    $tipo_tema=$cuestiontb[1];
    $desc_tema=$cuestiontb[0]; 	
	$estado_tema=$cuestiontb[2]; 
	$conductor = '';
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
			  $txt_actions = $rowres['txt_actions'];
    $crudex -> insertar_respuestas_checklist($id_tema,$tipo_tema,$id_grupo_preguntas,$id_preguntas,$tipo_pregunta,$id_respueta,$desc_resp,$puntos_pregunta,$doble_check,$orden_resp,$resp_correcta,$respuesta_user,$user_creador,$idcentro,$fecha_hora,$fecha_fin_user,$aid,$conductor,$aleatorio,$fecha,$txt_actions,$id_preguntas.$aleatorio);
		  }
		Db::desconectar();		  
	}
	header('Location: checklistrpt?exa=iniciar&id='.$id_tema.'');
	exit();
	} else {	
	header('Location: checklistrpt?exa=iniciar&id='.$id_tema.'');
	exit();
	}
    break;
    case "enviarchecklist":
	if(isset($_GET['grupo'],$_GET['id_tema'])) {
	$temarow = $crudex -> sacarmonbredb('id_area','exa_temas','id='.$_GET['id_tema'].''); 	
	$crudex ->finalizar_checklist($_GET['grupo'],$idcentro,$_GET['id_tema']); 
	?>
	<div class="alert alert-success" role="alert"> Se registro correctamente...  </div>
	<?php
    header('Refresh: 2; URL=checklistrpt?ckl='.$temarow[0].'');	
	} else {}
	break;
	
    case "result":
	$id=$_GET['id'];
	resultckl($id);
	break;	
	case "resultwatch":
	$id_tema=$_GET['id'];
	$clave=$_GET['clave'];
	form_examen_check_watch($id_tema,$clave);
	break;	
    case "iniciar":	
	if(isset($_POST['grupo'],$_POST['nameradiocheck'])){
	$grupo=$_POST['grupo'];
	foreach($_POST['nameradiocheck'] as $tipo_pregunta => $detalles)
	{
    foreach($detalles as $ids => $idsval)
	{
    if($tipo_pregunta==1 || $tipo_pregunta==3){ //radio buttons 
	$crudex ->grabar_respuesta_checklist(1,$fecha_hora,$idsval,$idcentro,$aid);   		
	} else if ($tipo_pregunta==2){ //check box  
	$crudex ->grabar_respuesta_checklist(1,$fecha_hora,$ids,$idcentro,$aid);	
	} else if ($tipo_pregunta==4 || $tipo_pregunta==7){ // radio buton si no multiple y  range multiple
	$crudex ->grabar_respuesta_checklist($idsval,$fecha_hora,$ids,$idcentro,$aid);	
	} else if ($tipo_pregunta==5 || $tipo_pregunta==6){ // texto y  textarea
	$crudex ->grabar_respuesta_checklist($idsval,$fecha_hora,$ids,$idcentro,$aid);	
	} else if ($tipo_pregunta==8){ //fecha head 
	$crudex ->grabar_respuesta_checklist($idsval,$fecha_hora,$ids,$idcentro,$aid);
	$crudex ->grabar_head_checklist('fecha_registro',$idsval,$grupo,$idcentro);	
	} else if ($tipo_pregunta==9){ //empresa head 
	$crudex ->grabar_respuesta_checklist($idsval,$fecha_hora,$ids,$idcentro,$aid);
    $crudex ->grabar_head_checklist('empresa',$idsval,$grupo,$idcentro);	
	} else if ($tipo_pregunta==10){ //bk head 
	$crudex ->grabar_respuesta_checklist($idsval,$fecha_hora,$ids,$idcentro,$aid);
    $crudex ->grabar_head_checklist('bk',$idsval,$grupo,$idcentro);   	
	} else if ($tipo_pregunta==11){ //puesto de trabajo  head 
	$crudex ->grabar_respuesta_checklist($idsval,$fecha_hora,$ids,$idcentro,$aid);
    $crudex ->grabar_head_checklist('puesto_trabajo',$idsval,$grupo,$idcentro);  	
	} else if ($tipo_pregunta==12){ //satisfaccion 1 al 5  
	$crudex ->grabar_respuesta_checklist(1,$fecha_hora,$idsval,$idcentro,$aid); 	
	} else if ($tipo_pregunta==13){ //codigo cliente head   
	$crudex ->grabar_respuesta_checklist($idsval,$fecha_hora,$ids,$idcentro,$aid);
    $crudex ->grabar_head_checklist('codigo_cliente',$idsval,$grupo,$idcentro); 	
	} else if ($tipo_pregunta==14){ //areas oficina head 
	$crudex ->grabar_respuesta_checklist($idsval,$fecha_hora,$ids,$idcentro,$aid);
    $crudex ->grabar_head_checklist('area_oficina',$idsval,$grupo,$idcentro);	
	} else if ($tipo_pregunta==15){ //areas almacen head 
	$crudex ->grabar_respuesta_checklist($idsval,$fecha_hora,$ids,$idcentro,$aid);
	$crudex ->grabar_head_checklist('area_almacen',$idsval,$grupo,$idcentro);	
	} else if ($tipo_pregunta==16){ //usuario HC head 
	$crudex ->grabar_respuesta_checklist($idsval,$fecha_hora,$ids,$idcentro,$aid);
    $crudex ->grabar_head_checklist('usuario_hc',$idsval,$grupo,$idcentro);	
	} else if ($tipo_pregunta==17){ //vehiculos head 
	$crudex ->grabar_respuesta_checklist($idsval,$fecha_hora,$ids,$idcentro,$aid);
    $crudex ->grabar_head_checklist('flota',$idsval,$grupo,$idcentro);	
	} else if ($tipo_pregunta==18){ //Select lista 5s
	$crudex ->grabar_respuesta_checklist($idsval,$fecha_hora,$idsval,$idcentro,$aid); 
	} else if ($tipo_pregunta==100){ //comentarios 
	$crudex ->grabar_comentario_checklist($idsval,$ids,$idcentro,$aid);
	}
	}
	}
	}	
	$id_tema = $_GET['id'];
	form_examen_check($id_tema);
	break;
	case "eliminarchecklist":
	if (isset($_GET['grupo'],$_GET['user_registro']) && ($aid == $_GET['user_registro'])) {
	eliminar_ckl($_GET['grupo'],$_GET['user_registro']);
	header('Refresh: 3; URL=checklistrpt?exa=result&id='.$_GET['id_tema'].'');
	echo "eliminado";
	} else {
	header('Refresh: 3; URL=checklistrpt?exa=result&id='.$_GET['id_tema'].'');	
	echo "ocurrio un error no eres el usuario creador";
	}
    break;
    default:
	?>
	<div class="row border">
    <div class="col-sm-12 bg-danger">
	<div class="d-flex justify-content-center">
	<div class="p-2 bd-highlight"><div class="text-white text-md-center font-weight-bolder">Check list</div></div>
	<div class="p-2 bd-highlight"></div>
	</div>
	</div>
	</div>
	<?php 
	 inicio_user_check_encuestas_examen($idcentro,$aid,$ckl);
	endswitch;
	} else {
     echo $html_acceso;		
	}
	}
	require('../footer.php');
	ob_end_flush();	
	?>