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
	$db=Db::conectar();	
	$selectAr=$db->prepare("SELECT dni, concat(nombre,' ',apellidos) as usuario FROM `usuarios` WHERE puesto=14 AND centro='$idcentro'");
	$selectAr->execute();	
	while($row = $selectAr->fetch(PDO::FETCH_ASSOC)) {
		$dni[] = $row['dni'];
		$usuario[] = $row['usuario'];
	}
	Db::desconectar();
	$usuario_pik = array_combine(isset($dni) ? $dni : array() , isset($usuario) ? $usuario : array());	
	/*
	$usuario_pik = array(
		'2168364'=>'FELICIANO CCARI QUISPE',
		'41795149'=>'LUIS ELVER CCARI QUISPE',
		'43980778'=>'HERACLIO MAMANI APAZA',
		'45048110'=>'EDGAR MAMANI MAMANI',
		'73121990'=>'ELISBAN CCARI CARI',
		'75283465'=>'LENIN AQUISE CHAMBI'
						);
	*/			
	function reporte(){
		global $fechars,$idcentro;
		echo '<div class="form-row">
			<div class="mb-3">
				<form action="alistamiento" method="GET" >
				<label for="fecha">Fecha</label>
				<input type="date" name="fechaselec" id="fecha" value="'.$fechars.'">
				<input type="hidden" class="form-control" name="j5xqi9554vUXBmoX9IHXg" value="reporte" >
				<button type="submit" class="btn btn-danger btn-sm">Ver</button>
				</form>				
			</div>
			</div>';		
		echo '<div class="table-responsive-sm">
		 	   <table id="example" class="display compact" style="width:100%" >
        		<thead>
				<tr>
				<th scope="col">#</th>
				<th scope="col">Transporte</th>
				<th scope="col">Placa Ruta</th>
				<th scope="col">Vj</th>
				<th scope="col">Pickinero</th>
				<th scope="col">Cjs</th>
				<th scope="col">Und</th>
				<th scope="col">Inicio</th>
				<th scope="col">Fin</th>
				<th scope="col">Horas</th>
				</tr>
			</thead>
			<tbody>';
		$db=Db::conectar();	
		$select=$db->prepare("SELECT 
		a.Fecha,
		a.Transporte,
		a.Placa,
		a.Ruta,
		a.Viaje,
		a.Inicio,
		a.Fin,           
		IFNULL((SELECT apellidos FROM usuarios WHERE dni=a.Pickinero),a.Pickinero) as Pickinero,
		SUM(`Cajas_Picking`) AS Cajas,
		SUM(`Unidades_Picking`) AS Unidades,
		SUM(`TiempHoras`) AS Horas
		FROM (
		SELECT 
		`Transporte`, `Centro`, `Fecha`, `Placa`, `Ruta`, `Viaje`, `Cajas_Picking`, `Unidades_Picking`, `Pickinero`, `Inicio`, `Fin`,
		TIMESTAMPDIFF(MINUTE, `Inicio`, `Fin` )/60  as TiempHoras   
		FROM `KPI_Picking` WHERE centro='$idcentro' AND Fecha = '$fechars' 
			) AS a 
		GROUP BY
		a.Pickinero,
		a.Fecha,
		a.Transporte,
		a.Placa,
		a.Ruta,
		a.Viaje,
		a.Inicio,
		a.Fin ORDER BY  a.Ruta, a.Viaje ASC");
		$select->execute();	
		$cuenta_col = $select->rowCount();
		$n=1;
		while ($row=$select->fetch(PDO::FETCH_ASSOC)){
		echo   '<tr>
				<td>'.$n.'</td>
				<td>'.$row['Transporte'].'</td>
				<td>'.$row['Placa'].' '.$row['Ruta'].'</td>
				<td>'.$row['Viaje'].'</td>
				<td>'.$row['Pickinero'].'</td>
				<td>'.$row['Cajas'].'</td>
				<td>'.$row['Unidades'].'</td>
				<td>'.$row['Inicio'].'</td>
				<td>'.$row['Fin'].'</td>
				<td>'.$row['Horas'].'</td>
				</tr>';
		$n++;		 
		}		
		Db::desconectar();
		echo '</tbody>
			  </table>
			  </div>';	 
			  ?>
			  <script> 
			  $(document).ready(function() {
				  $('#example').DataTable({
					  "pageLength": 50,
					  "columnDefs": [{
						  "targets": 0
					  }],
					  language: {
						  "sProcessing": "Procesando...",
						  "sLengthMenu": "Mostrar _MENU_ resultados",
						  "sZeroRecords": "No se encontraron resultados",
						  "sEmptyTable": "Ningún dato disponible en esta tabla",
						  "sInfo": "Mostrando resultados _START_-_END_ de  _TOTAL_",
						  "sInfoEmpty": "Mostrando resultados del 0 al 0 de un total de 0 registros",
						  "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
						  "sSearch": "Buscar:",
						  "sLoadingRecords": "Cargando...",
						  "oPaginate": {
							  "sFirst": "Primero",
							  "sLast": "Último",
							  "sNext": "Siguiente",
							  "sPrevious": "Anterior"
						  },
					  }
				  });
			  });
			  </script>
			  <?php 
	}
    function check(){	
		global $fechars,$idcentro,$turno_sorting,$aid,$rutas,$TREV,$TENV,$usuarionombre,$usuarioapellidos,$crudAlm,$fecha_hora,$usuario_pik;
		$fecha_sorting = $fechars;	
		$disabled = '';  
		echo '<div class="form-row">
			<div class="mb-3">
				<form action="alistamiento" method="GET" >
				<label for="fecha">Fecha</label>
				<input type="date" name="fechaselec" id="fecha" value="'.$fechars.'">
				<button type="submit" class="btn btn-danger btn-sm">Ver</button>
				</form>				
			</div>
			</div>';
		echo '<div class="table-responsive-sm">';
		?> <table id="example" class="display compact" style="width:100%" data-order='[[ 0, "asc" ]]' data-page-length='100'> <?php 
		echo '<thead>
				<tr>
				<th scope="col">#</th>
				<th scope="col">Placa</th>
				<th scope="col">Ruta</th>
				<th scope="col">Vj.</th>
				<th scope="col">Cjs.</th>
				<th scope="col">Uni.</th>
				<th scope="col">Preparador</th>
				<th scope="col">Inicio</th>
				<th scope="col">Tiempo</th>
				<th scope="col"></th>
				</tr>
			</thead>
			<tbody>';
		$db=DB::conectar();	
		$select=$db->prepare("SELECT 
		 	`id`, `Transporte`, `Centro`, `Fecha`, `Placa`, `Ruta`, `Viaje`, `Cajas_Picking`, `Unidades_Picking`, `Responsable`, `Pickinero`, `Inicio`, `Fin`,
		 	TIMEDIFF(Fin , Inicio) as Tiempo, TIMESTAMPDIFF(MINUTE, Inicio, Fin )/60  as TiempHoras  
		 		FROM `KPI_Picking` WHERE Fecha='$fechars' AND Centro='$idcentro'");
		$select->execute();	
		$cuenta_col = $select->rowCount();
		$n=1;
		while ($row=$select->fetch(PDO::FETCH_ASSOC)){
		if(IS_NULL($row['Inicio']) AND IS_NULL($row['Fin'])) {  $disabled=''; } else { $disabled='disabled'; }	
		$id_usuario = $row['Pickinero'];
		$array_id_usuario[] = $row['Pickinero'];
		echo	'<tr>
				<td>'.$n.'</td>
				<td>'.$row['Placa'].'</td>
				<td>'.$row['Ruta'].'</td>
				<td>'.$row['Viaje'].'</td>
				<td>'.$row['Cajas_Picking'].'</td>
				<td>'.$row['Unidades_Picking'].'</td>
				<td>';
				if(IS_NULL($row['Inicio'])){
				   echo '<form method="POST" action="alistamiento?j5xqi9554vUXBmoX9IHXg=beginning" >';
				} else {
					echo '<form method="POST" action="alistamiento?j5xqi9554vUXBmoX9IHXg=endup" >';
				}
		echo   '<select class="form-control form-control-sm"  name="picking[pickinero]" '.$disabled.' required >
				<option  value=""> Seleccionar </option>';
					foreach($usuario_pik  as $valor=>$clave)
					{
							if ($valor == $id_usuario){
							echo '<option  value="'.$valor.'" selected >'.$clave.'</option>';		
							}else {
							echo '<option  value="'.$valor.'" >'.$clave.'</option>';	
							}
					}			
		echo   '</select>
				<input type="hidden" class="form-control" value="'.$row['Transporte'].'" name="picking[transporte]">
				</td>
				<td>'.$row['Inicio'].'</td>
				<td>'.$row['Tiempo'].'</td>
				<td>';
				if(IS_NULL($row['Inicio'])){
					echo '<button type="submit" class="btn btn-danger btn-sm"  '.$disabled.' >Inicio</button>';
				} else {
					if (IS_NULL($row['Fin'])) { $disabled=''; } else { $disabled='disabled'; }
					echo '<button type="submit" class="btn btn-dark btn-sm" '.$disabled.' >Fin</button>';
				}
		echo   '</td>
				</form>
				</tr>';
		$n++;		 
		}		
		Db::desconectar();
		echo '</tbody>
			</table></div>';
		/*
	 $nombre_usuario = $usuarionombre.' '.$usuarioapellidos;
	 echo '<form method="post">
			  <div class="form-group">
				<label for="fecha_sorting">Fecha :</label>
				<input type="date" '.$disabled.' class="form-control" id="fecha_sorting" value="'.$fecha_sorting.'" name="sorting[fecha]">
				<input type="hidden" class="form-control" id="fecha_clasificacion" value="'.$aid.'" name="sorting[nombre_usuario]">
			  </div>
			  <div class="form-group">
				<label for="turno_sorting">Clasificador :</label>
					<select class="form-control" id="turno_sorting" name="sorting[id_usuario]" '.$disabled.' required>
					         <option  value=""> Seleccionar </option>';
						foreach($usuario_pik  as $valor=>$clave)
						{
								if ($valor == ''){
								echo '<option  value="'.$valor.'" selected >'.$clave.'</option>';		
								}else {
								echo '<option  value="'.$valor.'" >'.$clave.'</option>';	
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
		*/
	}

	switch ($j5xqi9554vUXBmoX9IHXg):
    case "beginning":
		if(isset($_POST['picking'])){
			$pinking		= $_POST['picking'];
			$pickinero 		= $pinking['pickinero'];
			$transporte 	= $pinking['transporte'];
			 $crudAlm-> ModificarPickingBeginning($transporte,$idcentro,$pickinero,$aid,$fecha_hora);
			 header('Location: alistamiento');
			 die();			  
			} else {
			 echo "algo salio mal, no existe";
			}		
        break;
	case "endup":
		if(isset($_POST['picking'])){
			$pinking = $_POST['picking'];
			$transporte = $pinking['transporte'];

			print_r($_POST['picking']);

			ECHO $idcentro.$fecha_hora;

		    $crudAlm-> ModificarPickingEndup($transporte,$idcentro,$fecha_hora);
			header('Location: alistamiento');
			die();		  
			} else {
			 echo "algo salio mal, no existe";
			}
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