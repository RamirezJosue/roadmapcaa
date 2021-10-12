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
	$idcentrotb = $idcentro;
	$dbrs=Db::conectar();
    $selectrs=$dbrs->prepare("SELECT SUM(a.Contar) as Contactos , SUM(a.Entrega) as Cajas  FROM (SELECT 1 as Contar , `Codigo`, `Entrega` FROM t77_rs_temp_".$idcentrotb.") AS a");
	$selectrs->execute();
    $rowrs=$selectrs->fetch();
	$Contactos=$rowrs['Contactos'];
	$Cajas=$rowrs['Cajas'];
	Db::desconectar();
	if ($Contactos > 0){ $disablegrabar=''; $disablecancelar='disabled'; } else { $disablegrabar='disabled'; $disablecancelar=''; }
	if (isset($_GET['op'])){ $op = $_GET['op']; } else { $op = ""; }
function tabla_rs($contactos, $cajas){
	global $idcentro,$idcentrotb;	
?>  <p class="text-muted">Contactos: <?php echo $contactos; ?> Cajas: <?php echo $cajas; ?></p>
	<div class="table-responsive">
		<table class="table table-sm table-bordered">
		<thead>
		<tr>
		    <th>Ruta</th>
			<th>Vehiculo</th>
			<th>Fecha</th>
			<th>Codigo</th>
			<th>TipoCliente</th>
			<th>ZNPVTA</th>
			<th>ZNTVTA</th>
			<th>Cliente</th>
			<th>Direccion</th>
			<th>Abre</th>
			<th>Cierra</th>
			<th>Viaje</th>
			<th>DistKm</th>
			<th>Sec2</th>
			<th>Sec1</th>
			<th>Entrega</th>
			<th>Recojo</th>
			<th>Llega</th>
			<th>Sale</th>
			<th>Ciudad</th>
			<th>Longitud</th>
			<th>Latitud</th>
		</tr>
		</thead>	
         <?php
		  $db=Db::conectar();
          $select=$db->prepare("SELECT * FROM t77_rs_sap LIMIT 15");
		  $select->execute();
          while ($registro=$select->fetch()) { 	
		?>   
		<tr>		
		<td><?php echo $registro[0]; ?></td>
		<td><?php echo $registro[1]; ?></td>
		<td><?php echo $registro[2]; ?></td>
		<td><?php echo $registro[3]; ?></td>
		<td><?php echo $registro[4]; ?></td>
		<td><?php echo $registro[5]; ?></td>
		<td><?php echo $registro[6]; ?></td>
		<td><?php echo $registro[7]; ?></td>
		<td><?php echo $registro[8]; ?></td>
		<td><?php echo $registro[9]; ?></td>
		<td><?php echo $registro[10]; ?></td>
		<td><?php echo $registro[11]; ?></td>
		<td><?php echo $registro[12]; ?></td>
		<td><?php echo $registro[13]; ?></td>
		<td><?php echo $registro[14]; ?></td>
		<td><?php echo $registro[15]; ?></td>
		<td><?php echo $registro[16]; ?></td>
		<td><?php echo $registro[17]; ?></td>
		<td><?php echo $registro[18]; ?></td>
		<td><?php echo $registro[19]; ?></td>
		<td><?php echo $registro[20]; ?></td>
		<td><?php echo $registro[21]; ?></td>
		</tr>
		<?php
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
			$detele=$db->prepare("DELETE FROM t77_rs_temp_".$idcentrotb."");
			$detele->execute();
			Db::desconectar();
}
function sacar_fecha($sql){
global $db,$idcentro,$idcentrotb;
			$db=DB::conectar();
			$select=$db->prepare($sql);
			$select->execute();
		    $row=$select->fetch();
			return $row[0];
			Db::desconectar();
}
switch ($op):
    case "borrarbdrs":
	borrar_rs_tem();
	header('Location: hr_peru');
	echo "xx";
	
    break;
    case "enviarrsdb":
	/*
	    $sqlverifi="SELECT Fecha FROM t77_rs_temp_".$idcentrotb." GROUP BY Fecha";
		$fecharsverifi=sacar_fecha($sqlverifi);	
	    if($crud->contardbuser('Fecha','t77_rs','Fecha = "'.$fecharsverifi.'" AND centro="'.$idcentro.'"')>0){
			?> 
		   <div class="alert alert-danger" role="alert">Existen registros con la misma fecha.</div>
		   <?php
         header('Refresh: 3; URL=http://www.bk77.co/excel_importar_rs');		   
		} else {
	      $db=Db::conectar();
          $select=$db->prepare("SELECT * FROM t77_rs_temp_".$idcentrotb."");
		  $select->execute();
          while ($inserrs=$select->fetch()) {
			$db=Db::conectar();  
			$indx = $inserrs[0].str_replace("-","",$inserrs[2]).$inserrs[3].$inserrs[11];
			$insert=$db->prepare("INSERT INTO t77_rs VALUES(null,'".$indx."',0,0,0,'',0,'".$idcentro."','','','0000-00-00 00:00:00','0000-00-00 00:00:00','','',0,0,'',0,0,'".$inserrs[0]."','".$inserrs[1]."','".$inserrs[2]."','".$inserrs[3]."','".$inserrs[4]."','".$inserrs[5]."','".$inserrs[6]."','".$inserrs[7]."','".$inserrs[8]."','".$inserrs[9]."','".$inserrs[10]."','".$inserrs[11]."','".$inserrs[12]."','".$inserrs[13]."','".$inserrs[14]."','".$inserrs[15]."','".$inserrs[16]."','".$inserrs[17]."','".$inserrs[18]."','".$inserrs[19]."','".$inserrs[20]."','".$inserrs[21]."')");  
		    $insert->execute();	  
		  }
		  $lastInsertId = $db->lastInsertId();
		  if($lastInsertId>0){
			  borrar_rs_tem();
		   ?> 
		   <div class="alert alert-success" role="alert">Se registro</div>
		   <?php 
		    header('Refresh: 3; URL=http://www.bk77.co/dashboard');
		  }else{ 
		   ?> 
		   <div class="alert alert-danger" role="alert">no se pudo registrar</div>
		   <?php 
		    header('Refresh: 3; URL=http://www.bk77.co/excel_importar_rs');	
		  } 
		}
	*/	
        break;
	case "modificatema":
         break;
	case "ModificaSeccion":
        break;				
    default:	
?>
<p class="h3"> Importar Hoja Peru : <?php echo $idcentro; ?></p>
<form action="hr_peru" method="post" enctype="multipart/form-data">
   <div class="form-group">
   <label for="FormControlFile1">Importar archivo</label>
   <input type="file" name="archivoexcel" class="form-control-file"  id="exampleFormControlFile1" <?php //echo $disablecancelar; ?> accept=".xlsx" required>
   <button class="btn btn-danger btn-sm" type="submit"name="submit" <?php //echo $disablecancelar; ?> >Cargar excel</button>
   <button class="btn btn-danger btn-sm" type="button" onclick="location.href='excel_importar_rs?op=enviarrsdb';" >Grabar publicar</button>
   <button class="btn btn-danger btn-sm" type="button" onclick="location.href='excel_importar_rs?op=borrarbdrs';" >Borrar datos cargados</button>
   </div>
</form>
<?php	

  if(isset($_FILES['archivoexcel'])){
	require('phpexcel/Classes/PHPExcel.php');
    $tmpfname = $_FILES['archivoexcel']['tmp_name'];
	$leerexcel = PHPExcel_IOFactory::createReaderForFile($tmpfname);
    $excelobj = $leerexcel->load($tmpfname);
    $hoja = $excelobj -> getSheet(0);
    $filas = $hoja -> getHighestRow();	
    $db=DB::conectar();	
	for ($row = 2;$row<=$filas;$row++){
		$Region     = htmlspecialchars($hoja -> getCell('A'.$row)->getCalculatedValue(),ENT_QUOTES,'UTF-8');
		$CD     	= htmlspecialchars($hoja -> getCell('B'.$row)->getCalculatedValue(),ENT_QUOTES,'UTF-8');
		$Empresa   	= htmlspecialchars($hoja -> getCell('C'.$row)->getCalculatedValue(),ENT_QUOTES,'UTF-8');
		$Ruta 	    = htmlspecialchars($hoja -> getCell('D'.$row)->getCalculatedValue(),ENT_QUOTES,'UTF-8');
		$Transporte = htmlspecialchars($hoja -> getCell('E'.$row)->getCalculatedValue(),ENT_QUOTES,'UTF-8');
		$FechaPlan 	= date($format = "Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($hoja -> getCell('F'.$row)->getCalculatedValue()+1));
		$FechaIndx 	= date($format = "Ymd", PHPExcel_Shared_Date::ExcelToPHP($hoja -> getCell('F'.$row)->getCalculatedValue()+1));
        $Vehiculo   = htmlspecialchars($hoja -> getCell('G'.$row)->getCalculatedValue(),ENT_QUOTES,'UTF-8');			
		$Codigo 	= htmlspecialchars($hoja -> getCell('H'.$row)->getCalculatedValue(),ENT_QUOTES,'UTF-8');
		$TipoCliente= htmlspecialchars($hoja -> getCell('I'.$row)->getCalculatedValue(),ENT_QUOTES,'UTF-8'); //no es necesario este columna
		$ZNPVTA 	= htmlspecialchars($hoja -> getCell('J'.$row)->getCalculatedValue(),ENT_QUOTES,'UTF-8'); 
		$ZNTVTA 	= htmlspecialchars($hoja -> getCell('K'.$row)->getCalculatedValue(),ENT_QUOTES,'UTF-8');
		$Cliente 	= htmlspecialchars($hoja -> getCell('L'.$row)->getCalculatedValue(),ENT_QUOTES,'UTF-8');
		$Direccion  = htmlspecialchars($hoja -> getCell('M'.$row)->getCalculatedValue(),ENT_QUOTES,'UTF-8');
		$Distrito   = htmlspecialchars($hoja -> getCell('N'.$row)->getCalculatedValue(),ENT_QUOTES,'UTF-8');
		$Latitud 	= htmlspecialchars($hoja -> getCell('O'.$row)->getCalculatedValue(),ENT_QUOTES,'UTF-8');		
		$Longitud 	= htmlspecialchars($hoja -> getCell('P'.$row)->getCalculatedValue(),ENT_QUOTES,'UTF-8');
		$Viaje 		= htmlspecialchars($hoja -> getCell('Q'.$row)->getCalculatedValue(),ENT_QUOTES,'UTF-8');
		$HL 		= htmlspecialchars($hoja -> getCell('R'.$row)->getCalculatedValue(),ENT_QUOTES,'UTF-8');
		$Packs 		= htmlspecialchars($hoja -> getCell('S'.$row)->getCalculatedValue(),ENT_QUOTES,'UTF-8');
		$UC 		= htmlspecialchars($hoja -> getCell('T'.$row)->getCalculatedValue(),ENT_QUOTES,'UTF-8');
		$Envases 	= htmlspecialchars($hoja -> getCell('U'.$row)->getCalculatedValue(),ENT_QUOTES,'UTF-8');
		$SupVentas 	= htmlspecialchars($hoja -> getCell('V'.$row)->getCalculatedValue(),ENT_QUOTES,'UTF-8');
		$SalesManag = htmlspecialchars($hoja -> getCell('W'.$row)->getCalculatedValue(),ENT_QUOTES,'UTF-8');
		$DistKm 	= htmlspecialchars($hoja -> getCell('X'.$row)->getCalculatedValue(),ENT_QUOTES,'UTF-8');
		$Inicio 	= htmlspecialchars($hoja -> getCell('Y'.$row)->getCalculatedValue(),ENT_QUOTES,'UTF-8');
		$Fin 		= htmlspecialchars($hoja -> getCell('Z'.$row)->getCalculatedValue(),ENT_QUOTES,'UTF-8');
		$CodET 		= htmlspecialchars($hoja -> getCell('AA'.$row)->getCalculatedValue(),ENT_QUOTES,'UTF-8');
		$TelefCli 	= htmlspecialchars($hoja -> getCell('AB'.$row)->getCalculatedValue(),ENT_QUOTES,'UTF-8');
		$TipoPedido	= htmlspecialchars($hoja -> getCell('AC'.$row)->getCalculatedValue(),ENT_QUOTES,'UTF-8');
		$ValorCont 	= htmlspecialchars($hoja -> getCell('AD'.$row)->getCalculatedValue(),ENT_QUOTES,'UTF-8');
		$ValorCred 	= htmlspecialchars($hoja -> getCell('AE'.$row)->getCalculatedValue(),ENT_QUOTES,'UTF-8');
		$DeudaEnvas	= htmlspecialchars($hoja -> getCell('AF'.$row)->getCalculatedValue(),ENT_QUOTES,'UTF-8');
		$DeudaProdt	= htmlspecialchars($hoja -> getCell('AG'.$row)->getCalculatedValue(),ENT_QUOTES,'UTF-8');
		$centro		= htmlspecialchars($hoja -> getCell('AH'.$row)->getCalculatedValue(),ENT_QUOTES,'UTF-8');
		$Tipo_Riesg = htmlspecialchars($hoja -> getCell('AI'.$row)->getCalculatedValue(),ENT_QUOTES,'UTF-8');
		$Servicio_F = htmlspecialchars($hoja -> getCell('AJ'.$row)->getCalculatedValue(),ENT_QUOTES,'UTF-8');
		$Tipo_NPS   = htmlspecialchars($hoja -> getCell('AK'.$row)->getCalculatedValue(),ENT_QUOTES,'UTF-8');
		$RMD = round(htmlspecialchars($hoja -> getCell('AL'.$row)->getCalculatedValue(),ENT_QUOTES,'UTF-8'),2);
		$TIPO = htmlspecialchars($hoja -> getCell('AM'.$row)->getCalculatedValue(),ENT_QUOTES,'UTF-8');
		$Rechazos = htmlspecialchars($hoja -> getCell('AN'.$row)->getCalculatedValue(),ENT_QUOTES,'UTF-8');
		//$FechaPlan = date_create($FechaPlan);
		//$FechaPlan = date_format($FechaPlan, 'Ymd');	
		
		$indx = $Ruta.$FechaIndx.$Codigo.$Viaje;
		
			/*	
		echo $Region.'|'.$CD.'|'.$Empresa.'|'.$Transporte.'|'.$Ruta.'|'.$Vehiculo.'|'.$FechaPlan.'|'.$Codigo.'|'.$TipoCliente.'|'.$ZNPVTA.'|'.$ZNTVTA.'|'.
		$Cliente.'|'.$Direccion.'|'.$Viaje.'|'.$DistKm.'|'.$UC.'|'.$Envases.'|'.$Distrito.'|'.$Longitud.'|'.$Latitud.'|'.$HL.'|'.$Packs.'|'.$SupVentas.'|'.
		$SalesManag.'|'.$Inicio.'|'.$Fin.'|'.$CodET.'|'.$TelefCli.'|'.$TipoPedido.'|'.$ValorCont.'|'.$ValorCred.'|'.$DeudaEnvas.'|'.$DeudaProdt.'<br>';
		
			*/
		
		$sql = "INSERT INTO `t77_rs_sap`
		(
	 `id`, `indx`, `centro`, `Transporte`, `Ruta`, `Vehiculo`, `Fecha`, `Codigo`, `TipoCliente`, `ZNPVTA`, `ZNTVTA`, `Cliente`, 
	 `Direccion`, `Viaje`, `DistKm`, `Entrega`, `Recojo`, `Ciudad`, `Longitud`, `Latitud`, `HL`, `Packs`, `SalesSupervisionID`, 
	 `SalesManagement`, `InicioViaje`, `FinViaje`, `Cod_ET`, `TelefonoCliente`, `TipoPedido`, `ValorContado`, `VarlorCredito`,
	 `DeudaEnv`, `DeudaProd`, `Tipo_Riesgo`, `Servicio_Flex`, `Tipo_NPS`, `RMD`, `TIPO`, `Rechazos` 
		) VALUES 
		(null,'$indx','$centro','$Transporte','$Ruta','$Vehiculo','$FechaPlan',$Codigo,'$TipoCliente','$ZNPVTA','$ZNTVTA',
		'$Cliente','$Direccion',$Viaje,$DistKm,$UC,$Envases,'$Distrito',$Longitud,$Latitud,$HL,$Packs,'$SupVentas',
		'$SalesManag','$Inicio','$Fin',$CodET,'$TelefCli','$TipoPedido',$ValorCont,$ValorCred,$DeudaEnvas,$DeudaProdt,'$Tipo_Riesg','$Servicio_F','$Tipo_NPS',$RMD,'$TIPO','$Rechazos')";
			 $insert=$db->prepare($sql);  
		     $insert->execute();
	

    }
	Db::desconectar();
	}
	endswitch;
	
	} else { echo "no tienes permiso para acceder a esta seccion ".$accesos.'-'.$aid.'<br><a  href="index">Inicio</a>'; }
	}
    ob_end_flush();	
?>
</main>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
      <script>window.jQuery || document.write('<script src="../assets/js/vendor/jquery.slim.min.js"><\/script>')</script><script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.9.0/feather.min.js"></script>
		  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
		   <script>
		        $('input[type="file"]').on('change', function(){
            var ext = $( this ).val().split('.').pop();
            if ($( this ).val() != '') {
            if(ext == "xls" || ext == "xlsx" || ext == "csv"){
            }
            else
            {
                $( this ).val('');
                Swal.fire("Mensaje De Error","Extensión no permitida: " + ext+"","error");
            }
            }
        });
		
		
		
		  </script>
</body>
</html>