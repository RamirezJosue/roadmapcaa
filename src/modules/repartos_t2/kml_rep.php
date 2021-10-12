<?php 
    ob_start();	
    $accesos = basename(dirname(__FILE__));
	require_once('../../includes/ini.php');
	require_once('../../bd/crud_usuario.php');
	$crud=new CrudUsuario();
    if ($usuarioestado==0){
	echo $html_bloqueo;
	} else {
	$bootstrapjs =  1;	
	$datatablesjs = 1;
	if(isset($_GET['fechaselec'])): 
	$fecha_form = $_GET['fechaselec'];
	$fechars = $_GET['fechaselec'];	
	else:
	$fechars = $fechars; 
	$fecha_form = $fecha;
	endif;
	$pasa = 4564;	
if($pasa==4564 && isset($_GET['id'],$_GET['fecha'])){
$ruta = $_GET['id'];
$Viaje = $_GET['vj'];
$xml = new XMLWriter();
$xml->openMemory();
$xml->setIndent(true);
$xml->setIndentString('	'); 
$xml->startDocument('1.0', 'UTF-8');
$xml->startElement("kml"); //elemento kml
   $xml->writeAttribute('xmlns', 'http://earth.google.com/kml/2.2');
   $xml->startElement("Document"); //elemento document
      $xml->startElement("Style"); //elemento responsable
            $xml->writeAttribute('id', "placemark-blue");
            $xml->startElement("IconStyle"); //elemento alumno
			     $xml->startElement("Icon"); //elemento alumno
					$xml->writeElement("href", "http://mapswith.me/placemarks/placemark-blue.png");
			$xml->endElement(); //fin alumno	
            $xml->endElement(); //fin alumno					
      $xml->endElement(); //fin clase
       $xml->startElement("Style"); //elemento responsable
            $xml->writeAttribute('id', "placemark-red");
            $xml->startElement("IconStyle"); //elemento alumno
			     $xml->startElement("Icon"); //elemento alumno
					$xml->writeElement("href", "http://mapswith.me/placemarks/placemark-red.png");
			$xml->endElement(); //fin alumno	
            $xml->endElement(); //fin alumno					
      $xml->endElement(); //fin clase
	  $name = $ruta.'_'.$Viaje.'_'.$fecha;
	  $xml->writeElement("name", "$name");
	  $xml->writeElement("visibility", "1");
		$db=Db::conectar();
		$sql ="SELECT Abre,Cierra,Sec1,Viaje,Cliente,Codigo,Direccion,Ciudad,Entrega,Longitud,Latitud,Telef1,Telef2 FROM (SELECT * FROM `t77_rs` WHERE Fecha=:Fecha AND centro=:centro AND Ruta=:Ruta AND Viaje=:Viaje) AS r LEFT JOIN (SELECT Telef1, Telef2, codcli FROM `t77_mc` WHERE centro=:centro) as m ON r.Codigo = m.codcli ORDER BY Sec1 DESC";
        $select=$db->prepare($sql);
		$select->bindValue('Ruta',$ruta);
		$select->bindValue('Fecha',$_GET['fecha']);		
		$select->bindValue('centro',$idcentro);
		$select->bindValue('Viaje',$Viaje);
		$select->execute();
		$n=1;
		while ($rows=$select->fetch()) {
$abre = substr($rows['Abre'],0,5);
$cierra = substr($rows['Cierra'],0,5);
if(($abre.$cierra)=='00:0023:59'){ $stylecolor='#placemark-blue'; } else { $stylecolor='#placemark-red'; }
$namers = $rows['Sec1'].'|'.htmlspecialchars($rows['Cliente'],ENT_QUOTES,'UTF-8').'|'.htmlspecialchars($rows['Direccion'],ENT_QUOTES,'UTF-8').'|Cjs:'.round($rows['Entrega']).'|VH:'.$abre.'-'.$cierra.'|Tel:'.$rows['Telef1'].'-'.$rows['Telef2'];
$coordi = str_replace(",",".",$rows['Longitud']).','.str_replace(",",".",$rows['Latitud']);
       $xml->startElement("Placemark"); //elemento responsable
	        $xml->writeElement("name", "$namers");
	        $xml->writeElement("styleUrl", "$stylecolor");
			$xml->startElement("Point"); //elemento alumn
					$xml->writeElement("coordinates", "$coordi");
            $xml->endElement(); //fin alumno					
       $xml->endElement(); //fin clase
		}
		Db::desconectar();
   $xml->endElement(); //fin document
$xml->endElement(); //fin kml
$content = $xml->outputMemory();
ob_end_clean();
ob_start();
header('Content-Type: application/xml; charset=UTF-8');
header('Content-Encoding: UTF-8');
header('Content-Disposition: attachment;filename='.$ruta.'_'.$Viaje.'_'.$fecha.'.kml');
header('Expires: 0');
header('Pragma: cache');
header('Cache-Control: private');
echo $content;
}
	}
?>