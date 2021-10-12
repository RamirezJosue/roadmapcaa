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
	$today = date($fechars);
	$yesterday = strtotime('-1 day', strtotime($today));
	$yesterday = date ('Y-m-d', $yesterday);
	$fechaselec = $yesterday; 
	$fecha = $yesterday;
	endif;
	$fecha_ini = date_format(date_create($fechaselec),"Y-m-01");
	$fecha_fin = date_format(date_create($fechaselec),"Y-m-30");
	////
	if(isset($_GET['mes'])){
	if($_GET['mes']=='2021-01'){
	$fecha_ini='2021-01-01'; $fecha_fin='2021-01-31'; $messelct='2021-01'; 	
	}else if($_GET['mes']=='2021-02'){
	$fecha_ini='2021-02-01'; $fecha_fin='2021-02-28'; $messelct='2021-02';
	}else if($_GET['mes']=='2021-03'){
	$fecha_ini='2021-03-01'; $fecha_fin='2021-03-31'; $messelct='2021-03';	
	}else if($_GET['mes']=='2021-04'){
	$fecha_ini='2021-04-01'; $fecha_fin='2021-04-30'; $messelct='2021-04';	
	}else if($_GET['mes']=='2021-05'){
	$fecha_ini='2021-05-01'; $fecha_fin='2021-05-31'; $messelct='2021-05';	
	}else if($_GET['mes']=='2021-04'){
	$fecha_ini='2021-06-01'; $fecha_fin='2021-06-30'; $messelct='2021-06';	
	}else if($_GET['mes']=='2021-07'){
	$fecha_ini='2021-07-01'; $fecha_fin='2021-07-31'; $messelct='2021-07';	
	}else if($_GET['mes']=='2021-08'){
	$fecha_ini='2021-08-01'; $fecha_fin='2021-08-31'; $messelct='2021-08';	
	}else if($_GET['mes']=='2021-09'){
	$fecha_ini='2021-09-01'; $fecha_fin='2021-09-30'; $messelct='2021-09';	
	}else if($_GET['mes']=='2021-10'){
	$fecha_ini='2021-10-01'; $fecha_fin='2021-10-31'; $messelct='2021-10';	
	}else if($_GET['mes']=='2021-11'){
	$fecha_ini='2021-11-01'; $fecha_fin='2021-11-30'; $messelct='2021-11';	
	}else if($_GET['mes']=='2021-12'){
	$fecha_ini='2021-12-01'; $fecha_fin='2021-12-31'; $messelct='2021-12';	
	}else if($_GET['mes']=='2021-12'){
	$fecha_ini = date("Y-01-01",$time = time()); $fecha_fin = date("Y-m-d",$time = time()); $messelct='2021-12';	
	}
	} else { 
	$fecha_ini = date("Y-01-01",$time = time());
	$fecha_fin = date("Y-m-d",$time = time());
	}	
$sql_mes="
SELECT * FROM (
    SELECT `mesanio`,`mesl` FROM `fechas_det` WHERE `anio`='2021' GROUP BY `mesanio`
) AS c LEFT JOIN 
(
SELECT
85 as meta,    
a.tema,
a.id_tema,
a.mes,
sum(a.countcheck) as countpreguntas,
sum(a.respuesta_usuario) as respuesta_usuario,
sum(a.countcheck)*3 as countcheck,
round (sum(a.respuesta_usuario)/(sum(a.countcheck)*3)*100,2) as resultado
FROM 
(
SELECT
1 as countcheck, 
`id`, 
`id_tema`, 
`id_grupo_preguntas`,
`tipo_pregunta`, 
`id_respueta`,
`desc_resp`,
`respuesta_user`, 
`user_registro`, 
`grupo`, 
`fecha`, 
`empresa`, 
`fecha_registro`, 
DATE_FORMAT(`fecha_registro`,'%m-%Y') as mes, 
`bk`, 
`puesto_trabajo`, 
`codigo_cliente`, 
`area_oficina`, 
`area_almacen`, 
`usuario_hc`, 
`flota`, 
`txt_actions`, 
`id_pregunta_grupo`, 
`txt_comentario`,
(SELECT descripcion FROM exa_temas WHERE id=`id_tema`) as tema,
(SELECT descripcion FROM exa_grupo_preguntas WHERE id=`id_grupo_preguntas`) as grupo_preguntas,
(SELECT descripcion FROM exa_preguntas WHERE id=`id_preguntas`) as preguntas,
(CASE
    WHEN (SELECT respuestas FROM exa_respuesta WHERE id=`id_respueta`) = '0 = No ok. Hay GAPs' THEN 0
    WHEN (SELECT respuestas FROM exa_respuesta WHERE id=`id_respueta`) = '1 = Necesita acciones para mejorar' THEN 1
	WHEN (SELECT respuestas FROM exa_respuesta WHERE id=`id_respueta`) = '3 = Ok.' THEN 3
    ELSE ''
END) AS respuesta_usuario
FROM `exa_detalle_checklist` 
WHERE id_tema=854630120 AND centro=:centro AND `st`=1 AND `respuesta_user`<>'' AND id_grupo_preguntas <> 56
) AS a group by 
a.tema,
a.id_tema,
a.mes
) AS d ON c.mesanio = d.mes
	";	  $db=Db::conectar();
          $selectmes=$db->prepare($sql_mes);
		  $selectmes->bindValue('centro',$idcentro);
		  //$select->bindValue('Fecha',$fechaselec);
		  $selectmes->execute();
          while ($rowmes=$selectmes->fetch()) {
		  $mes[] = substr($rowmes['mesl'],0,3);
		  $resultadomes[] = round($rowmes['resultado'],1);
		  $metames[] = $rowmes['meta'];
		  }
	Db::desconectar();
$sql_placa="
SELECT * FROM (
SELECT 
85 as metaplaca,
a.tema,
a.id_tema,
a.flota,
sum(a.countcheck) as countpreguntas,
sum(a.respuesta_usuario) as respuesta_usuario,
sum(a.countcheck)*3 as countcheck,
round (sum(a.respuesta_usuario)/(sum(a.countcheck)*3)*100,2) as resultado
FROM 
(
SELECT
1 as countcheck, 
`id`, 
`id_tema`, 
`id_grupo_preguntas`,
`tipo_pregunta`, 
`id_respueta`,
`desc_resp`,
`respuesta_user`, 
`user_registro`, 
`grupo`, 
`fecha`, 
`empresa`, 
`fecha_registro`, 
`bk`, 
`puesto_trabajo`, 
`codigo_cliente`, 
`area_oficina`, 
`area_almacen`, 
`usuario_hc`, 
`flota`, 
`txt_actions`, 
`id_pregunta_grupo`, 
`txt_comentario`,
(SELECT descripcion FROM exa_temas WHERE id=`id_tema`) as tema,
(SELECT descripcion FROM exa_grupo_preguntas WHERE id=`id_grupo_preguntas`) as grupo_preguntas,
(SELECT descripcion FROM exa_preguntas WHERE id=`id_preguntas`) as preguntas,
(CASE
    WHEN (SELECT respuestas FROM exa_respuesta WHERE id=`id_respueta`) = '0 = No ok. Hay GAPs' THEN 0
    WHEN (SELECT respuestas FROM exa_respuesta WHERE id=`id_respueta`) = '1 = Necesita acciones para mejorar' THEN 1
	WHEN (SELECT respuestas FROM exa_respuesta WHERE id=`id_respueta`) = '3 = Ok.' THEN 3
    ELSE ''
END) AS respuesta_usuario
FROM `exa_detalle_checklist` 
WHERE id_tema=854630120 AND centro=:centro AND `st`=1 AND `respuesta_user`<>'' AND id_grupo_preguntas <> 56 AND fecha_registro >= '$fecha_ini' AND fecha_registro <= '$fecha_fin'
) AS a group by 
a.tema,
a.id_tema,
a.flota  ) AS z ORDER BY z.resultado DESC
";	  $db=Db::conectar();
          $selectplaca=$db->prepare($sql_placa);
		  $selectplaca->bindValue('centro',$idcentro);
		  //$select->bindValue('Fecha',$fechaselec);
		  $selectplaca->execute();
          while ($rowplaca=$selectplaca->fetch()) {
		  $flota[] = $rowplaca['flota'];
		  $resultadoplaca[] = round($rowplaca['resultado']);
		  $metaplaca[] = $rowplaca['metaplaca'];
		  }
		  Db::desconectar();
$sql_por5S="
SELECT 
a.tema,
a.id_tema,
a.id_grupo_preguntas,
a.grupo_preguntas,
sum(a.countcheck) as countpreguntas,
sum(a.respuesta_usuario) as respuesta_usuario,
sum(a.countcheck)*3 as countcheck,
round (sum(a.respuesta_usuario)/(sum(a.countcheck)*3)*100,2) as resultado
FROM 
(
SELECT
1 as countcheck, 
`id`, 
`id_tema`, 
`id_grupo_preguntas`,
`tipo_pregunta`, 
`id_respueta`,
`desc_resp`,
`respuesta_user`, 
`user_registro`, 
`grupo`, 
`fecha`, 
`empresa`, 
`fecha_registro`, 
`bk`, 
`puesto_trabajo`, 
`codigo_cliente`, 
`area_oficina`, 
`area_almacen`, 
`usuario_hc`, 
`flota`, 
`txt_actions`, 
`id_pregunta_grupo`, 
`txt_comentario`,
(SELECT descripcion FROM exa_temas WHERE id=`id_tema`) as tema,
(SELECT descripcion FROM exa_grupo_preguntas WHERE id=`id_grupo_preguntas`) as grupo_preguntas,
(SELECT descripcion FROM exa_preguntas WHERE id=`id_preguntas`) as preguntas,
(CASE
    WHEN (SELECT respuestas FROM exa_respuesta WHERE id=`id_respueta`) = '0 = No ok. Hay GAPs' THEN 0
    WHEN (SELECT respuestas FROM exa_respuesta WHERE id=`id_respueta`) = '1 = Necesita acciones para mejorar' THEN 1
	WHEN (SELECT respuestas FROM exa_respuesta WHERE id=`id_respueta`) = '3 = Ok.' THEN 3
    ELSE ''
END) AS respuesta_usuario
FROM `exa_detalle_checklist` 
WHERE id_tema=854630120 AND centro=:centro AND `st`=1 AND `respuesta_user`<>'' AND id_grupo_preguntas <> 56 AND fecha_registro >= '$fecha_ini' AND fecha_registro <= '$fecha_fin'
) AS a group by 
a.tema,
a.id_tema,
a.id_grupo_preguntas,
a.grupo_preguntas
ORDER BY a.id_grupo_preguntas  ASC
";	  $db=Db::conectar();
          $select5s=$db->prepare($sql_por5S);
		  $select5s->bindValue('centro',$idcentro);
		  //$select->bindValue('Fecha',$fechaselec);
		  $select5s->execute();
          while ($row5s=$select5s->fetch()) {
		  $grupopreguntas[] = $row5s['grupo_preguntas'];
		  $resultado5s[] = $row5s['resultado'];
		  }
	Db::desconectar();	  
$sql_5Sempresa="
SELECT 
85 as meta,
a.tema,
a.id_tema,
b.transportista,
sum(a.countcheck) as countpreguntas,
sum(a.respuesta_usuario) as respuesta_usuario,
sum(a.countcheck)*3 as countcheck,
round (sum(a.respuesta_usuario)/(sum(a.countcheck)*3)*100,2) as resultado
FROM 
(
SELECT
1 as countcheck, 
`id`, 
`id_tema`, 
`id_grupo_preguntas`,
`tipo_pregunta`, 
`id_respueta`,
`desc_resp`,
`respuesta_user`, 
`user_registro`, 
`grupo`, 
`fecha`, 
`empresa`, 
 DATE_FORMAT(`fecha_registro`,'%m-%Y') as mes,
`fecha_registro`, 
`bk`, 
`puesto_trabajo`, 
`codigo_cliente`, 
`area_oficina`, 
`area_almacen`, 
`usuario_hc`, 
`flota`, 
`txt_actions`, 
`id_pregunta_grupo`, 
`txt_comentario`,
(SELECT descripcion FROM exa_temas WHERE id=`id_tema`) as tema,
(SELECT descripcion FROM exa_grupo_preguntas WHERE id=`id_grupo_preguntas`) as grupo_preguntas,
(SELECT descripcion FROM exa_preguntas WHERE id=`id_preguntas`) as preguntas,
(CASE
    WHEN (SELECT respuestas FROM exa_respuesta WHERE id=`id_respueta`) = '0 = No ok. Hay GAPs' THEN 0
    WHEN (SELECT respuestas FROM exa_respuesta WHERE id=`id_respueta`) = '1 = Necesita acciones para mejorar' THEN 1
	WHEN (SELECT respuestas FROM exa_respuesta WHERE id=`id_respueta`) = '3 = Ok.' THEN 3
    ELSE ''
END) AS respuesta_usuario
FROM `exa_detalle_checklist` 
WHERE id_tema=854630120 AND centro=:centro AND `st`=1 AND `respuesta_user`<>'' AND id_grupo_preguntas <> 56 AND fecha_registro >= '$fecha_ini' AND fecha_registro <= '$fecha_fin'
) AS a  LEFT JOIN (
SELECT `placa`,`transportista` FROM `t77_vehiculos` WHERE `centro`=:centro
) AS b ON a.`flota` = b.`placa`
group by 
a.tema,
a.id_tema,
b.transportista 
";	  $db=Db::conectar();
          $select5smes=$db->prepare($sql_5Sempresa);
		  $select5smes->bindValue('centro',$idcentro);
		  //$select->bindValue('Fecha',$fechaselec);
		  $select5smes->execute();
          while ($row5smes=$select5smes->fetch()) {
		  $EmpresaResult[] = $row5smes['resultado'];
		  $Empresa[] = $row5smes['transportista'];
		  $EmpresaMeta[] = $row5smes['meta'];
		  }
	Db::desconectar();		
?>
	<div class="row border">
    <div class="col-sm-12 bg-danger">
	<div class="d-flex justify-content-center">
	<div class="p-2 bd-highlight"><div class="text-white text-md-center font-weight-bolder">5s flota <?php echo $idcentro.' | '.$fecha_ini.' al '.$fecha_fin; ?></div></div>
	<div class="p-2 bd-highlight"><button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#myModalFecha">Mes</button></div>
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
	<form method="GET" >
	<div class="form-row">		 
	 <select class="form-control" name="mes" id="exampleFormControlSelect1">
	  <option value="" selected disabled ><--Todos los meses--></option>
      <option value="2021-01">Enero 2021</option>
      <option value="2021-02">Febrero 2021</option>
      <option value="2021-03">Marzo 2021</option>
      <option value="2021-04">Abril 2021</option>
      <option value="2021-05">Mayo 2021</option>
	  <option value="2021-06">Junio 2021</option>
	  <option value="2021-07">Julio 2021</option>
	  <option value="2021-08">Agosto 2021</option>
	  <option value="2021-09">Setiembre 2021</option>
	  <option value="2021-11">Octubre 2021</option>
	  <option value="2021-11">Noviembre 2021</option>
	  <option value="2021-12">Diciembre 2021</option>
    </select>
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
    <div class="col-sm-6 col-md-6 border">
	<div>
	<canvas id="flota5smes" style="max-width:600px"></canvas>
	</div>
	</div>
    <div class="col-sm-6 col-md-6 border">
	<div>
	<canvas id="flota5splaca" style="max-width:600px"></canvas>
	</div>
	</div>
	</div>
	
	<div class="row">
    <div class="col-sm-6 col-md-6 border">
	<div>
	<canvas id="flota5sporcadas" style="max-width:600px"></canvas>
	</div>
	</div>
    <div class="col-sm-6 col-md-6 border">
	<div>
	<canvas id="5sEmpresa" style="max-width:600px"></canvas>
	</div>
	</div>
	</div>
	
	<div class="row">
    <div class="col-sm-12">
	<div>
	<div class="table-responsive">
	 <table id="obs_5s"  data-order='[[ 0, "asc" ]]' data-page-length='10'
          class="display compact cell-border">
	<thead>
    <tr>
      <th>#</th>
	  <th>Fecha</th>
      <th>Sección</th>
	  <th>Pregunta</th>
	  <th>Respuesta</th>
	  <th>Flota</th>
	  <th>Resultado</th>
	  <th>Comentario</th>
	  <th></th>
    </tr>
	</thead>
	<tbody>
		<?php		
$sql_top_observaciones="SELECT * FROM 
(SELECT 
a.tema,
a.grupo_preguntas,
a.preguntas,
a.id_tema,
a.flota,
a.grupo,
a.fecha_registro,
sum(a.countcheck) as countpreguntas,
sum(a.respuesta_usuario) as respuesta_usuario,
sum(a.countcheck)*3 as countcheck,
round (sum(a.respuesta_usuario)/(sum(a.countcheck)*3)*100,2) as resultado
FROM 
(
SELECT
1 as countcheck, 
`id`, 
`id_tema`, 
`id_grupo_preguntas`,
`tipo_pregunta`, 
`id_respueta`,
`desc_resp`,
`respuesta_user`, 
`user_registro`, 
`grupo`, 
`fecha`, 
`empresa`, 
`fecha_registro`, 
`bk`, 
`puesto_trabajo`, 
`codigo_cliente`, 
`area_oficina`, 
`area_almacen`, 
`usuario_hc`, 
`flota`, 
`txt_actions`, 
`id_pregunta_grupo`, 
`txt_comentario`,
(SELECT descripcion FROM exa_temas WHERE id=`id_tema`) as tema,
(SELECT descripcion FROM exa_grupo_preguntas WHERE id=`id_grupo_preguntas`) as grupo_preguntas,
(SELECT descripcion FROM exa_preguntas WHERE id=`id_preguntas`) as preguntas,
(CASE
    WHEN (SELECT respuestas FROM exa_respuesta WHERE id=`id_respueta`) = '0 = No ok. Hay GAPs' THEN 0
    WHEN (SELECT respuestas FROM exa_respuesta WHERE id=`id_respueta`) = '1 = Necesita acciones para mejorar' THEN 1
	WHEN (SELECT respuestas FROM exa_respuesta WHERE id=`id_respueta`) = '3 = Ok.' THEN 3
    ELSE ''
END) AS respuesta_usuario
FROM `exa_detalle_checklist` 
WHERE id_tema=854630120 AND centro=:centro AND `st`=1 AND `respuesta_user`<>'' AND id_grupo_preguntas <> 56 AND fecha_registro >= '$fecha_ini' AND fecha_registro <= '$fecha_fin'
) AS a group by 
a.tema,
a.grupo_preguntas,
a.preguntas,
a.id_tema,
a.flota,
a.grupo,
a.fecha_registro
ORDER BY `respuesta_usuario`  DESC
) AS c left join 
(
SELECT
a.grupo as grupockl,
a.txt_comentario,
a.respuesta_usertxt
FROM 
(
SELECT
`id_tema`,  
`grupo`,  
`fecha_registro`,     
`flota`,  
`txt_comentario`,
(SELECT respuestas FROM exa_respuesta WHERE id=`id_respueta`) as respuesta_usertxt,
(SELECT descripcion FROM exa_temas WHERE id=`id_tema`) as tema,
(SELECT descripcion FROM exa_grupo_preguntas WHERE id=`id_grupo_preguntas`) as grupo_preguntas,
(SELECT descripcion FROM exa_preguntas WHERE id=`id_preguntas`) as preguntas,
(CASE
    WHEN (SELECT respuestas FROM exa_respuesta WHERE id=`id_respueta`) = '0 = No ok. Hay GAPs' THEN 0
    WHEN (SELECT respuestas FROM exa_respuesta WHERE id=`id_respueta`) = '1 = Necesita acciones para mejorar' THEN 1
	WHEN (SELECT respuestas FROM exa_respuesta WHERE id=`id_respueta`) = '3 = Ok.' THEN 3
    ELSE ''
END) AS respuesta_usuario
FROM `exa_detalle_checklist` 
WHERE id_tema=854630120 AND centro=:centro AND `st`=1 AND `respuesta_user`<>'' AND txt_comentario <> '' AND fecha_registro >= '$fecha_ini' AND fecha_registro <= '$fecha_fin'
GROUP BY 
`id_tema`,  
`grupo`,  
`fecha_registro`,     
`flota`,  
`txt_comentario`,
`id_respueta`,
`id_grupo_preguntas`,
`id_preguntas`
) as a 
) as d ON c.grupo=d.grupockl WHERE c.resultado <> 100 ORDER BY  c.resultado ASC";	      
		  $db=Db::conectar();
          $selectobstop=$db->prepare($sql_top_observaciones);
		  $selectobstop->bindValue('centro',$idcentro);
		  //$select->bindValue('Fecha',$fechaselec);
		  $selectobstop->execute();
		  $n=1;
          while ($rowobstop=$selectobstop->fetch()) {		
		?>
		<tr>
		<td><?php echo $n; ?></td>
		<td><?php echo $rowobstop['fecha_registro']; ?></td>
		<td><?php echo $rowobstop['grupo_preguntas']; ?></td>
		<td><?php echo $rowobstop['preguntas']; ?></td>
        <td><?php echo $rowobstop['respuesta_usertxt']; ?></td>
	    <td><?php echo $rowobstop['flota']; ?></td>
		<td><?php echo $rowobstop['resultado']; ?></td>
	    <td><?php echo $rowobstop['txt_comentario']; ?></td>                                           	
        <td><button  type="button" class="btn btn-danger btn-sm" onclick="location.href='checklistrpt?exa=resultwatch&amp;id=<?php echo $rowobstop['id_tema']; ?>&amp;clave=<?php echo $rowobstop['grupockl']; ?>';" >Ver</button></td>
		</tr>	
		<?php 
		$n++;	
		}
		Db::desconectar();
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
			    <!--datatables-->
  <link rel="stylesheet" type="text/css" href=" https://cdn.datatables.net/1.10.23/css/jquery.dataTables.min.css" />
  <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.18/datatables.min.js"></script>
  <script>
$('#obs_5s').DataTable( {
    responsive: true,
	 "searching": true,
        "paging":   true,
        "info":     true,
		"autoWidth": true
} );
  </script>		  
	<script>
var lineData = {
          labels: [<?php echo "'".implode("','", $mes)."'"; ?>],
          datasets: [{
          label: "Europe",
		  type: "line",
		  label: 'meta',
		  backgroundColor: 'rgba(255, 0, 0,7)',
          borderColor: 'rgb(224, 33, 36)', 		  
          data: [<?php echo implode(",", $metames); ?>],
          fill: false
                }, 
	    {
          label: "Europe",
		  type: "bar",
		  label: 'resultado',
          backgroundColor: 'rgba(0, 90, 156, 0.5)',
          borderColor: 'rgb(0, 90, 156)',
          borderWidth: 1,// Ancho del borde		 
          data: [<?php echo implode(",", $resultadomes); ?>],
        }				
				]
            };
        var lineOptions = {
			scales: {
			yAxes: [{
            ticks: {
                min: 60
				}
			}]
			},
			legend: {
			display: true,
			labels: {
                     boxWidth: 20,
                     padding: 10
                    }
					},			
				      title: {
			display: true,
			text: 'Auditoria 5S flota 2021'
			},
                responsive: true,
                animation: {
                    onComplete: function(){
                        var ctx = this.chart.ctx;
                        ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, 'normal', Chart.defaults.global.defaultFontFamily);
                        ctx.fillStyle = "gray";
                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'bottom';

                        this.data.datasets.forEach(function (dataset){
                            for(var i = 0; i < dataset.data.length; i++){
                                for(var key in dataset._meta){
                                    var model = dataset._meta[key].data[i]._model;
                                    ctx.fillText(dataset.data[i], model.x, model.y - 5);
                                }
                            }
                        });
                    }
                }
				
            };
            ctx = document.getElementById("flota5smes").getContext("2d");
            myChart = new Chart(ctx, {
                type: 'bar',
                data: lineData,
                options: lineOptions,
            });
	</script>
	<script>
var lineData = {
          labels: [<?php echo "'".implode("','", $flota)."'"; ?>],
          datasets: [{
          label: "Europe",
		  type: "line",
		  label: 'meta',
		  backgroundColor: 'rgba(255, 0, 0,7)',
          borderColor: 'rgb(224, 33, 36)', 
          data: [<?php echo implode(",", $metaplaca); ?>],
          fill: false
                }, 
	    {
          label: "Europe",
		  type: "bar",
		  label: 'resultado',
          backgroundColor: 'rgba(0, 90, 156, 0.5)',
          borderColor: 'rgb(0, 90, 156)',
          borderWidth: 1,// Ancho del borde		 
          data: [<?php echo implode(",", $resultadoplaca); ?>], 
        }				
				]
            };
        var lineOptions = {
			scales: {
			yAxes: [{
            ticks: {
                min: 60
				}
			}]
			},
			legend: {
			display: true,
			labels: {
                     boxWidth: 20,
                     padding: 10
                    }
					},			
				      title: {
			display: true,
			text: <?php echo "'Resultado por placa del ".$fecha_ini.' al '.$fecha_fin."'"; ?>
			},
                responsive: true,
                animation: {
                    onComplete: function(){
                        var ctx = this.chart.ctx;
                        ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontFamily, 'normal', Chart.defaults.global.defaultFontFamily);
                        ctx.fillStyle = "gray";
                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'bottom';
                        this.data.datasets.forEach(function (dataset){
                            for(var i = 0; i < dataset.data.length; i++){
                                for(var key in dataset._meta){
                                    var model = dataset._meta[key].data[i]._model;
                                    ctx.fillText(dataset.data[i], model.x, model.y - 5);
                                }
                            }
                        });
                    }
                }
				
            };
            ctx = document.getElementById("flota5splaca").getContext("2d");
            myChart = new Chart(ctx, {
                type: 'bar',
                data: lineData,
                options: lineOptions,
            });
	</script>
	<script>
var lineData = {
		  labels: [<?php echo "'".implode("','", $grupopreguntas)."'"; ?>],
		  datasets: [
        {
          label: "5s",
          backgroundColor: ['rgba(225, 225, 225, 0.7)','rgba(255, 242, 0, 0.7)','rgba(247, 149, 29, 0.7)','rgba(224, 33, 36, 0.7)','rgba(151, 27, 30, 0.7)'],
		  borderColor: ['rgb(225, 225, 225)','rgb(255, 242, 0)','rgb(247, 149, 29)','rgb(224, 33, 36)','rgb(151, 27, 30)'],
          borderWidth: 2,// Ancho del borde	
		  
           data: [<?php echo implode(",", $resultado5s); ?>]
        }
		]	
            };
        var lineOptions = {
			scales: {
			xAxes: [{
            ticks: {
                min: 60
				}
			}]
			},
			legend: {
			display: false,
			labels: {
                     boxWidth: 20,
                     padding: 10
                    }
					},			
				      title: {
			display: true,
			text: <?php echo "'Resultado por 5s del ".$fecha_ini.' al '.$fecha_fin."'"; ?>
			},
                responsive: true,
                animation: {
                    onComplete: function(){
                        var ctx = this.chart.ctx;
                        ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontFamily, 'normal', Chart.defaults.global.defaultFontFamily);
                        ctx.fillStyle = "black";
                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'top';
                        this.data.datasets.forEach(function (dataset){
                            for(var i = 0; i < dataset.data.length; i++){
                                for(var key in dataset._meta){
                                    var model = dataset._meta[key].data[i]._model;
                                    ctx.fillText(dataset.data[i], model.x, model.y - 5);
                                }
                            }
                        });
                    }
                }
				
            };
            ctx = document.getElementById("flota5sporcadas").getContext("2d");
            myChart = new Chart(ctx, {
                type: 'horizontalBar',
                data: lineData,
                options: lineOptions,
            });
	</script>	
		<script>
var lineData = {
          labels: [<?php echo "'".implode("','", $Empresa)."'"; ?>],
          datasets: [{
          label: "Europe",
		  type: "line",
		  label: 'meta',
		  backgroundColor: 'rgba(255, 0, 0,7)',
          borderColor: 'rgb(224, 33, 36)', 
          data: [<?php echo implode(",", $EmpresaMeta); ?>],
          fill: false
                }, 
	    {
          label: "Europe",
		  type: "bar",
		  label: 'resultado',
          backgroundColor: 'rgba(66, 255, 51, 0.5)',
          borderColor: 'rgb(66, 255, 51)',
          borderWidth: 1,// Ancho del borde		 
          data: [<?php echo implode(",", $EmpresaResult); ?>], 
        }				
				]
            };
        var lineOptions = {
			scales: {
			yAxes: [{
            ticks: {
                min: 60
				}
			}]
			},
			legend: {
			display: true,
			labels: {
                     boxWidth: 20,
                     padding: 10
                    }
					},			
				      title: {
			display: true,
			text: <?php echo "'Resultado por empresa del ".$fecha_ini.' al '.$fecha_fin."'"; ?>
			},
                responsive: true,
                animation: {
                    onComplete: function(){
                        var ctx = this.chart.ctx;
                        ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontFamily, 'normal', Chart.defaults.global.defaultFontFamily);
                        ctx.fillStyle = "gray";
                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'bottom';
                        this.data.datasets.forEach(function (dataset){
                            for(var i = 0; i < dataset.data.length; i++){
                                for(var key in dataset._meta){
                                    var model = dataset._meta[key].data[i]._model;
                                    ctx.fillText(dataset.data[i], model.x, model.y - 5);
                                }
                            }
                        });
                    }
                }
				
            };
            ctx = document.getElementById("5sEmpresa").getContext("2d");
            myChart = new Chart(ctx, {
                type: 'bar',
                data: lineData,
                options: lineOptions,
            });
	</script>	
	<?php 		
	} else {
	echo $html_acceso;		
	}
	} ?>
	</body>
	</html>
