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
	$fechaselec = $fechars; 
	$fecha = $fechars;
	endif;
	$fecha_ini = date_format(date_create($fechaselec),"Y-m-01");
	$fecha_fin = date_format(date_create($fechaselec),"Y-m-30");
    
     $sic_sortin_avance ="
                    SELECT 
                z.`centro`,
                z.`Fecha`,
                z.sic_hr,
                SUM(z.`CJ`) as cajas,
                (SELECT SUM(e.`CJ`)
                FROM (
                SELECT
                CASE
                    WHEN HOUR(`Final`) <= '07' THEN '07:00'
                    WHEN HOUR(`Final`) <= '09' THEN '09:00'
                    WHEN HOUR(`Final`) <= '11' THEN '11:00'
                    WHEN HOUR(`Final`) <= '13' THEN '13:00'
                    WHEN HOUR(`Final`) <= '15' THEN '15:00'
                    ELSE '16:00'
                END AS sic_hr,
                `CJ`
                FROM kpi_sorting AS x WHERE x.Fecha='2021-08-28' AND x.centro='BK77' 
                ) AS e 
                WHERE e.sic_hr <= z.sic_hr
                ) as AcuCjas
                FROM 
                (
                SELECT 
                `centro`,
                `Fecha`,
                CASE
                    WHEN HOUR(`Final`) <= '07' THEN '07:00'
                    WHEN HOUR(`Final`) <= '09' THEN '09:00'
                    WHEN HOUR(`Final`) <= '11' THEN '11:00'
                    WHEN HOUR(`Final`) <= '13' THEN '13:00'
                    WHEN HOUR(`Final`) <= '15' THEN '15:00'
                    ELSE '16:00'
                END AS sic_hr,
                `CJ`, 
                `Final`
                FROM `kpi_sorting` WHERE Fecha='2021-08-28' AND `centro`='BK77'
                ) AS z GROUP BY 
                z.`centro`,
                z.`Fecha`,
                z.sic_hr
                ";
                $db=Db::conectar();
                $select=$db->prepare($sic_sortin_avance);
                //$select->bindValue('centro',$idcentro);
                //$select->bindValue('Fecha',$fechaselec);
                $select->execute();
                while ($arraysic=$select->fetch()) {
                    $HoraEntS[] = $arraysic['sic_hr'];
                    $CjasRechazoAcuS[] = round($arraysic['AcuCjas']);	
                }
                Db::desconectar(); 
?>
	<canvas id="SICRechazos" style="max-width:600px"> </canvas>
		<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
		<script>window.jQuery || document.write('<script src="../assets/js/vendor/jquery.slim.min.js"><\/script>')</script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.9.0/feather.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script> 
         
	<script>
                new Chart(document.getElementById("SICRechazos"), {
                    type: 'bar',
                    data: {
                    labels: ['07:00','09:00','12:00','13:00','15:00'], 	
                    datasets: [{
                        label: "Horas",
                        type: "line",
                        label: 'Cajas Rechazo', 
                        data: [2000,4000,5000,6000,12000],
                        fill: false,
                        backgroundColor: "#0000FF",
                        borderColor: "#0000FF",
                        borderCapStyle: 'butt',
                        borderDash: [],
                        borderDashOffset: 0.0,
                        borderJoinStyle: 'miter',
                        lineTension: 0.3,
                        pointBackgroundColor: "#0000FF",
                        pointBorderColor: "#0000FF",
                        pointBorderWidth: 1,
                        pointHoverRadius: 5,
                        pointHoverBackgroundColor: "#0000FF",
                        pointHoverBorderColor: "#0000FF",
                        pointHoverBorderWidth: 2,
                        pointRadius: 4,
                        pointHitRadius: 10
                        },		
                        {
                        label: "Rojo",
                        type: "bar",
                        backgroundColor: 'rgb(255, 0, 0)',
                        data: [2500,5000,7500,10000,12500],
                        },
                        {
                        label: "Amarillo",
                        type: "bar",
                        backgroundColor: 'rgb(255, 255, 0)',
                        data: [0,0,0,0,0],
                        },
                        {
                        label: "Verde",
                        type: "bar",
                        backgroundColor: 'rgb(0, 128, 0)',
                        data: [17500,15000,12500,10000,7500],
                        }
                    ]
                    },
                    options: {
                        scales: {
                            xAxes: [{stacked: true, barPercentage: 2 }],
                            yAxes: [{stacked: true}],
                        },
                    responsive: true,	   
                    title: {
                        display: true,
                        text: 'SIC Cajas Rechazo Producto'
                    },
                    legend: {
                            display: true,
                            labels: {
                                    boxWidth: 20,
                                    padding: 10
                                    }
                                    },
                    tooltips: {
                                mode: 'label',
                                intersect: false
                            },				
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
