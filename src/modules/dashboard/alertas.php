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
          $select=$db->prepare("SELECT mc.supervisor,rs.Fecha,rs.centro,sum(rs.Entrega) as cjsentreg, sum(rs.alerta) as contalerta FROM (SELECT * FROM t77_rs WHERE Fecha=:Fecha AND centro=:centro AND alerta=1) AS rs LEFT JOIN (SELECT * FROM t77_mc) AS mc ON rs.Codigo = mc.codcli WHERE mc.supervisor <> '' GROUP BY mc.supervisor,rs.Fecha,rs.centro");
		  $select->bindValue('centro',$idcentro);
		  $select->bindValue('Fecha',$fechaselec);
		  $select->execute();
          while ($arraychartsp=$select->fetch()) { 
		  $suparraysp[] = $arraychartsp['supervisor'];
		  $cajasarraysp[] = $arraychartsp['cjsentreg'];
		  $contarraysp[] = $arraychartsp['contalerta'];
		  }
		  Db::desconectar();
		  $db=Db::conectar();
          $selectmod=$db->prepare("
SELECT 
z.Fecha,z.CjsModRech,z.CjsAler,z.ContAler,z.ContModRech, 
(z.CjsAler - z.CjsModRech) as CjsSalv, 
(z.ContAler - z.ContModRech) as ContSalv, 
((z.CjsAler - z.CjsModRech)/z.CjsAler)*100 as CjsEfect, 
((z.CjsModRech+((z.CjsAler - z.CjsModRech)))/z.CjsAler)*100 as CjsEfici,
((z.ContAler - z.ContModRech)/z.ContAler)*100 as ContEfect, 
((z.ContModRech+((z.ContAler - z.ContModRech)))/z.ContAler)*100 as ContEfici
FROM
( 		  
SELECT
a.Fecha,SUM(a.CjsModRech) AS CjsModRech, SUM(a.CjsAler) AS CjsAler, SUM(a.ContAler) AS ContAler,SUM(a.ContModRech) AS ContModRech
FROM (
SELECT Fecha, IF(rechazo=1,SUM(cjasrechazadas),0) as CjsModRech, SUM(Entrega) AS CjsAler, Codigo, 1 AS ContAler, IF(rechazo=1,1,0) AS ContModRech  FROM `t77_rs` 
WHERE centro=:centro
AND `Fecha`=:Fecha AND alerta=1 GROUP BY Fecha,rechazo,Codigo
	) AS a GROUP BY a.Fecha	
	) AS z
		  ");
		  $selectmod->bindValue('centro',$idcentro);
		  $selectmod->bindValue('Fecha',$fechaselec);
		  $selectmod->execute();
          while ($rowmod=$selectmod->fetch()){
			$CjsModRech=$rowmod['CjsModRech'];
			$CjsAler=$rowmod['CjsAler'];
			$ContAler=$rowmod['ContAler'];
			$ContModRech=$rowmod['ContModRech'];
			
			$CjsSalv=$rowmod['CjsSalv'];			
			$ContSalv=$rowmod['ContSalv'];
			$CjsEfect=$rowmod['CjsEfect'];
			$CjsEfici=$rowmod['CjsEfici'];
			$ContEfect=$rowmod['ContEfect'];
			$ContEfici=$rowmod['ContEfici'];
		  }
		  Db::desconectar();		  
	//*fin Alertas Supervior*//
		  $db=Db::conectar();
          $select=$db->prepare("SELECT em.empresa,rs.Fecha,rs.centro,sum(rs.Entrega) as cjsentreg, sum(rs.alerta) as contalerta FROM (SELECT * FROM t77_rs WHERE Fecha=:Fecha AND centro=:centro AND alerta=1) AS rs LEFT JOIN (SELECT * FROM t77_em) AS em ON rs.Ruta = em.ruta GROUP BY  em.empresa,rs.Fecha,rs.centro");
		  $select->bindValue('centro',$idcentro);
		  $select->bindValue('Fecha',$fechaselec);
		  $select->execute();
          while ($arraychartem=$select->fetch()) { 
		  $empresarrayem[] = $arraychartem['empresa'];
		  $cajasarrayem[] = $arraychartem['cjsentreg'];
		  $contarrayem[] = $arraychartem['contalerta'];
		  }
		  Db::desconectar();
	//*inicio chart Cajas BK */
           $db=Db::conectar();
          $select=$db->prepare("SELECT Ruta,Fecha,centro,sum(Entrega) as cjsentreg, sum(alerta) as contalerta FROM t77_rs WHERE centro=:centro AND alerta=1 AND Fecha=:Fecha  GROUP BY  Ruta,Fecha,centro");
		  $select->bindValue('centro',$idcentro);
		   $select->bindValue('Fecha',$fechaselec);
		  $select->execute();
          while ($arraychart=$select->fetch()) { 
		  $rutasarray[] = $arraychart['Ruta'];
		  $cajasarray[] = $arraychart['cjsentreg'];
		  $contarray[] = $arraychart['contalerta'];
		  }
		  Db::desconectar();
		  $db=Db::conectar();
          $select=$db->prepare("
SELECT 
DATE_FORMAT(z.Fecha, '%e') as Dia ,z.CjsModRech,z.CjsAler,z.ContAler,z.ContModRech, 
(z.CjsAler - z.CjsModRech) as CjsSalv, 
(z.ContAler - z.ContModRech) as ContSalv, 
((z.CjsAler - z.CjsModRech)/z.CjsAler)*100 as CjsEfect, 
((z.CjsModRech+((z.CjsAler - z.CjsModRech)))/z.CjsAler)*100 as CjsEfici,
((z.ContAler - z.ContModRech)/z.ContAler)*100 as ContEfect, 
((z.ContModRech+((z.ContAler - z.ContModRech)))/z.ContAler)*100 as ContEfici
FROM
( 		  
SELECT
a.Fecha,SUM(a.CjsModRech) AS CjsModRech, SUM(a.CjsAler) AS CjsAler, SUM(a.ContAler) AS ContAler,SUM(a.ContModRech) AS ContModRech
FROM (
SELECT Fecha, IF(rechazo=1,SUM(cjasrechazadas),0) as CjsModRech, SUM(Entrega) AS CjsAler, Codigo, 1 AS ContAler, IF(rechazo=1,1,0) AS ContModRech  FROM `t77_rs` 
WHERE centro=:centro
AND `Fecha`>='$fecha_ini' AND `Fecha`<='$fecha_fin' AND alerta=1 GROUP BY Fecha,rechazo,Codigo
	) AS a GROUP BY a.Fecha	
	) AS z
		  ");
		  $select->bindValue('centro',$idcentro);
		  $select->execute();
          while ($rechazodiario=$select->fetch()) { 
		  $Rechdia[] = $rechazodiario['Dia'];
		  $CjsRech[] = round($rechazodiario['ContEfect'],2);
		  $MetaRech[] = round($rechazodiario['ContEfici'],2);
		  }
		Db::desconectar();		  
?>
	<div class="row border">
    <div class="col-sm-12 bg-danger">
	<div class="d-flex justify-content-center">
	<div class="p-2 bd-highlight"><div class="text-white text-md-center font-weight-bolder">Modulaciones CD-<?php echo $idcentro.'-'.$fechaselec; ?></div></div>
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
	<form method="get" action="alertas">
	<div class="form-row">	
	<input  aria-label="First name" id="fechastema" class="form-control" value="<?php echo $fechaselec; ?>" placeholder="Fecha inicio" type="date" name='fechaselec'> 
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
    <div class="border col-sm-6 text-center">
	 <div class="col"><p class="p-0 mb-0 font-weight-bold text-danger">Contactos Modulaciones <?php echo $fechaselec; ?></p></div>
	 <div class="w-100"></div>
	 <div class="col">
		<div class="row text-center">
			<div class="col">
				<p><small>Alertados</small></p>
				<p class="h4 text-success font-weight-bold"><?php echo isset($ContAler)? $ContAler: 0; ?></p>
			</div>		
			<div class="col">
			    <p><small>Rechazados</small></p>
				<p class="h4 text-primary font-weight-bold"><?php echo isset($ContModRech)? $ContModRech: 0; ?></p>
			</div>
			<div class="col">
				<p><small>Salvados</small></p>
				<p class="h4 text-success font-weight-bold"><?php echo isset($ContSalv)? $ContSalv: 0; ?></p>
			</div>			
			<div class="col">
			    <p><small>%Efectividad</small></p>
				<p class="h4 text-danger font-weight-bold"><?php echo  isset($ContEfect)? round($ContEfect,1) :0; ?></p>
			</div>
			<div class="col">
			    <p><small>%Eficiencia</small></p>
				<p class="h4 text-danger font-weight-bold"><?php echo  isset($ContEfici)? round($ContEfici,1):0; ?></p>
			</div>			
		</div>
	</div>	
	</div>
    <div class="border col-sm-6 text-center">
	 <div class="col"><p class="p-0 mb-0 font-weight-bold text-danger">Cajas modulaciones  <?php echo $fechaselec; ?></p></div>
	 <div class="w-100"></div>
	 <div class="col">
		<div class="row text-center">
			<div class="col">
				<p><small>Alertados</small></p>
				<p class="h4 text-success font-weight-bold"><?php echo isset($CjsAler)? round($CjsAler): 0; ?></p>
			</div>		
			<div class="col">
			    <p><small>Rechazados</small></p>
				<p class="h4 text-primary font-weight-bold"><?php echo isset($CjsModRech)? round($CjsModRech): 0; ?></p>
			</div>
			<div class="col">
				<p><small>Salvados</small></p>
				<p class="h4 text-success font-weight-bold"><?php echo isset($CjsSalv)? round($CjsSalv): 0; ?></p>
			</div>			
			<div class="col">
			    <p><small>%Efectividad</small></p>
				<p class="h4 text-danger font-weight-bold"><?php echo isset($CjsEfect)? round($CjsEfect,1): 0; ?></p>
			</div>
			<div class="col">
			    <p><small>%Eficiencia</small></p>
				<p class="h4 text-danger font-weight-bold"><?php echo isset($CjsEfici)? round($CjsEfici,1): 0; ?></p>
			</div>			
		</div>
	</div>	
	</div>
	</div>
    <div class="row"> 
    <div class="col-sm-6 col-md-6 border">
	<div>
    <canvas id="Moduladosdiario"></canvas>
    </div>	
	</div>
	<div class="col-sm-6 col-md-6 border">
	<div>
    <canvas id="myChartAlertaSupervisor"></canvas>
    </div>
	</div>
    </div>
	<div class="row">
    <div class="col-sm-6 col-md-6 border">
	<div>
	<canvas id="myChartAlertaEmpresario"></canvas>
	</div>
	</div>
    <div class="col-sm-6 col-md-6 border">
	<div>
	<canvas id="myChartAlertaBK"> </canvas>
	</div>
	</div>
	</div>
   <div class="row">
    <div class="col-sm-6 col-md-12 border">
	<div>
	<?php 
		  $db=Db::conectar();
          $select=$db->prepare("
SELECT Codigo,Cliente,Ruta,Fecha,centro,Entrega,mr,entregado,rechazo,cjasrechazadas 
FROM t77_rs WHERE Fecha = :Fecha AND centro = :centro AND alerta = 1 
		  ");
		  $select->bindValue('centro',$idcentro);
		  $select->bindValue('Fecha',$fechaselec);		  
		  $select->execute();
		  Db::desconectar();
		  ?> 
		<br><p class="text-muted text-center">Cajas Alertadas</p> 
       <div class="table-responsive-sm">		
		<table class="table table-sm table-bordered">
		  <thead>
			<tr >
			  <th scope="col">#</th>
			  <th scope="col">Codigo</th>
			  <th scope="col">Cliente</th>
			  <th scope="col">Ruta</th>
			  <th scope="col">Motivo</th>
			  <th scope="col">Estado</th>
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
		<td><?php if($arraysic['entregado']==1) { echo 'Entregado'; } else if ($arraysic['rechazo']==1){ echo 'Rechazado'; } else { echo 'Pendiente'; } ?></td>
		<td><?php echo $arraysic['Entrega']; ?></td>
		<td><?php echo $arraysic['cjasrechazadas']; ?></td>
		</tr>
		<?php
		$i++;
		  }
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
			<?php
	$suparraysp = isset($suparraysp) ? $suparraysp : array('nobody') ;
	$cajasarraysp = isset($cajasarraysp) ? $cajasarraysp : array('nobody') ;
	$contarraysp = isset($contarraysp) ? $contarraysp : array('nobody') ;	
			?>
	<script>
			var barChartData = {
			labels: [<?php echo "'".implode("','", $suparraysp)."'"; ?>],
			datasets: [{
				label: 'Cajas',
				backgroundColor: 'rgb(192, 0, 0)',
                yAxisID: 'y-axis-1',
				data: [<?php echo implode(",", $cajasarraysp); ?>],
			}, {
				label: 'Contactos',
				 backgroundColor: 'rgb(255, 192, 0)',
				yAxisID: 'y-axis-2',
				data: [<?php echo implode(",", $contarraysp); ?>],    
			}]
		};
	new Chart(document.getElementById("myChartAlertaSupervisor"), {
				type: 'bar',
				data: barChartData,
				options: {
					responsive: true,
					title: {
						display: true,
						text: 'Alertas Por Supervisor'
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
	$empresarrayem = isset($empresarrayem) ? $empresarrayem : array('nobody') ;
	$cajasarrayem = isset($cajasarrayem) ? $cajasarrayem : array('nobody') ;
	$contarrayem = isset($contarrayem) ? $contarrayem : array('nobody') ;	
			?>
	<script>
			var barChartData = {
			labels: [<?php echo "'".implode("','", $empresarrayem)."'"; ?>],
			datasets: [{
				label: 'Cajas',
				backgroundColor: 'rgb(192, 0, 0)',
                yAxisID: 'y-axis-1',
				data: [<?php echo implode(",", $cajasarrayem); ?>],
			}, {
				label: 'Contactos',
				 backgroundColor: 'rgb(255, 192, 0)',
				yAxisID: 'y-axis-2',
				data: [<?php echo implode(",", $contarrayem); ?>],    
			}]
		};
	new Chart(document.getElementById("myChartAlertaEmpresario"), {
				type: 'bar',
				data: barChartData,
				options: {
					responsive: true,
					title: {
						display: true,
						text: 'Alertas Por Empresario'
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
	$rutasarray = isset($rutasarray) ? $rutasarray : array('nobody') ;
	$cajasarray = isset($cajasarray) ? $cajasarray : array('nobody') ;
	$contarray = isset($contarray) ? $contarray : array('nobody') ;	
			?>	
	<script>
			var barChartData = {
			labels: [<?php echo "'".implode("','", $rutasarray)."'"; ?>],
			datasets: [{
				label: 'Cajas',
				backgroundColor: 'rgb(192, 0, 0)',
                yAxisID: 'y-axis-1',
				data: [<?php echo implode(",", $cajasarray); ?>],
			}, {
				label: 'Contactos',
				 backgroundColor: 'rgb(255, 192, 0)',
				yAxisID: 'y-axis-2',
				data: [<?php echo implode(",", $contarray); ?>],    
			}]
		};
	new Chart(document.getElementById("myChartAlertaBK"), {
				type: 'bar',
				data: barChartData,
				options: {
					responsive: true,
					title: {
						display: true,
						text: 'Alertas Por Ruta'
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
	$Rechdia = isset($Rechdia) ? $Rechdia : array('nobody') ;
	$MetaRech = isset($MetaRech) ? $MetaRech : array('nobody') ;
	$CjsRech = isset($CjsRech) ? $CjsRech : array('nobody') ;	
			?>		
    <script>
new Chart(document.getElementById("Moduladosdiario"), {
    type: 'bar',
    data: {
      labels: [<?php echo "'".implode("','", $Rechdia)."'"; ?>],
      datasets: [{
          label: "Europe",
		  type: "line",
		  label: '% Eficiencia',
		  backgroundColor: 'rgb(247, 149, 29)',
          borderColor: 'rgb(255, 242, 0)',
          data: [<?php echo implode(",", $MetaRech); ?>],
          fill: false		  
	}, 
		{
         label: "Europe",
		 type: "bar",
		 label: '% Efectividad',
         backgroundColor: 'rgb(224, 33, 36)',
         data: [<?php echo implode(",", $CjsRech); ?>],
        }
      ]
    },
    options: {
		scales: {
               xAxes: [{ barPercentage: 0.7 }]
           },
      title: {
        display: true,
         text: <?php echo "'CONTACTOS : Modulacion ".$fecha_ini.' al '.$fecha_fin."'"; ?>
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
