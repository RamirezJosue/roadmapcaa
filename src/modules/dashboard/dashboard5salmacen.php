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
	}else if($_GET['mes']=='2021-06'){
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
SELECT 
85 as meta,
y.mesl,
y.mesanio,
avg (g.resultado) as resultado_mes
FROM (
SELECT 
a.id_tema,
a.tema,
a.mes,
a.area_almacen as area_cd,
round (sum(a.respuesta_usuario)/(sum(a.countcheck)*4)*100,2) as resultado
FROM 
(
SELECT
1 as countcheck, 
`id`, 
`id_grupo_preguntas`,
`tipo_pregunta`, 
`id_respueta`,
`id_tema`,
`desc_resp`,
`respuesta_user`, 
`user_registro`, 
`grupo`, 
`fecha`, 
`empresa`, 
fecha_registro,
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
    WHEN `desc_resp` = '0 = No ha empezado' THEN 0
    WHEN `desc_resp` = '1 = Muy Pobre' THEN 1
    WHEN `desc_resp` = '2 = Mas o menos' THEN IF(id_grupo_preguntas=70,2*1.25,IF(id_grupo_preguntas=71,2*1.3,2))
	WHEN `desc_resp` = '3 = Bueno' THEN IF(id_grupo_preguntas=67,3*1.25,IF(id_grupo_preguntas=68,3*1.22,IF(id_grupo_preguntas=69,3*1.25,3))) 
	WHEN `desc_resp` = '4 = Excelente' THEN 4
    ELSE ''
END) AS respuesta_usuario
FROM `exa_detalle_checklist` 
WHERE id_tema=769341408 AND centro=:centro AND `st`=1 AND `respuesta_user`<>'' AND id_grupo_preguntas <> 66 AND fecha_registro >= '$fecha_ini' AND fecha_registro <= '$fecha_fin'
) AS a group by 
a.id_tema,
a.tema,
a.mes,
a.area_almacen
UNION ALL
SELECT 
v.id_tema,
v.tema,
v.mes,
v.area_oficina as area_cd,
avg(v.resultado) as resultado
FROM 
(
SELECT 
b.tema,
b.id_tema,
b.grupo,
b.area_oficina,
b.mes,
round ((2*(sum(b.Puntaje1)*0.2))+(2*(sum(b.Puntaje2)*0.8)),2)  as resultado
FROM 
(
SELECT 
a.mes,
a.tema,
a.id_tema,
a.id_grupo_preguntas,
a.grupo_preguntas,
a.id_preguntas, 
a.grupo,
a.fecha_registro,
a.area_oficina,
a.usuario_hc,
if(a.id_preguntas IN(319,320,321,322,323),a.respuesta_usuario,0) AS Puntaje1, 
if(a.id_preguntas IN(324,325,326,327,328),a.respuesta_usuario,0) AS Puntaje2, 
a.respuesta_usuario,
a.countcheck
FROM 
(
SELECT
1 as countcheck, 
`id`,
`id_tema`,
`id_grupo_preguntas`,
`id_preguntas`,
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
(SELECT pregunta FROM exa_preguntas WHERE id=`id_preguntas`) as preguntas,
(CASE
    WHEN `desc_resp` = '0 = No ha empezado' THEN 0
    WHEN `desc_resp` = '1 = Muy Pobre' THEN 1
    WHEN `desc_resp` = '5 = Mas o menos' THEN 5
	WHEN `desc_resp` = '10 = Excelente' THEN 10
    ELSE ''
END) AS respuesta_usuario
FROM `exa_detalle_checklist` 
WHERE id_tema=197358123 AND centro=:centro AND `st`=1 AND `respuesta_user`<>'' AND id_grupo_preguntas <> 63 AND fecha_registro >= '$fecha_ini' AND fecha_registro <= '$fecha_fin' 
) AS a 
) b GROUP BY
b.tema,
b.id_tema,
b.grupo,
b.area_oficina,
b.mes
) as v 
group by
v.id_tema,
v.tema,
v.mes,
v.area_oficina
) AS g right join (
   SELECT `mesanio`,`mesl` FROM `fechas_det` WHERE `anio`='2021' GROUP BY `mesanio`
) as y ON g.mes = y.mesanio
group by 
y.mesl, y.mesanio ORDER BY y.mesanio ASC
	";	  $db=Db::conectar();
          $selectmes=$db->prepare($sql_mes);
		  $selectmes->bindValue('centro',$idcentro);
		  //$select->bindValue('Fecha',$fechaselec);
		  $selectmes->execute();
          while ($rowmes=$selectmes->fetch()) {
		  $mes[] = substr($rowmes['mesl'],0,3);
		  $resultadomes[] = round($rowmes['resultado_mes'],1);
		  $metames[] = $rowmes['meta'];
		  }
		  Db::desconectar();
$sql_5S_cd="
SELECT 
g.id_tema,
g.tema,
CONCAT(y.duenio,' | ',y.zona) as zona_cd,
avg (g.resultado) as resultado_zona_cd
FROM (
SELECT 
a.id_tema,
a.tema,
a.area_almacen as area_cd,
round (sum(a.respuesta_usuario)/(sum(a.countcheck)*4)*100,2) as resultado
FROM 
(
SELECT
1 as countcheck, 
`id`, 
`id_grupo_preguntas`,
`tipo_pregunta`, 
`id_respueta`,
`id_tema`,
`desc_resp`,
`respuesta_user`, 
`user_registro`, 
`grupo`, 
`fecha`, 
`empresa`, 
fecha_registro,
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
    WHEN `desc_resp` = '0 = No ha empezado' THEN 0
    WHEN `desc_resp` = '1 = Muy Pobre' THEN 1
    WHEN `desc_resp` = '2 = Mas o menos' THEN IF(id_grupo_preguntas=70,2*1.25,IF(id_grupo_preguntas=71,2*1.3,2))
	WHEN `desc_resp` = '3 = Bueno' THEN IF(id_grupo_preguntas=67,3*1.25,IF(id_grupo_preguntas=68,3*1.22,IF(id_grupo_preguntas=69,3*1.25,3))) 
	WHEN `desc_resp` = '4 = Excelente' THEN 4
    ELSE ''
END) AS respuesta_usuario
FROM `exa_detalle_checklist` 
WHERE id_tema=769341408 AND centro=:centro AND `st`=1 AND `respuesta_user`<>'' AND id_grupo_preguntas <> 66 AND fecha_registro >= '$fecha_ini' AND fecha_registro <= '$fecha_fin'
) AS a group by 
a.id_tema,
a.tema,
a.area_almacen
UNION ALL
SELECT 
v.id_tema,
v.tema,
v.area_oficina as area_cd,
avg(v.resultado) as resultado
FROM 
(
SELECT 
b.tema,
b.id_tema,
b.grupo,
b.area_oficina,
b.mes,
round ((2*(sum(b.Puntaje1)*0.2))+(2*(sum(b.Puntaje2)*0.8)),2)  as resultado
FROM 
(
SELECT 
a.mes,
a.tema,
a.id_tema,
a.id_grupo_preguntas,
a.grupo_preguntas,
a.id_preguntas, 
a.grupo,
a.fecha_registro,
a.area_oficina,
a.usuario_hc,
if(a.id_preguntas IN(319,320,321,322,323),a.respuesta_usuario,0) AS Puntaje1, 
if(a.id_preguntas IN(324,325,326,327,328),a.respuesta_usuario,0) AS Puntaje2, 
a.respuesta_usuario,
a.countcheck
FROM 
(
SELECT
1 as countcheck, 
`id`,
`id_tema`,
`id_grupo_preguntas`,
`id_preguntas`,
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
(SELECT pregunta FROM exa_preguntas WHERE id=`id_preguntas`) as preguntas,
(CASE
    WHEN `desc_resp` = '0 = No ha empezado' THEN 0
    WHEN `desc_resp` = '1 = Muy Pobre' THEN 1
    WHEN `desc_resp` = '5 = Mas o menos' THEN 5
	WHEN `desc_resp` = '10 = Excelente' THEN 10
    ELSE ''
END) AS respuesta_usuario
FROM `exa_detalle_checklist` 
WHERE id_tema=197358123 AND centro=:centro AND `st`=1 AND `respuesta_user`<>'' AND id_grupo_preguntas <> 63 AND fecha_registro >= '$fecha_ini' AND fecha_registro <= '$fecha_fin'
) AS a 
) b GROUP BY
b.tema,
b.id_tema,
b.grupo,
b.area_oficina,
b.mes
) as v 
group by
v.id_tema,
v.tema,
v.area_oficina
) AS g left join (
SELECT descripcion,areatxt,duenio,subduenio,zona FROM `exa_area_5s` WHERE `centro`=:centro AND `st`=1
) as y ON g.area_cd = y.descripcion
group by 
g.id_tema,
g.tema,
y.duenio,
y.zona ORDER BY g.resultado DESC
";	  $db=Db::conectar();
          $selectplaca=$db->prepare($sql_5S_cd);
		  $selectplaca->bindValue('centro',$idcentro);
		  //$select->bindValue('Fecha',$fechaselec);
		  $selectplaca->execute();
          while ($rowplaca=$selectplaca->fetch()) {
		  $zona_cd[] = $rowplaca['zona_cd'];
		  $resultado_zona_cd[] = round($rowplaca['resultado_zona_cd'],2);
		  }
		  Db::desconectar();
$sql_por5Salm="
SELECT 
a.id_tema,
a.tema,
a.grupo_preguntas as grupo_preguntasgr,
sum(a.respuesta_usuario) as respuesta_usuario,
sum(a.countcheck)*4 as countcheck,
round (sum(a.respuesta_usuario)/(sum(a.countcheck)*4)*100,2) as resultadogr
FROM 
(
SELECT
1 as countcheck, 
`id`, 
`id_grupo_preguntas`,
`tipo_pregunta`, 
`id_respueta`,
`id_tema`,
`desc_resp`,
`respuesta_user`, 
`user_registro`, 
`grupo`, 
`fecha`, 
`empresa`, 
fecha_registro,
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
    WHEN `desc_resp` = '0 = No ha empezado' THEN 0
    WHEN `desc_resp` = '1 = Muy Pobre' THEN 1
    WHEN `desc_resp` = '2 = Mas o menos' THEN IF(id_grupo_preguntas=70,2*1.25,IF(id_grupo_preguntas=71,2*1.3,2))
	WHEN `desc_resp` = '3 = Bueno' THEN IF(id_grupo_preguntas=67,3*1.25,IF(id_grupo_preguntas=68,3*1.22,IF(id_grupo_preguntas=69,3*1.25,3))) 
	WHEN `desc_resp` = '4 = Excelente' THEN 4
    ELSE ''
END) AS respuesta_usuario
FROM `exa_detalle_checklist` 
WHERE id_tema=769341408 AND centro=:centro AND `st`=1 AND `respuesta_user`<>'' AND id_grupo_preguntas <> 66 AND fecha_registro >= '$fecha_ini' AND fecha_registro <= '$fecha_fin'
) AS a group by 
a.id_tema,
a.tema,
a.grupo_preguntas
";	  $db=Db::conectar();
          $select5s=$db->prepare($sql_por5Salm);
		  $select5s->bindValue('centro',$idcentro);
		  //$select->bindValue('Fecha',$fechaselec);
		  $select5s->execute();
          while ($row5s=$select5s->fetch()) {
		  $grupopreguntas[] = $row5s['grupo_preguntasgr'];
		  $resultado5s[] = $row5s['resultadogr'];
		  }
		  Db::desconectar();
$sql_5Srespoalm="
SELECT 
g.id_tema,
g.tema,
y.subduenio,
y.areatxt,
g.area_cd as sub_zona_cd,
avg (g.resultado) as resultado_subzona_cd
FROM (
SELECT 
a.id_tema,
a.tema,
a.area_almacen as area_cd,
round (sum(a.respuesta_usuario)/(sum(a.countcheck)*4)*100,2) as resultado
FROM 
(
SELECT
1 as countcheck, 
`id`, 
`id_grupo_preguntas`,
`tipo_pregunta`, 
`id_respueta`,
`id_tema`,
`desc_resp`,
`respuesta_user`, 
`user_registro`, 
`grupo`, 
`fecha`, 
`empresa`, 
fecha_registro,
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
    WHEN `desc_resp` = '0 = No ha empezado' THEN 0
    WHEN `desc_resp` = '1 = Muy Pobre' THEN 1
    WHEN `desc_resp` = '2 = Mas o menos' THEN IF(id_grupo_preguntas=70,2*1.25,IF(id_grupo_preguntas=71,2*1.3,2))
	WHEN `desc_resp` = '3 = Bueno' THEN IF(id_grupo_preguntas=67,3*1.25,IF(id_grupo_preguntas=68,3*1.22,IF(id_grupo_preguntas=69,3*1.25,3))) 
	WHEN `desc_resp` = '4 = Excelente' THEN 4
    ELSE ''
END) AS respuesta_usuario
FROM `exa_detalle_checklist` 
WHERE id_tema=769341408 AND centro=:centro AND `st`=1 AND `respuesta_user`<>'' AND id_grupo_preguntas <> 66 AND fecha_registro >= '$fecha_ini' AND fecha_registro <= '$fecha_fin'
) AS a group by 
a.id_tema,
a.tema,
a.area_almacen
UNION ALL
SELECT 
v.id_tema,
v.tema,
v.area_oficina as area_cd,
avg(v.resultado) as resultado
FROM 
(
SELECT 
b.tema,
b.id_tema,
b.grupo,
b.area_oficina,
b.mes,
round ((2*(sum(b.Puntaje1)*0.2))+(2*(sum(b.Puntaje2)*0.8)),2)  as resultado
FROM 
(
SELECT 
a.mes,
a.tema,
a.id_tema,
a.id_grupo_preguntas,
a.grupo_preguntas,
a.id_preguntas, 
a.grupo,
a.fecha_registro,
a.area_oficina,
a.usuario_hc,
if(a.id_preguntas IN(319,320,321,322,323),a.respuesta_usuario,0) AS Puntaje1, 
if(a.id_preguntas IN(324,325,326,327,328),a.respuesta_usuario,0) AS Puntaje2, 
a.respuesta_usuario,
a.countcheck
FROM 
(
SELECT
1 as countcheck, 
`id`,
`id_tema`,
`id_grupo_preguntas`,
`id_preguntas`,
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
(SELECT pregunta FROM exa_preguntas WHERE id=`id_preguntas`) as preguntas,
(CASE
    WHEN `desc_resp` = '0 = No ha empezado' THEN 0
    WHEN `desc_resp` = '1 = Muy Pobre' THEN 1
    WHEN `desc_resp` = '5 = Mas o menos' THEN 5
	WHEN `desc_resp` = '10 = Excelente' THEN 10
    ELSE ''
END) AS respuesta_usuario
FROM `exa_detalle_checklist` 
WHERE id_tema=197358123 AND centro=:centro AND `st`=1 AND `respuesta_user`<>'' AND id_grupo_preguntas <> 63 AND fecha_registro >= '$fecha_ini' AND fecha_registro <= '$fecha_fin' 
) AS a 
) b GROUP BY
b.tema,
b.id_tema,
b.grupo,
b.area_oficina,
b.mes
) as v 
group by
v.id_tema,
v.tema,
v.area_oficina
) AS g left join (
SELECT descripcion,areatxt,duenio,subduenio,zona FROM `exa_area_5s` WHERE `centro`=:centro AND `st`=1
) as y ON g.area_cd = y.descripcion
group by 
g.id_tema,
g.tema,
y.subduenio,
y.areatxt,
g.area_cd ORDER BY g.resultado DESC
";	  $db=Db::conectar();
          $select5smes=$db->prepare($sql_5Srespoalm);
		  $select5smes->bindValue('centro',$idcentro);
		  //$select->bindValue('Fecha',$fechaselec);
		  $select5smes->execute();
          while ($row5smes=$select5smes->fetch()) {
		  $EmpresaResult[] = $row5smes['resultado_subzona_cd'];
		  $Empresa[] = $row5smes['sub_zona_cd'];
		  }
		  Db::desconectar();		  
?>
	<div class="row border">
    <div class="col-sm-12 bg-danger">
	<div class="d-flex justify-content-center">
	<div class="p-2 bd-highlight"><div class="text-white text-md-center font-weight-bolder">5S Oficinas/Almacen<?php echo $idcentro.' | '.$fecha_ini.' al '.$fecha_fin; ?></div></div>
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
	  <option value="2021-10">Octubre 2021</option>
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
	<canvas id="zona_cd" style="max-width:600px"></canvas>
	</div>
	</div>
	</div>
	
	<div class="row">
    <div class="col-sm-4 col-md-4 border">
	<div>
	<canvas id="flota5sporcadas" style="max-width:600px"></canvas>
	</div>
	</div>
    <div class="col-sm-8 col-md-8 border">
	<div class="responsive">
	<canvas id="5sEmpresa" style="height:350px"></canvas>
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
	  <th>Resultado</th>
      <th>Sección</th>
	  <th>Pregunta</th>
	  <th>Respuesta</th>
	  <th>Area</th>
	  <th>Comentario</th>
	  <th></th>
    </tr>
	</thead>
	<tbody>
		<?php		
$sql_top_observaciones="
SELECT
`id_tema`,
`grupo`,
`txt_comentario`,
`id_preguntas`,
`desc_resp`,
`area_almacen` AS area_cd,
`id_grupo_preguntas`,
 `fecha_registro`, 
(SELECT descripcion FROM exa_temas WHERE id=`id_tema`) as tema, 
(SELECT descripcion FROM exa_grupo_preguntas WHERE id=`id_grupo_preguntas`) as grupo_preguntas,
(SELECT descripcion FROM exa_preguntas WHERE id=`id_preguntas`) as preguntas
FROM `exa_detalle_checklist` 
WHERE id_tema=769341408 AND centro=:centro AND `st`=1 AND `respuesta_user`<>'' AND txt_comentario <> '' AND id_grupo_preguntas <> 66 AND fecha_registro >= '$fecha_ini' AND fecha_registro <= '$fecha_fin'
group by 
`id_tema`,
`grupo`,
`txt_comentario`,
`id_preguntas`,
`desc_resp`,
`area_almacen`,
`id_grupo_preguntas`,
 `fecha_registro`
UNION ALL 
 SELECT
`id_tema`,
`grupo`,
`txt_comentario`,
`id_preguntas`,
`desc_resp`,
`area_oficina` AS area_cd,
`id_grupo_preguntas`,
 `fecha_registro`, 
(SELECT descripcion FROM exa_temas WHERE id=`id_tema`) as tema, 
(SELECT descripcion FROM exa_grupo_preguntas WHERE id=`id_grupo_preguntas`) as grupo_preguntas,
(SELECT descripcion FROM exa_preguntas WHERE id=`id_preguntas`) as preguntas
FROM `exa_detalle_checklist` 
WHERE id_tema=197358123 AND centro=:centro AND `st`=1 AND `respuesta_user`<>'' AND txt_comentario <> '' AND id_grupo_preguntas <> 63 AND fecha_registro >= '$fecha_ini' AND fecha_registro <= '$fecha_fin'
group by 
`id_tema`,
`grupo`,
`txt_comentario`,
`id_preguntas`,
`desc_resp`,
`area_oficina`,
`id_grupo_preguntas`,
 `fecha_registro`
";	      $db=Db::conectar();
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
		<td><?php echo $rowobstop['tema']; ?></td>
		<td><?php echo $rowobstop['grupo_preguntas']; ?></td>
		<td><?php echo $rowobstop['preguntas']; ?></td>
        <td><?php echo $rowobstop['desc_resp']; ?></td>
	    <td><?php echo $rowobstop['area_cd']; ?></td>
	    <td><?php echo $rowobstop['txt_comentario']; ?></td>                                           	
        <td><button  type="button" class="btn btn-danger btn-sm" onclick="location.href='checklistrpt?exa=resultwatch&amp;id=<?php echo $rowobstop['id_tema']; ?>&amp;clave=<?php echo $rowobstop['grupo']; ?>';" >Ver</button></td>
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
		  backgroundColor: 'rgba(224, 33, 36)',
          borderColor: 'rgb(224, 33, 36)', 		  
          data: [<?php echo implode(",", $metames); ?>],
          fill: false
                }, 
	    {
          label: "Europe",
		  type: "bar",
		  label: 'resultado',
          backgroundColor: 'rgba(247, 149, 29, 0.5)',
          borderColor: 'rgb(247, 149, 29)',
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
			text: 'Oficinas y almacen 2021'
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
		  labels: [<?php echo "'".implode("','", $zona_cd)."'"; ?>],
		  datasets: [
        {
          label: "dueño | zona",
          backgroundColor: 'rgba(224, 33, 36, 0.5)', 
		   borderColor: 'rgb(224, 33, 36)',	
          borderWidth: 2,// Ancho del borde	
          data: [<?php echo implode(",", $resultado_zona_cd); ?>],
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
			text: <?php echo "'Oficinas y almacen ".$fecha_ini.' al '.$fecha_fin."'"; ?>
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
            ctx = document.getElementById("zona_cd").getContext("2d");
            myChart = new Chart(ctx, {
                type: 'horizontalBar',
                data: lineData,
                options: lineOptions,
            });		
	</script>	
	<script>
var lineData = {
		  labels: [<?php echo "'".implode("','", $grupopreguntas)."'"; ?>],
		  datasets: [
        {
          label: "Almacen",
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
			text: <?php echo "'Almacen ".$fecha_ini.' al '.$fecha_fin."'"; ?>
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
		  datasets: [
        {
          label: "Sub zona",
          backgroundColor: 'rgba(66, 255, 51, 0.5)', 
		   borderColor: 'rgb(66, 255, 51)',	
          borderWidth: 2,// Ancho del borde	
            data: [<?php echo implode(",", $EmpresaResult); ?>], 
        }
		]	
            };
        var lineOptions = {
			scales: {
			xAxes: [{
			barPercentage : 1,
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
			text: <?php echo "'Resultado por sub zona del ".$fecha_ini.' al '.$fecha_fin."'"; ?>
			},
                responsive: false,
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
            ctx = document.getElementById("5sEmpresa").getContext("2d");
            myChart = new Chart(ctx, {
                type: 'horizontalBar',
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

