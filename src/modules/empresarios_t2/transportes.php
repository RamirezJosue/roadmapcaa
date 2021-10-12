<?php
ob_start();
$accesos = basename(dirname(__FILE__));
require_once('../../includes/ini.php');
require_once('../../bd/crud_usuario.php');
$crud = new CrudUsuario();
if ($usuarioestado == 0) {
	echo $html_bloqueo;
} else {
	$arraruser = explode(',', $usuarioaccesos);
	if (in_array($accesos, $arraruser)) {
		if ($usuariotipo == 0) : $aid_super = 0;
		else : $aid_super = 1;
		endif;
		/*inicio vefifia si tiene permisos de adminrepartos */
		if (in_array("adminrepartos", $arraruser)) : $adminrepartos = 1;
		else : $adminrepartos = 0;
		endif;
		if ($aid_super == 1 || $adminrepartos == 1) : $disableform = '';
		else : $disableform = 'disabled';
		endif;
		/*fin vefifia si tiene permisos de adminrepartos */
		$bootstrapjs =  1;
		$datatablesjs = 1;
		$datatablesjsresponsive = 1;
		require('../head.php');
		if (isset($_GET['fechaselec'])) :
			$fecha_form = $_GET['fechaselec'];
			$fechars = $_GET['fechaselec'];
		else :
			$fechars = $fechars;
			$fecha_form = $fecha;
		endif;
		// fin head
		$actClasifEnv = 1;
		$hash = 'Xa6UYNOhEMOu5OUPtqaGAUiflsig';
		if (isset($_GET['FMfcgxwLtkdRLdXFNVNgdJkHCHKrmRpl'])) :
			$FMfcgxwLtkdRLdXFNVNgdJkHCHKrmRpl = $_GET['FMfcgxwLtkdRLdXFNVNgdJkHCHKrmRpl'];
		else :
			$FMfcgxwLtkdRLdXFNVNgdJkHCHKrmRpl = "";
		endif;
		$enlace = $sitio . 'modulos/' . $accesos;
		$enlace_actual = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		$archivo = basename(__FILE__, ".php");
		function modal_entregar($indx, $id, $actClasifEnv, $cjsEntrega, $ruta, $fecha, $viaje)
		{
			global  $enlace_actual;
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
							<form class="needs-validation" action="<?php echo $enlace_actual; ?>" method="POST" novalidate>
								<input type="hidden" name="EntregarModal[IdEntrega]" value="<?php echo $id; ?>">
								<input type="hidden" name="EntregarModal[CjasEntregadas]" value="<?php echo $cjsEntrega; ?>">
								<?php if ($actClasifEnv == 1) { ?>
									<div class="form-group">
										<label for="validationCustom02">Clasifica envases</label>
										<select class="custom-select" id="validationCustom02" name="EntregarModal[ClasificaEnvases]" required>
											<option value="">--</option>
											<?php
											$db = Db::conectar();
											$selectclasenv = $db->prepare("SELECT id, descripcion FROM t77_rs_check");
											$selectclasenv->execute();
											while ($registclasenv = $selectclasenv->fetch()) {
												if ($registclasenv['id'] == '') {
													echo '<option  value="' . $registclasenv['id'] . '" selected >' . $registclasenv['descripcion'] . '</option>';
												} else {
													echo '<option  value="' . $registclasenv['id'] . '" >' . $registclasenv['descripcion'] . '</option>';
												}
											}
											Db::desconectar();
											?>
										</select>
									</div>
									<div class="form-group">
										<label for="validationCustom01">Cajas clasificadas</label>
										<input type="text" class="form-control" id="validationCustom01" value="" name="EntregarModal[CjasClasificadas]" required>
									</div>
								<?php } else {
								?>
									<input type="hidden" value="" name="EntregarModal[ClasificaEnvases]" required>
									<input type="hidden" value="0" name="EntregarModal[CjasClasificadas]" required>
								<?php
								}
								?>
						</div>
						<div class="modal-footer">
							<input type="hidden" name="EntregarModal[FMfcgxwLtkdRLdXFNVNgdJkHCHKrmRpl]" value="AlertarWS">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
							<button type="submit" class="btn btn-primary">Entregar</button>
						</div>
						</form>
					</div>
				</div>
			</div>
		<?php
		}
		function modal_entregar_todos($indx, $ruta, $fecha, $viaje)
		{
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
						<form method="GET">
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
		function modal_eta($id, $ruta, $fecha, $viaje, $Llega, $nombre)
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
									<label>LLega : <?php echo $Llega; ?> modificar a : </label>
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
		function modal_rechazar($indx, $id, $mr, $comentario, $autorizaRech, $CjsEntrega)
		{
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
							<form class="needs-validation" novalidate method="POST">
								<input type="hidden" name="modalRechazo[idrechazo]" value="<?php echo $id; ?>">
								<input type="hidden" name="modalRechazo[confirmarrchz]" value="Rechazar Pedido">
								<input type="hidden" name="modalRechazo[grabaralerta]" value="grabaralerta">
								<input type="hidden" name="modalRechazo[indx]" value="<?php; ?>">
								<input type="hidden" name="modalRechazo[Fecha]" value="<?php; ?>">
								<input type="hidden" name="modalRechazo[FMfcgxwLtkdRLdXFNVNgdJkHCHKrmRpl]" value="GrabarRechazo">
								<input type="hidden" value="0" name="modalRechazo[ClasificaEnvRec]">
								<input type="hidden" value="0" name="modalRechazo[CjasClasifRech]">
								<div class="form-group">
									<label for="validationCustom01">Motivo rechazo</label>
									<select class="form-control form-control-sm" id="validationCustom02" required name="modalRechazo[mr]">
										<option value="">Seleccionar</option>
										<?php
										$db = Db::conectar();
										$select = $db->prepare("SELECT id, descripcion FROM t77_mr WHERE st=1");
										$select->execute();
										while ($regis = $select->fetch()) {
											if ($regis['descripcion'] == $mr) {
												echo '<option  value="' . $regis['descripcion'] . '" selected >' . $regis['descripcion'] . '</option>';
											} else {
												echo '<option  value="' . $regis['descripcion'] . '" >' . $regis['descripcion'] . '</option>';
											}
										}
										Db::desconectar();
										?>
									</select>
								</div>
								<div class="form-group">
									<label for="validationCustom01">Comentario rechazo</label>
									<textarea name="modalRechazo[comentarios]" class="form-control form-control-sm" id="validationCustom03" required><?php echo $comentario; ?></textarea>
								</div>
								<div class="form-group">
									<label for="validationCustom02">Quien autoriza rechazo</label>
									<select class="custom-select" id="validationCustom02" required name="modalRechazo[autoriza_rech]">
										<option value="">
											<--Quien autoriza rechazo-->
										</option>
										<?php
										$db = Db::conectar();
										$select = $db->prepare("SELECT id, descripcion FROM t77_autoriza_rech");
										$select->execute();
										while ($regist = $select->fetch()) {

											if ($regist['descripcion'] == $autorizaRech) {
												echo '<option  value="' . ucwords($regist['descripcion']) . '" selected >' . ucwords($regist['descripcion']) . '</option>';
											} else {
												echo '<option  value="' . ucwords($regist['descripcion']) . '" >' . ucwords($regist['descripcion']) . '</option>';
											}
										}
										Db::desconectar();
										?>
									</select>
								</div>
								<div class="form-group">
									<label for="validationCustom01">Cajas Programadas</label>
									<input type="text" class="form-control" value="<?php echo $CjsEntrega; ?>" disabled>
									<input type="hidden" class="form-control" value="<?php echo $CjsEntrega; ?>" name="modalRechazo[CjsEntrega]">
								</div>
								<div class="form-group">
									<label for="validationCustom01">Cajas rechazadas/modificadas</label>
									<input type="text" class="form-control" id="validationCustom01" value="" required name="modalRechazo[cjasrechazadas]">
									<small id="emailHelp" class="form-text text-muted">El rechazo no deve ser mayor a lo
										programado</small>
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
		function rutas__()
		{
			global $idcentro, $fechars, $aid, $enlace_actual, $archivo;
		?>
			<div class="row">
				<div class="col-sm-12 p-0 bg-light">
					<div class="d-flex">
						<div class="p-2 bg-light">
							<div class="text-muted text-md-left font-weight-bolder">Resumen Por Zonas
								<?php echo $idcentro . ' | ' . $fechars; ?></div>
						</div>
					</div>
				</div>
			</div>
			<div class="form-row">
				<div class="mb-3">
					<form action="<?php echo $enlace_actual; ?>" method="GET">
						<label for="fecha">Fecha</label>
						<input type="date" name="fechaselec" id="fecha" value="<?php echo $fechars; ?>">
						<button type="submit" class="btn btn-danger btn-sm">Ver</button>
					</form>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12 p-0">
					<div class="table-responsive">
						<table id="transportesresponsive" data-order='[[ 1, "asc" ]]' data-page-length='25' class="display compact cell-border">
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
								$db = Db::conectar();
								$sql = "SELECT * FROM 
          (
          SELECT z.id_ruta, z.Transporte, z.`Ruta`, z.`Viaje`, z.`Vehiculo`, z.`FechaRS`,
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
          `Transporte`,
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
          FROM t77_rs WHERE `Fecha`= :Fecha AND centro = :centro /* AND Ruta=:Ruta */
               ) AS z GROUP BY z.id_ruta, z.Transporte, z.`Ruta`, z.`Viaje`,z.`Vehiculo`,z.`FechaRS`
          ) AS v LEFT JOIN 
          (
          SELECT * FROM `t77_rs_ruta_sif` WHERE centro = :centro AND fecha_plan = :fecha_plan
          ) AS s ON v.id_ruta = s.indx ORDER BY v.Ruta
		  ";
								$select = $db->prepare($sql);
								$select->bindValue('centro', $idcentro);
								$select->bindValue('Fecha', $fechars);
								$select->bindValue('fecha_plan', $fechars);
								//$select->bindValue('Ruta', strtoupper($aid));
								$select->execute();
								while ($registro = $select->fetch()) {
									$stkpi = $registro['st_kpi'];
									$idruta = $registro['id_ruta'];
									$LinkHora = '#';
									$idhr = $registro['id'];
									$FechaRS = $registro['FechaRS'];
									$Ruta = $registro['Ruta'];
									$Viaje = $registro['Viaje'];
									$Vehiculo = $registro['Vehiculo'];
									$inicio_conductor = $registro['inicio_conductor'];
									$salida_cd = $registro['salida_cd'];
									$llegada_cd = $registro['llegada_cd'];
									$ingreso_cd = $registro['ingreso_cd'];
									$fin_conductor = $registro['fin_conductor'];
									if ($stkpi >= 1) {
										$ckpi = 'success';
									} else {
										$ckpi = 'danger';
									}
									if (IS_NULL($inicio_conductor)) {
										//echo  'si'; 
										$LinkHora1 = '' . $archivo . '?&fecha=' . $FechaRS . '&ruta=' . $Ruta . '&viaje=' . $Viaje . '&vehiculo=' . $Vehiculo . '';
									} else {
										//echo  'no';
										$LinkHora1 = '#';
										$hora = '0000-00-00 00:00:00';
										switch ($hora) {
											case (($salida_cd == $hora) && ($llegada_cd == $hora) && ($ingreso_cd == $hora) && ($fin_conductor == $hora)):
												$LinkHora2 = '' . $archivo . '?&fecha=' . $FechaRS . '&ruta=' . $Ruta . '&viaje=' . $Viaje . '&vehiculo=' . $Vehiculo . '&tb=salida_cd&id=' . $idhr . '';
												break;
											case (($salida_cd != $hora) && ($llegada_cd == $hora) && ($ingreso_cd == $hora) && ($fin_conductor == $hora)):
												$LinkHora3 = '' . $archivo . '?&fecha=' . $FechaRS . '&ruta=' . $Ruta . '&viaje=' . $Viaje . '&vehiculo=' . $Vehiculo . '&tb=llegada_cd&id=' . $idhr . '';
												break;
											case (($salida_cd != $hora) && ($llegada_cd != $hora) && ($ingreso_cd == $hora) && ($fin_conductor == $hora)):
												$LinkHora4 = '' . $archivo . '?&fecha=' . $FechaRS . '&ruta=' . $Ruta . '&viaje=' . $Viaje . '&vehiculo=' . $Vehiculo . '&tb=ingreso_cd&id=' . $idhr . '';
												break;
											case (($salida_cd != $hora) && ($llegada_cd != $hora) && ($ingreso_cd != $hora) && ($fin_conductor == $hora)):
												$LinkHora5 = '' . $archivo . '?&fecha=' . $FechaRS . '&ruta=' . $Ruta . '&viaje=' . $Viaje . '&vehiculo=' . $Vehiculo . '&tb=fin_conductor&id=' . $idhr . '';
												break;
										}
									}
								?>
									<tr role="row">
										<td></td>
										<td><span class="badge badge-<?php echo $ckpi; ?>">&nbsp;</span>
											<?php echo $Ruta . '|' . $Viaje . '|' . $Vehiculo; ?></td>
										<td><?php echo $registro['CajasProgramadas']; ?></td>
										<td><?php echo $registro['ContactosProgramados']; ?></td>
										<td><?php echo $registro['ContactosAlertados']; ?></td>
										<td><?php echo $registro['ContactosRechazados']; ?></td>
										<td><?php echo $registro['ContactosEntregados']; ?></td>
										<td><?php echo round($registro['PorcientoAvance']); ?>%</td>
										<td>
											<button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#Tiem<?php echo $idruta; ?>"><i class="fa fa-truck" aria-hidden="true"></i></button>
											<button class="btn btn-danger btn-sm" onclick="location.href='<?php echo $archivo; ?>?FMfcgxwLtkdRLdXFNVNgdJkHCHKrmRpl=DetalleRuta&amp;ruta=<?php echo $registro['Ruta']; ?>&amp;fecha=<?php echo $registro['FechaRS']; ?>&amp;viaje=<?php echo $registro['Viaje']; ?>';"><i class="fa fa-check-square" aria-hidden="true"></i></button>
											<button class="btn btn-danger btn-sm" onclick="location.href='<?php echo $archivo; ?>?FMfcgxwLtkdRLdXFNVNgdJkHCHKrmRpl=DepositosBancarios';"><i class="fa fa-credit-card" aria-hidden="true"></i></button>
										</td>
									</tr>
									<input type="hidden" name="id" value="<?php echo $registro['id'] ?>">
									<div class="modal fade" id="Tiem<?php echo $idruta; ?>" tabindex="-1" aria-labelledby="tiempos" aria-hidden="true">
										<div class="modal-dialog">
											<div class="modal-content">
												<div class="modal-header">
													<h5 class="modal-title" id="exampleModalLabel">
														<?php echo $Ruta . ' - ' . $Vehiculo . ' Viaje ' . $Viaje; ?></h5>
													<button type="button" class="close" data-dismiss="modal" aria-label="Close">
														<span aria-hidden="true">&times;</span>
													</button>
												</div>
												<div class="modal-body">
													<form action="" method="post">
														<div class="list-group">
															<a href="<?php echo isset($LinkHora1) ? $LinkHora1 : '#'; ?>" class="list-group-item list-group-item-action <?php echo isset($LinkHora1) ? '' : 'disabled'; ?>">
																<h5 class="mb-1"> Inicio ruta </h5>
																<small><?php echo $inicio_conductor; ?></small>
															</a>
															<a href="<?php echo isset($LinkHora2) ? $LinkHora2 : '#'; ?>" class="list-group-item list-group-item-action <?php echo isset($LinkHora2) ? '' : 'disabled'; ?>">
																<h5 class="mb-1"> Salida CD </h5>
																<small><?php echo $salida_cd; ?></small>
															</a>
															<a href="<?php echo isset($LinkHora3) ? $LinkHora3 : '#'; ?>" class="list-group-item list-group-item-action <?php echo isset($LinkHora3) ? '' : 'disabled'; ?>">
																<h5 class="mb-1"> Llegada CD </h5>
																<small><?php echo $llegada_cd; ?></small>
															</a>
															<a href="<?php echo isset($LinkHora4) ? $LinkHora4 : '#'; ?>" class="list-group-item list-group-item-action <?php echo isset($LinkHora4) ? '' : 'disabled'; ?>">
																<h5 class="mb-1"> Ingreso CD </h5>
																<small><?php echo $ingreso_cd; ?></small>
															</a>
															<a href="<?php echo isset($LinkHora5) ? $LinkHora5 : '#'; ?>" class="list-group-item list-group-item-action <?php echo isset($LinkHora5) ? '' : 'disabled'; ?>">
																<h5 class="mb-1"> Fin ruta </h5>
																<small><?php echo $fin_conductor; ?></small>
															</a>
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
		function rutas__detalle($fecha, $ruta, $viaje)
		{
			global $idcentro, $hash, $enlace_actual, $archivo;
			require_once('../../bd/array/select_motivos.php');
			if (isset($_POST['demora']) && is_array($_POST['demora'])) {
				$demora = $_POST['demora'];
				$indx 		= $demora['indx'];
				$tiemRs 	= $demora['tiemRs'];
				$demoraReal = $demora['horaReal'] . ':' . $demora['minReal'] . ':00';
				$motivo 	= $demora['motivo'];
				$comentario	= $demora['comentario'];
				$db = Db::conectar();
				$insert = $db->prepare("INSERT INTO `t77_rs_demoras`(`id_d`, `indx_d`, `tiempo_demora_rs`, `tiempo_demora_real`, `motivo_d`, `comentario_d`, `centro_d`) 
								  VALUES (null,'$indx', '$tiemRs', '$demoraReal', '$motivo', '$comentario', '$idcentro')");
				$insert->execute();
				$lastInsertId = $db->lastInsertId();
				if ($lastInsertId > 0) {
					echo '<div class="alert alert-success" role="alert">Se registro</div>';
				} else {
					echo '<div class="alert alert-danger" role="alert">no se pudo registrar</div>';
				}
				Db::desconectar();
			}
		?>
			<script>
				function comprobar_check_enviar(obj) {
					if (obj.checked) {
						document.getElementById('boton_ck_env').style.display = "";
					} else {
						document.getElementById('boton_ck_env').style.display = "none";
					}
				}
				(function() {
					var form = document.getElementById('ruta_detalle');
					form.addEventListener('submit', function(event) {
						// si es false entonces que no haga el submit
						if (!confirm('Realmente desea continuar ?')) {
							event.preventDefault();
						}
					}, false);
				})();
			</script>
			<form action="<?php echo $enlace_actual; ?>" method="POST" id="ruta_detalle">
				<div class="row">
					<div class="col-sm-12 p-0 bg-light">
						<div class="d-flex">
							<div class="p-2 bg-light">
								<div class="text-muted text-md-left font-weight-bolder"><?php echo $ruta . '|' . $viaje . '|' . $fecha; ?>
									<button type="button" class="btn btn-danger btn-sm" onclick="location.href='<?php echo $archivo; ?>?&fecha=<?php echo $fecha; ?>&e=<?php echo $hash; ?>';"><i class="fa fa-arrow-left" aria-hidden="true"></i></button>
									<button type="submit" class="btn btn-danger btn-sm" name="encuestarepaso">Grabar</button>
									<button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#entregar_ped_todos">Entregar todos</button>
									<button type="submit" class="btn btn-danger btn-sm" name="encuestarepaso" id="boton_ck_env" readonly style="display:none">Entregar selección</button>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12 p-0">
						<div class="table-responsive">
							<table id="transclienresponsive" data-order='[[ 0, "asc" ]]' data-page-length='50' class="display compact cell-border">
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
									$db = Db::conectar();
									$sql = "SELECT  * 
		  FROM (
		  SELECT
		  a.`id`, a.`indx`, a.`entregado`, a.`alerta`, a.`rechazo`, a.`mr`, a.`cjasrechazadas`, a.`centro`, a.`comentario`, a.`dni`, a.`fechahoraalerta`, a.`registrofin`, 
		  a.`autoriza_rech`, a.`clasifica_envases`, a.`cjasclasificadas`, a.`repaso`, a.`encuesta`, a.`st_kpi`, a.`reprogramado`, a.`Transporte`, a.`Ruta`, a.`Vehiculo`, 
		  a.`Fecha`, a.`Codigo`, a.`TipoCliente`, a.`ZNPVTA`, a.`ZNTVTA`, a.`Cliente`, a.`Direccion`, a.`Abre`, a.`Cierra`, a.`Viaje`, a.`DistKm`, a.`Sec2`, a.`Sec1`, a.`Entrega`, 
		  a.`Recojo`, a.`Llega`, a.`Sale`, TIMEDIFF(a.Sale, a.Llega) as minutos_atencion_rs , a.`Ciudad`, a.`Longitud`, a.`Latitud`, a.`HL`, a.`Packs`, a.`SupervisionID`, a.`SalesManagement`, 
		  a.`DistanciaPlan`, a.`Inicio`, a.`Fin`, a.`CodEt`, a.`TelefonoCli`, a.`TipoPedido`, a.`ValorContado`, a.`VarlorCredito`, a.`DeudaEnv`, a.`DeudaProd`, a.`Tipo_Riesgo`, a.`Servicio_Flex`, a.`Tipo_NPS`, a.`RMD`, a.`TIPO`, a.`Rechazos`,
		  b.codcli,b.observacion
		FROM 
		  (
		SELECT * FROM t77_rs WHERE Ruta=:Ruta AND Fecha=:Fecha AND Viaje=:Viaje AND centro=:centro 
		  ) AS a LEFT JOIN 
		  (
		SELECT codcli,observacion FROM `t77_nps` WHERE  centro=:centro  
		  ) AS b  ON a.Codigo = b.codcli ORDER BY a.Sec1 ASC 
		  ) AS z LEFT JOIN (SELECT * FROM `t77_rs_demoras`) AS v
		  ON z.indx = v.indx_d
		  ";
									$select = $db->prepare($sql);
									$select->bindValue('Ruta', $ruta);
									$select->bindValue('Fecha', $fecha);
									$select->bindValue('Viaje', $viaje);
									$select->bindValue('centro', $idcentro);
									$select->execute();
									while ($registro = $select->fetch(PDO::FETCH_ASSOC)) {
										$Llega = substr($registro['Llega'], 0, 5);
										if (($registro['entregado'] == 1) and ($registro['alerta'] == 0) and ($registro['rechazo'] == 0)) {
											$msjestado = "Entregado";
											$class = 'class="table-success"';
											$valuebutton = ' ';
											$disabled = 'checked disabled';
											$reprogramadodisb = 'disabled';
										} else if (($registro['entregado'] == 1) and ($registro['alerta'] == 1) and ($registro['rechazo'] == 0)) {
											$msjestado = "Entregado";
											$class = 'class="table-warning"';
											$valuebutton = ' ';
											$disabled = 'checked disabled';
											$reprogramadodisb = 'disabled';
										} else if (($registro['entregado'] == 0) and ($registro['alerta'] == 1) and ($registro['rechazo'] == 1)) {
											$msjestado = "Rechazado";
											$class = 'class="table-danger"';
											$valuebutton = ' ';
											$disabled = 'checked disabled';
											$reprogramadodisb = 'disabled';
										} else if (($registro['entregado'] == 0) and ($registro['alerta'] == 0) and ($registro['rechazo'] == 1)) {
											$msjestado = "Rechazado";
											$class = 'class="table-danger"';
											$valuebutton = ' ';
											$disabled = 'checked disabled';
											$reprogramadodisb = 'disabled';
										} else if (($registro['entregado'] == 0) and ($registro['alerta'] == 1) and ($registro['rechazo'] == 0)) {
											$msjestado = "Alertado";
											$class = 'class="table-warning"';
											$valuebutton = ' ';
											$disabled = '';
											$reprogramadodisb = '';
										} else if (($registro['entregado'] == 0) and ($registro['alerta'] == 0) and ($registro['rechazo'] == 0)) {
											$msjestado = "Pendiente";
											$class = 'class="table-light"';
											$valuebutton = ' ';
											$disabled = '';
											$reprogramadodisb = '';
										}
										if (($registro['repaso'] == 1)) {
											$disabledcheckedEnt = 'checked disabled';
										} else {
											$disabledcheckedEnt = '';
										}
										if (($registro['encuesta'] == '')) {
											$disabledselect = '';
										} else {
											$disabledselect = 'disabled';
										}
										if (is_null($registro['codcli'])) {
											$classnps = "";
										} else {
											$classnps = 'class="text-danger"';
										}
										if (is_null($registro['indx_d'])) {
											$indx_d = "";
										} else {
											$indx_d = 'disabled';
										}
										if ($registro['reprogramado'] == 1) {
											$iconclock = '<i class="fa fa-clock-o" aria-hidden="true"></i>';
										} else {
											$iconclock = '';
										}
									?>
										<tr <?php echo $class; ?>>
											<td></td>
											<td <?php echo $classnps; ?>>
												<?php echo $registro['Sec1'] . substr($registro['Cliente'], 0, 25) . '<br><small>' . $registro['Direccion'] . '-' . $registro['Ciudad'] . ' - Llega ' . $Llega . ' ' . $iconclock . '</small>'; ?>
											</td>
											<td><input name="ck_enviar[<?php echo $registro['id']; ?>]" type="checkbox" id="ck_enviar" class="form-check-input" onChange="comprobar_check_enviar(this);" <?php echo $disabled; ?>>
												<?php echo number_format($registro['Entrega'], 1, '.', '') ?></td>
											<td><?php echo substr($registro['Abre'], 0, 5) . ' ' . substr($registro['Cierra'], 0, 5); ?></td>
											<td><?php echo $registro['Codigo']; ?></td>
											<td>&nbsp;&nbsp;&nbsp;<input type="checkbox" name="repaso[<?php echo $registro['id']; ?>]" class="form-check-input" id="exampleCheck1" <?php echo $disabledcheckedEnt; ?>></td>
											<td class="text-center">
												<div class="form-group">
													<select class="form-control form-control-sm" name="encuesta[<?php echo $registro['id']; ?>]" <?php echo $disabledselect; ?>>
														<option value="">
															<-->
														</option>
														<?php
														$db = Db::conectar();
														$encuesta = $db->prepare("SELECT id_desc, descripcion FROM t77_pregunta_encuesta WHERE pregunta='clienteyape'");
														$encuesta->execute();
														while ($rowencuesta = $encuesta->fetch()) {
															if ($registro['encuesta'] == $rowencuesta['id_desc']) {
																echo '<option  value="' . $rowencuesta['id_desc'] . '" selected >' . $rowencuesta['descripcion'] . '</option>';
															} else {
																echo '<option  value="' . $rowencuesta['id_desc'] . '" >' . $rowencuesta['descripcion'] . '</option>';
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
			<td class="text-center">
				<div class="btn-group btn-group-sm" role="group" aria-label="botones">
					<button type="button" class="btn btn-success btn-sm" onclick="location.href='<?php echo $archivo; ?>?FMfcgxwLtkdRLdXFNVNgdJkHCHKrmRpl=AlertarWS&amp;id=<?php echo $registro['id']; ?>';"><i class="fa fa-whatsapp"></i> <?php echo $valuebutton; ?></button>
					<button type="button" class="btn btn-danger btn-sm" onclick="location.href='encuestarepartos?exa=crear_examen_check&amp;cod=<?php echo $registro['Codigo']; ?>&amp;ruta=<?php echo $registro['Ruta']; ?>&amp;fecha=<?php echo $registro['Fecha']; ?>&amp;viaje=<?php echo $registro['Viaje']; ?>';">Enc</button>
					<button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modalETA<?php echo $registro['id']; ?>" <?php echo $reprogramadodisb; ?>>ETA</button>
					<button type="button" class="btn btn-secondary btn-sm" data-toggle="modal" data-target="#<?php echo $registro['indx']; ?>" <?php echo $indx_d; ?>><i class="fa fa-clock-o" aria-hidden="true"></i></button>
				</div>
				<?php
					if (($registro['entregado'] == 1) || ($registro['rechazo'] == 1)) {
					} else {
						modal_eta($registro['id'], $registro['Ruta'], $registro['Fecha'], $registro['Viaje'], $Llega, $registro['Cliente']);
					}
				?>
				<div class="modal fade" id="<?php echo $registro['indx']; ?>" tabindex="-1" aria-labelledby="<?php echo $registro['indx']; ?>Label" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="<?php echo $registro['indx']; ?>Label">
									<?php echo $registro['Cliente']; ?></h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<form action="<?php echo $enlace_actual; ?>" method="POST">
								<div class="modal-body">
									<div class="form-group">
										<label for="time_rs">Tiempo RS</label>
										<input type="text" class="form-control form-control-sm" id="time_rs" disabled value="<?php echo $registro['minutos_atencion_rs']; ?>">
										<input type="hidden" name="demora[indx]" value="<?php echo $registro['indx']; ?>">
										<input type="hidden" name="demora[tiemRs]" value="<?php echo $registro['minutos_atencion_rs']; ?>">
									</div>
									<div class="form-group">
										<label for="time_rs">Demora Tiempo Real</label>
										<div class="row ">
											<div class="col">
												<span class="help-block text-muted small-font">Horas </span>
												<select class="form-control form-control-sm" name="demora[horaReal]" required>
													<option value="">--</option>
													<option value="00">00</option>
													<option value="01">01</option>
													<option value="02">02</option>
													<option value="03">03</option>
													<option value="04">04</option>
													<option value="05">05</option>
													<option value="06">06</option>
													<option value="07">07</option>
													<option value="08">08</option>
													<option value="09">09</option>
												</select>
											</div>
											<div class="col">
												<span class="help-block text-muted small-font">Minutos </span>
												<select class="form-control form-control-sm" name="demora[minReal]" required>
													<option value="">--</option>
													<option value="01">01</option>
													<option value="02">02</option>
													<option value="03">03</option>
													<option value="04">04</option>
													<option value="05">05</option>
													<option value="06">06</option>
													<option value="07">07</option>
													<option value="08">08</option>
													<option value="09">09</option>
													<option value="10">10</option>
													<option value="11">11</option>
													<option value="12">12</option>
													<option value="13">13</option>
													<option value="14">14</option>
													<option value="15">15</option>
													<option value="16">16</option>
													<option value="17">17</option>
													<option value="18">18</option>
													<option value="19">19</option>
													<option value="20">20</option>
													<option value="21">21</option>
													<option value="22">22</option>
													<option value="23">23</option>
													<option value="24">24</option>
													<option value="25">25</option>
													<option value="26">26</option>
													<option value="27">27</option>
													<option value="28">28</option>
													<option value="29">29</option>
													<option value="30">30</option>
													<option value="31">31</option>
													<option value="32">32</option>
													<option value="33">33</option>
													<option value="34">34</option>
													<option value="35">35</option>
													<option value="36">36</option>
													<option value="37">37</option>
													<option value="38">38</option>
													<option value="39">39</option>
													<option value="40">40</option>
													<option value="41">41</option>
													<option value="42">42</option>
													<option value="43">43</option>
													<option value="44">44</option>
													<option value="45">45</option>
													<option value="46">46</option>
													<option value="47">47</option>
													<option value="48">48</option>
													<option value="49">49</option>
													<option value="50">50</option>
													<option value="51">51</option>
													<option value="52">52</option>
													<option value="53">53</option>
													<option value="54">54</option>
													<option value="56">56</option>
													<option value="57">57</option>
													<option value="58">58</option>
													<option value="59">59</option>
												</select>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label for="motivo_demora">Motivo Demora</label>
										<select class="form-control form-control-sm" name="demora[motivo]" required>
											<option value=""> Seleccionar </option>';
											<?php
											foreach ($motivoDemora  as $valor => $clave) {
												if ($valor == '') {
													echo '<option  value="' . $valor . '" selected >' . $clave . '</option>';
												} else {
													echo '<option  value="' . $valor . '" >' . $clave . '</option>';
												}
											}
											?>
										</select>
									</div>
									<div class="form-group">
										<label for="comentario_tiempo">Comentario</label>
										<textarea class="form-control form-control-sm" id="comentario_tiempo" rows="3" name="demora[comentario]" required></textarea>
									</div>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
									<button type="submit" class="btn btn-danger">Guardar</button>
								</div>
						</div>
						</form>
					</div>
				</div>
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
		function rutas__ws($id)
		{
			global $idcentro, $hash, $actClasifEnv, $archivo;
			$db = Db::conectar();
			$select = $db->prepare("
  SELECT * FROM 
  (SELECT * FROM t77_rs WHERE id = :id AND centro=:centro) AS r 
	LEFT JOIN 
  (SELECT centro as ct,zv,nombreSup,nombreAc,telfAc,telfSup FROM t77_zv_detalle WHERE centro = :centro) AS z 
	ON r.ZNPVTA = z.zv
  ");
			$select->bindValue('id', $id);
			$select->bindValue('centro', $idcentro);
			$select->execute();
			while ($registro = $select->fetch()) {
				$lng = str_replace(",", ".", $registro['Longitud']);
				$lat = str_replace(",", ".", $registro['Latitud']);
				$codigowsp = $registro['Codigo'];
				$clientewsp = str_replace(" ", "+", $registro['Cliente']);
				$supervisorwsp = str_replace(" ", "+", $registro['nombreSup']);
				$agentewsp = str_replace(" ", "+", $registro['nombreAc']);
				$cajaswsp = $registro['Entrega'];
				$comentarioswsp = str_replace(" ", "+", $registro['comentario']);
				$comentario = $registro['comentario'];
				$autoriza_rech = $registro['autoriza_rech'];
				$ruta = $registro['Ruta'];
				$indx = $registro['indx'];
				$mr = $registro['mr'];
				$TipoPedido = $registro['TipoPedido'];
                $TipoCliente = $registro['TipoCliente'];
				$Tipo_Riesgo = $registro['Tipo_Riesgo'];
				$Servicio_Flex = $registro['Servicio_Flex'];
				$Tipo_NPS = $registro['Tipo_NPS'];
				$FR = $registro['TIPO'];
				$Rechazos = $registro['Rechazos'];
				if ($registro['RMD'] == 0) {
					$rmd = '';
				} else {
					$rmd = $registro['RMD'];
				}
				if ($registro['ZNPVTA'] == '') {
					$zonavta = $registro['zonatv'];
				} else {
					$zonavta = $registro['ZNPVTA'];
				}
				$ciudad = $registro['Ciudad'];
				$urlwsp = "https://bit.ly/37GA070";
				$msjwsp = "*Codigo:*+" . $codigowsp . "%0D%0A";
				$msjwsp .= "*Nombre:*+" . $clientewsp . "%0D%0A";
				$msjwsp .= "*Supervisor:*+" . $supervisorwsp . "%0D%0A";
				$msjwsp .= "*BDR:*+" . $zonavta . ' ' . $agentewsp . "%0D%0A";
				$msjwsp .= "*Pedido|Cliente|Riesgo:*+" . $TipoPedido .' '. $TipoCliente .' '. $Tipo_Riesgo .' '. $Tipo_NPS . "%0D%0A";
				$msjwsp .= "*Rechazos:*+" . $FR .' '.$Rechazos. "%0D%0A";
				$msjwsp .= "*RMD|Servicio Flex:*+" . $rmd .' '.$Servicio_Flex. "%0D%0A";
				$msjwsp .= "*Cajas:*+" . $cajaswsp . "%0D%0A";
				$msjwsp .= "*Ruta:*+" . $ruta . "%0D%0A";
				$msjwsp .= "*Motivo:*+" . $mr . "%0D%0A";
				$msjwsp .= "*Localidad:*+" . $ciudad . "%0D%0A+" . $comentarioswsp . "+%0D%0A" . $urlwsp . "";
				if ($registro['cjasrechazadas'] == 0) {
					$entregarechazo = $registro['Entrega'];
				} else if (($registro['cjasrechazadas'] - $registro['Entrega']) == 0) {
					$entregarechazo = $registro['Entrega'];
				} else {
					$entregarechazo = $registro['cjasrechazadas'];
				}
				if ($registro['rechazo'] == 1 or $registro['entregado'] == 1) {
					$disabled = "disabled";
				} else {
					$disabled = "";
				}
		?>
			<div class="row">
				<div class="col-sm-6 border">
					<br>
					<button type="button" class="btn btn-primary" onclick="location.href='<?php echo $archivo; ?>?FMfcgxwLtkdRLdXFNVNgdJkHCHKrmRpl=DetalleRuta&amp;ruta=<?php echo $registro['Ruta']; ?>&amp;fecha=<?php echo $registro['Fecha']; ?>&amp;viaje=<?php echo $registro['Viaje']; ?>';"><i class="fa fa-arrow-left" aria-hidden="true"></i> Volver</button>
					<br><br>
					<form class="needs-validation" novalidate method="POST">
						<div class="form-group">
							<table class="table table-sm">
								<tr>
									<td><strong>Cliente:</strong></td>
									<td><?php echo $registro['Codigo'] . ' - ' . $registro['Cliente']; ?></td>
								</tr>
								<tr>
									<td><strong>Supervisor:</strong></td>
									<td><?php echo $registro['nombreSup']; ?></td>
								</tr>
								<tr>
									<td><strong>BDR:</strong></td>
									<td><?php echo $registro['ZNPVTA'] . ' - ' . $registro['nombreAc']; ?></td>
								</tr>
								<tr>
									<td><strong>Tipo Pedido:</strong></td>
									<td><?php echo $registro['TipoPedido']; ?></td>
								</tr>
								<tr>
									<td><strong>Tipo Cliente:</strong></td>
									<td><?php echo $registro['TipoCliente']; ?></td>
								</tr>
								<tr>
									<td><strong>Ventana Horaria:</strong></td>
									<td><?php echo $registro['Abre'] . ' - ' . $registro['Cierra']; ?></td>
								</tr>
								<tr>
									<td><strong>Telf. Cliente:</strong></td>
									<td><a href="tel:+<?php echo $registro['TelefonoCli']; ?>"><?php echo $registro['TelefonoCli']; ?></a>
									</td>
								</tr>
								<tr>
									<td><strong>Tipo Riesgo:</strong></td>
									<td><?php echo $registro['Tipo_Riesgo']; ?></td>
								</tr>
								<tr>
									<td><strong>Tipo NPS:</strong></td>
									<td><?php echo $registro['Tipo_NPS']; ?></td>
								</tr>
								<tr>
									<td><strong>FR:</strong></td>
									<td><?php echo $registro['TIPO']; ?></td>
								</tr>
								<tr>
									<td><strong>Rechazos:</strong></td>
									<td><?php echo $registro['Rechazos']; ?></td>
								</tr>
								<tr>
									<td><strong>RMD:</strong></td>
									<td><?php echo $rmd; ?></td>
								</tr>
								<tr>
									<td><strong>Servicio Flex:</strong></td>
									<td><?php echo $registro['Servicio_Flex']; ?></td>
								</tr>
								<tr>
									<td><strong>Reparto:</strong></td>
									<td><?php echo $registro['Ruta']; ?><br></td>
								</tr>
								<tr>
									<td><strong>Cjs.Ent.Prog:</strong></td>
									<td><?php echo $registro['Entrega']; ?></td>
								</tr>
								<tr>
									<td><strong>Hl.Prog:</strong></td>
									<td><?php echo $registro['HL']; ?></td>
								</tr>
								<tr>
									<td><strong>Mot. Rechazo:</strong></td>
									<td>
										<select class="form-control form-control-sm" id="validationCustom02" required name="GrabAlerta[mr]" <?php echo $disabled; ?>>
											<option value="">Seleccionar</option>
											<?php
											$db = Db::conectar();
											$select = $db->prepare("SELECT id, descripcion FROM t77_mr WHERE st=1");
											$select->execute();
											while ($regis = $select->fetch()) {
												if ($regis['descripcion'] == $registro['mr']) {
													echo '<option  value="' . $regis['descripcion'] . '" selected >' . $regis['descripcion'] . '</option>';
												} else {
													echo '<option  value="' . $regis['descripcion'] . '" >' . $regis['descripcion'] . '</option>';
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
										<textarea name="GrabAlerta[comentarios]" class="form-control form-control-sm" id="validationCustom03" required <?php echo $disabled; ?>><?php echo $registro['comentario']; ?></textarea>
										<div class="invalid-feedback">
											Ingrese un comentario valido
										</div>
									</td>
								</tr>
								<tr>
									<td colspan="2">
										<?php
										if ($registro['alerta'] == 0 and $registro['entregado'] == 0) {
										?>
											<button type="submit" class="btn btn-success" name="GrabAlerta[grabaralt]"><i class="fa fa-whatsapp"></i> Registrar</button>
											<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#entregar_ped" <?php if ($registro['rechazo'] == 1 or $registro['entregado'] == 1) {
																																				echo "disabled";
																																			} ?>>Entregar</button>
											<button type="button" class="btn btn-primary" onclick="location.href='<?php echo $archivo; ?>?FMfcgxwLtkdRLdXFNVNgdJkHCHKrmRpl=DetalleRuta&amp;ruta=<?php echo $registro['Ruta']; ?>&amp;fecha=<?php echo $registro['Fecha']; ?>&amp;viaje=<?php echo $registro['Viaje']; ?>';"><i class="fa fa-arrow-left" aria-hidden="true"></i> Volver</button>
										<?php } else {
										?>
											<div class='redes-flotantes'>
												<a href="https://api.whatsapp.com/send?text=<?php echo $msjwsp; ?>" title="Compartir alerta" style="clear: left; float: left; margin-bottom: 1em; margin-right: 1em;" target="_blank">
													<img border="0" data-original-height="59" data-original-width="59" src="https://1.bp.blogspot.com/-q3Dot9N2qac/XOQgr9etVpI/AAAAAAABT1M/6V4Bqaqr-6UQcl9Fy2_CaVgex0N_OYuQgCLcBGAs/s1600/whatsapp%2Bicono.png" />
												</a>
											</div>
											<button type="button" class="btn btn-success" onclick="location.href='https://api.whatsapp.com/send?text=<?php echo $msjwsp; ?>';"><i class="fa fa-whatsapp"></i> WhatsApp</button>
											<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#rechazar_ped" <?php echo $disabled; ?>>Rechazar</button>
											<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#entregar_ped" <?php echo $disabled; ?>>Entregar</button>
											<button type="button" class="btn btn-primary" onclick="location.href='<?php echo $archivo; ?>?FMfcgxwLtkdRLdXFNVNgdJkHCHKrmRpl=DetalleRuta&amp;ruta=<?php echo $registro['Ruta']; ?>&amp;fecha=<?php echo $registro['Fecha']; ?>&amp;viaje=<?php echo $registro['Viaje']; ?>';"><i class="fa fa-arrow-left" aria-hidden="true"></i> Volver</button>
										<?php
										}
										?>
									</td>
								</tr>
							</table>
							<input type="hidden" name="GrabAlerta[idrechazo]" value="<?php echo $id; ?>">
							<input type="hidden" name="GrabAlerta[FMfcgxwLtkdRLdXFNVNgdJkHCHKrmRpl]" value="GrabarAlerta">
						</div>
					</form>
				</div>
			</div>
		<?php
				modal_rechazar($indx, $id, $mr, $comentario, $autoriza_rech, $cajaswsp);
				modal_entregar($indx, $id, $actClasifEnv, $cajaswsp, $ruta, $registro['Fecha'], $registro['Viaje']);
			}
			Db::desconectar();
		}
		function depositos_bancarios_clientes()
		{
			global $idcentro, $fechars, $fecha_hora, $aid, $archivo;
			if (isset($_POST['depst']) && is_array($_POST['depst'])) {
				$depst = $_POST['depst'];
				$indice		=   $depst['indice'];
				$ruta		= 	$depst['ruta'];
				$fecha		= 	$depst['fecha'];
				$codigo		= 	$depst['codigo'];
				$nombre		= 	$depst['nombre'];
				$banco		= 	$depst['banco'];
				$importe	= 	$depst['importe'];
				$transporte	= 	$depst['transporte'];
				$db = Db::conectar();
				$insert = $db->prepare("
		INSERT INTO  `t77_rs_depositos_user`(`id`, `transporte`, `centro`,`indice`, `fecha_registro`, `ruta`, `fecha`, `codigocliente`, `nombre` ,`banco`, `importe`) 
		VALUES (null , '$transporte' , '$idcentro' ,'$indice', '$fecha_hora', '$ruta', '$fecha', $codigo, '$nombre', '$banco', $importe)
		");
				$insert->execute();
				Db::desconectar();
				$lastInsertId = $db->lastInsertId();
				if ($lastInsertId > 0) {
					echo '<div class="alert alert-success" role="alert">Se registro</div>';
				} else {
					echo '<div class="alert alert-success" role="alert">No se pudo registrar</div>';
				}
			}
		?>
		<div class="row">
			<div class="col-sm-12 p-0 bg-light">
				<div class="d-flex">
					<div class="p-2 bg-light">
						<div class="text-muted text-md-left font-weight-bolder">Depositos : <?php echo $idcentro; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12 p-0">
				<div class="table-responsive">
					<table id="depositosclientes" data-order='[[ 4, "asc" ]]' data-page-length='25' class="display compact cell-border">
						<thead>
							<tr>
								<th></th>
								<th>Cliente</th>
								<th>Fecha</th>
								<th>Tranporte</th>
								<th>Ruta</th>
								<th>Banco</th>
								<th>Documento</th>
								<th>Importe</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<?php
							$db = Db::conectar();
							$sql = "SELECT d.indice, d.fecha_registro, d.fecha , d.codigocliente, d.banco, d.importe , d.documento ,CONCAT(r.Codigo,' ',r.Cliente) AS Cliente, r.Cliente as Nombre , r.Ruta, r.Transporte FROM 
		  (
		  SELECT Transporte, Codigo, Cliente, Ruta, centro FROM `t77_rs` WHERE Fecha='$fechars'  AND centro='$idcentro'  GROUP BY Transporte, Codigo, Cliente, Ruta, centro
		  ) AS r  JOIN 
		  (
			SELECT 	a.id, a.indice, a.fecha, a.codigocliente, a.banco, a.importe, a.documento, b.fecha_registro
			FROM (
			SELECT * FROM `t77_rs_depositos` WHERE fecha='$fechars' 
				) AS a LEFT JOIN 
				(
			SELECT * FROM `t77_rs_depositos_user` WHERE fecha='$fechars' 
				) AS b 
				ON a.indice = b.indice
		  ) as d 
		  ON r.Codigo = d.codigocliente";
							$select = $db->prepare($sql);
							$select->execute();
							while ($registro = $select->fetch()) {
							?>
								<tr>
									<td></td>
									<td><?php echo $registro['Cliente']; ?></td>
									<td><?php echo $registro['fecha']; ?></td>
									<td><?php echo $registro['Transporte']; ?></td>
									<td><?php echo $registro['Ruta']; ?></td>
									<td><?php echo $registro['banco']; ?></td>
									<td><?php echo $registro['documento']; ?></td>
									<td><?php echo $registro['importe']; ?></td>
									<td>
										<form onsubmit="return confirm('Esta seguro de usar el deposito <?php echo $registro['codigocliente'] . ' -> ' . $registro['importe']; ?>');" name="conductor" method='POST'>
											<input type="hidden" name="FMfcgxwLtkdRLdXFNVNgdJkHCHKrmRpl" value="DepositosBancarios">
											<input type="hidden" name="depst[indice]" value="<?php echo $registro['indice']; ?>">
											<input type="hidden" name="depst[ruta]" value="<?php echo $registro['Ruta']; ?>">
											<input type="hidden" name="depst[fecha]" value="<?php echo $registro['fecha']; ?>">
											<input type="hidden" name="depst[codigo]" value="<?php echo $registro['codigocliente']; ?>">
											<input type="hidden" name="depst[nombre]" value="<?php echo $registro['Nombre']; ?>">
											<input type="hidden" name="depst[banco]" value="<?php echo $registro['banco']; ?>">
											<input type="hidden" name="depst[importe]" value="<?php echo $registro['importe']; ?>">
											<input type="hidden" name="depst[transporte]" value="<?php echo $registro['Transporte']; ?>">
											<button type="submit" <?php echo is_null($registro['fecha_registro']) ? '' : 'disabled'; ?> class="btn btn-danger btn-sm">Usar</button>
										</form>
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
		switch ($FMfcgxwLtkdRLdXFNVNgdJkHCHKrmRpl):
			case "RegistraHora":
				break;
			case "DetalleRuta":
				if (isset($_GET['fecha'], $_GET['ruta'], $_GET['viaje'])) {
					if (isset($_GET['EntregarTodos']) && $_GET['EntregarTodos'] == 'cHNKXCgPVRsxZwjjgxGwXz') {
						$crud->EntregarPedidoTodos($aid, $idcentro, $_GET['ruta'], $_GET['fecha'], $_GET['viaje']);
					}
					if (isset($_POST['encuestarepaso'])) {  //graba encuesta repaso 
						if (isset($_POST['repaso'])) {
							foreach ($_POST['repaso'] as $clave => $valor) {
								$crud->insertar_repaso_t2($clave, $idcentro, 1);
							}
						}
						if (isset($_POST['ck_enviar'])) {
							foreach ($_POST['ck_enviar'] as $clave => $valor) {
								$crud->EntregarPedido($clave, $aid, $idcentro, 0, 0, 0);
							}
						}
						if (isset($_POST['encuesta'])) {
							foreach ($_POST['encuesta'] as $clave => $valor) {
								if ($valor != '') {
									$crud->insertar_encuesta_t2($clave, $idcentro, $valor);
								}
							}
						}
					}
					rutas__detalle($_GET['fecha'], $_GET['ruta'], $_GET['viaje']);
					modal_entregar_todos(1, $_GET['ruta'], $_GET['fecha'], $_GET['viaje']);
				} else {
					?><div class="alert alert-danger" role="alert">Algo esta mal...</div><?php
				}
				break;
			case "AlertarWS":
				if (isset($_POST['GrabAlerta']) && is_array($_POST['GrabAlerta'])) {
					$GrabAlerta 	= $_POST['GrabAlerta'];
					$mr 			= $GrabAlerta['mr'];
					$comentarios 	= $GrabAlerta['comentarios'];
					$grabaralt 		= $GrabAlerta['grabaralt'];
					$IdEntrega 		= $GrabAlerta['idrechazo'];
					$GrabarAlerta 	= $GrabAlerta['FMfcgxwLtkdRLdXFNVNgdJkHCHKrmRpl'];
					$crud->GrabarAlerta($mr, 0, $comentarios, $aid, $IdEntrega);
					echo '<div class="alert alert-success" role="alert">Alerta registrada, ahora se compartira por WS</div>';
				} else {
					$IdEntrega = $_GET["id"];
				}
				if (isset($_POST['EntregarModal']) && is_array($_POST['EntregarModal'])) {
					$EntregarModal 		= $_POST['EntregarModal'];
					$IdEntrega 			= $EntregarModal['IdEntrega'];
					$CjasEntregadas 	= $EntregarModal['CjasEntregadas'];
					$ClasificaEnvases 	= $EntregarModal['ClasificaEnvases'];
					$CjasClasificadas 	= $EntregarModal['CjasClasificadas'];
					$AlertarWS 			= $EntregarModal['FMfcgxwLtkdRLdXFNVNgdJkHCHKrmRpl'];
					$crud->EntregarPedido($IdEntrega, $aid, $idcentro, 0, $ClasificaEnvases, $CjasClasificadas);
					echo '<div class="alert alert-success" role="alert">Pedido entregado</div>';
				} else {
					$IdEntrega = $_GET["id"];
				}
				if (isset($_POST['modalRechazo']) && is_array($_POST['modalRechazo'])) {
					$modalRechazo 			= $_POST['modalRechazo'];
					$IdEntrega 		= $modalRechazo['idrechazo'];
					$confirmarrchz 		= $modalRechazo['confirmarrchz'];
					$grabaralerta 		= $modalRechazo['grabaralerta'];
					$GrabarRechazo 		= $modalRechazo['FMfcgxwLtkdRLdXFNVNgdJkHCHKrmRpl'];
					$ClasificaEnvRec	= $modalRechazo['ClasificaEnvRec'];
					$CjasClasifRech 	= $modalRechazo['CjasClasifRech'];
					$mr 				= $modalRechazo['mr'];
					$autoriza_rech 		= $modalRechazo['autoriza_rech'];
					$CjsEntrega 		= $modalRechazo['CjsEntrega'];
					$cjasrechazadas 	= $modalRechazo['cjasrechazadas'];
					$comentarios 		= $modalRechazo['comentarios'];
					if ($cjasrechazadas > $CjsEntrega) {
						echo '<div class="alert alert-warning" role="alert">El rechazo es mayor a lo programado, favor de corregir</div>';
					} else {
						$crud->GrabarRechazo($aid, $IdEntrega, $autoriza_rech, $cjasrechazadas, $ClasificaEnvRec, $CjasClasifRech, $idcentro, $mr, $comentarios);
						echo '<div class="alert alert-success" role="alert">Rechazo registrado</div>';
					}
				} else {
					$IdEntrega = $_GET["id"];
				}
				rutas__ws($IdEntrega);
				break;
			case "updateETA":
				if (isset($_GET['fecha'], $_GET['eta'], $_GET['Llega'])) {
					$Llega = $_GET['Llega'] . ':00';
					$crud->modificar_eta($Llega, $_GET['eta'], $idcentro);
					header('Location: ' . $archivo . '?FMfcgxwLtkdRLdXFNVNgdJkHCHKrmRpl=DetalleRuta&ruta=' . $_GET['ruta'] . '&fecha=' . $_GET['fecha'] . '&viaje=' . $_GET['viaje'] . '');
				} else {
					header('Location: ' . $archivo . '?FMfcgxwLtkdRLdXFNVNgdJkHCHKrmRpl=DetalleRuta&ruta=' . $_GET['ruta'] . '&fecha=' . $_GET['fecha'] . '&viaje=' . $_GET['viaje'] . '');
				}
				break;
			case "DepositosBancarios":
				depositos_bancarios_clientes();
				break;
			default:
				if (isset($_GET['fecha'], $_GET['ruta'], $_GET['viaje'], $_GET['vehiculo']) && (($crud->contardbuser('id', 't77_rs_ruta_sif', 'fecha_plan = "' . $_GET['fecha'] . '" AND viaje = "' . $_GET['viaje'] . '" AND vehiculo = "' . $_GET['vehiculo'] . '" AND centro="' . $idcentro . '"')) == 0)) {
					$ruta = $_GET['ruta'];
					$viaje = $_GET['viaje'];
					$vehiculo = $_GET['vehiculo'];
					$fecha = $_GET['fecha'];
					$indx = $vehiculo . $ruta . $viaje . $fecha;
					if (strtoupper($aid) == strtoupper($ruta)) {
						$crud->InsertarHoraInicioVehiculoT2($indx, $idcentro, $vehiculo, $ruta, $viaje, $fecha, $fecha_hora);
					?><div class="alert alert-success" role="alert">Se registro...</div><?php
					} else {
					?><div class="alert alert-danger" role="alert">No eres el usuario <?php echo $ruta; ?>...</div><?php
											}
										} else {
											if (isset($_GET['fecha'], $_GET['id'], $_GET['tb'])) {
												if ($aid == $_GET['ruta']) {
													$crud->ModificarHoraInicioVehiculoT2($_GET['tb'], $fecha_hora, $idcentro, $_GET['id']);
												?><div class="alert alert-success" role="alert">Se registro...</div><?php
												} else {
					?><div class="alert alert-danger" role="alert">No eres el usuario <?php echo $_GET['ruta']; ?>...</div><?php
													}
												} else {
												}
											}
											rutas__();
									endswitch;
								} else {
									echo $html_acceso;
								}
							}
							require('../footer.php');
							ob_end_flush();
														?>