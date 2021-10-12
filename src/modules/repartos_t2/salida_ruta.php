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
	function confirma_check($id){
		global $idcentro,$fecha_hora,$fechars;	
		$db=DB::conectar();
		$insert=$db->prepare("
		UPDATE `t77_checkin_cd` SET `st`=1 WHERE `id`=$id
		");
		//$insert->bindValue('id',$id);
		$insert->execute();
		Db::desconectar();
	}
	function insertar_txt($transporte,$txt_reparto){
		global $idcentro,$fecha_hora,$fecha_hora,$aid;	
		$db=DB::conectar();
		$insert=$db->prepare("INSERT INTO `t77_checkin_pt_txt`(`Transporte_tx`, `txt_reparto`, `user_reparto`, `centro`,`fecha_hora_reparto`) 
								VALUES ('$transporte','$txt_reparto','$aid','$idcentro','$fecha_hora')");
		$insert->execute();
		Db::desconectar();
	}	

function listar_productos(){
	global $idcentro,$fecha_hora,$fechars,$filename,$aid,$usuarionombre;

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
	</script>

	<form action="<?php echo $filename; ?>" method="POST">
	<div class="row">
    <div class="col-sm-12 p-0 bg-light">	
	<div class="d-flex">
	<div class="p-2 bg-light">
	<div class="text-danger font-weight-bold h4"><?php echo $idcentro.' - '.$aid.' - '.$usuarionombre; ?></div>
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
		  $transporte = null;
		  $txt_reparto = null;
		  $db=Db::conectar();
		  $sql ="SELECT * FROM `t77_checkin_cd` WHERE Centro='$idcentro' AND Ruta='$aid' AND Conductor='$usuarionombre' AND Viaje = '1' ";
          $select=$db->prepare($sql);
		  $select->execute();
          while ($registro=$select->fetch(PDO::FETCH_ASSOC)){
		  if($registro['st']==1) { $checked = 'checked disabled'; } else {  $checked = ''; }
		  $transporte = $registro['Transporte'];
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
	    if(is_null($txt_reparto)){ $txdisabled= ''; } else { $txdisabled='disabled'; } 
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
			<label for="comment"><p class="text-danger">Commentario Reparto: <?php  echo $transporte; ?></p></label>
			<textarea class="form-control" rows="5" id="comment" name="comentario[<?php  echo $transporte; ?>]"  <?php echo $txdisabled; ?> required ><?php  echo $txt_reparto; ?></textarea>
		</div>
	</div>
	<div class="p-2 bg-light">
	<div class="text-md-left font-weight-bold">
	<button type="submit" class="btn btn-danger"  > <?php //echo  $Ruta.' | Viaje : '.$Viaje; ?> | Grabar </button>
	</div>
	</div>
	</form>	
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
	</div>
    </div>	
	<?php
	}
	switch ($FMfcgxwLtkdRLdXFNVNgdJkHCHKrmRpl):
		case "RegistraHora":
	break;
	    case "DetalleRuta":
	break;
	    case "AlertarWS":
	break;
	    case "EntregarPedido":

	break;		
		case "DepositosBancarios":
	break;	
	default:
	listar_productos();
	endswitch;
	
	} else {
	echo $html_acceso;		
	}
	}
	require('../footer.php');
	ob_end_flush();	
	?>