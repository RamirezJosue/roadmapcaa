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
    $selectrs=$dbrs->prepare("SELECT * FROM `t77_checkin_pt` WHERE FechaPlan='$fechars'");
	$selectrs->execute();
	$cuenta_col = $selectrs->rowCount();	
	Db::desconectar();
	if ($cuenta_col > 0){ $disablegrabar='disabled'; } else { $disablegrabar=''; }
	if (isset($_GET['op'])){ $op = $_GET['op']; } else { $op = ""; }
function tabla_rs(){
	global $fechars;
?>  
	<div class="table-responsive">
		<table class="table table-sm table-bordered">
		<thead>
		<tr>
			<th>#</th>
		    <th>Centro</th>
			<th>Fecha Planificación</th>
			<th>Cajas</th>
			<th>Unidades</th>
		</tr>
		</thead>	
         <?php
		  $n=1;
		  $db=Db::conectar();
          $select=$db->prepare("SELECT Centro, FechaPlan,  SUM(Cajas) AS Cajas, SUM(Unidades) AS Unidades FROM t77_checkin_pt WHERE FechaPlan='$fechars' GROUP BY Centro,FechaPlan");
		  $select->execute();
          while ($registro=$select->fetch()) {
		?>   
		<tr>
		<td><?php echo $n; ?></td>			
		<td><?php echo $registro['Centro']; ?></td>
		<td><?php echo $registro['FechaPlan']; ?></td>
		<td><?php echo $registro['Cajas']; ?></td>
		<td><?php echo $registro['Unidades']; ?></td>
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
			$detele=$db->prepare("DELETE FROM t77_checkin_pt WHERE centro = '$idcentro'");
			$detele->execute();
			Db::desconectar();
}
switch ($op):
    case "borrarbdrs":
	borrar_rs_tem();
	header('Location: check_out_almacen_peru');	
    break;				
    default:	
?>
<p class="text-danger font-weight-bold h4"> Check In Almacen : <?php echo $idcentro; ?></p>
<p class="text-muted h3"> Registros : <?php echo $cuenta_col; ?></p>

<form action="check_out_almacen_peru" method="POST" enctype="multipart/form-data">
   <div class="form-group">
   <input type="file" name="archivoexcel" class="form-control-file"  id="exampleFormControlFile1" accept=".xlsx" <?php echo $disablegrabar; ?> required>
   <button class="btn btn-danger btn-sm" type="submit"name="submit" <?php echo $disablegrabar; ?> >Cargar excel</button>
   </div>
</form>
<?php	
  if(isset($_FILES['archivoexcel']) && $cuenta_col <= 0){
	require('phpexcel/Classes/PHPExcel.php');
    $tmpfname = $_FILES['archivoexcel']['tmp_name'];
	$leerexcel = PHPExcel_IOFactory::createReaderForFile($tmpfname);
    $excelobj = $leerexcel->load($tmpfname);
    $hoja = $excelobj -> getSheet(0);
    $filas = $hoja -> getHighestRow();	
    $db=DB::conectar();
	for ($row = 13;$row<=$filas;$row++){
		$Transporte = htmlspecialchars($hoja -> getCell('A'.$row)->getCalculatedValue(),ENT_QUOTES,'UTF-8');
		$FechaPlan 	= date($format = "Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($hoja -> getCell('B'.$row)->getCalculatedValue()+1));
		$Centro 		= htmlspecialchars($hoja -> getCell('C'.$row)->getCalculatedValue(),ENT_QUOTES,'UTF-8');
		$Ruta 	= htmlspecialchars($hoja -> getCell('D'.$row)->getCalculatedValue(),ENT_QUOTES,'UTF-8');
		$Viaje = htmlspecialchars($hoja -> getCell('E'.$row)->getCalculatedValue(),ENT_QUOTES,'UTF-8'); //no es necesario este columna
		$Placa 		= htmlspecialchars($hoja -> getCell('F'.$row)->getCalculatedValue(),ENT_QUOTES,'UTF-8'); 
		$Material 		= htmlspecialchars($hoja -> getCell('G'.$row)->getCalculatedValue(),ENT_QUOTES,'UTF-8');
		$Denominacion 	= htmlspecialchars($hoja -> getCell('H'.$row)->getCalculatedValue(),ENT_QUOTES,'UTF-8');
		$cajas  = htmlspecialchars($hoja -> getCell('I'.$row)->getCalculatedValue(),ENT_QUOTES,'UTF-8');
		$unidades		= htmlspecialchars($hoja -> getCell('J'.$row)->getCalculatedValue(),ENT_QUOTES,'UTF-8');
			 $insert=$db->prepare("
	INSERT INTO `t77_checkin_pt`(`id`, `Transporte`, `FechaPlan`, `Centro`, `Ruta`, `Viaje`, `Placa`, `Material`, `Denominacion`, `Cajas`, `Unidades`, `st`) 
	 VALUES (null,'$Transporte','$FechaPlan','$Centro','$Ruta',$Viaje,'$Placa',$Material,'$Denominacion',$cajas,$unidades,0)			 
			 ");  
		     $insert->execute();
	}
	Db::desconectar();
	header('Location: check_out_almacen_peru');
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