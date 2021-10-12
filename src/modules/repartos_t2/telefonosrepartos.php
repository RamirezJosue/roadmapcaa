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
	$sweetalert = 0;
	$bootstrapjs = 1;
	$datatablesjs = 1;
	require('../head.php');
	if(isset($_GET['fechaselec'])): 
	$fecha_form = $_GET['fechaselec'];
	$fechars = $_GET['fechaselec'];	
	else:
	$fechars = $fechars; 
	$fecha_form = $fecha;
	endif;
	if (isset($_GET['rs'])){ $rs = $_GET['rs']; } else { $rs = ""; }
	function crud_ventas(){
	 global $fechars,$idcentro,$fecha_form,$db,$disableform,$aid,$crud;
     modal_ventas('add','','','','Agregar','Agregar'); 	 
	?>
	<p class="h3 text-danger">Actualizar contacto repartos <?php echo $idcentro; ?></p>
	<div class="table-responsive">
			<table id="example"  data-order='[[ 0, "asc" ]]' data-page-length='50' class="table table-sm table-striped table-hover table-bordered">
			<thead>
			<tr>
			<th scope="col">Ruta</th>
			<th scope="col">Empresa</th>
			<th scope="col">Telefono</th>
			<th scope="col"><button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#myModaladd">Agregar</button></th>
			<th scope="col"></th>
			</tr>
			</thead>
			<tbody>	
			<?php	
		  $db=Db::conectar();
		  $sql ="SELECT * FROM t77_em WHERE centro=:centro ORDER BY `ruta` ASC";
          $select=$db->prepare($sql);
		  $select->bindValue('centro',$idcentro);
		  $select->execute();
          while ($registro=$select->fetch()) {			  
			?>
			<tr>
			<td><?php echo $registro['ruta']; ?></td>
			<td><?php echo $registro['empresa']; ?></td>
			<td><?php echo $registro['telefonoreparto']; ?></td>
			<td>
			<div class="btn-group btn-group-sm" role="group" aria-label="...">
			<button type="button"   class="btn btn-danger btn-sm" data-toggle="modal" data-target="#myModal<?php echo $registro['id']; ?>">Editar</button>
			<form  onsubmit="return confirm('Esta seguro de eliminar <?php echo $registro['ruta']; ?>');" name="conductor" method='POST'>
			<input type="hidden" name="DfXdz2htPH0lsSSs5nCTspuj" value="<?php echo $registro['id']; ?>">
			<input type="hidden" name="ruta" value="<?php echo $registro['ruta']; ?>">
			<button type="submit"   class="btn btn-danger btn-sm" >Eliminar</button>
			</form>
			</div>
			</td>
			<td>
			<?php modal_ventas($registro['id'],$registro['ruta'],$registro['empresa'],$registro['telefonoreparto'],'Editar','Editar'); ?>
			</td>
			</tr>
			 <?php
        }
		Db::desconectar();
        echo "<tbody></table></div>";
	}
	function modal_ventas($id,$ruta,$empresa,$telefonoreparto,$tipo,$action){
	global $fechars,$idcentro,$fecha_form,$db,$disableform,$aid,$crud;
    ?>
		<div class="modal fade" id="myModal<?php echo $id; ?>" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
	<!-- Modal content-->
		<div class="modal-content">
		<div class="modal-header">
		 <h5 class="modal-title" id="exampleModalLabel"><?php echo $tipo; ?> zona <?php echo $ruta; ?> </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
		</div>
		  <form  method="post"> 
		 <div class="modal-body">
		    <input name="<?php echo $tipo; ?>" type="hidden" value="<?php echo $action; ?>">
			<?php if($tipo=='Agregar'){?>
		    <div class="form-group">
			<label for="ruta">Ruta</label>
			<input type="text" class="form-control form-control-sm" id="ruta" name="ruta" value="" required>
			</div>
			<?php } else { 
			}?>
		    <div class="form-group">
			<label for="empresa">Empresa</label>
			<select class="form-control form-control-sm" id="empresa" required name="empresa" required>
			<option value="">Seleccionar</option>
			<?php	
			$selectSQL = "SELECT descripcion,descripcion  FROM `usuario_empresa` WHERE centro=:centro";
			$db=Db::conectar();
			$select=$db->prepare($selectSQL);
			$select->bindValue('centro',$idcentro);
			$select->execute();
			while ($regis=$select->fetch()) {
			if ($regis['descripcion'] == $empresa){
			echo '<option  value="'.$regis['descripcion'].'" selected >'.$regis['descripcion'].'</option>';		
			}else {
			echo '<option  value="'.$regis['descripcion'].'" >'.$regis['descripcion'].'</option>';	
			}
			}
			Db::desconectar();
			?>
			</select>
			</div>
		    <div class="form-group">
			<label for="telefonoreparto">Telefono reparto</label>
			<input type="text" class="form-control form-control-sm" id="telefonoreparto" name="telefonoreparto" value="<?php echo $telefonoreparto; ?>" required>
			<input name="id" type="hidden" value="<?php echo $id; ?>">
			</div>		
		</div>
	  <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-danger">Grabar</button>
      </div>
	     </form>
		</div>
		</div>
		</div>	
	<?php	
	}
	switch ($rs):
    case "buscar_clientes_rs":
	break;	
    default:
		if (isset($_POST['empresa'],$_POST['telefonoreparto'],$_POST['id'],$idcentro,$_POST['Editar']))
		{  
		$crud->editar_contacto_reparto($_POST['empresa'],$_POST['telefonoreparto'],$_POST['id'],$idcentro);
		  ?>
		  <div class="alert alert-success alert-dismissible fade show" role="alert">
		  <strong>Modificado correctamente !</strong> 
		   <button type="button" class="close" data-dismiss="alert" aria-label="Close">
		   <span aria-hidden="true">&times;</span>
		   </button>
		  </div>
		  <?php
		}
		if (isset($_POST['DfXdz2htPH0lsSSs5nCTspuj'],$_POST['ruta'])) {
		  $id=$_POST['DfXdz2htPH0lsSSs5nCTspuj'];
		  $ruta=$_POST['ruta'];		  
		  $crud->eliminar_contacto_reparto($id,$idcentro,$ruta);
		  ?>
		  <div class="alert alert-danger alert-dismissible fade show" role="alert">
		  <strong>se elimino !</strong>  <?php echo $ruta.' de centro '.$idcentro; ?>
		   <button type="button" class="close" data-dismiss="alert" aria-label="Close">
		   <span aria-hidden="true">&times;</span>
		   </button>
		  </div>
		  <?php
		}
		if (isset($_POST['empresa'],$_POST['telefonoreparto'],$_POST['id'],$idcentro,$_POST['Agregar'],$_POST['ruta']))
		{  
		 $crud->agregar_contacto_reparto($idcentro,$_POST['ruta'],$_POST['empresa'],$_POST['telefonoreparto']);
		  ?>
		  <div class="alert alert-success alert-dismissible fade show" role="alert">
		  <strong>Agregado correctamente !</strong>  <?php echo $_POST['ruta'].' de centro '.$idcentro; ?>
		   <button type="button" class="close" data-dismiss="alert" aria-label="Close">
		   <span aria-hidden="true">&times;</span>
		   </button>
		  </div>
		  <?php
		}
	crud_ventas();
	endswitch;
	} else {
	echo $html_acceso;		
	}
	}
	require('../footer.php');
	ob_end_flush();	
	?>