<?php 
    ob_start();	
    $accesos = basename(dirname(__FILE__));
	require_once('../../includes/ini.php');
	require_once('../../bd/crud_almacen.php');
	$crudAlm=new CrudAlmacen();
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
	$datatablesjs = 1;
	$momentjs = 0;
	require('../head.php');
	if(isset($_GET['fechaselec'])): 
	$fecha_form = $_GET['fechaselec'];
	$fechars = $_GET['fechaselec'];	
	else:
	$fechars = $fechars; 
	$fecha_form = $fecha;
	endif;
	/*fin includes head systen ini*/
	if (isset($_GET['j5xqi9554vUXBmoX9IHXg'])){ $j5xqi9554vUXBmoX9IHXg = $_GET['j5xqi9554vUXBmoX9IHXg']; } else { $j5xqi9554vUXBmoX9IHXg = ""; }
	$turno_sorting = array(1=>'Uno',2=>'Dos');	
	$rutas		 = array(
							'BK7701'=>'BK7701',
							'BK7702'=>'BK7702',
							'BK7703'=>'BK7703',
							'BK7704'=>'BK7704',
							'BK7705'=>'BK7705',
							'BK7706'=>'BK7706',
							'BK7707'=>'BK7707',
							'BK7708'=>'BK7708',
							'BK7709'=>'BK7709',
							'BK7710'=>'BK7710',
							'BK7711'=>'BK7711',
							'BK7712'=>'BK7712',
							'BK7713'=>'BK7713',
							'BK7714'=>'BK7714',
							'BK7715'=>'BK7715',
							'BK7716'=>'BK7716',
							'BK7717'=>'BK7717',
							'BK7718'=>'BK7718',
							'BK7719'=>'BK7719',
							'BK7720'=>'BK7720',
							'BK7721'=>'BK7721',
							'BK7722'=>'BK7722',
							'BK7723'=>'BK7723',
							'BK7724'=>'BK7724',
							'BK7725'=>'BK7725',
							'BK7726'=>'BK7726',
							'BK7727'=>'BK7727',
							'BK7728'=>'BK7728'
						);
	$db=Db::conectar();	
	$selectAr=$db->prepare("SELECT dni, concat(nombre,' ',apellidos) as usuario FROM `usuarios` WHERE puesto=16 AND centro='$idcentro'");
	$selectAr->execute();	
	while($row = $selectAr->fetch(PDO::FETCH_ASSOC)) {
		$dni[] = $row['dni'];
		$usuario[] = $row['usuario'];
	}
	Db::desconectar();
	$usuario_cla = array_combine(isset($dni) ? $dni : array() , isset($usuario) ? $usuario : array());					
	/*					
	$usuario_cla = array(
							'44692328'=>'LUIS ALBERTO DURAND BEJAR',
							'7516148'=>'ABRAHAM PARIAPAZA SUCASACA',
							'41229966'=>'RICHER RODRIGUEZ YANA',
							'43058539'=>'YONI CHOQUE CHOQUEPIUNTA',
							'45858868'=>'FREDY VIDAL COILA CUTIPA',
							'47838588'=>'FREDY ARMANDO HUANCOLLO ARAPA',
							'70160283'=>'PERCY MANUEL QUISPE VILCA',
							'71593502'=>'JUAN JOSE MAMANI PERALTA',
							'72725240'=>'ROLHY BRANDON SALINAS MAMANI',
							'72774875'=>'YEFRI JHOEL VILCA LACUTA',
							'73640470'=>'RUBEN PACCO FLORES',
							'74609074'=>'FRANK ELVIS COAQUIRA QUISPE',
							'74761616'=>'CRISTIAN RONALDO ZELA JARA',
							'74864582'=>'JHON FRANKLIN QUISPE MAMANI',
							'76858649'=>'DEYWID ELVIS MAMANI TORRES'
						);
	*/								
	$TREV = array('Triki Trak'=>'Triki Trak','Pucho'=>'Pucho','Muestreo'=>'Muestreo');	
	$TENV = array('620'=>'620','1000'=>'1000');
	function reporte(){
		global $fechars,$idcentro,$usuario_cla;
		echo '<h3>Seguimiento clasificación envases :</h2><div class="table-responsive-sm"> 
		 	   <table id="example" class="display compact" style="width:100%">
        		<thead>
				<tr>
				<th scope="col">#</th>
				<th scope="col">Clasificador</th>
				<th scope="col">Cajas</th>
				<th scope="col">Inicio</th>
				<th scope="col">Final</th>
				<th scope="col">Tiempo</th>
				<th scope="col">Horas</th>
				</tr>
			</thead>
			<tbody>';
		$db=Db::conectar();	
		$select=$db->prepare("
		SELECT `id`, `centro`, `Fecha`, `id_usuario`, `Nombre_Clasif`, `Turno`, `Ruta`, `TREV`, `TENV`, `CJ`, `Inicio`, `Final`,
		TIMEDIFF(Final , Inicio) as Tiempo, TIMESTAMPDIFF(MINUTE, Inicio, Final )/60  as TiempHoras 
		FROM `KPI_Sorting` WHERE centro='$idcentro' AND Fecha='$fechars'	ORDER BY id_usuario ASC	
		");
		$select->execute();	
		$cuenta_col = $select->rowCount();  
		$n=1;
		while ($row=$select->fetch(PDO::FETCH_ASSOC)){
		$id_usuario = $row['id_usuario'];
         
		  

		echo	'<tr>
				<td>'.$n.'</td>
				<td>'.$usuario_cla[$id_usuario].'</td>
				<td class="ColTd1A">'.$row['CJ'].'</td>
				<td>'.$row['Inicio'].'</td>
				<td>'.$row['Final'].'</td>
				<td>'.$row['Tiempo'].'</td>
				<td class="ColTd1B">'.round($row['TiempHoras'],2).'</td>
				</tr>';
		$n++;		 
		}		
		Db::desconectar();
		echo '</tbody>
				<tfoot>
				<tr>
				<td></td>
				<td></td>
				<td class="TotalTd1 A"></td>
				<td></td>
				<td></td>
				<td></td>
				<td class="TotalTd1 B"></td>
				</tr>	
				</tfoot>
			  </table>
			  </div>';	 
			  ?>
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

    function check(){	
		global $fechars,$idcentro,$turno_sorting,$aid,$rutas,$TREV,$TENV,$usuarionombre,$usuarioapellidos,$crudAlm,$fecha_hora,$usuario_cla;
		$fecha_sorting = $fechars;	
		$disabled = '';     
		?>
		<script>
		</script>
		<?php 
		echo '<div class="table-responsive-sm">
		<table id="example" class="display compact" style="width:100%">
			<thead>
				<tr>
				<th scope="col">#</th>
				<th scope="col">Clasificador</th>
				<th scope="col">Cajas</th>
				<th scope="col">Inicio</th>
				<th scope="col">Tiempo</th>
				<th scope="col"></th>
				</tr>
			</thead>
			<tbody>';
		$db=DB::conectar();	
		$select=$db->prepare("SELECT * FROM `KPI_Sorting` WHERE Final is null AND id_usuario='$aid'");
		$select->execute();	
		$cuenta_col = $select->rowCount();
		$n=1;
		while ($row=$select->fetch(PDO::FETCH_ASSOC)){
		$id_usuario = $row['id_usuario'];
		$array_id_usuario[] = $row['id_usuario'];
		echo	'<tr>
				<td>'.$n.'</td>
				<td>'.$usuario_cla[$id_usuario].'</td>
				<td>'.$row['CJ'].'</td>
				<td>'.$row['Inicio'].'</td>
				<td> - </td>
				<td>';
				?><button type="button" class="btn btn-danger btn-sm" onclick="location.href='envases?j5xqi9554vUXBmoX9IHXg=update&Sorting=<?php echo $row['id']; ?>';" >Finalizar</button> <?php
		echo    '</td>
				</tr>';
		$n++;		 
		}		
		Db::desconectar();
		echo '</tbody>
			</table></div>';	 
		if(isset($_POST['sorting'])){
		$sorting 		= $_POST['sorting'];
		$fecha 			= $sorting['fecha'];
		$id_usuario 	= $sorting['id_usuario'];
		$nombre_usuario = $sorting['nombre_usuario'];
		$turno 			= $sorting['turno'];
		$rutas 			= $sorting['rutas'];
		$TREV 			= $sorting['TREV'];
		$TENV 			= $sorting['TENV'];
		$cajas 			= $sorting['cajas'];	
		if (in_array($id_usuario, $array_id_usuario)) {
			echo 'Ya existen registros de '.$id_usuario.'';
		header('Refresh: 3; URL=envases');  
		die();	
		} else {
		$crudAlm-> InsertarSorting($idcentro,$fecha,$id_usuario,$nombre_usuario,$turno,$rutas,$TREV,$TENV,$cajas,$fecha_hora);	
		header('Location: envases');
		die();
		}
		} else {
		}
	
	 $nombre_usuario = $usuarionombre.' '.$usuarioapellidos;
	 echo '<form method="post">
			  <div class="form-group">
				<label for="fecha_sorting">Fecha :</label>
				<input type="date" '.$disabled.' class="form-control" id="fecha_sorting" value="'.$fecha_sorting.'" name="sorting[fecha]">
				<input type="hidden" class="form-control" id="fecha_clasificacion" value="'.$aid.'" name="sorting[nombre_usuario]">
			  </div>
			  <div class="form-group">
				<label for="turno_sorting">Clasificador :</label>
					<select class="form-control" id="turno_sorting" name="sorting[id_usuario]" '.$disabled.' required>';
						foreach($usuario_cla  as $valor=>$clave)
						{
								if ($valor == $aid){
								echo '<option  value="'.$valor.'" selected >'.$clave.'</option>';		
								}else {
								//echo '<option  value="'.$valor.'" >'.$clave.'</option>';	
								}
						}			
			echo   '</select>
			  </div>				
			  </div>
			  <div class="form-group">
				<label for="turno_sorting">Turno :</label>
					<select class="form-control" id="turno_sorting" name="sorting[turno]" '.$disabled.' required>';
						foreach($turno_sorting  as $valor=>$clave)
						{
								if ($valor == ''){
								echo '<option  value="'.$valor.'" selected >'.$clave.'</option>';		
								}else {
								echo '<option  value="'.$valor.'" >'.$clave.'</option>';	
								}
						}			
			echo   '</select>
			  </div>';
			  
			echo  '<input type="hidden" class="form-control" id="fecha_clasificacion" value="n/a" name="sorting[rutas]" >			  
			  <div class="form-group">
				<label for="TREV">Tipo Revisión :</label>
					<select class="form-control" id="TREV" name="sorting[TREV]" '.$disabled.' required>';
						foreach($TREV  as $valor=>$clave)
						{
								if ($valor == ''){
								echo '<option  value="'.$valor.'" selected >'.$clave.'</option>';		
								}else {
								echo '<option  value="'.$valor.'" >'.$clave.'</option>';	
								}
						}			
			echo   '</select>	
			  </div>	
			  <div class="form-group">
				<label for="TENV">Tipo Envase :</label>
					<select class="form-control" id="TENV" name="sorting[TENV]" '.$disabled.' required >';
						foreach($TENV  as $valor=>$clave)
						{
								if ($valor == ''){
								echo '<option  value="'.$valor.'" selected >'.$clave.'</option>';		
								}else {
								echo '<option  value="'.$valor.'" >'.$clave.'</option>';	
								}
						}			
			echo   '</select>	
			  </div>
			  <div class="form-group">
				<label for="cajas_sorting">Cajas Clasificadas :</label>
				<input type="text" '.$disabled.' class="form-control" id="cajas_sorting" value="" name="sorting[cajas]">
			  </div>	
            <button type="submit" class="btn btn-danger"  '.$disabled.' >Agregar</button>';
		?>				
		  </form>
	    <?php 
	}

	switch ($j5xqi9554vUXBmoX9IHXg):
    case "update":
	/*
		$to_time = strtotime($row['Inicio']);
		$from_time = strtotime($fecha_hora);
		$minutos =(round(abs($to_time - $from_time) / 60,2))/60;
	*/
	$crudAlm-> ModificarSorting($_GET['Sorting'],$idcentro,$aid,$fecha_hora,5,'13:13:13');
	header('Location: envases');
	die();
        break;
    case "reporte":
	reporte();
	break;
    default:
		check();
	endswitch;
	} else {
     echo $html_acceso;		
	}
	}
	require('../footer.php');
	ob_end_flush();	
	?>