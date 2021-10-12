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
	$bootstrapjs =  1;	
	$mapasjs =  0;
	$datatablesjs = 0;
	require('../head.php');
	if(isset($_GET['fechaselec'])): 
	$fecha_form = $_GET['fechaselec'];
	$fechars = $_GET['fechaselec'];	
	else:
	$fechars = $fechars; 
	$fecha_form = $fecha;
	endif;
	/*fin includes head systen ini*/
	if (isset($_GET['rs'])): $rs = $_GET['rs']; else: $rs = ""; endif;
	function busca_cliente_fefo($sku,$fechaV){
	global $fechars,$idcentro,$fecha_form,$db,$disableform,$aid,$crud,$sitio;
	if(isset($aid,$sku)){
	 echo "<br><p class='text-muted'><b> Palabra clave: </b> ". $sku."</p>";
	 $db=Db::conectar();
	 $select=$db->prepare("SELECT
							`material`, `descripcion`, `rt_nr`, 
							`factor`, `pallet`, `cama`, `categ`, 
							`marca`, `capacidad`, `cuarenta_cinco`, 
							`sesenta`, `cien`, `apilado`, `tipo`, `abc`,
							DATE_ADD('$fechaV', INTERVAL -`cien` DAY) as FP,
							DATE_ADD('$fechaV', INTERVAL -(`cien`-`cuarenta_cinco`) DAY) as FPB, 
							DATE_ADD('$fechaV', INTERVAL -(`cien`-`sesenta`) DAY) as FB,
							DATEDIFF('$fechaV', DATE_ADD('$fechaV', INTERVAL -(`cien`-`sesenta`) DAY)) as FBD
	  					 FROM `fefo` WHERE centro='$idcentro' AND material = '$sku'");
	 $select->execute();
     if($select->rowCount() > 0) {
		while ($row=$select->fetch()) {
		if($row['FP'] >= $fechars) { $FP = "Revisar fecha vencimiento"; $classfp = 'class="text-danger"'; } else { $FP = "Ok"; $classfp = ''; } 
		if($row['FPB'] <= $fechars) { $FPB = "Riesgo de merma"; $classfpb = 'class="text-danger"'; } else {  $FPB = "Ok"; $classfpb = ''; } 
		echo '<div class="table-responsive-sm"><table class="table table-sm table-bordered">
		<tbody>
		<tr>
		<th scope="row">Codigo:</th>
		<td>'.$row['material'].'</td>
		<td>'.$row['descripcion'].'</td>	
	    </tr> 
		<tr>
		<th scope="row">F. Vencimiento:</th>
		<td colspan="2" class="text-center table-danger">'.$fechaV.'</td>
	    </tr> 
		<tr>
		<th scope="row">F. Produccion:</th>
		<td>'.$row['FP'].'</td>
		<td '.$classfp.'>'.$FP.'</td>	
	    </tr>
		<tr>
		<th scope="row">F. P Bloqueo (45%) :</th>
		<td>'.$row['FPB'].'</td>
		<td '.$classfpb.' >'.$FPB.'</td>	
	    </tr>
		<tr>
		<th scope="row">F. Bloqueo (60%) :</th>
		<td>'.$row['FB'].'</td>
		<td> A '.$row['FBD'].' dias para bloqueo</td>	
	    </tr>	
		<tr>
		<th scope="row">Producto ABC :</th>
		<td colspan="2" class="text-center table-secondary">'.$row['abc'].'</td>	
	    </tr>
		<tr>
		<th scope="row">PH :</th>
		<td colspan="2" class="text-center table-secondary">'.$row['pallet'].'</td>	
	    </tr>
		<tr>
		<th scope="row">Factor :</th>
		<td colspan="2" class="text-center table-secondary">'.$row['factor'].'</td>	
	    </tr>
		<tr>
		<th scope="row">Cama :</th>
		<td colspan="2" class="text-center table-secondary">'.$row['cama'].'</td>	
	    </tr>
		<tr>
		<th scope="row">Apilado :</th>
		<td colspan="2" class="text-center table-secondary">'.$row['apilado'].'</td>
	    </tr>	
		</tbody>
		</table></div>';
		} 
        } else {
			echo "<p class='text-muted'>Resultados encontrados  $idcentro : Ninguno </p>";	
		}
		Db::desconectar();
    }
	}
	switch ($rs):	 	
    default:
	?>
	<div class="row">
    <div class="col-sm-12 bg-highlight">
	<div class="d-flex justify-content-left">
	<div class="p-2 bd-highlight"><p class="h4 text-danger"><?php echo 'FEFO APP '.$idcentro;  ?></p></div>
	</div>
	</div>
	</div>
	<form method="GET">
	<div class="form-row">
    <div class="form-group col-md-6">
      <label for="sku">Codigo Material</label>
      <input id="sku" required name="sku" type="text"   placeholder="codigo sku" class="form-control" required>
    </div>
    <div class="form-group col-md-6">
      <label for="vf">Fecha Vencimiento</label>
      <input id="vf" required name="fechaV" type="date"   class="form-control" value="<?php if(isset($_GET['fechaV'])){ echo $_GET['fechaV']; } ?>" required>
    </div>
  	</div>
		  <button type="submit" class="btn btn-danger btn-lg btn-block">Buscar</button>	
	</form>	
	<?php 
		if(isset($_GET['sku'],$_GET['fechaV'])){ 
			busca_cliente_fefo($_GET['sku'],$_GET['fechaV']);
		} else {
		}
	endswitch;
	} else {
     echo $html_acceso;		
	}
	}
	require('../footer.php');
	ob_end_flush();	
	?>