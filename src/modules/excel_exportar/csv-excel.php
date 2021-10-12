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
	/*fin includes head systen ini*/
	if(isset($_POST['excel'])){
	$excel 		= $_POST['excel'];
	$fecha_ini 	= $excel['fecha_ini'];
	$fecha_fin 	= $excel['fecha_fin'];
	$centro 	= $excel['centro'];
	$id 		= $excel['id'];
	$fecha1= new DateTime($fecha_ini);
	$fecha2= new DateTime($fecha_fin);
	$diff = $fecha1->diff($fecha2);
	$diff->days;
	if($diff->days >= 5){
		header('Location: lista?msj=seleccione menos de  6 dias');
		die();
	}
	}
	if(isset($_GET['id'],$_GET['id_tema'])){	
		$id = $_GET['id'];
		$id_tema = $_GET['id_tema'];
	}
	
	function csv($str,$headcol,$name,$delimiter=","){
		global $fecha2,$idcentro,$fecha;
       $filename = "" . $name . ".csv";
       $f = fopen('php://memory', 'w');	 
		  $db=Db::conectar();
		  $db->query("SET NAMES 'UTF8' ");
		  $sql = $str;
          $select=$db->prepare($sql);
		  $select->execute();
          while ($registro=$select->fetch()) { 
				//$lineData = array($registro['telefono'],$registro['nombre']);
				//fputcsv($f, $lineData $delimiter);
				fputcsv($f, $registro, $delimiter);
			}
		 Db::desconectar();
		fseek($f, 0);
		header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '";');
		fpassthru($f);
		exit;
	}
	function csv_excel($str,$headcol,$name,$delimiter=","){
		global $fecha2,$idcentro,$fecha,$aid;
	$headcolumna = explode( ',', $headcol);	
	echo '<!DOCTYPE html>
			<html>
			<head>
			<title>SF</title>
			<meta charset="UTF-8">
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			</head>
			<body>
				<table>
					<tr>';
					for ($i = 0; $i < count($headcolumna); $i++)
					{
					 echo '<th>'.$headcolumna[$i].'</th>';
					}
	echo 			'</tr>';
		  $db=Db::conectar();
		  $db->query("SET NAMES 'UTF8' ");
		  $sql = $str;
          $select=$db->prepare($sql);
		  $select->execute();
		  $cuenta_col = $select->columnCount();
          while ($registro=$select->fetch()) {
			echo '<tr>';
			for ($i = 0; $i < $cuenta_col; $i++)
				{
				    echo '<td>'.$registro[$i].'</td>';
				}				
			echo '</tr>';
			}
		 Db::desconectar();
    echo        '</table>	
			</body>
			</html>';
    $filename = "csv_excel_".$name.".xls";
	header("Content-Type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=\"$filename\"");
	}
	function excel($str,$headcol,$name){
		global $fecha2,$idcentro,$fecha,$aid;
	$headcolumna = explode( ',', $headcol);
	require('phpexcel/Classes/PHPExcel.php');
	$objPHPExcel = new PHPExcel();
	$objPHPExcel->
	getProperties()
	->setCreator("bk77.co")
	->setLastModifiedBy("bk77.co")
	->setTitle("rcahuapp")
	->setSubject("rcahuapp")
	->setDescription("Documento generado en CD Juliaca")
	->setKeywords("usuarios $aid")
	->setCategory("reportes");
		  $db=Db::conectar();
		  $db->query("SET NAMES 'UTF8' ");
          $select=$db->prepare($str);
		  $select->execute();
        $cuenta_col = $select->columnCount();
        $objPHPExcel->setActiveSheetIndex(0);
		$columnCol = 'A';	
		$nrCol = 1;
		if(count($headcolumna) > 1){
			for ($i = 0; $i < count($headcolumna); $i++)
				{
				 $objPHPExcel->getActiveSheet()->setCellValue($columnCol.$nrCol,$headcolumna[$i])
											   ->getStyle($columnCol.$nrCol)
												->getFill()
												->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
												->getStartColor()->setRGB('E02124');
				 $columnCol++;
				}
		$n=2; 		
		} else {
		$n=1; 	
		}		
        while ($registro=$select->fetch()){
			$column = 'A';	
			for ($i = 0; $i < $cuenta_col; $i++)
				{
				 $objPHPExcel->getActiveSheet()->setCellValue($column.$n,$registro[$i]);
				 $column++;
				}
		$n++;		
        }
		Db::desconectar();
	$objPHPExcel->getActiveSheet()->setTitle("$aid");
	$objPHPExcel->setActiveSheetIndex(0);
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="'.$name.'.xls"');
	header('Cache-Control: max-age=0');
	$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');
	$objWriter->save('php://output');
	exit;
	}
		switch ($id):
			case "RSrcahuapp":
				if(isset($fecha_ini,$fecha_fin,$centro)){
					if ($centro=='ALL'): $centro=""; else: $centro="AND centro='$centro'"; endif;
				$str = "SELECT 
							if (entregado=0,'No','Si') as entregado,
							if (alerta=0,'No','Si') as alerta,
							if (rechazo=0,'No','Si') as rechazo,
							mr,centro,comentario,fechahoraalerta,registrofin,Ruta,Vehiculo,Fecha,Codigo,Cliente,Direccion,Abre,Cierra,Viaje,Sec1,Entrega,
							cjasrechazadas,Llega,Sale,Ciudad,clasifica_envases,
							autoriza_rech,0 as minutosmodulacion,ZNPVTA,SupervisionID,
							ZNPVTA as ZNPVTAd,cjasclasificadas,encuesta,repaso,dni,HL,Packs,Transporte,TipoCliente,CodEt,Tipo_Riesgo,Servicio_Flex,Tipo_NPS,RMD,Sec2
					    FROM `t77_rs` WHERE Fecha >= '$fecha_ini' AND Fecha <= '$fecha_fin' $centro";
				$headcol="entregado,alerta,rechazo,mr,centro,retroalimentacion,fechahoraalerta,registrofin,Ruta,Vehiculo,Fecha,Codigo,Cliente,Direccion,Abre,Cierra,Viaje,
						 Sec1,Entrega,CjasRechazado,Llega,Sale,Ciudad,ClasificaEnvases,AutorizaRechazo,MinutosModulacion,zonaac,supervisor,agente,CjasClasificadas,
						 Encuesta,Repaso,UsuarioRegistro,Hl_Programado,Packs_Programado,Transporte,UnidadDeNegocio,Empresa,TipoRiesgo,ServicioFlex,TipoNPS,RMD,UsuarioAlerta";
				csv_excel($str,$headcol,'HojaRuta');
				}
				break;
			case "POCcritico":
					if(isset($fecha_ini,$fecha_fin,$centro)){
						if ($centro=='ALL'): $centroAll=""; else: $centroAll="AND centro='$centro'"; endif; 
				$str = "SELECT 
							(SELECT descripcion FROM exa_temas WHERE id=id_tema) AS tema, 
							(SELECT descripcion FROM exa_grupo_preguntas WHERE id=id_grupo_preguntas) AS grupo_pregunta, 
							(SELECT pregunta FROM exa_preguntas WHERE id=id_preguntas) AS pregunta, 
							(SELECT respuestas FROM exa_respuesta WHERE id=id_respueta) AS sub_pregunta, 
							respuesta_user,user_registro,placa AS cliente,fecha,
							(SELECT nombre FROM t77_mc WHERE codcli=placa) AS nombrecli, 
							(SELECT direccion FROM t77_mc WHERE codcli=placa) AS direccion,centro
						FROM `exa_detalle_reparto` WHERE fecha >= '$fecha_ini' AND fecha <= '$fecha_fin' AND respuesta_user <> '' $centroAll";
				$headcol="tema,grupo_pregunta,pregunta,sub_pregunta,respuesta_user,user_registro,cliente,fecha,nombrecli,direccion,centro";
				csv_excel($str,$headcol,'PocCritico');
					}
				break;
			case "MsjClientesMPILCOSA": //Multi SMS Sender (MSS) - MPILCOSA xls
					if(isset($fecha_ini,$fecha_fin,$centro)){
						if ($centro=='ALL'): $centroAll=""; else: $centroAll="AND centro='$centro'"; endif; 
				$str = "Select CONCAT('Sr(a) ', mc3.Nombre, '.') as name, mc3.telefono as Mobile, concat('{','name','}', ' Para consultar su pedido Backus ingrese a https://bit.ly/39RE3x0') as Message FROM 
		  (SELECT mc2.codcli, if((INSTR(mc2.Nombres,' ')) = 0, mc2.Nombres, trim(mid(mc2.Nombres,1,(INSTR(mc2.Nombres,' '))))) as Nombre, mc2.telefono From 
		  (SELECT mc.codcli, if(INSTR(mc.nombre,',') = 0,'Cliente', trim(mid(mc.nombre,(INSTR(mc.nombre,',')+1),100))) as Nombres, mc.telefono FROM 
			  (SELECT `Codigo` FROM `t77_rs` WHERE Fecha>='$fecha_ini' AND Fecha<='$fecha_fin' $centroAll) AS rs 
			  LEFT JOIN 
			  (
			  SELECT codcli,nombre,Telef1 AS telefono, character_length(CAST(Telef1 AS INTEGER)) as ContTel FROM t77_mc 
			  UNION ALL
			  SELECT codcli,nombre,Telef2 AS telefono, character_length(CAST(Telef2 AS INTEGER)) as ContTel FROM t77_mc 
			  ) AS mc 
			  ON rs.`Codigo` = mc.codcli
			  WHERE mc.ContTel = 9 ) as mc2 ) as mc3";
				$headcol="Name,Mobile,Message";
				excel($str,$headcol,'SAGroupText');
					}
				break;
			case "Msj2ClientesMPILCOSA": //SA Group Text Lite - MPILCOSA csv-excel csv
					if(isset($fecha_ini,$fecha_fin,$centro)){
						if ($centro=='ALL'): $centroAll=""; else: $centroAll="AND centro='$centro'"; endif; 
				$str = "Select mc3.codcli, CONCAT('Sr(a) ', mc3.Nombre) as nombre, mc3.telefono FROM 
		  (SELECT mc2.codcli, if((INSTR(mc2.Nombres,' ')) = 0, mc2.Nombres, trim(mid(mc2.Nombres,1,(INSTR(mc2.Nombres,' '))))) as Nombre, mc2.telefono From 
		  (SELECT mc.codcli, if(INSTR(mc.nombre,',') = 0,'Cliente', trim(mid(mc.nombre,(INSTR(mc.nombre,',')+1),100))) as Nombres, mc.telefono FROM 
			  (SELECT `Codigo` FROM `t77_rs` WHERE Fecha>='2021-08-16' AND Fecha<='2021-08-16' AND centro='BK77') AS rs 
			  LEFT JOIN 
			  (
			  SELECT codcli,nombre,Telef1 AS telefono, character_length(CAST(Telef1 AS INTEGER)) as ContTel FROM t77_mc 
			  UNION ALL
			  SELECT codcli,nombre,Telef2 AS telefono, character_length(CAST(Telef2 AS INTEGER)) as ContTel FROM t77_mc 
			  ) AS mc 
			  ON rs.`Codigo` = mc.codcli
			  WHERE mc.ContTel = 9 ) as mc2 ) as mc3";
				$headcol="";
				excel($str,$headcol,'MultiSMS');
					}
				break;	
			case "AlertarSalesForceObed": //Csv-Sf-Dia-TodosCDs 
					if(isset($fecha_ini,$fecha_fin,$centro)){
						if ($centro=='ALL'): $centroAll=""; else: $centroAll="AND centro='$centro'"; endif; 
				$str = "SELECT 
				c.`Código SAP`,c.`Fecha de creación`,c.`Creado por`,1 AS `Recuento de comentarios`,
				CONCAT(
				c.`Código SAP`,
				'@Zona ',IF(c.`zonaac` IS NULL,'',c.`zonaac`),
				'@',IF(c.`agente` IS NULL,'',c.`agente`),
				'@Logistics RTM ',c.`centro`,
				'@Distribución ',c.`centro`,
				'@',c.autoriza_rech,
				'*',c.`mr`,' : ',c.`Recuento de comentarios`,
				'NRO CAJAS: ',c.`Entrega`,
				IF(c.empresa IS NULL,'',c.empresa),
				'Reparto ',c.`Ruta`,' ',IF(c.telefonoreparto IS NULL,'',c.telefonoreparto),' - '
				'SUP ',c.`Ruta`,' 0',
				c.`Ruta`,'Tipo de cliente Telefono de Cliente',
				'ENVIADO :',c.`Hora_Envio`,
				'Version 14 #AlertaRechazo' 
				) as cuerpo,c.Hora_Envio,c.centro
				FROM (
				SELECT rs.`Codigo` as `Código SAP`,rs.`Fecha` as `Fecha de creación`, 
				CONCAT(rs.`centro`,' ',rs.`dni`) AS `Creado por`,rs.`comentario` as `Recuento de comentarios`, 
				mc.`zonaac`,mc.`agente`,rs.`centro`,rs.autoriza_rech,rs.`mr`,rs.`Entrega`,
				rs.`Ruta` AS empresa,rs.`Ruta` AS telefonoreparto,rs.`Ruta`,DATE_FORMAT(rs.`fechahoraalerta`,'%H:%i') AS Hora_Envio
				FROM 
				(
				  SELECT * FROM `t77_rs` WHERE Fecha='$fecha_ini' AND Fecha='$fecha_fin' AND alerta=1 $centroAll
				) AS rs LEFT JOIN 
				(
				SELECT * FROM `t77_mc` 
				) AS mc
				ON rs.`Codigo` = mc.`codcli`
				) AS c";
				$headcol="Código SAP,Fecha de creación,Creado por,Recuento de comentarios,Cuerpo,Hora_Envio,Cd";
				csv_excel($str,$headcol,'AlertarSalesForceObed');
					}
				break;
				case "FlotaCkLST2salida": //Inspección diaria de unidad (salida)
					if(isset($fecha_ini,$fecha_fin,$centro)){
						if ($centro=='ALL'): $centroAll=""; else: $centroAll="AND centro='$centro'"; endif; 
				$str = "SELECT fecha,centro,fecha_ini_user,fecha_fin_user,
				user_registro,placa,
				(SELECT descripcion FROM exa_temas WHERE id=id_tema) AS desc_tema,
				(SELECT descripcion FROM exa_grupo_preguntas WHERE id=id_grupo_preguntas) AS desc_grupo_preguntas,
				(SELECT pregunta FROM exa_preguntas WHERE id=id_preguntas) AS desc_preguntas,
				tipo_pregunta,desc_resp,respuesta_user
				FROM `exa_detalle_user` WHERE fecha >= '$fecha_ini' AND fecha <= '$fecha_fin' AND id_tema='2147483647' $centroAll";
				$headcol="fecha,centro,fecha_ini_user,fecha_fin_user,user_registro,placa,CheckList,grupo_preguntas,preguntas,tipo_pregunta,descripcion_resp,respuesta_usuario";
				csv_excel($str,$headcol,'FlotaCkLST2');
					}
				break;						
				case "FlotaCkLST2retorno": //Inspección diaria de unidad (retorno)
					if(isset($fecha_ini,$fecha_fin,$centro)){
						if ($centro=='ALL'): $centroAll=""; else: $centroAll="AND centro='$centro'"; endif; 
				$str = "SELECT fecha,centro,fecha_ini_user,fecha_fin_user,
				user_registro,placa,
				(SELECT descripcion FROM exa_temas WHERE id=id_tema) AS desc_tema,
				(SELECT descripcion FROM exa_grupo_preguntas WHERE id=id_grupo_preguntas) AS desc_grupo_preguntas,
				(SELECT pregunta FROM exa_preguntas WHERE id=id_preguntas) AS desc_preguntas,
				tipo_pregunta,desc_resp,respuesta_user
				FROM `exa_detalle_user` WHERE fecha >= '$fecha_ini' AND fecha <= '$fecha_fin' AND id_tema='359282242' $centroAll";
				$headcol="fecha,centro,fecha_ini_user,fecha_fin_user,user_registro,placa,CheckList,grupo_preguntas,preguntas,tipo_pregunta,descripcion_resp,respuesta_usuario";
				csv_excel($str,$headcol,'FlotaCkLST2');
					}
				break;
				case "usuariosRM":
					if(isset($fecha_ini,$fecha_fin,$centro)){
						if ($centro=='ALL'): $centroAll=""; else: $centroAll="AND centro='$centro'"; endif; 
				$str = "SELECT 
				nombre,apellidos,dni,centro,empresa,ruc,responsable,telefono_res,domicilio,Actividad,puesto,area,division,supervisor,domicilio_user,
				telefono,email,genero,brevete,brevete_cat,vencimiento_brevete,fecha_alta,pin,user_registro,tipo,descripcion,fecha_estado 
				FROM (		  
				SELECT 
				`nombre`, `apellidos`, `dni`, `centro`, 
				IF(`estado`=0,'Ina','Act') AS estado,
				(SELECT descripcion FROM usuario_empresa WHERE id=`id_empresa`) AS empresa, 
				(SELECT ruc FROM usuario_empresa WHERE id=`id_empresa`) AS ruc, 
				(SELECT responsable FROM usuario_empresa WHERE id=`id_empresa`) AS responsable, 
				(SELECT telefono FROM usuario_empresa WHERE id=`id_empresa`) AS telefono_res, 
				(SELECT domicilio FROM usuario_empresa WHERE id=`id_empresa`) AS domicilio, 
				(SELECT Actividad FROM usuario_empresa WHERE id=`id_empresa`) AS Actividad, 
				(SELECT descripcion FROM usuario_puesto WHERE id=`puesto`) AS puesto, 
				(SELECT descripcion FROM usuario_area WHERE id=`area`) AS area, 
				(SELECT descripcion FROM usuario_division WHERE id=`division`) AS division, 
				(SELECT descripcion FROM usuario_supervisor WHERE id=`supervisor`) AS supervisor, 
				`domicilio_user`, `telefono`, `email`, 
				(SELECT descripcion FROM usuario_genero WHERE id=`genero`) AS genero, 
				`brevete`, 
				(SELECT descripcion FROM usuario_brevete WHERE id=`brevete_cat`) AS brevete_cat, 
				`vencimiento_brevete`, `fecha_alta`, `pin`, `user_registro`
				FROM `usuarios` WHERE puesto NOT IN (28,29) $centroAll
				) AS hc LEFT JOIN (SELECT tipo,descripcion,fecha_estado,dni AS dniina FROM `usuario_baja` WHERE dni<>0 $centroAll) AS ina 
				ON hc.dni = ina.dniina ORDER BY hc.dni";
				$headcol="nombre,apellidos,dni,centro,empresa,ruc,responsable,telefono_res,domicilio,actividad,puesto,area,
				division,supervisor,domicilio_user,telefono,email,genero,brevete,brevete_cat,vencimiento_brevete,fecha_alta,pin,
				user_registro,Tipo Bloq/Act,descripcion Bloq/Act,Fecha Bloq/Act";
				csv_excel($str,$headcol,'usuariosRM');
					}
				break;	
				case "pruevasCovid":
					if(isset($fecha_ini,$fecha_fin,$centro)){
						if ($centro=='ALL'): $centroAll=""; else: $centroAll="AND centro='$centro'"; endif; 
				$str = "SELECT 
				cb.dni,hc.`nombre`,hc.`apellidos`,hc.empresa,hc.ruc,hc.responsable,hc.telefono_res,hc.puesto,hc.area,hc.telefono,hc.email,cb.tipoexamen,
				cb.fecha_examen,cb.dias_cuarentena,cb.seguimientomedico,cb.positivo,cb.laboratorio,cb.observacion,cb.centro
				FROM 
				(
				SELECT 
				`dni`, 
				(SELECT descripcion FROM usuario_covid_examen WHERE id=`tipo_examen`) AS tipoexamen, 
				`fecha_examen`, 
				`dias_cuarentena`, 
				IF(`seguimiento_medico`=1,'Si','No') AS seguimientomedico , 
				IF(`positivo`=1,'Si','No') AS positivo , 
				`laboratorio`, 
				`observacion`, 
				`centro` 
				FROM `usuario_covid` WHERE `tipo_examen` <> '' $centroAll
				) AS cb LEFT JOIN 
				(
				SELECT 
				`nombre`, `apellidos`, `dni`, `centro`, 
				IF(`estado`=0,'Ina','Act') AS estado,
				(SELECT descripcion FROM usuario_empresa WHERE id=`id_empresa`) AS empresa, 
				(SELECT ruc FROM usuario_empresa WHERE id=`id_empresa`) AS ruc, 
				(SELECT responsable FROM usuario_empresa WHERE id=`id_empresa`) AS responsable, 
				(SELECT telefono FROM usuario_empresa WHERE id=`id_empresa`) AS telefono_res, 
				(SELECT domicilio FROM usuario_empresa WHERE id=`id_empresa`) AS domicilio, 
				(SELECT Actividad FROM usuario_empresa WHERE id=`id_empresa`) AS Actividad, 
				(SELECT descripcion FROM usuario_puesto WHERE id=`puesto`) AS puesto, 
				(SELECT descripcion FROM usuario_area WHERE id=`area`) AS area, 
				(SELECT descripcion FROM usuario_division WHERE id=`division`) AS division, 
				(SELECT descripcion FROM usuario_supervisor WHERE id=`supervisor`) AS supervisor, 
				`domicilio_user`, `telefono`, `email`, 
				(SELECT descripcion FROM usuario_genero WHERE id=`genero`) AS genero, 
				`brevete`, 
				(SELECT descripcion FROM usuario_brevete WHERE id=`brevete_cat`) AS brevete_cat, 
				`vencimiento_brevete`, `fecha_alta`, `pin`
				FROM `usuarios` WHERE puesto NOT IN (28,29) $centroAll
				) AS hc ON cb.dni = hc.dni ORDER BY  hc.empresa, hc.`apellidos` ASC";
				$headcol="dni,nombre,apellidos,empresa,ruc,responsable,telefono_res,puesto,area,telefono,email,
				tipoexamen,fecha_examen,dias_cuarentena,seguimientomedico,positivo,laboratorio,observacion,centro";
				csv_excel($str,$headcol,'pruevasCovid');
					}
				break;
				case "checklist":
					if(isset($id,$id_tema,$idcentro)){
						if ($centro=='ALL'): $centroAll=""; else: $centroAll="AND centro='$centro'"; endif; 
				$str = "SELECT
				(SELECT descripcion FROM exa_temas WHERE id=`id_tema`) as tema,
				(SELECT descripcion FROM exa_grupo_preguntas WHERE id=`id_grupo_preguntas`) as grupo_preguntas,
				(SELECT pregunta FROM exa_preguntas WHERE id=`id_preguntas`) as preguntas,
				(SELECT descripcion FROM exa_preguntas WHERE id=`id_preguntas`) as pregunta_descripcion,
				`desc_resp` AS respuestas,
				if(`respuesta_user`=`id`,1,`respuesta_user`) as usuario_respuesta, 
				`user_registro`, 
				`grupo`, 
				`empresa`, 
				`fecha_registro` as fecha_check_list, 
				`bk`, 
				`puesto_trabajo`, 
				`codigo_cliente`, 
				`area_oficina`, 
				`area_almacen`, 
				`usuario_hc`, 
				`flota`, 
				`txt_comentario` as comentario
				FROM `exa_detalle_checklist` 
				WHERE id_tema='$id_tema' AND centro='$idcentro' AND `st`=1 AND `respuesta_user`<>''";	
				$headcol="tema,grupo_preguntas,preguntas,preguntas_descripcion,respuestas,usuario_respuesta,user_registro,grupo,empresa,
				fecha_check_list,bk,puesto_trabajo,codigo_cliente,area_oficina,area_almacen,usuario_hc,flota,comentario";
				csv_excel($str,$headcol,'checklist');
					}
				break;	
				case "listaasistenciaT2":
					if(isset($id,$fecha_ini,$fecha_fin,$idcentro)){
						if ($centro=='ALL'): $centroAll=""; else: $centroAll="AND centro='$centro'"; endif; 
				$str = "SELECT 
				a.`dni`,
				(SELECT `apellidos` FROM `usuarios` WHERE `dni` = a.`dni`) AS Nombre_Apellidos, 
				a.`fecha`, 
				a.`llegada`, 
				a.`salida`, 
				a.`centro`, 
				a.`excluye` 
				FROM (
				SELECT 
				`dni`,
				`fecha`, 
				`llegada`, 
				`salida`, 
				`centro`, 
				`excluye` 
				FROM `t77_tiempos_personal` 
				WHERE centro='$centro' AND fecha >= '$fecha_ini' AND fecha <= '$fecha_fin' 
				) AS a";
				$headcol="dni,Nombre_Apellidos,fecha,llegada,salida,centro,perniso_vacaciones";
				csv_excel($str,$headcol,'listaasistenciaT2');
					}
				break;	
				case "npsmpilcosa":
					if(isset($id,$fecha_ini,$fecha_fin,$idcentro)){
						if ($centro=='ALL'): $centroAll=""; else: $centroAll="AND centro='$centro'"; endif; 
				$str = "SELECT checklist,pregunta,subpregunta,respuestas,aid,codigocliente,fecharegistro,centro,grupo FROM `exa_array` WHERE centro='$centro'";
				$headcol="checklist,pregunta,subpregunta,respuestas,aid,codigocliente,fecharegistro,centro,grupo";
				csv_excel($str,$headcol,'nps_mpilcosa');
					}
				break;	
				case "demoras-atencion-t2":
					if(isset($id,$fecha_ini,$fecha_fin,$idcentro)){
						if ($centro=='ALL'): $centroAll=""; else: $centroAll="AND centro='$centro'"; endif; 
				$str = "SELECT 
				b.Transporte, b.Fecha, b.centro as BK, b.Ruta, b.Viaje, b.Codigo as Cod_Cli, a.minutos_demora_real as Tiempo, 
				a.motivo_d as Motivo , a.comentario_d as Comentario 
				FROM 
				(
				SELECT
				indx_d,  
				(CAST(Date_format(`tiempo_demora_real`,'%H') AS DECIMAL)*60)+(CAST(Date_format(`tiempo_demora_real`,'%i') AS DECIMAL)) as minutos_demora_real,
				`motivo_d`,
				`comentario_d`
				FROM `t77_rs_demoras`
				) AS a INNER JOIN
				(
				SELECT indx,Transporte,Fecha,centro,Ruta,Viaje,Codigo FROM `t77_rs` WHERE Fecha >= '$fecha_ini' AND Fecha <= '$fecha_fin'  $centroAll
				) AS b 
				ON a.indx_d = b.indx";
				$headcol="Transporte,Fecha,BK,Ruta,Viaje,Cod_Cli,Tiempo,Motivo,Comentario";
				csv_excel($str,$headcol,'demoras_atencion_t2');
					}
				break;															
			default:

		endswitch;
	} else {
     echo 'sin acceso';		
	}
	}
	ob_end_flush();	
	?>
?>





