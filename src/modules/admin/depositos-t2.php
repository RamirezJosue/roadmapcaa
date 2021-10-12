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
	$datatablesjs = 1;
	require('../head.php');
	if(isset($_GET['fechaselec'])): 
	$fecha_form = $_GET['fechaselec'];
	$fechars = $_GET['fechaselec'];	
	else:
	$fechars = $fechars; 
	$fecha_form = $fecha;
	endif;
    if (isset($_GET['hc'])){ $hc = $_GET['hc']; } else { $hc = ""; }

function tabla_usuarios()
{ global $db,$idcentro,$fechars;
	 ?>	
    <div class="row">
    <div class="col-sm-12 p-0 bg-light">	
	<div class="d-flex">
	<div class="p-2 bg-light">
	<div class="text-muted text-md-left font-weight-bolder">
		<form action="depositos-t2" method="GET" >
		<label for="fecha">Fecha</label>
		<input type="date" name="fechaselec" id="fecha" value="<?php echo  $fechars; ?>">
		<button type="submit" class="btn btn-danger btn-sm">Ver</button>
		</form>	
	</div>
	</div>
	</div>
	</div>
	</div>
	<div class="row">
    <div class="col-sm-12 bg-highlight">
	<div class="d-flex justify-content-left">
	<div class="p-2 bd-highlight"><p class="h4 text-danger">Depositos confirmados T2: <?php echo $idcentro; ?></p> </div>
	</div>
	</div>
	</div> 
    <div class="table-responsive">	 
    <table id="example"  data-order='[[ 0, "asc" ]]' data-page-length='20'
          class="table table-sm table-striped table-hover table-bordered">
	<thead>
		  <tr>
          <th scope="col">#</th>
          <th scope="col">transporte</th>
		  <th scope="col">fecha_registro</th>
          <th scope="col">ruta</th>
		  <th scope="col">codigocliente</th>
		  <th scope="col">banco</th>
		  <th scope="col">documento</th>
		  <th scope="col">importe</th>
        </tr>
	</thead>
	<tbody>
	<?php

		$db=Db::conectar();
		$sql ="SELECT * FROM `t77_rs_depositos_user`  WHERE centro = :centro AND fecha=:fecha";
        $select=$db->prepare($sql);
		$select->bindValue('centro',$idcentro);
		$select->bindValue('fecha',$fechars);
		$select->execute();
		$n=1;
		while ($row=$select->fetch(PDO::FETCH_ASSOC)){		
		?>
		<tr>
		<td><?php echo $n; ?></td>
		<td><?php echo $row['transporte']; ?></td>	
		<td><?php echo $row['fecha_registro']; ?></td>	
		<td><?php echo $row['ruta']; ?></td>	
		<td><?php echo $row['codigocliente'].' '.$row['nombre']; ?></td>	
		<td><?php echo $row['banco']; ?></td>
		<td><?php echo substr($row['indice'],8,25); ?></td>	
		<td><?php echo $row['importe']; ?></td>		
		</tr>	
		<?php 
		$n++;	
		}
		Db::desconectar();
	?>
	</tbody>
	</table>
    </div>
	<?php	 	
}
switch ($hc):
    case "modificar":
        break;
    case "CambiarPassword":
        break;
	case "CambiarAccesos":
		break;	
	case "EstadoUser":
        break;
	case "Covid":
        break;	 
	case "ModificaParametrosUser":
        break;		
	case "InsertarParametrosUser":
        break;			
	case "InicioParametrosUser":
        break;	
	case "listarcovid":  
        break;	
	case "listaconductorescds":
        break;		 	
    default: 
        tabla_usuarios();
	endswitch;
	} else {
	echo $html_acceso;		
	}
	}
	require('../footer.php');
	ob_end_flush();	
	?>