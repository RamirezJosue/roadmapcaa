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
	$sweetalert = 1;
	$bootstrapjs = 1;
	require('../head.php');
	if(isset($_GET['fechaselec'])): 
	$fecha_form = $_GET['fechaselec'];
	$fechars = $_GET['fechaselec'];	
	else:
	$fechars = $fechars; 
	$fecha_form = $fecha;
	endif;
	/*fin includes head systen ini*/
	$dbrs=Db::conectar();
    $selectrs=$dbrs->prepare("SELECT FechaPlan FROM `t77_checkin_cd` WHERE centro='$idcentro' GROUP BY FechaPlan");
	$selectrs->execute();
	$cuenta_col = $selectrs->rowCount();
	$regis = $selectrs->fetch();
    $FechaPlan = $regis['FechaPlan'];
	Db::desconectar();
	if ($cuenta_col > 0){ $disabled='disabled'; $disabledEnviar = ''; } else { $disabled=''; $disabledEnviar = 'disabled'; }
	if (isset($_GET['op'])){ $op = $_GET['op']; } else { $op = ""; }
function tabla_rs(){
	global $idcentro,$fechars;
?>  
	<div class="table-responsive">
		<table class="table table-sm table-bordered">
		<thead>
		<tr>
		    <th>#</th>
			<th>centro</th>
			<th>material</th>
			<th>descripcion</th>
		</tr>
		</thead>	
         <?php
		  $n=1;
		  $db=Db::conectar();
          $select=$db->prepare("SELECT centro,material,descripcion FROM `fefo` WHERE centro='$idcentro' GROUP BY material,descripcion");
		  $select->execute();
          while ($registro=$select->fetch()) {
		?>   
		<tr>		
		<td><?php echo $n; ?></td>
		<td><?php echo $registro['centro']; ?></td>
		<td><?php echo $registro['material']; ?></td>
		<td><?php echo $registro['descripcion']; ?></td>
		</tr>
		<?php
		 $n++;
		}
		Db::desconectar();
		?>
		</table>
     </div>		
<?php	

}
function borrar_rs_tem(){
	global $db,$idcentro,$idcentrotb;
				$db=DB::conectar();
				$detele=$db->prepare("DELETE FROM `fefo` WHERE centro = '$idcentro'");
				$detele->execute();
				Db::desconectar();
	}
switch ($op):
    case "borrarbdrs":
	borrar_rs_tem();
	header('Location: fefo');	
    break;
	case "enviarrsdb":
     if($crud->contardbuser('Fecha','KPI_Picking','Fecha = "'.$FechaPlan.'" AND Centro="'.$idcentro.'"')>0){
      echo "existen registros con la misma fecha";
	 } else {
		$db=Db::conectar();
		$insert=$db->prepare("INSERT INTO `KPI_Picking` SELECT 
						NULL,`Transporte`, `Centro`, `FechaPlan`, `Placa`, `Ruta`, `Viaje`, SUM(`Cajas`) AS `Cajas`, 
						SUM(`Unidades`) AS `Unidades`, NULL , NULL , NULL , NULL
						FROM `t77_checkin_cd` 
						WHERE `FechaPlan`='$FechaPlan' AND Centro='$idcentro'
						GROUP BY `Transporte`,`Centro`,`FechaPlan`,`Placa`,`Ruta`,`Viaje` ORDER BY `Ruta`,`Viaje` ASC
		");  
		$insert->execute();	
		Db::desconectar();
		$lastInsertId = $db->lastInsertId();
		if($lastInsertId>0){
		 ?> 
		 <div class="alert alert-success" role="alert">Se registro</div>	   
		 <?php 
		  header('Refresh: 3; URL=fefo');
		} else { 
		 ?> 
		 <div class="alert alert-danger" role="alert">no se pudo registrar</div>	   
		 <?php 
		  header('Refresh: 3; URL=fefo');	
		}
	 }
	break; 					
    default:
	if(isset($_GET['msj'])){ 
		echo '<div class="alert alert-danger" role="alert">'.$_GET['msj'].'</div>';
		}
?>
<p class="text-danger font-weight-bold h4">FEFO : <?php echo $idcentro; ?></p>
<form action="fefo" method="POST" enctype="multipart/form-data">
   <div class="form-group">
   <input type="file" name="archivoexcel" class="form-control-file"  id="exampleFormControlFile1" accept=".xlsx" <?php echo $disabled; ?> required>
   <input type="submit" onclick="
		this.disabled=true;this.value='Espere por favor...';this.form.submit();
		setTimeout(() => {
			this.value = 'Guardar';
			this.disabled= false;
		}, 20000);
	" class="btn btn-danger btn-sm" id="btnsubmit" value="Cargar excel">
   	   <button class="btn btn-danger btn-sm" type="button" onclick="location.href='fefo?op=borrarbdrs';" >Borrar datos cargados</button>
</form>   	  
   </div>
<?php	
  if(isset($_FILES['archivoexcel']) && $cuenta_col <= 0){
	require('phpexcel/Classes/PHPExcel.php');
    $tmpfname = $_FILES['archivoexcel']['tmp_name'];
	$leerexcel = PHPExcel_IOFactory::createReaderForFile($tmpfname);
    $excelobj = $leerexcel->load($tmpfname);
    $hoja = $excelobj -> getSheet(0);
    $filas = $hoja -> getHighestRow();	
    $db=DB::conectar();
	for ($row = 2;$row<=$filas;$row++){
		
		$material       = htmlspecialchars($hoja -> getCell('A'.$row)->getCalculatedValue(),ENT_QUOTES,'UTF-8');
		$descripcion	= htmlspecialchars($hoja -> getCell('B'.$row)->getCalculatedValue(),ENT_QUOTES,'UTF-8');
		$rt_nr 		    = htmlspecialchars($hoja -> getCell('C'.$row)->getCalculatedValue(),ENT_QUOTES,'UTF-8');
		$factor 	    = htmlspecialchars($hoja -> getCell('D'.$row)->getCalculatedValue(),ENT_QUOTES,'UTF-8');
		$pallet         = htmlspecialchars($hoja -> getCell('E'.$row)->getCalculatedValue(),ENT_QUOTES,'UTF-8'); 
		$cama 		    = htmlspecialchars($hoja -> getCell('F'.$row)->getCalculatedValue(),ENT_QUOTES,'UTF-8'); 
		$categ 		    = htmlspecialchars($hoja -> getCell('G'.$row)->getCalculatedValue(),ENT_QUOTES,'UTF-8');
		$marca 	        = htmlspecialchars($hoja -> getCell('H'.$row)->getCalculatedValue(),ENT_QUOTES,'UTF-8');
		$capacidad      = htmlspecialchars($hoja -> getCell('I'.$row)->getCalculatedValue(),ENT_QUOTES,'UTF-8');
		$cuarenta_cinco = htmlspecialchars($hoja -> getCell('J'.$row)->getCalculatedValue(),ENT_QUOTES,'UTF-8');
        $sesenta		= htmlspecialchars($hoja -> getCell('K'.$row)->getCalculatedValue(),ENT_QUOTES,'UTF-8');
        $cien		    = htmlspecialchars($hoja -> getCell('L'.$row)->getCalculatedValue(),ENT_QUOTES,'UTF-8');
        $apilado		= htmlspecialchars($hoja -> getCell('M'.$row)->getCalculatedValue(),ENT_QUOTES,'UTF-8');
        $tipo		    = htmlspecialchars($hoja -> getCell('N'.$row)->getCalculatedValue(),ENT_QUOTES,'UTF-8');
        $abc		    = htmlspecialchars($hoja -> getCell('O'.$row)->getCalculatedValue(),ENT_QUOTES,'UTF-8');
			$insert=$db->prepare("INSERT INTO `fefo`
             (`id`, `centro`, `material`, `descripcion`, `rt_nr`, `factor`, `pallet`, `cama`, `categ`, `marca`, `capacidad`, `cuarenta_cinco`, `sesenta`, `cien`, `apilado`, `tipo`, `abc`) 
             VALUES 
             (null,'$idcentro','$material','$descripcion','$rt_nr','$factor','$pallet','$cama','$categ','$marca','$capacidad','$cuarenta_cinco','$sesenta','$cien','$apilado','$tipo','$abc')");  
		     $insert->execute();	
	}
		$lastInsertId = $db->lastInsertId();
		if($lastInsertId>0){
		 ?> 
		 <div class="alert alert-success" role="alert">Se registro</div>	   
		 <?php 
		 // header('Refresh: 3; URL=fefo');
		} else { 
		 ?> 
		 <div class="alert alert-danger" role="alert">no se pudo registrar</div>	   
		 <?php 
		 // header('Refresh: 3; URL=fefo');	
		}
	Db::desconectar();
	//header('Location: fefo'.$msj.'');
	//die();
	}

	tabla_rs();
	endswitch;
	} else {
	echo $html_acceso;		
	}
	}
	require('../footer.php');
	ob_end_flush();	
	?>