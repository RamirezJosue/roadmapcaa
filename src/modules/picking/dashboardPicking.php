<?php 
    ob_start();	
    $accesos = basename(dirname(__FILE__));
	require_once('../../includes/ini.php');
	require_once('../../bd/crud_almacen.php');
	$crudAlm=new CrudAlmacen();
    if ($usuarioestado==0){
	echo $html_bloqueo;
	}else{
    $arraruser = explode ( ',', $usuarioaccesos);	
	if (in_array($accesos, $arraruser)) {
	if ($usuariotipo==0): $aid_super = 0; else: $aid_super = 1; endif;
	/*inicio vefifia si tiene permisos de adminrepartos */
	if (in_array("adminrepartos", $arraruser)): $adminrepartos = 1; else: $adminrepartos = 0; endif;
    if ($aid_super==1 || $adminrepartos==1): $disableform = ''; else: $disableform = 'disabled'; endif;
	/*fin vefifia si tiene permisos de adminrepartos */
	$bootstrapjs =  1;	
	$mapasjs =  0;
	$datatablesjs = 1;
	$momentjs = 0;
	require('../head.php');
	if(isset($_GET['fechaselec'])): 
	$fecha_form = $_GET['fechaselec'];
	$fechars = $_GET['fechaselec'];	
	else:
	$fechars = $fechars; 
	$fecha_form = $fecha;
	endif;
	/*fin includes head systen ini*/
	if (isset($_GET['j5xqi9554vUXBmoX9IHXg'])){ $j5xqi9554vUXBmoX9IHXg = $_GET['j5xqi9554vUXBmoX9IHXg']; } else { $j5xqi9554vUXBmoX9IHXg = ""; }
	$turno_sorting = array(1=>'Uno',2=>'Dos');	
	function dashboard(){
		global $idcentro, $fechars;
    ?>
	<form method="GET"> <input type="date" value="<?php echo $fechars ?>" name="fechaselec"> <button type="submit" class="btn btn-danger btn-sm">Ver</button> </form>
	<script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>
    <div class="row border">
            <div class="col-sm-12">
                <div>
                    <canvas id="PickingSIC"> </canvas>
                </div>
            </div>
        </div>
        <div class="row border">
            <div class="col-sm-12">
                <div>
                <canvas id="PickingPikinero"></canvas>
                </div>
            </div>
        </div>	
    <script>
            /*
            Encierro todo en una función asíncrona para poder usar async y await cómodamente
            */
            (async () => {
                // Llamar a nuestra API. Puedes usar cualquier librería para la llamada, yo uso fetch, que viene nativamente en JS
                const respuestaRaw = await fetch("https://bk77.co/json/dashboard/pickingSic.php?centro=<?php echo $idcentro; ?>&fecha=<?php echo $fechars; ?>");
                const respuestaRaw2 = await fetch("https://bk77.co/json/dashboard/pickingPickero.php?fecha=<?php echo $fechars; ?>&centro=<?php echo $idcentro; ?>"); 
                // Decodificar como JSON
                const respuesta = await respuestaRaw.json();
                const respuesta2 = await respuestaRaw2.json();
                // Ahora ya tenemos las etiquetas y datos dentro de "respuesta"
                // Obtener una referencia al elemento canvas del DOM
                // const $grafica = document.querySelector("#grafica");
                const sic_hr = respuesta.sic_hr; // <- Aquí estamos pasando el valor traído usando AJAX
                const AcuTrptFin = respuesta.AcuTrptFin; // <- Aquí estamos pasando el valor traído usando AJAX
                const NroTrps = respuesta.NroTrps; // <- Aquí estamos pasando el valor traído usando AJAX
                const Pickinero = respuesta2.Pickinero; // <- Aquí estamos pasando el valor traído usando AJAX
                const Cajas = respuesta2.Cajas; // <- Aquí estamos pasando el valor traído usando AJAX
                const Horas = respuesta2.Horas; // <- Aquí estamos pasando el valor traído usando AJAX
                // Podemos tener varios conjuntos de datos. Comencemos con uno

                new Chart(document.getElementById("PickingSIC"), {
                    type: 'line',
                    data: {
                    labels: sic_hr, 	
                    datasets: [{
                        label: "Horas",
                        type: "line",
                        label: '#Transportes', 
                        data: AcuTrptFin,
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
                        pointRadius: 6,
                        pointHitRadius: 10
                        },		
                        {
                        label: "",
                        type: "line",
                        fill: true,
                        pointRadius: 0,
                        backgroundColor: 'rgb(255, 0, 0)',
                        data: [2,5,9,12,15,18],
                        },
                        {
                        label: "",
                        type: "line",
                        fill: true,
                        pointRadius: 0,
                        backgroundColor: 'rgb(255, 255, 0)',
                        data: [4,8,12,17,21,21],
                        },
                        {
                        label: "",
                        type: "line",
                        fill: true,
                        pointRadius: 0,
                        backgroundColor: 'rgb(0, 128, 0)',
                        data: NroTrps,
                        }
                    ]
                    },
                    options: {
                        scales: {
                            xAxes: [{stacked: false, barPercentage: 2 }],
                            yAxes: [{stacked: false}],
                        },    
                    responsive: true,	   
                    title: {
                        display: true,
                        text: 'SIC Picking'
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

                var barChartData = {
                    labels: Pickinero,
                    datasets: [{
                        label: 'Cajas',
                        backgroundColor: 'rgb(224, 33, 36)',
                        yAxisID: 'y-axis-1',
                        data: Cajas,
                    }, {
                        label: 'Horas',
                        backgroundColor: 'rgb(255, 242, 0)',
                        yAxisID: 'y-axis-2',
                        data: Horas,
                        
                    }]
                };
                    new Chart(document.getElementById("PickingPikinero"), {

                        type: 'bar',
                        data: barChartData,
                        options: {
                            responsive: true,
                            title: {
                                display: true,
                                text: 'Picking Cajas y Horas'
                            },
                            tooltips: {
                                mode: 'index',
                                intersect: true
                            },
                            
                            scales: {
                                yAxes: [{
                                    type: 'linear', // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
                                    display: true,
                                    position: 'left',
                                    id: 'y-axis-1',
                                }, {
                                    type: 'linear', // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
                                    display: true,
                                    position: 'right',
                                    id: 'y-axis-2',
                                    gridLines: {
                                        drawOnChartArea: false
                                    }
                                }],
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

            })();       
	</script>	  
	<?php 
	}
   

	switch ($j5xqi9554vUXBmoX9IHXg):
    case "update":
        break;
    case "reporte":
	break;
    default:
		dashboard();
	endswitch;
	} else {
     echo $html_acceso;		
	}
	}
	require('../footer.php');
	ob_end_flush();	
	?>