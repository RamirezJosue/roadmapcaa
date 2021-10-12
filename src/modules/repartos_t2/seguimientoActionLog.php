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
	$fechars  = $fecha;
	endif;
    if (isset($_GET['hc'])){ $hc = $_GET['hc']; } else { $hc = ""; }

    function seguimiento_action_log(){
		global $idcentro,$fechars,$aid,$fecha_hora;

    if (isset($_POST['actLog']) && is_array($_POST['actLog'])){
         $actLog = $_POST['actLog'];
         $idAl      = $actLog['id'];
         $cometSup  = $actLog['comentarioSup']; 
        $db=DB::conectar();
        $insert=$db->prepare("UPDATE `t77_action_log` SET `estatus`=1, `fecha_estatus`='$fecha_hora', `usuario_estatus`='$aid', `comentario_sup_t2`='$cometSup' WHERE  `id`='$idAl' AND `centro`='$idcentro'");
        $insert->execute();
        Db::desconectar();
    }

	 ?>	
	<div class="row">
    <div class="col-sm-12 bg-highlight">
	<div class="d-flex justify-content-left">
	<div class="p-2 bd-highlight"><p class="h4 text-danger">Seguimiento Action Log</div>
	</div>
	</div>
	</div> 
    <div class="table-responsive">	 
    <table id="example"  data-order='[[ 0, "asc" ]]' data-page-length='20'
          class="table table-sm table-striped table-hover table-bordered">
	<thead>
		  <tr>
          <th scope="col">#</th>
		  <th scope="col">Fecha</th>  
          <th scope="col">Indicador</th>
          <th scope="col">Datos</th>
		  <th scope="col">Transportista</th>
		  <th scope="col">Problema</th>
		  <th scope="col">1erPorque</th>
          <th scope="col">2doPorque</th>
          <th scope="col">3erPorque</th>
          <th scope="col">4toPorque</th>
          <th scope="col">5toPorque</th>
          <th scope="col">CausaRaiz</th>
          <th scope="col">Accion</th>
          <th scope="col">Responsable</th>
          <th scope="col">Compromiso</th>
          <th scope="col"></th>
        </tr>
	</thead>
	<tbody>
	<?php
		$db=Db::conectar();
		$sql ="SELECT 
        `id`, `nombre`, `indicador_tema`, `fecha_plan`, `ruta`, `vehiculo`, `empresa`, 
        `descripcion_anomalia`, `1erporque`, `2doporque`, `3erporque`, `4toporque`, `5toporque`, `causa_raiz`, 
        `accion`, `resposable`, `fecha_compromiso`, `estatus`, `fecha_estatus`, `usuario_estatus`, `comentario_sup_t2` 
        FROM `t77_action_log` WHERE  `centro`='$idcentro'";
        $select=$db->prepare($sql);
		$select->execute();
		$n=1;
		while ($row=$select->fetch(PDO::FETCH_ASSOC)){
            if($row['estatus'] == 1) { $class='table-success'; } else { $class='table-active'; }	
            if (is_null($row['comentario_sup_t2'])) { $disabled=''; } else { $disabled='disabled'; };
		?>
		<tr class="<?php echo $class; ?>" >
		<td><?php echo $n; ?></td>
		<td><?php echo $row['fecha_plan']; ?></td>
		<td><?php echo $row['indicador_tema']; ?></td>
        <td><?php echo $row['nombre']; ?></td>	
        <td><?php echo $row['ruta'].' '.$row['vehiculo'].' '.$row['empresa']; ?></td>	
        <td><?php echo $row['descripcion_anomalia']; ?></td>	
        <td><?php echo $row['1erporque']; ?></td>
        <td><?php echo $row['2doporque']; ?></td>
        <td><?php echo $row['3erporque']; ?></td>
        <td><?php echo $row['4toporque']; ?></td>
        <td><?php echo $row['5toporque']; ?></td>
        <td><?php echo $row['causa_raiz']; ?></td>	
        <td><?php echo $row['accion']; ?></td>	
        <td><?php echo $row['resposable']; ?></td>	
        <td><?php echo $row['fecha_compromiso']; ?></td>	
		<td><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#actionLog<?php echo $row['id']; ?>">Comentar</button></td>	
		</tr>
        <tr>
        <td colspan="16" >    
        <div class="modal fade" id="actionLog<?php echo $row['id'];?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
             <form action="seguimientoActionLog" method="POST">    
               <div class="form-group">
                 <label for="ComentarioSup">Comentario Supervisor</label>
                 <textarea class="form-control" name="actLog[comentarioSup]" id="ComentarioSup" rows="3" <?php echo $disabled; ?> ><?php echo $row['comentario_sup_t2']; ?></textarea>
                 <input type="hidden"  name="actLog[id]" id="inputName" value="<?php echo $row['id']; ?>">
               </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" <?php echo $disabled; ?> >Save changes</button>
            </div>
            </div>
            </form>
        </div>
        </div>
        </td>
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
    seguimiento_action_log();
	endswitch;
	} else {
	echo $html_acceso;		
	}
	}
	require('../footer.php');
	ob_end_flush();	
	?>
