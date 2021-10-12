<?php
/**
 * Sample PHP code to use reCAPTCHA V2.
 *
 * @copyright Copyright (c) 2014, Google Inc.
 * @link      http://www.google.com/recaptcha
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
require_once "recaptchalib.php";

// Register API keys at https://www.google.com/recaptcha/admin
$siteKey = "6LeJ3jQaAAAAAD_-N20poa2e508MIhYYqVt2CpbX";
$secret = "6LeJ3jQaAAAAAFEOyUuypuIbpt5qzSDsSzlJWe6x";
// reCAPTCHA supported 40+ languages listed here: https://developers.google.com/recaptcha/docs/language
$lang = "es";

// The response from reCAPTCHA
$resp = null;
// The error code from reCAPTCHA, if any
$error = null;

$reCaptcha = new ReCaptcha($secret);

// Was there a reCAPTCHA response?
if (isset($_POST["g-recaptcha-response"])) {
    $resp = $reCaptcha->verifyResponse(
        $_SERVER["REMOTE_ADDR"],
        $_POST["g-recaptcha-response"]
    );
}
date_default_timezone_set("America/Lima");
$fecha = date("Y-m-d",$time = time());
$fecha_hora = date("Y-m-d H:i:s",$time = time());

?>
<!doctype html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Jekyll v4.1.1">
	<meta name="theme-color" content="#971B1E">
    <title>RoadMap Delivery</title>
	<link rel="shortcut icon" href="img/roadmap.ico" type="image/x-icon">
    <link rel="canonical" href="https://getbootstrap.com/docs/4.5/examples/navbar-fixed/">
    <!-- Bootstrap core CSS -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link href="css/bootstrap-slider.css" rel="stylesheet">   
    <!-- Custom styles for this template -->
    <link href="css/navbar-top-fixed.css" rel="stylesheet">
<style>
input[type = "radio"]{ display:none;/*position: absolute;top: -1000em;*/}
label{ color:grey;}
.clasificacion{
    direction: rtl;
    unicode-bidi: bidi-override;
}
label:hover,
label:hover ~ label{color:orange;}
input[type = "radio"]:checked ~ label{color:orange;}
</style>
	</head>
	<body>
<main role="main" class="container-sm">
<?php
if ($resp != null && $resp->success) {
	ob_start();	
	if(isset($_POST['consultaid'],$_POST['PalabraClave']) && $_POST['consultaid']==1){
	require_once('bd/banco.php');
	 $fechars = date("Y-m-d",$time = time());
	 $db=Db::conectar();
     $result=$db->prepare("SELECT * FROM (SELECT * FROM `t77_rs` WHERE Fecha = '".$fechars."' AND centro <> 'BK99') AS z WHERE z.Codigo = '" . $_POST['PalabraClave'] . "'");
	 $result->execute();
	 if($result->rowCount() > 0) {	 
        $row_count=0;
	         ?>	
	<div class="card"> 
    <div class="card-header font-weight-bold">Consulta de pedido</div>
	<div class="card-body">
					     <?php
		$db=Db::conectar();
		while ($row=$result->fetch()){			
            $row_count++;   
			$select=$db->prepare("SELECT * FROM t77_em WHERE ruta=:ruta");
			$select->bindValue('ruta',$row['Ruta']);
			$select->execute();
			while ($rowmc=$select->fetch()){	
			$idRuta = $row['Vehiculo'].$row['Ruta'].$row['Viaje'].$row['Fecha'];
		?>
	<h6 class="card-subtitle mb-2 text-muted"><?php echo $row['Cliente'] ?></h6>
    <div class="dropdown-divider"></div>
    	<div class="card-body">
    <h6 class="card-title">Entrega cajas</h6>	
	<h6 class="card-subtitle mb-2 text-muted"><?php echo number_format($row['Entrega'], 0, '.', ','); ?></h6>	
    <h6 class="card-title">Llega</h6>	
	<h6 class="card-subtitle mb-2 text-muted"><?php if((round (substr($row['Llega'],0,2))) >= 13 ){ echo "PM-".$row['Fecha']; } else { echo "AM - ".$row['Fecha']; } ?></h6>
    <h6 class="card-title">Agente reparto</h6>	
	<h6 class="card-subtitle mb-2 text-muted"><?php echo $row['Ruta'].' - '?>  <a href="tel:+<?php echo $rowmc['telefonoreparto']; ?>"> 📞 <?php echo $rowmc['telefonoreparto']; ?></a></h6>
	<div class="dropdown-divider"></div>
	
    <h6 class="card-title">Seguimiento</h6>
				<?php 
	   		$selecttiempo=$db->prepare("
SELECT * FROM (
SELECT 1 as nr, 1 as estado, 'Salio a ruta' as TXestado, `salida_cd` as hora, '' as mr FROM `t77_rs_ruta_sif` WHERE `indx`=:indx
UNION ALL 
SELECT 3 as nr, `entregado` as estado, 'Entregado' as TXestado, `registrofin` as hora, mr FROM `t77_rs` WHERE `Codigo`=:Codigo AND Fecha=:Fecha
UNION ALL
SELECT 2 as nr, `alerta` as estado, 'Primera visita' as TXestado, `fechahoraalerta` as hora, mr FROM `t77_rs` WHERE `Codigo`=:Codigo AND Fecha=:Fecha
UNION ALL
SELECT 4 as nr, `rechazo` as estado, 'No entregado' as TXestado, `registrofin` as hora, mr FROM `t77_rs` WHERE `Codigo`=:Codigo AND Fecha=:Fecha
) AS a WHERE a.estado=1	ORDER BY a.nr ASC		
			");
			$selecttiempo->bindValue('indx',$idRuta);
			$selecttiempo->bindValue('Codigo',$row['Codigo']);
			$selecttiempo->bindValue('Fecha',$fecha);
			$selecttiempo->execute();
			while ($rowtiempo=$selecttiempo->fetch()) {
			   if($rowtiempo['TXestado']=='Salio a ruta') {
			   $textocolor='text-primary'; 
			   $mrestado = '';						
			   } else if ($rowtiempo['TXestado']=='No entregado') { 
			   $textocolor='text-danger'; 
			   $mrestado = $rowtiempo['mr'];
			   } else if ($rowtiempo['TXestado']=='Entregado') { 
			   $textocolor='text-success';
			   $mrestado = '';			   
			   } else if($rowtiempo['TXestado']=='Primera visita') { 
			   $textocolor='text-warning'; 
			   $mrestado = $rowtiempo['mr'];
			   } else {
			   $textocolor='';  
			   $mrestado = $rowtiempo['mr'];
			   } 	
				?>		
		<h6 class="card-subtitle mb-2 <?php echo $textocolor; ?>"><?php echo $rowtiempo['TXestado']; ?></h6>
	    <p class="card-text"><small class="text-muted"><?php echo $rowtiempo['hora']; ?> </small><small class="text-muted"> <?php echo $mrestado; ?></small></p>
				<?php
				}
				Db::desconectar();
            $codigoCliente = $row['Codigo'];    
            $nombreCliente = $row['Cliente'];
            $vehiculo = $row['Vehiculo'];
            $ruta = $row['Ruta'];
            $viaje = $row['Viaje'];
            $fechaPlan = $row['Fecha'];
			}
			Db::desconectar();
		}
		Db::desconectar();
		?>
	</div>
	<button type="button" class="btn btn-success btn-lg btn-block" data-toggle="modal" data-target="#calificaCli">Simulador Califica mi entrega</button>	
	<button type="button" class="btn btn-danger btn-lg btn-block" onclick="location.href='consultacliente';" >Volver a consultar</button>
	</div>
	</div>
	<div class="modal fade" id="calificaCli" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-sm" >
			<div class="modal-content">
			<div class="modal-header bg-danger text-white">
				<h5 class="modal-title" id="exampleModalLabel">Simulador Califica mi entrega</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
		<form method="post">	
			<input type="hidden" name="rmdRm[codigoCliente]" value="<?php echo $codigoCliente; ?>" >
            <input type="hidden" name="rmdRm[nombreCliente]" value="<?php echo $nombreCliente; ?>" >
            <input type="hidden" name="rmdRm[vehiculo]" value="<?php echo $vehiculo; ?>" >
            <input type="hidden" name="rmdRm[ruta]" value="<?php echo $ruta; ?>" >
            <input type="hidden" name="rmdRm[viaje]" value="<?php echo $viaje; ?>" >
            <input type="hidden" name="rmdRm[fechaPlan]" value="<?php echo $fechaPlan; ?>" >  

		<div class="form-group">
			<label for="cliente">Califica la atención del reparto :</label>
		<p class="clasificacion">
		<input id="radio1" type="radio" name="rmdRm[estrellas]" value="5" required><label for="radio1">&#9733;</label>
		<input id="radio2" type="radio" name="rmdRm[estrellas]" value="4" required><label for="radio2">&#9733;</label>
		<input id="radio3" type="radio" name="rmdRm[estrellas]" value="3" required><label for="radio3">&#9733;</label>
		<input id="radio4" type="radio" name="rmdRm[estrellas]" value="2" required><label for="radio4">&#9733;</label>
		<input id="radio5" type="radio" name="rmdRm[estrellas]" value="1" required><label for="radio5">&#9733;</label>
		</p>
		</div> 
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
				<button type="submit" class="btn btn-danger">Guardar</button>
			</div>
		</form>  
			</div>
		</div>
	</div>	
		<?php 
	 } else {
        echo "Resultados encontrados: Ninguno";	
	 header('Refresh: 3; URL=http://www.bk77.co/consultacliente');		
		}
	 header('Refresh: 180; URL=http://www.bk77.co/consultacliente');	
 }else if(isset($_POST['consultaid'],$_POST['PalabraClave']) && $_POST['consultaid']==2){
	 
	     $tiporeclamo = array(1=>'Actitus descortes - Reparto',
				2=>'Recojo indebido de envases del POS',
				3=>'Pedido no entregado',
				4=>'Producto incompleto segun comprobante',
				5=>'NO entregado de comprobante',
				6=>'Entrega de vuelto inadecuado',
				7=>'NO sabe operar BEES',
				8=>'Dejaron un producto por otro',
				9=>'Da?o ocasionado en vivienda y/o  local',
				10=>'No reconoce deuda',
				11=>'Cajas en mal estado',
				12=>'Bonificaciones no planificadas',
				13=>'NO recibe visita de Ventas',
				14=>'No le llega descuento ofrecidos',
				15=>'No recogen PFN',
				16=>'Corregir geoposicion de cliente');
	require_once('bd/banco.php');
						    
		    $db=Db::conectar();		 
			$select=$db->prepare("SELECT * FROM `t77_reclamos` WHERE `cliente`=:cliente AND estado <= 1");
			$select->bindValue('cliente',$_POST['PalabraClave']);
			$select->execute();
			$cuenta_col = $select->rowCount();
			$rowmc=$select->fetch();
		    if($cuenta_col==1){
	         ?>	
	<div class="card"> 
    <div class="card-header font-weight-bold">Consulta de reclamos</div>
	<div class="card-body">
	<h6 class="card-subtitle mb-2 text-muted">Cliente : <?php echo $_POST['PalabraClave']; ?></h6>
    <div class="dropdown-divider"></div>
    	<div class="card-body">
    <h6 class="card-title">Tipo de reclamo</h6>	
	<h6 class="card-subtitle mb-2 text-muted"><?php echo $tiporeclamo[$rowmc['tipo_reclamo']]; ?></h6>	
    <h6 class="card-title">Mensaje</h6>	
	<h6 class="card-subtitle mb-2 text-muted"><?php echo $rowmc['descripcion_cliente']; ?></h6>
    <h6 class="card-title">Fecha</h6>	
	<h6 class="card-subtitle mb-2 text-muted"><?php echo $rowmc['fecha_registro']; ?></h6>
	<div class="dropdown-divider"></div>	
    <h6 class="card-title">Seguimiento</h6>
				<?php 
	   		$selecttiempo=$db->prepare("SELECT * FROM `t77_reclamos_det` WHERE `id_reclamo`=:id_reclamo");
			$selecttiempo->bindValue('id_reclamo',$rowmc['id']);
			$selecttiempo->execute();
			while ($rowtiempo=$selecttiempo->fetch()) {		
				?>		
		<h6 class="card-subtitle mb-2"><?php echo $rowtiempo['descripcion']; ?></h6>
	    <p class="card-text"><small class="text-muted"><?php echo $rowtiempo['fecha_registro']; ?></small></p>
				<?php
				}
		?>
		 </div>
	<button type="button" class="btn btn-danger btn-sm"  data-toggle="modal" data-target="#solucionado" >Dar por solucionado</button>	
	</div>
	</div>
	<div class="modal fade" id="solucionado" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="exampleModalLabel">Dar por solucionado</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
    <form method="post" action="consultacliente">
    <div class="form-group">	
            <label for="message-text" class="col-form-label">Comentario:</label>
            <textarea class="form-control" id="message-text" required name="rclmmensajefinal"></textarea>
    </div> 	
	<div class="form-group">
            <label for="message-text" class="col-form-label">En la escala de 1 al 10 califique la solucion:</label>
	      	<select class="form-control" name="rclcalificafinal" required>
			<option value="" disabled selected><--></option>
			 <option value="1" >1</option>
			 <option value="2" >2</option>
			 <option value="3" >3</option>
			 <option value="4" >4</option>
			 <option value="5" >5</option>
			 <option value="6" >6</option>
			 <option value="7" >7</option>
			 <option value="8" >8</option>
			 <option value="9" >9</option>
			 <option value="10" >10</option>
			</select>
    </div> 
	<input type="hidden" id="id" name="rclid" value="<?php echo $rowmc['id']; ?>">
    </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary">Grabar</button>
      </div>
	</form>  
    </div>
	</div>
	</div>
		<?php 
	 } else {  
		echo "No tienes reclamos pendientes de solucion";
	header('Refresh: 3; URL=http://www.bk77.co/consultacliente');			 
	 }
	Db::desconectar();	 
	 } else {
        echo "Resultados encontrados: Ninguno";	
	 header('Refresh: 3; URL=http://www.bk77.co/consultacliente');		
		   } 	
 ob_end_flush();		   	
 } else {
	 require_once('bd/banco.php');
	 function registra_reclamo($tipo_reclamo,$cliente,$telefono,$descripcion_cliente,$fecha_registro)
	{ global $db;
			$db=DB::conectar();
			$insert=$db->prepare('
INSERT INTO `t77_reclamos`(`id`, `tipo_reclamo`, `cliente`, `telefono`, `descripcion_cliente`, `fecha_registro`) VALUES 
                          (NULL,:tipo_reclamo,:cliente,:telefono,:descripcion_cliente,:fecha_registro)
			');
			$insert->bindValue('tipo_reclamo',$tipo_reclamo);
			$insert->bindValue('cliente',$cliente);
			$insert->bindValue('telefono',$telefono);
			$insert->bindValue('descripcion_cliente',$descripcion_cliente);
			$insert->bindValue('fecha_registro',$fecha_registro);
			$insert->execute();
			Db::desconectar();
	}
	 function modifica_reclamo_cliente($id,$califica_cliente,$fecha_solucion_fin,$usuario_respuesta)
	{ global $db;
			$db=DB::conectar();
			$insert=$db->prepare('
UPDATE `t77_reclamos` SET 
            `estado`=:estado,
  `califica_cliente`=:califica_cliente,
`fecha_solucion_fin`=:fecha_solucion_fin,
 `usuario_respuesta`=:usuario_respuesta 
         WHERE `id`=:id
			');
			$insert->bindValue('estado',2);
			$insert->bindValue('id',$id);
			$insert->bindValue('califica_cliente',$califica_cliente);
			$insert->bindValue('fecha_solucion_fin',$fecha_solucion_fin);
			$insert->bindValue('usuario_respuesta',$usuario_respuesta);
			$insert->execute();
			Db::desconectar();
	}
    if (isset($_POST['rmdRm']) && is_array($_POST['rmdRm'])){
       $rmdRm = $_POST['rmdRm'];      
       $codigo = $rmdRm['codigoCliente'];
       $nombre = $rmdRm['nombreCliente'];
       $ruta = $rmdRm['ruta'];       
       $vehiculo = $rmdRm['vehiculo'];
       $viaje = $rmdRm['viaje'];
       $fechaPlan = $rmdRm['fechaPlan'];
       date_default_timezone_set("America/Lima");
       $fecha_hora = date("Y-m-d H:i:s",$time = time());
       $estrellas = $rmdRm['estrellas'];
       $sqlRmD = "INSERT INTO `t77_califica_entrega`
        (`id`, `centro`, `codigo`, `nombre`, `ruta`, `vehiculo`, `viaje`, `fechaPlan`, `fecha_registro`, `estrellas`, `comentario_reparto`, `fecha_comentario`) 
        VALUES 
        (NULL, '$ruta' , '$codigo' , '$nombre' , '$ruta' , '$vehiculo' , '$viaje' , '$fechaPlan' , '$fecha_hora' , '$estrellas' , NULL , NULL )";
        $db=DB::conectar();
        $insert=$db->prepare($sqlRmD);
        $insert->execute();
        $lastInsertId = $db->lastInsertId();
          if($lastInsertId>0){
            echo '<div class="alert alert-success" role="alert">Gracias por su Calificación !</div>';
          }else{ 
            echo '<div class="alert alert-danger" role="alert">No se pudo registrar !</div>';
          } 
        Db::desconectar();  
    }
	?>	
	<div class="row">
    <div class="col-sm-12 bg-highlight">
	<div class="d-flex justify-content-left">
	<div class="p-2 bd-highlight"><p class="h5 text-danger">Consulta y seguimiento de pedidos BK</p></div>
	</div>
	</div>
	</div>
	<form method="post">
	  <div class="form-row">
	  <div class="form-group col-md-4">
      <div class="g-recaptcha" data-sitekey="<?php echo $siteKey;?>"></div>
      <script type="text/javascript"
          src="https://www.google.com/recaptcha/api.js?hl=<?php echo $lang;?>">
      </script>
	  </div>
	  </div>  
	  <div class="form-row">
		<div class="form-group col-md-4">
        <input id="buscarrs" required name="PalabraClave" type="text"   placeholder="Código cliente" class="form-control form-control-lg">  
		</div>
		  <input name="buscar" type="hidden"  value="buscar">	
	  </div>
	  <div class="form-row">
		<div class="form-group col-md-4">
       	  <select class="form-control form-control-lg" name="consultaid" required>
			<option value="" disabled selected>Tipo consulta</option>
			 <option value="1" >Estado de pedido</option>
			 <option value="2" >Estado de reclamo</option>
			</select>
		</div>
	  </div>  
	    <div class="form-row">
		<div class="form-group col-md-4">
		<button type="submit" class="btn btn-danger btn-lg btn-block" >Consultar</button>	
		</div>
	    </div> 
	    <div class="form-row">
		<div class="form-group col-md-4">
		<button type="button" class="btn btn-dark btn-lg btn-block" data-toggle="modal" data-target="#reclamo">Registrar reclamo</button>	
		</div>
	    </div> 			
	</form>
<div class="modal fade" id="reclamo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="exampleModalLabel">Reclamos cliente BK</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
  <form method="post">	
  <div class="form-group">
    <label for="cliente">Código cliente :</label>
    <input type="text" class="form-control form-control-sm" id="cliente" name="rclmcliente" placeholder="Ej. 12459568" required>
    <small id="emailHelp" class="form-text text-muted">Código cliente que figura en los comprobantes dejados por el camion</small>
  </div>	  
  <div class="form-group">
    <label for="telefono">Teléfono :</label>
    <input type="text" class="form-control form-control-sm" id="telefono" name="rclmtelefono" placeholder="Ej. 951986688" required>
  </div>
  <div class="form-group">
    <label for="tiporeclamo">Tipo de reclamo :</label>
    <select class="form-control form-control-sm" name="rclmtiporeclamo" id="tiporeclamo" required>
        <option value="" selected disabled><--></option>	
		<option value="1">Actitus descortés - Reparto</option>
		<option value="2">Recojo indebido de envases del POS</option>
		<option value="3">Pedido no entregado</option>
		<option value="4">Producto incompleto según comprobante</option>
		<option value="5">NO entregado de comprobante</option>
		<option value="6">Entrega de vuelto inadecuado</option>
		<option value="7">NO sabe operar BEES</option>
		<option value="8">Dejaron un producto por otro</option>
		<option value="9">Daño ocasionado en vivienda y/o  local</option>
		<option value="10">No reconoce deuda</option>
		<option value="11">Cajas en mal estado</option>
		<option value="12">Bonificaciones no planificadas</option>
		<option value="13">NO recibe visita de Ventas</option>
		<option value="14">No le llega descuento ofrecidos</option>
		<option value="15">No recogen PFN</option>
		<option value="16">Corregir geoposición de cliente</option>
    </select>
    </div> 
	<div class="form-group">
            <label for="message-text" class="col-form-label">Descripción :</label>
            <textarea class="form-control" id="message-text" required name="rclmmensaje"></textarea>
    </div>  
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-danger">Guardar</button>
      </div>
	</form>  
    </div>
  </div>
</div>	
	<?php
	if(isset($_POST['rclmcliente'],$_POST['rclmtelefono'],$_POST['rclmtiporeclamo'],$_POST['rclmmensaje'])){
     $rclmcliente = $_POST['rclmcliente'];
	 $rclmtelefono = $_POST['rclmtelefono'];
	 $rclmtiporeclamo = $_POST['rclmtiporeclamo'];
	 $rclmmensaje = $_POST['rclmmensaje'];		
     registra_reclamo($rclmtiporeclamo,$rclmcliente,$rclmtelefono,$rclmmensaje,$fecha_hora);
	?>
	  <div class="row">
	  <div class="col-sm-4">
	<div class="alert alert-success alert-dismissible fade show" role="alert">
	<strong>Gracias por registrar tu reclamo !</strong> en breve nos contactaremos... gracias. 
	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
	</button>
	</div>	  
	  </div>
	  </div>
	<?php
	}
	if(isset($_POST['rclid'],$_POST['rclmmensajefinal'],$_POST['rclcalificafinal'])){
     $rclid = $_POST['rclid'];
	 $rclmmensajefinal = $_POST['rclmmensajefinal'];
	 $rclcalificafinal = $_POST['rclcalificafinal'];
	modifica_reclamo_cliente($rclid,$rclcalificafinal,$fecha_hora,$rclmmensajefinal);
	?>
	  <div class="row">
	  <div class="col-sm-4">
	<div class="alert alert-success alert-dismissible fade show" role="alert">
	<strong>Gracias por confirmar la solucion !</strong>
	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
	</button>
	</div>	  
	  </div>
	  </div>
	<?php
	}
    	
}
?>
  </main>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script>window.jQuery || document.write('<script src="../assets/js/vendor/jquery.slim.min.js"><\/script>')</script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.9.0/feather.min.js"></script>
</body>
</html>