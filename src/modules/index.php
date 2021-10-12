<?php
    ob_start();	
    $accesos = basename(dirname(__FILE__));
	require_once('../includes/ini.php');
	require_once('../bd/crud_usuario.php');
	$crud=new CrudUsuario();
    if ($usuarioestado==0){
	echo $html_bloqueo;
	}else{
    $arraruser = explode ( ',', $usuarioaccesos);	
	$sweetalert = 0;
	$bootstrapjs = 1;
	require('headMod.php');
	if(isset($_GET['fechaselec'])): 
	$fecha_form = $_GET['fechaselec'];
	$fechars = $_GET['fechaselec'];	
	else:
	$fechars = $fechars; 
	$fecha_form = $fecha;
	endif;
	/*fin includes head systen ini*/
	if (isset($_GET['op'])){ $op = $_GET['op']; } else { $op = ""; }
switch ($op):
    case "borrarbdrs":
    break;
    case "enviarrsdb":
        break;
	case "modificatema":
         break;
	case "ModificaSeccion":
        break;				
    default:
	$db=Db::conectar();
	$select=$db->prepare("SELECT 
			SUM(v.RutASCont) AS RutASCont,
			SUM(v.visitAS) AS visitAS,
			SUM(v.ejecutados) AS ejecutados,
			SUM(v.alertados) AS alertados,
			SUM(v.rechazados) AS rechazados,
			SUM(v.rutafin) AS rutafin
			FROM (
			SELECT 
			1 AS RutASCont,
			SUM(x.visitAS) AS visitAS, 
			SUM(x.`entregado`) AS ejecutados,
			SUM(x.`alerta`) AS alertados,
			SUM(x.`rechazo`) AS rechazados,   
			IF(SUM(x.visitAS)=(SUM(x.`entregado`)+SUM(x.`rechazo`)),1,0)  AS rutafin,
			x.`Ruta` AS rutAS,
			x.`Viaje` AS viajes
			from ( SELECT 1 AS visitAS, `entregado`, `alerta`, `rechazo`,`Codigo`,`Ruta`, `Viaje` FROM `t77_rs` WHERE `Fecha`=:Fecha AND centro = :centro ) AS  x 
			GROUP BY
			x.`Ruta`,
			x.`Viaje`
			) AS v
	");
	$select->bindValue('centro',$idcentro);
	$select->bindValue('Fecha',$fechars);
	$select->execute();
	while ($rowdashboard=$select->fetch(PDO::FETCH_ASSOC)) {
	 $RutASCont = $rowdashboard['RutASCont'];
	 $visitAS = $rowdashboard['visitAS'];
	 $ejecutados = $rowdashboard['ejecutados'];
	 $alertados = $rowdashboard['alertados'];
	 $rechazados = $rowdashboard['rechazados'];
	 $rutafin = $rowdashboard['rutafin'];		  
	}			
	Db::desconectar();
	?>
	<p class="h4 text-muted font-weight-bold"><?php echo $idcentro .' | '.$fechars; ?></p>
<div class="row">
    <div class="border col-sm-4 text-center">
        <div class="col">
            <p class="p-0 mb-0 font-weight-bold text-danger">Rutas</p>
        </div>
        <div class="w-100"></div>
        <div class="col">
            <div class="row text-center">
                <div class="col">
                    <p class="p-0 mb-0 font-weight-normal">Viajes</p>
                    <p class="h2 text-primary font-weight-bold"><?php echo $RutASCont; ?></p>
                </div>
                <div class="col">
                    <p class="p-0 mb-0 font-weight-normal">Ejecutados</p>
                    <p class="h2 text-success font-weight-bold"><?php echo $rutafin; ?></p>
                </div>
                <div class="col">
                    <p class="p-0 mb-0 font-weight-normal">Pendientes</p>
                    <p class="h2 text-danger font-weight-bold"><?php echo ($RutASCont-$rutafin); ?></p>
                </div>
            </div>
        </div>
    </div>
    <div class="border col-sm-4 text-center">
        <div class="col">
            <p class="p-0 mb-0 font-weight-bold text-danger">Visitas</p>
        </div>
        <div class="w-100"></div>
        <div class="col">
            <div class="row text-center">
                <div class="col">
                    <p class="p-0 mb-0 font-weight-normal">Entregas</p>
                    <p class="h2 text-primary font-weight-bold"><?php echo $visitAS; ?></p>
                </div>
                <div class="col">
                    <p class="p-0 mb-0 font-weight-normal">Exitosas</p>
                    <p class="h2 text-success font-weight-bold"><?php echo $ejecutados; ?></p>
                </div>
                <div class="col">
                    <p class="p-0 mb-0 font-weight-normal">Fallidas</p>
                    <a href="dashboardrechazos">
                        <p class="h2 text-danger font-weight-bold"> <?php echo ($rechazados); ?></p>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="border col-sm-4 text-center">
        <div class="col">
            <p class="p-0 mb-0 font-weight-bold text-danger">Modulados</p>
        </div>
        <div class="w-100"></div>
        <div class="col">
            <div class="row text-center">
                <div class="col">
                    <p class="p-0 mb-0 font-weight-normal">Alertas</p>
                    <a href="dashboardalertas">
                        <p class="h2 text-primary font-weight-bold"> <?php echo $alertados; ?></p>
                    </a>
                </div>
                <div class="col">
                    <p class="p-0 mb-0 font-weight-normal"></p>
                    <p class="h2 text-success font-weight-bold"></p>
                </div>
                <div class="col">
                    <p class="p-0 mb-0 font-weight-normal"></p>
                    <p class="h2 text-danger font-weight-bold"></p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php 
	endswitch;
	}
	require('footer.php');
	ob_end_flush();	
	?>