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
	$datatablesjsresponsive = 1;
	require('../head.php');
	if(isset($_GET['fechaselec'])): 
	$fecha_form = $_GET['fechaselec'];
	$fechars = $_GET['fechaselec'];	
	else:
	$fechars = $fechars; 
	$fecha_form = $fecha;
	endif;
	// fin head
	$actClasifEnv = 1;
	$hash='Xa6UYNOhEMOu5OUPtqaGAUiflsig';	
	if (isset($_GET['FMfcgxwLtkdRLdXFNVNgdJkHCHKrmRpl'])): 
	$FMfcgxwLtkdRLdXFNVNgdJkHCHKrmRpl = $_GET['FMfcgxwLtkdRLdXFNVNgdJkHCHKrmRpl']; 
	else:
	$FMfcgxwLtkdRLdXFNVNgdJkHCHKrmRpl = ""; 
	endif;
	$enlace = $sitio.'modulos/'.$accesos;
	function tabla_reporte_diario(){
		global $fechars,$idcentro;
		$where='';
    ?>
	<div class="row">
    <div class="col-sm-12 bg-dark">
	<div class="d-flex justify-content-center">
	<div class="p-2"><div class="text-white text-md-center font-weight-bolder">Indicadores Diarios <?php echo $idcentro.'-'.$fechars; ?></div></div>
	<div class="p-2"><button type="button" class="btn btn-warning btn-sm" data-toggle="modal" disabled data-target="#myModalFecha">Fecha</button></div>
	</div>
	</div>
	</div>	
	<div class="table-responsive">
		<?php 
		  $n=0; 
		  $dbr=Db::conectar();
		  $sqlr ="
SELECT 1 as MRplan, 100 as ModPlan, ((v.CjsPro*1)/100) as CjsRechPlan, v.Fecha,z.empresa,v.Ruta,v.Vehiculo,v.CjsPro,v.CjsEnt,v.PocProg,v.PocEnt,z.TiePlan,z.TieReal,z.DistPlan,z.DistReal,v.PocAler,v.PocRech,
v.CjsRech,
((v.CjsRech/v.CjsPro)*100) as Refusales,
((v.PocAler - v.PocRech)/v.PocAler)*100 as PocModEfec,
(ABS(z.TieReal-z.TiePlan)/z.TiePlan) AS DesvTie,
(ABS(z.DistReal-z.DistPlan)/z.DistPlan) AS DesvDist 
FROM
(
SELECT a.Fecha, a.Ruta, a.Vehiculo,
sum(a.CjsPro) as CjsPro, sum(a.CjsEnt) as CjsEnt, sum(a.CjsAler) as CjsAler, sum(a.CjsRech) as CjsRech,
sum(a.PocProg) as PocProg, sum(a.PocEnt) as PocEnt, sum(a.PocAler) as PocAler,  sum(a.PocRech) as PocRech 
FROM (
SELECT
Fecha,Ruta,Vehiculo,Entrega As CjsPro,
if(entregado=1,Entrega,0) as CjsEnt,
if(alerta=1,Entrega,0) as CjsAler,
if(rechazo=1,cjasrechazadas,0) as CjsRech,
1 as PocProg,
if(entregado=1,1,0) As PocEnt,
if(alerta=1,1,0) As PocAler,
if(rechazo=1,1,0) As PocRech 
FROM `t77_rs` WHERE centro=:centro AND Fecha=:Fecha $where
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
WHERE centro=:centro AND fecha_plan=:fecha_plan GROUP BY ruta,empresa,vehiculo,fecha_plan
) AS a LEFT JOIN
(
SELECT a.fecha, UPPER(a.vehiculo) AS vehiculo, sum(a.inicial) as km_inicial, sum(a.final) as  km_final, 
IF(SUM(a.inicial)=0,0,IF(sum(a.final)=0,0,(sum(a.final)-sum(a.inicial)))) AS km_recorrido
FROM (
SELECT CAST(respuesta_user AS DECIMAL) AS inicial,0 as final, user_registro AS vehiculo,fecha
FROM `exa_detalle_user` WHERE id_tema = 2147483647 AND fecha=:fecha AND tipo_pregunta=5 AND centro=:centro
UNION ALL
SELECT 0 as inicial, CAST(respuesta_user AS DECIMAL) AS final,user_registro AS vehiculo,fecha
FROM `exa_detalle_user` WHERE id_tema = 359282242 AND fecha=:fecha AND tipo_pregunta=5 AND centro=:centro
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
WHERE fecha_plan=:fecha_plan AND centro=:centro
GROUP BY  vehiculo,ruta,fecha_plan
    ) AS a
) AS d ON c.vehiculo = d.vehiculo
) AS z ON v.Vehiculo = z.vehiculo   		  
		  ";
          $selectr=$dbr->prepare($sqlr);
		  $selectr->bindValue('centro',$idcentro);
		  $selectr->bindValue('fecha_plan',$fechars);
		  $selectr->bindValue('fecha',$fechars);
		  $selectr->bindValue('Fecha',$fechars);
		  $selectr->execute();
          while ($rpr=$selectr->fetch()){
		  $n++;	  
	?> 
<table class="table table-sm table-striped table-hover border">
  <thead>
    <tr class="bg-danger text-white">
      <th scope="col"><small><?php echo $rpr['Ruta'].''.$rpr['Vehiculo']; ?></small></th>
      <th scope="col"><small>Plan</small></th>
      <th scope="col"><small>Real</small></th>
      <th scope="col"><small>Delta</small></th>
	  <th scope="col"><small>Coment</small></th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th scope="row"><small>Cajas Rechazadas</small></th>
      <td><small><?php echo round($rpr['CjsRechPlan']); ?></small></td>
      <td><small><?php echo round($rpr['CjsRech']); ?></small></td>
      <td><small><?php echo round($rpr['CjsRech']-$rpr['CjsRechPlan']); ?></small></td>
      <td></td>
    </tr>
    <tr>
      <th scope="row"><small>MR %</small></th>
      <td><small><?php echo $rpr['MRplan']; ?>%</small></td>
      <td><small><?php echo round($rpr['Refusales'],1); ?>%</small></td>
      <td><small><?php echo round($rpr['Refusales']-$rpr['MRplan'],1); ?>%</small></td>
      <td><a href="#myModalMR" class="badge badge-danger" data-toggle="modal" data-target="#myModalMR" disabled >Add</a></td>
    </tr>
    <tr>
      <th scope="row"><small>Desv. KM</small></th>
      <td><small><?php echo round($rpr['DistPlan']); ?></small></td>
      <td><small><?php echo $rpr['DistReal']; ?></small></td>
      <td><small><?php echo round(($rpr['DistReal']-$rpr['DistPlan'])); ?></small></td>
      <td><a href="#myModalKM" class="badge badge-danger" data-toggle="modal" data-target="#myModalKM" disabled >Add</a></td>
    </tr>
    <tr>
      <th scope="row"><small>Desv. Tiempo</small></th>
      <td><small><?php echo round($rpr['TiePlan'],2); ?></small></td>
      <td><small><?php echo round($rpr['TieReal'],2); ?></small></td>
      <td><small><?php echo round(($rpr['TieReal']-$rpr['TiePlan']),2); ?></small></td>
      <td><a href="#myModalTIEM" class="badge badge-danger" data-toggle="modal" data-target="#myModalTIEM" >Add</a></td>
    </tr>
    <tr>
      <th scope="row"><small>Eficiencia Modulación</small></th>
      <td><small><?php echo round($rpr['ModPlan'],1); ?>%</small></td>
      <td><small><?php echo round($rpr['PocModEfec'],1); ?>%</small></td>
      <td><small><?php echo round(($rpr['PocModEfec']-$rpr['ModPlan']),1); ?>%</small></td>
      <td><a href="#myModalMOD" class="badge badge-danger" data-toggle="modal" data-target="#myModalMOD" >Add</a></td>
    </tr>
    <tr>
    <td colspan="5">
	<form  onsubmit="return confirm('Esta seguro de enviar la información');" name="conductor" method='POST' action='transportes?l=seg&amp;fecha=<?php echo $fechars; ?>&amp;ruta=<?php echo $rpr['Ruta']; ?>'>
	<input type='hidden' name='segkpi' value='DfXdz2htPH0lsSSs5nCTpuj'>
	<button class="btn btn-dark btn-sm" disabled >Enviar</button>
	</form>	  
	</td>  
    </tr>	
  </tbody>
</table>
	<?php
	}
	Db::desconectar();
	?>
	</div>	
	<?php	
	}
	function modal_reporte_diario($titulo,$selectSQL,$SelectVal,$action){
     ?>
	<div class="modal fade" id="myModal<?php echo $titulo; ?>" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
	<div class="modal-content">
	<div class="modal-header">
	<h5 class="modal-title">Motivo <?php echo $titulo; ?></h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
    </button>
	</div>
	<div class="modal-body">
	<form method="get" action="">
  <div class="form-group">
    <label for="formGroupExampleInput">Cliente</label>
    <input type="text" class="form-control form-control-sm" id="formGroupExampleInput" placeholder="codigo">
  </div>
	 <div class="form-group"> 	
       <label for="text">Motivo</label>	 
			<select class="form-control form-control-sm" id="validationCustom02" required name="mr">
			<option value="">Seleccionar</option>
			<?php		
			$db=Db::conectar();
			$select=$db->prepare($selectSQL);
			$select->execute();
			while ($regis=$select->fetch()) {
			if ($regis['descripcion'] == $SelectVal){
			echo '<option  value="'.$regis['descripcion'].'" selected >'.$regis['descripcion'].'</option>';		
			}else {
			echo '<option  value="'.$regis['descripcion'].'" >'.$regis['descripcion'].'</option>';	
			}
			}
			Db::desconectar();
			?>
			</select>
	<input type="hidden" name="l" value="seg">
	</div>
	<div class="modal-footer">
	<button type="submit" class="btn btn-secondary btn-lg btn-block" disabled>Guardar</button>
	<button type="button"  class="btn btn-danger btn-lg btn-block" data-dismiss="modal">Cerrar</button>
	</div>
	</form>
	</div>
	</div>
	</div>
	</div> 
	 <?php	 
	}	
	function modal_entregar($indx,$id,$actClasifEnv,$cjsEntrega,$ruta,$fecha,$viaje){
		?> 
		<div class="modal fade" id="entregar_ped" tabindex="-1" aria-labelledby="rechazar_pedLabel" aria-hidden="true">
	<div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title" id="exampleModalLabel">Confirmar entrega pedido</h6>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>  
      <div class="modal-body">
	  <form class="needs-validation" novalidate action="transportes?st=det&amp;ruta=<?php echo $ruta; ?>&amp;fecha=<?php echo $fecha; ?>&amp;viaje=<?php echo $viaje; ?>" method="get"> 
	    <input type="hidden" name="IdEntrega" value="<?php echo $id;?>">
		 <input type="hidden" name="CjasEntregadas" value="<?php echo $cjsEntrega; ?>">
	<?php if($actClasifEnv == 1){ ?>	 
		<div class="form-group">
        <label for="validationCustom02">Clasifica envases</label>
			<select class="custom-select" id="validationCustom02" name="ClasificaEnvases" required>  
			<option value="" >--</option>
			<?php		
			$db=Db::conectar();
			$selectclasenv=$db->prepare("SELECT id, descripcion FROM t77_rs_check");
			$selectclasenv->execute();
			while ($registclasenv=$selectclasenv->fetch()) {
			if ($registclasenv['id'] == ''){
			echo '<option  value="'.$registclasenv['id'].'" selected >'.$registclasenv['descripcion'].'</option>';		
			}else {
			echo '<option  value="'.$registclasenv['id'].'" >'.$registclasenv['descripcion'].'</option>';	
			}
			}
			Db::desconectar();
			?>
		   </select>
		</div>		
		<div class="form-group">
		<label for="validationCustom01">Cajas clasificadas</label>
		<input type="text" class="form-control" id="validationCustom01" value="" name="CjasClasificadas" required>
		</div>
	<?php } else {
        ?>
	    <input type="hidden" value="" name="ClasificaEnvases" required>
		<input type="hidden" value="0" name="CjasClasificadas" required>
		<?php
	  } 
	?>	 		
      </div>
      <div class="modal-footer">
	    <input type="hidden" name="FMfcgxwLtkdRLdXFNVNgdJkHCHKrmRpl" value="EntregarPedido">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-primary">Entregar</button>
      </div>
	  </form>
    </div>
	</div>
	</div>	
	<?php 	
	}
	function modal_entregar_todos($indx,$ruta,$fecha,$viaje){
	?> 
<div class="modal fade" id="entregar_ped_todos" tabindex="-1" aria-labelledby="entregar_ped_todosLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Confirmar todas las entregas !</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
<div class="alert alert-warning" role="alert">
  <h4 class="alert-heading">Estas seguro !</h4>
  <p>Se confirmaran todas las entregas, exepto lo alertado y rechazado...</p>
  <hr>
  <p class="mb-0">...</p>
</div>
      </div>
	<form method="get">  
      <div class="modal-footer">
	    <input type="hidden" name="FMfcgxwLtkdRLdXFNVNgdJkHCHKrmRpl" value="DetalleRuta">
		<input type="hidden" name="ruta" value="<?php echo $ruta; ?>">
		<input type="hidden" name="fecha" value="<?php echo $fecha; ?>">
		<input type="hidden" name="viaje" value="<?php echo $viaje; ?>">
		<input type="hidden" name="EntregarTodos" value="cHNKXCgPVRsxZwjjgxGwXz">		
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary">Guardar los cambios</button>
      </div>
	</form>  
    </div>
  </div>
</div>
	<?php 	
	}
	function modal_eta($id,$ruta,$fecha,$viaje,$Llega,$nombre)
	{
	?> 
<div class="modal fade" id="modalETA<?php echo $id; ?>" tabindex="-1" aria-labelledby="modalETA" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modificar tiempo estimado de arribo</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
	<form method="GET" id="ETA" name="ETA">   
      <div class="modal-body">
	  <h6><?php echo $nombre; ?></h6>
	    <div class="form-group">
     <label>LLega : <?php echo $Llega; ?>  modificar a : </label>
     <input type="time" name="Llega" value="<?php echo $Llega; ?>">
		</div>
      </div>
      <div class="modal-footer">
	    <input type="hidden" name="FMfcgxwLtkdRLdXFNVNgdJkHCHKrmRpl" value="updateETA">
		<input type="hidden" name="eta" value="<?php echo $id; ?>">
		<input type="hidden" name="ruta" value="<?php echo $ruta; ?>">
		<input type="hidden" name="fecha" value="<?php echo $fecha; ?>">
		<input type="hidden" name="viaje" value="<?php echo $viaje; ?>">		
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-warning">Guardar los cambios</button>
      </div>
	</form>  
    </div>
  </div>
</div>
	<?php 	
	}	
	function modal_rechazar($indx,$id,$mr,$comentario,$autorizaRech,$CjsEntrega){
		?>
<div class="modal fade" id="rechazar_ped" tabindex="-1" aria-labelledby="rechazar_pedLabel" aria-hidden="true">
	<div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title" id="exampleModalLabel">Rechazar </h6>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>  
      <div class="modal-body">
	  <form class="needs-validation" novalidate method="get"> 
	    <input type="hidden" name="idrechazo" value="<?php echo $id;?>">
		<input type="hidden" name="confirmarrchz" value="Rechazar Pedido">
		<input type="hidden" name="grabaralerta" value="grabaralerta">
		 <input type="hidden" name="indx" value="<?php ;?>">
		  <input type="hidden" name="Fecha" value="<?php  ;?>">
		   <input type="hidden" name="FMfcgxwLtkdRLdXFNVNgdJkHCHKrmRpl" value="GrabarRechazo">		
	    <input type="hidden" value="0" name="ClasificaEnvRec">
		<input type="hidden" value="0" name="CjasClasifRech">
		<div class="form-group">
		<label for="validationCustom01">Motivo rechazo</label>
		<select class="form-control form-control-sm" id="validationCustom02" required name="mr">
			<option value="">Seleccionar</option>
			<?php		
			$db=Db::conectar();
			$select=$db->prepare("SELECT id, descripcion FROM t77_mr WHERE st=1");
			$select->execute();
			while ($regis=$select->fetch()) {
			if ($regis['descripcion'] == $mr){
			echo '<option  value="'.$regis['descripcion'].'" selected >'.$regis['descripcion'].'</option>';		
			}else {
			echo '<option  value="'.$regis['descripcion'].'" >'.$regis['descripcion'].'</option>';	
			}
			}
			Db::desconectar();
			?>
		</select>
		</div>		
		<div class="form-group">
		<label for="validationCustom01">Comentario rechazo</label>
		<textarea name="comentarios" class="form-control form-control-sm" id="validationCustom03" required ><?php echo $comentario;?></textarea>
		</div>			
		<div class="form-group">
        <label for="validationCustom02">Quien autoriza rechazo</label>
	  	<select class="custom-select" id="validationCustom02" required name="autoriza_rech">
			<option value=""><--Quien autoriza rechazo--></option>
			<?php		
			$db=Db::conectar();
			$select=$db->prepare("SELECT id, descripcion FROM t77_autoriza_rech");
			$select->execute();
			while ($regist=$select->fetch()) {
				
			if ($regist['descripcion'] == $autorizaRech){
			echo '<option  value="'.ucwords($regist['descripcion']).'" selected >'.ucwords($regist['descripcion']).'</option>';		
			}else {
			echo '<option  value="'.ucwords($regist['descripcion']).'" >'.ucwords($regist['descripcion']).'</option>';	
			}
			}
			Db::desconectar();
			?>
		</select>
		</div>	
		<div class="form-group">
		<label for="validationCustom01">Cajas Programadas</label>
		<input type="text" class="form-control" value="<?php echo $CjsEntrega;?>" disabled >
		<input type="hidden" class="form-control" value="<?php echo $CjsEntrega;?>"  name="CjsEntrega" >
		</div>
		<div class="form-group">
		<label for="validationCustom01">Cajas rechazadas/modificadas</label>
		<input type="text" class="form-control" id="validationCustom01" value="" required name="cjasrechazadas" >
	     <small id="emailHelp" class="form-text text-muted">El rechazo no deve ser mayor a lo programado</small>
		</div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-primary">Grabar</button>
      </div>
	  </form>
    </div>
	</div>
	</div>		
		<?php
	}
	function rutas__(){
		global $idcentro,$fechars;
	?>
	<div class="row">
    <div class="col-sm-12 p-0 bg-light">	
	<div class="d-flex">
	<div class="p-2 bg-light"><div class="text-muted text-md-left font-weight-bolder">Resumen Por Zonas <?php echo $idcentro.' | '.$fechars; ?></div></div>
	</div>
	</div>
	</div>
	<div class="row">
    <div class="col-sm-12 p-0">	
	<div class="table-responsive">
	 <table id="transportesresponsive"  data-order='[[ 1, "asc" ]]' data-page-length='25'
     class="display compact cell-border">
		<thead>
		<tr>
		    <th></th>
		    <th>Ruta|Vj|Vehiculo</th>
			<th>Cjas</th>
			<th>Cont</th>
			<th>#Alertas</th>
			<th>#Rechazos</th>
			<th>#Entregados</th>
			<th>%Avance</th>
			<th></th>
		</tr>
		</thead>
		<tbody>
		<?php 
		  $db=Db::conectar();
		  $sql ="
SELECT * FROM 
(
SELECT z.id_ruta,z.`Ruta`,z.`Viaje`,z.`Vehiculo`,z.`FechaRS`,
round (SUM(z.CajasProgramadas)) as CajasProgramadas,
SUM(z.st_kpi) AS st_kpi,
SUM(z.ContactosEntregados) as ContactosEntregados,
SUM(z.ContactosRechazados) as ContactosRechazados,
SUM(z.ContactosAlertados) as ContactosAlertados,
SUM(z.ContactosProgramados) as ContactosProgramados,
IF(SUM(z.ContactosEntregados)=0,0,((SUM(z.ContactosEntregados)+SUM(z.ContactosRechazados))/SUM(z.ContactosProgramados)*100)) as PorcientoAvance
FROM ( 
SELECT
CONCAT(`Vehiculo`,`Ruta`,`Viaje`,`Fecha`) AS id_ruta, 
1 as ContactosProgramados,
st_kpi,
`Codigo`,
`Ruta`,
`Vehiculo`,
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
	 ) AS z GROUP BY z.id_ruta,z.`Ruta`,z.`Viaje`,z.`Vehiculo`,z.`FechaRS`
) AS v LEFT JOIN 
(
SELECT * FROM `t77_rs_ruta_sif` WHERE centro = :centro AND fecha_plan = :fecha_plan
) AS s ON v.id_ruta = s.indx ORDER BY v.Ruta
		  ";
          $select=$db->prepare($sql);
		  $select->bindValue('centro',$idcentro);
		  $select->bindValue('Fecha',$fechars);
		  $select->bindValue('fecha_plan',$fechars);
		  $select->execute();
          while ($registro=$select->fetch()){
	    $stkpi = $registro['st_kpi']; 
        $idruta=$registro['id_ruta'];
		$LinkHora = '#';
		$idhr=$registro['id'];
		$FechaRS=$registro['FechaRS'];
		$Ruta=$registro['Ruta'];
		$Viaje=$registro['Viaje'];
		$Vehiculo=$registro['Vehiculo']; 
		$inicio_conductor=$registro['inicio_conductor'];
		$salida_cd=$registro['salida_cd'];
        $llegada_cd=$registro['llegada_cd'];
        $ingreso_cd=$registro['ingreso_cd'];
        $fin_conductor=$registro['fin_conductor'];
		if($stkpi>=1){ $ckpi = 'success'; } else { $ckpi = 'danger'; }
		if(IS_NULL($inicio_conductor)){
		//echo  'si'; 
		$LinkHora1='transportes?FMfcgxwLtkdRLdXFNVNgdJkHCHKrmRpl=RegistraHora&fecha='.$FechaRS.'&ruta='.$Ruta.'&viaje='.$Viaje.'&vehiculo='.$Vehiculo.'';
		} else {
		//echo  'no';
    $LinkHora1='#';
	$hora = '0000-00-00 00:00:00';
	switch ($hora) {
    case (($salida_cd==$hora) && ($llegada_cd==$hora) && ($ingreso_cd==$hora) && ($fin_conductor==$hora)):
        $LinkHora2='transportes?FMfcgxwLtkdRLdXFNVNgdJkHCHKrmRpl=RegistraHora&fecha='.$FechaRS.'&ruta='.$Ruta.'&viaje='.$Viaje.'&vehiculo='.$Vehiculo.'&tb=salida_cd&id='.$idhr.'';
        break;
    case (($salida_cd!=$hora) && ($llegada_cd==$hora) && ($ingreso_cd==$hora) && ($fin_conductor==$hora)):
        $LinkHora3='transportes?FMfcgxwLtkdRLdXFNVNgdJkHCHKrmRpl=RegistraHora&fecha='.$FechaRS.'&ruta='.$Ruta.'&viaje='.$Viaje.'&vehiculo='.$Vehiculo.'&tb=llegada_cd&id='.$idhr.'';	
        break;	
    case (($salida_cd!=$hora) && ($llegada_cd!=$hora) && ($ingreso_cd==$hora) && ($fin_conductor==$hora)):
       $LinkHora4='transportes?FMfcgxwLtkdRLdXFNVNgdJkHCHKrmRpl=RegistraHora&fecha='.$FechaRS.'&ruta='.$Ruta.'&viaje='.$Viaje.'&vehiculo='.$Vehiculo.'&tb=ingreso_cd&id='.$idhr.'';	
        break;
    case (($salida_cd!=$hora) && ($llegada_cd!=$hora) && ($ingreso_cd!=$hora) && ($fin_conductor==$hora)):
        $LinkHora5='transportes?FMfcgxwLtkdRLdXFNVNgdJkHCHKrmRpl=RegistraHora&fecha='.$FechaRS.'&ruta='.$Ruta.'&viaje='.$Viaje.'&vehiculo='.$Vehiculo.'&tb=fin_conductor&id='.$idhr.'';	
        break;
	}
		}
	?> 
		<tr role="row">
		<td></td>
		<td><span class="badge badge-<?php echo $ckpi; ?>">&nbsp;</span> <?php echo $Ruta.'|'.$Viaje.'|'.$Vehiculo; ?></td>
		<td><?php echo $registro['CajasProgramadas']; ?></td>
		<td><?php echo $registro['ContactosProgramados']; ?></td>	
		<td><?php echo $registro['ContactosAlertados']; ?></td>
		<td><?php echo $registro['ContactosRechazados']; ?></td>
		<td><?php echo $registro['ContactosEntregados']; ?></td>	
		<td><?php echo round($registro['PorcientoAvance']); ?>%</td>	
		<td>
		<button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#Tiem<?php echo $idruta; ?>">Tiempos</button>
		<button class="btn btn-danger btn-sm" onclick="location.href='transportes?FMfcgxwLtkdRLdXFNVNgdJkHCHKrmRpl=DetalleRuta&amp;ruta=<?php echo $registro['Ruta']; ?>&amp;fecha=<?php echo $registro['FechaRS']; ?>&amp;viaje=<?php echo $registro['Viaje']; ?>';">Ver</button>
		</td>		
		</tr>
		<input type="hidden" name="id" value="<?php echo $registro['id'] ?>">
<div class="modal fade" id="Tiem<?php echo $idruta; ?>" tabindex="-1" aria-labelledby="tiempos" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><?php echo $Ruta.' - '.$Vehiculo.' Viaje '. $Viaje; ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>  
      <div class="modal-body">
	  <form   action="" method="post">	  
<div class="list-group">
  <a href="<?php echo isset($LinkHora1) ? $LinkHora1 : '#' ; ?>" class="list-group-item list-group-item-action <?php echo isset($LinkHora1) ?'':'disabled';?>"><h5 class="mb-1"> Inicio ruta </h5> <small><?php echo $inicio_conductor; ?></small></a>
  <a href="<?php echo isset($LinkHora2) ? $LinkHora2 : '#' ; ?>" class="list-group-item list-group-item-action <?php echo isset($LinkHora2) ?'':'disabled';?>"><h5 class="mb-1"> Salida CD </h5> <small><?php echo $salida_cd; ?></small></a>
  <a href="<?php echo isset($LinkHora3) ? $LinkHora3 : '#' ; ?>" class="list-group-item list-group-item-action <?php echo isset($LinkHora3) ?'':'disabled';?>"><h5 class="mb-1"> Llegada CD </h5> <small><?php echo $llegada_cd; ?></small></a>
  <a href="<?php echo isset($LinkHora4) ? $LinkHora4 : '#' ; ?>" class="list-group-item list-group-item-action <?php echo isset($LinkHora4) ?'':'disabled';?>"><h5 class="mb-1"> Ingreso CD </h5> <small><?php echo $ingreso_cd; ?></small></a>
  <a href="<?php echo isset($LinkHora5) ? $LinkHora5 : '#' ; ?>" class="list-group-item list-group-item-action <?php echo isset($LinkHora5) ?'':'disabled';?>"><h5 class="mb-1"> Fin ruta </h5> <small><?php echo $fin_conductor; ?></small></a>
</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">Cancelar</button>
      </div>
	  </form>
    </div>
  </div>
</div>	
<?php
	}
	Db::desconectar();
?>
	</tbody>
		</table>
		</div>
	</div>
    </div>	
	<?php
	}
	function rutas__detalle($fecha,$ruta,$viaje){
		global $idcentro,$hash;
	?> 	
	<form method="post" id="ruta_detalle">	
	<div class="row">
    <div class="col-sm-12 p-0 bg-light">	
	<div class="d-flex">
	<div class="p-2 bg-light"><div class="text-muted text-md-left font-weight-bolder"><?php echo $ruta.'|'.$viaje.'|'.$fecha;?>
	<button type="submit" class="btn btn-danger btn-sm" name="encuestarepaso">Grabar</button>
	<button type="button" class="btn btn-danger btn-sm" onclick="location.href='transportes?&fecha=<?php echo $fecha; ?>&e=<?php echo $hash; ?>';">Cancelar</button>
	<button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#entregar_ped_todos">Entregar todos</button>
	<button type="submit" class="btn btn-danger btn-sm"  name="encuestarepaso" id="boton_ck_env" readonly style="display:none" >Entregar selección</button>
	</div>
	</div>
	</div>
	</div>
	</div>
	<div class="row">
    <div class="col-sm-12 p-0">
	 <div class="table-responsive">
	 <table id="transclienresponsive"  data-order='[[ 0, "asc" ]]' data-page-length='50'
     class="display compact cell-border">
		<thead>
		<tr>
		    <th></th>
			<th>Cliente|Dirección</th>
			<th>Cj</th>
			<th>VH</th>
			<th>Codigo</th>
			<th>Repaso</th>
			<th>Encuesta</th>
			<th>Estado</th>
			<th class="table-warning">Cliente NPS</th>
			<th></th>
		</tr>
		</thead>
		<tbody>
		<?php 
		  $db=Db::conectar();
		  $sql ="
		  SELECT * FROM 
		  (
		SELECT * FROM t77_rs WHERE Ruta=:Ruta AND Fecha=:Fecha AND Viaje=:Viaje AND centro=:centro 
		  ) AS a LEFT JOIN 
		  (
		SELECT codcli,observacion FROM `t77_nps` WHERE  centro=:centro  
		  ) AS b  ON a.Codigo = b.codcli ORDER BY a.Sec1 ASC 
		  ";
          $select=$db->prepare($sql);
		  $select->bindValue('Ruta',$ruta);
		  $select->bindValue('Fecha',$fecha);
		  $select->bindValue('Viaje',$viaje);
		  $select->bindValue('centro',$idcentro);
		  $select->execute();
          while ($registro=$select->fetch()) {
        $Llega = substr ($registro['Llega'],0,5);		
        if (($registro['entregado']==1) and ($registro['alerta']==0) and ($registro['rechazo']==0)){ 
		$msjestado = "Entregado"; $class='class="table-success"'; $valuebutton=' '; $disabled='checked disabled'; $reprogramadodisb = 'disabled'; 
		} else if(($registro['entregado']==1) and ($registro['alerta']==1) and ($registro['rechazo']==0)) {
        $msjestado = "Entregado"; $class='class="table-warning"'; $valuebutton=' '; $disabled='checked disabled'; $reprogramadodisb = 'disabled';        
		} else if(($registro['entregado']==0) and ($registro['alerta']==1) and ($registro['rechazo']==1)) {
		$msjestado = "Rechazado"; $class='class="table-danger"';  $valuebutton=' '; $disabled='checked disabled'; $reprogramadodisb = 'disabled'; 		
		} else if(($registro['entregado']==0) and ($registro['alerta']==0) and ($registro['rechazo']==1)) {
		$msjestado = "Rechazado"; $class='class="table-danger"';  $valuebutton=' '; $disabled='checked disabled'; $reprogramadodisb = 'disabled'; 	
		} else if(($registro['entregado']==0) and ($registro['alerta']==1) and ($registro['rechazo']==0)) {
		$msjestado = "Alertado"; $class='class="table-warning"';  $valuebutton=' '; $disabled=''; $reprogramadodisb = '';
		} else if(($registro['entregado']==0) and ($registro['alerta']==0) and ($registro['rechazo']==0)) {
		$msjestado = "Pendiente"; $class='class="table-light"'; $valuebutton=' '; $disabled=''; $reprogramadodisb = '';
		}	 
		if(($registro['repaso']==1)) { $disabledcheckedEnt='checked disabled'; } else { $disabledcheckedEnt=''; }
		if(($registro['encuesta']=='')) { $disabledselect=''; } else {  $disabledselect='disabled'; }
		if(is_null($registro['codcli'])){ $classnps=""; }else{ $classnps='class="text-danger"'; }
	    if($registro['reprogramado']==1) {
		$iconclock = '<i class="fa fa-clock-o" aria-hidden="true"></i>';
		} else {
		$iconclock = '';
		}
	?>
    <tr <?php echo $class; ?> >
	<td></td>
	<td <?php echo $classnps; ?> ><?php echo $registro['Sec1'].substr($registro['Cliente'],0,25).'<br><small>'.$registro['Direccion'].'-'.$registro['Ciudad'].' - Llega '.$Llega.' '.$iconclock.'</small>'; ?></td>
	<td><input name="ck_enviar[<?php echo $registro['id']; ?>]"  type="checkbox" id="ck_enviar" class="form-check-input"  onChange="comprobar_check_enviar(this);" <?php echo $disabled; ?> > <?php echo number_format($registro['Entrega'], 1, '.', '')?></td>
    <td><?php echo substr($registro['Abre'],0,5).' '.substr($registro['Cierra'],0,5); ?></td>
	<td><?php echo $registro['Codigo']; ?></td>
	<td>&nbsp;&nbsp;&nbsp;<input type="checkbox" name="repaso[<?php echo $registro['id']; ?>]" class="form-check-input" id="exampleCheck1" <?php echo $disabledcheckedEnt; ?>></td>
	<td class="text-center">
	<div class="form-group">
		  	<select class="form-control form-control-sm"   name="encuesta[<?php echo $registro['id']; ?>]" <?php echo $disabledselect; ?> >
			<option value=""><--></option>
			<?php		
			$db=Db::conectar();
			$encuesta=$db->prepare("SELECT id_desc, descripcion FROM t77_pregunta_encuesta WHERE pregunta='clienteyape'");
			$encuesta->execute();
			while ($rowencuesta=$encuesta->fetch()) {
			if ($registro['encuesta'] == $rowencuesta['id_desc']){
			echo '<option  value="'.$rowencuesta['id_desc'].'" selected >'.$rowencuesta['descripcion'].'</option>';		
			}else {
			echo '<option  value="'.$rowencuesta['id_desc'].'" >'.$rowencuesta['descripcion'].'</option>';	
			}
			}
			Db::desconectar();
			?>
		   </select>
	</div>
	</form>
	</td>
	<td><?php echo $msjestado; ?></td>
	<td class="table-warning"><?php echo $registro['observacion']; ?></td>
	<td class="text-center" >
	<div class="btn-group btn-group-sm" role="group" aria-label="botones">
	<button type="button" class="btn btn-success btn-sm" onclick="location.href='transportes?FMfcgxwLtkdRLdXFNVNgdJkHCHKrmRpl=AlertarWS&amp;id=<?php echo $registro['id']; ?>';"><i class="fa fa-whatsapp"></i> <?php echo $valuebutton; ?></button>
	<button type="button" class="btn btn-danger btn-sm" onclick="location.href='encuestarepartos?exa=crear_examen_check&amp;cod=<?php echo $registro['Codigo']; ?>&amp;ruta=<?php echo $registro['Ruta']; ?>&amp;fecha=<?php echo $registro['Fecha']; ?>&amp;viaje=<?php echo $registro['Viaje']; ?>';">Enc</button>
	<button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modalETA<?php echo $registro['id']; ?>"  <?php echo $reprogramadodisb; ?> >ETA</button>
	</div>
	<?php
    if(($registro['entregado']==1) || ($registro['rechazo']==1)){	
	} else { 
		modal_eta($registro['id'],$registro['Ruta'],$registro['Fecha'],$registro['Viaje'],$Llega,$registro['Cliente']); 
	}
	?>
	</td>
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
	<?php		
	}
	function rutas__ws($id){
		global $idcentro,$hash,$actClasifEnv;
  $db=Db::conectar();
  $select=$db->prepare("
  SELECT * FROM 
  (SELECT * FROM t77_rs WHERE id = :id AND centro=:centro) AS r 
	LEFT JOIN 
  (SELECT centro as ct,zv,nombreSup,nombreAc,telfAc,telfSup FROM t77_zv_detalle WHERE centro = :centro) AS z 
	ON r.ZNPVTA = z.zv
  ");
  $select->bindValue('id',$id);
  $select->bindValue('centro',$idcentro);
  $select->execute();
  while ($registro=$select->fetch()){ 
  $lng = str_replace(",",".",$registro['Longitud']);
  $lat = str_replace(",",".",$registro['Latitud']);
  $codigowsp = $registro['Codigo'];
  $clientewsp = str_replace(" ","+",$registro['Cliente']);
  $supervisorwsp = str_replace(" ","+",$registro['nombreSup']);
  $agentewsp = str_replace(" ","+",$registro['nombreAc']);
  $cajaswsp = $registro['Entrega'];
  $comentarioswsp = str_replace(" ","+",$registro['comentario']);
  $comentario = $registro['comentario'];
  $autoriza_rech = $registro['autoriza_rech'];
  $ruta = $registro['Ruta'];
  $indx = $registro['indx'];
  $mr = $registro['mr'];
  $TipoPedido = $registro['TipoPedido'];
  $Tipo_Riesgo = $registro['Tipo_Riesgo'];
  $Servicio_Flex = $registro['Servicio_Flex'];
  $Tipo_NPS = $registro['Tipo_NPS'];
  IF($registro['RMD']==0){ $rmd='-'; }else{ $rmd=$registro['RMD']; }
  if($registro['ZNPVTA']==''){ $zonavta = $registro['zonatv']; } else { $zonavta = $registro['ZNPVTA']; } 
  $ciudad = $registro['Ciudad'];
  $urlwsp = "https://bit.ly/37GA070";
  
	$msjwsp = "*CODIGO:*+".$codigowsp."%0D%0A";
	$msjwsp .= "*NOMBRE:*+".$clientewsp."%0D%0A";
	$msjwsp .= "*SUP:*+".$supervisorwsp."%0D%0A";
    $msjwsp .= "*BDR:*+".$zonavta.' '.$agentewsp."%0D%0A";
	$msjwsp .= "*TIPO PEDIDO:*+".$TipoPedido."%0D%0A";
	$msjwsp .= "*TIPO RIESGO:*+".$Tipo_Riesgo."%0D%0A";
	$msjwsp .= "*TIPO NPS:*+".$Tipo_NPS."%0D%0A";
	$msjwsp .= "*RMD:*+".$rmd."%0D%0A";
	$msjwsp .= "*SERVICIO FLEX:*+".$Servicio_Flex."%0D%0A";
	$msjwsp .= "*CAJAS:*+".$cajaswsp."%0D%0A";
	$msjwsp .= "*RUTA:*+".$ruta."%0D%0A";
	$msjwsp .= "*MOTIVO:*+".$mr."%0D%0A";
	$msjwsp .= "*LOCALIDAD:*+".$ciudad."%0D%0A+".$urlwsp."+%0D%0A".$comentarioswsp."";
	      if($registro['cjasrechazadas']==0){
		  $entregarechazo=$registro['Entrega']; 
		  }else if (($registro['cjasrechazadas']-$registro['Entrega'])==0){
		  $entregarechazo=$registro['Entrega'];	  
		  }else {
		  $entregarechazo=$registro['cjasrechazadas'];	  
		  }  
        if($registro['rechazo'] == 1 or $registro['entregado'] == 1){ 
		$disabled = "disabled"; 
		} else {
		$disabled = "";	
		} 		  
  ?>
	<div class="row">
    <div class="col-sm-6 border">	
		<form class="needs-validation" novalidate method="get"> 
		<div class="form-group">
		<table class="table table-sm">
		<tr>
		<td><strong>Cliente:</strong></td><td><?php echo $registro['Codigo'].' - '.$registro['Cliente'];?></td>	
	    </tr> 
		<tr>
		<td><strong>Supervisor:</strong></td><td><?php echo $registro['nombreSup'];?></td>	
	    </tr>
		<tr>
		<td><strong>BDR:</strong></td><td><?php echo $registro['ZNPVTA'].' - '.$registro['nombreAc'];?></td>	
	    </tr>
		<tr>
		<td><strong>Tipo Pedido:</strong></td><td><?php echo $registro['TipoPedido'];?></td>	
	    </tr>
		<tr>
		<td><strong>Tipo Cliente:</strong></td><td><?php echo $registro['TipoCliente'];?></td>	
	    </tr>		
		<tr>
		<td><strong>Ventana Horaria:</strong></td><td><?php echo $registro['Abre'].' - '.$registro['Cierra'];?></td>	
	    </tr>	
		<tr>	
		<td><strong>Telf. Cliente:</strong></td><td><a href="tel:+<?php echo $registro['TelefonoCli'];?>"><?php echo $registro['TelefonoCli'];?></a></td>	
	    </tr>	
		<tr>
		<td><strong>Tipo Riesgo:</strong></td><td><?php echo $registro['Tipo_Riesgo'];?></td>	
	    </tr>
		<tr>
		<td><strong>Tipo NPS:</strong></td><td><?php echo $registro['Tipo_NPS'];?></td>	
	    </tr>	
		<tr>
		<td><strong>RMD:</strong></td><td><?php echo $rmd;?></td>	
	    </tr>			
		<tr>	
		<td><strong>Servicio Flex:</strong></td><td><?php echo $registro['Servicio_Flex'];?></td>	
	    </tr>
		<tr>
		<td><strong>Reparto:</strong></td><td><?php echo $registro['Ruta'];?><br></td>	
	    </tr>
		<tr>
		<td><strong>Cjs.Ent.Prog:</strong></td><td><?php echo $registro['Entrega'];?></td>	
	    </tr>
		<tr>
		<td><strong>Hl.Prog:</strong></td><td><?php echo $registro['HL'];?></td>	
	    </tr>		
		<tr>
		<td><strong>Mot. Rechazo:</strong></td>
		<td>
		<select class="form-control form-control-sm" id="validationCustom02" required name="mr" <?php echo $disabled; ?> >
			<option value="">Seleccionar</option>
			<?php		
			$db=Db::conectar();
			$select=$db->prepare("SELECT id, descripcion FROM t77_mr WHERE st=1");
			$select->execute();
			while ($regis=$select->fetch()) {
			if ($regis['descripcion'] == $registro['mr']){
			echo '<option  value="'.$regis['descripcion'].'" selected >'.$regis['descripcion'].'</option>';		
			}else {
			echo '<option  value="'.$regis['descripcion'].'" >'.$regis['descripcion'].'</option>';	
			}
			}
			Db::desconectar();
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
		<textarea name="comentarios" class="form-control form-control-sm" id="validationCustom03" required <?php echo $disabled; ?> ><?php echo $registro['comentario'];?></textarea>
		<div class="invalid-feedback">
        Ingrese un comentario valido
        </div>
		</td>	
	    </tr>
		<tr>
		<td colspan="2">
		<?php 
		if($registro['alerta'] == 0 and $registro['entregado'] == 0){ 
		?>  
        <button type="submit" class="btn btn-success" name="grabaralt" ><i class="fa fa-whatsapp"></i> Registrar</button>
				<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#entregar_ped"  <?php if($registro['rechazo'] == 1 or $registro['entregado'] == 1){ echo "disabled"; } ?>  >Entregar</button>
        <button type="button" class="btn btn-primary" onclick="location.href='transportes?FMfcgxwLtkdRLdXFNVNgdJkHCHKrmRpl=DetalleRuta&amp;ruta=<?php echo $registro['Ruta']; ?>&amp;fecha=<?php echo $registro['Fecha']; ?>&amp;viaje=<?php echo $registro['Viaje']; ?>';">Cancelar</button>		
		<?php } else { 
		?> 
		<div class='redes-flotantes'>
		<a href="https://api.whatsapp.com/send?text=<?php echo $msjwsp;?>" title="Compartir alerta" style="clear: left; float: left; margin-bottom: 1em; margin-right: 1em;" target="_blank">
		<img border="0" data-original-height="59" data-original-width="59" src="https://1.bp.blogspot.com/-q3Dot9N2qac/XOQgr9etVpI/AAAAAAABT1M/6V4Bqaqr-6UQcl9Fy2_CaVgex0N_OYuQgCLcBGAs/s1600/whatsapp%2Bicono.png" />
		</a>
		</div>
		<button type="button" class="btn btn-success" onclick="location.href='https://api.whatsapp.com/send?text=<?php echo $msjwsp;?>';" ><i class="fa fa-whatsapp"></i> WhatsApp</button>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#rechazar_ped"  <?php echo $disabled; ?>  >Rechazar</button>
		<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#entregar_ped"  <?php echo $disabled; ?>  >Entregar</button>
        <button type="button" class="btn btn-primary" onclick="location.href='transportes?FMfcgxwLtkdRLdXFNVNgdJkHCHKrmRpl=DetalleRuta&amp;ruta=<?php echo $registro['Ruta']; ?>&amp;fecha=<?php echo $registro['Fecha']; ?>&amp;viaje=<?php echo $registro['Viaje']; ?>';">Cancelar</button>		
		<?php 
		}
		?> 
		</td>	
	    </tr>
		</table>
		<input type="hidden" name="idrechazo" value="<?php echo $id;?>">
		<input type="hidden" name="FMfcgxwLtkdRLdXFNVNgdJkHCHKrmRpl" value="GrabarAlerta">
		</div>
	</form>
	</div>
    </div>	
		<?php
	modal_rechazar($indx,$id,$mr,$comentario,$autoriza_rech,$cajaswsp);
	modal_entregar($indx,$id,$actClasifEnv,$cajaswsp,$ruta,$registro['Fecha'],$registro['Viaje']);
	}
	Db::desconectar();	
	}
function depositos_bancarios_clientes(){
		global $idcentro,$hash,$fecha_hoy;	
	?> 	
	<div class="row">
    <div class="col-sm-12 p-0 bg-light">	
	<div class="d-flex">
	<div class="p-2 bg-light"><div class="text-muted text-md-left font-weight-bolder">Depositos : <?php echo $idcentro;?></div>
	</div>
	</div>
	</div>
	</div>
	<div class="row">
    <div class="col-sm-12 p-0">
	 <div class="table-responsive">
	 <table id="depositosclientes"  data-order='[[ 2, "asc" ]]' data-page-length='25'
     class="display compact cell-border">
		<thead>
		<tr>
		    <th></th>
			<th>Cliente</th>
			<th>Ruta</th>
			<th>Importe</th>
			<th>Banco</th>
		</tr>
		</thead>
		<tbody>
		<?php 
		  $db=Db::conectar();
		  $sql ="
SELECT  CONCAT(r.Codigo,' ',r.Cliente) AS Cliente,r.Ruta,d.importe,d.banco FROM 
(
SELECT Codigo,Cliente,Ruta,centro FROM `t77_rs` WHERE Fecha=:Fecha AND centro=:centro
) AS r  JOIN 
(
SELECT `id`, `fecha`, `codigocliente`, `importe`, `banco` FROM `t77_rs_depositos` WHERE fecha=:fecha
) as d 
ON r.Codigo = d.codigocliente 
				";
          $select=$db->prepare($sql);
		  $select->bindValue('fecha',$fecha_hoy);
		  $select->bindValue('Fecha',$fecha_hoy);
		  $select->bindValue('centro',$idcentro);
		  $select->execute();
          while ($registro=$select->fetch()) {
	echo '<tr>
			<td></td>
			<td>'.$registro[0].'</td>
			<td>'.$registro[1].'</td>
			<td>'.$registro[2].'</td>
			<td>'.$registro[3].'</td>
			</tr>
			';  
		  }
	Db::desconectar();
	?>   
	</tbody>
	</table>
	</div>
	</div>
	</div>
	<?php		
	}
	switch ($FMfcgxwLtkdRLdXFNVNgdJkHCHKrmRpl):
		case "RegistraHora":
	 if(isset($_GET['fecha'],$_GET['ruta'],$_GET['viaje'],$_GET['vehiculo']) && (($crud-> contardbuser('id','t77_rs_ruta_sif','fecha_plan = "'.$_GET['fecha'].'" AND viaje = "'.$_GET['viaje'].'" AND vehiculo = "'.$_GET['vehiculo'].'" AND centro="'.$idcentro.'"'))==0)){
	 $ruta=$_GET['ruta'];
	 $viaje=$_GET['viaje'];
	 $vehiculo=$_GET['vehiculo'];
	 $fecha=$_GET['fecha'];
	 $indx=$vehiculo.$ruta.$viaje.$fecha;
	 if(strtoupper($aid) == strtoupper($ruta)){
	 $crud->InsertarHoraInicioVehiculoT2($indx,$idcentro,$vehiculo,$ruta,$viaje,$fecha,$fecha_hora);
	 ?><div class="alert alert-success" role="alert">Se registro...</div><?php 
	 } else {
	 ?><div class="alert alert-danger" role="alert">No eres el usuario <?php echo $ruta; ?>...</div><?php 
	 }
	 header('Refresh: 3; URL=transportes?fecha='.$_GET['fecha'].'&e='.$hash.'');
	 } else {
	 if(isset($_GET['fecha'],$_GET['id'],$_GET['tb'])){
     if($aid == $_GET['ruta']){ 	 
	 $crud->ModificarHoraInicioVehiculoT2($_GET['tb'],$fecha_hora,$idcentro,$_GET['id']);
	 ?><div class="alert alert-success" role="alert">Se registro...</div><?php 
	 } else {
	 ?><div class="alert alert-danger" role="alert">No eres el usuario <?php echo $_GET['ruta']; ?>...</div><?php 
	 }
	 header('Refresh: 3; URL=transportes?fecha='.$_GET['fecha'].'&e='.$hash.'');
	} else {}
	}
	break;
	    case "DetalleRuta":
	if(isset($_GET['fecha'],$_GET['ruta'],$_GET['viaje'])){
	rutas__detalle($_GET['fecha'],$_GET['ruta'],$_GET['viaje']);
	modal_entregar_todos(1,$_GET['ruta'],$_GET['fecha'],$_GET['viaje']);
	if (isset($_GET['EntregarTodos']) && $_GET['EntregarTodos']=='cHNKXCgPVRsxZwjjgxGwXz'){
    $crud->EntregarPedidoTodos($aid,$idcentro,$_GET['ruta'],$_GET['fecha'],$_GET['viaje']); 
	header('Location: transportes?FMfcgxwLtkdRLdXFNVNgdJkHCHKrmRpl=DetalleRuta&ruta='.$_GET['ruta'].'&fecha='.$_GET['fecha'].'&viaje='.$_GET['viaje'].'');
	}
	if (isset($_POST['encuestarepaso'])){  //graba encuesta repaso 
	if(isset($_POST['repaso'])){
	foreach ($_POST['repaso'] as $clave => $valor){
    $crud->insertar_repaso_t2($clave,$idcentro,1);
	}
	}
	if(isset($_POST['ck_enviar'])){
	foreach ($_POST['ck_enviar'] as $clave => $valor){
    $crud->EntregarPedido($clave,$aid,$idcentro,0,0,0);
	}
	} else { echo "no hay"; }	
	
	if(isset($_POST['encuesta'])){
	foreach ($_POST['encuesta'] as $clave => $valor){
	if($valor!=''){	
	$crud->insertar_encuesta_t2($clave,$idcentro,$valor);
	}
	}
	}else{}
	header('Location: '.$_SERVER["REQUEST_URI"].'');
	}
    } else {
	?><div class="alert alert-danger" role="alert">Algo esta mal...</div><?php
	header('Refresh: 3; URL=transportes');
	}
	break;
	    case "AlertarWS":
	rutas__ws($_GET["id"]);
	break;
	break;
	    case "GrabarRechazo":
	if(isset($_GET['confirmarrchz'],$_GET['autoriza_rech'],$aid)){
		if($_GET['cjasrechazadas'] > $_GET['CjsEntrega']){
		echo '<div class="alert alert-warning" role="alert">El rechazo es mayor a lo programado, favor de corregir</div>';	
		header('Refresh: 3; URL=transportes?FMfcgxwLtkdRLdXFNVNgdJkHCHKrmRpl=AlertarWS&id='.$_GET['idrechazo'].''); 				
		} else {
		$idrechazo=$_GET['idrechazo'];
		$autoriza_rech=$_GET['autoriza_rech'];
		$cjasrechazadas=$_GET['cjasrechazadas'];
		$ClasificaEnvRec=$_GET['ClasificaEnvRec'];
		$CjasClasifRech=$_GET['CjasClasifRech'];
		$CjsEntrega=$_GET['CjsEntrega'];
		$mr=$_GET['mr'];
		$comentarios=$_GET['comentarios'];
	    $crud->GrabarRechazo($aid,$idrechazo,$autoriza_rech,$cjasrechazadas,$ClasificaEnvRec,$CjasClasifRech,$idcentro,$mr,$comentarios);
	echo '<div class="alert alert-success" role="alert">Rechazo registrado</div>';	
		header('Refresh: 1; URL=transportes?FMfcgxwLtkdRLdXFNVNgdJkHCHKrmRpl=AlertarWS&id='.$idrechazo.''); 			
		}
	} else {
	echo '<div class="alert alert-danger" role="alert">Algo esta mal</div>';	
		header('Refresh: 1; URL=transportes?FMfcgxwLtkdRLdXFNVNgdJkHCHKrmRpl=AlertarWS&id='.$idrechazo.''); 		
	}
	break;
	    case "GrabarAlerta":
	if($_GET['FMfcgxwLtkdRLdXFNVNgdJkHCHKrmRpl'] = 'GrabarAlerta' && isset($aid) && isset($_GET['FMfcgxwLtkdRLdXFNVNgdJkHCHKrmRpl'])){
		$crud->GrabarAlerta($_GET['mr'],0,$_GET['comentarios'],$aid,$_GET['idrechazo']);
	echo '<div class="alert alert-success" role="alert">Alerta registrada, ahora se compartira por WS</div>';	
		 header('Refresh: 1; URL=transportes?FMfcgxwLtkdRLdXFNVNgdJkHCHKrmRpl=AlertarWS&id='.$_GET['idrechazo'].''); 	
	} else {
	echo '<div class="alert alert-danger" role="alert">Algo esta mal</div>';	
		 header('Refresh: 1; URL=transportes?FMfcgxwLtkdRLdXFNVNgdJkHCHKrmRpl=AlertarWS&id='.$_GET['idrechazo'].''); 		
	}
	break;
	    case "EntregarPedido":
	if($_GET['FMfcgxwLtkdRLdXFNVNgdJkHCHKrmRpl'] = 'EntregarPedido' && isset($aid) && isset($_GET['FMfcgxwLtkdRLdXFNVNgdJkHCHKrmRpl'])){
	$IdEntrega=$_GET['IdEntrega'];
	$ClasificaEnvases=$_GET['ClasificaEnvases'];
	$CjasClasificadas=$_GET['CjasClasificadas'];
	$cjasrechazadas=0;
    $crud->EntregarPedido($IdEntrega,$aid,$idcentro,$cjasrechazadas,$ClasificaEnvases,$CjasClasificadas);	
	echo '<div class="alert alert-success" role="alert">Pedido entregado</div>';	
		 header('Refresh: 1; URL=transportes?FMfcgxwLtkdRLdXFNVNgdJkHCHKrmRpl=AlertarWS&id='.$_GET['IdEntrega'].''); 	
	} else {
	echo '<div class="alert alert-danger" role="alert">Algo esta mal</div>';	
		 header('Refresh: 2; URL=transportes?FMfcgxwLtkdRLdXFNVNgdJkHCHKrmRpl=AlertarWS&id='.$_GET['IdEntrega'].''); 		
	}
	break;
	    case "ReporteDiarioRuta":
     tabla_reporte_diario();
     modal_reporte_diario('MR','SELECT id, descripcion FROM t77_mr WHERE st=1','VALORSELECT','Action');
	 modal_reporte_diario('KM','SELECT id, descripcion FROM t77_decripcion WHERE tipo=2','VALORSELECT','Action');
	 modal_reporte_diario('TIEM','SELECT id, descripcion FROM t77_decripcion WHERE tipo=3','VALORSELECT','Action');
	 modal_reporte_diario('MOD','SELECT id, descripcion FROM t77_decripcion WHERE tipo=4','VALORSELECT','Action');
	break;	
	    case "updateETA":
	if(isset($_GET['fecha'],$_GET['eta'],$_GET['Llega'])){
	$Llega = $_GET['Llega'].':00'; 	
    $crud->modificar_eta($Llega,$_GET['eta'],$idcentro); 
    header('Location: transportes?FMfcgxwLtkdRLdXFNVNgdJkHCHKrmRpl=DetalleRuta&ruta='.$_GET['ruta'].'&fecha='.$_GET['fecha'].'&viaje='.$_GET['viaje'].'');	
	} else {
    header('Location: transportes?FMfcgxwLtkdRLdXFNVNgdJkHCHKrmRpl=DetalleRuta&ruta='.$_GET['ruta'].'&fecha='.$_GET['fecha'].'&viaje='.$_GET['viaje'].'');
	}
	break;		
		case "DepositosBancarios":
	depositos_bancarios_clientes();
	break;	
	default:
	rutas__();
	endswitch;
	} else {
	echo $html_acceso;		
	}
	}
	require('../footer.php');
	ob_end_flush();	
	?>