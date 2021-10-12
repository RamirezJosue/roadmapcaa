<?php 
    ob_start();	
    $accesos = basename(dirname(__FILE__));
	require_once('../../includes/ini.php');
	require_once('../../bd/crud_usuario.php');
	require_once('../../bd/crud_examen.php');
	require_once('../../bd/array/configexamen.php');
	$crud=new CrudUsuario();
	$crudex=new CrudExamen();
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
    if (isset($_GET['exa'])){ $exa = $_GET['exa']; } else { $exa = ""; }
function form_inicio($idcentro,$aid)
{	
?>
	<div class="container mb-2">
	<form method="get">
	<div class="row">
    <div class="col-sm">
	<select class="form-control" name="idtema" >
	<option value="">Seleccionar</option>
			<?php		
		    $db=Db::conectar();
		    $sql ="SELECT * FROM exa_temas WHERE centro=:centro AND id_user = :id_user ORDER BY descripcion ASC";
            $select=$db->prepare($sql);
		    $select->bindValue('centro',$idcentro);
			$select->bindValue('id_user',$aid);
		    $select->execute();
			while ($regis=$select->fetch()) {
			if ($regis['id'] == $_GET['idtema']){
			echo '<option  value="'.$regis['id'].'" selected >'.$regis['descripcion'].'</option>';		
			}else {
			echo '<option  value="'.$regis['id'].'" >'.$regis['descripcion'].'</option>';	
			}
			}
			Db::desconectar();
			?>
	</select>
    </div>
    <div class="col-sm">
    <button type="submit" class="btn btn-danger" >Seleccionar</button>
	<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#NuevoTemaForm">Agregar</button>
    </div>
	</div>
  	</form>
	</div>
<?php
}
function form_select($sql,$value)
{ global $idcentro;
?>
    <input type="hidden" class="form-control" id="txt1"  name="respuesta[]" value="<?php echo $value; ?>">
	<select class="form-control" >
	<option value="">Seleccionar</option>
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
function form_modal_tema($id_tema,$EditForm)
{ global $tipocuestionario,$cuestionariopara,$cuestionarioarea,$crudex,$idcentro,$aid;

if ($EditForm == true) {
$Countrespuesta = $crudex-> contardb('id','exa_respuesta','id_tema = '.$id_tema.'');	
if ($Countrespuesta == 0) { $disabled = ''; }else{ $disabled = 'disabled'; }  // si tiene respuestas no se puede modificar el campo tipo examen o cuestionario porque daria incosistencia 
$rowtema = $crudex -> sacarmonbredb(' * ','exa_temas','id='.$id_tema.'');
//$disabledcheck = '';
$idform = 'EditTemaForm'; 
$actionform = 'modificatema';
$tema_descripcion = $rowtema[2];
$tema_date_fin = substr($rowtema[4],0,10); 
$tema_time_fin = substr($rowtema[4],11,5); 
$tema_tipo = $rowtema[5]; 
$tema_para = $rowtema[7]; 
$id_area = $rowtema[9]; 
$estado = $rowtema[6];
} else { 
$idform = 'NuevoTemaForm';
$actionform = 'registratema';
$tema_descripcion = '';
$tema_fecha_fin = ''; 
$tema_tipo = ''; 
$tema_para = ''; 
$id_area = '';
$estado = 0;
$disabled = ''; 
}
?>	
<!-- Modal Inicio-->
	<div class="modal fade" id="<?php echo $idform;?>" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
	<!-- Modal content-->
	<div class="modal-content">
	<div class="modal-header text-white bg-danger">
	<h5 class="modal-title"><?php echo $tema_descripcion; ?></h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
    </button>
	</div>
	<div class="modal-body">
	<?php
	if ($estado==1) { $activado='disabled'; 
	?>
    <div class="alert alert-danger" role="alert">
	<h4 class="alert-heading">No es posible modificar!</h4>
	<p>El tema se encuentra activado / publicado</p>
	</div>
	<?php
	} else { $activado=''; }
	?>
    <form class="needs-validation" name="<?php echo $idform; ?>" action ="adminexamen?exa=<?php echo $actionform; ?>" method="post" novalidate>
    <div class="form-row">
    <div class="col-md-12">
      <label for="validationCustom01">Descripcion</label>
      <input type="text" class="form-control" id="validationCustom01" name="tema_descripcion"  value="<?php echo $tema_descripcion; ?>" required>
      <div class="invalid-feedback">
        Completar.
      </div>
    </div>
	</div>
	<div class="form-row">
    <div class="col-6">
      <label for="validationCustom02">Fecha fin</label>
      <input type="date" class="form-control" id="validationCustom02" value="<?php echo $tema_date_fin; ?>" name="tema_date_fin" required>
      <div class="valid-feedback">
        Correcto!
      </div>
    </div>
	 <div class="col-6">
      <label for="validationCustom13">Hora fin</label>
      <input type="time" class="form-control" id="validationCustom13" value="<?php echo $tema_time_fin; ?>" name="tema_time_fin" required>
      <div class="valid-feedback">
        Correcto!
      </div>
    </div>
	</div>

  <div class="form-row">
    <div class="col-md-6">
    <label for="validationCustom03">Tipo</label>
	<select class="custom-select" id="validationCustom03" name="tema_tipo" required>
	<option selected disabled value="">Seleccionar...</option>
			<?php		   
	foreach($tipocuestionario as $idcuestion=>$descuestion)
	{
			if ($idcuestion == $tema_tipo){
			echo '<option  value="'.$idcuestion.'" selected '.$disabled.' >'.$descuestion.'</option>';		
			}else {
			echo '<option  value="'.$idcuestion.'" '.$disabled.' >'.$descuestion.'</option>';	
			}
	}
	?>
	</select>
	<div class="invalid-feedback">
        Completar.
    </div>
    </div>
    <div class="col-md-6">
      <label for="validationCustom04">Para</label> 
		<select class="custom-select" id="validationCustom04" name="tema_para" required>
		<option selected disabled value="">Seleccionar...</option>
			<?php		   
	foreach($cuestionariopara as $idcuestionpara=>$descuestionpara)
	{
			if ($idcuestionpara == $tema_para){
			echo '<option  value="'.$idcuestionpara.'" selected >'.$descuestionpara.'</option>';		
			}else {
			echo '<option  value="'.$idcuestionpara.'" >'.$descuestionpara.'</option>';	
			}
	}
	?>
		</select>
		<div class="invalid-feedback">
        Completar.
		</div>
    </div>
  </div>
  <div class="form-row">
      <div class="col-md-6">
      <label for="validationCustom05">Area</label>
		<select class="custom-select" id="validationCustom05" name="id_area" required>
		<option selected disabled value="">Seleccionar...</option>
			<?php		   
	foreach($cuestionarioarea as $idcuestionarea=>$descuestionarea)
	{
			if ($idcuestionarea == $id_area){
			echo '<option  value="'.$idcuestionarea.'" selected >'.$descuestionarea.'</option>';		
			}else {
			echo '<option  value="'.$idcuestionarea.'" >'.$descuestionarea.'</option>';	
			}
	}
	?>
		</select>
      <div class="invalid-feedback">
        Completar.
      </div>
    </div>
    <div class="col-md-6">
    <label >Estado</label> 
	<div class="custom-control custom-switch">
    <input type="checkbox" class="custom-control-input" id="customSwitch1" name="tema_activo" <?php if($estado==1){ echo 'checked'; }?> >
    <label class="custom-control-label" for="customSwitch1">Off / On</label>
    </div>
	</div>
    </div>
	<div class="modal-footer">
	<button type="submit" class="btn btn-secondary btn-lg btn-block" <?php echo $activado; ?> >Guardar</button>
	<button type="button"  class="btn btn-danger btn-lg btn-block" data-dismiss="modal">Cerrar</button>
	</div>
	<input type="hidden" name="id_tema" value="<?php echo $id_tema; ?>" >
	</form>
	 </div>
	</div>
	</div>
	</div>
<?php
}

function form_modal_eliminar($id,$tabla,$estado_tema)
{ global $tipocuestionario,$cuestionariopara,$cuestionarioarea,$crudex,$idcentro,$aid;
//inicio eliminar tema
if ($tabla == 'exa_temas') {
	$id_modal = 'EliminarTema';
	$id_tema = '';
	$descripcion_titulo = $crudex -> sacarmonbredb('descripcion','exa_temas','id='.$id.'');
	$Countgrupo_pre = $crudex-> contardb('id','exa_grupo_preguntas','id_tema = '.$id.' AND centro = "'.$idcentro.'" AND user = "'.$aid.'"');  //
	$Countpreguntas = $crudex-> contardb('id','exa_preguntas','id_tema = '.$id.' AND centro = "'.$idcentro.'" AND user = "'.$aid.'"');  //
	$Countrespuesta = $crudex-> contardb('id','exa_respuesta','id_tema = '.$id.' AND centro = "'.$idcentro.'" AND user = "'.$aid.'"');  //
if ($Countgrupo_pre == 0 && $Countpreguntas == 0 && $Countrespuesta == 0 && $estado_tema == 0) {
	$disabled = ''; //se puede borrar
	$id_eliminar = $id;
	$eliminamsj1 = 'Esta seguro de Eliminar!';
	$eliminamsj2 = $descripcion_titulo[0];
	} else {
	$disabled = 'disabled';  // no se puede borrar existen registros subsiguientes o esta activo el tema
	$id_eliminar = $id;
	$eliminamsj1 = 'No se puede borrar!';
	$eliminamsj2 = 'Existen registros subsiguientes o se encuentra activo "'.$descripcion_titulo[0].'"';
	}
	}
//fin eliminar tema	
//inicio eliminar respuesta
	else if($tabla == 'exa_respuesta'){
	$id_modal = 'EliminarRespuesta';
	$descripcion_titulo = $crudex -> sacarmonbredb('pregunta,id_tema','exa_preguntas','id='.$id.'');
	$id_tema = $descripcion_titulo[1];
	$eliminamsj1 = 'Se eliminaran todas las respuestas de : ';
	$eliminamsj2 = $descripcion_titulo[0];
	$id_eliminar = $id;
	if($estado_tema == 0){ $disabled=''; } else { $disabled='disabled'; }
	}
//fin eliminar respuesta
//inicio eliminar pregunta
	else if ($tabla == 'exa_pregunta') {
	$id_modal = 'EliminarPregunta';
	$id_eliminar = $id;
	$descripcion_titulo = $crudex -> sacarmonbredb('pregunta,id_tema','exa_preguntas','id='.$id.'');
    $id_tema = $descripcion_titulo[1];		
	$Countrespuesta = $crudex-> contardb('id','exa_respuesta','id_pregunta = '.$id.' AND centro = "'.$idcentro.'" AND user = "'.$aid.'"');  // verificamos si existen respuestas  indexadas con las pregunrtas	
	if ($Countrespuesta == 0 && $estado_tema == 0) {
    $eliminamsj1 = 'Esta seguro de borrar!';
	$eliminamsj2 = '"'.$descripcion_titulo[0].'"';
	$disabled = '';
    } else {
	$eliminamsj1 = 'No se puede Borrar!';	
    $eliminamsj2 = 'Existen registros subsiguientes ó esta activado "'.$descripcion_titulo[0].'"';	
    $disabled = 'disabled';	 
	}		
	} 
//fin eliminar pregunta	
	else if ($tabla == 'exa_grupo_preguntas') {
	$descripcion_titulo = $crudex -> sacarmonbredb('descripcion,id_tema','exa_grupo_preguntas','id='.$id.'');	
	$Countrespuesta = $crudex-> contardb('id','exa_respuesta','id_grupo_preguntas = '.$id.' AND centro = "'.$idcentro.'" AND user = "'.$aid.'"');  // verificamos si existen respuestas  indexadas con las pregunrtas	
	$Countpreguntas = $crudex-> contardb('id','exa_preguntas','id_grupo_preguntas = '.$id.' AND centro = "'.$idcentro.'" AND user = "'.$aid.'"');  // verificamos si existen respuestas  indexadas con las pregunrtas	
    
	if ($Countrespuesta == 0 && $Countpreguntas == 0 && $estado_tema == 0) {
    $eliminamsj1 = 'Esta seguro de borrar!';
	$eliminamsj2 = '"'.$descripcion_titulo[0].'"';
	$disabled = '';
	} else {
	$eliminamsj1 = 'No se puede Borrar!';	
    $eliminamsj2 = 'Existen registros subsiguientes ó esta activado "'.$descripcion_titulo[0].'"';	
    $disabled = 'disabled';			
	} 
	$id_tema = $descripcion_titulo[1];
	$id_modal = 'EliminarGrupoPregunta';
    $id_eliminar = $id;	
	
	} else {}

?>	
<!-- Modal Inicio-->
	<div class="modal fade" id="<?php echo $id_modal.$id_eliminar; ?>" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
	<!-- Modal content-->
	<div class="modal-content">
	<div class="modal-header text-white bg-danger">
	<h5 class="modal-title"><?php echo $descripcion_titulo[0]; ?></h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
    </button>
	</div>
	<form action ="adminexamen?exa=<?php echo $id_modal; ?>" method="post" >
	<div class="modal-body">
	<div class="alert alert-danger" role="alert">
	<h4 class="alert-heading"><?php echo $eliminamsj1; ?></h4>
	<p><?php echo $eliminamsj2; ?></p>
	</div>
    </div>
	<div class="modal-footer">
	<button type="submit" class="btn btn-secondary btn-lg btn-block" <?php echo $disabled; ?> >Eliminar</button>
	<input type="hidden" name="id_<?php echo $tabla; ?>" value="<?php echo $id_eliminar; ?>" >
	<input type="hidden" name="id_tema" value="<?php echo $id_tema; ?>" >
	</form>
	<button type="button"  class="btn btn-danger btn-lg btn-block" data-dismiss="modal">Cerrar</button>
	</div>
	</div>
	</div>
	</div>
	
<?php
}


function form_modal_preguntas($id_pregunta,$id_tema,$id_grupo,$EditForm,$estado_tema)
{ global $crudex,$idcentro,$aid,$tipopregunta,$puntospregunta;


if ($EditForm == true) {
	$Countrespuesta = $crudex-> contardb('id','exa_respuesta','id_pregunta = '.$id_pregunta.' AND centro = "'.$idcentro.'" AND user = "'.$aid.'"');  //
	$rowpregunta = $crudex -> sacarmonbredb(' * ','exa_preguntas','id='.$id_pregunta.''); // id_tema es id_pregunta 
	$idform='ModificaPregunta';
	$id=$id_pregunta;
	$pregunta = $rowpregunta[3];
	$descripcion = $rowpregunta[10];;
	$id_tipo_pregunta = $rowpregunta[5];
	$puntos = $rowpregunta[6];
	$txt_header_modal = $rowpregunta[3];
	if($rowpregunta['txt_actions']==1){ $checked='checked'; } else { $checked=''; }
	if($rowpregunta['doblecheck']==1){ $checkeddoble='checked'; } else { $checkeddoble=''; } 
	
} else {
   $Countrespuesta = 0; 	
   $descpregunta = $crudex -> sacarmonbredb('descripcion','exa_grupo_preguntas','id='.$id_grupo.'');
   $idform='RegistraPregunta'; 
   $id=$id_grupo;
   $pregunta = '';
   $descripcion = '';
   $id_tipo_pregunta = '';
   $puntos = '';
   $txt_header_modal = $descpregunta[0];
   $checked='';
   $checkeddoble = '';
}  
?>	
<!-- Modal Inicio-->
	<div class="modal fade" id="<?php echo $idform.$id; ?>" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
	<!-- Modal content-->
	<div class="modal-content">
	<div class="modal-header text-white bg-danger">
	<h5 class="modal-title"> <?php echo $txt_header_modal.'---'.$Countrespuesta; ?> </h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
    </button>
	</div>
	<div class="modal-body">
    <?php
	if ($Countrespuesta > 0 || $estado_tema == 1) { $activado='disabled'; 
	?>
    <div class="alert alert-danger" role="alert">
	<h4 class="alert-heading">No es posible modificar!</h4>
	<p>La pregunta tiene respuestas o se encuentra activado</p>
	</div>
	<?php
	} else { $activado=''; }
	?>
    <form class="needs-validation"  action ="adminexamen?exa=<?php echo $idform; ?>" method="post" novalidate>
    <div class="form-row">
    <div class="col-md-12">
      <label for="validationCustom01"><p class="text-muted">Pregunta</p></label>
      <input type="text" class="form-control" id="validationCustom01" name="pregunta" value="<?php echo $pregunta; ?>"required>
	  <div class="invalid-feedback">
        Completar.
      </div>
	</div>	
    <div class="col-md-12">
      <label for="validationCustom01"><p class="text-muted">Descripcion</p></label>
      <input type="text" class="form-control" id="validationCustom01" name="descripcion" value="<?php echo $descripcion; ?>"required>
	  <div class="invalid-feedback">
        Completar.
      </div>
	</div>		
	<div class="col-md-12">
      <label for="validationCustom04"><p class="text-muted">Tipo Pregunta</p></label>  
	  
 	<select class="custom-select" id="validationCustom04"  name="id_tipo_pregunta" required>
	<option selected disabled value="">Seleccionar...</option>
			<?php		   
	foreach($tipopregunta as $idtipopre=>$desctipopre)
	{
			if ($idtipopre == $id_tipo_pregunta){
			echo '<option  value="'.$idtipopre.'" selected >'.$desctipopre.'</option>';		
			}else {
			echo '<option  value="'.$idtipopre.'" >'.$desctipopre.'</option>';	
			}
	}
	?>
	</select>
      <div class="invalid-feedback">
        Completar.
      </div>
    </div>
	<div class="col-md-12">
      <label for="validationCustom04"><p class="text-muted">Puntos</p></label>  
	  
 	<select class="custom-select" id="validationCustom04"  name="puntos" required>
	<option selected disabled value="">Seleccionar...</option>
			<?php		   
	foreach($puntospregunta as $idpuntospre=>$descpuntospre)
	{
			if ($idpuntospre == $puntos){
			echo '<option  value="'.$idpuntospre.'" selected >'.$descpuntospre.'</option>';		
			}else {
			echo '<option  value="'.$idpuntospre.'" >'.$descpuntospre.'</option>';	
			}
	}
	?>
	</select>
    <div class="invalid-feedback">
        Completar.
    </div>
    </div>
	
<div class="form-group">
    <div class="form-check">
      <input class="form-check-input" type="checkbox" id="gridCheck" name="txt_actions" <?php echo $checked; ?> >
      <label class="form-check-label" for="gridCheck"> <p class="text-muted">
        Activar comentario en la pregunta  
     </p> </label>
    </div>
</div>

<div class="form-group">
    <div class="form-check">
      <input class="form-check-input" type="checkbox" id="gridCheck" name="doblecheck" <?php echo $checkeddoble; ?> >
      <label class="form-check-label" for="gridCheck"> <p class="text-muted">
        Marcar la pregunta como doblecheck 
     </p> </label>
    </div>
</div>
	  <input type="hidden" name="id_tema" value="<?php echo $id_tema; ?>" >
	   <input type="hidden" name="id_grupo" value="<?php echo $id_grupo; ?>" >
	    <input type="hidden" name="id_pregunta" value="<?php echo $id; ?>" >
    </div>

	<div class="modal-footer">
	<button type="submit" class="btn btn-secondary btn-lg btn-block" <?php echo $activado; ?> >Guardar</button>
	<button type="button"  class="btn btn-danger btn-lg btn-block" data-dismiss="modal">Cerrar</button>
	</div>
	</form>
	 </div>
	</div>
	</div>
	</div>
<?php
}
function form_modal_seccion($id_tema,$id_grupo_preguntas,$estado_tema,$EditForm)
{ global $crudex,$idcentro,$aid;
$tema_txt = $crudex -> sacarmonbredb('descripcion','exa_temas','id='.$id_tema.'');
if ($EditForm == true) {
	$rowGrupoPreg = $crudex -> sacarmonbredb(' * ','exa_grupo_preguntas','id='.$id_grupo_preguntas.'');
	$idform = 'ModificaGrupoPreguntas'.$id_grupo_preguntas;
	$TxtGrupoPreg = $rowGrupoPreg[2];
	$actionForm = 'modificagrupo'; 	
	$CountPreguntas = $crudex-> contardb('id','exa_preguntas','id_grupo_preguntas = '.$id_grupo_preguntas.'');	
	$CountRespuestas = $crudex-> contardb('id','exa_respuesta','id_grupo_preguntas = '.$id_grupo_preguntas.'');	
} else {	
    $disabled = ''; 
	$idform = 'NuevoGrupoPreguntas';
	$actionForm = 'registragrupo'; 
	$TxtGrupoPreg = '';
	$CountPreguntas = 0;
	$CountRespuestas = 0;
}

?>	
<!-- Modal Inicio-->
	<div class="modal fade" id="<?php echo $idform; ?>" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
	<!-- Modal content-->
	<div class="modal-content">
	<div class="modal-header">
	<h5 class="modal-title"> <?php echo $tema_txt[0]; ?> </h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
    </button>
	</div>
	<div class="modal-body">
	<?php
	if ($CountPreguntas == 0 && $CountRespuestas == 0 && $estado_tema == 0) {  $disabled=''; 
	} else { $disabled=''; 
	?>
    <div class="alert alert-danger" role="alert">
	<h4 class="alert-heading">!Esta seguro de modificar !</h4>
	<p>Recuerde que esta sección tiene registros siguientes o esta activado / publicado</p>
	</div>
	<?php
	}
	?>
    <form class="needs-validation"  action ="adminexamen?exa=<?php echo $actionForm; ?>" method="post" novalidate>
    <div class="form-row">
    <div class="col-md-12">
      <label for="validationCustom01">Descripcion grupo de preguntas</label>
      <input type="text" class="form-control" id="validationCustom01" name="grupo_preguntas" value="<?php echo $TxtGrupoPreg; ?>" required>
	  <input type="hidden" name="id_tema" value="<?php echo $id_tema; ?>" >
	  <input type="hidden" name="id_centro" value="<?php echo $idcentro; ?>" >
	  <input type="hidden" name="id_user" value="<?php echo $aid; ?>" >
	  <input type="hidden" name="id_grupo_preguntas" value="<?php echo $id_grupo_preguntas; ?>" >
      <div class="invalid-feedback">
        Completar.
      </div>
    </div>
    </div>

	<div class="modal-footer">
	<button type="submit" class="btn btn-secondary btn-lg btn-block" <?php echo $disabled; ?> >Guardar</button>
	<button type="button"  class="btn btn-danger btn-lg btn-block" data-dismiss="modal">Cerrar</button>
	</div>
	</form>
	 </div>
	</div>
	</div>
	</div>
<?php
}
function form_modal_agregar_respuetas($id_tema,$id_grupo_preguntas,$id_pregunta,$tipo_tema)
{ global $crudex,$idcentro,$aid,$tipopregunta;
$arraytdpreg = $crudex -> sacarmonbredb('pregunta,tipo_pregunta,txt_actions,doblecheck','exa_preguntas','id='.$id_pregunta.'');
$descpregunta = $arraytdpreg[0];
$tipo_pregunta = $arraytdpreg[1];
$txt_actions = $arraytdpreg[2];
$doblecheck = $arraytdpreg[3];  //casilla de verificacion , seleccion multiple ...

?>	
<!-- Modal Inicio-->
	<div class="modal fade" id="AgregarRespuestas<?php echo $id_pregunta; ?>" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
	<!-- Modal content-->
	<div class="modal-content">
	<div class="modal-header text-white bg-danger">
	<h5 class="modal-title"> <?php echo ucfirst($descpregunta).'-'.$tipopregunta[$tipo_pregunta];?></h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
    </button>
	</div>
	<div class="modal-body text-dark">
    <form class="needs-validation"  action ="adminexamen?exa=registraespuesta" method="post" novalidate>
    <div class="form-row">
    <?php
	switch ($tipo_pregunta):
    case 1: 	
   ?>
    <div class="col-12">
    <input type="button" id="add_field<?php echo $id_pregunta; ?>" value="Adicionar">
	<div class="form-check">
	<input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios1" value="option1" checked>
	<label class="form-check-label" for="exampleRadios1">
    Respuesta
	</label>
	</div>
	<br>
	<div id="listas<?php echo $id_pregunta; ?>">
    <div><input type="text" name="respuesta[]" placeholder="Texto 1"></div>
	</div>
	</div>
	<?php
	if ($tipo_tema==2) { // 1 == custionario -> 2  == examen
	?>
	<div class="col-md-12">
    <label for="validationCustom04">Respuesta Correcta</label>
    <select class="custom-select" id="validationCustom04"  name="res_correcta" required>
    <option selected disabled value="">Seleccionar...</option>
    <option value="0">A</option>
	<option value="1">B</option>
	<option value="2">C</option>
	<option value="3">D</option>
	<option value="4">E</option>
    </select>
    <div class="invalid-feedback">Completar.</div>
    </div>
	<?php
    } else { 
	?> 
	<input type="hidden" name="res_correcta" value="0" > 
	<input type="hidden" name="res_multiple" value="0" >
	<input type="hidden" name="doblecheck" value="<?php echo $doblecheck; ?>" >
	<input type="hidden" name="txt_actions" value="<?php echo $txt_actions; ?>" >	
	<?php 
	}
    break;
    case 2: 	
	?>
	<div class="form-check">
    <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
    <label class="form-check-label" for="defaultCheck1">
    Default checkbox
    </label>
	</div>
	<div class="col-md-12">
	<input type="button" id="add_field<?php echo $id_pregunta; ?>" value="Adicionar">
	<br>
	<div id="listas<?php echo $id_pregunta; ?>">
    <div><input type="text" name="respuesta[]" placeholder="Pregunta 1 si/no"></div>
	</div>
    </div>
	<input type="hidden" name="res_correcta" value="0" > 
	<input type="hidden" name="res_multiple" value="0" >
	<input type="hidden" name="doblecheck" value="<?php echo $doblecheck; ?>" >
	<input type="hidden" name="txt_actions" value="<?php echo $txt_actions; ?>" >	
	<?php
    break;	
    case 3: 	
	?>
	<div class="col-md-12">
    <div class="form-group row">
	<label for="colFormLabelSm" class="col-sm-2 col-form-label col-form-label-sm">A:</label> 
	<div class="col-sm-10">
	<input type="text" class="form-control form-control-sm" id="validationCustom01" name="respuesta[]" for="exampleRadios1" placeholder="Respuesta 1" value="Si" required>
	<div class="invalid-feedback">
    Completar.
	</div>
	</div>
    </div>
    <div class="form-group row">
	<label for="colFormLabelSm" class="col-sm-2 col-form-label col-form-label-sm">B:</label> 
	<div class="col-sm-10">
	<input type="text" class="form-control form-control-sm" id="validationCustom02" name="respuesta[]" for="exampleRadios1" placeholder="Respuesta 2" value="No" required>
	<div class="invalid-feedback">
    Completar.
	</div>
	</div>
    </div>	
    </div>
	<?php
	if ($tipo_tema==2){ // 1 == custionario -> 2  == examen
	?>
	<div class="col-md-12">
    <label for="validationCustom04">Respuesta Correcta</label>
    <select class="custom-select" id="validationCustom04"  name="res_correcta" required>
    <option selected disabled value="">Seleccionar...</option>
    <option value="0">A</option>
	<option value="1">B</option>
    </select>
    <div class="invalid-feedback">
        Completar.
    </div>
    </div>
	<?php
    } else { 
	?> 
	<input type="hidden" name="res_correcta" value="0" > 
	<input type="hidden" name="res_multiple" value="0" >
	<input type="hidden" name="doblecheck" value="<?php echo $doblecheck; ?>" >
	<input type="hidden" name="txt_actions" value="<?php echo $txt_actions; ?>" >	
	<?php 
	}
    break;	
    case 4: 	
	?>	
	<div class="col-md-12">
	<input type="button" id="add_field<?php echo $id_pregunta; ?>" value="Adicionar">
	<br>
	<div id="listas<?php echo $id_pregunta; ?>">
    <div><input type="text" name="respuesta[]" placeholder="Pregunta 1 si/no"></div>
	</div>
    </div>
	<input type="hidden" name="res_correcta" value="0" > 
	<input type="hidden" name="res_multiple" value="0" >
	<input type="hidden" name="doblecheck" value="<?php echo $doblecheck; ?>" >
	<input type="hidden" name="txt_actions" value="<?php echo $txt_actions; ?>" >	
	<?php 	
    break;	
    case 5: 	
	?>	
	<div class="form-group row">
    <label for="txt1" class="col-2 col-form-label">1.</label>
    <div class="col-10">
    <input type="text" class="form-control" id="txt1"  name="respuesta[]" value="<?php echo $descpregunta; ?>" placeholder="Ingrese titulo de texto">
    </div>
	</div>
	<input type="hidden" name="res_correcta" value="0" > 
	<input type="hidden" name="res_multiple" value="0" >
	<input type="hidden" name="doblecheck" value="<?php echo $doblecheck; ?>" >
	<input type="hidden" name="txt_actions" value="<?php echo $txt_actions; ?>" >	
    <?php 
    break;
    case 6: 	
	?>
	<div class="form-group row">	
	<label for="validationTextarea"><?php echo $descpregunta; ?>"</label>
    <textarea class="form-control" cols="40" rows="5" ></textarea>
	<input type="hidden" name="res_correcta" value="0" > 
	<input type="hidden" name="res_multiple" value="0" >
	<input type="hidden" name="doblecheck" value="<?php echo $doblecheck; ?>" >
	<input type="hidden" name="txt_actions" value="<?php echo $txt_actions; ?>" >	
    <input type="hidden" name="respuesta[]" value="<?php echo  htmlspecialchars($descpregunta,ENT_QUOTES,'UTF-8'); ?>" >	
	</div>
    <?php
    break;
    case 7: 	
	?>	
	<div class="col-12">
    <input type="button" id="add_field<?php echo $id_pregunta; ?>" value="Adicionar">
	<input type="range" class="form-control-range" id="formControlRange">
	<br>
	<div id="listas<?php echo $id_pregunta; ?>">
    <div><input type="text" name="respuesta[]" placeholder="Pregunta 1"></div>
	</div>
	</div>
	<input type="hidden" name="res_correcta" value="0" > 
	<input type="hidden" name="res_multiple" value="0" >
	<input type="hidden" name="doblecheck" value="<?php echo $doblecheck; ?>" >
	<input type="hidden" name="txt_actions" value="<?php echo $txt_actions; ?>" >	
    <?php 
    break;
    case 8: 	
	?>	
	<div class="form-group row">
    <label for="txt1" class="col-2 col-form-label">1.</label>
    <div class="col-10">
    <input type="text" class="form-control" id="txt1"  name="respuesta[]" value="<?php echo $descpregunta; ?>" placeholder="Fecha">
    </div>
	</div>
	<input type="hidden" name="res_correcta" value="0" > 
	<input type="hidden" name="res_multiple" value="0" >
	<input type="hidden" name="doblecheck" value="<?php echo $doblecheck; ?>" >
	<input type="hidden" name="txt_actions" value="<?php echo $txt_actions; ?>" >	
    <?php
    break;
    case 9: 	
	?>	
	<div class="form-group row">
    <label for="txt1" class="col-2 col-form-label">1.</label>
    <div class="col-10">
	<?php form_select("SELECT empresa,empresa FROM t77_em WHERE centro='$idcentro' GROUP BY empresa",$descpregunta); ?>	
    </div>
	</div>
	<input type="hidden" name="res_correcta" value="0" > 
	<input type="hidden" name="res_multiple" value="0" >
	<input type="hidden" name="doblecheck" value="<?php echo $doblecheck; ?>" >
	<input type="hidden" name="txt_actions" value="<?php echo $txt_actions; ?>" >	
    <?php	
    break;
    case 10: 	
	?>	
	<div class="form-group row">
    <label for="txt1" class="col-2 col-form-label">1.</label>
    <div class="col-10">
	<?php form_select("SELECT ruta,ruta FROM t77_em WHERE centro='$idcentro' GROUP BY ruta",$descpregunta); ?>
    </div>
	</div>
	<input type="hidden" name="res_correcta" value="0" >
	<input type="hidden" name="res_multiple" value="0" >
	<input type="hidden" name="doblecheck" value="<?php echo $doblecheck; ?>" >
	<input type="hidden" name="txt_actions" value="<?php echo $txt_actions; ?>" >
    <?php	
    break;
    case 11: 	
	?>	
	<div class="form-group row">
    <label for="txt1" class="col-2 col-form-label">1.</label>
    <div class="col-10">
	<?php form_select("SELECT descripcion,descripcion FROM usuario_puesto",$descpregunta); ?>
    </div>
	</div>
	<input type="hidden" name="res_correcta" value="0" > 
	<input type="hidden" name="res_multiple" value="0" >
	<input type="hidden" name="doblecheck" value="<?php echo $doblecheck; ?>" >
	<input type="hidden" name="txt_actions" value="<?php echo $txt_actions; ?>" >	
    <?php	
    break;
    case 12: 	
	?>	
	<div class="col-md-12">
	<br>
    <input type="text" name="respuesta[]" value="Nada Satisfecho"><br>
	<input type="text" name="respuesta[]" value="Insatisfecho"><br>
	<input type="text" name="respuesta[]" value="Indiferente"><br>
	<input type="text" name="respuesta[]" value="Satisfecho"><br>
	<input type="text" name="respuesta[]" value="Muy Satisfecho"><br>
    </div>
	<input type="hidden" name="res_correcta" value="0" > 
	<input type="hidden" name="res_multiple" value="0" >
	<input type="hidden" name="doblecheck" value="<?php echo $doblecheck; ?>" >
	<input type="hidden" name="txt_actions" value="<?php echo $txt_actions; ?>" >
    <?php	
    break;
    case 13: 	
	?>	
	<div class="form-group row">
    <label for="txt1" class="col-2 col-form-label">1.</label>
    <div class="col-10">
    <input type="text" class="form-control" id="txt1"  name="respuesta[]" value="<?php echo $descpregunta; ?>" placeholder="Codigo cliente">
    </div>
	</div>
	<input type="hidden" name="res_correcta" value="0" > 
	<input type="hidden" name="res_multiple" value="0" >
	<input type="hidden" name="doblecheck" value="<?php echo $doblecheck; ?>" >
	<input type="hidden" name="txt_actions" value="<?php echo $txt_actions; ?>" >	
    <?php	
    break;
    case 14: 	
	?>	
	<div class="form-group row">
    <label for="txt1" class="col-2 col-form-label">1.</label>
    <div class="col-10">
	<?php form_select("SELECT descripcion,descripcion FROM exa_area_5s WHERE area=2",$descpregunta); ?>
    </div>
	</div>
	<input type="hidden" name="res_correcta" value="0" > 
	<input type="hidden" name="res_multiple" value="0" >
	<input type="hidden" name="doblecheck" value="<?php echo $doblecheck; ?>" >
	<input type="hidden" name="txt_actions" value="<?php echo $txt_actions; ?>" >	
    <?php	
    break;
    case 15: 	
	?>	
	<div class="form-group row">
    <label for="txt1" class="col-2 col-form-label">1.</label>
    <div class="col-10">
	<?php form_select("SELECT descripcion,descripcion FROM exa_area_5s WHERE area=1",$descpregunta); ?>
    </div>
	</div>
	<input type="hidden" name="res_correcta" value="0" > 
	<input type="hidden" name="res_multiple" value="0" >
	<input type="hidden" name="doblecheck" value="<?php echo $doblecheck; ?>" >
	<input type="hidden" name="txt_actions" value="<?php echo $txt_actions; ?>" >	
     <?php	
    break;
    case 16: 	
	?>	
	<div class="form-group row">
    <label for="txt1" class="col-2 col-form-label">1.</label>
    <div class="col-10">
	<?php form_select("SELECT CONCAT(apellidos,' ',nombre) as usuarios ,CONCAT(apellidos,' ',nombre) as usuario FROM usuarios WHERE centro='$idcentro' AND puesto NOT IN (29,28) AND estado=1 ORDER BY apellidos",$descpregunta); ?>
    </div>
	</div>
	<input type="hidden" name="res_correcta" value="0" > 
	<input type="hidden" name="res_multiple" value="0" >
	<input type="hidden" name="doblecheck" value="<?php echo $doblecheck; ?>" >
	<input type="hidden" name="txt_actions" value="<?php echo $txt_actions; ?>" >	
    <?php	
    break;
	case 17: 
	?>	
	<div class="form-group row">
    <label for="txt1" class="col-2 col-form-label">1.</label>
    <div class="col-10">
	<?php form_select("SELECT placa,placa FROM t77_vehiculos WHERE centro='$idcentro'",$descpregunta); ?>
    </div>
	</div>
	<input type="hidden" name="res_correcta" value="0" > 
	<input type="hidden" name="res_multiple" value="0" >
	<input type="hidden" name="doblecheck" value="<?php echo $doblecheck; ?>" >
	<input type="hidden" name="txt_actions" value="<?php echo $txt_actions; ?>" >	
    <?php 		
    break;
	case 18:
	?>	
	<div class="col-12">
	<div class="form-group">
    <label for="exampleFormControlSelect1">Example select</label>
    <select class="form-control" id="exampleFormControlSelect1">
      <option>1</option>
      <option>2</option>
      <option>3</option>
      <option>4</option>
      <option>5...</option>
    </select>
  </div>
    <input type="button" id="add_field<?php echo $id_pregunta; ?>" value="Adicionar">
	<br>
	<div id="listas<?php echo $id_pregunta; ?>">
    <div><input type="text" name="respuesta[]" placeholder="Pregunta 1"></div>
	</div>
	</div>
	<input type="hidden" name="res_correcta" value="0" > 
	<input type="hidden" name="res_multiple" value="0" >
	<input type="hidden" name="doblecheck" value="<?php echo $doblecheck; ?>" >
	<input type="hidden" name="txt_actions" value="<?php echo $txt_actions; ?>" >	
    <?php  	
    break;	
	endswitch;	 
	?>
	  <input type="hidden" name="id_tema" value="<?php echo $id_tema; ?>" >
	   <input type="hidden" name="id_grupo_preguntas" value="<?php echo $id_grupo_preguntas; ?>" >
	    <input type="hidden" name="id_pregunta" value="<?php echo $id_pregunta; ?>" >
		 <input type="hidden" name="tipo_pregunta" value="<?php echo $tipo_pregunta; ?>" >
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
<?php
}


switch ($exa):
    case "nuevotema":
        fomr_agregar_nuevo_tema();
        break;
    case "registratema":
        $tema_descripcion = $_POST['tema_descripcion'];
		$tema_fecha_fin = $_POST['tema_date_fin'].' '.$_POST['tema_time_fin'].':00';	
		$tema_tipo = $_POST['tema_tipo'];
		$tema_para = $_POST['tema_para'];
		$id_area = $_POST['id_area'];
		$tema_fecha_fin;
		$crudex->insertar_tema($idcentro,$tema_descripcion,$fecha_hora,$tema_fecha_fin,$tema_tipo,$tema_para,$aid,$id_area);
		header('Location: adminexamen');
        break;
	case "modificatema":
	    $id_tema = $_POST['id_tema'];
        $tema_descripcion = $_POST['tema_descripcion'];
		$tema_fecha_fin = $_POST['tema_date_fin'].' '.$_POST['tema_time_fin'].':00';
		//$tema_tipo = $_POST['tema_tipo']; no se puede modificar ID area por que es sensible a todo los resultados 
		$tema_para = $_POST['tema_para'];
		$id_area = $_POST['id_area'];  
		if(isset($_POST['tema_activo']) && $_POST['tema_activo']=='on'){ echo $tema_activo = 1; } else { echo  $tema_activo = 0; }
		$crudex->modifica_tema($id_tema,$tema_descripcion,$tema_fecha_fin,$tema_activo,$tema_para,$id_area);
		header('Location: adminexamen');
         break;
	case "ModificaSeccion":
	    $id_tema = $_POST['id_tema'];
        $tema_descripcion = $_POST['tema_descripcion'];
		$tema_fecha_fin = $_POST['tema_fecha_fin'];
		$tema_tipo = $_POST['tema_tipo'];
		$tema_para = $_POST['tema_para'];
		$id_area = $_POST['id_area'];
		//if(isset ($_POST['tema_activo'])){ $tema_activo = 1; } else {  $tema_activo = 0; }
		//$crudex->modifica_tema($id_tema,$tema_descripcion,$tema_fecha_fin,$tema_tipo,$tema_activo,$tema_para,$id_area);
		//header('Location: adminexamen');
        break;		
	case "ModificaPregunta":
	    $id_tema = $_POST['id_tema'];
		$id_pregunta = $_POST['id_pregunta'];
		$pregunta = $_POST['pregunta'];
		$tipo_pregunta = $_POST['id_tipo_pregunta'];
		$puntos = $_POST['puntos'];
		$descripcion = $_POST['descripcion'];
		if(isset($_POST['txt_actions'])) { 		$txt_actions = 1; } else { 		$txt_actions = 0; }
        if(isset($_POST['doblecheck'])) { 		$doblecheck = 1; } else { 		$doblecheck = 0; }		
		if(isset ($_POST['tema_activo'])){ $tema_activo = 1; } else {  $tema_activo = 0; }
		$crudex->modifica_pregunta($id_pregunta,$pregunta,$tipo_pregunta,$puntos,$idcentro,$aid,$txt_actions,$doblecheck,$descripcion);
		header('Location: adminexamen?idtema='.$id_tema.'');
        break;			
	case "EliminarTema":  
	    $id_tema = $_POST['id_exa_temas'];
		$crudex->eliminar_tema($id_tema,$idcentro,$aid);
		header('Location: adminexamen');
        break;	
	case "EliminarRespuesta":  
	    $id_pregunta = $_POST['id_exa_respuesta'];
		$id_tema = $_POST['id_tema'];
		$crudex->eliminar_respuestas($id_pregunta,$idcentro,$aid);
		header('Location: adminexamen?idtema='.$id_tema.'');
        break;	
	case "EliminarPregunta":  
	    $id_pregunta = $_POST['id_exa_pregunta'];
		$id_tema = $_POST['id_tema'];
		$crudex->eliminar_pregunta($id_pregunta,$idcentro,$aid);
		header('Location: adminexamen?idtema='.$id_tema.'');
        break;	
	case "EliminarGrupoPregunta":  
	    $id_grupo_preguntas = $_POST['id_exa_grupo_preguntas'];
		$id_tema = $_POST['id_tema'];
		$crudex->eliminar_grupo_preguntas($id_grupo_preguntas,$idcentro,$aid);
		header('Location: adminexamen?idtema='.$id_tema.'');
        break;			
    case "registragrupo":
		$crudex->insertar_grupo_preguntas($_POST['id_tema'],$_POST['grupo_preguntas'],$idcentro,$aid);
		header('Location: adminexamen?idtema='.$_POST['id_tema'].''); 
        break;
    case "modificagrupo":     	
		$crudex->modifica_grupo_preguntas($_POST['id_grupo_preguntas'],$_POST['grupo_preguntas'],$idcentro,$aid);
		header('Location: adminexamen?idtema='.$_POST['id_tema'].''); 
        break;		
    case "RegistraPregunta":
		$id_tema = $_POST['id_tema'];
		$id_grupo_preguntas = $_POST['id_grupo'];
		$pregunta = $_POST['pregunta'];
		$tipo_pregunta = $_POST['id_tipo_pregunta'];
		$puntos = $_POST['puntos'];
		$descripcion = $_POST['descripcion'];
		if(isset($_POST['txt_actions'])) { 		$txt_actions = 1; } else { 		$txt_actions = 0; }
		if(isset($_POST['doblecheck'])) { 		$doblecheck = 1; } else { 		$doblecheck = 0; }
		$crudex->insertar_pregunta($id_tema,$id_grupo_preguntas,$pregunta,$idcentro,$tipo_pregunta,$puntos,$aid,$doblecheck,$txt_actions,$descripcion);
	    header('Location: adminexamen?idtema='.$id_tema.''); 
        break;	
 	case "registraespuesta":
		if (isset($_POST['respuesta']) && is_array($_POST['respuesta'])){	
			 $id_tema = $_POST['id_tema'];
			 $id_grupo_preguntas = $_POST['id_grupo_preguntas'];
			 $id_pregunta = $_POST['id_pregunta'];
			 $tipo_pregunta = $_POST['tipo_pregunta'];
			 $respuesta = $_POST['respuesta']; // array
		     $res_correcta = $_POST['res_correcta'];
			 $res_multiple = $_POST['res_multiple'];
			 $doblecheck = $_POST['doblecheck'];
			 $txt_actions = $_POST['txt_actions'];
		foreach ($respuesta as $clave=>$valor)
   		{
			if($valor != ''){
			if ($clave == $res_correcta) 
			{
				$res_correc=1;
			$crudex->insertar_respuestas($id_tema,$id_grupo_preguntas,$id_pregunta,$tipo_pregunta,$clave,$valor,$res_correc,$res_multiple,$aid,$idcentro,$doblecheck,$txt_actions);	
		    } else {
				$res_correc=0;
		//echo $id_tema.'-'.$id_grupo_preguntas.'-'.$id_pregunta.'-'.$tipo_pregunta.'-'.$valor.'-'.$res_correc.'-'.$aid.'-'.$idcentro.'<br>';
            $crudex->insertar_respuestas($id_tema,$id_grupo_preguntas,$id_pregunta,$tipo_pregunta,$clave,$valor,$res_correc,$res_multiple,$aid,$idcentro,$doblecheck,$txt_actions);			
			}
			}
   		}
		}
	   header('Location: adminexamen?idtema='.$id_tema.''); 
        break;	 	
    default:
	   if(isset($_GET['idtema'])) { $id_tema = $_GET['idtema'];} else { $id_tema = 0; } 
	$cuestiontb = $crudex -> sacarmonbredb('descripcion,tipo,estado','exa_temas','id='.$id_tema.''); 
    $tipo_tema=$cuestiontb[1];
    $desc_tema=$cuestiontb[0]; 	
	$estado_tema=$cuestiontb[2];   
       form_inicio($idcentro,$aid);
	   form_modal_tema($id_tema,true,$estado_tema);  //editar
	   form_modal_tema($id_tema,false,$estado_tema); //nuevo registro
	   form_modal_eliminar($id_tema,'exa_temas',$estado_tema);
	if($id_tema != 0){
	form_modal_seccion($id_tema,0,0,false);	
    ?>
	<div class="card text-white bg-dark mb-2">
	<h6 class="card-header">
	<?php echo ucfirst($desc_tema); ?> <a class="font-italic"> <?php echo 'Tipo :'.$tipocuestionario[$tipo_tema]; ?> </a>
	<div class="btn-group btn-group-sm" role="group">
    <button id="btnGroupDrop1" type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      Opciones
    </button>
    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
	      <button type="button" class="dropdown-item" data-toggle="modal" data-target="#NuevoGrupoPreguntas">Agregar sección</button>
		  <button type="button" class="dropdown-item" data-toggle="modal" data-target="#EditTemaForm">Editar</button>
		  <button type="button" class="dropdown-item" data-toggle="modal" data-target="#EliminarTema<?php echo $id_tema; ?>">Eliminar</button>
    </div>
	</div>
    </h6>
	</div>	
	<?php

	if (isset($_GET['idgrupre'])){ $id_grupre = $_GET['idgrupre'];}
          $sec=1;
		  $db=Db::conectar();
		  $sql ='SELECT * FROM exa_grupo_preguntas WHERE id_tema=:id_tema';
          $select=$db->prepare($sql);
		  $select->bindValue('id_tema',$id_tema);
		  $select->execute();
          while ($rowgru=$select->fetch()) {
	$id_grupo_preguntas = $rowgru['id']; 		  
    form_modal_preguntas(false,$id_tema,$id_grupo_preguntas,false,0);			  
	?> 
	<div class="card text-white bg-secondary mb-2">
	<h6 class="card-header">
	 <?php echo $sec.'.- '.ucfirst($rowgru['descripcion']); $sec++; ?>
	<div class="btn-group btn-group-sm" role="group">
    <button id="btnGroupDrop1" type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    Opciones
    </button>
    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
    <button type="button" class="dropdown-item" data-toggle="modal" data-target="#RegistraPregunta<?php echo $id_grupo_preguntas; ?>">Agregar pregunta</button>  
    <button type="button" class="dropdown-item" data-toggle="modal" data-target="#ModificaGrupoPreguntas<?php echo $id_grupo_preguntas; ?>">Editar sección</button>
	<button type="button" class="dropdown-item" data-toggle="modal" data-target="#EliminarGrupoPregunta<?php echo $id_grupo_preguntas; ?>">Eliminar sección</button>
    </div>
    </div>
	</h6>
	<div class="card-body">
	<?php 
		  $db=Db::conectar();
		  $sqlgp ='SELECT * FROM exa_preguntas WHERE id_tema=:id_tema AND id_grupo_preguntas=:id_grupo_preguntas AND centro=:centro';
          $selectgp=$db->prepare($sqlgp);
		  $selectgp->bindValue('id_tema',$id_tema);
		  $selectgp->bindValue('id_grupo_preguntas',$id_grupo_preguntas);
		  $selectgp->bindValue('centro',$idcentro);
		  $selectgp->execute(); $pre=1;
          while ($rowrpg=$selectgp->fetch()) {
		  $id_pregunta = $rowrpg['id'];
		   $id_tema = $rowrpg['id_tema'];
		    $id_grupo_preguntas = $rowrpg['id_grupo_preguntas'];
		     $pregunta = $rowrpg['pregunta'];
		      $tipo_pregunta = $rowrpg['tipo_pregunta'];
			   $puntos = $rowrpg['puntos'];
			    $txt_actions = $rowrpg['txt_actions'];
				 $doblecheck = $rowrpg['doblecheck'];
				  $descripcion = $rowrpg['descripcion'];
				 if($doblecheck == 1 ) { $classs='table-secondary'; } else { $classs=''; }
			    $rowCountRes = $crudex-> contardb('id','exa_respuesta','id_pregunta = '.$id_pregunta.' AND centro = "'.$idcentro.'"');
			?>
		<div class="card text-dark border-light mb-2">
		<h6 class="card-header">
		<?php echo $pre.'.- '.ucfirst($pregunta).' - '.$tipopregunta[$tipo_pregunta]; if ($tipo_tema==2){ echo ' - Puntos : '.$puntos.''; } $pre++;?>
		<div class="btn-group btn-group-sm" role="group">
		<button id="btnGroupDrop1" type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		Opciones
		</button>
		<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
		<?php
        if ($rowCountRes == 0){		
		?>
		<button type="button" class="dropdown-item" data-toggle="modal" data-target="#AgregarRespuestas<?php echo $id_pregunta; ?>">Agregar Respuestas</button>  
		<?php
		}
		?>
		<button type="button" class="dropdown-item" data-toggle="modal" data-target="#ModificaPregunta<?php echo $id_pregunta; ?>">Editar pregunta</button>
		<button type="button" class="dropdown-item" data-toggle="modal" data-target="#EliminarPregunta<?php echo $id_pregunta; ?>">Eliminar Pregunta</button>
		<button type="button" class="dropdown-item" data-toggle="modal" data-target="#EliminarRespuesta<?php echo $id_pregunta; ?>">Eliminar respuestas</button>
		</div>
		</div>
		</h6>
		<div class="card-body <?php echo $classs; ?> >" >
		<h6 class="card-subtitle mb-2 text-muted"><?php echo $descripcion; ?></h6>
		<form>
		<table class="table table-sm table-bordered">
		<tbody>
		<?php
		  $dbres=Db::conectar();
		  $sqlres ='SELECT * FROM exa_respuesta WHERE id_tema=:id_tema AND id_grupo_preguntas=:id_grupo_preguntas AND id_pregunta=:id_pregunta AND centro=:centro ORDER BY orden ASC';
          $selectres=$dbres->prepare($sqlres);
		  $selectres->bindValue('id_tema',$id_tema);
		  $selectres->bindValue('id_grupo_preguntas',$id_grupo_preguntas);
		  $selectres->bindValue('id_pregunta',$id_pregunta);
		  $selectres->bindValue('centro',$idcentro);
		  $selectres->execute();
          while ($rowres=$selectres->fetch()) {
    switch ($tipo_pregunta):
    case 1: 	
			if ($tipo_tema==2){ // 1 == custionario -> 2  == examen
			if($rowres['res_correcta']==1){ $checked='checked'; $class='class="bg-success text-white"'; $disabled=''; } else { $checked=''; $class=''; $disabled='disabled';};
			} else { $class = ''; $checked = ''; $disabled = ''; }
			  ?>	   
		<tr  <?php echo $class; ?> >
		<th scope="row" style="width: 10%;" >
			<label for="colFormLabelSm"><?php echo  $letrasresp[$rowres['orden']].' :'; ?></label>
		</th>
		<td style="width: 90%;" >
			<div class="form-check">
			<input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios1" <?php echo $checked.''.$disabled; ?> value="option1" >
			<label class="form-check-label" for="exampleRadios1"><?php  echo $rowres['respuestas']; ?></label>
			</div>
		</td>
		</tr>
			  <?php
         break;
    case 2: //2=>'Casilla de verificación checkbox'
			if ($tipo_tema==2){ // 1 == custionario -> 2  == examen
			if($rowres['res_correcta']==1){ $checked='checked'; $class='class="bg-success text-white"'; $disabled=''; } else { $checked=''; $class=''; $disabled='disabled';};
			} else { $class = ''; $checked = ''; $disabled = ''; }
			  ?>   
		<tr  <?php echo $class; ?> >
		<th scope="row" style="width: 10%;" >
			<label for="colFormLabelSm"><?php echo  $letrasresp[$rowres['orden']].' :'; ?></label>
		</th>
		<td style="width: 90%;" >
			<div class="form-check">
			<input class="form-check-input" type="checkbox" name="exampleRadios" id="exampleRadios1" <?php echo $checked.''.$disabled; ?> value="option1" >
			<label class="form-check-label" for="exampleRadios1"><?php  echo $rowres['respuestas']; ?></label>
			</div>
		</td>
		</tr>
			  <?php
        break;
    case 3: //3=>'Check Si-No radio button'
			if ($tipo_tema==2){ // 1 == custionario -> 2  == examen
			if($rowres['res_correcta']==1){ $checked='checked'; $class='class="bg-success text-white"'; $disabled=''; } else { $checked=''; $class=''; $disabled='disabled';};
			} else { $class = ''; $checked = ''; $disabled = ''; }
			  ?>	   
		<tr  <?php echo $class; ?> >
		<th scope="row" style="width: 10%;" >
			<label for="colFormLabelSm"><?php echo  $letrasresp[$rowres['orden']].' :'; ?></label>
		</th>
		<td style="width: 90%;" >
			<div class="form-check">
			<input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios1" <?php echo $checked.''.$disabled; ?> value="option1" >
			<label class="form-check-label" for="exampleRadios1"><?php  echo $rowres['respuestas']; ?></label>
			</div>
		</td>
		</tr>
			  <?php
        break;
    case 4: // 4=>'Múltiple Si-No radio button'
			?>
		<tr>
		<td scope="row" style="width: 50%;" >
	    <label for="colFormLabelSm"><?php echo  $rowres['respuestas']; ?></label>
		</td>
		<td style="width: 50%;" >
		<div class="form-check form-check-inline">
		<input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1">
		<label class="form-check-label" for="inlineRadio1">Si</label>
		</div>
		<div class="form-check form-check-inline">
		<input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2">
		<label class="form-check-label" for="inlineRadio2">No</label>
		</div>
		</td>
		</tr>
			<?php
        break;
    case 5: //5=>'Texto'
	?>
		<tr>
		<td style="width: 70%;" >
		<input type="text" class="form-control" id="colFormLabelSm" placeholder="<?php echo  $rowres['respuestas']; ?>">
		</td>
		</tr>
	<?php	
        break;
    case 6:  //6=>'Textarea'
		?>
	<div class="form-group row">	
	<label for="validationTextarea"><?php echo  $rowres['respuestas']; ?></label>
    <textarea class="form-control" cols="40" rows="5" ></textarea>
	</div>
		<?php 
    case 7: // 7=>'Múltiple RangeInputs'
		?>
	<div class="form-group">
	<label for="formControlRange"><?php echo  $rowres['respuestas']; ?></label>
    <input type="range" class="custom-range" min="0" max="10" id="formControlRange">
	</div>
		<?php	
        break;
    case 8: //5=>'fecha'
	?>
		<tr>
		<td style="width: 70%;" >
		<input type="date" class="form-control" id="validationCustom02" value="<?php echo $fecha; ?>" >
		</td>
		</tr>
	<?php	
        break;
    case 9: //'empresa'
	?>
		<tr>
		<td style="width: 70%;" >
	<?php form_select("SELECT empresa,empresa FROM t77_em WHERE centro='$idcentro' GROUP BY empresa",1); ?>
		</td>
		</tr>
	<?php	
        break;
	case 10: //'bk'
	?>
		<tr>
		<td style="width: 70%;" >
	<?php form_select("SELECT ruta,ruta FROM t77_em WHERE centro='$idcentro' GROUP BY ruta",1); ?>
		</td>
		</tr>
	<?php	
        break;	
	  case 11: //'bk'
	?>
		<tr>
		<td style="width: 70%;" >
	<?php form_select("SELECT descripcion,descripcion FROM usuario_puesto",1); ?>
		</td>
		</tr>
	<?php	
        break;
    case 12: 	
			if ($tipo_tema==2){ // 1 == custionario -> 2  == examen
			if($rowres['res_correcta']==1){ $checked='checked'; $class='class="bg-success text-white"'; $disabled=''; } else { $checked=''; $class=''; $disabled='disabled';};
			} else { $class = ''; $checked = ''; $disabled = ''; }
			  ?>	   
		<tr  <?php echo $class; ?> >
		<th scope="row" style="width: 10%;" >
			<label for="colFormLabelSm"><?php echo  $letrasresp[$rowres['orden']].' :'; ?></label>
		</th>
		<td style="width: 90%;" >
			<div class="form-check">
			<input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios1" <?php echo $checked.''.$disabled; ?> value="option1" >
			<label class="form-check-label" for="exampleRadios1"><?php  echo $rowres['respuestas']; ?></label>
			</div>
		</td>
		</tr>
			  <?php
         break;	
    case 13: //1=>'Codigo cliente'	
			  ?>	   
		<tr>
		<td style="width: 70%;" >
		<input type="text" class="form-control" id="colFormLabelSm" placeholder="<?php echo  $rowres['respuestas']; ?>">
		</td>
		</tr>
			  <?php
         break;		
    case 14: //1=>'Codigo cliente'	
	?>
		<tr>
		<td style="width: 70%;" >
	<?php form_select("SELECT descripcion,descripcion FROM exa_area_5s WHERE area=2",1); ?>
		</td>
		</tr>
	<?php
         break;	
    case 15: //1=>'Codigo cliente'	
	?>
		<tr>
		<td style="width: 70%;" >
	<?php form_select("SELECT descripcion,descripcion FROM exa_area_5s WHERE area=1",1); ?>
		</td>
		</tr>
	<?php
         break;	
    case 16: //1=>'Codigo cliente'	
	?>
		<tr>
		<td style="width: 70%;" >
	<?php form_select("SELECT CONCAT(apellidos,' ',nombre) as usuarios ,CONCAT(apellidos,' ',nombre) as usuario FROM usuarios WHERE centro='$idcentro' AND puesto NOT IN (29,28) AND estado=1 ORDER BY apellidos",1); ?>
		</td>
		</tr>
	<?php
         break;	
    case 17: //1=>'Codigo cliente'	
	?>
		<tr>
		<td style="width: 70%;" >
	<?php form_select("SELECT placa,placa FROM t77_vehiculos WHERE centro='$idcentro'",1); ?>
		</td>
		</tr>
	<?php
         break;
    case 18: //1=>'Codigo cliente'	
         break;		 
	endswitch;	  
		  }
	Db::desconectar();	  
		?>	
		</tbody>
		</table>
	<?php 
	if($tipo_pregunta==18){
	form_select("SELECT respuestas,respuestas FROM exa_respuesta WHERE centro='$idcentro' AND id_pregunta='$id_pregunta'",1); 
	} else { } 
    if($txt_actions == 1){
	?> 
	<div class="form-group">
	<label for="text11">Acciones  / Comentario</label>
	<input type="text" class="form-control" id="text11" >
	</div>
	<?php 
	} else { } 
	?> 		
		</form>		
		</div>
		</div>
	
			<?php
        form_modal_agregar_respuetas($id_tema,$id_grupo_preguntas,$id_pregunta,$cuestiontb[1]);	
		form_modal_preguntas($id_pregunta,$id_tema,$id_grupo_preguntas,true,$estado_tema);
        form_modal_eliminar($id_pregunta,'exa_pregunta',$estado_tema);
        form_modal_eliminar($id_pregunta,'exa_respuesta',$estado_tema);		
		}
		Db::desconectar();
	?>
	</div>
	</div> 
	<?php
	form_modal_seccion($id_tema,$id_grupo_preguntas,$estado_tema,true);
	form_modal_eliminar($id_grupo_preguntas,'exa_grupo_preguntas',$estado_tema);	
	}
	Db::desconectar();
	}
endswitch;
ob_end_flush();
?>
    </main>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script>window.jQuery || document.write('<script src="../assets/js/vendor/jquery.slim.min.js"><\/script>')</script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.9.0/feather.min.js"></script>
    <script>
// Example starter JavaScript for disabling form submissions if there are invalid fields
(function() {
  'use strict';
  window.addEventListener('load', function() {
    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.getElementsByClassName('needs-validation');
    // Loop over them and prevent submission
    var validation = Array.prototype.filter.call(forms, function(form) {
      form.addEventListener('submit', function(event) {
        if (form.checkValidity() === false) {
          event.preventDefault();
          event.stopPropagation();
        }
        form.classList.add('was-validated');
      }, false);
    });
  }, false);
})();
   </script>
 <?php 
    if (isset($id_tema)) {
		  $db=Db::conectar();
		  $sqlgp ='SELECT id FROM exa_preguntas WHERE id_tema=:id_tema AND centro=:centro';
          $selectgp=$db->prepare($sqlgp);
		  $selectgp->bindValue('id_tema',$id_tema);
		  $selectgp->bindValue('centro',$idcentro);
		  $selectgp->execute();
          while ($rowrpg=$selectgp->fetch()) {
		  $id_pregunta = $rowrpg['id'];
		  $add_field = '#add_field'.$id_pregunta;
		  $listas = '#listas'.$id_pregunta;
		   ?>
		  <script id="rendered-js" >
	var campos_max = 51; //max de 10 campos
	var x = 2;
	$(<?php echo '"'.$add_field.'"'; ?>).click(function (e) {
	e.preventDefault(); //prevenir novos clicks
	if (x < campos_max) {
    $(<?php echo '"'.$listas.'"'; ?>).append('<div>\
                                <input type="text" name="respuesta[]" placeholder="Texto '+x+'" >\
                                <a href="#" class="remover_campo">Remover</a>\
                                </div>');
    x++;
	}
	});
	// Remover o div anterior
	$(<?php echo '"'.$listas.'"'; ?>).on("click", ".remover_campo", function (e) {
	e.preventDefault();
	$(this).parent('div').remove();
	x--;
	});
	//# sourceURL=pen.js
		</script> 
		   <?php 
		  }
		  Db::desconectar();
		  }
		} else { echo "no tienes permiso para acceder a esta seccion ".$accesos.'-'.$aid.'<br><a  href="index">Inicio</a>'; }
	}	  
	?>	
</body>
</html>
