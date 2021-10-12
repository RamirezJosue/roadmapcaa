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
	$datatablesjs = 1;
	$bootstrapjs =  1;
	require('../head.php');
	if (isset($_GET['rs'])){ $rs = $_GET['rs']; } else { $rs = ""; }
	if(isset($_GET['fechaselec'])){ 
	$fecha_form = $_GET['fechaselec'];
	$fechars = $_GET['fechaselec'];	
	} else { 
	$fechars = $fechars; 
	$fecha_form = $fecha;
	}
	function crud_ventas(){
	 global $fechars,$idcentro,$fecha_form,$db,$disableform,$aid,$crud;
     modal_ventas('add','','','','','','Agregar','Agregar'); 	
	?>
	<p class="h3 text-danger">Actualizar contacto ventas <?php echo $idcentro; ?></p>
	<div class="table-responsive">
			<table id="example"  data-order='[[ 0, "asc" ]]' data-page-length='50' class="table table-sm table-striped table-hover table-bordered">
			<thead>
			<tr>
			<th scope="col">ZV</th>
			<th scope="col">Supervisor</th>
			<th scope="col">Agente</th>
			<th scope="col">TelfSup</th>
			<th scope="col">TelfAge</th>
			<th scope="col"><button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#myModaladd">Agregar</button></th>
			<th scope="col"></th>
			</tr>
			</thead>
			<tbody>	
			<?php	
		  $db=Db::conectar();
		  $sql ="SELECT * FROM `t77_zv_detalle` WHERE centro=:centro ORDER BY `zv` ASC";
          $select=$db->prepare($sql);
		  $select->bindValue('centro',$idcentro);
		  $select->execute();
          while ($registro=$select->fetch()) {			  
			  $id 	      = $registro[0];	
			  $centro     = $registro[1];
			  $zv     	  = $registro[2];
			  $supervisor = $registro[3];
			  $agente 	  = $registro[4];
			  $telfAge 	  = $registro[5];
			  $telfSup 	  = $registro[6];
			?>
			<tr>
			<td><?php echo $zv; ?></td>
			<td><?php echo $supervisor; ?></td>
			<td><?php echo $agente; ?></td>
			<td><?php echo $telfSup; ?></td>
			<td><?php echo $telfAge; ?></td>
			<td>
			<div class="btn-group btn-group-sm" role="group" aria-label="...">
			<button type="button"   class="btn btn-danger btn-sm" data-toggle="modal" data-target="#myModal<?php echo $id; ?>">Editar</button>
			<form  onsubmit="return confirm('Esta seguro de eliminar <?php echo $zv; ?>');" name="conductor" method='POST'>
			<input type="hidden" name="DfXdz2htPH0lsSSs5nCTspuj" value="<?php echo $id; ?>">
			<input type="hidden" name="zv" value="<?php echo $zv; ?>">
			<button type="submit"   class="btn btn-danger btn-sm" >Eliminar</button>
			</form>
			</div>
			</td>
			<td>
			<?php modal_ventas($id,$zv,$supervisor,$telfSup,$agente,$telfAge,'Editar','Editar'); ?>
			</td>
			</tr>
			 <?php
        }
		Db::desconectar();
        echo "<tbody></table></div>";
	}
	function modal_ventas($id,$zv,$supervisor,$telfSup,$agente,$telfAge,$tipo,$action){
	global $fechars,$idcentro,$fecha_form,$db,$disableform,$aid,$crud;
    ?>
		<div class="modal fade" id="myModal<?php echo $id; ?>" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
	<!-- Modal content-->
		<div class="modal-content">
		<div class="modal-header">
		 <h5 class="modal-title" id="exampleModalLabel"><?php echo $tipo; ?> zona venta <?php echo $zv; ?> </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
		</div>
		  <form  method="post"> 
		 <div class="modal-body">
		    <input name="<?php echo $tipo; ?>" type="hidden" value="<?php echo $action; ?>">
			<?php if($tipo=='Agregar'){?>
		    <div class="form-group">
			<label for="zonaventa">Zona venta</label>
			<input type="text" class="form-control form-control-sm" id="zonaventa" name="zonaventa" value="" required>
			</div>
			<?php } ?>
		    <div class="form-group">
			<label for="Supervisor">Supervisor</label>
			<input type="text" class="form-control form-control-sm" id="supervisor" name="supervisor" value="<?php echo $supervisor; ?>" required>
			</div>
		    <div class="form-group">
			<label for="TelefonoSup">Telefono supervisor</label>
			<input type="text" class="form-control form-control-sm" id="telfSup" name="telfSup" value="<?php echo $telfSup; ?>" required>
			</div>
		    <div class="form-group">
			<label for="Agente">Agente</label>
			<input type="text" class="form-control form-control-sm" id="agente" name="agente" value="<?php echo $agente; ?>" required>
			</div>
		    <div class="form-group">
			<label for="TelefonoAgen">Telefono agente</label>
			<input type="text" class="form-control form-control-sm" id="telfAge" name="telfAge" value="<?php echo $telfAge; ?>" required>
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
		if (isset($_POST['supervisor'],$_POST['telfSup'],$_POST['agente'],$_POST['telfAge'],$_POST['id'],$idcentro,$_POST['Editar']))
		{  
		$crud->editar_contacto_ventas($_POST['supervisor'],$_POST['agente'],$_POST['telfAge'],$_POST['telfSup'],$_POST['id'],$idcentro);
		  ?>
		  <div class="alert alert-success alert-dismissible fade show" role="alert">
		  <strong>Modificado correctamente !</strong> 
		   <button type="button" class="close" data-dismiss="alert" aria-label="Close">
		   <span aria-hidden="true">&times;</span>
		   </button>
		  </div>
		  <?php
		}
		if (isset($_POST['DfXdz2htPH0lsSSs5nCTspuj'],$_POST['zv'])) {
		  $id=$_POST['DfXdz2htPH0lsSSs5nCTspuj'];
		  $zv=$_POST['zv'];
		  $crud->elimiar_contacto_ventas($id,$idcentro,$zv);
		  ?>
		  <div class="alert alert-danger alert-dismissible fade show" role="alert">
		  <strong>se elimino !</strong>  <?php echo $zv.' de centro '.$idcentro; ?>
		   <button type="button" class="close" data-dismiss="alert" aria-label="Close">
		   <span aria-hidden="true">&times;</span>
		   </button>
		  </div>
		  <?php
		}
		if (isset($_POST['supervisor'],$_POST['telfSup'],$_POST['agente'],$_POST['telfAge'],$_POST['id'],$idcentro,$_POST['Agregar'],$_POST['zonaventa']))
		{  
		 $crud->agregar_contacto_ventas($idcentro,$_POST['zonaventa'],$_POST['supervisor'],$_POST['agente'],$_POST['telfAge'],$_POST['telfSup']);
		  ?>
		  <div class="alert alert-success alert-dismissible fade show" role="alert">
		  <strong>Agregado correctamente !</strong>  <?php echo $_POST['zonaventa'].' de centro '.$idcentro; ?>
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