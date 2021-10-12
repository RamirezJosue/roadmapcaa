<?php 
    ob_start();	
    $accesos = basename(dirname(__FILE__));
	require_once('../../includes/ini.php');
	require_once('../../bd/crud_usuario.php');
	require_once('../../bd/crud_nps.php');
	require_once('../../bd/array/bd_nps_mpilcosa1.php');
	$CrudNPS=new CrudNPS();
	$crud=new CrudUsuario();
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
	$datatablesjs = 0;
	require('../head.php');
	if(isset($_GET['fechaselec'])): 
	$fecha_form = $_GET['fechaselec'];
	$fechars = $_GET['fechaselec'];	
	else:
	$fechars = $fechars; 
	$fecha_form = $fecha;
	endif;
	if (isset($_GET['j5xqi9554vUXBmoX9IHXg'])){ $j5xqi9554vUXBmoX9IHXg = $_GET['j5xqi9554vUXBmoX9IHXg']; } else { $j5xqi9554vUXBmoX9IHXg = ""; }
	$aleatorio = uniqid();
	function unoaldiez($value){	
		echo '<div class="row">
				<div class="col-6 text-left text-muted small">Poco Probable</div>
				<div class="col-6 text-right text-muted small">Muy Probable</div>
			  </div>';
	echo '<div class="row">
				<div class="col-12 mx-auto">';
	echo '<nav aria-label="Page navigation example">
			<ul class="pagination justify-content-center">';
				if($value==0 && is_numeric($value)):
				echo '<li class="page-item active" aria-current="page"><span class="page-link">0</span></li>'; else:
				?> <li class="page-item"><a class="page-link" onclick="this.href='nps?score=0&codcli='+document.getElementById('codcli').value" >0</a></li> <?php ; endif;
				if($value==1 && is_numeric($value)):
				echo '<li class="page-item active" aria-current="page"><span class="page-link">1</span></li>'; else:
				?> <li class="page-item"><a class="page-link" onclick="this.href='nps?score=1&codcli='+document.getElementById('codcli').value" >1</a></li> <?php ; endif;			
				if($value==2 && is_numeric($value)):
				echo '<li class="page-item active" aria-current="page"><span class="page-link">2</span></li>'; else:
				?> <li class="page-item"><a class="page-link" onclick="this.href='nps?score=2&codcli='+document.getElementById('codcli').value" >2</a></li> <?php ; endif;
				if($value==3 && is_numeric($value)):
				echo '<li class="page-item active" aria-current="page"><span class="page-link">3</span></li>'; else:
				?> <li class="page-item"><a class="page-link" onclick="this.href='nps?score=3&codcli='+document.getElementById('codcli').value" >3</a></li> <?php ; endif;
				if($value==4 && is_numeric($value)):
				echo '<li class="page-item active" aria-current="page"><span class="page-link">4</span></li>'; else:
				?> <li class="page-item"><a class="page-link" onclick="this.href='nps?score=4&codcli='+document.getElementById('codcli').value" >4</a></li> <?php ; endif;
				if($value==5 && is_numeric($value)):
				echo '<li class="page-item active" aria-current="page"><span class="page-link">5</span></li>'; else:
				?> <li class="page-item"><a class="page-link" onclick="this.href='nps?score=5&codcli='+document.getElementById('codcli').value" >5</a></li> <?php ; endif;
				if($value==6 && is_numeric($value)):
				echo '<li class="page-item active" aria-current="page"><span class="page-link">6</span></li>'; else:
				?> <li class="page-item"><a class="page-link" onclick="this.href='nps?score=6&codcli='+document.getElementById('codcli').value" >6</a></li> <?php ; endif;
				if($value==7 && is_numeric($value)):
				echo '<li class="page-item active" aria-current="page"><span class="page-link">7</span></li>'; else:
				?> <li class="page-item"><a class="page-link" onclick="this.href='nps?score=7&codcli='+document.getElementById('codcli').value" >7</a></li> <?php ; endif;
				if($value==8 && is_numeric($value)):
				echo '<li class="page-item active" aria-current="page"><span class="page-link">8</span></li>'; else:
				?> <li class="page-item"><a class="page-link" onclick="this.href='nps?score=8&codcli='+document.getElementById('codcli').value" >8</a></li> <?php ; endif;
				if($value==9 && is_numeric($value)):
				echo '<li class="page-item active" aria-current="page"><span class="page-link">9</span></li>'; else:
				?> <li class="page-item"><a class="page-link" onclick="this.href='nps?score=9&codcli='+document.getElementById('codcli').value" >9</a></li> <?php ; endif;
				if($value==10 && is_numeric($value)):
				echo '<li class="page-item active" aria-current="page"><span class="page-link">10</span></li>'; else:
				?> <li class="page-item"><a class="page-link" onclick="this.href='nps?score=10&codcli='+document.getElementById('codcli').value" >10</a></li> <?php ; endif;				
	echo 	'</ul>
		</nav>';
	echo '</div>
			</div>';			  
	}
	function textareaform($value,$name){
		
	echo  '<textarea class="form-control" name="'.$name.'" placeholder="Ingrese comentario" required></textarea>';
		
	}
	function codigoclienteform($value,$name){
	echo	'<div class="form-group">';
	//echo		'<label for="codigocliente">Código cliente</label>';
	echo 		'<input type="text" name="'.$name.'" id="codcli" value="'.$value.'" class="form-control" placeholder="Código cliente" required>';
	echo 	'</div>';
	}
	function hiddenform($value,$name){
	echo	'<div class="form-group">';
	//echo		'<label for="codigocliente">Código cliente</label>';
	echo 		'<input type="hidden" name="'.$name.'" class="form-control" value="'.$value.'">';
	echo 	'</div>';
	}	
	
	/*
	Tipo Pregunta
	 1 = CodigoCliente
	 2 = UnoAlDiez
	 3 = Textarea
	 default array 
	 */

	switch ($j5xqi9554vUXBmoX9IHXg):
    case "resultado":
        break;
    case "vistas":
	break;	 	
    default:
	if(isset ($_POST['ckx'],$_POST['namearreglo'])){
	foreach ($_POST['ckx'] as $respuestas => $preguntas){
		foreach ($preguntas as $pregunta => $subpregunta){
	$CrudNPS-> insertarNPS('Net Promoter Score',$pregunta,$subpregunta,$respuestas,$aid,12345678,$fecha_hora,$idcentro,$aleatorio);	
		}
	}
	foreach ($_POST['namearreglo'] as $pregunta => $respuestas){
	$CrudNPS-> insertarNPS('Net Promoter Score',$pregunta,'',$respuestas,$aid,12345678,$fecha_hora,$idcentro,$aleatorio);
	} 
	echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
			<strong>Se registro correctamente !</strong>
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
			</button>
		  </div>';
	}
	
	$score = isset($_GET['score'])? $_GET['score'] : null ;
	$codcli = isset($_GET['codcli'])? $_GET['codcli'] : null ;
	if($score >= 9 and  $score <= 10) { 
	$rptapgta = '9-10';
	} else if ($score >= 0 and  $score <= 8){
	$rptapgta = '0-8';	
	}	
	echo '<img src="../../img/nps NUEVO LOGO2.svg" class="img-fluid" alt="nps" width="100%" height="auto"><br><br>  ';
    echo '<form method="post">';
	foreach ($nps as $pregunta => $tipopregunta){
                		
			    switch ($tipopregunta):
				case 1: // 
					echo '<h5>'.$pregunta.'</h5>'; 
					codigoclienteform($codcli,'namearreglo['.$pregunta.']');
					break;
				case 2: 
				    echo '<div class="text-muted">Pensando en la relación a largo plazo con la backus por favor califique la siguiente pregunta :</div>';
					echo '<h5>'.$pregunta.'</h5>'; 
					unoaldiez($score);
					hiddenform($score,'namearreglo['.$pregunta.']');
					break;
					
				case 3:
				if (IS_NULL($score)){}else{
					echo '<h5>'.$pregunta.'</h5>'; 
					textareaform('','namearreglo['.$pregunta.']');
				}		
					break;	
                default:
				if (IS_NULL($score)){}else{
					echo '<h5>'.$pregunta.'</h5>'; 
					$n=1;
					foreach($tipopregunta as $subpregunta => $tiposubpregunta){
					    //$nuevasubpregunta = preg_replace('([^A-Za-z0-9])', '', $subpregunta);	
						echo '<h6><a class="btn btn-light text-left" data-toggle="collapse" href="#acord'.$n.'" role="button" aria-expanded="false" aria-controls="collapseExample">';
						echo  $subpregunta;
						echo '</a></h6>';			
					if(is_array($tiposubpregunta)){
					echo '<div class="collapse" id="acord'.$n.'">';
					echo '<ul>';
					foreach($tiposubpregunta as $respuestas => $idrespuestas){
						if($idrespuestas==$rptapgta){		
						echo '<div class="form-check">';
						echo '<input class="form-check-input" type="checkbox" name="ckx['.$respuestas.']['.$pregunta.']" value="'.$subpregunta.'" id="defaultCheck1">';
						echo '<label class="form-check-label" for="defaultCheck1">'.$respuestas.'</label>';
						echo '</div>';
						}
					}
					echo '</ul>';
						echo '</div>';					
					}
					$n++;	
					}
				}	
				endswitch;		
	}
		 ?> <br>
			<button type="submit" class="btn btn-success" <?php if(is_numeric($score) && $score <= 10){ }else{ echo 'disabled'; } ?>>Enviar</button> 
			<button type="button" class="btn btn-danger" onclick="location.href='nps';">Reiniciar</button>
		 <?php
	 echo '<form>';
	endswitch;	
	} else { echo "no tienes permiso para acceder a esta seccion ".$accesos.'-'.$aid.'<br><a  href="index">Inicio</a>'; }
	}
	ob_end_flush();	
	?>
    </main>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script>window.jQuery || document.write('<script src="../assets/js/vendor/jquery.slim.min.js"><\/script>')</script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.9.0/feather.min.js"></script>
  <!--datatables-->
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.18/datatables.min.css" />
  <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.18/datatables.min.js"></script>
  <script type="text/javascript" src="js/datatable.js"></script>
  <!--datatables-->	
</body>
</html>	