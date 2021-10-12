<?php 
	ob_start();
	$accesos = basename(dirname(__FILE__));
	require_once('../../includes/ini.php');
	require_once('../../bd/crud_usuario.php');
	$crud=new CrudUsuario();
    if ($usuarioestado==0){
	echo $html_bloqueo;
	}else{
    $arraruser = explode (',', $usuarioaccesos);	
	if (in_array($accesos, $arraruser)) {
	if ($usuariotipo==0): $aid_super = 0; else: $aid_super = 1; endif;
	/*inicio vefifia si tiene permisos de adminrepartos */
	if (in_array($accesos, $arraruser)): $adminrepartos = 1; else: $adminrepartos = 0; endif;
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
	$arraruserexcluye = array('..','.');
	$dir = '../../modules';
	$directorio = opendir($dir);
	$carpetas_mod = array();
	while ($f = readdir($directorio)) {
	if (is_dir("$dir/$f") && !in_array($f, $arraruserexcluye)) {
	$carpetas_mod_arr[] = $f;
	} else { }
	}
	closedir($directorio);
	$carpetas_mod = implode(",", $carpetas_mod_arr);
function form2_user($type,$id,$name,$td,$value,$disabled,$placeholder,$required)
{	global $db,$idcentro,$aid;
	if ($type=='select'){
	?>
 	<select class="form-control form-control-sm" id="<?php echo $id; ?>"  name="<?php echo $name; ?>" <?php echo $disabled.' '.$required; ?> >
	<option  value="">Seleccionar...</option>
			<?php
   			$db=Db::conectar();
			$select=$db->prepare('SELECT * FROM '.$td.'');
			$select->execute();
			while ($row=$select->fetch()) {
			if ($row['id'] == $value){
			echo '<option  value="'.$row['id'].'" selected >'.$row['descripcion'].'</option>';		
			}else {
			echo '<option  value="'.$row['id'].'" >'.$row['descripcion'].'</option>';	
			}
			}
			Db::desconectar();
	?>
	</select>  
	<?php
	}
}
function form_user($type,$id,$name,$td,$value,$disabled,$placeholder,$required)
{	global $db,$idcentro;
	if ($type=='select'){
	?>
 	<select class="form-control form-control-sm" id="<?php echo $id; ?>"  name="<?php echo $name; ?>" <?php echo $disabled; ?> >
	<option  value="">Seleccionar...</option>
			<?php
            if(($td=='usuario_puesto') || ($td=='usuario_genero') || ($td=='usuario_division') || ($td=='usuario_area') || ($td=='usuario_brevete') || ($td=='usuario_st') || ($td=='usuarios_roles')) { $where=''; } else { $where='WHERE centro="'.$idcentro.'"';}
			$db=Db::conectar();
			$select=$db->prepare('SELECT * FROM '.$td.' '.$where.'');
			$select->execute();
			while ($row=$select->fetch()) {
			if ($row['id'] == $value){
			echo '<option  value="'.$row['id'].'" selected >'.$row['descripcion'].'</option>';		
			}else {
			echo '<option  value="'.$row['id'].'" >'.$row['descripcion'].'</option>';	
			}
			}
			Db::desconectar();
	?>
	</select>  
	<?php
	} else if($type=='text' || $type=='password' || $type=='email' || $type=='date') {
	?>
	<input class="form-control form-control-sm" id="<?php echo  $id; ?>" type="<?php echo  $type; ?>" name="<?php echo  $name; ?>"  value="<?php echo  $value; ?>" placeholder="<?php echo  $placeholder; ?>" <?php echo  $disabled.' '.$required;?>  >	
	<?php
	}
}
function form_usuario_nuevo_edit($Id,$aid_n,$edit,$idpuesto)
{	global $db,$idcentro,$fecha_hora;
	require_once('../../bd/array/configsitio.php');
	if($edit=='8qX7L4aJSEg' && isset($_GET['id']) && isset ($_GET['aid'])){
	$db=Db::conectar();
	$select=$db->prepare('SELECT * FROM usuarios WHERE Id=:Id AND dni=:dni AND centro=:centro');
	$select->bindValue('Id',$Id);
	$select->bindValue('dni',$aid_n);
	$select->bindValue('centro',$idcentro);
	$select->execute();
	while ($rowuser=$select->fetch()) {
	$nombre 			= $rowuser['nombre'];
	$clave 				= $rowuser['clave'];
	$apellidos 			= $rowuser['apellidos'];
	$dni				= $rowuser['dni'];
	$centro				= $rowuser['centro'];
	$id_empresa 		= $rowuser['id_empresa'];
	$puesto				= $rowuser['puesto'];
	$area				= $rowuser['area'];
	$division			= $rowuser['division'];
	$supervisor 		= $rowuser['supervisor'];
	$domicilio 			= $rowuser['domicilio_user'];
	$telefono			= $rowuser['telefono'];
	$email				= $rowuser['email'];
	$genero				= $rowuser['genero'];
	$brevete			= $rowuser['brevete'];
	$catbrevete 		= $rowuser['brevete_cat'];
	$vencimiento_brevete= $rowuser['vencimiento_brevete'];
	$fecha_alta			= $rowuser['fecha_alta'];
	$pin				= $rowuser['pin'];
	$img				= $rowuser['img'];
	$user_registro		= $rowuser['user_registro'];    
	$desactivar			= ''; 
	$nuevo_disabled		= 'disabled';
	$form_accion 		= 'userhc?hc=modificar&puesto='.$idpuesto.'';
	$tipo				= $rowuser['tipo'];
	$accesos			= $rowuser['accesos'];
	$estado				= $rowuser['estado'];
	$aid_n 				= $rowuser['dni'];
	}
	Db::desconectar();
	} else { //nuevo 
	$desactivar			= 'disabled';
	$puesto				= $idpuesto;
	$nuevo_disabled 	= '';
	$form_accion 		= '../../controller_login?puesto='.$idpuesto.'';
    $tipo				= 0;
	$accesos 			= 0;
	$estado				= 1;
	$apellidos 			= '';
	$nombre 			= '';
	$aid_n 				= '';
	$id_empresa 		= '';
	$area 				= '';
    $division 			= '';
	$genero 			= ''; 
	$supervisor 		= '';
	$domicilio 			= '';  
	$telefono 			= ''; 
	$email 				= ''; 
	$brevete 			= ''; 
	$catbrevete 		= ''; 
	$vencimiento_brevete= ''; 
	$pin 				= '';
	}
	if ($idpuesto == 29)		{ $nombre_td = 'Ruta Reparto'; }
	else if ($idpuesto == 7)	{ $nombre_td = 'DNI Chofer'; }
	else if ($idpuesto == 1)	{ $nombre_td = 'Zona Ventas'; }
	else if ($idpuesto == 28)	{ $nombre_td = 'Placa Vehiculo'; }
	else { $nombre_td = 'Usuario | DNI'; }
?>
  <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <li class="nav-item">
      <a class="nav-link active" data-toggle="tab" href="#home">Datos 1</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-toggle="tab" href="#menu1">Datos 2</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-toggle="tab" href="#menu2">Datos 3</a>
    </li>
  </ul>
  <form action="<?php echo $form_accion; ?>" method="post">
  <!-- Tab panes -->
  <div class="tab-content">
    <div id="home" class="container tab-pane active"><br>
	<div class="form-row">
    <div class="form-group col-md-2">
      <label ><?php echo $nombre_td; ?></label>
		<?php form_user('text','','dni','',$aid_n,'','','required'); ?> 
    </div>
    <div class="form-group col-md-3">
      <label >Contraseña</label>
	<div class="input-group">  
		<?php form_user('text','','clave','','',$nuevo_disabled,'','required'); ?>
		    <div class="input-group-prepend">
    <button type="button" class="btn btn-danger btn-sm"   data-toggle="modal" data-target="#contrasenia"  <?php echo $desactivar; ?> >Cambiar</button>
			</div>
    </div>
	</div>
    <div class="form-group col-md-1">
      <label for="idcentro">Centro</label>
      <select id="idcentro" name="centro" class="form-control form-control-sm" disabled>
        <option selected value="<?php echo $idcentro; ?>" ><?php echo $idcentro; ?></option>
      </select>	  
    </div>
    <div class="form-group col-md-3">
      <label for="idaccesos">Rol | Accesos</label>
	<div class="input-group">  
	    <?php form_user('select','idaccesos','accesos','usuarios_roles',$accesos,'','','required'); ?>
		<?php //form_user('text','idaccesos','accesos','',$accesos,'','',''); ?>
    </div>
	</div>
	<div class="form-group col-md-3">
      <label for="idtelefono">Telefono</label>
	  <?php form_user('text','idtelefono','telefono','',$telefono,'','','required'); ?>
    </div>
	</div>
	<div class="form-row">
		<div class="form-group col-md-3">
		<label for="idnombre">Nombre</label>
		<div class="input-group">
		<?php form_user('text','idnombre','nombre','',$nombre,'','','required'); ?>
		<div class="input-group-prepend">
	    <button type="button" class="btn btn-danger btn-sm"   data-toggle="modal" data-target="#covid">Covid+</button>
		</div>		
		</div>
		</div>
		<div class="form-group col-md-3">
		<label for="apellidos1">Apellidos</label>
		<?php form_user('text','idapellidos','apellidos','',$apellidos,'','','required'); ?> 			
		</div>
		<div class="form-group col-md-3">
      	<label for="idselectpuesto">Puesto</label>
	  	<?php form_user('select','idselectpuesto','puesto','usuario_puesto',$puesto,'','',''); ?> 	  
    	</div>		
		<div class="form-group col-md-3">
		<label for="idestado">Estado</label>
		<div class="input-group">
		<?php form_user('select','idestado','estado','usuario_st',$estado,$nuevo_disabled,'','');  if($estado==0){$txtestado='Activar';}else{$txtestado='Bloquear';}?> 
		    <div class="input-group-prepend">
	<button type="button" class="btn btn-danger btn-sm"   data-toggle="modal" data-target="#EstadoUser"  <?php echo $desactivar; ?> ><?php echo $txtestado; ?></button>
			</div>
		</div>
		</div>
	</div>		
    </div>
    <div id="menu1" class="container tab-pane fade"><br>
	<div class="form-row">
	<div class="form-group col-md-6">
      <label for="idselectempresa">Empresa</label>
	  <?php form_user('select','idselectempresa','empresa','usuario_empresa',$id_empresa,'','',''); ?> 
    </div>
    <div class="form-group col-md-6">
      <label for="idselectarea">Area</label>
	  <?php form_user('select','idselectarea','area','usuario_area',$area,'','',''); ?> 	  
    </div>
	</div>
	<div class="form-row">
	<div class="form-group col-md-3">
      <label for="idselectdivision">Division </label>
	  <?php form_user('select','idselectdivision','division','usuario_division',$division,'','',''); ?> 		   
    </div>
    <div class="form-group col-md-3">
      <label for="idgenero">Genero</label>
	  <?php form_user('select','idgenero','genero','usuario_genero',$genero,'','',''); ?>
    </div>
    <div class="form-group col-md-3">
      <label for="idselectsupervisor">Supervisor</label>
	  <?php form_user('select','idselectsupervisor','supervisor','usuario_supervisor',$supervisor,'','',''); ?> 	  
    </div>
	<div class="form-group col-md-3">
		<label for="idaltauser">Fecha alta</label>
	  <?php form_user('text','idaltauser','fecha_alta','',$fecha_hora,'disabled','',''); ?> 
		</div>
	</div>	
    </div>
    <div id="menu2" class="container tab-pane fade"><br>
	<div class="form-row">
    <div class="form-group col-md-6">
      <label for="iddomicilio">Domicilio</label>
	  <?php form_user('text','iddomicilio','domicilio','',$domicilio,'','',''); ?> 	  
    </div>
	<div class="form-group col-md-6">
      <label for="idemail">Email</label>
	  <?php form_user('email','idemail','email','',$email,'','',''); ?> 	  
    </div>
	</div>
  <div class="form-row">
    <div class="form-group col-md-3">
      <label for="idbrevete">Brevete</label>
	  <?php form_user('text','idbrevete','brevete','',$brevete,'','',''); ?> 	  
    </div>
    <div class="form-group col-md-3">
      <label for="idcatbrevete">Categoria</label>
	  <?php form_user('select','idcatbrevete','catbrevete','usuario_brevete',$catbrevete,'','',''); ?>	  
    </div>
    <div class="form-group col-md-3">
      <label for="iddate">Vencimiento brevete</label>
	  <?php form_user('date','iddate','date_brevete','',$vencimiento_brevete,'','',''); ?> 	  
    </div>
	<div class="form-group col-md-3">
      <label for="idpin">Codigo PIN</label>
	  <?php form_user('text','idpin','pin','',$pin,'','',''); ?> 	  
    </div>
  </div>
    </div>
  </div>
  <input type="hidden" name="registrarse" value="registrarse">
   <input type="hidden" name="Id" value="<?php echo $Id; ?>">
    <input type="hidden" name="tipo" value="<?php echo $tipo; ?>">
  <div class="form-row">
  <div class="col-6">
   <button type="button" class="btn btn-danger btn-block" onclick="location.href='userhc';"  >Cancelar</button>
  </div>
  <div class="col-6">
   <button type="submit" class="btn btn-dark btn-block"  >Grabar</button> 
   </div>
  </div>
</form>		
<?php 
}
function modificar_user($Id,$nombre, $clave, $apellidos, $dni, $idcentro, $tipo, $accesos_user, $estado, $empresa, $puesto,$area, $division, $supervisor, $domicilio, $telefono, $email, $genero, $brevete, $catbrevete,$date_brevete, $fecha_hora, $pin, $img, $aid)
{ global $tipocuestionario,$cuestionariopara,$cuestionarioarea,$crudex,$idcentro,$aid;
		  $db=Db::conectar();
          $insert=$db->prepare("
		  UPDATE `usuarios` SET `nombre`=:nombre,`apellidos`=:apellidos,`dni`=:dni,`tipo`=:tipo,
`accesos`=:accesos,`estado`=:estado,`id_empresa`=:id_empresa,`puesto`=:puesto,`area`=:area,`division`=:division,`supervisor`=:supervisor,
`domicilio_user`=:domicilio_user,`telefono`=:telefono,`email`=:email,`genero`=:genero,`brevete`=:brevete,`brevete_cat`=:brevete_cat,
`vencimiento_brevete`=:vencimiento_brevete,`pin`=:pin WHERE Id = :Id
		  ");
			$insert->bindValue('Id',$Id);		  
		  	$insert->bindValue('nombre',$nombre);
			$insert->bindValue('apellidos',$apellidos);
			$insert->bindValue('dni',$dni);
			$insert->bindValue('tipo',$tipo);
			$insert->bindValue('accesos',$accesos_user);
			$insert->bindValue('estado',$estado);
			$insert->bindValue('id_empresa',$empresa);
			$insert->bindValue('puesto',$puesto);
			$insert->bindValue('area',$area);
			$insert->bindValue('division',$division);
			$insert->bindValue('supervisor',$supervisor);
			$insert->bindValue('domicilio_user',$domicilio);
			$insert->bindValue('telefono',$telefono);
			$insert->bindValue('email',$email);
			$insert->bindValue('genero',$genero);
			$insert->bindValue('brevete',$brevete);
			$insert->bindValue('brevete_cat',$catbrevete);
			$insert->bindValue('vencimiento_brevete',$date_brevete);
			$insert->bindValue('pin',$pin);
		    $insert->execute();
			Db::desconectar();
}

function form_modal_contrasena($id,$dni)
{ global $tipocuestionario,$cuestionariopara,$cuestionarioarea,$crudex,$idcentro,$aid;
?>
<!-- Modal -->
<div class="modal fade" id="contrasenia" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Cambio de contraseña</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
<form method="post" action="userhc?hc=CambiarPassword">
  <div class="form-group">
    <label for="usuario1">Usuario</label>
    <input type="text" class="form-control form-control-sm" id="usuario1" value="<?php echo $dni; ?>" disabled >
  </div>
  <div class="form-group">
    <label for="InputPassword1">Nueva contraseña</label>
    <input type="password" class="form-control form-control-sm" id="InputPassword1" name="newpassword">
  </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-dark">Guardar</button>
      </div>
	<input type="hidden" id="id" name="id" value="<?php echo $id; ?>">
	<input type="hidden" id="dni" name="dni" value="<?php echo $dni; ?>">	
</form>	  
    </div>
  </div>
</div>
<?php 
}

function form_modal_estado_user($id,$dni,$st)
{ global $tipocuestionario,$cuestionariopara,$cuestionarioarea,$crudex,$idcentro,$aid,$fecha;
?>
<!-- Modal -->
<div class="modal fade" id="EstadoUser" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><?php if($st==1){ echo 'Registrar : Bloqueo '; } else if($st==0) { echo 'Registrar : Activación'; } ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
<form method="post" action="userhc?hc=EstadoUser">
  <div class="form-group">
    <label for="usuario1">Usuario</label>
    <input type="text" class="form-control form-control-sm" id="usuario1" value="<?php echo $dni; ?>" disabled >
  </div>
  <div class="form-group">
    <label for="InputPassword1">Fecha</label>
    <input type="date" class="form-control form-control-sm" id="InputPassword1" name="fecha_registro" value="<?php echo $fecha; ?>" required>
  </div>
    <div class="form-group">
    <label for="InputPassword1">Descripción</label>
    <textarea class="form-control form-control-sm" id="validationTextarea" placeholder="Comente el nuevo registro" name="TXTestado" required></textarea>
    </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-dark">Guardar</button>
      </div>
	<input type="hidden" id="id" name="id" value="<?php echo $id; ?>">
	<input type="hidden" id="dni" name="dni" value="<?php echo $dni; ?>">
    <input type="hidden" id="estado" name="estado" value="<?php echo $st; ?>">	
</form>	  
    </div>
  </div>
</div>
<?php 
}
function form_modal_covid_user($id,$dni,$st)
{ global $tipocuestionario,$cuestionariopara,$cuestionarioarea,$crudex,$idcentro,$aid,$fecha;
?>
<!-- Modal -->
<div class="modal fade" id="covid" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">COVID</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
<form method="post" action="userhc?hc=Covid">
  <div class="form-group">
    <label for="usuario1">Usuario</label>
    <input type="text" class="form-control form-control-sm" id="usuario1" value="<?php echo $dni; ?>" disabled >
  </div>
    <div class="form-group">
    <label for="InputPassword1">Fecha examen</label>
    <input type="date" class="form-control form-control-sm" id="InputPassword1" name="fecha_examen" value="<?php echo $fecha; ?>" required>
  </div>
  	<div class="form-group">
      <label for="idtipoexamen">Tipo examen</label>
	  <?php form2_user('select','idtipoexamen','tipo_examen','usuario_covid_examen','','','','required'); ?> 
    </div>
	<div class="form-group">
      <label for="idresultado">Resultado positivo</label>
	  <?php form2_user('select','idresultado','positivo','t77_rs_check','','','','required'); ?> 
    </div>
  	<div class="form-group">
      <label for="iddiascuarentena">Días cuarentena</label>
		<?php form_user('text','iddiascuarentena','dias_cuarentena','','','','','required'); ?>
    </div> 
	<div class="form-group">
      <label for="idresultado">Seguimiento médico</label>
	  <?php form2_user('select','idseguimientomedico','seguimiento_medico','t77_rs_check','','','','required'); ?> 
    </div>	
  	<div class="form-group">
      <label for="idlaboratorio">Laboratorio</label>
		<?php form_user('text','idlaboratorio','laboratorio','','','','','required'); ?>
    </div> 
    <div class="form-group">
    <label for="InputPassword1">Observación</label>
    <textarea class="form-control form-control-sm" id="validationTextarea" placeholder="Observación" name="observacion" required></textarea>
    </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-dark" >Guardar</button>
      </div>
	<input type="hidden" id="dni" name="dni" value="<?php echo $dni; ?>">	
</form>	  
    </div>
  </div>
</div>
<?php 
}
function form_opciones_user()
{
global $idcentro;
if ($idcentro=='BK77') { $disabled = ''; } else { $disabled = 'disabled'; }
?>	
<div class="btn-group" role="group" aria-label="Button group with nested dropdown">
  <div class="btn-group" role="group">
    <button id="btnGroupDrop1" type="button" class="btn btn-dark btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      Opciones
    </button>
    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
	  <a class="dropdown-item" href="userhc">Inicio</a>
	  <a class="dropdown-item" href="excel?excel=usuarios">Exportar Usuario</a>
	  <a class="dropdown-item" href="excel?excel=resultadoscovid">Exportar Resultados Covid</a>
      <a class="dropdown-item" href="userhc?hc=InicioParametrosUser&amp;tb=empresa">Actualizar Empresa</a>
	  <a class="dropdown-item" href="userhc?hc=InicioParametrosUser&amp;tb=supervisor">Actualizar supervisor</a>
      <?php if($disabled != 'disabled' ){ ?>
	  <a class="dropdown-item <?php echo $disabled; ?>"  href="userhc?hc=InicioParametrosUser&amp;tb=puesto">Actualizar Puesto</a>
	  <a class="dropdown-item <?php echo $disabled; ?>" href="userhc?hc=InicioParametrosUser&amp;tb=area">Actualizar Area</a>
	  <a class="dropdown-item <?php echo $disabled; ?>" href="userhc?hc=InicioParametrosUser&amp;tb=division">Actualizar Division</a>
	  <a class="dropdown-item <?php echo $disabled; ?>" href="userhc?hc=InicioParametrosUser&amp;tb=brevete">Actualizar Brevete</a>	
	  <?php } ?>
	  </div>
  </div>
  <div class="btn-group" role="group">
    <button id="btnGroupDrop1" type="button" class="btn btn-dark btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      Nuevo 
    </button>
    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
	<a class="dropdown-item" href="userhc?nuevo=new">Usuario</a>
	  <a class="dropdown-item" href="userhc?nuevo=new&puesto=29">Liquidador Reparto</a>
	  <a class="dropdown-item" href="userhc?nuevo=new&puesto=7">Conductor Reparto</a>
	  <a class="dropdown-item" href="userhc?nuevo=new&puesto=1">Ventas</a>
	  <a class="dropdown-item" href="userhc?nuevo=new&puesto=28">Vehiculo</a>
	  </div>
  </div>
  <div class="btn-group" role="group">
    <button id="btnGroupDrop1" type="button" class="btn btn-dark btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    Listar
    </button>
    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
	  <a class="dropdown-item" href="userhc">Usuarios</a>
      <a class="dropdown-item" href="userhc?estado=0">Inactivos</a>
	  <a class="dropdown-item" href="userhc?hc=listarcovid">Covid</a>
	  <a class="dropdown-item" href="userhc?puesto=29">Liquidador Reparto</a>
	  <a class="dropdown-item" href="userhc?puesto=7">Conductor Reparto</a>
	  <a class="dropdown-item" href="userhc?puesto=1">Ventas</a>
	  <a class="dropdown-item" href="userhc?puesto=28">Vehiculos</a>
	  </div>
  </div> 
  <a href="roles" class="btn btn-dark btn-sm">Roles</a>
</div>
<?php
}
function tabla_usuarios($estado,$puesto)
{ global $db,$crudex,$idcentro,$aid,$tipopregunta;
	 ?>	
	<div class="row">
    <div class="col-sm-12 bg-highlight">
	<div class="d-flex justify-content-left">
	<div class="p-2 bd-highlight"><p class="h4 text-danger">Administrador de usuarios : <?php echo $idcentro; ?></p> </div>
	</div>
	</div>
	</div> 
    <div class="table-responsive">	 
    <table id="example"  data-order='[[ 0, "asc" ]]' data-page-length='20'
          class="table table-sm table-striped table-hover table-bordered">
	<thead>
		      <tr>
          <th scope="col">#</th>
		  <th scope="col">Usuario</th>
          <th scope="col">Nombre</th>
          <th scope="col">Apellidos</th>
		  <th scope="col">Empresa</th>
          <th scope="col">Puesto</th>
		  <th scope="col">Telefono</th>
		  <th scope="col">Supervisor</th>
		  <th scope="col">Estado</th>
		  <th scope="col"></th>
        </tr>
	</thead>
	<tbody>
	<?php

    if($puesto==false){
    $puesto_db = "AND puesto NOT IN (28,29)";
	}else{
	$puesto_db = "AND puesto='$puesto'";		
	}
		$db=Db::conectar();
		$sql ="
 SELECT 
Id,nombre,apellidos,dni,centro,estado,
(SELECT descripcion FROM usuario_empresa WHERE id=`id_empresa`) AS empresa, 
(SELECT ruc FROM usuario_empresa WHERE id=`id_empresa`) AS ruc, 
(SELECT responsable FROM usuario_empresa WHERE id=`id_empresa`) AS responsable, 
(SELECT telefono FROM usuario_empresa WHERE id=`id_empresa`) AS telefono_res, 
(SELECT domicilio FROM usuario_empresa WHERE id=`id_empresa`) AS domicilio, 
(SELECT Actividad FROM usuario_empresa WHERE id=`id_empresa`) AS Actividad, 
(SELECT descripcion FROM usuario_puesto WHERE id=`puesto`) AS puesto, 
(SELECT descripcion FROM usuario_area WHERE id=`area`) AS area, 
(SELECT descripcion FROM usuario_division WHERE id=`division`) AS division, 
(SELECT descripcion FROM usuario_supervisor WHERE id=`supervisor`) AS supervisor, 
`domicilio_user`, `telefono`, `email`, 
(SELECT descripcion FROM usuario_genero WHERE id=`genero`) AS genero, 
`brevete`, 
(SELECT descripcion FROM usuario_brevete WHERE id=`brevete_cat`) AS brevete_cat, 
`vencimiento_brevete`, `fecha_alta`, `pin`, `img`, `user_registro` 
FROM `usuarios`	WHERE centro=:centro AND estado=:estado $puesto_db ORDER BY Id DESC	
		";
        $select=$db->prepare($sql);
		$select->bindValue('centro',$idcentro);
		$select->bindValue('estado',$estado);
		$select->execute();
		$n=1;
		while ($row=$select->fetch()){		
		?>
		<tr>
		<td><?php echo $n; ?></td>
		<td><?php echo $row['dni']; ?></td>	
		<td><?php echo $row['nombre']; ?></td>	
		<td><?php echo $row['apellidos']; ?></td>
		<td><?php echo $row['empresa']; ?></td>	
		<td><?php echo $row['puesto']; ?></td>	
		<td><?php echo $row['telefono']; ?></td>	
		<td><?php echo $row['supervisor']; ?></td>		
		<td><?php if($row['estado']==0){ echo 'Ina'; } else { echo 'Act'; } ?></td>	
		<td><button class="btn btn-danger btn-sm" onclick="location.href='userhc?id=<?php echo $row['Id']; ?>&amp;aid=<?php echo $row['dni']; ?>&amp;edit=8qX7L4aJSEg&amp;st=<?php echo $row['estado']; ?>';" >Selec.</button></td>		
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
function tabla_usuarios_covid()
{ global $db,$crudex,$idcentro,$aid,$tipopregunta,$crud;
	 ?>	
	<div class="row">
    <div class="col-sm-12 bg-highlight">
	<div class="d-flex justify-content-left">
	<div class="p-2 bd-highlight"><p class="h4 text-danger">Usuarios covid : <?php echo $idcentro; ?></p> </div>
	</div>
	</div>
	</div> 
    <div class="table-responsive">	 
    <table id="example"  data-order='[[ 0, "asc" ]]' data-page-length='20'
          class="table table-sm table-striped table-hover table-bordered">
	<thead>
		      <tr>
          <th scope="col">Dni</th>
          <th scope="col">Nombre</th>
		  <th scope="col">Tipo examen</th>
          <th scope="col">Fecha</th>
		  <th scope="col">Resultado</th>
		  <th scope="col">Dias decanso</th>
		  <th scope="col">Seguimiento Medico</th>
		  <th scope="col">Laboratorio</th>
		  <th scope="col">Observación</th>
        </tr>
	</thead>
	<tbody>
	<?php

		$db=Db::conectar();
		$sql ="SELECT * FROM `usuario_covid` WHERE centro=:centro";
        $select=$db->prepare($sql);
		$select->bindValue('centro',$idcentro);
		$select->execute();
		$n=1;
		while ($row=$select->fetch()){
        $dni=$row['dni'];
        $rowrs = $crud -> sacarmonbre_db(" * ","usuarios","dni='".$dni."' AND centro='".$idcentro."'");
        $rowrsexa = $crud -> sacarmonbre_db(" * ","usuario_covid_examen","id='".$row['tipo_examen']."'");	
		?>
		<tr>
		<td><?php echo $row['dni']; ?></td>
		<td><?php echo $rowrs[1].' '.$rowrs[3]; ?></td>	
		<td><?php echo $rowrsexa[1]; ?></td>	
		<td><?php echo $row['fecha_examen']; ?></td>
		<td><?php if($row['positivo']==0){ echo "Negativo"; }else{ echo "Positivo"; } ?></td>		
		<td><?php echo $row['dias_cuarentena']; ?></td>	
		<td><?php if($row['seguimiento_medico']==0){ echo "No"; }else{ echo "Si"; } ?></td>		
		<td><?php echo $row['laboratorio']; ?></td>	
		<td><?php echo $row['observacion']; ?></td>		
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
function tabla_conductores()
{ global $db,$crudex,$idcentro,$aid,$tipopregunta,$crud;
	 ?>	
	<div class="row">
    <div class="col-sm-12 bg-highlight">
	<div class="d-flex justify-content-left">
	<div class="p-2 bd-highlight"><p class="h4 text-danger">Usuarios Check List Flota T2 Peru: <?php echo $idcentro; ?></p> </div>
	</div>
	</div>
	</div> 
    <div class="table-responsive">	 
    <table id="example"  data-order='[[ 0, "asc" ]]' data-page-length='50'
          class="table table-sm table-striped table-hover table-bordered">
	<thead>
		      <tr>
          <th scope="col">#</th>
          <th scope="col">Centro</th>
		  <th scope="col">Usuario</th>
          <th scope="col">Clave</th>
		  <th scope="col">Estado</th>
		  <th scope="col"></th>
        </tr>
	</thead>
	<tbody>
	<?php

		$db=Db::conectar();
		$sql ="SELECT id,nombre,clave,dni,centro,estado FROM `usuarios` WHERE puesto=28";
        $select=$db->prepare($sql);
		$select->bindValue('centro',$idcentro);
		$select->execute();
		$n=1;
		while ($row=$select->fetch()){
		?>
		<tr>
		<td><?php echo $n; ?></td>
		<td><?php echo $row['centro']; ?></td>	
		<td><?php echo $row['dni']; ?></td>	
		<td><?php echo $row['clave']; ?></td>	
		<td><?php if($row['estado']==0){ echo "Inactivo"; }else{ echo "Activo"; } ?></td>
		<td><button type="button" class="btn btn-danger btn-sm" onclick="location.href='userhc?hc=listaconductorescds&id=<?php echo $row['id']; ?>';"  >Centro</button></td>		
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
function tabla_usuarios_parametros($tb)
{ global $db,$crudex,$idcentro,$aid,$tipopregunta;
	 ?>	
	<br>
	<div class="row">
    <div class="col-sm-12 bg-highlight">
	<div class="d-flex justify-content-left">
	<div class="p-2 bd-highlight"><p class="h4 text-danger">Tabla : <?php echo $tb; ?></p> </div>
	<div class="p-2 bd-highlight"><button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#ParametroUserAdd">Anadir</button></div>
	</div>
	</div>
	</div>
<div class="table-responsive">	 
    <table id="example"  data-order='[[ 2, "asc" ]]' data-page-length='10'
          class="table table-sm table-striped table-hover table-bordered">
	<thead>
		      <tr>
          <th scope="col">ID</th>
          <th scope="col">Nombre</th>
          <th scope="col">Centro</th>
          <th scope="col">Op</th>
		  <th scope="col"></th>
        </tr>
	</thead>
	<tbody>
	<?php		
		$db=Db::conectar();
		$sql ="SELECT * FROM usuario_$tb WHERE centro=:centro";
        $select=$db->prepare($sql);
		$select->bindValue('centro',$idcentro);
		$select->execute();
		$n=1;
		while ($row=$select->fetch()){		
		?>
		<tr>
		<td><?php echo $n; ?></td>
		<td><?php echo $row['descripcion']; ?></td>
		<td><?php echo $row['centro']; ?></td>	
		<td><button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#ParametroUser<?php echo $row['id']; ?>"  >Editar</button></td>		
		<td><?php form_modal_parametro_user_edit($row['id'],$tb,$row['descripcion']); ?></td>
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
function form_modal_parametro_user_edit($id,$tb,$descripcion)
{ global $crud,$cuestionariopara,$cuestionarioarea,$crudex,$idcentro,$aid,$fecha;
?>
<!-- Modal -->
<div class="modal fade" id="ParametroUser<?php echo $id;?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modificar <?php echo $tb;?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
<form method="post" action="<?php echo "userhc?hc=InicioParametrosUser&tb=$tb";?>">
  <div class="form-group">
    <label for="descripcion1">Descripción</label>
    <input type="text" class="form-control form-control-sm" id="descripcion1" name="descripcion" value="<?php echo $descripcion; ?>" required >
  </div>
  
  <?php 
  if($tb=='empresa') {
	  $rowrs = $crud -> sacarmonbre_db(" * ","usuario_$tb","id='".$id."' AND centro='".$idcentro."'");
	?>  
  <div class="form-group">
    <label for="Ruc">Ruc</label>
    <input type="text" class="form-control form-control-sm" id="Ruc" name="ruc" value="<?php echo $rowrs[3]; ?>" required >
  </div>
  <div class="form-group">
    <label for="Responsable">Responsable</label>
    <input type="text" class="form-control form-control-sm" id="Responsable" name="responsable" value="<?php echo $rowrs[4]; ?>" required >
  </div> 
  <div class="form-group">
    <label for="Telefono">Telefono</label>
    <input type="text" class="form-control form-control-sm" id="Telefono" name="telefono" value="<?php echo $rowrs[5];?>" required >
  </div> 
  <div class="form-group">
    <label for="Domicilio">Domicilio</label>
    <input type="text" class="form-control form-control-sm" id="Domicilio" name="domicilio" value="<?php echo $rowrs[6]; ?>" required >
  </div> 
  <div class="form-group">
    <label for="Actividad">Actividad</label>
    <input type="text" class="form-control form-control-sm" id="Actividad" name="actividad" value="<?php echo $rowrs[7]; ?>" required >
  </div>   
	<?php 	
  } else {}
  ?>
  
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-dark">Guardar</button>
      </div>
	<input type="hidden" id="id" name="id" value="<?php echo $id; ?>">
	<input type="hidden" id="accion" name="accion" value="modificar">
	<input type="hidden" id="dni" name="tb" value="<?php echo $tb; ?>">
</form>	  
    </div>
  </div>
</div>
<?php 
}

function form_modal_parametro_user_add($tb)
{ global $crud,$cuestionariopara,$cuestionarioarea,$crudex,$idcentro,$aid,$fecha;
?>
<!-- Modal -->
<div class="modal fade" id="ParametroUserAdd" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Agregar <?php echo $tb;?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
<form method="post" action="<?php echo "userhc?hc=InicioParametrosUser&tb=$tb";?>">
  <div class="form-group">
    <label for="descripcion1">Descripción</label>
    <input type="text" class="form-control form-control-sm" id="descripcion1" name="descripcion" value="" required >
  </div>
  
  <?php 
  if($tb=='empresa') {
	?>  
  <div class="form-group">
    <label for="Ruc">Ruc</label>
    <input type="text" class="form-control form-control-sm" id="Ruc" name="ruc" value="" required >
  </div>
  <div class="form-group">
    <label for="Responsable">Responsable</label>
    <input type="text" class="form-control form-control-sm" id="Responsable" name="responsable" value="" required >
  </div> 
  <div class="form-group">
    <label for="Telefono">Telefono</label>
    <input type="text" class="form-control form-control-sm" id="Telefono" name="telefono" value="" required >
  </div> 
  <div class="form-group">
    <label for="Domicilio">Domicilio</label>
    <input type="text" class="form-control form-control-sm" id="Domicilio" name="domicilio" value="" required >
  </div> 
  <div class="form-group">
    <label for="Actividad">Actividad</label>
    <input type="text" class="form-control form-control-sm" id="Actividad" name="actividad" value="" required >
  </div>   
	<?php 	
  } else {}
  ?>
  
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-dark">Guardar</button>
      </div>
	<input type="hidden" id="id" name="id" value="">
	<input type="hidden" id="accion" name="accion" value="agregar">
	<input type="hidden" id="dni" name="tb" value="<?php echo $tb; ?>">
</form>	  
    </div>
  </div>
</div>
<?php 
}


switch ($hc):
    case "modificar":
if (isset($_POST['Id'])) {
$Id = $_POST['Id'];	
$nombre = $_POST['nombre'];
$clave = '';
$apellidos = $_POST['apellidos'];
$dni = $_POST['dni'];
$tipo = $_POST['tipo'];
$accesos_user = $_POST['accesos'];
$estado = 1;
$empresa = $_POST['empresa'];
$puesto = $_POST['puesto'];
$area = $_POST['area'];
$division = $_POST['division'];
$supervisor = $_POST['supervisor'];
$domicilio = $_POST['domicilio'];
$telefono = $_POST['telefono'];
$email = $_POST['email'];
$genero = $_POST['genero'];
$brevete = $_POST['brevete'];
$catbrevete = $_POST['catbrevete'];
$date_brevete = $_POST['date_brevete'];
$pin = $_POST['pin'];
$img = 'defaul.jpeg';
			modificar_user($Id,$nombre, $clave, $apellidos, $dni, $idcentro, $tipo, $accesos_user, $estado, $empresa, $puesto,$area, $division, $supervisor, $domicilio, $telefono, $email, $genero, $brevete, $catbrevete,$date_brevete, $fecha_hora, $pin, $img, $aid);
			header('Location: userhc?puesto='.$puesto.'');	
	}
        break;
    case "CambiarPassword":
	if(isset($_POST['newpassword'])){
	$id=$_POST['id'];
	$dni=$_POST['dni'];
	$newclave=$_POST['newpassword'];		
	$crud->modificar_password($id,$dni,$newclave,$idcentro);
	header('Location: userhc');
	}else {}
        break;
	case "CambiarAccesos":
			if(isset($_POST['newpassword'])){
			$id=$_POST['id'];
			$dni=$_POST['dni'];
			$newclave=$_POST['newpassword'];		
			$crud->modificar_password($id,$dni,$newclave,$idcentro);
			header('Location: userhc');
			}else {}
				break;	
	case "EstadoUser":
	if (isset($_POST['estado'])){ 
	$fecha_estado = $_POST['fecha_registro'];
	$descripcion = $_POST['TXTestado'];
	$dniuser = $_POST['dni'];
	$id = $_POST['id'];
	if($_POST['estado']==1){ $tipo='Bloqueado'; $estado=0;  }else if ($_POST['estado']==0) { $tipo='Activado'; $estado=1;  } else {  }
	$crud->registrar_estado_usuario($tipo,$dniuser,$descripcion,$fecha_estado,$fecha_hora,$aid,$idcentro);
	$crud->modificar_estado_usuario($id,$dniuser,$estado,$idcentro);
	header('Location: userhc');
	} else {}
         break;
	case "Covid":
	if (isset($_POST['dni'])){
	$dni = $_POST['dni'];
	$tipo_examen = $_POST['tipo_examen'];
	$fecha_examen = $_POST['fecha_examen'];
	$dias_cuarentena = $_POST['dias_cuarentena'];
	$seguimiento_medico = $_POST['seguimiento_medico'];
	$positivo = $_POST['positivo'];
	$laboratorio = $_POST['laboratorio'];
	$observacion = $_POST['observacion'];
	$crud->registrar_estado_covid($dni,$tipo_examen,$fecha_examen,$dias_cuarentena,$seguimiento_medico,$positivo,$laboratorio,$observacion,$idcentro);
	header('Location: userhc?hc=listarcovid');
	} else {
	header('Location: userhc');	
	}
         break;	 
	case "ModificaParametrosUser":
	?>
	<?php
        break;		
	case "InsertarParametrosUser":
        break;			
	case "InicioParametrosUser":
     
	if($idcentro == 'BK77') { 
	if(isset($_POST['accion']) && $_POST['accion']=='modificar' ){ 
    if(isset($_POST['id']) && $_POST['tb']!='empresa' ){
	$crud->modificar_parametro_usuario($_POST['id'],$_POST['tb'],$_POST['descripcion'],$idcentro);
	} else if (isset($_POST['id']) && $_POST['tb']=='empresa'){
	$crud->modificar_parametro_usuario_empresa($_POST['id'],$_POST['tb'],$_POST['descripcion'],$_POST['ruc'],$_POST['responsable'],$_POST['telefono'],$_POST['domicilio'],$_POST['actividad'],$idcentro);	
	}
	} else if (isset($_POST['accion']) && $_POST['accion']=='agregar' ) {
	if(isset($_POST['id']) && $_POST['tb']!='empresa' ){
      $crud->agregar_parametro_usuario($_POST['tb'],$_POST['descripcion'],$idcentro);
	} else if (isset($_POST['id']) && $_POST['tb']=='empresa'){
	$crud->agregar_parametro_usuario_empresa($_POST['tb'],$_POST['descripcion'],$_POST['ruc'],$_POST['responsable'],$_POST['telefono'],$_POST['domicilio'],$_POST['actividad'],$idcentro);
	}
	}
	} else {
	if ((isset($_POST['tb'])) && (($_POST['tb']=='empresa') || ($_POST['tb']=='supervisor'))){
	if(isset($_POST['accion']) && $_POST['accion']=='modificar' ){ 
    if(isset($_POST['id']) && $_POST['tb']!='empresa' ){
	$crud->modificar_parametro_usuario($_POST['id'],$_POST['tb'],$_POST['descripcion'],$idcentro);
	} else if (isset($_POST['id']) && $_POST['tb']=='empresa'){
	$crud->modificar_parametro_usuario_empresa($_POST['id'],$_POST['tb'],$_POST['descripcion'],$_POST['ruc'],$_POST['responsable'],$_POST['telefono'],$_POST['domicilio'],$_POST['actividad'],$idcentro);	
	}
	} else if (isset($_POST['accion']) && $_POST['accion']=='agregar' ) {
	if(isset($_POST['id']) && $_POST['tb']!='empresa' ){
      $crud->agregar_parametro_usuario($_POST['tb'],$_POST['descripcion'],$idcentro);
	} else if (isset($_POST['id']) && $_POST['tb']=='empresa'){
	$crud->agregar_parametro_usuario_empresa($_POST['tb'],$_POST['descripcion'],$_POST['ruc'],$_POST['responsable'],$_POST['telefono'],$_POST['domicilio'],$_POST['actividad'],$idcentro);
	}
	}
	} 
	}
	form_opciones_user();
	if (isset($_GET['tb'])){
	tabla_usuarios_parametros($_GET['tb']);
	form_modal_parametro_user_add($_GET['tb']);
    } else { }
        break;	
	case "listarcovid":  
	form_opciones_user();
	tabla_usuarios_covid(); 
        break;	
	case "listaconductorescds":
   echo  $_GET['id'];
    tabla_conductores();
        break;		 	
    default: 

	if(isset($_GET['mensaje'])){
      echo '<div class="alert alert-danger" role="alert">'.$_GET['mensaje'].'</div>';
	} 

     if(isset($_GET['id'],$_GET['edit'])){ 
	 $id=$_GET['id']; $aid_n=$_GET['aid']; $edit=$_GET['edit']; $st=$_GET['st']; 
	 } else { 
	 $id = 0; $aid_n = ''; $st = ''; $edit = false; 
	 }

	 if (isset($_GET['estado']) && $_GET['estado']==0){ $estado = 0; } else { $estado = 1; }
	 if (isset($_GET['puesto'])){ $puesto = $_GET['puesto']; } else { $puesto = false; }
     form_opciones_user();
	 ?>
	 <br><br>	 
	 <?php	 
	 if($id!=0 || isset($_GET['nuevo'])){  
		 if(isset($_GET['puesto'])){ 
			 $idpuesto=$_GET['puesto']; 
			} else { 
				$idpuesto=''; 
			}
		 form_usuario_nuevo_edit($id,$aid_n,$edit,$idpuesto); 
		}
	 form_modal_contrasena($id,$aid_n);
	 form_modal_estado_user($id,$aid_n,$st);
	 form_modal_covid_user($id,$aid_n,$st);
	 tabla_usuarios($estado,$puesto); 
	endswitch;
	} else {
	echo $html_acceso;		
	}
	}
	require('../footer.php');
	ob_end_flush();	
	?>