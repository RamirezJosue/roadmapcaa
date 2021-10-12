<?php 
    ob_start();	
    $accesos = basename(dirname(__FILE__));
	require_once('../../includes/ini.php');
	require_once('../../bd/crud_usuario.php');
	$crud=new CrudUsuario();
    if ($usuarioestado==0){
	echo $html_bloqueo;
	} else {
    $arraruser = explode ( ',', $usuarioaccesos);	
	if (in_array($accesos, $arraruser)) {
	if ($usuariotipo==0): $aid_super = 0; else: $aid_super = 1; endif;
	/*inicio vefifia si tiene permisos de adminrepartos */
	if (in_array("adminrepartos", $arraruser)): $adminrepartos = 1; else: $adminrepartos = 0; endif;
    if ($aid_super==1 || $adminrepartos==1): $disableform = ''; else: $disableform = 'disabled'; endif;
	/*fin vefifia si tiene permisos de adminrepartos */
	$bootstrapjs =  1;	
	$datatablesjs = 1;
	require('../head.php');
	if(isset($_GET['fechaselec'])): 
	$fecha_form = $_GET['fechaselec'];
	$fechaselec  = $_GET['fechaselec'];	
	else:
	$fechaselec  = $fechars; 
	$fecha_form = $fecha;
	endif;
    if (isset($_GET['hc'])){ $hc = $_GET['hc']; } else { $hc = ""; }
function form_opciones_user()
{
global $idcentro;
if ($idcentro=='BK77') { $disabled = ''; } else { $disabled = 'disabled'; }
?>	
<div class="btn-group btn-group-sm" role="group" aria-label="Button group with nested dropdown">
  <div class="btn-group btn-group-sm" role="group">
    <button id="btnGroupDrop1" type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      Opciones
    </button>
    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
	  <a class="dropdown-item" href="userhc">Inicio</a>
	  <a class="dropdown-item" href="userhc?nuevo=new">Nueveo Usuario</a>
      <a class="dropdown-item" href="userhc?estado=0">Ver usuarios Inactivos</a>
	  <a class="dropdown-item" href="userhc?hc=listarcovid">Ver usuarios Covid</a>
	  <a class="dropdown-item" href="userhc?puesto=29">Ver usuarios ruta t2</a>
	  <a class="dropdown-item" href="userhc?puesto=28">Ver usuarios conductor t2</a>
	  <a class="dropdown-item" href="excel?excel=usuarios">Exportar XLS</a>
	  <div class="dropdown-divider"></div> 
      <a class="dropdown-item" href="userhc?hc=InicioParametrosUser&amp;tb=empresa">Actualizar empresa</a>
	  <a class="dropdown-item <?php echo $disabled; ?>"  href="userhc?hc=InicioParametrosUser&amp;tb=puesto">Actualizar puesto</a>
	  <a class="dropdown-item <?php echo $disabled; ?>" href="userhc?hc=InicioParametrosUser&amp;tb=area">Actualizar area</a>
	  <a class="dropdown-item <?php echo $disabled; ?>" href="userhc?hc=InicioParametrosUser&amp;tb=division">Actualizar division</a>
	  <a class="dropdown-item" href="userhc?hc=InicioParametrosUser&amp;tb=supervisor">Actualizar supervisor</a>
	  <a class="dropdown-item <?php echo $disabled; ?>" href="userhc?hc=InicioParametrosUser&amp;tb=brevete">Actualizar brevete</a>	  
    </div>
  </div>
</div>
<?php
}
function lista_reportes()
{
global $idcentro;
if ($idcentro=='BK77') { $disabled = ''; } else { $disabled = 'disabled'; }
?>	
<div class="card border-light mb-3">
  <div class="card-header text-white bg-danger">Reportes T2</div>
  <div class="card-body">
<ul class="list-group list-group-flush">
<a href="reportesT2?hc=cvs-excel" class="list-group-item list-group-item-action list-group-item-light">Exportar Datos para SMS </a>
</ul>
  </div>
</div>
<?php
}
function participacionrutinat2($fechaselec)
{ global $fecha,$idcentro,$aid,$crudex;	
?>	
	 <table id="asistenciarutinat2"  data-order='[[ 0, "asc" ]]'
          class="display compact cell-border">
	<thead>
    <tr class="bg-danger text-white">
      <th>Empresa</th>
	  <th>HC</th>
	  <th>Excluye</th>
	  <th>Antes 6:35</th>
	  <th>Despues 6:35</th>
	  <th>Falta</th>
	  <th>%Cumplimiento</th>
	  <th>%Participa</th>
	  <th>%No participa</th>
    </tr>
	</thead>
	<tbody>
  <?php		
		$db=Db::conectar();
		$sql ="
SELECT 
e.empresa, 
SUM(e.contarhc) AS contarhc,
SUM(e.excluye) AS excluye, 
SUM(e.asiste) AS asiste, 
((SUM(e.contarhc)-SUM(e.cumplimiento))/SUM(e.contarhc))*100 AS cumplimiento,
SUM(e.cumplimiento) AS sinregistro,
(SUM(e.contarhc)-SUM(e.asiste)) AS noasiste, 
(SUM(e.asiste)/SUM(e.contarhc))*100 AS participa,
((SUM(e.contarhc)-SUM(e.asiste))/SUM(e.contarhc))*100 AS noparticipa  
FROM 
(
SELECT z.nombre,z.apellidos,z.dni,IF(v.excluye IS NULL,0,v.excluye) AS excluye,
(SELECT descripcion FROM usuario_empresa WHERE id=z.id_empresa) AS empresa,
(SELECT descripcion FROM usuario_puesto WHERE id=z.puesto) AS puesto,
v.fecha,v.llegada,
DATE_FORMAT(v.llegada, '%H:%i:%s') AS hora,
IF(v.llegada IS NULL,1,0) AS cumplimiento,
IF(v.excluye = 1,0,1) AS contarhc,
IF(DATE_FORMAT(v.llegada, '%H:%i:%s') <= '23:59:00',1,0) AS asiste,
v.salida,v.minutos FROM 
(
SELECT * FROM `usuarios` WHERE centro=:centro AND puesto IN (6,7,12,15) AND estado=1
) AS  z LEFT JOIN 
(
SELECT * FROM `t77_tiempos_personal` WHERE centro=:centro AND fecha=:fecha
) AS v ON z.dni = v.dni
) AS e GROUP BY  e.empresa
		";
        $select=$db->prepare($sql);	
        $select->bindValue('centro',$idcentro);
		$select->bindValue('fecha',$fechaselec);		
		$select->execute();
		$n=1;
		while ($rows=$select->fetch()) {	
		?>
		<tr>
		<td><?php echo $rows['empresa']; ?></td>
		<td><?php echo $rows['contarhc']; ?></td>
		<td><?php echo $rows['excluye']; ?></td>
		<td><?php echo $rows['asiste']; ?></td>
        <td><?php echo $rows['noasiste']; ?></td>
		<td><?php echo $rows['sinregistro']; ?></td>
		<td><?php echo round($rows['cumplimiento']); ?>%</td>
        <td><?php echo round($rows['participa']); ?>%</td>  
		<td><?php echo round($rows['noparticipa']); ?>%</td>  		
 		</tr>	
		<?php 
		$n++;	
		}
		Db::desconectar();
	?>
	</tbody>
	</table>
<?php 	
}
function participacionrutinat2detalle($fechaselec)
{ global $fecha,$idcentro,$aid,$crudex;	
?>	
	 <table id="asistenciarutinat2detalle"  data-order='[[ 0, "asc" ]]'
          class="display compact cell-border">
	<thead>
    <tr class="bg-danger text-white">
	  <th>#</th>
      <th>Empresa</th>
	  <th>Nombre</th>
	  <th>HC</th>
	  <th>Excluye</th>
	  <th>LLega</th>
	  <th>Falta</th>
    </tr>
	</thead>
	<tbody>
  <?php		
		$db=Db::conectar();
		$sql ="
SELECT 
z.nombre,
z.apellidos,
z.dni,
IF(v.excluye IS NULL,0,v.excluye) AS excluye,
(SELECT descripcion FROM usuario_empresa WHERE id=z.id_empresa) AS empresa,
(SELECT descripcion FROM usuario_puesto WHERE id=z.puesto) AS puesto,
v.fecha,
v.llegada,
DATE_FORMAT(v.llegada, '%H:%i:%s') AS hora,
IF(v.llegada IS NULL,1,0) AS cumplimiento,
IF(v.excluye = 1,0,1) AS contarhc,
IF(DATE_FORMAT(v.llegada, '%H:%i:%s') <= '06:35:00',1,0) AS asiste,
v.salida,v.minutos FROM 
(
SELECT * FROM `usuarios` WHERE centro=:centro AND puesto IN (6,7,12,15) AND estado=1
) AS  z LEFT JOIN 
(
SELECT * FROM `t77_tiempos_personal` WHERE centro=:centro AND fecha=:fecha
) AS v ON z.dni = v.dni ORDER BY z.id_empresa,z.apellidos ASC 
		";
        $select=$db->prepare($sql);	
        $select->bindValue('centro',$idcentro);	
		$select->bindValue('fecha',$fechaselec);
		$select->execute();
		$n=1;
		while ($rows=$select->fetch()) {
		IF($rows['asiste']==1) { $classs='class="table-success"'; } else { $classs='class="table-danger"'; }
		?>
		<tr <?php echo $classs; ?> >
		<td><?php echo $n; ?></td>		
		<td><?php echo $rows['empresa']; ?></td>
		<td><?php echo $rows['apellidos'].' '.$rows['nombre']; ?></td>
		<td><?php echo $rows['contarhc']; ?></td>
		<td><?php echo $rows['excluye']; ?></td>
        <td><?php echo $rows['hora']; ?></td>
		<td><?php echo $rows['cumplimiento']; ?></td>  		
 		</tr>	
		<?php 
		$n++;	
		}
		Db::desconectar();
	?>
	</tbody>
	</table>
<?php 	
}
function control_licencias()
{ global $fecha,$idcentro,$aid,$crudex;	
?>	

	<div class="table-responsive">   
<table id="tdlicenciasconducir" class="display nowrap" style="width:100%">
	<thead>
    <tr>
	  <th>#</th>
      <th>Nombre</th>
	  <th>Licencia</th>
	  <th>Vence</th>
	  <th>Dias</th>
	  <th>Estado</th>
	  <th>Empresa</th>
	  <th>Puesto</th>
    </tr>
	</thead>
	<tbody>
  <?php		
		$db=Db::conectar();
		$sql ="
SELECT a.nombre,apellidos,(SELECT descripcion FROM usuario_empresa WHERE id=a.id_empresa) AS id_empresa,
(SELECT descripcion FROM usuario_brevete WHERE id=a.brevete_cat) AS brevete_cat, a.vencimiento_brevete,a.diasxvencer,
(SELECT descripcion FROM usuario_puesto WHERE id=a.puesto) as puesto,
if(a.diasxvencer<=0,'Vencido',if(a.diasxvencer<=30,'Por Vencer','ok')) as estado,brevete 
FROM (
SELECT nombre,apellidos,id_empresa,brevete,brevete_cat,vencimiento_brevete,TIMESTAMPDIFF(DAY,DATE_SUB(NOW(), INTERVAL 5 HOUR),vencimiento_brevete) AS diasxvencer ,puesto FROM `usuarios` WHERE centro=:centro AND estado=1 AND brevete_cat<>'' AND puesto IN (6,7,12,15)
     ) AS a ORDER BY a.diasxvencer ASC
		";
        $select=$db->prepare($sql);	
        $select->bindValue('centro',$idcentro);	
		$select->execute();
		$n=1;
		while ($rows=$select->fetch()) {
		IF($rows['estado']=='Vencido') { 
		$classs='class="table-danger"'; 
		} 
		else if ($rows['estado']=='Por Vencer'){ 
		$classs='class="table-warning"'; 
		} 
		else if ($rows['estado']=='ok'){ 
		$classs='class="table-success"'; 
		} else { $classs=''; }
		?>
		<tr <?php echo $classs; ?> >
		<td><?php echo $n; ?></td>		
		<td><?php echo $rows['apellidos'].' '.$rows['nombre']; ?></td>
        <td><?php echo $rows['brevete_cat'].' '.$rows['brevete']; ?></td>
     	<td><?php echo $rows['vencimiento_brevete']; ?></td>
		<td><?php echo $rows['diasxvencer']; ?></td> 
        <td><?php echo $rows['estado']; ?></td>
		<td><?php echo $rows['id_empresa']; ?></td>
		<td><?php echo $rows['puesto']; ?></td>  		
 		</tr>	
		<?php 
		$n++;	
		}
		Db::desconectar();
	?>
	</tbody>
	</table>
	</div>
<?php 	
}
function ejecucion_de_reparto($fechaselec)
{ global $fecha,$idcentro,$aid,$crudex;	
?>	
	<div class="table-responsive">
	 <table id="ejecuciondereparto"  data-order='[[ 0, "asc" ]]'
     class="display compact cell-border">
	<thead>
    <tr>
	  <th>#</th>
	  <th>Ruta</th>
	  <th>Vehiculo</th>
	  <th>CjsPro</th>
	  <th>CjsEnt</th>
	  <th>PocProg</th>
	  <th>PocEnt</th>
	  <th>Refusales</th>
	  <th>Modulacion</th>
	  <th>TiePlan</th>
	  <th>TieReal</th>
	  <th>DesvTiem</th>
	  <th>DistPlan</th>
	  <th>DistReal</th>
	  <th>DesvDist</th>
    </tr>
	</thead>
	<tbody>
  <?php		
		$db=Db::conectar();
		$sql ="
SELECT v.Fecha,z.empresa,v.Ruta,v.Vehiculo,v.CjsPro,v.CjsEnt,v.PocProg,v.PocEnt,z.TiePlan,z.TieReal,z.DistPlan,z.DistReal,v.PocAler,v.PocRech,
((v.CjsPro-v.CjsEnt)/v.CjsPro)*100 as Refusales,
((v.PocAler - v.PocRech)/v.PocAler)*100 as PocModEfec, 
(ABS(z.TieReal-z.TiePlan)/z.TiePlan) AS DesvTie,
(ABS(z.DistReal-z.DistPlan)/z.DistPlan) AS DesvDist  
FROM 
(
SELECT a.Fecha, a.Ruta, a.Vehiculo, 
sum(a.CjsPro) as CjsPro, sum(a.CjsEnt) as CjsEnt, sum(a.PocProg) as PocProg, 
sum(a.PocEnt) as PocEnt, sum(a.PocAler) as PocAler,  sum(a.PocRech) as PocRech  
FROM (
SELECT 
Fecha,Ruta,Vehiculo,Entrega As CjsPro, if(entregado=1,Entrega,0) as CjsEnt, 1 as PocProg, if(entregado=1,1,0) As PocEnt, if(alerta=1,1,0) As PocAler, if(rechazo=1,1,0) As PocRech  
FROM `t77_rs` WHERE centro=:centro AND Fecha='$fechaselec'
     ) AS a GROUP BY a.Fecha, a.Ruta, a.Vehiculo
) AS v 
LEFT JOIN (
SELECT 
c.ruta,c.empresa,c.vehiculo,c.fecha_plan,c.TiePlan,c.DistPlan,c.DistReal,d.TieReal
FROM 
(
SELECT 
a.ruta,a.empresa,a.vehiculo,a.fecha_plan,a.TiePlan,a.DistPlan,if(b.km_recorrido is null,0,b.km_recorrido) as DistReal
FROM 
(
SELECT ruta,empresa,vehiculo,fecha_plan,sum(tiempo_horas) as TiePlan, sum(distancia) as DistPlan  
	FROM `plan_resumen` 
WHERE centro=:centro AND fecha_plan='$fechaselec' GROUP BY ruta,empresa,vehiculo,fecha_plan
) AS a LEFT JOIN 
(
SELECT a.fecha, UPPER(a.vehiculo) AS vehiculo, sum(a.inicial) as km_inicial, sum(a.final) as  km_final,  
IF(SUM(a.inicial)=0,0,IF(sum(a.final)=0,0,(sum(a.final)-sum(a.inicial)))) AS km_recorrido
FROM (
SELECT CAST(respuesta_user AS DECIMAL) AS inicial,0 as final, user_registro AS vehiculo,fecha 
FROM `exa_detalle_user` WHERE id_tema = 2147483647 AND fecha='$fechaselec' AND tipo_pregunta=5 AND centro=:centro
UNION ALL
SELECT 0 as inicial, CAST(respuesta_user AS DECIMAL) AS final,user_registro AS vehiculo,fecha 
FROM `exa_detalle_user` WHERE id_tema = 359282242 AND fecha='$fechaselec' AND tipo_pregunta=5 AND centro=:centro
    ) as a GROUP BY a.fecha, a.vehiculo 
) AS b ON a.vehiculo = b.vehiculo	
) AS c LEFT JOIN 
(
SELECT 
a.vehiculo,a.ruta,a.fecha_plan,a.inicio,a.fin,
IF(a.fin='0000-00-00 00:00:00',TIMESTAMPDIFF(MINUTE,a.inicio,a.hora_actual)/60,TIMESTAMPDIFF(MINUTE,a.inicio,a.fin)/60) as TieReal 
FROM (
SELECT vehiculo,ruta,fecha_plan,MIN(salida_cd) as inicio, MAX(llegada_cd) as fin, DATE_SUB(NOW(), INTERVAL 5 HOUR) AS hora_actual
FROM `t77_rs_ruta_sif` 
WHERE fecha_plan='$fechaselec' AND centro=:centro 
GROUP BY  vehiculo,ruta,fecha_plan
    ) AS a
) AS d ON c.vehiculo = d.vehiculo
) AS z ON v.Vehiculo = z.vehiculo
		";
        $select=$db->prepare($sql);
		$select->bindValue('centro',$idcentro);
		$select->execute();
		$n=1;
		while ($rows=$select->fetch()) {
		if($rows['PocProg']>=50){ $clase='class="table-danger"'; }else{ $clase=''; }	
		?>
		<tr <?php echo $clase; ?> >
		<td><?php echo $n; ?></td>
		<td><?php echo $rows['Ruta']; ?></td>
		<td><?php echo $rows['Vehiculo']; ?></td>
		<td><?php echo round($rows['CjsPro']); ?></td>
		<td><?php echo round($rows['CjsEnt']); ?></td>
		<td><?php echo $rows['PocProg']; ?></td>
		<td><?php echo $rows['PocEnt']; ?></td>
		<td class="table-warning"><?php echo round($rows['Refusales'],2); ?>%</td>
		<td class="table-warning"><?php echo round($rows['PocModEfec'],2); ?>%</td>
        <td><?php echo round($rows['TiePlan'],2); ?> Hr</td>
		<td><?php echo round($rows['TieReal'],2); ?> Hr</td>
        <td class="table-warning"><?php echo round($rows['DesvTie'],2); ?>%</td> 		
		<td><?php echo $rows['DistPlan']; ?> Km</td> 
        <td><?php echo $rows['DistReal']; ?> Km</td>
        <td class="table-warning"><?php echo round($rows['DesvDist'],2); ?>%</td> 		
 		</tr>	
		<?php 
		$n++;	
		}
		Db::desconectar();
	?>
	</tbody>
	</table>
	</div>
<?php 	
}
function ejecucion_de_reparto_empresario($fechaselec)
{ global $fecha,$idcentro,$aid,$crudex;	
?>	
	<div class="table-responsive">
	 <table id="ejecucionderepartoempresa"  data-order='[[ 0, "asc" ]]'
     class="display compact cell-border">
	<thead>
    <tr>
	  <th>#</th>
	  <th>Empresario</th>
	  <th>CjsPro</th>
	  <th>CjsEnt</th>
	  <th>PocProg</th>
	  <th>PocEnt</th>
	  <th>Refusales</th>
	  <th>Modulacion</th>
	  <th>TiePlan</th>
	  <th>TieReal</th>
	  <th>DesvTiem</th>
	  <th>DistPlan</th>
	  <th>DistReal</th>
	  <th>DesvDist</th>
    </tr>
	</thead>
	<tbody>
  <?php		
		$db=Db::conectar();
		$sql ="
SELECT v.Fecha,z.empresa, 
sum(v.CjsPro) as CjsPro,
sum(v.CjsEnt) as CjsEnt,
sum(v.PocProg) as PocProg,
sum(v.PocEnt) as PocEnt,
avg(z.TiePlan) as TiePlan,
avg(z.TieReal) as TieReal,
avg(z.DistPlan) as DistPlan,
avg(z.DistReal) as DistReal,
sum(v.PocAler) as PocAler,
sum(v.PocRech) as PocRech,
((sum(v.CjsPro)-sum(v.CjsEnt))/sum(v.CjsPro))*100 as Refusales,
((sum(v.PocAler) - sum(v.PocRech))/sum(v.PocAler))*100 as PocModEfec, 
(ABS(avg(z.TieReal)-avg(z.TiePlan))/avg(z.TiePlan)) AS DesvTie,
(ABS(avg(z.DistReal)-avg(z.DistPlan))/avg(z.DistPlan)) AS DesvDist  
FROM 
(
SELECT a.Fecha, a.Ruta, a.Vehiculo, 
sum(a.CjsPro) as CjsPro, sum(a.CjsEnt) as CjsEnt, sum(a.PocProg) as PocProg, 
sum(a.PocEnt) as PocEnt, sum(a.PocAler) as PocAler,  sum(a.PocRech) as PocRech  
FROM (
SELECT 
Fecha,Ruta,Vehiculo,Entrega As CjsPro, if(entregado=1,Entrega,0) as CjsEnt, 1 as PocProg, if(entregado=1,1,0) As PocEnt, if(alerta=1,1,0) As PocAler, if(rechazo=1,1,0) As PocRech  
FROM `t77_rs` WHERE centro=:centro AND Fecha='$fechaselec'
     ) AS a GROUP BY a.Fecha, a.Ruta, a.Vehiculo
) AS v 
LEFT JOIN (
SELECT 
c.ruta,c.empresa,c.vehiculo,c.fecha_plan,c.TiePlan,c.DistPlan,c.DistReal,d.TieReal
FROM 
(
SELECT 
a.ruta,a.empresa,a.vehiculo,a.fecha_plan,a.TiePlan,a.DistPlan,if(b.km_recorrido is null,0,b.km_recorrido) as DistReal
FROM 
(
SELECT ruta,empresa,vehiculo,fecha_plan,sum(tiempo_horas) as TiePlan, sum(distancia) as DistPlan  
	FROM `plan_resumen` 
WHERE centro=:centro AND fecha_plan='$fechaselec' GROUP BY ruta,empresa,vehiculo,fecha_plan
) AS a LEFT JOIN 
(
SELECT a.fecha, UPPER(a.vehiculo) AS vehiculo, sum(a.inicial) as km_inicial, sum(a.final) as  km_final,  
IF(SUM(a.inicial)=0,0,IF(sum(a.final)=0,0,(sum(a.final)-sum(a.inicial)))) AS km_recorrido
FROM (
SELECT CAST(respuesta_user AS DECIMAL) AS inicial,0 as final, user_registro AS vehiculo,fecha 
FROM `exa_detalle_user` WHERE id_tema = 2147483647 AND fecha='$fechaselec' AND tipo_pregunta=5 AND centro=:centro
UNION ALL
SELECT 0 as inicial, CAST(respuesta_user AS DECIMAL) AS final,user_registro AS vehiculo,fecha 
FROM `exa_detalle_user` WHERE id_tema = 359282242 AND fecha='$fechaselec' AND tipo_pregunta=5 AND centro=:centro
    ) as a GROUP BY a.fecha, a.vehiculo  
) AS b ON a.vehiculo = b.vehiculo	
) AS c LEFT JOIN 
(
SELECT 
a.vehiculo,a.ruta,a.fecha_plan,a.inicio,a.fin,
IF(a.fin='0000-00-00 00:00:00',TIMESTAMPDIFF(MINUTE,a.inicio,a.hora_actual)/60,TIMESTAMPDIFF(MINUTE,a.inicio,a.fin)/60) as TieReal 
FROM (
SELECT vehiculo,ruta,fecha_plan,MIN(salida_cd) as inicio, MAX(llegada_cd) as fin, DATE_SUB(NOW(), INTERVAL 5 HOUR) AS hora_actual
FROM `t77_rs_ruta_sif` 
WHERE fecha_plan='$fechaselec' AND centro=:centro 
GROUP BY  vehiculo,ruta,fecha_plan
    ) AS a
) AS d ON c.vehiculo = d.vehiculo
) AS z ON v.Vehiculo = z.vehiculo GROUP BY v.Fecha,z.empresa
		";
        $select=$db->prepare($sql);
		$select->bindValue('centro',$idcentro);
		$select->execute();
		$n=1;
		while ($rows=$select->fetch()) {
		?>
		<tr>
		<td><?php echo $n; ?></td>
		<td><?php echo $rows['empresa']; ?></td>
		<td><?php echo round($rows['CjsPro']); ?></td>
		<td><?php echo round($rows['CjsEnt']); ?></td>
		<td><?php echo $rows['PocProg']; ?></td>
		<td><?php echo $rows['PocEnt']; ?></td>
		<td class="table-warning"><?php echo round($rows['Refusales'],2); ?>%</td>
		<td class="table-warning"><?php echo round($rows['PocModEfec'],2); ?>%</td>
        <td><?php echo round($rows['TiePlan'],2); ?> Hr</td>
		<td><?php echo round($rows['TieReal'],2); ?> Hr</td>
        <td class="table-warning"><?php echo round($rows['DesvTie'],2); ?>%</td> 		
		<td><?php echo round($rows['DistPlan'],2); ?> Km</td> 
        <td><?php echo round($rows['DistReal'],2); ?> Km</td>
        <td class="table-warning"><?php echo round($rows['DesvDist'],2); ?>%</td> 		
 		</tr>	
		<?php 
		$n++;	
		}
		Db::desconectar();
	?>
	</tbody>
	</table>
	</div>
<?php 	
}
function ejecucion_de_reparto_dia($fechaselec)
{ global $fecha,$idcentro,$aid,$crudex;	
?>	
	<div class="table-responsive">
	 <table id="ejecucionderepartodia"  data-order='[[ 0, "asc" ]]'
     class="display compact cell-border">
	<thead>
    <tr>
	  <th>#</th>
	  <th>Fecha</th>
	  <th>CjsPro</th>
	  <th>CjsEnt</th>
	  <th>PocProg</th>
	  <th>PocEnt</th>
	  <th>Refusales</th>
	  <th>Modulacion</th>
	  <th>TiePlan</th>
	  <th>TieReal</th>
	  <th>DesvTiem</th>
	  <th>DistPlan</th>
	  <th>DistReal</th>
	  <th>DesvDist</th>
    </tr>
	</thead>
	<tbody>
  <?php		
		$db=Db::conectar();
		$sql ="
SELECT v.Fecha, 
sum(v.CjsPro) as CjsPro,
sum(v.CjsEnt) as CjsEnt,
sum(v.PocProg) as PocProg,
sum(v.PocEnt) as PocEnt,
avg(z.TiePlan) as TiePlan,
avg(z.TieReal) as TieReal,
avg(z.DistPlan) as DistPlan,
avg(z.DistReal) as DistReal,
sum(v.PocAler) as PocAler,
sum(v.PocRech) as PocRech,
((sum(v.CjsPro)-sum(v.CjsEnt))/sum(v.CjsPro))*100 as Refusales,
((sum(v.PocAler) - sum(v.PocRech))/sum(v.PocAler))*100 as PocModEfec, 
(ABS(avg(z.TieReal)-avg(z.TiePlan))/avg(z.TiePlan)) AS DesvTie,
(ABS(avg(z.DistReal)-avg(z.DistPlan))/avg(z.DistPlan)) AS DesvDist  
FROM 
(
SELECT a.Fecha, a.Ruta, a.Vehiculo, 
sum(a.CjsPro) as CjsPro, sum(a.CjsEnt) as CjsEnt, sum(a.PocProg) as PocProg, 
sum(a.PocEnt) as PocEnt, sum(a.PocAler) as PocAler,  sum(a.PocRech) as PocRech  
FROM (
SELECT 
Fecha,Ruta,Vehiculo,Entrega As CjsPro, if(entregado=1,Entrega,0) as CjsEnt, 1 as PocProg, if(entregado=1,1,0) As PocEnt, if(alerta=1,1,0) As PocAler, if(rechazo=1,1,0) As PocRech  
FROM `t77_rs` WHERE centro=:centro AND Fecha='$fechaselec'
     ) AS a GROUP BY a.Fecha, a.Ruta, a.Vehiculo
) AS v 
LEFT JOIN (
SELECT 
c.ruta,c.empresa,c.vehiculo,c.fecha_plan,c.TiePlan,c.DistPlan,c.DistReal,d.TieReal
FROM 
(
SELECT 
a.ruta,a.empresa,a.vehiculo,a.fecha_plan,a.TiePlan,a.DistPlan,if(b.km_recorrido is null,0,b.km_recorrido) as DistReal
FROM 
(
SELECT ruta,empresa,vehiculo,fecha_plan,sum(tiempo_horas) as TiePlan, sum(distancia) as DistPlan  
	FROM `plan_resumen` 
WHERE centro=:centro AND fecha_plan='$fechaselec' GROUP BY ruta,empresa,vehiculo,fecha_plan
) AS a LEFT JOIN 
(
SELECT a.fecha, UPPER(a.vehiculo) AS vehiculo, sum(a.inicial) as km_inicial, sum(a.final) as  km_final,  
IF(SUM(a.inicial)=0,0,IF(sum(a.final)=0,0,(sum(a.final)-sum(a.inicial)))) AS km_recorrido
FROM (
SELECT CAST(respuesta_user AS DECIMAL) AS inicial,0 as final, user_registro AS vehiculo,fecha 
FROM `exa_detalle_user` WHERE id_tema = 2147483647 AND fecha='$fechaselec' AND tipo_pregunta=5 AND centro=:centro
UNION ALL
SELECT 0 as inicial, CAST(respuesta_user AS DECIMAL) AS final,user_registro AS vehiculo,fecha 
FROM `exa_detalle_user` WHERE id_tema = 359282242 AND fecha='$fechaselec' AND tipo_pregunta=5 AND centro=:centro
    ) as a GROUP BY a.fecha, a.vehiculo 
) AS b ON a.vehiculo = b.vehiculo	
) AS c LEFT JOIN 
(
SELECT 
a.vehiculo,a.ruta,a.fecha_plan,a.inicio,a.fin,
IF(a.fin='0000-00-00 00:00:00',TIMESTAMPDIFF(MINUTE,a.inicio,a.hora_actual)/60,TIMESTAMPDIFF(MINUTE,a.inicio,a.fin)/60) as TieReal 
FROM (
SELECT vehiculo,ruta,fecha_plan,MIN(salida_cd) as inicio, MAX(llegada_cd) as fin, DATE_SUB(NOW(), INTERVAL 5 HOUR) AS hora_actual
FROM `t77_rs_ruta_sif` 
WHERE fecha_plan='$fechaselec' AND centro=:centro 
GROUP BY  vehiculo,ruta,fecha_plan
    ) AS a
) AS d ON c.vehiculo = d.vehiculo
) AS z ON v.Vehiculo = z.vehiculo GROUP BY v.Fecha
		";
        $select=$db->prepare($sql);
		$select->bindValue('centro',$idcentro);
		$select->execute();
		$n=1;
		while ($rows=$select->fetch()) {
		?>
		<tr>
		<td><?php echo $n; ?></td>
		<td><?php echo $rows['Fecha']; ?></td>
		<td><?php echo round($rows['CjsPro']); ?></td>
		<td><?php echo round($rows['CjsEnt']); ?></td>
		<td><?php echo $rows['PocProg']; ?></td>
		<td><?php echo $rows['PocEnt']; ?></td>
		<td class="table-warning"><?php echo round($rows['Refusales'],2); ?>%</td>
		<td class="table-warning"><?php echo round($rows['PocModEfec'],2); ?>%</td>
        <td><?php echo round($rows['TiePlan'],2); ?> Hr</td>
		<td><?php echo round($rows['TieReal'],2); ?> Hr</td>
        <td class="table-warning"><?php echo round($rows['DesvTie'],2); ?>%</td> 		
		<td><?php echo round($rows['DistPlan'],2); ?> Km</td> 
        <td><?php echo round($rows['DistReal'],2); ?> Km</td>
        <td class="table-warning"><?php echo round($rows['DesvDist'],2); ?>%</td> 		
 		</tr>	
		<?php 
		$n++;	
		}
		Db::desconectar();
	?>
	</tbody>
	</table>
	</div>
<?php 	
}
switch ($hc):
    case "cvs-excel":
	?>
	<div class="list-group">
	<li class="list-group-item list-group-item-danger active disabled" aria-current="true" >Exportar XLS / CSV</li>
	<a class="list-group-item list-group-item-action" data-toggle="modal" data-target="#myMSJ1" >SA Group Text Lite - MPILCOSA</a>
	<a class="list-group-item list-group-item-action" data-toggle="modal" data-target="#myMSJ2" >Multi SMS Sender (MSS) - MPILCOSA</a>
	<a class="list-group-item list-group-item-action" data-toggle="modal" data-target="#myModalFechaSFObed" >Csv-Sf-Dia-TodosCDs - OBED</a>
	</div>
		<!-- Modal INICIO-->
	<div class="modal fade" id="myModalFechaSFObed" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
	<div class="modal-content">
	<div class="modal-header">
	<h5 class="modal-title">Seleccionar Fecha</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
    </button>
	</div>
	<div class="modal-body">
	<form method="GET" action="csv-excel.php" >
	<div class="form-row">	
	<input  aria-label="First name" id="fechastema" class="form-control" value="<?php echo $fechaselec; ?>" placeholder="Fecha_inicio" type="date" name="fecha_inicio"> 
	<input type="hidden" name="hc" value="AlertarSalesForceObed">
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
			<!-- Modal INICIO-->
	<div class="modal fade" id="myMSJ2" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
	<div class="modal-content">
	<div class="modal-header">
	<h5 class="modal-title">Seleccionar Centro</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
    </button>
	</div>
	<div class="modal-body">
	<form method="GET" action="csv-excel" >
	<div class="form-row">	
    <select class="form-control" name="centro" required >
	<option value="ALL">Todos</option>
			<?php		
		    $db=Db::conectar();
		    $sql ="SELECT centro, COUNT(Codigo) AS Regs  FROM t77_rs WHERE Fecha=:Fecha GROUP BY centro";
            $select=$db->prepare($sql);
			$select->bindValue('Fecha',$fecha);
		    $select->execute();
			while ($regis=$select->fetch()) {
			if ($regis['centro'] == $_GET['centro']){
			echo '<option  value="'.$regis['centro'].'" selected >'.$regis['centro'].' ('.$regis['Regs'].')</option>';		
			}else {
			echo '<option  value="'.$regis['centro'].'" >'.$regis['centro'].' ('.$regis['Regs'].')</option>';	
			}
			}
			?>
	</select>
	<input type="hidden" name="hc" value="Msj2ClientesMPILCOSA">
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
			<!-- Modal INICIO-->
	<div class="modal fade" id="myMSJ1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
	<div class="modal-content">
	<div class="modal-header">
	<h5 class="modal-title">Seleccionar Centro</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
    </button>
	</div>
	<div class="modal-body">
	<form method="GET" action="csv-excel" >
	<div class="form-row">	
    <select class="form-control" name="centro" required >
	<option value="ALL">Todos</option>
			<?php		
		    $db=Db::conectar();
		    $sql ="SELECT centro, COUNT(Codigo) AS Regs  FROM t77_rs WHERE Fecha=:Fecha GROUP BY centro";
            $select=$db->prepare($sql);
			$select->bindValue('Fecha',$fecha);
		    $select->execute();
			while ($regis=$select->fetch()) {
			if ($regis['centro'] == $_GET['centro']){
			echo '<option  value="'.$regis['centro'].'" selected >'.$regis['centro'].' ('.$regis['Regs'].')</option>';		
			}else {
			echo '<option  value="'.$regis['centro'].'" >'.$regis['centro'].' ('.$regis['Regs'].')</option>';	
			}
			}
			Db::desconectar();
			?>
	</select>
	<input type="hidden" name="hc" value="MsjClientesMPILCOSA">
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
	<?php 
        break;
    case "asistenciarutinat2":
		
	?>
	<div class="row border">
    <div class="col-sm-12 bg-danger">
	<div class="d-flex justify-content-center">
	<div class="p-2 bd-highlight"><div class="text-white text-md-center font-weight-bolder">Asistencia rutina <?php echo $idcentro.'-'.$fechaselec; ?></div></div>
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
	<form method="POST" action="reportesT2?hc=asistenciarutinat2" >
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
	<?php
	participacionrutinat2($fechaselec);
	participacionrutinat2detalle($fechaselec);
        break;
	case "ejecuciondereparto":
	?>
	<!-- Modal Inicio-->
	<div class="modal fade" id="myModalFechaRep" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
	<form method="GET" action="reportesT2?hc=ejecuciondereparto" >
	<div class="form-row">	
	<input  aria-label="First name" id="fechastema" class="form-control" value="<?php echo $fechaselec; ?>" placeholder="Fecha inicio" type="date" name='fechaselec'> 
	</div>
	<input type="hidden" name="hc" value="ejecuciondereparto">
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
	<div class="row border">
    <div class="col-sm-12 bg-danger">
	<div class="d-flex justify-content-center">
	<div class="p-2 bd-highlight"><div class="text-white text-md-center font-weight-bolder">Dashboard Ejecucion de Reparto-<?php echo $idcentro.'-'.$fechaselec; ?></div></div>
	<div class="p-2 bd-highlight"><button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#myModalFechaRep">Fecha</button></div>
	</div>
	</div>
	</div>
	<div class="row border">
    <div class="col-sm-12">
<?php ejecucion_de_reparto_dia($fechaselec); ?>
	</div>
	</div>
	<div class="row border">
    <div class="col-sm-12">
<?php ejecucion_de_reparto_empresario($fechaselec); ?>
	</div>
	</div>	
	<div class="row border">
    <div class="col-sm-12">
<?php ejecucion_de_reparto($fechaselec); ?>
	</div>
	</div>
	<?php 	
         break;
	case "licenciasconducir":
	?>
	<div class="row border">
    <div class="col-sm-12 bg-danger">
	<div class="d-flex justify-content-center">
	<div class="p-2 bd-highlight"><div class="text-white text-md-center font-weight-bolder">Control licencias conducir <?php echo $idcentro.'-'.$fechaselec; ?></div></div>
	</div>
	</div>
	</div>
	<div class="row border">
    <div class="col-sm-12">
<?php control_licencias(); ?>
	</div>
	</div>
	<?php 
         break;	 
	case "ModificaParametrosUser":
        break;		
	case "InsertarParametrosUser":
        break;			
	case "InicioParametrosUser":
        break;	
	case "listarcovid":  
        break;		 	
    default: 
	lista_reportes();
	endswitch;
	} else {
	echo $html_acceso;		
	}
	}
	require('../footer.php');
	ob_end_flush();	
	?>
