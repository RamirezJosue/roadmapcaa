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
	//$yesterday = strtotime('-1 day', strtotime($today));
	//$yesterday = date ('Y-m-d', $yesterday);
	$fechaselec = $fechars; 
	$fecha = $fechars;
	endif;
	$fecha_ini = date_format(date_create($fechaselec),"Y-m-01");
	$fecha_fin = date_format(date_create($fechaselec),"Y-m-30");
	//*inicio Alertas Supervior*//
		  $db=Db::conectar();
          $select=$db->prepare("
		  SELECT mr,CjasRechazo,colorchart FROM 
			(SELECT mr, sum(cjasrechazadas) as CjasRechazo FROM `t77_rs` WHERE rechazo = 1 AND Fecha = :Fecha AND centro = :centro AND Entrega <> 0 GROUP BY mr) AS c 
			LEFT JOIN 
			(SELECT * FROM `t77_mr`) as b 
			ON c.mr = b.descripcion 
		  ");
		  $select->bindValue('centro',$idcentro);
		  $select->bindValue('Fecha',$fechaselec);
		  $select->execute();
          while ($arraychartsp=$select->fetch()) { 
		  $MrArray[] = $arraychartsp['mr'];
		  $MrCajas[] = round($arraychartsp['CjasRechazo']);
		  $MrColorChart[] = $arraychartsp['colorchart'];
		  }
		  Db::desconectar();
		  //*final chart circular*//	
/* inicio por supervisor*/
		  $db=Db::conectar();
          $select=$db->prepare("
SELECT mc.supervisor,rs.Fecha,rs.centro,SUM(rs.cjasrechazadas) AS cjsrech,SUM(rs.rechazo) AS contalerta
FROM
    (SELECT * FROM t77_rs WHERE Fecha = :Fecha AND centro = :centro AND rechazo = 1) AS rs
LEFT JOIN
	(SELECT * FROM t77_mc) AS mc
ON rs.Codigo = mc.codcli
GROUP BY mc.supervisor,rs.Fecha,rs.centro
		  ");
		  $select->bindValue('centro',$idcentro);
		  $select->bindValue('Fecha',$fechaselec);
		  $select->execute();
          while ($arraychartsp=$select->fetch()) { 
		  $rechsuper[] = $arraychartsp['supervisor'];
		  $rechsupercajas[] = $arraychartsp['cjsrech'];
		  $rechsupercont[] = $arraychartsp['contalerta'];
		  }
		  Db::desconectar();
/* fin por supervisor*/		  
	//*inicio  empresa*//
		  $db=Db::conectar();
          $select=$db->prepare("
SELECT em.empresa,rs.Fecha,rs.centro,sum(rs.cjasrechazadas) as cjsrech, sum(rs.rechazo) as contalerta 
FROM 
(SELECT * FROM t77_rs WHERE Fecha = :Fecha AND centro = :centro AND rechazo = 1) AS rs
LEFT JOIN 
(SELECT * FROM t77_em) AS em 
ON rs.Ruta = em.ruta 
GROUP BY  em.empresa,rs.Fecha,rs.centro		  
		  ");
		  $select->bindValue('centro',$idcentro);
		  $select->bindValue('Fecha',$fechaselec);
		  $select->execute();
          while ($arraychartem=$select->fetch()) { 
		  $rechempresa[] = $arraychartem['empresa'];
		  $rechempresacajas[] = $arraychartem['cjsrech'];
		  $rechempresacont[] = $arraychartem['contalerta'];
		  }
		  Db::desconectar();
	//*inicio chart Cajas BK */
           $db=Db::conectar();
          $select=$db->prepare("
SELECT Ruta,Fecha,centro,sum(cjasrechazadas) as cjsrech, sum(rechazo) as contalerta 
FROM t77_rs WHERE Fecha = :Fecha AND centro = :centro AND rechazo = 1 
GROUP BY  Ruta,Fecha,centro		  
		  ");
		  $select->bindValue('centro',$idcentro);
		   $select->bindValue('Fecha',$fechaselec);
		  $select->execute();
          while ($arraychart=$select->fetch()) { 
		  $rechbk[] = $arraychart['Ruta'];
		  $rechbkcajas[] = $arraychart['cjsrech'];
		  $rechbkcont[] = $arraychart['contalerta'];
		  }	
	//*fin chart Cajas BK */	  
?>
	<div class="row border">
    <div class="col-sm-12 bg-danger">
	<div class="d-flex justify-content-center">
	<div class="p-2 bd-highlight"><div class="text-white text-md-center font-weight-bolder">SIC Rechazos CD-<?php echo $idcentro.'-'.$fechaselec; ?></div></div>
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
	<form method="GET" action="rechazos">
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
	
	<!-- Modal fecha Fin-->
	<div class="row">
    <div class="col-sm-6 col-md-6 border">
	<div>
	  <canvas id="SICRechazos" style="max-width:600px"> </canvas>
	</div>
	</div>
    <div class="col-sm-6 col-md-6 border">
	<div>
	<?php 
			  		  $db=Db::conectar();
          $select=$db->prepare("
SELECT v.HoraEnt,v.FechaRS,v.CjasRechazo,v.CjasRechazoAcu,v.CjasProgramadas,
v.ContactosRechazadosAcu,v.ContactosProgramados,
IF(v.CjasRechazoAcu=0,0,(v.CjasRechazoAcu/v.CjasProgramadas)*100) as PorcentajeRechazo FROM
(
SELECT z.HoraEnt,z.FechaRS,
SUM(z.CjasRechazo) as CjasRechazo,
SUM(z.ContactosRechazados) as ContactosRechazados,
(SELECT SUM(e.`cjasrechazadas`) FROM (SELECT IF(HOUR(`registrofin`) <= 10,'10:00',IF(HOUR(`registrofin`) <= 12,'12:00',IF(HOUR(`registrofin`) <= 15,'15:00',IF(HOUR(`registrofin`) <= 18,'18:00','19:00>')))) AS HoraEnt,`cjasrechazadas` FROM t77_rs AS x WHERE x.`Fecha`= :Fecha AND x.centro = :centro AND x.rechazo = 1) AS e WHERE e.HoraEnt <= z.HoraEnt) as CjasRechazoAcu,
(SELECT COUNT(e.`Codigo`) FROM (SELECT IF(HOUR(`registrofin`) <= 10,'10:00',IF(HOUR(`registrofin`) <= 12,'12:00',IF(HOUR(`registrofin`) <= 15,'15:00',IF(HOUR(`registrofin`) <= 18,'18:00','19:00>')))) AS HoraEnt,`Codigo` FROM t77_rs AS x WHERE x.`Fecha`= :Fecha AND x.centro = :centro AND x.rechazo = 1) AS e WHERE e.HoraEnt <= z.HoraEnt) as ContactosRechazadosAcu,
(SELECT SUM(`Entrega`) FROM t77_rs WHERE `Fecha`= :Fecha AND centro = :centro  GROUP BY `Fecha`) AS CjasProgramadas,
(SELECT COUNT(`Codigo`) FROM t77_rs WHERE `Fecha`= :Fecha AND centro = :centro GROUP BY `Fecha`) AS ContactosProgramados
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
IF(rechazo=1,`cjasrechazadas`,0) as CjasRechazo,
IF(rechazo=1,1,0) as ContactosRechazados
FROM t77_rs WHERE `Fecha`= :Fecha AND centro = :centro
) AS z GROUP BY z.HoraEnt,z.FechaRS ORDER BY z.HoraEnt ASC
) AS v
		  ");
		  $select->bindValue('centro',$idcentro);
		  $select->bindValue('Fecha',$fechaselec);
		  $select->execute();
		  ?> 
		<br><p class="text-muted text-center">Cajas Rechazadas</p>  
		<table class="table table-sm table-bordered">
		  <thead>
			<tr >
			  <th scope="col">Hora</th>
			  <th scope="col">Cjs.RechAcu.</th>
			  <th scope="col">Poc.RechAcu.</th>
			  <th scope="col">Cjs.Prog.</th>
			  <th scope="col">Poc.Prog.</th>		  
			  <th scope="col">%Rechazo</th>
			</tr>
		  </thead>
		  <tbody>
		  <?php 
          while ($arraysic=$select->fetch()) { 
		?>	
	    <tr>
		<td><?php echo $arraysic['HoraEnt']; ?></td>
		<td><?php echo round($arraysic['CjasRechazoAcu']); ?></td>
		<td><?php echo round($arraysic['ContactosRechazadosAcu']); ?></td>
		<td><?php echo round($arraysic['CjasProgramadas']); ?></td>
		<td><?php echo round($arraysic['ContactosProgramados']); ?></td>
		<td><?php echo round($arraysic['PorcentajeRechazo'],2); ?>%</td>
		</tr>
		<?php
		 $HoraEntS[] = $arraysic['HoraEnt'];
		  $CjasRechazoAcuS[] = round($arraysic['CjasRechazoAcu']);
		   $CjasProgramadasS[] = round($arraysic['CjasProgramadas']);
            $PorcentajeRechazoS[]  = round($arraysic['PorcentajeRechazo']);	
		  }
		  Db::desconectar();
	?>	
		</tbody>
		</table>
	</div>
	</div>
	</div>
	<div class="row">
    <div class="col-sm-6 col-md-6 border">
	<div>
	 <canvas id="pieChart"></canvas>
	</div>
	</div>
    <div class="col-sm-6 col-md-6 border">
	<div>
	 <canvas id="myChartRechazoSupervisor"></canvas>
	</div>
	</div>
	</div>
	<div class="row">
    <div class="col-sm-6 col-md-6 border">
	<div>
	 <canvas id="myChartRechazoEmpresario"></canvas>
	</div>
	</div>
    <div class="col-sm-6 col-md-6 border">
	<div>
	 <canvas id="myChartRechazoBK"></canvas>
	</div>
	</div>
	</div>
	<div class="row">
    <div class="col-sm-6 col-md-12 border">
	<div>
	<?php 
		  $db=Db::conectar();
          $select=$db->prepare("
SELECT Codigo,Cliente,Ruta,Fecha,centro,Entrega,cjasrechazadas,mr,autoriza_rech 
FROM t77_rs WHERE Fecha = :Fecha AND centro = :centro AND rechazo = 1 
		  ");
		  $select->bindValue('centro',$idcentro);
		  $select->bindValue('Fecha',$fechaselec);		  
		  $select->execute();
		  Db::desconectar();
		  ?> 
		<br><p class="text-muted text-center">Cajas Rechazadas</p> 
       <div class="table-responsive-sm">		
		<table class="table table-sm table-bordered">
		  <thead>
			<tr >
			  <th scope="col">#</th>
			  <th scope="col">Codigo</th>
			  <th scope="col">Cliente</th>
			  <th scope="col">Ruta</th>
			  <th scope="col">Motivo</th>
			  <th scope="col">Autorizo</th>
			  <th scope="col">CjsProg</th>
			  <th scope="col">CjsRech</th>
			</tr>
		  </thead>
		  <tbody>
		  <?php 
		  $i=1;
          while ($arraysic=$select->fetch()){	  
		?>	
	    <tr>
		<td><?php echo $i;  ?></td>
		<td><?php echo $arraysic['Codigo']; ?></td>
		<td><?php echo $arraysic['Cliente']; ?></td>
	    <td><?php echo $arraysic['Ruta']; ?></td>
		<td><?php echo $arraysic['mr']; ?></td>
		<td><?php echo $arraysic['autoriza_rech']; ?></td>
		<td><?php echo $arraysic['Entrega']; ?></td>
		<td><?php echo $arraysic['cjasrechazadas']; ?></td>
		</tr>
		<?php
		$i++;
		  }	  
		  $MrCajas = isset($MrCajas) ? $MrCajas : array('nobody') ;
		  $MrColorChart = isset($MrColorChart) ? $MrColorChart : array('nobody') ;
		  $MrArray = isset($MrArray) ? $MrArray : array('nobody') ;
	?>	
		</tbody>
		</table>
		</div>
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
var data = {
    datasets: [{
        data: [<?php echo implode(",", $MrCajas); ?>],
        backgroundColor: [<?php echo "'".implode("','", $MrColorChart)."'"; ?>],
        label: 'My dataset' // for legend
    }],
    labels: [<?php echo "'".implode("','", $MrArray)."'"; ?>]
};

var pieOptions = {
  events: false,
  animation: {
    duration: 500,
    easing: "easeOutQuart",
    onComplete: function () {
      var ctx = this.chart.ctx;
      ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontFamily, 'normal', Chart.defaults.global.defaultFontFamily);
      ctx.textAlign = 'center';
      ctx.textBaseline = 'bottom';

      this.data.datasets.forEach(function (dataset) {

        for (var i = 0; i < dataset.data.length; i++) {
          var model = dataset._meta[Object.keys(dataset._meta)[0]].data[i]._model,
              total = dataset._meta[Object.keys(dataset._meta)[0]].total,
              mid_radius = model.innerRadius + (model.outerRadius - model.innerRadius)/2,
              start_angle = model.startAngle,
              end_angle = model.endAngle,
              mid_angle = start_angle + (end_angle - start_angle)/2;

          var x = mid_radius * Math.cos(mid_angle);
          var y = mid_radius * Math.sin(mid_angle);

          ctx.fillStyle = '#fff';
          if (i == 3){ // Darker text color for lighter background
            ctx.fillStyle = '#444';
          }
          var percent = String(Math.round(dataset.data[i]/total*100)) + "%";
          ctx.fillText(dataset.data[i], model.x + x, model.y + y);
          // Display percent in another line, line break doesn't work for fillText
          ctx.fillText(percent, model.x + x, model.y + y + 15);
        }
      });               
    }
  },
        title: {
        display: true,
         text: 'Motivos de Rechazo'
      },
};

var pieChartCanvas = $("#pieChart");
var pieChart = new Chart(pieChartCanvas, {
  type: 'doughnut', // or doughnut
  data: data,
  options: pieOptions
});
	</script>
	<?php   if(max($CjasRechazoAcuS)>50) { $maxrech=max($CjasRechazoAcuS); }else { $maxrech=50; } ?>
	<script>
	
	
new Chart(document.getElementById("SICRechazos"), {
    type: 'bar',
    data: {
	  labels: [<?php echo "'".implode("','", $HoraEntS)."'"; ?>], 	
	  datasets: [{
	      label: "Horas",
		  type: "line",
		  label: 'Cajas Rechazo', 
		  data: [<?php echo implode(",", $CjasRechazoAcuS); ?>],
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
		  label: "Verde",
		  type: "bar",
          backgroundColor: 'rgb(0, 128, 0)',
          data: [50,50,50,50,50],
        },
		{
          label: "Amarillo",
		  type: "bar",
          backgroundColor: 'rgb(255, 255, 0)',
          data: [50,50,50,50,50],
        },
		{
		  label: "Rojo",
		  type: "bar",
          backgroundColor: 'rgb(255, 0, 0)',
          data: [<?php echo $maxrech; ?>,<?php echo $maxrech; ?>,<?php echo $maxrech; ?>,<?php echo $maxrech; ?>,<?php echo $maxrech; ?>],
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
         text: 'SIC Cajas Rechazo Producto'
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
	$rechsuper = isset($rechsuper) ? $rechsuper : array('nobody') ;
	$rechsupercajas = isset($rechsupercajas) ? $rechsupercajas : array('nobody') ;
	$rechsupercont = isset($rechsupercont) ? $rechsupercont : array('nobody') ;	
	?>
	<script>
			var barChartData = {
			labels: [<?php echo "'".implode("','", $rechsuper)."'"; ?>],
			datasets: [{
				label: 'Cajas',
				backgroundColor: 'rgb(192, 0, 0)',
                yAxisID: 'y-axis-1',
				data: [<?php echo implode(",", $rechsupercajas); ?>],
			}, {
				label: 'Contactos',
				 backgroundColor: 'rgb(255, 192, 0)',
				yAxisID: 'y-axis-2',
				data: [<?php echo implode(",", $rechsupercont); ?>],    
			}]
		};
	new Chart(document.getElementById("myChartRechazoSupervisor"), {
				type: 'bar',
				data: barChartData,
				options: {
					responsive: true,
					title: {
						display: true,
						text: 'Rechazos Por Supervisor'
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
	</script>
	<?php
	$rechempresa = isset($rechempresa) ? $rechempresa : array('nobody') ;
	$rechempresacajas = isset($rechempresacajas) ? $rechempresacajas : array('nobody') ;
	$rechempresacont = isset($rechempresacont) ? $rechempresacont : array('nobody') ;	
	?>	
	<script>
			var barChartData = {
			labels: [<?php echo "'".implode("','", $rechempresa)."'"; ?>],
			datasets: [{
				label: 'Cajas',
				backgroundColor: 'rgb(192, 0, 0)',
                yAxisID: 'y-axis-1',
				data: [<?php echo implode(",", $rechempresacajas); ?>],
			}, {
				label: 'Contactos',
				 backgroundColor: 'rgb(255, 192, 0)',
				yAxisID: 'y-axis-2',
				data: [<?php echo implode(",", $rechempresacont); ?>],    
			}]
		};
	new Chart(document.getElementById("myChartRechazoEmpresario"), {
				type: 'bar',
				data: barChartData,
				options: {
					responsive: true,
					title: {
						display: true,
						text: 'Rechazos Por Empresario'
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
	</script>
		<?php
	$rechbk = isset($rechbk) ? $rechbk : array('nobody') ;
	$rechbkcajas = isset($rechbkcajas) ? $rechbkcajas : array('nobody') ;
	$rechbkcont = isset($rechbkcont) ? $rechbkcont : array('nobody') ;	
	?>
	<script>
			var barChartData = {
			labels: [<?php echo "'".implode("','", $rechbk)."'"; ?>],
			datasets: [{
				label: 'Cajas',
				backgroundColor: 'rgb(192, 0, 0)',
                yAxisID: 'y-axis-1',
				data: [<?php echo implode(",", $rechbkcajas); ?>],
			}, {
				label: 'Contactos',
				 backgroundColor: 'rgb(255, 192, 0)',
				yAxisID: 'y-axis-2',
				data: [<?php echo implode(",", $rechbkcont); ?>],    
			}]
		};
	new Chart(document.getElementById("myChartRechazoBK"), {
				type: 'bar',
				data: barChartData,
				options: {
					responsive: true,
					title: {
						display: true,
						text: 'Rechazos Por Ruta'
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
	</script>	
	<?php 		
	} else {
	echo $html_acceso;		
	}
	} ?>
</body>
</html>
