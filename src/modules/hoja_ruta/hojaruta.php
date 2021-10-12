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
	function busca_cliente_rs($buscar,$PalabraClave){
	global $fechars,$idcentro,$fecha_form,$db,$disableform,$aid,$crud,$sitio;		
	?>
	<div class="row">
    <div class="col-sm-12 bg-highlight">
	<div class="d-flex justify-content-left">
	<div class="p-2 bd-highlight"><p class="h4 text-danger">Fecha: <?php echo $fechars.' - '.$idcentro;  ?></p></div>
	</div>
	</div>
	</div>
	<form method="GET">
	  <div class="form-row">
		<div class="form-group col-md-4">
        <input  aria-label="First name" id="Fecha" class="form-control" value="<?php echo $fecha_form; ?>" placeholder="Fecha inicio" type="date" name='fechaselec'> 
		</div>
		<div class="form-group col-md-8">
    <input id="buscarrs" required name="PalabraClave" type="text"   placeholder="codigo ó nombre" class="form-control">  
	<input  type="hidden" name="rs" value="buscar_clientes_rs" >  
		</div>
		  <input name="buscar" type="hidden"  value="buscar">	
		  <button type="submit" class="btn btn-danger btn-lg btn-block" >Buscar</button>	
	  </div>
	</form>	
	<?php 
	if($buscar=='buscar' && isset($aid))
	{
	 echo "<br><p class='text-muted'>Palabra clave:<b> ". $PalabraClave."</b> en fecha <b>".$fechars."</b></p>";
     $db=Db::conectar();
     $selectrs=$db->prepare("
    SELECT * FROM 
	(
	SELECT * FROM (
		SELECT * FROM `t77_rs` WHERE Fecha = :Fecha AND centro = :centro) AS z WHERE z.Codigo like '%" . $PalabraClave . "%' OR z.Cliente like '%" . $PalabraClave . "%'
    ) AS rs LEFT JOIN (SELECT codcli,supervisor,zonaac,agente,Telef1,Telef2 FROM t77_mc WHERE centro = :centro) AS mc
    ON rs.Codigo = mc.codcli
						  ");
     $selectrs->bindValue('Fecha',$fechars);
	 $selectrs->bindValue('centro',$idcentro);	 
	 $selectrs->execute();
     if($selectrs->rowCount() > 0) {
	         ?>	
			<div class="table-responsive-sm">
			<table class="table table-sm">
			<thead>
			<tr>
			<th scope="col">Cliente</th>
			<th scope="col">Cj</th>
			<th scope="col">LLega</th>
			<th scope="col">Rep</th>
			<th scope="col"></th>
			<th scope="col"></th>
			</tr>
			</thead>
            <tbody>			
		     <?php
		$row_count=1;	 
        while ($row=$selectrs->fetch()){		
			if ($row['entregado']==1) { $msjestado = "Entregado"; } 
			else {
			if($row['rechazo']==1){ $msjestado = "Rechazado"; }
			else{ 
			    if($row['alerta']==1){ $msjestado = "Alertado";}
			    else{ $msjestado = "Pendiente";} 
			    }
			 }
			$horaalerta = substr($row['fechahoraalerta'],11,5);
			$horafin =substr($row['registrofin'],11,5);
            ?>
			<tr>
			<td><?php echo $row['Codigo'] .'-'. $row['Cliente']; ?></td>
			<td><?php echo number_format($row['Entrega'], 0, '.', ','); ?></td>
			<td><?php echo substr($row['Llega'],0,5); ?></td>
			<td><?php echo $row['Ruta']; ?></td>
			<td>
			<div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
			<button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#myModal<?php echo $row_count; ?>">Ver</button>
			<button type="button" onclick="location.href='<?php echo $sitio; ?>modules/repartos_t2/adminrepartos?FMfcgxwLtkdRLdXFNVNgdJkHCHKrmRpl=AlertarWS&amp;id=<?php echo $row['id']; ?>';" class="btn btn-success btn-sm"><i class="fa fa-whatsapp"></i> WhatsApp</button>
			</div>
			</td>
			<td>
		<div class="modal fade" id="myModal<?php echo $row_count; ?>" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
	<!-- Modal content-->
		<div class="modal-content">
		<div class="modal-header">
		 <h5 class="modal-title" id="exampleModalLabel">Detalle entrega </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
		</div>
		<div class="modal-body">
		<table class="table table-sm table-bordered">
		<tbody>
		<tr>
		<th scope="row">Cliente:</th><td><?php echo $row['Codigo'].' - '.$row['Cliente'];?></td>	
	    </tr> 
		<tr>
		<th scope="row">Dirección:</th><td><?php echo $row['Direccion'].' - '.$row['Ciudad'];?></td>	
	    </tr> 
		<tr>
		<th scope="row">Supervisor:</th><td><?php echo $row['supervisor'];?></td>	
	    </tr>
		<tr>
		<th scope="row">BDR:</th><td><?php echo $row['zonaac'].' - '.$row['agente'];?></td>	
	    </tr>
		<tr>
		<th scope="row">Tipo Pedido:</th><td><?php echo $row['TipoPedido']; ?></td>	
	    </tr>		
		<tr>	
		<th scope="row">Telf. Cliente:</th><td><a href="tel:+<?php echo $row['Telef1'];?>">📞<?php echo $row['Telef1'];?></a> - <a href="tel:+<?php echo $row['Telef2'];?>"><?php echo $row['Telef2'];?></a></td>	
	    </tr>
		<tr> 
		<th scope="row">Reparto:</th><td><?php echo $row['Ruta'];?> <a href="tel:+<?php echo $crud->sacatelfrep($row['Ruta']);?>">📞<?php echo $crud->sacatelfrep($row['Ruta']);?></a></td>	
	    </tr>
		<tr>
		<th scope="row">Cjs.Entrega:</th><td><?php echo $row['Entrega'];?></td>	
	    </tr>
		<tr>
		<th scope="row">HL.Entrega:</th><td><?php echo $row['HL'];?></td>	
	    </tr>		
		<tr>
		<th scope="row">Viaje:</th><td><?php echo $row['Viaje'];?></td>	
	    </tr>		
		<tr>
		<th scope="row">Llega:</th><td><?php echo substr($row['Llega'],0,5).' - '.substr($row['Sale'],0,5).' - '.$row['Fecha'];?></td>	
	    </tr>
		<tr>
		<th scope="row">Estado:</th><td><?php echo $msjestado.' - '.$horaalerta.' - '.$horafin; ?></td>	
	    </tr>
		<tr>
		<th scope="row">Mot.Rechazo:</th><td><?php echo $row['mr']; ?> </td>	
	    </tr>
		<tr>
		<th scope="row">Comentario:</th><td><?php echo $row['comentario'];?></td>	
	    </tr>
		</tbody>
		</table>	 
		</div>
	    <div class="modal-footer">
		<button type="button"  class="btn btn-danger btn-lg btn-block" data-dismiss="modal">Cerrar</button>
		</div>
		</div>
		</div>
		</div>
			</td>
			</tr>
			 <?php
		$row_count++;	 
        }
		Db::desconectar();
        echo " <tbody></table></div>";
    }else {
        echo "<br>Resultados encontrados: Ninguno";	
    }
	}
	}
    function lista_segura($fechars,$fecha_form){
		global $fechars,$idcentro,$fecha_form,$db,$disableform,$aid,$crud;
    ?>
	<div class="row">
    <div class="col-sm-12 bg-highlight">
	<div class="d-flex justify-content-left">
	<div class="p-2 bd-highlight"><p class="h4 text-danger">Lista segura reparto : <?php echo $fecha_form; ?></p> </div>
	<div class="p-2 bd-highlight"><button type="button" class="btn btn-danger" data-toggle="modal" data-target="#myModalListaSegura">Fecha</button></div>
	</div>
	</div>
	</div> 
    <!-- Modal Inicio-->
	<div class="modal fade" id="myModalListaSegura" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
	<!-- Modal content-->
	<div class="modal-content">
	<div class="modal-header">
	<h5 class="modal-title">Seleccionar Fecha</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
    </button>
	</div>
	<div class="modal-body">
	<form method="get" action="hojaruta?st=listasegura">
	<div class="form-row">	
	<input  aria-label="First name" id="fechastema" class="form-control" value="<?php echo $fecha_form; ?>" placeholder="Fecha inicio" type="date" name='fechaselec'> 
	</div>
	<div class="modal-footer">
	<input type="hidden" name="rs" value="listasegura" >
	<button type="submit" class="btn btn-secondary btn-lg btn-block">Guardar</button>
	<button type="button"  class="btn btn-danger btn-lg btn-block" data-dismiss="modal">Cerrar</button>
	</div>
	</form>
	</div>
	</div>
	</div>
	</div>
	<!-- Modal Fin-->   
   			<table class="table table-striped table-sm table-bordered">
			<thead>
			<tr class='w3-dark-grey'>
			<th>Cliente</th>
			<th>Cj</th>
			<th>Ent</th>
			<th>Rep</th>
			<th></th>
			</tr>
			</thead>
			<?php
		  $db=Db::conectar();
		  $sql ="SELECT * FROM
			(
		SELECT ls.codcli as idcli,ls.motivo,rs.entregado,rs.alerta,rs.rechazo,rs.mr,rs.comentario,rs.Viaje,rs.Ruta,rs.Fecha,rs.Llega,rs.Sale,rs.Entrega,rs.fechahoraalerta,rs.registrofin FROM (SELECT * FROM t77_ls) AS ls LEFT JOIN (SELECT * FROM t77_rs WHERE Fecha=:Fecha AND centro = :centro) AS rs ON ls.codcli = rs.Codigo
			) AS sl LEFT JOIN 
		(SELECT * FROM t77_mc) AS mc ON sl.idcli = mc.codcli WHERE sl.Ruta <> '' 
		ORDER BY `sl`.`Entrega`  DESC";
		  $row_count=0;
          $select=$db->prepare($sql);
		  $select->bindValue('Fecha',$fechars);
		  $select->bindValue('centro',$idcentro);
		  $select->execute();
          while ($registro=$select->fetch()) {
			 if ($registro['entregado']==1) { $msjestado = "Entregado"; } 
			else {
			if($registro['rechazo']==1){ $msjestado = "Rechazado"; }
			else{ 
			    if($registro['alerta']==1){ $msjestado = "Alertado";}
			    else{ $msjestado = "Pendiente";} 
			    }
			 }
			$horaalerta = substr($registro['fechahoraalerta'],11,5);
			$horafin =substr($registro['registrofin'],11,5);  
			?>
			<tr>
			<td><?php echo $registro['idcli'].'-'.$registro['nombre']; ?></td>
			<td><?php echo number_format($registro['Entrega'], 0, '.', ','); ?></td>
			<td><?php if($registro['Llega']!=null){ echo substr($registro['Llega'],0,5); }else{ echo 'NoRep'; }?></td>
			<td><?php if($registro['Ruta']!=null){ echo $registro['Ruta']; }else{ echo 'NoRep'; }?></td> 
			<td><button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#myModal<?php echo $row_count; ?>">Ver</button>
		
		<div class="modal fade" id="myModal<?php echo $row_count; ?>" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
		<!-- Modal content-->
		<div class="modal-content">
		<div class="modal-header">
		<h5 class="modal-title" id="exampleModalLabel">Detalle entrega </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        </button>
		</div>
		<div class="modal-body">
		<table class="table table-striped table-sm table-bordered">
		<tr>
		<td><strong>Cliente:</strong></td><td><?php echo $registro['idcli'].'-'.$registro['nombre']; ?></td>	
	    </tr> 
		<tr>
		<td><strong>Dirección:</strong></td><td><?php echo $registro['direccion'].'-'.$registro['distrito']; ?></td>	
	    </tr> 
		<tr>
		<td><strong>ListaSegura:</strong></td><td><?php echo $registro['motivo']; ?></td>	
	    </tr>
		<tr>
		<td><strong>Supervisor:</strong></td><td><?php echo $registro['supervisor']; ?></td>	
	    </tr>
		<tr>
		<td><strong>BDR:</strong></td><td><?php echo $registro['zonaac'].' - '.$registro['agente'];?></td>	
	    </tr>
		<tr>	
		<td><strong>Telf. Cliente:</strong></td><td><a href="tel:+<?php echo $registro['Telef1'];?>">📞<?php echo $registro['Telef1'];?></a> - <a href="tel:+<?php echo $registro['Telef2'];?>"><?php echo $registro['Telef2'];?></a></td>	
	    </tr>
		<tr> 
		<td><strong>Reparto:</strong></td><td><?php echo $registro['Ruta'];?> <a href="tel:+<?php echo $crud->sacatelfrep($registro['Ruta']);?>">📞<?php echo $crud->sacatelfrep($registro['Ruta']);?></a></td>	
	    </tr>
		<tr>
		<td><strong>Cjs.Entrega:</strong></td><td><?php echo $registro['Entrega'];?></td>	
	    </tr>
		<tr>
		<td><strong>Viaje:</strong></td><td><?php echo $registro['Viaje'];?></td>	
	    </tr>
		<tr>
		<td><strong>Llega:</strong></td><td><?php echo $registro['Llega'].' - '.$registro['Sale'];?></td>	
	    </tr>
		<tr>
		<td><strong>Estado:</strong></td><td><?php echo $msjestado.' - '.$horaalerta.' - '.$horafin; ?></td>	
	    </tr>
		<tr>
		<td><strong>Mot.Rechazo:</strong></td><td><?php echo $registro['mr']; ?> </td>	
	    </tr>
		<tr>
		<td><strong>Comentario:</strong></td><td><?php echo $registro['comentario'];?></td>	
	    </tr>
		</table>
		</div>
	    <div class="modal-footer">
		<button type="button"  class="btn btn-danger btn-lg btn-block" data-dismiss="modal">Cerrar</button>
		</div>
		</div>
		</div>
		</div>
			</td>
			</tr>
		<?php  
		$row_count++;   
		  }
		  Db::desconectar();
		   echo "</table>";		
	}
    function listar_alertas($fechars,$fecha_form){
		global $fechars,$idcentro,$fecha_form,$db,$disableform,$aid,$fecha_hora,$crud;
	?>	
	<div class="row">
    <div class="col-sm-12 bg-highlight">
	<div class="d-flex justify-content-left">
	<div class="p-2 bd-highlight"> <p class="h4 text-danger">Alertas : <?php echo $fechars; ?></p></div>
	<div class="p-2 bd-highlight"><button type="button" class="btn btn-danger" data-toggle="modal" data-target="#myModalAlertas">Fecha</button></div>
	</div>
	</div>
	</div> 	
	<!-- Modal Inicio-->
	<div class="modal fade" id="myModalAlertas" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
	<!-- Modal content-->
	<div class="modal-content">
	<div class="modal-header">
	<h5 class="modal-title">Seleccionar Fecha</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
    </button>
	</div>
	<div class="modal-body">
	<form method="GET" action="hojaruta?st=detallealertas">
	<div class="form-row">	
	<input  aria-label="First name" id="fechastema" class="form-control" value="<?php echo $fecha_form; ?>" placeholder="Fecha inicio" type="date" name='fechaselec'> 
	</div>
	<div class="modal-footer">
	<button type="submit" class="btn btn-secondary btn-lg btn-block">Guardar</button>
	<button type="button"  class="btn btn-danger btn-lg btn-block" data-dismiss="modal">Cerrar</button>
	</div>
	</form>
	</div>
	</div>
	</div>
	</div>
	<!-- Modal Fin--> 
       <div class="card-columns">
         <?php	 	 
		  $db=Db::conectar();
          $select=$db->prepare("SELECT * FROM t77_rs WHERE centro=:centro AND alerta=1 AND Fecha=:Fecha ORDER BY fechahoraalerta DESC");
		  $select->bindValue('centro',$idcentro);
		  $select->bindValue('Fecha',$fechars);
		  $select->execute();
          while ($registro=$select->fetch()) {
		$fechahora_ini = $registro['fechahoraalerta'];
		if($registro['registrofin']=='0000-00-00 00:00:00'){
                $fechahora_fintb='0000-00-00 00:00:00';			
				$fechahora_fin=$fecha_hora;		
		}else { $fechahora_fin = $registro['registrofin']; $fechahora_fintb = $registro['registrofin']; }
		if (($registro['entregado']==1) and ($registro['alerta']==1) and ($registro['rechazo']==0)) { $msjestado = "Entregado"; $color='success'; $valuebutton='Ver'; }
		else {
			if($registro['rechazo']==1){ $msjestado = "Rechazado"; $color='danger';  $valuebutton='Ver'; }
			else{ 
			    if($registro['alerta']==1){ $msjestado = "Alertado"; $color='warning';  $valuebutton='Ver'; }
			    else{ $msjestado = "Pendiente"; $color='secondary '; $valuebutton='Alertar'; } 
			    }
			 }
        $arrayventas = $crud->sacarmcarray($registro['ZNPVTA'],$idcentro);			 
		?>
		<div class="card mb-2 border-<?php echo $color; ?>" >
		<div class="card-body">
      <h6 class="card-subtitle"><?php echo $registro['Codigo'].' '.$registro['Cliente']; ?></h6>
	  <div class="dropdown-divider"></div>
      <p class="card-text"><b>Ruta:</b> <?php echo $registro['Ruta']; ?><br>
	  <b>Motivo:</b> <?php echo $registro['mr']; ?><br>
	  <b>Comentario:</b> <?php echo $registro['comentario']; ?><br>
	  <b>Cajas:</b> <?php echo $registro['Entrega']; ?><br>
	  <b>Hora reporte:</b> <?php echo substr($fechahora_ini,11,8); ?><br>
	  <b>Hora final:</b> <?php echo substr($fechahora_fintb,11,8); ?><br>
	  <b>Supervisor:</b> <?php echo $arrayventas['nombreSup']; ?><br>
	  <b>Agente:</b> <?php echo $arrayventas[1].''.$arrayventas[2]; ?><br>
	  <span class="text-<?php echo $color; ?>"><b>Estado:</b> <?php echo $msjestado; ?></span></p>
		</div>
		<div class="card-footer text-muted">
		Hace <?php echo $crud -> tiempoTranscurridoFechas($fechahora_ini,$fechahora_fin); ?>
		</div>
		</div>	
		<?php
		}
		Db::desconectar();
		?>	
	  </div>	
	 <?php		
	}
	switch ($rs):
    case "listasegura":
		if(isset($_GET['fechaselec'])){ 
		$fecha_form = $_GET['fechaselec'];
		$fechars = $_GET['fechaselec'];	
		} else { 
		$fechars = $fechars; 
		$fecha_form = $fecha;
		}
		lista_segura($fechars,$fecha_form);
        break;
    case "buscar_clientes_rs":
	if(isset ($_GET['buscar'],$_GET['PalabraClave'])){
		$buscar = $_GET['buscar']; 
		$PalabraClave = $_GET['PalabraClave']; 
		} else {
		$buscar = ''; 
		$PalabraClave = ''; 	
		}
		busca_cliente_rs($buscar,$PalabraClave);
	break;	 	
    default:
		if(isset($_GET['fechaselec'])){ 
		$fecha_form = $_GET['fechaselec'];
		$fechars = $_GET['fechaselec'];	
		} else { 
		$fechars = $fechars; 
		$fecha_form = $fecha;
		}
		listar_alertas($fechars,$fecha_form);
	endswitch;
	} else {
     echo $html_acceso;		
	}
	}
	require('../footer.php');
	ob_end_flush();	
	?>