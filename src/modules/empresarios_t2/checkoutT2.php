<?php 
    ob_start();	
    $accesos = basename(dirname(__FILE__));
	require_once('../../includes/ini.php');
	require_once('../../bd/crud_usuario.php');
	$crud=new CrudUsuario();
    if ($usuarioestado==0){
	echo $html_bloqueo;
	} else {
    $arraruser = explode ( ',', $usuarioaccesos);	
	if (in_array($accesos, $arraruser)) {
	if ($usuariotipo==0): $aid_super = 0; else: $aid_super = 1; endif;
	/*inicio vefifia si tiene permisos de adminrepartos */
	if (in_array("adminrepartos", $arraruser)): $adminrepartos = 1; else: $adminrepartos = 0; endif;
    if ($aid_super==1 || $adminrepartos==1): $disableform = ''; else: $disableform = 'disabled'; endif;
	/*fin vefifia si tiene permisos de adminrepartos */
	$bootstrapjs =  1;	
	$datatablesjs = 1;
	$datatablesjsresponsive = 1;
	require('../head.php');
	if(isset($_GET['fechaselec'])): 
	$fecha_form = $_GET['fechaselec'];
	$fechars = $_GET['fechaselec'];	
	else:
	$fechars = $fechars; 
	$fecha_form = $fecha;
	endif;
	// fin head
	$actClasifEnv = 1;
	$hash='Xa6UYNOhEMOu5OUPtqaGAUiflsig';	
	if (isset($_GET['FMfcgxwLtkdRLdXFNVNgdJkHCHKrmRpl'])): 
	$FMfcgxwLtkdRLdXFNVNgdJkHCHKrmRpl = $_GET['FMfcgxwLtkdRLdXFNVNgdJkHCHKrmRpl']; 
	else:
	$FMfcgxwLtkdRLdXFNVNgdJkHCHKrmRpl = ""; 
	endif;
	$enlace = $sitio.'modulos/'.$accesos;
	$filename = pathinfo(basename(__FILE__))['filename'];
	$enlace_actual = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

	function confirma_check($id){
		global $idcentro,$fecha_hora,$fechars;	
		$db=DB::conectar();
		$insert=$db->prepare("
		UPDATE `t77_checkin_pt` SET `st`=1 WHERE `id`=:id
		");
		$insert->bindValue('id',$id);
		$insert->execute();
		Db::desconectar();
	}
	function insertar_txt($transporte,$txt_reparto){
		global $idcentro,$fecha_hora,$fecha_hora,$aid;	
		$db=DB::conectar();
		$insert=$db->prepare("INSERT INTO `t77_checkin_pt_txt`
				   (`Transporte_tx`, `txt_reparto`, `user_reparto`, `centro`,`fecha_hora_reparto`) 
			VALUES ('$transporte','$txt_reparto','$aid','$idcentro','$fecha_hora')");
		$insert->execute();
		Db::desconectar();
	}	

function listar_transportes(){
	global $idcentro,$fecha_hora,$fechars,$filename;
	?> 
	<div class="row">
    <div class="col-sm-12 p-0 bg-light">	
	<div class="d-flex">
	<div class="p-2 bg-light">
	<div class="text-danger font-weight-bold h4">
	 Estatus Check Out T2 <?php  echo $fechars; ?>
	</div>
	</div>
	</div>
	</div>
	</div>
	<div class="row">
    <div class="col-sm-12 p-0">	
	<div class="table-responsive">
	 <table id="asistenciarutinat2detalle"  data-order='[[ 1, "asc" ]]' data-page-length='25' 
     class="display compact cell-border">
		<thead>
		<tr>
		    <th>Transporte</th>
			<th>Fecha</th>
			<th>Zona</th>
			<th>Cjs.</th>
			<th>Und.</th>
			<th>St</th>
			<th></th>
		</tr>
		</thead>
		<tbody>
		<?php 
		  $db=Db::conectar();
		  $sql ="SELECT 
`Transporte`,`FechaPlan`,`Ruta`,`Placa`,`Viaje`,
SUM(`Cajas`) AS Cjs,
SUM(`Unidades`) AS Uni, 
ROUND(SUM(`st`)/COUNT(`st`)*100,2) as estado
FROM `t77_checkin_pt` WHERE FechaPlan=:FechaPlan AND centro=:centro
GROUP BY `Transporte`,`FechaPlan`,`Ruta`,`Placa`,`Viaje` ORDER BY  `Ruta`, `Viaje` ASC";
          $select=$db->prepare($sql);
		  $select->bindValue('FechaPlan',$fechars);
		  $select->bindValue('centro',$idcentro);
		  //$select->bindValue('Ruta',$Ruta);
		  //$select->bindValue('Viaje',$Viaje);
		  //$select->bindValue('Placa',$Placa);
		  $select->execute();
          while ($registro=$select->fetch(PDO::FETCH_ASSOC)){
			  if($registro['estado'] == 100) { $text=''; } else if ($registro['estado'] == 0) {  $text=''; } else { $text='class="text-danger"'; }
		?>
		<tr role="row" <?php  echo $text; ?>>
		<td><?php  echo $registro['Transporte']; ?></td>
		<td><?php  echo $registro['FechaPlan']; ?></td>
		<td><?php  echo $registro['Ruta'].' '.$registro['Placa'].' '.$registro['Viaje']; ?></td>
		<td class="ColTd1A"><?php  echo round($registro['Cjs']); ?></td>	
		<td class="ColTd1B"><?php  echo round($registro['Uni']); ?></td>
		<td><?php  echo $registro['estado']; ?>%</td>
		<td><button type="button" class="btn btn-danger btn-sm" onclick="location.href='checkoutT2?FMfcgxwLtkdRLdXFNVNgdJkHCHKrmRpl=transporte&idt=<?php  echo $registro['Transporte']; ?>&placa=<?php  echo $registro['Placa']; ?>&viaje=<?php  echo $registro['Viaje']; ?>';">Ver</button></td>		
		</tr>
<?php
		}
	Db::desconectar();
?>
	</tbody>
	<tfoot>
	<tr class="bg-danger text-light text-center">
	<td>Total</td>
	 <td></td>
	 <td></td>
	 <td class="TotalTd1 A"></td>
	 <td class="TotalTd1 B"></td>
	 <td></td>
	 <td></td>
	</tr>	
	</tfoot>
		</table>
	</div>
	</form>	
	</div>
    </div>	
	<?php
}
function listar_transportes_resumen(){
	global $idcentro,$fecha_hora,$fechars,$filename;
	?> 
	<div class="row">
    <div class="col-sm-12 p-0 bg-light">	
	<div class="d-flex">
	<div class="p-2 bg-light">
	<div class="text-danger font-weight-bold h4">
	 Estatus Check Out T2
	</div>
	</div>
	</div>
	</div>
	</div>
	<div class="row">
    <div class="col-sm-12 p-0">	
	<div class="table-responsive">
	 <table id="asistenciarutinat2detalle"  data-order='[[ 1, "asc" ]]' data-page-length='25' 
     class="display compact cell-border">
		<thead>
		<tr>
		    <th>Transporte</th>
			<th>Fecha</th>
			<th>Zona</th>
			<th>Cjs.</th>
			<th>Und.</th>
			<th>St</th>
			<th></th>
		</tr>
		</thead>
		<tbody>
		<?php 
		  $db=Db::conectar();
		  $sql ="SELECT * FROM(
			SELECT 
  `Transporte`,`FechaPlan`,`Ruta`,`Placa`,`Viaje`,
  SUM(`Cajas`) AS Cjs,
  SUM(`Unidades`) AS Uni, 
  ROUND(SUM(`st`)/COUNT(`st`)*100,2) as estado
  FROM `t77_checkin_pt` WHERE centro=:centro
  GROUP BY `Transporte`,`FechaPlan`,`Ruta`,`Placa`,`Viaje` ORDER BY  `Ruta`, `Viaje` ASC) AS c WHERE c.estado <> 0";
          $select=$db->prepare($sql);
		  $select->bindValue('centro',$idcentro);
		  //$select->bindValue('Ruta',$Ruta);
		  //$select->bindValue('Viaje',$Viaje);
		  //$select->bindValue('Placa',$Placa);
		  $select->execute();
          while ($registro=$select->fetch(PDO::FETCH_ASSOC)){
			  if($registro['estado'] == 100) { $text=''; } else if ($registro['estado'] == 0) {  $text=''; } else { $text='class="text-danger"'; }
		?>
		<tr role="row" <?php  echo $text; ?>>
		<td><?php  echo $registro['Transporte']; ?></td>
		<td><?php  echo $registro['FechaPlan']; ?></td>
		<td><?php  echo $registro['Ruta'].' '.$registro['Placa'].' '.$registro['Viaje']; ?></td>
		<td class="ColTd1A"><?php  echo round($registro['Cjs']); ?></td>	
		<td class="ColTd1B"><?php  echo round($registro['Uni']); ?></td>
		<td><?php  echo $registro['estado']; ?>%</td>
		<td><button type="button" class="btn btn-danger btn-sm" onclick="location.href='checkoutT2?FMfcgxwLtkdRLdXFNVNgdJkHCHKrmRpl=transporte&idt=<?php  echo $registro['Transporte']; ?>&placa=<?php  echo $registro['Placa']; ?>&viaje=<?php  echo $registro['Viaje']; ?>';">Ver</button></td>		
		</tr>
<?php
		}
	Db::desconectar();
?>
	</tbody>
	<tfoot>
	<tr class="bg-danger text-light text-center">
	<td>Total</td>
	 <td></td>
	 <td></td>
	 <td class="TotalTd1 A"></td>
	 <td class="TotalTd1 B"></td>
	 <td></td>
	 <td></td>
	</tr>	
	</tfoot>
		</table>
	</div>
	</form>	
	</div>
    </div>	
	<?php
}	
function listar_productos_transportes($transporte,$placa,$viaje){
	global $idcentro,$fecha_hora,$fechars,$filename,$enlace_actual,$aid;
	
	if (isset ($_POST['checkpdt']) && is_array($_POST['checkpdt'])){
	 foreach ($_POST['checkpdt'] as $clave => $valor){
		confirma_check($clave);
	 }
	}
	if (isset ($_POST['comentario']) && is_array($_POST['comentario'])) {
	 foreach ($_POST['comentario'] as $clave => $valor){
		  insertar_txt($clave,$valor);
	 }
	}
	?>
	<script type="text/javascript">
	function marcar(source) 
	{
		checkboxes=document.getElementsByTagName('input'); //obtenemos todos los controles del tipo Input
		for(i=0;i<checkboxes.length;i++) //recoremos todos los controles
		{
			if(checkboxes[i].type == "checkbox") //solo si es un checkbox entramos
			{
				checkboxes[i].checked=source.checked; //si es un checkbox le damos el valor del checkbox que lo llamó (Marcar/Desmarcar Todos)
			}
		}
	}
		$(function(){
    // Enables popover
    $("[data-toggle=popover]").popover();
	});
	</script>	
	<form action="<?php echo $enlace_actual; ?>" method="POST">
	<div class="row">
    <div class="col-sm-12 p-0 bg-light">	
	<div class="d-flex">
	<div class="p-2 bg-light">
	<div class="text-danger font-weight-bold h6">Transporte : <?php echo $transporte.' '.$placa.' Viaje : '.$viaje; ?>
	</div>
	</div>
	</div>
	</div>
	</div>
	<div class="row">
    <div class="col-sm-12 p-0">	
	<div class="table-responsive">
	 <table id="asistenciarutinat2detalle"  data-order='[[ 1, "asc" ]]' data-page-length='25' 
     class="display compact cell-border">
		<thead>
		<tr>
		    <th>Vehículo</th>
			<th>Descripción</th>
			<th>Cjs.</th>
			<th>Und.</th>
			<th><input type="checkbox" onclick="marcar(this);" />Todos</th>
		</tr>
		</thead>
		<tbody>
		<?php 
		  $db=Db::conectar();
		  $sql ="
		  SELECT * FROM
		  ( SELECT *  FROM `t77_checkin_pt` WHERE Transporte=:Transporte ) AS a LEFT JOIN
		  ( SELECT * FROM `t77_checkin_pt_txt` ) AS b  
		  ON a.Transporte = b.Transporte_tx ORDER BY a.Denominacion ASC";
          $select=$db->prepare($sql);
		  $select->bindValue('Transporte',$transporte);
		 // $select->bindValue('FechaPlan',$fechars);
		  $select->execute();
          while ($registro=$select->fetch(PDO::FETCH_ASSOC)){
		  if($registro['st']==1) { $checked = 'checked disabled'; } else {  $checked = ''; }
		  $transporte = $registro['Transporte'];
		  $txt_reparto = $registro['txt_reparto'];
		  $txt_almacen = $registro['txt_almacen'];
		  $fecha_hora_reparto = $registro['fecha_hora_reparto'];
		  $fecha_hora_almacen = $registro['fecha_hora_almacen'];
		?>
		<tr role="row">
		<td><?php  echo $registro['Placa']; ?></td>
		<td><?php  echo $registro['Material'].' '.$registro['Denominacion']; ?></td>
		<td class="ColTd1A"><?php  echo round($registro['Cajas']); ?></td>	
		<td class="ColTd1B"><?php  echo round($registro['Unidades']); ?></td>
		<td>
<div class="form-check"><input class="form-check-input" type="checkbox" <?php echo $checked; ?> name="checkpdt[<?php  echo $registro['id']; ?>]" id="defaultCheck1"></div></td>		
		</tr>
		<?php
		}
		Db::desconectar();
		if(!is_null($txt_reparto)){
		$disabled='';		
		} else { 
		echo '<div class="alert alert-danger" role="alert">
				No existe registros de conformidad del reparto
				</div>'; 
		$disabled='disabled';		
		}  
		?>
	</tbody>
	<tfoot>
	<tr class="bg-danger text-light text-center">
	<td>Total</td>
	 <td></td>
	 <td class="TotalTd1 A"></td>
	 <td class="TotalTd1 B"></td>
	 <td></td>
	</tr>	
	</tfoot>
		</table>
		<br>
		<div class="form-group">
			<label for="comment"><p class="text-muted">Commentario Reparto: <?php  echo $transporte.' Fecha: '.$fecha_hora_reparto; ?></p></label>
			<textarea class="form-control" rows="3" id="comment" name="comentario[<?php  echo $transporte; ?>]" ><?php  echo $txt_reparto; ?></textarea>
		</div>
		<br>
		<div class="form-group">
			<label for="comment"><p class="text-muted">Commentario Almacen: <?php  echo $transporte; ?></p></label>
			<textarea class="form-control" rows="3" id="comment" disabled ><?php  echo $txt_almacen; ?></textarea>
		</div>		
	</div>
	<div class="p-2 bg-light">
	<div class="text-md-left font-weight-bold">
	<button type="submit" class="btn btn-danger">Grabar T2</button>
	</div>
	</div>
	</form>
	</div>
    </div>	
	<?php
	}
	?>
		<script type="text/javascript">
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
	switch ($FMfcgxwLtkdRLdXFNVNgdJkHCHKrmRpl):
		case "transporte":
		if(isset($_GET['idt'],$_GET['placa'],$_GET['viaje'])){
			listar_productos_transportes($_GET['idt'],$_GET['placa'],$_GET['viaje']);
		}			
	break;
	    case "resumen":
			listar_transportes_resumen();
	break;
	    case "AlertarWS":
	break;
	    case "EntregarPedido":

	break;		
		case "DepositosBancarios":
	break;	
	default:
	listar_transportes();
	endswitch;
	
	} else {
	echo $html_acceso;		
	}
	}
	require('../footer.php');
	ob_end_flush();	
	?>