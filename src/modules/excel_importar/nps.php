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
    $selectrs=$dbrs->prepare("SELECT SUM(a.Contar) as Contactos,MAX(a.updated_at) as actulizado FROM (SELECT 1 as Contar,updated_at FROM t77_nps WHERE centro='$idcentro') AS a");
	$selectrs->execute();
    $rowrs=$selectrs->fetch();
	$Contactos=$rowrs['Contactos'];
	$actulizado=$rowrs['actulizado'];
	Db::desconectar();
	if ($Contactos > 0){ $disablegrabar=''; $disablecancelar='disabled'; } else { $disablegrabar='disabled'; $disablecancelar=''; }
	if (isset($_GET['op'])){ $op = $_GET['op']; } else { $op = ""; }
	
function tabla_fbl5n($Contactos,$actulizado){
	global $idcentro,$idcentrotb;
   
   $cabezastb='id,centro,codcli,Tipo_reclamo,observacion'; 
   $arrayHTB = explode(",", $cabezastb);
?> 
<div class="alert alert-success" role="alert">
Registros : <?php echo $Contactos; ?>  Actualizado: <?php echo $actulizado;?> 
</div>
	<div class="table-responsive">
		<table class="table table-sm table-bordered">
		<thead>
		<tr>
		<?php 
		foreach($arrayHTB as $element) {    
		echo "<th>$element</th>";
		}
		?>
		</tr>
		</thead>	
         <?php
		  $db=Db::conectar();
          $select=$db->prepare("SELECT `id`, `centro`, `codcli`, `Tipo_reclamo`, `observacion` FROM t77_nps WHERE centro=:centro");
		  $select->bindValue('centro',$idcentro);
		  $select->execute();
          while ($registro=$select->fetch()) { 	
		?>   
		<tr>		
		<?php 
		 for ($i = 0; $i <= 4; $i++) {
		echo "<td>".$registro[$i]."</td>"; 
		 }
		?>
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
			$detele=$db->prepare("DELETE FROM t77_nps WHERE centro=:centro");
		    $detele->bindValue('centro',$idcentro);
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
	header('Location: nps');
	echo "xx";
	
    break;
    case "enviarrsdb":
	    $sqlverifi="SELECT Fecha FROM t77_nps GROUP BY Fecha";
		$fecharsverifi=sacar_fecha($sqlverifi);	
	    if($crud->contardbuser('Fecha','t77_rs','Fecha = "'.$fecharsverifi.'" AND centro="'.$idcentro.'"')>0){
			?> 
		   <div class="alert alert-danger" role="alert">Existen registros con la misma fecha.</div>
		   <?php
         header('Refresh: 3; URL=http://www.bk77.co/nps');		   
		} else {
	      $db=Db::conectar();
          $select=$db->prepare("SELECT * FROM plan_sap_".$idcentrotb."");
		  $select->execute();
		  Db::desconectar();
          while ($inserrs=$select->fetch()) {
			$db=Db::conectar();  
			$insert=$db->prepare("INSERT INTO t77_rs VALUES(null,0,0,0,'',0,'".$idcentro."','','','0000-00-00 00:00:00','0000-00-00 00:00:00','".$inserrs[0]."','".$inserrs[1]."','".$inserrs[2]."','".$inserrs[3]."','".$inserrs[4]."','".$inserrs[5]."','".$inserrs[6]."','".$inserrs[7]."','".$inserrs[8]."','".$inserrs[9]."','".$inserrs[10]."','".$inserrs[11]."','".$inserrs[12]."','".$inserrs[13]."','".$inserrs[14]."','".$inserrs[15]."','".$inserrs[16]."','".$inserrs[17]."','".$inserrs[18]."','".$inserrs[19]."','".$inserrs[20]."','".$inserrs[21]."')");  
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
		    header('Refresh: 3; URL=http://www.bk77.co/nps');	
		  } 
		}
        break;
	case "modificatema":
         break;
	case "ModificaSeccion":
        break;				
    default:	
?>
<p class="h3"> Clientes NPS : <?php echo $idcentro; ?></p>
<a href="img/formato_nps.xlsx" class="badge badge-info" target="_blank" >Formato clientes nps</a>
<form action="nps" method="post" enctype="multipart/form-data">
   <div class="form-group"> 
   <label for="FormControlFile1">Importar archivo mensaje hoja ruta</label>
   <input type="file" name="archivoexcel" class="form-control-file"  id="FormControlFile1" <?php echo $disablecancelar; ?> accept=".xlsx" required>
   <button class="btn btn-danger btn-sm" type="submit"name="submit" <?php echo $disablecancelar; ?> >Cargar excel</button>
   <button class="btn btn-danger btn-sm" type="button" onclick="location.href='excel_importar_nps?op=borrarbdrs';" <?php echo $disablegrabar; ?> >Borrar datos cargados</button>
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
		$tipo_recla	= htmlspecialchars($hoja -> getCell('A'.$row)->getCalculatedValue(),ENT_QUOTES,'UTF-8'); //ok
        $codcli   	= htmlspecialchars($hoja -> getCell('B'.$row)->getCalculatedValue(),ENT_QUOTES,'UTF-8');	
		$observacio = htmlspecialchars($hoja -> getCell('C'.$row)->getCalculatedValue(),ENT_QUOTES,'UTF-8');	
			 $insert=$db->prepare("
			 INSERT INTO t77_nps 
(`id`, `centro`, `codcli`, `Tipo_reclamo`, `observacion`, `updated_at`)
			 VALUES
(null,'$idcentro','$codcli','$tipo_recla','$observacio','$fecha_hora')
			 ");  
			 
		     $insert->execute();
		     header('Location: nps');
	}
	Db::desconectar();
	}
	
	
	if($Contactos > 0){
	tabla_fbl5n($Contactos,$actulizado);
	} else {  ?> <p class="text-muted">No hay datos cargados</p> <?php } 
	endswitch;
	} else {
	echo $html_acceso;		
	}
	}
	require('../footer.php');
	ob_end_flush();	
	?>