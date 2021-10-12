<?php 
	session_start();
	if (!isset($_COOKIE['usuarioid'])) {
		header('Location: index.php');
	}
	 $_SESSION['usuarioid']=$_COOKIE['usuarioid'];
	date_default_timezone_set("America/Lima");
    $fecha_hora = date("Y-m-d H:i:s",$time = time());
    $fecha = date("Y-m-d",$time = time());
    $fechars = date("Y-m-d",$time = time());	
	$aid = $_COOKIE['usuarioid'];
	require('conexion.php');
	require('crud_usuario.php');
	$crud=new CrudUsuario();
	$idcentro=$crud->sacarcentro($aid);
	    if (($crud-> contardbuser('id','usuarios','dni = "'.$aid.'" AND centro = "'.$idcentro.'" AND estado = 1'))==0){
    echo "Usuario bloqueado ".$accesos.'-'.$aid;
	echo '<br><a  href="logout.php">Sign out</a>';
	}else{
	if (($crud-> contardbuser('id','usuarios','dni = "'.$aid.'" AND centro = "'.$idcentro.'" AND estado = "1" AND tipo = "1"'))==0) {
    $aid_super = 0; } else { $aid_super = 1; }
	require('head.php');
	if(isset($_POST['fechaselec'])){ 
	$fechaselec = $_POST['fechaselec'];	
	} else { 
	$fechaselec = $fechars; 
	}
		  $db=Db::conectar();
          $select=$db->prepare("
SELECT v.dia,SUM(v.CjsProg) AS CjsProg, SUM(v.CjsRech) AS CjsRech, IF(SUM(v.CjsProg)=0,0,(SUM(v.CjsProg)*1)/100) AS MetaRech FROM (
SELECT f.dia, IF(r.CjsRech IS NULL,0,r.CjsRech) AS CjsRech, IF(r.CjsProg IS NULL,0,r.CjsProg) AS CjsProg FROM 
(
SELECT dia,fecha FROM `fechas_det` WHERE `mesanio`='03-2021'
) AS f LEFT JOIN 
(
SELECT Fecha, IF(rechazo=1,SUM(Entrega),0) as CjsRech, SUM(Entrega) AS CjsProg FROM `t77_rs` 
WHERE centro='BK77' 
AND `Fecha`>='2021-03-01' AND `Fecha`<='2021-03-31'  GROUP BY Fecha,rechazo
) AS r ON f.fecha = r.Fecha
) AS v GROUP BY v.dia
		  ");
		  $select->bindValue('centro',$idcentro);
		  $select->bindValue('Fecha',$fechaselec);
		  $select->execute();
          while ($rechazodiario=$select->fetch()) { 
		  $Rechdia[] = $rechazodiario['dia'];
		  $CjsRech[] = round($rechazodiario['CjsRech'],2);
		  $MetaRech[] = round($rechazodiario['MetaRech'],2);
		  }	
		  $db=Db::conectar();
          $select=$db->prepare("
SELECT c.mr,b.colorchart,SUM(c.CjsRech) AS CjsRech FROM 
			(
SELECT Fecha,mr, IF(rechazo=1,SUM(Entrega),0) as CjsRech, SUM(Entrega) AS CjsProg FROM `t77_rs` 
WHERE centro='BK77' 
AND `Fecha`>='2021-03-01' AND `Fecha`<='2021-03-31'  GROUP BY Fecha,rechazo,mr
			) AS c 
			LEFT JOIN 
			(SELECT * FROM `t77_mr`) as b 
			ON c.mr = b.descripcion WHERE  CjsRech <> 0 GROUP BY  c.mr,b.colorchart ORDER BY  CjsRech ASC
		  ");
		  $select->bindValue('centro',$idcentro);
		  $select->bindValue('Fecha',$fechaselec);
		  $select->execute();
          while ($rechazomotivo=$select->fetch()){ 
		  $mr[] = $rechazomotivo['mr'];
		  $colorchart[] = $rechazomotivo['colorchart'];
		  $CjsRechMR[] = round($rechazomotivo['CjsRech'],2);
		  }			  
	
	
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
	<form method="post" action="dashboard.php">
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
    <div class="row">
    <div class="col-sm-8 col-md-8 border">
	
	<br>
	<div class="table-responsive-sm">
<table class="table table-sm table-bordered" >
  <thead>
    <tr class="bg-danger text-light text-center">
<th rowspan="2" scope="col" >Supervisor de ventas</th>
<th colspan="3" scope="col" >Cajas</th>
<th rowspan="2" scope="col" >Total</th>
<th rowspan="2" scope="col" >Meta MR 1%</th>
<th rowspan="2" scope="col" >% Avance</th>
</tr>
<tr class="bg-danger text-light text-center">
<th scope="col">Total prog.</th>
<th scope="col">Rechazado</th>
<th scope="col">Modificado</th>
  </tr>
  </thead>
<tbody>
<?php 
		  $db=Db::conectar();
          $selects=$db->prepare("
SELECT x.supervisor,SUM(x.CjsProg) AS CjsProg, SUM(x.CjsRech) AS CjsRech, IF(SUM(x.CjsProg)=0,0,(SUM(x.CjsProg)*1)/100) AS MetaRech,(((SUM(x.CjsProg)/(SUM(x.CjsProg)-SUM(x.CjsRech)))-1)*100) AS PorcRech FROM 
(
SELECT
rs.Fecha,
mc.supervisor,
mc.agente,
sum(rs.CjsRech) as CjsRech,
sum(rs.CjsProg) as CjsProg
FROM(
SELECT Fecha, IF(rechazo=1,Entrega,0) as CjsRech, Entrega AS CjsProg, Codigo FROM `t77_rs` 
WHERE centro='BK77' 
AND `Fecha`>='2021-03-06' AND `Fecha`<='2021-03-06'
    ) AS rs LEFT JOIN (
SELECT `codcli`, `supervisor`, `agente` FROM `t77_mc` WHERE `centro`='BK77'
    ) AS mc ON rs.Codigo = mc.codcli
GROUP BY rs.Fecha,mc.supervisor,mc.agente
) AS x GROUP BY supervisor ORDER BY x.CjsRech DESC
		  ");
		  $selects->execute();
          while ($rowsup=$selects->fetch()) { 
?>
<tr>
<td><?php echo $rowsup['supervisor']; ?></td>
<td><?php echo number_format($rowsup['CjsProg'], 2, '.', ','); ?></td>
<td><?php echo number_format($rowsup['CjsRech'], 2, '.', ','); ?></td>
<td></td>
<td><?php echo number_format(($rowsup['CjsProg']-$rowsup['CjsRech']), 2, '.', ','); ?></td>
<td><?php echo number_format($rowsup['MetaRech'], 2, '.', ','); ?></td>
<td><?php echo number_format($rowsup['PorcRech'], 2, '.', ','); ?> %</td>
</tr>
<?php 
		  }
?>
</tbody>
</table>
</div>
	</div>
    <div class="col-sm-4 col-md-4 border">
	</div>
	</div>
    <div class="row">
    <div class="col-sm-8 col-md-8 border">
	
	<br>
	<div class="table-responsive-sm">
<table class="table table-sm table-bordered" >
  <thead>
    <tr class="bg-danger text-light text-center">
<th rowspan="2" scope="col" >Supervisor de ventas</th>
<th colspan="3" scope="col" >Cajas</th>
<th rowspan="2" scope="col" >Total</th>
<th rowspan="2" scope="col" >Meta MR 1%</th>
<th rowspan="2" scope="col" >% Avance</th>
</tr>
<tr class="bg-danger text-light text-center">
<th scope="col">Total prog.</th>
<th scope="col">Rechazado</th>
<th scope="col">Modificado</th>
  </tr>
  </thead>
<tbody>
<?php 
		  $db=Db::conectar();
          $selecta=$db->prepare("
SELECT x.agente,SUM(x.CjsProg) AS CjsProg, SUM(x.CjsRech) AS CjsRech, IF(SUM(x.CjsProg)=0,0,(SUM(x.CjsProg)*1)/100) AS MetaRech,(((SUM(x.CjsProg)/(SUM(x.CjsProg)-SUM(x.CjsRech)))-1)*100) AS PorcRech FROM 
(
SELECT
rs.Fecha,
mc.supervisor,
mc.agente,
sum(rs.CjsRech) as CjsRech,
sum(rs.CjsProg) as CjsProg
FROM(
SELECT Fecha, IF(rechazo=1,Entrega,0) as CjsRech, Entrega AS CjsProg, Codigo FROM `t77_rs` 
WHERE centro='BK77' 
AND `Fecha`>='2021-03-06' AND `Fecha`<='2021-03-06'
    ) AS rs LEFT JOIN (
SELECT `codcli`, `supervisor`, `agente` FROM `t77_mc` WHERE `centro`='BK77'
    ) AS mc ON rs.Codigo = mc.codcli
GROUP BY rs.Fecha,mc.supervisor,mc.agente
) AS x GROUP BY agente ORDER BY x.CjsRech DESC
		  ");
		  $selecta->execute();
          while ($rowagente=$selecta->fetch()) { 
?>
<tr>
<td><?php echo $rowagente['agente']; ?></td>
<td><?php echo number_format($rowagente['CjsProg'], 2, '.', ','); ?></td>
<td><?php echo number_format($rowagente['CjsRech'], 2, '.', ','); ?></td>
<td></td>
<td><?php echo number_format(($rowagente['CjsProg']-$rowagente['CjsRech']), 2, '.', ','); ?></td>
<td><?php echo number_format($rowagente['MetaRech'], 2, '.', ','); ?></td>
<td><?php echo number_format($rowagente['PorcRech'], 2, '.', ','); ?> %</td>
</tr>
<?php 
		  }
?>
</tbody>
</table>
</div>
	</div>
    <div class="col-sm-4 col-md-4 border">
	</div>
	</div>	
   </main>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
      <script>window.jQuery || document.write('<script src="../assets/js/vendor/jquery.slim.min.js"><\/script>')</script><script src="js/bootstrap.bundle.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.9.0/feather.min.js"></script>
          <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script>
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
         text: 'Rechazos diario'
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
        text: 'Motivos de rechazos acululado'
      }
    }
});	
	    </script>			
<?php 	} ?>
</body>
</html>
