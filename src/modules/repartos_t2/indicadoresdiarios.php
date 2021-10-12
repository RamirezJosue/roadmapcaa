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
	$datatablesjs = 0;
	$datatablesjsresponsive = 1;
	require('../head.php');
	if(isset($_GET['fechaselec'])){
	$fecha_form = $_GET['fechaselec'];
	$fechars = $_GET['fechaselec'];	
	$date_rmd = $_GET['fechaselec'];
	} else {
	$fechars = $fechars; 
	$fecha_form = $fecha;

	$db=Db::conectar();
	$select=$db->prepare("SELECT  MAX(`Fecha`) AS Fecha_max FROM `t77_rmd` WHERE id_centro='$idcentro'");
	$select->execute();
	$row=$select->fetch(PDO::FETCH_ASSOC);
	$date_rmd  = $row['Fecha_max'];
	Db::desconectar();

	}


	echo $date_rmd;

	// fin head
	$actClasifEnv = 1;
	$hash='Xa6UYNOhEMOu5OUPtqaGAUiflsig';	
	if (isset($_GET['FMfcgxwLtkdRLdXFNVNgdJkHCHKrmRpl'])): 
	$FMfcgxwLtkdRLdXFNVNgdJkHCHKrmRpl = $_GET['FMfcgxwLtkdRLdXFNVNgdJkHCHKrmRpl']; 
	else:
	$FMfcgxwLtkdRLdXFNVNgdJkHCHKrmRpl = ""; 
	endif;
	$enlace = $sitio.'modulos/'.$accesos;
	
	$desviacionKM = array(1=>'Calle Cerrada / Obras', 
					  2=>'Repasos a mas de 5 POC', 
					  3=>'Retorno Ruta Alterna',
					  4=>'Huelga - ruta alterna',
					  5=>'Planificación inconsistente');
	$desviacionTie = array(1=>'Repasos', 
						  2=>'Retorno Ruta Alterna', 
						  3=>'Espera excesiva en Clientes',
						  4=>'Demora en Modulación excesiva');	
	$modulacion = array(1=>'Olvido', 
						  2=>'Bajo drop', 
						  3=>'Contacto Directo BDR');
	$responsable = array(1=>'Supervisor T2', 
						  2=>'Empresario T2', 
						  3=>'Asistente T2');
	require_once('../../bd/array/EmpresaTransporte.php');
    /* 
	if(date("N", strtotime($fechars)) == 1){
		//lunes		
		$date_rmd = strtotime('-2 day', strtotime($fechars));
		$date_rmd = date("Y-m-d", $date_rmd);
	}else {
	    //otros dias
		$date_rmd = strtotime('-1 day', strtotime($fechars));
		$date_rmd = date("Y-m-d", $date_rmd);
	}
	*/
	$enlace_actual = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    function insertar_action_log($array){
       global $idcentro;
	   $unoPorque = $array['1porque']; 
	   $dosPorque = $array['2porque'];
	   $trePorque = $array['3porque'];
	   $cuaPorque = $array['4porque'];
	   $cinPorque = $array['5porque'];
	   $causaRaiz = $array['causaRaiz'];
	   $accion = $array['accion'];
	   $resposable = $array['responsable'];
	   $fecha_compromiso = $array['fechaCompromiso'];
	   $nombre = $array['codigoCli'];
	   $descripcion_anomalia = $array['descripcion'];
	   $indicador_tema = $array['indicadorTema'];
	   $fecha_plan = $array['fechaPlan'];
	   $ruta = $array['ruta'];
	   $vehiculo = $array['vehiculo'];
	   $empresa = $array['empresa'];
	   $sql	= "INSERT INTO `t77_action_log`
	   		 (`id`, `centro`, `nombre`, `indicador_tema`, `fecha_plan`, `ruta`, `vehiculo`, `empresa`, `descripcion_anomalia`, `1erporque`, `2doporque`, `3erporque`, `4toporque`, 
				`5toporque`, `causa_raiz`, `accion`, `resposable`, `fecha_compromiso`, `estatus`, `fecha_estatus`, `usuario_estatus`, `comentario_sup_t2`)
	   VALUES (NULL,'$idcentro','$nombre','$indicador_tema','$fecha_plan','$ruta','$vehiculo','$empresa','$descripcion_anomalia','$unoPorque','$dosPorque','$trePorque','$cuaPorque', 
	   			'$cinPorque','$causaRaiz','$accion','$resposable','$fecha_compromiso',NULL,NULL,NULL,NULL)";
	   $db=DB::conectar();
	   $insert=$db->prepare($sql);
	   $insert->execute();
	   $lastInsertId = $db->lastInsertId();
		 if($lastInsertId>0){
		   echo '<h3>Se registro !</h3>';
		 }else{ 
		   echo '<h3>No se pudo registrar</h3>';
		 } 
	   Db::desconectar();
	} 
	function html_causa_raiz(){
		?>
		<div class="card mb-3">
		<div class="card-header">Busqueda Causa Raiz</div>
		<div class="card-body">
		<div class="form-row">
		<div class="form-group col-md-2">
		<label for="1porque" class="col-form-label col-form-label-sm">1er Porqué =></label>
		<textarea class="form-control form-control-sm" id="1porque" rows="3" name="actionLog[1porque]" required  ></textarea>
		</div>
		<div class="form-group col-md-2">
		<label for="2porque" class="col-form-label col-form-label-sm">2do Porqué =></label>
		<textarea class="form-control form-control-sm" id="2porque" rows="3" name="actionLog[2porque]" required  ></textarea>
		</div>
		<div class="form-group col-md-2">
		<label for="3porque" class="col-form-label col-form-label-sm">3er Porqué =></label>
		<textarea class="form-control form-control-sm" id="3porque" rows="3" name="actionLog[3porque]" required  ></textarea>
		</div>
		<div class="form-group col-md-2">
		<label for="4porque" class="col-form-label col-form-label-sm">4to Porqué =></label>
		<textarea class="form-control form-control-sm" id="4porque" rows="3" name="actionLog[4porque]" required  ></textarea>
		</div>
		<div class="form-group col-md-2">
		<label for="5porque" class="col-form-label col-form-label-sm">5to Porqué =></label>
		<textarea class="form-control form-control-sm" id="5porque" rows="3" name="actionLog[5porque]" required  ></textarea>
		</div>
		<div class="form-group col-md-2">
		<label for="causaraiz" class="col-form-label col-form-label-sm">Causa Raíz</label>
		<textarea class="form-control form-control-sm" id="causaraiz" rows="3" name="actionLog[causaRaiz]" required  ></textarea>
		</div>	
		</div>
		</div>
		</div>
		<?php 
	}
	function html_action_log($Indicador){
		global $responsable;
		?>
			<div class="card mb-3">
		    <div class="card-header">Action Log</div>
		    <div class="card-body">
			<div class="form-row">
			<div class="form-group col-md-3">
			<label for="Tema" class="col-form-label col-form-label-sm">Tema :</label>
			<input class="form-control form-control-sm" type="text" id="Tema" value="<?php echo $Indicador; ?>" disabled>
			</div>
			<div class="form-group col-md-3">
			<label for="Accion" class="col-form-label col-form-label-sm">Accion :</label>
			<input class="form-control form-control-sm" type="text" id="Accion" name="actionLog[accion]" required >
			</div>
			<div class="form-group col-md-3">
			<label for="Responsable" class="col-form-label col-form-label-sm">Responsable :</label>
				<select class="form-control form-control-sm" id="exampleFormControlSelect2"  name="actionLog[responsable]" required>
				<option  value="" selected >Seleccionar</option>
				<?php 
				foreach($responsable  as $valor=>$clave)
				{
						if ($valor == ''){
						echo '<option  value="'.$clave.'" selected >'.$clave.'</option>';		
						}else {
						echo '<option  value="'.$clave.'" >'.$clave.'</option>';	
						}
				}
				?>
				</select>
			</div>
			<div class="form-group col-md-3">
			<label for="Fecha_compromiso" class="col-form-label col-form-label-sm">Fecha Compromiso :</label>
			<input class="form-control form-control-sm" type="date" id="Fecha_compromiso" name="actionLog[fechaCompromiso]" required >
			</div>
			</div>			
				<button class="btn btn-danger btn sm" type="submit">Grabar</button>
			</div>
			</div>
		<?php 
	}
	function modal_fecha(){
		global $fechars;
		?>
			<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
			  <div class="modal-dialog modal-sm">
				<div class="modal-content">
				  <div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Seleccionar Fecha</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					  <span aria-hidden="true">&times;</span>
					</button>
				  </div>
				  <form method="GET">
				  <div class="modal-body">
							<input class="form-control" type="date" name="fechaselec" value="<?php echo $fechars; ?>">
				  </div>
				  <div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
					<button type="submit" class="btn btn-danger">Guardar</button>
				  </form>
				  </div>
				</div>
			  </div>
			</div>	
		<?php
	}
	function tabla_reporte_diario(){
		global $fechars,$idcentro,$date_rmd;
		$where='';
    ?>
	<div class="row">
    <div class="col-sm-12 bg-dark">
	<div class="d-flex justify-content-center">
	<div class="p-2">
	<div class="text-white text-md-center font-weight-bolder">Indicadores | <?php echo $idcentro.' | '.$fechars; ?></div>
    </div>
	<div class="p-2">
	<button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#exampleModal">Fecha</button>
	</div>
	<?php 

	if($idcentro == 'BK77'){
	echo 	'<div class="p-2">
      		<div class="btn-group" role="group" aria-label="Button group with nested dropdown">
			<div class="btn-group" role="group">
			  <button id="btnGroupDrop1" type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				Indicadores
			  </button>
			  <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
				<a class="dropdown-item" href="https://www.bk77.co/doc_delivery/BK77/2021/Indicadores.pdf" target="_blank" >Resumen PDF</a>
			  </div>
			</div>
		  	</div>
			</div>';
						}

	?>
	</div>
	</div>
	</div>
	<div class="table-responsive">
		<?php 
		  $n=0; 
		  $dbr=Db::conectar();
		  $sqlr ="SELECT
1 as MRplan, 100 as ModPlan, 5 as rmd_plam,		  
((t.CjsPro*1)/100) as CjsRechPlan, 
((t.CjsRech/t.CjsPro)*100) as Refusales,
t.Fecha_rs,t.Ruta_rs,t.Vehiculo_RS,t.CodEt,t.CjsPro,t.CjsEnt,t.CjsAler,t.CjsRech,t.PocProg,t.PocEnt,t.PocAler,
t.PocRech,t.DistanciaPlan,t.Inicio_plan,t.Fin_Plan,t.Tiempo_plan,t.Tiempo_min_plan,
t.km_inicial_real,t.km_final_real,t.km_recorrido_real,t.inicio_real,t.fin_real,t.TieReal,
d.Fecha_rmd,IFNULL(d.ContCli_rmd,0) as ContCli_rmd,IFNULL(d.Rating_rmd,0) as Rating_rmd,
((t.PocRech/t.PocAler)*100) as PocModEfic,
 IFNULL((t.km_recorrido_real - t.DistanciaPlan),0)  AS Desvkm,
(t.TieReal - t.Tiempo_min_plan) AS DesvTie,
(IFNULL(d.Rating_rmd,0) - 5) AS Rmd 
FROM (
SELECT *
FROM
	(
/*INICIO SQL RS */	
SELECT a.Fecha AS Fecha_rs, a.Ruta as Ruta_rs, a.Vehiculo as Vehiculo_RS, a.CodEt,
sum(a.CjsPro) as CjsPro, sum(a.CjsEnt) as CjsEnt, sum(a.CjsAler) as CjsAler, sum(a.CjsRech) as CjsRech,
sum(a.PocProg) as PocProg, sum(a.PocEnt) as PocEnt, sum(a.PocAler) as PocAler,  sum(a.PocRech) as PocRech,
a.DistanciaPlan,
a.Inicio as Inicio_plan,
a.Fin as Fin_Plan,
TIMEDIFF( `Fin`,`Inicio`) as Tiempo_plan,
TIMESTAMPDIFF(MINUTE,`Inicio`,`Fin`)/60 as Tiempo_min_plan
FROM (
SELECT
Fecha,Ruta,Vehiculo,CodEt,
Entrega As CjsPro,
if(entregado=1,Entrega,0) as CjsEnt,
if(alerta=1,Entrega,0) as CjsAler,
if(rechazo=1,cjasrechazadas,0) as CjsRech,
1 as PocProg,
if(entregado=1,1,0) As PocEnt,
if(alerta=1,1,0) As PocAler,
if(rechazo=1,1,0) As PocRech,
DistanciaPlan,
Inicio,
Fin
FROM `t77_rs` WHERE centro='$idcentro' AND Fecha='$fechars' 
     ) AS a GROUP BY a.Fecha, a.Ruta, a.Vehiculo, a.CodEt
/*FIN SQL RS */		 
	) AS rs LEFT JOIN (
SELECT km.fecha,km.vehiculo,km.km_inicial_real,km.km_final_real,km.km_recorrido_real,tp.inicio_real,tp.fin_real,tp.TieReal FROM (	
/*INICIO SQL KM REAL CHECK LIST*/
SELECT a.fecha, UPPER(a.vehiculo) AS vehiculo, sum(a.inicial) as km_inicial_real, sum(a.final) as  km_final_real, 
IF(SUM(a.inicial)=0,0,IF(sum(a.final)=0,0,(sum(a.final)-sum(a.inicial)))) AS km_recorrido_real
FROM (
SELECT CAST(respuesta_user AS DECIMAL) AS inicial,0 as final, user_registro AS vehiculo,fecha
FROM `exa_detalle_user` WHERE id_tema = 2147483647 AND fecha='$fechars' AND tipo_pregunta=5 AND centro='$idcentro'
UNION ALL
SELECT 0 as inicial, CAST(respuesta_user AS DECIMAL) AS final,user_registro AS vehiculo,fecha
FROM `exa_detalle_user` WHERE id_tema = 359282242 AND fecha='$fechars' AND tipo_pregunta=5 AND centro='$idcentro'
    ) as a GROUP BY a.fecha, a.vehiculo
/*FIN SQL KM REAL CHECK LIST*/	
) AS km LEFT JOIN  (
/* INICIO SQL TIEMPO REAL SACA DE RUTA SIF */
SELECT
a.vehiculo,a.ruta,a.fecha_plan,a.inicio AS inicio_real,a.fin AS fin_real,
IF(a.fin='0000-00-00 00:00:00',TIMESTAMPDIFF(MINUTE,a.inicio,a.hora_actual)/60,TIMESTAMPDIFF(MINUTE,a.inicio,a.fin)/60) as TieReal
FROM (
SELECT vehiculo,ruta,fecha_plan,MIN(salida_cd) as inicio, MAX(llegada_cd) as fin, DATE_SUB(NOW(), INTERVAL 5 HOUR) AS hora_actual
FROM `t77_rs_ruta_sif`
WHERE fecha_plan='$fechars' AND centro='$idcentro'
GROUP BY  vehiculo,ruta,fecha_plan
    ) AS a
/* FIN SQL TIEMPO REAL SACA DE RUTA SIF */	
) as tp 
ON  km.vehiculo = tp.vehiculo	
	) AS kt  ON rs.Vehiculo_RS = kt.vehiculo
	) AS t LEFT JOIN 
	(
/* RMD */
SELECT  `Fecha` as Fecha_rmd, COUNT(`CodCliente`) AS ContCli_rmd, `Ruta` as Ruta_rmd, AVG(`Rating`) AS Rating_rmd 
FROM `t77_rmd` 
WHERE id_centro='$idcentro' AND Fecha='$date_rmd' GROUP BY `Fecha`, `Ruta`
/* RMD */	
	) AS d ON  t.Ruta_rs = d.Ruta_rmd		  
		  ";
          $selectr=$dbr->prepare($sqlr);
		  //$selectr->bindValue('centro',$idcentro);
		  //$selectr->bindValue('fecha_plan',$fechars);
		  //$selectr->bindValue('fecha',$fechars);
		  //$selectr->bindValue('Fecha',$fechars);
		  $selectr->execute();
          while ($rpr=$selectr->fetch(PDO::FETCH_ASSOC)){
		  $n++;	  
	?> 
	<br>
<table class="table table-sm table-hover">
  <thead>
    <tr class="text-center table-active">
      <th scope="col" colspan="3" ><?php echo $rpr['Ruta_rs'].' | '.$rpr['Vehiculo_RS'].' | '.$rpr['Fecha_rs']; ?></th>
      <th scope="col">Plan</th>
      <th scope="col">Real</th>
      <th scope="col">Delta</th>
	  <th scope="col">Coment</th>
    </tr>
  </thead>
  <tbody>
     <tr class="text-center <?php if($rpr['Refusales'] > 1.2): echo 'text-danger'; else: echo ''; endif;  ?>">
      <th scope="row">MR %</th>
	  <td> - </td>
	  <td> - </td>
      <td><?php  echo $rpr['MRplan']; ?>%</td>
      <td><?php  echo round($rpr['Refusales'],1); ?>%</td>
      <th scope="row"><?php  echo round($rpr['Refusales']-$rpr['MRplan'],1); ?>%</th>
      <td></td>
    </tr> 
    <tr class="text-center <?php if($rpr['CjsRech'] > 20): echo 'text-danger'; else: echo ''; endif;  ?>">
      <th scope="row">Cajas Rechazadas</th>
	  <td><small class="text-muted">Cjs.Prog.</small><br><?php  echo round($rpr['CjsPro']); ?></td>
	  <td><small class="text-muted">Cjs.Rech.</small><br><?php  echo round($rpr['CjsRech']); ?></td>
      <td><small class="text-muted">Cjs. 1%</small><br><?php  echo round($rpr['CjsRechPlan']); ?></td>
      <td><small class="text-muted">Cjs.Rech</small><br><?php  echo round($rpr['CjsRech']); ?></td>
      <th scope="row"><?php  echo round($rpr['CjsRech']-$rpr['CjsRechPlan']); ?></th>
      <td><button type="button" class="btn btn-danger btn-sm" onclick="location.href='indicadoresdiarios?&FMfcgxwLtkdRLdXFNVNgdJkHCHKrmRpl=Actionlog&Indicador=Market_Refusal&Ruta=<?php  echo $rpr['Ruta_rs']; ?>&Fecha=<?php  echo $rpr['Fecha_rs']; ?>&Vehiculo=<?php  echo $rpr['Vehiculo_RS']; ?>&Empresa=<?php  echo $rpr['CodEt']; ?>';">Action Log</button></td>
    </tr>
    <tr class="text-center <?php if($rpr['Desvkm'] > -15 AND $rpr['Desvkm'] < 15) : echo ''; else: echo 'text-danger'; endif;  ?>">
      <th scope="row">Desv. KM</th>
	  <td><small class="text-muted">Ini.Real</small><br><?php  echo $rpr['km_inicial_real']; ?></td>
	  <td><small class="text-muted">Fin.Real</small><br><?php  echo $rpr['km_final_real']; ?></td>
      <td><small class="text-muted">Km.</small><br><?php echo round($rpr['DistanciaPlan']); ?></td>
      <td><small class="text-muted">Km.</small><br><?php echo $rpr['km_recorrido_real']; ?></td>
      <th scope="row"><?php echo round($rpr['Desvkm']); ?></th>
      <td><button type="button" class="btn btn-danger btn-sm" onclick="location.href='indicadoresdiarios?&FMfcgxwLtkdRLdXFNVNgdJkHCHKrmRpl=Actionlog&Indicador=Desviacion_KM&Ruta=<?php  echo $rpr['Ruta_rs']; ?>&Fecha=<?php  echo $rpr['Fecha_rs']; ?>&Vehiculo=<?php  echo $rpr['Vehiculo_RS']; ?>&Empresa=<?php  echo $rpr['CodEt']; ?>';">Action Log</button></td>
    </tr>
     <tr class="text-center <?php if($rpr['DesvTie'] > -1.5 AND $rpr['DesvTie'] < 1.5) : echo ''; else: echo 'text-danger'; endif;  ?>">
      <th scope="row">Desv. Tiempo</th>  
	  <td><small class="text-muted">Ini.Real</small><br><?php echo date_format(date_create($rpr['inicio_real']), 'H:i:s'); ?></td>
	  <td><small class="text-muted">Fin.Real</small><br><?php echo date_format(date_create($rpr['fin_real']), 'H:i:s'); ?></td>
      <td><small class="text-muted">Horas</small><br><?php  echo round($rpr['Tiempo_min_plan'],2); ?></td>
      <td><small class="text-muted">Horas</small><br><?php  echo round($rpr['TieReal'],2); ?></td>
      <th scope="row"><?php  echo round($rpr['DesvTie'],2); ?></th>
      <td><button type="button" class="btn btn-danger btn-sm" onclick="location.href='indicadoresdiarios?&FMfcgxwLtkdRLdXFNVNgdJkHCHKrmRpl=Actionlog&Indicador=Desviacion_Tiempo&Ruta=<?php  echo $rpr['Ruta_rs']; ?>&Fecha=<?php  echo $rpr['Fecha_rs']; ?>&Vehiculo=<?php  echo $rpr['Vehiculo_RS']; ?>&Empresa=<?php  echo $rpr['CodEt']; ?>';">Action Log</button></td>
    </tr>
    <tr class="text-center">
      <th scope="row">Eficiencia Modulación</th>
	  <td><small class="text-muted">Poc.Aler.</small><br><?php echo $rpr['PocAler']; ?></td>
	  <td><small class="text-muted">Poc.Rech.</small><br><?php echo $rpr['PocRech']; ?></td>
      <td><small class="text-muted">Mod.</small><br><?php echo round($rpr['ModPlan'],2); ?>%</td>
      <td><small class="text-muted">Mod.</small><br><?php echo round($rpr['PocModEfic'],2); ?>%</td>
      <th scope="row"><?php echo round($rpr['PocModEfic'],2); ?>%</th>
      <td><button type="button" class="btn btn-danger btn-sm" onclick="location.href='indicadoresdiarios?&FMfcgxwLtkdRLdXFNVNgdJkHCHKrmRpl=Actionlog&Indicador=Eficiencia_Modulacion&Ruta=<?php  echo $rpr['Ruta_rs']; ?>&Fecha=<?php  echo $rpr['Fecha_rs']; ?>&Vehiculo=<?php  echo $rpr['Vehiculo_RS']; ?>&Empresa=<?php  echo $rpr['CodEt']; ?>';">Action Log</button></td>
    </tr>
    <tr class="text-center <?php if($rpr['Rmd'] > 0 AND $rpr['Rmd'] < 4): echo 'text-danger'; else: echo ''; endif;  ?>">
      <th scope="row">Rate My Delivery | <?php echo $rpr['Fecha_rmd']; ?></th>
	  <td><small class="text-muted">Poc.Rmd.</small><br><?php  echo $rpr['ContCli_rmd']; ?></td>
	  <td> - </td>
      <td><small class="text-muted">Rmd.</small><br><?php echo round($rpr['rmd_plam'],2); ?></td>
      <td><small class="text-muted">Rmd.</small><br><?php echo round($rpr['Rating_rmd'],2); ?></td>
      <th scope="row"><?php echo round(($rpr['Rmd']),2); ?></th>
      <td><button type="button" class="btn btn-danger btn-sm" onclick="location.href='indicadoresdiarios?&FMfcgxwLtkdRLdXFNVNgdJkHCHKrmRpl=Actionlog&Indicador=Rate_My_Delivery&Ruta=<?php  echo $rpr['Ruta_rs']; ?>&Fecha=<?php  echo $rpr['Fecha_rmd']; ?>&Vehiculo=<?php  echo $rpr['Vehiculo_RS']; ?>&Empresa=<?php  echo $rpr['CodEt']; ?>';">Action Log</button></td>
    </tr>	
    <tr>
    <td colspan="7">
	<form  onsubmit="return confirm('Esta seguro de enviar la información');" name="conductor" method='POST' action='visitas?l=seg&amp;fecha=<?php // echo $fechars; ?>&amp;ruta=<?php // echo $rpr['Ruta']; ?>'>
	<input type='hidden' name='segkpi' value='DfXdz2htPH0lsSSs5nCTpuj'>
	<button class="btn btn-danger btn-sm" disabled >Enviar</button>
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
	function action_log_reporte_diario($Indicador,$Ruta,$Fecha,$Vehiculo,$Empresa){
		global $EmpresaTransporte,$desviacionKM,$desviacionTie,$idcentro,$enlace_actual;
		
		if ($Empresa==0) { $Emptp = 'Ninguno'; }else{ $Emptp = $EmpresaTransporte[$Empresa]; }
		
		switch ($Indicador) {

    case "Market_Refusal":
            if(isset($_POST['actionLog'])){
			insertar_action_log($_POST['actionLog']);
			}
			$db=Db::conectar();
			$sql="SELECT mr,cjasrechazadas,comentario,autoriza_rech,Transporte,Ruta,Vehiculo,Codigo,Cliente,Direccion,Entrega,Ciudad 
				FROM `t77_rs` 
			WHERE rechazo=1 AND centro=:centro AND Ruta=:Ruta AND Fecha=:Fecha";
			$selectr=$db->prepare($sql);
		    $selectr->bindValue('centro',$idcentro);
			$selectr->bindValue('Ruta',$Ruta);
			$selectr->bindValue('Fecha',$Fecha);
			$selectr->execute();
	echo   '<form action="'.$enlace_actual.'" method="POST"><div class="card mb-3">
			<div class="card-header">'.$Indicador.' | '.$Ruta.' | '.$Fecha.' | '.$Vehiculo.' | '.$Emptp.'</div>
			<div class="card-body">';			 	  
	echo   '<div class="form-row">'; 
	echo   '<div class="form-group col-md-12">';
	echo   '<label for="exampleFormControlSelect2" class="col-form-label col-form-label-sm">Cliente | Cajas Plan | Cajas Rechazo | MR </label>';
			  while ($row=$selectr->fetch()){
                  $datos_cab = $row['Codigo'].' | '.$row['Cliente'].' | '.$row['Entrega'].' | '.$row['cjasrechazadas'].' | '.$row['mr'];
			echo '<div class="form-check">
				  <input class="form-check-input" type="radio" name="actionLog[codigoCli]" id="cliente" value="'.$datos_cab.'" required >
				  <label class="form-check-label" for="cliente">'.$datos_cab.'</label>
				  </div>';
				}
				Db::desconectar();
	echo   '</div>
			</div>';
	echo	'<div class="form-group col-md-12">
			<label for="descripcion" class="col-form-label col-form-label-sm">Descripcion :</label>
			<textarea class="form-control form-control-sm" id="descripcion" rows="3" name="actionLog[descripcion]" required  ></textarea>
			</div>';		
	echo   '</div>
			</div>';
	echo 	'<input  name="actionLog[indicadorTema]" type="hidden" value="Market Refusal">';
    echo 	'<input  name="actionLog[fechaPlan]" type="hidden" value="'.$Fecha.'">';
	echo 	'<input  name="actionLog[ruta]" type="hidden" value="'.$Ruta.'">';
	echo 	'<input  name="actionLog[vehiculo]" type="hidden" value="'.$Vehiculo.'">';
	echo 	'<input  name="actionLog[empresa]" type="hidden" value="'.$Emptp.'">';
			html_causa_raiz();
			html_action_log('Market Refusal');
			echo '</form>';
        break;
    case "Desviacion_KM":
			if(isset($_POST['actionLog'])){
			insertar_action_log($_POST['actionLog']);
			}				
	echo   '<div class="card mb-3"><form action="'.$enlace_actual.'" method="POST"><div class="card mb-3">
			<div class="card-header">'.$Indicador.' | '.$Ruta.' | '.$Fecha.' | '.$Vehiculo.' | '.$Emptp.'</div>
			<div class="card-body">';			 	  
	echo   '<div class="form-row">'; 
	echo   '<div class="form-group col-md-12">';
	echo   '<label for="exampleFormControlSelect2" class="col-form-label col-form-label-sm">Motivo</label>
			<select size="5" class="form-control form-control-sm" name="actionLog[codigoCli]" id="exampleFormControlSelect2" required >';
				foreach($desviacionKM  as $valor=>$clave)
				{
						if ($valor == ''){
						echo '<option  value="'.$clave.'" selected >'.$clave.'</option>';		
						}else {
						echo '<option  value="'.$clave.'" >'.$clave.'</option>';	
						}
				}			
	echo   '</select>
			</div>
			</div>';
	echo	'<div class="form-group col-md-12">
			<label for="descripcion" class="col-form-label col-form-label-sm">Descripcion :</label>
			<textarea class="form-control form-control-sm" id="descripcion" rows="3" name="actionLog[descripcion]" required  ></textarea>
			</div>';					
	echo   '</div>
			</div>';			
	echo 	'<input  name="actionLog[indicadorTema]" type="hidden" value="Desviacion KM">';
	echo 	'<input  name="actionLog[fechaPlan]" type="hidden" value="'.$Fecha.'">';
	echo 	'<input  name="actionLog[ruta]" type="hidden" value="'.$Ruta.'">';
	echo 	'<input  name="actionLog[vehiculo]" type="hidden" value="'.$Vehiculo.'">';
	echo 	'<input  name="actionLog[empresa]" type="hidden" value="'.$Emptp.'">';				
            html_causa_raiz();
			html_action_log('Desviacion_KM');
        break;
    case "Desviacion_Tiempo":
			if(isset($_POST['actionLog'])){
			insertar_action_log($_POST['actionLog']);
			}
	echo   '<div class="card mb-3"><form action="'.$enlace_actual.'" method="POST"><div class="card mb-3">
			<div class="card-header">'.$Indicador.' | '.$Ruta.' | '.$Fecha.' | '.$Vehiculo.' | '.$Emptp.'</div>
			<div class="card-body">';			 	  
	echo   '<div class="form-row">'; 
	echo   '<div class="form-group col-md-12">';
	echo   '<label for="exampleFormControlSelect2" class="col-form-label col-form-label-sm">Motivo</label>
			<select size="5" class="form-control form-control-sm" id="exampleFormControlSelect2"  name="actionLog[codigoCli]"  required >';
				foreach($desviacionTie  as $valor=>$clave)
				{
						if ($valor == ''){
						echo '<option  value="'.$clave.'" selected >'.$clave.'</option>';		
						}else {
						echo '<option  value="'.$clave.'" >'.$clave.'</option>';	
						}
				}			
	echo   '</select>
			</div>
			</div>';
	echo	'<div class="form-group col-md-12">
			<label for="descripcion" class="col-form-label col-form-label-sm">Descripcion :</label>
			<textarea class="form-control form-control-sm" id="descripcion" rows="3" name="actionLog[descripcion]" required  ></textarea>
			</div>';					
	echo   '</div>
			</div>';					
	echo 	'<input  name="actionLog[indicadorTema]" type="hidden" value="Desviacion Tiempo">';
    echo 	'<input  name="actionLog[fechaPlan]" type="hidden" value="'.$Fecha.'">';
	echo 	'<input  name="actionLog[ruta]" type="hidden" value="'.$Ruta.'">';
	echo 	'<input  name="actionLog[vehiculo]" type="hidden" value="'.$Vehiculo.'">';
	echo 	'<input  name="actionLog[empresa]" type="hidden" value="'.$Emptp.'">';			
            html_causa_raiz();
			html_action_log('Desviacion Tiempo');		
        break;
    case "Eficiencia_Modulacion":
			if(isset($_POST['actionLog'])){
			insertar_action_log($_POST['actionLog']);
			}		
			$db=Db::conectar();
			$sql="SELECT mr,cjasrechazadas,comentario,autoriza_rech,Transporte,Ruta,Vehiculo,Codigo,Cliente,Direccion,Entrega,Ciudad 
				FROM `t77_rs` 
			WHERE alerta=1 AND centro=:centro AND Ruta=:Ruta AND Fecha=:Fecha";
			$selectr=$db->prepare($sql);
		    $selectr->bindValue('centro',$idcentro);
			$selectr->bindValue('Ruta',$Ruta);
			$selectr->bindValue('Fecha',$Fecha);
			$selectr->execute();
	echo   '<div class="card mb-3"><form action="'.$enlace_actual.'" method="POST"><div class="card mb-3">
			<div class="card-header">'.$Indicador.' | '.$Ruta.' | '.$Fecha.' | '.$Vehiculo.' | '.$Emptp.'</div>
			<div class="card-body">';			 	  
	echo   '<div class="form-row">'; 
	echo   '<div class="form-group col-md-12">';
	echo   '<label for="exampleFormControlSelect2" class="col-form-label col-form-label-sm">Cliente | Cajas Plan | Cajas Rechazo | Comentario </label>
			<select size="5" class="form-control form-control-sm" name="actionLog[codigoCli]" id="exampleFormControlSelect2" required >'; 
			  while ($row=$selectr->fetch()){
				$datos_cab = $row['Codigo'].' '.$row['Cliente'].' | '.$row['Entrega'].' | '.$row['cjasrechazadas'].' | '.$row['comentario'];
				echo '<option value="'.$datos_cab.'">'.$datos_cab.'</option>';
			  	}
				Db::desconectar();
	echo   '</select>
			</div>
			</div>';
	echo	'<div class="form-group col-md-12">
			<label for="descripcion" class="col-form-label col-form-label-sm">Descripcion :</label>
			<textarea class="form-control form-control-sm" id="descripcion" rows="3" name="actionLog[descripcion]" required  ></textarea>
			</div>';						
	echo   '</div>
			</div>';				
	echo 	'<input  name="actionLog[indicadorTema]" type="hidden" value="Eficiencia Modulacion">';
	echo 	'<input  name="actionLog[fechaPlan]" type="hidden" value="'.$Fecha.'">';
	echo 	'<input  name="actionLog[ruta]" type="hidden" value="'.$Ruta.'">';
	echo 	'<input  name="actionLog[vehiculo]" type="hidden" value="'.$Vehiculo.'">';
	echo 	'<input  name="actionLog[empresa]" type="hidden" value="'.$Emptp.'">';		
            html_causa_raiz();
			html_action_log('Eficiencia Modulacion');		
        break;
    case "Rate_My_Delivery":
			if(isset($_POST['actionLog'])){
			insertar_action_log($_POST['actionLog']);
			}		
			$db=Db::conectar();
			$sql="SELECT CodCliente,Rating,Motivo,Comentario,NombreCliente FROM `t77_rmd` WHERE `id_centro`=:id_centro AND `Fecha`=:Fecha AND Ruta=:Ruta";
			$selectr=$db->prepare($sql);
		    $selectr->bindValue('id_centro',$idcentro);
			$selectr->bindValue('Ruta',$Ruta);
			$selectr->bindValue('Fecha',$Fecha);
			$selectr->execute();
	echo   '<div class="card mb-3"><form action="'.$enlace_actual.'" method="POST"><div class="card mb-3">
			<div class="card-header">'.$Indicador.' | '.$Ruta.' | '.$Fecha.' | '.$Vehiculo.' | '.$Emptp.'</div>
			<div class="card-body">';			 	  
	echo   '<div class="form-row">'; 
	echo   '<div class="form-group col-md-12">';
	echo   '<label for="exampleFormControlSelect2" class="col-form-label col-form-label-sm">Cliente | Rating | Comentario </label>
			<select size="5" class="form-control form-control-sm" id="exampleFormControlSelect2" name="actionLog[codigoCli]" required >'; 
			  while ($row=$selectr->fetch()){
				$datos_cab = $row['CodCliente'].' '.$row['NombreCliente'].' | '.$row['Rating'].' | '.$row['Motivo'].' '.$row['Comentario'];
				echo '<option value="'.$datos_cab.'">'.$datos_cab.'</option>';
			  	}
				Db::desconectar();
	echo   '</select>
			</div>
			</div>';
	echo	'<div class="form-group col-md-12">
			<label for="descripcion" class="col-form-label col-form-label-sm">Descripcion :</label>
			<textarea class="form-control form-control-sm" id="descripcion" rows="3" name="actionLog[descripcion]" required  ></textarea>
			</div>';						
	echo   '</div>
			</div>';				
	echo 	'<input  name="actionLog[indicadorTema]" type="hidden" value="Rate My Delivery">';
	echo 	'<input  name="actionLog[fechaPlan]" type="hidden" value="'.$Fecha.'">';
	echo 	'<input  name="actionLog[ruta]" type="hidden" value="'.$Ruta.'">';
	echo 	'<input  name="actionLog[vehiculo]" type="hidden" value="'.$Vehiculo.'">';
	echo 	'<input  name="actionLog[empresa]" type="hidden" value="'.$Emptp.'">';			
            html_causa_raiz();
			html_action_log('Rate My Delivery');		
        break;		
		} 
	}
	switch ($FMfcgxwLtkdRLdXFNVNgdJkHCHKrmRpl):
	case "Actionlog":
	
	if(isset($_GET['Indicador'],$_GET['Ruta'],$_GET['Fecha'],$_GET['Vehiculo'])){
	action_log_reporte_diario($_GET['Indicador'],$_GET['Ruta'],$_GET['Fecha'],$_GET['Vehiculo'],$_GET['Empresa']);
	}	
	
	break;	
	case "Registrar":
	if(isset($_GET['Indicador'],$_GET['Ruta'],$_GET['Fecha'],$_GET['Vehiculo'],$_GET['Empresa'])){
	//action_log_reporte_diario($_GET['Indicador'],$_GET['Ruta'],$_GET['Fecha'],$_GET['Vehiculo'],$_GET['Empresa']);
	}	
	break;	
	default:
     tabla_reporte_diario();
	 modal_fecha();
	endswitch;
	} else {
	echo $html_acceso;		
	}
	}
	require('../footer.php');
	ob_end_flush();	
	?>