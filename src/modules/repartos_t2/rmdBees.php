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
	require('../head.php');
	if(isset($_GET['fechaselec'])): 
	$fechars  = $_GET['fechaselec'];	
	else:
		$db=Db::conectar();
		$select=$db->prepare("SELECT  MAX(`Fecha`) AS Fecha_max FROM `t77_rmd` WHERE id_centro='$idcentro'");
		$select->execute();
		$row=$select->fetch(PDO::FETCH_ASSOC);
		$fechars  = $row['Fecha_max'];
		Db::desconectar();
	endif;
    if (isset($_GET['hc'])){ $hc = $_GET['hc']; } else { $hc = ""; }

    function califica_mi_entrega(){
		global $idcentro,$fechars;
	 ?>	
	<div class="row">
    <div class="col-sm-12 bg-highlight">
	<div class="d-flex justify-content-left">
	<div class="p-2 bd-highlight"><p class="h4 text-danger">Rate My Delivery - Bees</div>
	</div>
	</div>
	</div> 
     
	<form method="GET"> <input type="date" value="<?php echo $fechars ?>" name="fechaselec"> <button type="submit" class="btn btn-danger btn-sm">Ver</button> </form>
     
    <div class="table-responsive">	 
    <table id="example"  data-order='[[ 0, "asc" ]]' data-page-length='20'
          class="table table-sm table-striped table-hover table-bordered">
	<thead>
		  <tr>
          <th scope="col">#</th>
		  <th scope="col">Fecha</th>  
          <th scope="col">EmpresaTransporte</th>
		  <th scope="col">Ruta</th>
          <th scope="col">Vehiculo</th>
		  <th scope="col">#Cli</th>
		  <th scope="col">RMD</th>
		  <th scope="col"></th>
        </tr>
	</thead>
	<tbody>
	<?php
		$db=Db::conectar();
		$sql ="SELECT 
				a.`Fecha`,
				a.`Placa`,
				a.`Centro`,
				a.`Ruta`,
				a.`EmpresaTransporte`,
				a.`CodEmpresa`,
				AVG (a.`Rating`) AS rmd, 
				COUNT(`CodCliente`) AS nroCli
				FROM (
				SELECT `CodCliente`, `Fecha`,  `Rating`, `Placa`, `Centro`, `Ruta` , `EmpresaTransporte`, `CodEmpresa`
				FROM `t77_rmd` WHERE id_centro='$idcentro' AND Fecha='$fechars'  
					) AS a GROUP BY 
				a.`Fecha`,
				a.`Placa`,
				a.`Centro`,
				a.`Ruta`,
				a.`EmpresaTransporte`, a.`CodEmpresa` ORDER BY a.EmpresaTransporte ASC";

        $select=$db->prepare($sql);
		$select->execute();
		$n=1;
		while ($row=$select->fetch(PDO::FETCH_ASSOC)){		
		?>
		<tr>
		<td><?php echo $n; ?></td>
		<td><?php echo $row['Fecha']; ?></td>
		<td><?php echo $row['EmpresaTransporte']; ?></td>	
		<td><?php echo $row['Ruta']; ?></td>	
		<td><?php echo $row['Placa']; ?></td>	
		<td><?php echo round ($row['nroCli'],1); ?></td>	
		<td><?php echo round ($row['rmd'],2); ?></td>	
		<td><button type="button" class="btn btn-danger btn-sm" onclick="location.href='indicadoresdiarios?&FMfcgxwLtkdRLdXFNVNgdJkHCHKrmRpl=Actionlog&Indicador=Rate_My_Delivery&Ruta=<?php  echo $row['Ruta']; ?>&Fecha=<?php  echo $row['Fecha']; ?>&Vehiculo=<?php  echo $row['Placa']; ?>&Empresa=<?php  echo $row['CodEmpresa']; ?>';">Action Log</button></td>	
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
    case "cvs-excel":
        break;
    case "asistenciarutinat2":
        break;
	case "ejecuciondereparto":
         break;	 
	case "ModificaParametrosUser":
        break;		
	case "InsertarParametrosUser":
        break;			
	case "InicioParametrosUser":
        break;	
	case "listarcovid":  
        break;		 	
    default: 
	 califica_mi_entrega();
	endswitch;
	} else {
	echo $html_acceso;		
	}
	}
	require('../footer.php');
	ob_end_flush();	
	?>
