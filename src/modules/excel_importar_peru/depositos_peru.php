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
	////
	if ($crud->contardbuser('id','t77_rs_depositos','fecha = "'.$fecha.'"') > 0): $disablegrabar=''; $disablecancelar='disabled';  else:  $disablegrabar='disabled'; $disablecancelar=''; endif;
	if (isset($_GET['op'])){ $op = $_GET['op']; } else { $op = ""; }

function tabla(){
	global $fechars;
          $sql = "SELECT `fecha`,`banco`, COUNT(`codigocliente`) as registros,
		  sum(`importe`) as importe 
		  FROM `t77_rs_depositos`  WHERE  fecha='$fechars' 
		  GROUP by  `fecha`, `banco`";
		  $db=Db::conectar();
          $select=$db->prepare($sql);
		  $select->execute();
	      $cuenta_col = $select->rowCount();
		  //$result = $select->fetchAll(PDO::FETCH_COLUMN, 3);

		  echo '<div class="form-row">
		  <div class="mb-3">
			  <form action="depositos_peru" method="GET" >
			  <label for="fecha">Fecha</label>
			  <input type="date" name="fechaselec" id="fecha" value="'.$fechars.'">
			  <button type="submit" class="btn btn-danger btn-sm">Ver</button>
			  </form>				
		  </div>
		  </div>';
?>  
	<p class="text-muted">Registros: <?php echo $cuenta_col; ?></p>
	<div class="table-responsive">
		<table class="table table-sm table-bordered">
		<thead>
		<tr class="table-active">
			<th>Fecha</th>
			<th>Banco</th>
			<th>Registros</th>
			<th>Importe</th>
		</tr>
		</thead>
		<tbody> 	
         <?php 
		  $i=0;
          while (($registro=$select->fetch()) && ($i < 10)) { 	
		?>   
		<tr>		
		<td ><?php echo $registro["fecha"]; ?></td>
		<td><?php echo $registro["banco"]; ?></td>
		<td class="ColTd1A"><?php echo $registro["registros"]; ?></td>
		<td class="ColTd1B"><?php echo number_format($registro["importe"], 2, '.', ''); ?></td>
		</tr>
		<?php
		$i++;
		}
		Db::desconectar();
		?>
        </tbody> 
		<tfoot>
		<tr>
		<td scope="row">Total</td>
		<td scope="row"></td>
		<td scope="row" class="TotalTd1 A"></td>
		<td scope="row" class="TotalTd1 B"></td>
		</tr>	
		</tfoot>
		</table>
     </div>	
	<script>
			document.querySelectorAll('.TotalTd1').forEach(function (TotalTd1) {
		if (TotalTd1.classList.length > 1) {
			var letra = TotalTd1.classList[1];
			var suma = 0;
			document.querySelectorAll('.ColTd1' + letra).forEach(function (celda) {
				var valor = parseInt(celda.innerHTML);
				suma += valor;
					});
					TotalTd1.innerHTML = suma;
				}
			});
	</script>	
<?php	
}
function borrar(){
global $db,$fechars;
			$db=DB::conectar();
			$detele=$db->prepare("DELETE FROM t77_rs_depositos WHERE fecha='$fechars'");
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
    case "borrardepositosdeldia":
	borrar();
	header('Location: depositos_peru');
    break;
    case "enviarrsdbdsfsdfsf":
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
		  Db::desconectar();
          while ($inserrs=$select->fetch()) {
			$db=Db::conectar();  
			$indx = $inserrs[0].str_replace("-","",$inserrs[2]).$inserrs[3].$inserrs[11];
			$insert=$db->prepare("INSERT INTO t77_rs VALUES
			(null,'".$indx."',0,0,0,'',0,'".$idcentro."','','','0000-00-00 00:00:00','0000-00-00 00:00:00','','',0,0,'',0,0,'',
			'".$inserrs[0]."','".$inserrs[1]."','".$inserrs[2]."','".$inserrs[3]."','".$inserrs[4]."','".$inserrs[5]."','".$inserrs[6]."','".$inserrs[7]."','".$inserrs[8]."','".$inserrs[9]."','".$inserrs[10]."','".$inserrs[11]."','".$inserrs[12]."','".$inserrs[13]."','".$inserrs[14]."','".$inserrs[15]."','".$inserrs[16]."','".$inserrs[17]."','".$inserrs[18]."','".$inserrs[19]."','".$inserrs[20]."','".$inserrs[21]."',
			0,0,'','',0,'00:00:00','00:00:00',0,'','',0,0,0,0)");  
		    $insert->execute();	  
		  }
		  Db::desconectar();
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
        break;
	case "modificatema":
         break;
	case "ModificaSeccion":
        break;				
    default:	
?>
<p class="h3">Importar depósitos bancarios : Peru</p>
<form action="depositos_peru" method="post" enctype="multipart/form-data">
   <div class="form-group">
   <label for="FormControlFile1">Importar depósito bancarios</label>
   <input type="file" name="archivoexcel" class="form-control-file"  id="exampleFormControlFile1" <?php echo $disablecancelar; ?> accept=".xlsx" required>
   <button class="btn btn-danger btn-sm" type="submit"name="submit" <?php echo $disablecancelar; ?> >Cargar excel</button>
   <button class="btn btn-danger btn-sm" type="button" <?php echo $disablegrabar; ?> onclick="location.href='depositos_peru?op=borrardepositosdeldia';" >Borrar depósito del dia</button>
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
		$Fecha  	= date($format = "Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($hoja -> getCell('A'.$row)->getCalculatedValue()+1));
        $CodClie    = htmlspecialchars($hoja -> getCell('B'.$row)->getCalculatedValue(),ENT_QUOTES,'UTF-8');	
		$Banco 		= htmlspecialchars($hoja -> getCell('C'.$row)->getCalculatedValue(),ENT_QUOTES,'UTF-8');
		$Importe 	= round(htmlspecialchars($hoja -> getCell('D'.$row)->getCalculatedValue(),ENT_QUOTES,'UTF-8'),2);
		$NroDeposit = round(htmlspecialchars($hoja -> getCell('E'.$row)->getCalculatedValue(),ENT_QUOTES,'UTF-8'),2);
		$order   = array("-", ".");
        $indx = str_replace("-","",$Fecha).$NroDeposit;
			 $insert=$db->prepare("INSERT INTO `t77_rs_depositos`(`id`, `indice` ,`fecha`, `codigocliente`, `banco`, `importe`, `documento`) 
			 VALUES (null,'$indx','$Fecha',$CodClie,'.$Banco.',$Importe, '$NroDeposit')");  
		     $insert->execute();
		     header('Location: depositos_peru');	
	}
	Db::desconectar();
	}
	tabla();
	endswitch;
	} else {
	echo $html_acceso;		
	}
	}
	require('../footer.php');
	ob_end_flush();	
	?>