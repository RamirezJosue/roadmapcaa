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
	require('../../bd/array/confighc.php');
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
	
		
		  $db=Db::conectar();
          $select=$db->prepare("
SELECT v.dia,SUM(v.CjsProg) AS CjsProg, SUM(v.CjsRech) AS CjsRech, IF(SUM(v.CjsProg)=0,0,(SUM(v.CjsProg)*1)/100) AS MetaRech FROM (
SELECT f.dia, IF(r.CjsRech IS NULL,0,r.CjsRech) AS CjsRech, IF(r.CjsProg IS NULL,0,r.CjsProg) AS CjsProg FROM 
(
SELECT dia,fecha FROM `fechas_det` WHERE  fecha>='$fecha_ini' AND fecha<='$fecha_fin'
) AS f LEFT JOIN 
(
SELECT Fecha, IF(rechazo=1,SUM(cjasrechazadas),0) as CjsRech, SUM(Entrega) AS CjsProg FROM `t77_rs` 
WHERE centro=:centro
AND `Fecha`>='$fecha_ini' AND `Fecha`<='$fecha_fin'  GROUP BY Fecha,rechazo
) AS r ON f.fecha = r.Fecha
) AS v GROUP BY v.dia
		  ");
		  $select->bindValue('centro',$idcentro);
		  $select->execute();
          while ($rechazodiario=$select->fetch()){ 
		  $Rechdia[] = $rechazodiario['dia'];
		  $CjsRech[] = round($rechazodiario['CjsRech'],2);
		  $MetaRech[] = round($rechazodiario['MetaRech'],2);
		  }	
		  Db::desconectar();
		  $db=Db::conectar();
          $select=$db->prepare("
SELECT c.mr,b.colorchart,SUM(c.CjsRech) AS CjsRech FROM 
			(
SELECT Fecha,mr, IF(rechazo=1,SUM(cjasrechazadas),0) as CjsRech, SUM(Entrega) AS CjsProg FROM `t77_rs` 
WHERE centro=:centro 
AND `Fecha`>='$fecha_ini' AND `Fecha`<='$fecha_fin'  GROUP BY Fecha,rechazo,mr
			) AS c 
			LEFT JOIN 
			(SELECT * FROM `t77_mr`) as b 
			ON c.mr = b.descripcion WHERE  CjsRech <> 0 GROUP BY  c.mr,b.colorchart ORDER BY  CjsRech ASC
		  ");
		  $select->bindValue('centro',$idcentro);
		  $select->execute();
          while ($rechazomotivo=$select->fetch()){ 
		  $mr[] = $rechazomotivo['mr'];
		  $colorchart[] = $rechazomotivo['colorchart'];
		  $CjsRechMR[] = round($rechazomotivo['CjsRech'],2);
		  }
		  Db::desconectar();
?>
	<div class="row border">
    <div class="col-sm-12 bg-danger">
	<div class="d-flex justify-content-center">
	<div class="p-2 bd-highlight"><div class="text-white text-md-center font-weight-bolder">DASHBOARD DE RECHAZOS - VENTAS</div></div>
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
	<form  class="w3-container" method="post" action="dashboardventas" >
	<div class="input-group">
	<input  aria-label="First name" id="fechasrsini" class="form-control" value="<?php echo $fecha_ini; ?>" placeholder="Fecha inicio" type="date" name="fecha_ini">
	<input  aria-label="Last name" id="fechasrsfin" class="form-control" value="<?php echo $fecha_fin; ?>" placeholder="Fecha fin" type="date" name="fecha_fin">
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
    <div class="col-sm-7 col-md-7 border">
	<div class="table-responsive-sm">
	<div>
	<canvas id="RechazoDiario" style="max-width:750px"></canvas>
	</div>
	</div>
	</div>
    <div class="col-sm-5 col-md-5 border">
	<div>
	<canvas id="Motivosdeechazos" width="800" height="450"></canvas>
	</div>
	</div>
	</div>
	<div class="row border">
    <div class="col-sm-12 bg-danger">
	<div class="d-flex justify-content-center">
	<div class="p-2 bd-highlight"><div class="text-white text-md-center font-weight-bolder">Detalle por supervisor/agente/cliente</div></div>
	<div class="p-2 bd-highlight"><button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#myModalFecha_d">Fecha</button></div>
	</div>
	</div>
	</div>
   <!-- Modal Inicio-->
	<div class="modal fade" id="myModalFecha_d" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
	<form  class="w3-container" method="post" action="dashboardventas" >
	<div class="input-group">
	<input  aria-label="First name" id="fechasrsini" class="form-control" value="<?php echo $fecha_ini_d; ?>" placeholder="Fecha inicio" type="date" name="fecha_ini_d">
	<input  aria-label="Last name" id="fechasrsfin" class="form-control" value="<?php echo $fecha_fin_d; ?>" placeholder="Fecha fin" type="date" name="fecha_fin_d">
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
	<br>
    <div class="row">
    <div class="col-sm-12 col-md-12 border">
	<div class="p-1 mb-1 bg-light text-dark">Datos del <?php echo $fecha_fin.' al '.$fecha_fin; ?></div>
	<div class="table-responsive-sm">
<table id="example_supervisor" class="display compact cell-border" data-order='[[ 0, "ASC" ]]' >
    <thead>
	<tr  style="font-size: 13px;" >
	<th>Supervisor</th>
	<th>Programado</th>
	<th>Rechazado</th>
	<th>Total</th>
	<th>MR 1%</th>
	<th>%Avance</th>
	</tr>
    </thead>
<tbody>
<?php 
		  $db=Db::conectar();
          $selects=$db->prepare("
SELECT 
x.supervisor,
SUM(x.CjsProg) AS CjsProg, 
SUM(x.CjsRech) AS CjsRech,
0 AS CjsMod, 
(SUM(x.CjsProg)-SUM(x.CjsRech)) AS Total,
1 AS MetaRech,
(((SUM(x.CjsProg)/(SUM(x.CjsProg)-SUM(x.CjsRech)))-1)*100) AS PorcRech 
FROM 
(
SELECT
rs.Fecha,
mc.supervisor,
mc.agente,
sum(rs.CjsRech) as CjsRech,
sum(rs.CjsProg) as CjsProg,
sum(rs.CjsMod) as CjsMod
FROM(
SELECT Fecha, IF(rechazo=1,cjasrechazadas,0) as CjsRech, 0 AS CjsMod,Entrega AS CjsProg, Codigo FROM `t77_rs` 
WHERE centro=:centro
AND `Fecha`>='$fecha_ini' AND `Fecha`<='$fecha_fin'
    ) AS rs LEFT JOIN (
SELECT `codcli`, `supervisor`, `agente` FROM `t77_mc` WHERE `centro`=:centro
    ) AS mc ON rs.Codigo = mc.codcli
GROUP BY rs.Fecha,mc.supervisor,mc.agente
) AS x GROUP BY supervisor ORDER BY x.CjsRech DESC
		  ");
		  $selects->bindValue('centro',$idcentro);
		  $selects->execute();
		  $col_count = $selects->columnCount();
          while ($rowsup=$selects->fetch()) {
			  IF($rowsup['PorcRech'] > 1) { $class = 'class="table-danger"'; } else { $class =  ''; } 
?>
<tr style="font-size: 13px;" <?php echo $class; ?> >
<td><?php echo $rowsup['supervisor']; ?></td>
<td class="ColTd1A"><?php echo $rowsup['CjsProg']; ?></td>
<td class="ColTd1B"><?php echo $rowsup['CjsRech']; ?></td>
<td class="ColTd1C"><?php echo $rowsup['Total']; ?></td>
<td class="ColTd1D"><?php echo round ($rowsup['MetaRech'],2); ?></td>
<td class="ColTd1E"><?php echo round ($rowsup['PorcRech'],2); ?></td>
</tr>
<?php    
		  }
		  Db::desconectar();
?>
</tbody>
	<tfoot>
	<tr class="bg-danger text-light text-center" style="font-size: 13px;">
	<td>Total</td>
	 <td class="TotalTd1 A"></td>
	 <td class="TotalTd1 B"></td>
	 <td class="TotalTd1 C"></td>
	 <td class="TotalTd1 D"></td>
	 <td class="TotalTd1 E"></td>
	</tr>	
	</tfoot>
</table>
</div>
	</div>
	</div>
	<br>
    <div class="row">
    <div class="col-sm-12 col-md-12 border">
<div class="p-1 mb-1 bg-light text-dark">Datos del <?php echo $fecha_fin.' al '.$fecha_fin; ?></div>
	<div class="table-responsive-sm">
<table id="example_agente" class="display compact cell-border" data-order='[[ 0, "ASC" ]]' >
    <thead>
	<tr  style="font-size: 13px;">
	<th>Agente</th>
	<th>Programado</th>
	<th>Rechazado</th>
	<th>Total</th>
	<th>MR 1%</th>
	<th>%Avance</th>
	</tr>
  </thead>
<tbody>
<?php 
		  $db=Db::conectar();
          $selecta=$db->prepare("
SELECT 
x.agente,
SUM(x.CjsProg) AS CjsProg, 
SUM(x.CjsRech) AS CjsRech,
0 AS CjsMod, 
(SUM(x.CjsProg)-SUM(x.CjsRech)) AS Total,
1 AS MetaRech,
(((SUM(x.CjsProg)/(SUM(x.CjsProg)-SUM(x.CjsRech)))-1)*100) AS PorcRech 
FROM 
(
SELECT
rs.Fecha,
mc.supervisor,
mc.agente,
sum(rs.CjsRech) as CjsRech,
sum(rs.CjsProg) as CjsProg
FROM(
SELECT Fecha, IF(rechazo=1,cjasrechazadas,0) as CjsRech, Entrega AS CjsProg, Codigo FROM `t77_rs` 
WHERE centro=:centro
AND `Fecha`>='$fecha_ini' AND `Fecha`<='$fecha_fin'
    ) AS rs LEFT JOIN (
SELECT `codcli`, `supervisor`, `agente` FROM `t77_mc` WHERE `centro`=:centro
    ) AS mc ON rs.Codigo = mc.codcli
GROUP BY rs.Fecha,mc.supervisor,mc.agente
) AS x GROUP BY x.agente ORDER BY x.CjsRech DESC
		  ");
		  $selecta->bindValue('centro',$idcentro);
		  $selecta->execute();
          while ($rowagente=$selecta->fetch()) { 
			  IF($rowagente['PorcRech'] > 1) { $class = 'class="table-danger"'; } else { $class =  ''; } 
?>
<tr style="font-size: 13px;" <?php echo $class; ?> >
<td><?php echo $rowagente['agente']; ?></td>
<td><?php echo $rowagente['CjsProg']; ?></td>
<td><?php echo $rowagente['CjsRech']; ?></td>
<td><?php echo $rowagente['Total']; ?></td>
<td><?php echo round ($rowagente['MetaRech'],2); ?></td>
<td><?php echo round ($rowagente['PorcRech'],2); ?></td>
</tr>
<?php
		  }
		  Db::desconectar();
?>
</tbody>
</table>
</div>
	</div>
	</div>
	<br>
   <div class="row">
    <div class="col-sm-12 col-md-12 border">
<div class="p-1 mb-1 bg-light text-dark">Datos del <?php echo $fecha_fin.' al '.$fecha_fin; ?></div>
	<div class="table-responsive-sm">			
	 <table id="example_cliente"  data-order='[[ 3, "desc" ]]' data-page-length='30'
          class="display compact cell-border">
	<thead>
	<tr  style="font-size: 13px;">
		    <th>Ruta</th>
			<th>Cliente</th>
			<th>Motivo</th>
			<th>Cajas</th>
			<th>Rechz</th>
			<th>H.ini</th>
			<th>H.fin</th>
			<th>Minutos</th>
			<th>Supervisor</th>
	</tr>
	</thead>
	<tbody>	
         <?php
		  $db=Db::conectar();
          $select=$db->prepare("SELECT fechahoraalerta,registrofin,Ruta,Codigo,Cliente,mr,Entrega,cjasrechazadas,rechazo,entregado FROM t77_rs WHERE centro=:centro AND rechazo=1 AND `Fecha`>='$fecha_ini' AND `Fecha`<='$fecha_fin'");
		  $select->bindValue('centro',$idcentro);
		  $select->execute();
          while ($registro=$select->fetch()) {
		$fechahora_ini = $registro['fechahoraalerta'];
		if($registro['registrofin']=='0000-00-00 00:00:00'){
                $fechahora_fintb='0000-00-00 00:00:00';			
				$fechahora_fin=$fecha_hora;		
		}else { $fechahora_fin = $registro['registrofin']; $fechahora_fintb = $registro['registrofin']; }
		?>    
	    <tr style="font-size: 13px;">
		<td><?php echo $registro['Ruta']; ?></td>
		<td><?php echo $registro['Codigo'].' '.$registro['Cliente']; ?></td>
		<td><?php echo $registro['mr']; ?></td>
		<td><?php echo $registro['Entrega']; ?></td>
		<td><?php echo $registro['cjasrechazadas']; ?></td>
		<td><?php echo substr($fechahora_ini,11,8); ?></td>
		<td><?php echo substr($fechahora_fintb,11,8); ?></td>
		<td><?php echo $crud->minutosTranscurridos($fechahora_ini,$fechahora_fin); ?></td>
		<td><?php echo $crud->sacarmc($registro['Codigo']); ?></td>
		</tr>
		<?php
		}
		Db::desconectar();
		?>
	</tbody>
	</table>
     </div>	
	    </div>	
		   </div>
<br>		   
   </main>
		<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
		<script>window.jQuery || document.write('<script src="../assets/js/vendor/jquery.slim.min.js"><\/script>')</script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.9.0/feather.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script>  
		    <!--datatables-->
  <link rel="stylesheet" type="text/css" href=" https://cdn.datatables.net/1.10.23/css/jquery.dataTables.min.css" />
  <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.18/datatables.min.js"></script>
  <script>
$('#example_supervisor').DataTable( {
    responsive: true,
	 "searching": false,
        "paging":   false,
        "info":     false,
		"autoWidth": true
} );
$('#example_agente').DataTable( {
    responsive: true,
	 "searching": false,
        "paging":   false,
        "info":     false,
		"autoWidth": true
} );
$('#example_cliente').DataTable( {
    responsive: true,
	 "searching": false,
        "paging":   false,
        "info":     false,
		"autoWidth": true
} );
  </script>
		<!--datatables-->
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
	<script>
new Chart(document.getElementById("RechazoDiario"), {
    type: 'bar',
    data: {
      labels: [<?php echo "'".implode("','", $Rechdia)."'"; ?>],
      datasets: [{
          label: "Europe",
		  type: "line",
		  label: 'Meta 1% cajas',
		  backgroundColor: 'rgb(247, 149, 29)',
          borderColor: 'rgb(255, 242, 0)',
          data: [<?php echo implode(",", $MetaRech); ?>],
          fill: false		  
	}, 
		{
         label: "Europe",
		 type: "bar",
		 label: 'Cajas rechazadas',
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
         text: <?php echo "'Rechazos del ".$fecha_ini.' al '.$fecha_fin."'"; ?>
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
	new Chart(document.getElementById("Motivosdeechazos"), {
    type: 'horizontalBar',
    data: {
      labels: [<?php echo "'".implode("','", $mr)."'"; ?>],
      datasets: [
        {
          label: "Motivos de rechazos",
          backgroundColor: [<?php echo "'".implode("','", $colorchart)."'"; ?>],
          data: [<?php echo implode(",", $CjsRechMR); ?>]
        }
      ]
    },
    options: {
		responsive: true,
      legend: { display: false },
      title: {
        display: true,
        text: <?php echo "'Motivos de rechazos del ".$fecha_ini.' al '.$fecha_fin."'"; ?>
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