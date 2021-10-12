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
	$bootstrapjs =  1;	
	$mapasjs =  0;
	$datatablesjs = 0;
	require('../head.php');
	if(isset($_GET['fechaselec'])): 
	$fecha = $_GET['fechaselec'];
	$fechaselec = $_GET['fechaselec'];	
	else:
	//$today = date($fechars);
	//$yesterday = strtotime('-0 day', strtotime($today));
	//$yesterday = date ('Y-m-d', $yesterday);
	$fechaselec = $fechars; 
	$fecha = $fechars;
	endif;
	$fecha_ini = date_format(date_create($fechaselec),"Y-m-01");
	$fecha_fin = date_format(date_create($fechaselec),"Y-m-30");
	//*INICIO CONSULTA SIC SOLO TOMA ENTREGADOS EXCLUYE RECHAZADOS//*
		  $db=Db::conectar();
          $select=$db->prepare("
SELECT v.HoraEnt,v.FechaRS,v.CjasEntregadas,v.CjasEntregadasAcu,v.CjasProgramadas,IF(v.CjasEntregadasAcu=0,0,(v.CjasEntregadasAcu/v.CjasProgramadas)*100) as PorcentajeEntre FROM
(
SELECT z.HoraEnt,z.FechaRS,
SUM(z.CjasEntregadas) as CjasEntregadas,
SUM(z.ContactosEntregados) as ContactosEntregados,
(SELECT SUM(e.`Entrega`) FROM (SELECT IF(HOUR(`registrofin`) <= 10,'10:00',IF(HOUR(`registrofin`) <= 12,'12:00',IF(HOUR(`registrofin`) <= 15,'15:00',IF(HOUR(`registrofin`) <= 18,'18:00','19:00>')))) AS HoraEnt,`Entrega` FROM t77_rs AS x WHERE x.`Fecha` = :Fecha  AND x.entregado = 1 AND x.centro = :centro) AS e WHERE e.HoraEnt <= z.HoraEnt) as CjasEntregadasAcu,
(SELECT COUNT(e.`Codigo`) FROM (SELECT IF(HOUR(`registrofin`) <= 10,'10:00',IF(HOUR(`registrofin`) <= 12,'12:00',IF(HOUR(`registrofin`) <= 15,'15:00',IF(HOUR(`registrofin`) <= 18,'18:00','19:00>')))) AS HoraEnt,`Codigo` FROM t77_rs AS x WHERE x.`Fecha` = :Fecha  AND x.entregado = 1 AND x.centro = :centro) AS e WHERE e.HoraEnt <= z.HoraEnt) as ContactosEntregadosAcu,
(SELECT SUM(`Entrega`) FROM t77_rs WHERE Fecha = :Fecha AND centro = :centro GROUP BY `Fecha`,centro) AS CjasProgramadas,
(SELECT COUNT(`Codigo`) FROM t77_rs WHERE Fecha = :Fecha AND centro = :centro GROUP BY `Fecha`,centro) AS ContactosProgramados
FROM ( 
SELECT 
`Codigo`,
IF(HOUR(`registrofin`) <= 10,'10:00',IF(HOUR(`registrofin`) <= 12,'12:00',IF(HOUR(`registrofin`) <= 15,'15:00',IF(HOUR(`registrofin`) <= 18,'18:00','19:00>')))) AS HoraEnt,
DATE(`registrofin`) AS FechaEnt,
`Fecha` as FechaRS, 
IF(entregado=1,`Entrega`,0) as CjasEntregadas,
IF(entregado=1,1,0) as ContactosEntregados,
IF(alerta=1,`Entrega`,0) as CjasAlertadas,
IF(alerta=1,1,0) as ContactosAlertados,
IF(rechazo=1,`Entrega`,0) as CjasRechazo,
IF(rechazo=1,1,0) as ContactosRechazados
FROM t77_rs WHERE Fecha = :Fecha AND centro = :centro
) AS z GROUP BY z.HoraEnt,z.FechaRS ORDER BY z.HoraEnt ASC
) AS v
		  ");
		  $select->bindValue('centro',$idcentro);
		  $select->bindValue('Fecha',$fechaselec);
		  $select->execute();
          while ($arraysic=$select->fetch()) { 
		  $HoraEntS[] = $arraysic['HoraEnt'];
		  $CjasEntregadasAcuS[] = round($arraysic['CjasEntregadasAcu']);
		   $CjasProgramadasS[] = round($arraysic['CjasProgramadas']);
            $SicCajasAcu[]  = round($arraysic['PorcentajeEntre']);	
		  }
	Db::desconectar();
	//*FIN CONSULTA SIC SOLO TOMA ENTREGADOS EXCLUYE RECHAZADOS//*
	
	

		  $db=Db::conectar();
          $select=$db->prepare("
SELECT 
SUM(v.RutASCont) AS RutASCont,
SUM(v.visitAS) AS visitAS,
SUM(v.ejecutados) AS ejecutados,
SUM(v.alertados) AS alertados,
SUM(v.rechazados) AS rechazados,
SUM(v.rutafin) AS rutafin
FROM (
SELECT 
1 AS RutASCont,
SUM(x.visitAS) AS visitAS, 
SUM(x.`entregado`) AS ejecutados,
SUM(x.`alerta`) AS alertados,
SUM(x.`rechazo`) AS rechazados,   
IF(SUM(x.visitAS)=(SUM(x.`entregado`)+SUM(x.`rechazo`)),1,0)  AS rutafin,
x.`Ruta` AS rutAS,
x.`Viaje` AS viajes
from ( SELECT 1 AS visitAS, `entregado`, `alerta`, `rechazo`,`Codigo`,`Ruta`, `Viaje` FROM `t77_rs` WHERE `Fecha`=:Fecha AND centro = :centro ) AS  x 
GROUP BY
x.`Ruta`,
x.`Viaje`
    ) AS v
		  ");
		  $select->bindValue('centro',$idcentro);
		  $select->bindValue('Fecha',$fechaselec);
		  $select->execute();
          while ($rowdashboard=$select->fetch()) {
		   $RutASCont = $rowdashboard['RutASCont'];
		   $visitAS = $rowdashboard['visitAS'];
		   $ejecutados = $rowdashboard['ejecutados'];
		   $alertados = $rowdashboard['alertados'];
		   $rechazados = $rowdashboard['rechazados'];
		   $rutafin = $rowdashboard['rutafin'];		  
		  }			
		  Db::desconectar();
	//*inicio chart horas*//
		  $db=Db::conectar();
          $select=$db->prepare("
SELECT CONCAT(z.HoraEnt,' Hr') as HoraEnt,
SUM(z.CjasEntregadas) as CjasEntregadas,
SUM(z.ContactosEntregados) as ContactosEntregados
FROM ( 
SELECT 
`Codigo`,
HOUR(`registrofin`) AS HoraEnt,
DATE(`registrofin`) AS FechaEnt,
`Fecha` as FechaRS, 
IF(entregado=1,`Entrega`,0) as CjasEntregadas,
IF(entregado=1,1,0) as ContactosEntregados,
IF(alerta=1,`Entrega`,0) as CjasAlertadas,
IF(alerta=1,1,0) as ContactosAlertados,
IF(rechazo=1,`Entrega`,0) as CjasRechazo,
IF(rechazo=1,1,0) as ContactosRechazados
FROM t77_rs WHERE `Fecha`=:Fecha AND centro = :centro
) AS z WHERE z.HoraEnt <> 0 GROUP BY z.HoraEnt,z.FechaRS ORDER BY z.HoraEnt ASC
		  ");
		  $select->bindValue('centro',$idcentro);
		  $select->bindValue('Fecha',$fechaselec);
		  $select->execute();
          while ($arraychartsp=$select->fetch()) { 
		  $HoraEntHr[] = $arraychartsp['HoraEnt'];
		  $CjasEntrHr[] = round($arraychartsp['CjasEntregadas']);
		  $ContEntrHr[] = $arraychartsp['ContactosEntregados'];
		  }
	Db::desconectar();	  
	//*fin chart horas*///	
	//*inicio chart Cajas BK *//
 $db=Db::conectar();
          $select=$db->prepare("
		  SELECT * FROM (
		  SELECT z.`Ruta`,
SUM(z.CjasEntregadas) as CjasEntregadas,
SUM(z.ContactosEntregados) as ContactosEntregados,
SUM(z.CajasProgramadas) as CajasProgramadas,
SUM(z.Contactos) AS ContactosProgramados
FROM ( 
SELECT 
1 as Contactos,
`Codigo`,
`Ruta`,
`Fecha` as FechaRS, 
IF(entregado=1,`Entrega`,0) as CjasEntregadas,
IF(entregado=1,1,0) as ContactosEntregados,
IF(alerta=1,`Entrega`,0) as CjasAlertadas,
IF(alerta=1,1,0) as ContactosAlertados,
IF(rechazo=1,`Entrega`,0) as CjasRechazo,
IF(rechazo=1,1,0) as ContactosRechazados,
`Entrega` AS CajasProgramadas
FROM t77_rs WHERE `Fecha`= :Fecha AND centro = :centro
	 ) AS z GROUP BY z.`Ruta`) AS v ORDER BY v.ContactosProgramados DESC
		  ");
		  $select->bindValue('centro',$idcentro);
		  $select->bindValue('Fecha',$fechaselec);
		  $select->execute();
          while ($arraychart=$select->fetch()) {  
		  $RutaChart[] = substr($arraychart['Ruta'],4,2);
		  $CjasEntChart[] = $arraychart['CjasEntregadas'];
		  $CjasProgChart[] = $arraychart['CajasProgramadas'];
		  $ContEntChart[] = $arraychart['ContactosEntregados'];
		  $ContProgChart[] = $arraychart['ContactosProgramados'];
		  }
	Db::desconectar();		
   //*inicio chart circular*//	
 $db=Db::conectar();
          $select=$db->prepare("
SELECT * FROM (
		  SELECT z.FechaRS,
SUM(z.CjasEntregadas) as CjasEntregadas,
SUM(z.ContactosEntregados) as ContactosEntregados,
SUM(z.CjasPendientes) as CjasPendientes,
SUM(z.ContactosPendientes) as ContactosPendientes,
SUM(z.CajasProgramadas) as CajasProgramadas,
SUM(z.ContactosProgramados) AS ContactosProgramados,
SUM(z.CjasRechazo) as CjasRechazo,
SUM(z.ContactosRechazados) AS ContactosRechazados
FROM ( 
SELECT 
1 as ContactosProgramados,
`Codigo`,
`Ruta`,
`Fecha` as FechaRS, 
IF(entregado=1,`Entrega`,0) as CjasEntregadas,
IF(entregado=1,1,0) as ContactosEntregados,
IF(entregado=1,0,`Entrega`) as CjasPendientes,
IF(entregado=1,0,1) as ContactosPendientes,
IF(alerta=1,`Entrega`,0) as CjasAlertadas,
IF(alerta=1,1,0) as ContactosAlertados,
IF(rechazo=1,`Entrega`,0) as CjasRechazo,
IF(rechazo=1,1,0) as ContactosRechazados,
`Entrega` AS CajasProgramadas
FROM t77_rs WHERE `Fecha`= :Fecha AND centro = :centro
	 ) AS z GROUP BY z.FechaRS ) AS v 
		  ");
		  $select->bindValue('centro',$idcentro);
		  $select->bindValue('Fecha',$fechaselec);
		  $select->execute();
          while ($arraydoughnut=$select->fetch()) {  
		  $CjasEntregadas = round($arraydoughnut['CjasEntregadas']);
		  $ContactosEntregados = round($arraydoughnut['ContactosEntregados']);
		  $CjasPendientes = round($arraydoughnut['CjasPendientes']);
		  $ContactosPendientes = round($arraydoughnut['ContactosPendientes']);
		  $CajasProgramadas = round($arraydoughnut['CajasProgramadas']);
		  $ContactosProgramados = round($arraydoughnut['ContactosProgramados']);
		  $CjasRechazo= round($arraydoughnut['CjasRechazo']);
		  $ContactosRechazados = round($arraydoughnut['ContactosRechazados']);
		  }
	Db::desconectar();		  
    //*final chart circular*//	
?>
	<div class="row border">
    <div class="col-sm-12 bg-danger">
	<div class="d-flex justify-content-center">
	<div class="p-2 bd-highlight"><div class="text-white text-md-center font-weight-bolder">SIC Entregas CD-<?php echo $idcentro.'-'.$fechaselec; ?></div></div>
	<div class="p-2 bd-highlight"><button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#myModalFecha">Fecha</button></div>
	</div>
	</div>
	</div>
   <!-- Modal Inicio-->
	<div class="modal fade" id="myModalFecha" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
	<form method="GET" action="entregas">
	<div class="form-row">		 
	<input  aria-label="First name" id="fechastema" class="form-control" value="<?php echo $fecha; ?>" placeholder="Fecha inicio" type="date" name='fechaselec'> 
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
  <div class="row">
    <div class="border col-sm-4 text-center">
	 <div class="col"><p class="p-0 mb-0 font-weight-bold text-danger">Rutas</p></div>
	 <div class="w-100"></div>
	 <div class="col">
		<div class="row text-center">
			<div class="col">
			    <p class="p-0 mb-0 font-weight-normal">Viajes</p>
				<p class="h2 text-primary font-weight-bold"><?php echo $RutASCont; ?></p>
			</div>
			<div class="col">
				<p class="p-0 mb-0 font-weight-normal">Ejecutados</p>
				<p class="h2 text-success font-weight-bold"><?php echo $rutafin; ?></p>
			</div>
			<div class="col">
			    <p class="p-0 mb-0 font-weight-normal">Pendientes</p>
				<p class="h2 text-danger font-weight-bold"><?php echo ($RutASCont-$rutafin); ?></p>
			</div>
		</div>
	</div>	
	</div>
    <div class="border col-sm-4 text-center">
	 <div class="col"><p class="p-0 mb-0 font-weight-bold text-danger">Visitas</p></div>
	 <div class="w-100"></div>
	 <div class="col">
		<div class="row text-center">
			<div class="col">
			    <p class="p-0 mb-0 font-weight-normal">Entregas</p>
				<p class="h2 text-primary font-weight-bold"><?php echo $visitAS; ?></p>
			</div>
			<div class="col">
				<p class="p-0 mb-0 font-weight-normal">Exitosas</p>
				<p class="h2 text-success font-weight-bold"><?php echo $ejecutados; ?></p>
			</div>
			<div class="col">
			    <p class="p-0 mb-0 font-weight-normal">Fallidas</p>
				<a href="dashboardrechazos" ><p class="h2 text-danger font-weight-bold"> <?php echo ($rechazados); ?></p></a> 
			</div>
		</div>
	</div>	
	</div>
    <div class="border col-sm-4 text-center">
	 <div class="col"><p class="p-0 mb-0 font-weight-bold text-danger">Modulados</p></div>
	 <div class="w-100"></div>
	 <div class="col">
		<div class="row text-center">
			<div class="col">
			    <p class="p-0 mb-0 font-weight-normal">Alertas</p>
				<a href="dashboardalertas" ><p class="h2 text-primary font-weight-bold"> <?php echo $alertados; ?></p></a> 
			</div>
			<div class="col">
				<p class="p-0 mb-0 font-weight-normal"></p>
				<p class="h2 text-success font-weight-bold"></p>
			</div>
			<div class="col">
			    <p class="p-0 mb-0 font-weight-normal"></p>
				<p class="h2 text-danger font-weight-bold"></p>
			</div>
		</div>
	</div>	
	</div>
  </div>	
    <div class="row">
    <div class="col-sm-3 border text-center">
	<div class="p-0 mb-0 font-weight-normal"><canvas id="myChartCajasDoughnut" width="180px" height="180px"></canvas></div>
	<p class="h3 text-primary font-weight-bold"><?php if(isset($CjasEntregadas)){ echo ($CjasEntregadas+$CjasRechazo).' / '.$CajasProgramadas;} ?></p>
	</div>
	<div class="col-sm-3 border text-center">
	<div class="p-0 mb-0 font-weight-normal"><canvas id="myChartContactosDoughnut" width="180px" height="180px"></canvas> </div>
	<p class="h3 text-success font-weight-bold"><?php if(isset($ContactosEntregados)){ echo ($ContactosEntregados+$ContactosRechazados).' / '.$ContactosProgramados; } ?></p>
	</div>
	<div class="col-sm-6 border">
	<div>
   <canvas id="SICentregas" style="max-width:600px"> </canvas>
    </div>
	</div>
    </div>
	<div class="row">
    <div class="col-sm-6 col-md-6 border">
	<div>
	<canvas id="myChartBKcajas" style="max-width:600px"></canvas>
	</div>
	</div>
    <div class="col-sm-6 col-md-6 border">
	<div>
	<canvas id="myChartBKcontactos" style="max-width:600px"> </canvas>
	</div>
	</div>
	</div>
	
    <div class="row">
    <div class="col-sm-6 col-md-6 border">
	
	
    <table class="table table-sm">
	  <thead>
	  <tr>
      <th scope="col">Transporte</th>
      <th scope="col">Viaje</th>
      <th scope="col">Avance</th>
      <th scope="col">Estado</th>
    </tr>
	</thead>
	<tbody>
	<?php
	////inicio consulta	 
	  $db=Db::conectar();
          $select=$db->prepare("
	SELECT z.`Ruta`,z.`Viaje`,
SUM(z.ContactosEntregados) as ContactosEntregados,
SUM(z.ContactosRechazados) as ContactosRechazados,
SUM(z.ContactosProgramados) as ContactosProgramados,
IF(SUM(z.ContactosEntregados)=0,0,((SUM(z.ContactosEntregados)+SUM(z.ContactosRechazados))/SUM(z.ContactosProgramados)*100)) as PorcientoAvance
FROM ( 
SELECT 
1 as ContactosProgramados,
`Codigo`,
`Ruta`,
`Viaje`,
`Fecha` as FechaRS, 
IF(entregado=1,`Entrega`,0) as CjasEntregadas,
IF(entregado=1,1,0) as ContactosEntregados,
IF(alerta=1,`Entrega`,0) as CjasAlertadas,
IF(alerta=1,1,0) as ContactosAlertados,
IF(rechazo=1,`Entrega`,0) as CjasRechazo,
IF(rechazo=1,1,0) as ContactosRechazados,
`Entrega` AS CajasProgramadas
FROM t77_rs WHERE `Fecha`= :Fecha AND centro = :centro
	 ) AS z GROUP BY z.`Ruta`,z.`Viaje`
		  ");
		  $select->bindValue('centro',$idcentro);
		  $select->bindValue('Fecha',$fechaselec);
		  $select->execute();
          while ($avancerutas=$select->fetch()) {
          $ContactosEntregadosv = round($avancerutas['ContactosEntregados']);			  
		  $ContactosRechazadosv = round($avancerutas['ContactosRechazados']);
		  $ContactosProgramadosv = round($avancerutas['ContactosProgramados']);
		  $PorcientoAvance = round($avancerutas['PorcientoAvance']);
          if($PorcientoAvance==0){ $st = 'No iniciado'; $ss='danger'; }else if($PorcientoAvance<=99){ $st = 'En ruta'; $ss='primary'; }else { $st = 'Finalizado'; $ss='success'; }
		  ?>
		<tr>
		<td><?php echo $avancerutas['Ruta']; ?></td> 
		<td><?php echo $avancerutas['Viaje']; ?></td>
		<td>
		<div class="progress">
 		<div class="progress-bar progress-bar-striped bg-<?php echo $ss;?> progress-bar-animated" role="progressbar" aria-valuenow="<?php echo $PorcientoAvance; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $PorcientoAvance;  ?>%"><?php echo ($ContactosEntregadosv+$ContactosRechazadosv).'/'.$ContactosProgramadosv.' ('.$PorcientoAvance.'%)'   ;?></div>
		</div>
		</td>
		<td class="bg-<?php echo $ss;?> text-white"><? echo $st;?></td>
		</tr>
		  <?php
		  }
    	Db::desconectar();	  
	?>
	 </tbody>
	</table>
	</div>
    <div class="col-sm-6 col-md-6 border">
	<div>
	<canvas id="myChartAvanceXhoras" style="max-width:600px"></canvas>
	</div>
	</div>
	</div>
   </main>
		<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
		<script>window.jQuery || document.write('<script src="../assets/js/vendor/jquery.slim.min.js"><\/script>')</script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.9.0/feather.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script>
	<script>
  	Chart.pluginService.register({
		beforeDraw: function (chart) {
			if (chart.config.options.elements.center) {
        //Get ctx from string
        var ctx = chart.chart.ctx;
				//Get options from the center object in options
        var centerConfig = chart.config.options.elements.center;
      	var fontStyle = centerConfig.fontStyle || 'Arial';
				var txt = centerConfig.text;
        var color = centerConfig.color || '#000';
        var sidePadding = centerConfig.sidePadding || 20;
        var sidePaddingCalculated = (sidePadding/100) * (chart.innerRadius * 2)
        //Start with a base font of 30px
        ctx.font = "30px " + fontStyle;
				//Get the width of the string and also the width of the element minus 10 to give it 5px side padding
        var stringWidth = ctx.measureText(txt).width;
        var elementWidth = (chart.innerRadius * 2) - sidePaddingCalculated;
        // Find out how much the font can grow in width.
        var widthRatio = elementWidth / stringWidth;
        var newFontSize = Math.floor(30 * widthRatio);
        var elementHeight = (chart.innerRadius * 2);
        // Pick a new font size so it will not be larger than the height of label.
        var fontSizeToUse = Math.min(newFontSize, elementHeight);
				//Set font settings to draw it correctly.
        ctx.textAlign = 'center';
        ctx.textBaseline = 'middle';
        var centerX = ((chart.chartArea.left + chart.chartArea.right) / 2);
        var centerY = ((chart.chartArea.top + chart.chartArea.bottom) / 2);
        ctx.font = fontSizeToUse+"px " + fontStyle;
        ctx.fillStyle = color;
        //Draw text in center
        ctx.fillText(txt, centerX, centerY);
			}
		}
	});
		var config = {
			type: 'doughnut',
			data: {
				labels: [
				  "Entregado",
				   "Pendiente"
				],
				datasets: [{
						data: [<?php echo $CjasEntregadas.','.($CjasPendientes+$CjasRechazo); ?>],
					backgroundColor: [
					  "#F7951D",
					  "#E1E1E1"
					],
					hoverBackgroundColor: [
					  "#331D0C",
					  "#331D0C"
					]
				}]
			},
		options: {
			    maintainAspectRatio: false,
				cutoutPercentage: 85,
				rotation: Math.PI / 2,
			legend: {
			display: true,
			labels: {
                     boxWidth: 5,
                     padding: 0
                    }
					},
			elements: {
				center: {  
				text: <?php echo '"'.round((($CjasEntregadas+$CjasRechazo)/$CajasProgramadas)*100).'%"'; ?>,
			color: '#F7951D', // Default is #000000
			fontStyle: 'Arial', // Default is Arial
			sidePadding: 20 // Defualt is 20 (as a percentage)
				}
			},
			title: {
			display: true,
			text: 'Productos entregados'
			}
		}
	};
		var ctx = document.getElementById("myChartCajasDoughnut").getContext("2d");
		var myChartCajasDoughnut = new Chart(ctx, config);
    </script>
	<script>
  	Chart.pluginService.register({
		beforeDraw: function (chart) {
			if (chart.config.options.elements.center) {
        //Get ctx from string
        var ctx = chart.chart.ctx;
				//Get options from the center object in options
        var centerConfig = chart.config.options.elements.center;
      	var fontStyle = centerConfig.fontStyle || 'Arial';
				var txt = centerConfig.text;
        var color = centerConfig.color || '#000';
        var sidePadding = centerConfig.sidePadding || 20;
        var sidePaddingCalculated = (sidePadding/100) * (chart.innerRadius * 2)
        //Start with a base font of 30px
        ctx.font = "30px " + fontStyle;
				//Get the width of the string and also the width of the element minus 10 to give it 5px side padding
        var stringWidth = ctx.measureText(txt).width;
        var elementWidth = (chart.innerRadius * 2) - sidePaddingCalculated;
        // Find out how much the font can grow in width.
        var widthRatio = elementWidth / stringWidth;
        var newFontSize = Math.floor(30 * widthRatio);
        var elementHeight = (chart.innerRadius * 2);
        // Pick a new font size so it will not be larger than the height of label.
        var fontSizeToUse = Math.min(newFontSize, elementHeight);
				//Set font settings to draw it correctly.
        ctx.textAlign = 'center';
        ctx.textBaseline = 'middle';
        var centerX = ((chart.chartArea.left + chart.chartArea.right) / 2);
        var centerY = ((chart.chartArea.top + chart.chartArea.bottom) / 2);
        ctx.font = fontSizeToUse+"px " + fontStyle;
        ctx.fillStyle = color;
        //Draw text in center
        ctx.fillText(txt, centerX, centerY);
			}
		}
	});
		var config = {
			type: 'doughnut',
			data: {
				labels: [
				  "Entregado",
				   "Pendiente"
				],
				datasets: [{
					data: [<?php echo $ContactosEntregados.','.($ContactosPendientes+$ContactosRechazados); ?>],
					backgroundColor: [
					  "#005A9C",
					  "#E1E1E1"
					],
					hoverBackgroundColor: [
					  "#331D0C",
					  "#331D0C"
					]
				}]
			},
		options: {
			    maintainAspectRatio: false,
				cutoutPercentage: 85,
				rotation: Math.PI / 2,
			legend: {
			display: true,
			labels: {
                     boxWidth: 5,
                     padding: 0
                    }
					},
			elements: {
				center: {
					text: <?php echo '"'.round((($ContactosEntregados+$ContactosRechazados)/$ContactosProgramados)*100).'%"'; ?>,
			color: '#005A9C', // Default is #000000
			fontStyle: 'Arial', // Default is Arial
			sidePadding: 20 // Defualt is 20 (as a percentage)
				}
			},
			title: {
			display: true,
			text: 'Visitas finalizadas'
			}
		}
	};
		var ctx = document.getElementById("myChartContactosDoughnut").getContext("2d");
		var myChartContactosDoughnut = new Chart(ctx, config);
    </script>
	
	<script>
		var barChartData = {
			labels: [<?php echo "'".implode("','", $HoraEntHr)."'"; ?>],
			datasets: [{
				label: 'Cajas',
				backgroundColor: 'rgb(192, 0, 0)',
                yAxisID: 'y-axis-1',
				data: [<?php echo implode(",", $CjasEntrHr); ?>],
			}, {
				label: 'Contactos',
				 backgroundColor: 'rgb(255, 192, 0)',
				yAxisID: 'y-axis-2',
				data: [<?php echo implode(",", $ContEntrHr); ?>],
                
			}]
		};
		window.onload = function() {
			var ctx = document.getElementById('myChartAvanceXhoras').getContext('2d');
			window.myBar = new Chart(ctx, {
				type: 'bar',
				data: barChartData,
				options: {
					responsive: true,
					title: {
						display: true,
						text: 'Avance Entregas Por Horas'
					},
					tooltips: {
						mode: 'index',
						intersect: true
                    },
                    
					scales: {
						yAxes: [{
							type: 'linear', // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
							display: true,
							position: 'left',
							id: 'y-axis-1',
						}, {
							type: 'linear', // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
							display: true,
							position: 'right',
							id: 'y-axis-2',
							gridLines: {
								drawOnChartArea: false
							}
						}],
					},
					legend: {
						display: true,
						labels: {
						boxWidth: 20,
						padding: 10
                    }
					}
				}
			});
		};   
	</script>
	<script>
new Chart(document.getElementById("myChartBKcajas"), {
    type: 'bar',
    data: {
      labels: [<?php echo "'".implode("','", $RutaChart)."'"; ?>],
      datasets: [{
          label: "Europe",
		  type: "bar",
		 label: 'Entregadas',
         backgroundColor: 'rgb(224, 33, 36)',
          data: [<?php echo implode(",", $CjasEntChart); ?>],
        }, 
		{
          label: "Europe",
		  type: "line",
		  label: 'Programadas',
		  backgroundColor: 'rgb(247, 149, 29)',
          borderColor: 'rgb(255, 242, 0)',
          data: [<?php echo implode(",", $CjasProgChart); ?>],
          fill: false
        }
      ]
    },
    options: {
		scales: {
               xAxes: [{ barPercentage: 0.7 }]
           },
      title: {
        display: true,
         text: 'Avance Cajas Por Reparto BK'
      },
	 legend: {
			display: true,
			labels: {
                     boxWidth: 20,
                     padding: 10
                    }
					}
    }
});
	</script>
	<script>
new Chart(document.getElementById("myChartBKcontactos"), {
    type: 'bar',
    data: {
     labels: [<?php echo "'".implode("','", $RutaChart)."'"; ?>],
      datasets: [{
          label: "Europe",
		  type: "bar",
		 label: 'Entregados',
		  backgroundColor: 'rgb(224, 33, 36)',
		  data: [<?php echo implode(",", $ContEntChart); ?>],
        }, 
		{
          label: "Europe",
		  type: "line",
		 label: 'Programados',
		 backgroundColor: 'rgb(255, 242, 0)',
          borderColor: 'rgb(255, 242, 0)',
           data: [<?php echo implode(",", $ContProgChart); ?>],
          fill: false
        }
      ]
    },
    options: {
		 scales: {
               xAxes: [{ barPercentage: 0.7 }]
           },
      title: {
        display: true,
       text: 'Avance Contactos Por Reparto BK'
      },
	 legend: {
			display: true,
			labels: {
                     boxWidth: 20,
                     padding: 10
                    }
					}
    }
});
	</script>
	
		<script>
new Chart(document.getElementById("SICentregas"), {
    type: 'bar',
    data: {
	  labels: [<?php echo "'".implode("','", $HoraEntS)."'"; ?>], 	
	  datasets: [{
	      label: "Horas",
		  type: "line",
		  label: '% Avance', 
		  data: [<?php echo implode(",", $SicCajasAcu); ?>],
           fill: false,
           backgroundColor: "#0000FF",
           borderColor: "#0000FF",
           borderCapStyle: 'butt',
           borderDash: [],
           borderDashOffset: 0.0,
           borderJoinStyle: 'miter',
           lineTension: 0.3,
           pointBackgroundColor: "#0000FF",
           pointBorderColor: "#0000FF",
           pointBorderWidth: 1,
           pointHoverRadius: 5,
           pointHoverBackgroundColor: "#0000FF",
           pointHoverBorderColor: "#0000FF",
           pointHoverBorderWidth: 2,
           pointRadius: 4,
           pointHitRadius: 10
        },		
		{
          label: "Rojo",
		  type: "bar",
          backgroundColor: 'rgb(255, 0, 0)',
          data: [30,30,30,30,30],
        },
		{
          label: "Amarillo",
		  type: "bar",
          backgroundColor: 'rgb(255, 255, 0)',
          data: [30,30,30,30,30],
        },
		{
		  label: "Verde",
		  type: "bar",
          backgroundColor: 'rgb(0, 128, 0)',
          data: [40,40,40,40,40],
        }
      ]
    },
    options: {
		scales: {
               xAxes: [{stacked: true, barPercentage: 2 }],
			   yAxes: [{stacked: true}],
           },
	   responsive: true,	   
      title: {
        display: true,
         text: 'SIC % Entrega Producto'
      },
	 legend: {
			display: true,
			labels: {
                     boxWidth: 20,
                     padding: 10
                    }
					},
	tooltips: {
				mode: 'label',
				intersect: false
			  },				
    }	
});
	</script>	
	<?php 		
	} else {
	echo $html_acceso;		
	}
	} ?>
</body>
</html>
