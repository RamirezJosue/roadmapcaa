<?php 
	session_start();
	if (!isset($_COOKIE['usuarioid'])) {
		header('Location: index.php');
	}
    $accesos="adminrepartos";
	ob_start();
	 $_SESSION['usuarioid']=$_COOKIE['usuarioid'];
	date_default_timezone_set("America/Lima");
    $fecha_hora = date("Y-m-d H:i:s",$time = time());
	$fecha = date("Y-m-d",$time = time());
	$fechars = date("d/m/Y",$time = time());
    //$fechars = '30/10/2020';	
	$aid = $_COOKIE['usuarioid'];
	require_once('conexion.php');
	require_once('crud_usuario.php');
	$crud=new CrudUsuario();
	$idcentro=$crud->sacarcentro($aid);
	
    if (($crud-> contardbuser('id','usuarios','dni = "'.$aid.'" AND centro = "'.$idcentro.'" AND estado = 1'))==0){
	echo "Usuario bloqueado ".$accesos.'-'.$aid; 
	echo '<br><a  href="logout.php">Sign out</a>';
	}else{
	$arraruser = $crud-> arrar_bd_return('accesos','usuarios','dni = "'.$aid.'" AND centro = "'.$idcentro.'"');
	if (in_array($accesos, $arraruser)) {
	if (($crud-> contardbuser('id','usuarios','dni = "'.$aid.'" AND centro = "'.$idcentro.'" AND estado = "1" AND tipo = "1"'))==0) {
    $aid_super = 0; } else { $aid_super = 1; }
	
	require('head.php');
?>
<?php if(($_GET['st'])=='det'){
	
	if(isset($_GET['ruta'])) { $ruta=$_GET['ruta']; }else{ $ruta=$_POST['ruta']; }
	if(isset($_GET['viaje'])) { $viaje=$_GET['viaje']; }else{ $viaje=$_POST['viaje']; }
	
	if(isset($_POST['ClasificaEnv'])){
	foreach ($_POST['ClasificaEnv'] as $clave => $valor) {	
		$crud->GrabarClienteClasificaEnvases($clave,$idcentro);
	}
	}
?> 	
    <form action="adminrepartos.php?st=det&amp;ruta=<?php echo $ruta; ?>&amp;fecha=<?php echo $fechars; ?>&amp;viaje=<?php echo $viaje; ?>" method="post">
	<input type="hidden" name="ruta" value="<?php echo $ruta ?>">
	<input type="hidden" name="viaje" value="<?php echo $viaje ?>">
	<div class="row">
    <div class="col-sm-12 bg-dark">
	<div class="d-flex justify-content-center">
	<div class="p-2 bd-highlight">
	<div class="text-white text-md-center font-weight-bolder"><?php echo $_GET['ruta'].' - '.$_GET['fecha'];?>
	<button type="button" class="btn btn-danger btn-sm" onclick="location.href='adminrepartos.php?st=res&amp;fecha=<?php echo $fechars; ?>';">Cancelar</button>
	<button type="submit" class="btn btn-danger btn-sm" >Grabar</button>
	</div>
	</div>
	</div>
	</div>
	</div>
	<div class="row">
    <div class="col-sm-10 border">
		<section id="no-more-tables">
		<table class="table table-hover table-sm">
		<thead>
		<tr class="bg-primary text-white">
			<th>Cliente</th>
			<th>Direccion</th>
			<th>Cajas/Estado</th>
			<th>Clasf.Env.</th>
			<th></th>
		</tr>
		</thead>
		<tbody>
		<?php 
		  $db=Db::conectar();
		  $sql ="SELECT * FROM t77_rs WHERE Ruta=:Ruta AND Fecha=:Fecha AND Viaje=:Viaje ORDER BY Sec1 ASC";
          $select=$db->prepare($sql);
		  $select->bindValue('Ruta',$ruta);
		  $select->bindValue('Fecha',$fechars);
		  $select->bindValue('Viaje',$viaje);
		  $select->execute();
          while ($registro=$select->fetch()) {

        if ($registro['entregado']==1) { $msjestado = "Ent."; $class='class="table-success"'; } 
		else {
			if($registro['rechazo']==1){ $msjestado = "Rech."; $class='class="table-danger"'; }
			else{ 
			    if($registro['alerta']==1){ $msjestado = "Aler."; $class='class="table-warning"';}
			    else{ $msjestado = "Pend."; $class='class="table-active"'; } 
			    }
			 }
		if($registro['clasifica_envases']==1) { $disabledchecked='disabled  checked'; } else { $disabledchecked=''; }
?> 
    <tr <?php echo $class; ?> >
	<td><?php echo $registro['Sec1'].'-'.$registro['Codigo'].'-'.substr($registro['Cliente'],0,25); ?></td>
    <td><?php echo substr($registro['Direccion'],0,25).'-'.$registro['Ciudad']; ?></td>
    <td><?php echo number_format($registro['Entrega'], 1, '.', '').' - '.$msjestado;?></td>
	<td class="text-center" ><div class="form-group form-check"><input type="checkbox" class="form-check-input" id="exampleCheck1" <?php echo $disabledchecked; ?> name="ClasificaEnv[<?php echo $registro['id']; ?>]" ></div></td>
	<?php if ($registro['entregado']==0){
	?>
	<td>
	<div class="btn-group" role="group" aria-label="Basic example">
	<input type="button" <?php if ($registro['rechazo']==1){ echo "disabled";} ?>  class="btn btn-danger btn-sm" onclick="location.href='adminrepartos.php?st=entregar&amp;id=<?php echo $registro['id']; ?>&amp;ruta=<?php echo $registro['Ruta']; ?>&amp;fecha=<?php echo $registro['Fecha']; ?>';" value="Entregar" />			
	<input type="button" class="btn btn-danger btn-sm" onclick="location.href='adminrepartos.php?st=rechazo&amp;id=<?php echo $registro['id']; ?>';" value="Alertar" />
	</td>	
	</div>	
	<?php
	} else {
	?>
	<td>
	<div class="btn-group" role="group" aria-label="Basic example">
	<input type="button" disabled class="btn btn-danger btn-sm" onclick="location.href='adminrepartos.php?st=entregar&amp;id=<?php echo $registro['id']; ?>&amp;ruta=<?php echo $registro['Ruta']; ?>&amp;fecha=<?php echo $registro['Fecha']; ?>';" value="Entregado" />			
	<input type="button" class="btn btn-danger btn-sm" onclick="location.href='adminrepartos.php?st=rechazo&amp;id=<?php echo $registro['id']; ?>';" value="Ver" />
	</div>
	</td>				
	<?php
	}	
	?> 
	</tr>
	<input type="hidden" name="id" value="<?php echo $registro['id'] ?>">
	<?php
	}
	?>   
	</tbody>
	</table>
	</section>
	</div>
	<div class="col-sm-2 border"></div>
    </div>
    </form>
	<?php
		 }
if(($_GET['st'])=='entregar'){
  $crud->EntregarPedido(1,'',0,$_GET['id'],$aid);
  $ruta = $_GET['ruta'];
  $fecha = $_GET['fecha'];
  header("Location: adminrepartos.php?st=det&ruta=$ruta&fecha=$fecha");
  exit;
}
if(($_GET['st'])=='rechazo'){
	
	if((isset($_POST['grabaralerta'])) and (isset($_POST['grabaralt']))){
		$crud->GrabarAlerta($_POST['mr'],$_POST['cajasrechazo'],$_POST['comentarios'],$aid,$_POST['idrechazo']);
		$msjalerta = "Alerta Registrada, Compartir...";
	}
	if((isset($_POST['grabaralerta'])) and (isset($_POST['confirmarrchz']))){
	    $crud->GrabarRechazo($aid,$_POST['idrechazo'],$_POST['autoriza_rech']);
		$msjalerta = "Rechazo Registrado";
	}
  		if (isset($msjalerta))
		{
			?>
			<div class="p-1 mb-1 bg-success text-white"><?php echo $msjalerta; ?></div>
			<?php 
		}
		
  $id = $_GET['id'];
  $db=Db::conectar();
  $select=$db->prepare("SELECT * FROM (SELECT * FROM t77_rs WHERE id = :id) AS z LEFT JOIN (SELECT * FROM t77_mc) AS m ON z.Codigo = m.codcli");
  $select->bindValue('id',$id);
  $select->execute();
  $registro=$select->fetch(); 
  $lng = str_replace(",",".",$registro['Longitud']);
  $lat = str_replace(",",".",$registro['Latitud']);
  $codigowsp = $registro['Codigo'];
  $clientewsp = str_replace(" ","+",$registro['Cliente']);
  $supervisorwsp = str_replace(" ","+",$registro['supervisor']);
  $agentewsp = str_replace(" ","+",$registro['agente']);
  $cajaswsp = $registro['Entrega'];
  $comentarioswsp = str_replace(" ","+",$registro['comentario']);
  $ruta = $registro['Ruta'];
  $urlwsp = "https://bit.ly/37GA070";
  $msjwsp="*CODIGO:*+".$codigowsp."%0D%0A*NOMBRE:*+".$clientewsp."%0D%0A*SUP:*+".$supervisorwsp."%0D%0A*BDR:*+".$agentewsp."%0D%0A*CAJAS:*+".$cajaswsp."%0D%0A*RUTA:*+".$ruta."%0D%0A+".$urlwsp."+%0D%0A".$comentarioswsp."";
  ?>

  	<div class="row">
    <div class="col-sm-12 bg-danger">
	<div class="d-flex justify-content-center">
	<div class="p-2 bd-highlight"><div class="text-white text-md-center font-weight-bolder">Alertar Rechazo ! </div></div>
	</div>
	</div>
	</div>
	<div class="row">
    <div class="col-sm-6 border">	
		<form class="needs-validation" novalidate action="adminrepartos.php?st=rechazo&id=<?php echo $id; ?>" method="post"> 
		<div class="form-group">
		<table class="table table-sm">
		<tr>
		<td><strong>Cliente:</strong></td><td><?php echo $registro['Codigo'].' - '.$registro['Cliente'];?></td>	
	    </tr> 
		<tr>
		<td><strong>Supervisor:</strong></td><td><?php echo $registro['supervisor'];?></td>	
	    </tr>
		<tr>
		<td><strong>BDR:</strong></td><td><?php echo $registro['zonaac'].' - '.$registro['agente'];?></td>	
	    </tr>
		<tr>	
		<td><strong>Telf. Cliente:</strong></td><td><a href="tel:+<?php echo $registro['Telef1'];?>"><?php echo $registro['Telef1'];?></a> - <a href="tel:+<?php echo $registro['Telef2'];?>"><?php echo $registro['Telef2'];?></a></td>	
	    </tr>
		<tr>
		<td><strong>Reparto:</strong></td><td><?php echo $registro['Ruta'];?><br></td>	
	    </tr>
		<tr>
		<td><strong>Cjs. Entrega:</strong></td><td><?php echo $registro['Entrega'];?></td>	
	    </tr>
		<tr>
		<td><strong>Cjs. Rechazo:</strong></td><td>
		<input type="text" class="form-control" id="validationCustom01" value="<?php echo $registro['Entrega'];?>" required name="cajasrechazo">
		<div class="valid-feedback">
        Ingresar cantidad rechazada 
		</div>
		</td>	
	    </tr>
		<tr>
		<td><strong>Mot. Rechazo:</strong></td>
		<td>
		<select class="custom-select" id="validationCustom02" required name="mr">
			<option value="">Seleccionar</option>
			<?php		
			$db=Db::conectar();
			$select=$db->prepare("SELECT id, descripcion FROM t77_mr");
			$select->execute();
			while ($regis=$select->fetch()) {
			if(strtolower($registro['mr']) == strtolower($regis['descripcion'])) { $selected = "selected"; }
            echo '<option  value="'.$regis['descripcion'].'"' . $selected . '>'.$regis['descripcion'].'</option>';
			}
			?>
		</select>
		<div class="invalid-feedback">
        Seleccione un motivo
        </div>
		</td>	
	    </tr>
		<tr>
		<td><strong>Comentario:</strong></td>
		<td>
		<textarea name="comentarios" class="form-control" id="validationCustom03" required ><?php echo $registro['comentario'];?></textarea>
		<div class="invalid-feedback">
        Ingrese un comentario valido
        </div>
		</td>	
	    </tr>
		<tr>
		<td></td>
		<td>
		<?php if($registro['alerta'] == 0 and $registro['entregado'] == 0){ 
		?>  
        <input type="submit" class="btn btn-danger" name="grabaralt" value="Grabar" >
        <button type="button" class="btn btn-primary" onclick="location.href='adminrepartos.php?st=res&amp;fecha=<?php echo $fechars; ?>';">Cancelar</button>		
		<?php } else { 
		?> 
		<div class='redes-flotantes'>
		<a href="https://api.whatsapp.com/send?text=<?php echo $msjwsp;?>" title="Compartir alerta" style="clear: left; float: left; margin-bottom: 1em; margin-right: 1em;" target="_blank">
		<img border="0" data-original-height="59" data-original-width="59" src="https://1.bp.blogspot.com/-q3Dot9N2qac/XOQgr9etVpI/AAAAAAABT1M/6V4Bqaqr-6UQcl9Fy2_CaVgex0N_OYuQgCLcBGAs/s1600/whatsapp%2Bicono.png" />
		</a>
		</div>
		<input type="submit" disabled class="btn btn-danger" value="Alertar" >
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#rechazar_ped"  <?php if($registro['rechazo'] == 1 or $registro['entregado'] == 1){ echo "disabled"; } ?>  >Rechazar</button>
        <button type="button" class="btn btn-primary" onclick="location.href='adminrepartos.php?st=res&amp;fecha=<?php echo $fechars; ?>';">Cancelar</button>		
		<?php 
		}
		?> 
		</td>	
	    </tr>
		</table>
		<input type="hidden" name="idrechazo" value="<?php echo $id;?>">
		<input type="hidden" name="grabaralerta" value="grabaralerta">
		
	</div>
	</form>
	
	<script>
// Example starter JavaScript for disabling form submissions if there are invalid fields
(function() {
  'use strict';
  window.addEventListener('load', function() {
    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.getElementsByClassName('needs-validation');
    // Loop over them and prevent submission
    var validation = Array.prototype.filter.call(forms, function(form) {
      form.addEventListener('submit', function(event) {
        if (form.checkValidity() === false) {
          event.preventDefault();
          event.stopPropagation();
        }
        form.classList.add('was-validated');
      }, false);
    });
  }, false);
})();
</script>
	
	
    <!--The div element for the map -->
    <script>
	// Initialize and add the map
	function initMap() {
	// The location of Uluru
	var uluru = {lat: <?php echo $lat;?>, lng: <?php echo $lng;?>};
	// The map, centered at Uluru
	var map = new google.maps.Map(
      document.getElementById('map'), {zoom: 16, center: uluru});
	// The marker, positioned at Uluru
	var marker = new google.maps.Marker({position: uluru, map: map});
	}
	</script>	
	</div>
	<div class="col-sm-6" style="height: 500px">
	<div id="map"></div>
	</div>
    </div>
	<div class="row">
    <div class="col-sm-6 border">
	<!-- Modal -->
<div class="modal fade" id="rechazar_ped" tabindex="-1" aria-labelledby="rechazar_pedLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Confirmar rechazo</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>  
      <div class="modal-body">
	  <form class="needs-validation" novalidate action="adminrepartos.php?st=rechazo&id=<?php echo $id; ?>" method="post"> 
	    <input type="hidden" name="idrechazo" value="<?php echo $id;?>">
		<input type="hidden" name="confirmarrchz" value="Rechazar Pedido">
		<input type="hidden" name="grabaralerta" value="grabaralerta">
	  	<select class="custom-select" id="validationCustom02" required name="autoriza_rech">
			<option value=""><--Quien autoriza rechazo--></option>
			<?php		
			$db=Db::conectar();
			$select=$db->prepare("SELECT id, descripcion FROM t77_autoriza_rech");
			$select->execute();
			while ($regist=$select->fetch()) {
				
			if ($regist['descripcion'] == $_GET['descripcion']){
			echo '<option  value="'.ucwords($regist['descripcion']).'" selected >'.ucwords($regist['descripcion']).'</option>';		
			}else {
			echo '<option  value="'.ucwords($regist['descripcion']).'" >'.ucwords($regist['descripcion']).'</option>';	
			}
			
			}
			?>
		</select>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-primary">Grabar</button>
      </div>
	  </form>
    </div>
  </div>
</div>
	</div>
    </div>	
		<?php 			
		} 

if(($_GET['st'])=='res'){
      
	   if(!isset($_GET['fecha'])) {
?>
	
<?php 
	   } else {
 ?>		
	<div class="row">
    <div class="col-sm-12 bg-danger">
	<div class="d-flex justify-content-center">
	<div class="p-2 bd-highlight"><div class="text-white text-md-center font-weight-bolder">Resumen Por Zonas <?php echo $idcentro; ?></div></div>
	</div>
	</div>
	</div>
	<div class="row">
    <div class="col-sm-10 border">	
        <section id="respon-ruta" >
		<table class="table table-striped table-sm">
		<thead>
		<tr class="bg-warning">
		
		    <th>Ruta</th>
			<th>Placa</th>
			<th>Alertas</th>
			<th>Viaje</th>
			<th>Cajas</th>
			<th>Contactos</th>
			<th></th>
		</tr>
		</thead>
		<tbody>
		<?php 
	
		  $db=Db::conectar();
		  $sql ="
		  SELECT id,centro,Ruta,Vehiculo,Fecha,Viaje,SUM(Entrega) AS Cajas ,COUNT(Codigo) AS Contactos, SUM(Alerta) as Alertas 
		  FROM t77_rs WHERE centro=:centro AND Fecha=:Fecha 
		  GROUP BY  centro,Ruta,Vehiculo,Fecha,Viaje ORDER BY Ruta ASC
		  ";
          $select=$db->prepare($sql);
		  $select->bindValue('centro',$idcentro);
		  $select->bindValue('Fecha',$_GET['fecha']);
		  $select->execute();
          while ($registro=$select->fetch()) {	  
?>    
		<tr role="row">
		<td><?php echo $registro['Ruta']; ?></td>
		<td><?php echo $registro['Vehiculo']; ?></td>
		<td><?php echo substr($registro['Alertas'],0,8); ?></td>
		<td><?php echo $registro['Viaje']; ?></td>	
		<td><?php echo $registro['Cajas']; ?></td>	
		<td><?php echo $registro['Contactos']; ?></td>	
		<td><button class="btn btn-danger btn-sm" onclick="location.href='adminrepartos.php?st=det&amp;ruta=<?php echo $registro['Ruta']; ?>&amp;fecha=<?php echo $registro['Fecha']; ?>&amp;viaje=<?php echo $registro['Viaje']; ?>';">Ver</button></td>		
		</tr>
		<input type="hidden" name="id" value="<?php echo $registro['id'] ?>">
<?php
	}
?>
		</tbody>
		</table>
		</section>
		
	</div>
	<div class="col-sm-2 border"></div>
    </div>
		
		
<?php 
	} 
	}
	ob_end_flush();
		} else { echo "no tienes permiso para acceder a esta seccion ".$accesos.'-'.$aid.'<br><a  href="index.php">Inicio</a>'; }
	}
?>
  </main>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script>window.jQuery || document.write('<script src="../assets/js/vendor/jquery.slim.min.js"><\/script>')</script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.9.0/feather.min.js"></script>
	<script async defer
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBhczrZ1XL_KbEoHlAd9z1cm0N3l-JPrCg&callback=initMap">
    </script>	
</body>
</html>
