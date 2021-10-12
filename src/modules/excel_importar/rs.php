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
    $selectrs=$dbrs->prepare("SELECT SUM(a.Contar) as Contactos , SUM(a.Entrega) as Cajas  FROM (SELECT 1 as Contar , `Codigo`, `Entrega`,centro FROM t77_rs_temp_centros WHERE centro = '$idcentro') AS a");
	$selectrs->execute();
    $rowrs=$selectrs->fetch();
	$Contactos=$rowrs['Contactos'];
	$Cajas=$rowrs['Cajas'];
	Db::desconectar();
	if (isset($_GET['op'])){ $op = $_GET['op']; } else { $op = ""; }
function tabla_rs($contactos, $cajas){
	global $idcentro,$fechars;	
?>  <p class="text-muted">Contactos: <?php echo $contactos; ?> Cajas: <?php echo $cajas; ?></p>
	<div class="table-responsive">
		<table class="table table-sm table-bordered">
		<thead>
		<tr>
		<th>#</th>
		<th>Centro</th>
		<th>Fecha</th>
		<th>Ruta</th>
		<th>Vehiculo</th>
		<th>Viaje</th>
		<th>Contactos</th>
		<th>Entrega</th>
		</tr>
		</thead>	
         <?php
		  $n=1;
		  $db=Db::conectar();
          $select=$db->prepare("SELECT a.`centro`, a.`Fecha`, a.`Ruta`, a.`Vehiculo`, a.`Viaje`, COUNT(a.`Codigo`) AS Contactos , SUM(a.`Entrega`) AS Entrega 
		  FROM `t77_rs` AS a WHERE centro='$idcentro' AND Fecha = '$fechars'
		  GROUP BY a.`centro`, a.`Fecha`, a.`Ruta`, a.`Vehiculo`, a.`Viaje`");
		  $select->execute();
          while ($registro=$select->fetch(PDO::FETCH_ASSOC)) { 	
			?>   
			<tr>		
			<td><?php echo $n; ?></td>
			<td><?php echo $registro['centro']; ?></td>
			<td><?php echo $registro['Fecha']; ?></td>
			<td><?php echo $registro['Ruta']; ?></td>
			<td><?php echo $registro['Vehiculo']; ?></td>
			<td><?php echo $registro['Viaje']; ?></td>
			<td><?php echo $registro['Contactos']; ?></td>
			<td><?php echo $registro['Entrega']; ?></td>
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
			$detele=$db->prepare("DELETE FROM t77_rs_temp_centros WHERE centro = '$idcentro'");
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
	header('Location: rs');
	echo "xx";
	
    break;
    case "enviarrsdb":
	    $sqlverifi="SELECT Fecha FROM t77_rs_temp_centros WHERE centro = '$idcentro' GROUP BY Fecha";
		$fecharsverifi=sacar_fecha($sqlverifi);	
	    if($crud->contardbuser('Fecha','t77_rs','Fecha = "'.$fecharsverifi.'" AND centro="'.$idcentro.'"')>0){
			?> 
		   <div class="alert alert-danger" role="alert">Existen registros con la misma fecha.</div>
		   <?php
         header('Refresh: 3; URL=rs');		   
		} else {
	      $db=Db::conectar();
          $select=$db->prepare("SELECT * FROM t77_rs_temp_centros WHERE centro = '$idcentro'");
		  $select->execute();
		  Db::desconectar();
          while ($inserrs=$select->fetch()) {
			$db=Db::conectar();  
			$indx = $inserrs[0].str_replace("-","",$inserrs[2]).$inserrs[3].$inserrs[11];
			$insert=$db->prepare("INSERT INTO t77_rs VALUES
			(null,'".$indx."',0,0,0,'',0,'".$idcentro."','','','0000-00-00 00:00:00','0000-00-00 00:00:00','','',0,0,'',0,0,'',
			'".$inserrs[0]."','".$inserrs[1]."','".$inserrs[2]."','".$inserrs[3]."','".$inserrs[4]."','".$inserrs[5]."','".$inserrs[6]."','".$inserrs[7]."','".$inserrs[8]."','".$inserrs[9]."','".$inserrs[10]."','".$inserrs[11]."','".$inserrs[12]."','".$inserrs[13]."','".$inserrs[14]."','".$inserrs[15]."','".$inserrs[16]."','".$inserrs[17]."','".$inserrs[18]."','".$inserrs[19]."','".$inserrs[20]."','".$inserrs[21]."',
			0,0,'','',0,'00:00:00','00:00:00',0,'','',0,0,0,0,'','','',0,'','')");  
		    $insert->execute();	  
		  }
		  Db::desconectar();
		  $lastInsertId = $db->lastInsertId();
		  if($lastInsertId>0){
			  borrar_rs_tem();
		   ?> 
		   <div class="alert alert-success" role="alert">Se registro</div>	   
		   <?php 
		    header('Refresh: 3; URL=entregas');
		  }else{ 
		   ?> 
		   <div class="alert alert-danger" role="alert">no se pudo registrar</div>	   
		   <?php 
		    header('Refresh: 3; URL=rs');	
		  } 
		}
        break;
	case "modificatema":
         break;
	case "ModificaSeccion":
        break;				
    default:	
?>
	<p class="h3"> Hoja de ruta rs : <?php echo $idcentro; ?></p>
	<a href="../../img/CARGA DE RS.pdf" class="badge badge-info" target="_blank" >Manual importar rs</a>
	<?php if ($Contactos == 0) { ?>
	<form action="rs" method="post" enctype="multipart/form-data">
	<label for="FormControlFile1">Importar archivo RS</label>
	<input type="file" name="archivoexcel" class="form-control-file"  id="exampleFormControlFile1" accept=".xlsx" required >
	<input name="archivo" type="hidden" value="archivo">
	<input type="submit" onclick="this.disabled=true;this.value='Espere por favor...';
	this.form.submit(); setTimeout(() => { this.value = 'Guardar'; this.disabled= false; }, 999999);
		"class="btn btn-danger btn-sm" id="btnsubmit" value="Cargar excel">
	</form>
	<br>
	<?php } else { ?>
	<div class="form-inline"> 
	<form onsubmit="return confirm('Por favor revise los datos rutas, contactos y fecha... de ser correctos enviar.');" name="enviar" action="rs?op=enviarrsdbxxx" method='GET'>
		<input name="op" type="hidden" value="enviarrsdbxxx">
		<button class="btn btn-danger btn-sm" type="submit">Grabar publicar</button>
	</form>
	<form onsubmit="return confirm('Esta seguro de borrar, tendras que cargar nuevamente...');" name="borrar" action="rs" method='GET'>
		<input name="op" type="hidden" value="borrarbdrs">
		<button class="btn btn-danger btn-sm" type="submit">Borrar datos cargados</button>
	</form>
	</div>	
	<?php } ?>
<?php	
  if(isset($_FILES['archivoexcel']) && $_FILES['archivoexcel']['name'] != null){
	require('phpexcel/Classes/PHPExcel.php');
    $tmpfname = $_FILES['archivoexcel']['tmp_name'];
	$leerexcel = PHPExcel_IOFactory::createReaderForFile($tmpfname);
    $excelobj = $leerexcel->load($tmpfname);
    $hoja = $excelobj -> getSheet(0);
    $filas = $hoja -> getHighestRow();	
    $db=DB::conectar();
	for ($row = 13;$row<=$filas;$row++){
		$Ruta 	    = htmlspecialchars($hoja -> getCell('A'.$row)->getCalculatedValue(),ENT_QUOTES,'UTF-8');
        $Vehiculo   = htmlspecialchars($hoja -> getCell('B'.$row)->getCalculatedValue(),ENT_QUOTES,'UTF-8');	
		$Fecha 		= htmlspecialchars($hoja -> getCell('C'.$row)->getCalculatedValue(),ENT_QUOTES,'UTF-8');
		$Codigo 	= htmlspecialchars($hoja -> getCell('D'.$row)->getCalculatedValue(),ENT_QUOTES,'UTF-8');
		$TipoCliente= htmlspecialchars($hoja -> getCell('E'.$row)->getCalculatedValue(),ENT_QUOTES,'UTF-8'); //no es necesario este columna
		$PVTA 		= htmlspecialchars($hoja -> getCell('F'.$row)->getCalculatedValue(),ENT_QUOTES,'UTF-8'); 
		$TVTA 		= htmlspecialchars($hoja -> getCell('G'.$row)->getCalculatedValue(),ENT_QUOTES,'UTF-8');
		$Cliente 	= htmlspecialchars($hoja -> getCell('H'.$row)->getCalculatedValue(),ENT_QUOTES,'UTF-8');
		$Direccion  = htmlspecialchars($hoja -> getCell('I'.$row)->getCalculatedValue(),ENT_QUOTES,'UTF-8');
		$Abre 		= htmlspecialchars($hoja -> getCell('J'.$row)->getCalculatedValue(),ENT_QUOTES,'UTF-8');
		$Cierra 	= htmlspecialchars($hoja -> getCell('K'.$row)->getCalculatedValue(),ENT_QUOTES,'UTF-8');
		$Viaje 		= htmlspecialchars($hoja -> getCell('L'.$row)->getCalculatedValue(),ENT_QUOTES,'UTF-8');
		$Dist 		= htmlspecialchars($hoja -> getCell('M'.$row)->getCalculatedValue(),ENT_QUOTES,'UTF-8'); //no es necesario este columna
		$Sec2 		= htmlspecialchars($hoja -> getCell('N'.$row)->getCalculatedValue(),ENT_QUOTES,'UTF-8'); //no es necesario este columna
		$Sec1 		= htmlspecialchars($hoja -> getCell('O'.$row)->getCalculatedValue(),ENT_QUOTES,'UTF-8');
		$Entrega 	= htmlspecialchars($hoja -> getCell('P'.$row)->getCalculatedValue(),ENT_QUOTES,'UTF-8');
		$Recojo 	= htmlspecialchars($hoja -> getCell('Q'.$row)->getCalculatedValue(),ENT_QUOTES,'UTF-8');
		$Llega 		= htmlspecialchars($hoja -> getCell('R'.$row)->getCalculatedValue(),ENT_QUOTES,'UTF-8');
		$Sale 		= htmlspecialchars($hoja -> getCell('S'.$row)->getCalculatedValue(),ENT_QUOTES,'UTF-8');
		$Ciudad 	= htmlspecialchars($hoja -> getCell('T'.$row)->getCalculatedValue(),ENT_QUOTES,'UTF-8');
		$Longitud 	= htmlspecialchars($hoja -> getCell('U'.$row)->getCalculatedValue(),ENT_QUOTES,'UTF-8');
		$Latitud 	= htmlspecialchars($hoja -> getCell('V'.$row)->getCalculatedValue(),ENT_QUOTES,'UTF-8');
	    $Fecha = str_replace("/","-",$Fecha);
		$Fecha = date_create($Fecha);
	    $Fecha = date_format($Fecha,"Y-m-d");
		$centroruta = substr($Ruta,0,4);
		if ($centroruta==$idcentro) { 
			 $insert=$db->prepare("INSERT INTO t77_rs_temp_centros VALUES('".$Ruta."','".$Vehiculo."','".$Fecha."','".$Codigo."','".$TipoCliente."','".$PVTA."','".$TVTA."','".ucwords(mb_strtolower($Cliente))."','".ucwords(mb_strtolower($Direccion))."','".$Abre."','".$Cierra."','".$Viaje."','','','".$Sec1."','".$Entrega."','".$Recojo."','".$Llega."','".$Sale."','".ucwords(mb_strtolower($Ciudad))."','".$Longitud."','".$Latitud."','$centroruta')");  
		     $insert->execute();
		     header('Location: rs');
		} else {
			 $errorcentro = 'error centro incorrecto';
		}
	}
		echo  '<div class="alert alert-danger" role="alert">'.$errorcentro.'</div>';
	Db::desconectar();
	}
	if($Contactos > 0){
	tabla_rs($Contactos,$Cajas);
	} else {  ?> <p class="text-muted"> No hay datos cargados </p> <?php } 
	endswitch;
	} else {
	echo $html_acceso;		
	}
	}
	require('../footer.php');
	ob_end_flush();	
	?>