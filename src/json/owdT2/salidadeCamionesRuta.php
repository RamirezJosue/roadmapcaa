<?php
        $WhereAnd = '';
    if(isset($_GET['centro'])){
        $centro = $_GET['centro'];
        $WhereAnd .= " AND centro =  '$centro' ";
    } else {
        $WhereAnd .= " AND centro = 'BK77'";
    }
    if(isset($_GET['fechaIni'],$_GET['fechaFin'])){
        $fechaIni = $_GET['fechaIni'];
        $fechaFin = $_GET['fechaFin'];
        $WhereAnd .= " AND fecha_registro >= '$fechaIni' AND fecha_registro <= '$fechaFin' ";
    } else {
        $WhereAnd .= " AND fecha_registro >= '2021-08-01' AND fecha_registro <= '2021-08-31' ";
    }
    if(isset($_GET['rutaT2'])){
        $rutaT2 = $_GET['rutaT2'];
        $WhereAnd .= " AND bk = '$rutaT2' ";
    } 
    if(isset($_GET['empresaT2'])){
        $empresaT2 = $_GET['empresaT2'];
        $WhereAnd .= " AND empresa = '$empresaT2' ";
    }
    $sql ="SELECT 
    v.tema,v.centro,v.empresa,v.bk,
    SUM(v.Ene) AS Ene,
    SUM(v.Feb) AS Feb,
    SUM(v.Mar) AS Mar,
    SUM(v.Abr) AS Abr,
    SUM(v.May) AS May,
    SUM(v.Jun) AS Jun,
    SUM(v.Jul) AS Jul,
    SUM(v.Ago) AS Ago,
    SUM(v.Seb) AS Seb,
    SUM(v.Oct) AS Oct,
    SUM(v.Nov) AS Nov,
    SUM(v.Dic) AS Dic
    FROM 
    (
    SELECT 
    s.tema,s.centro,s.grupo,s.empresa,s.bk,
    IF(s.mes='01',1,0) as Ene,
    IF(s.mes='02',1,0) as Feb,
    IF(s.mes='03',1,0) as Mar,
    IF(s.mes='04',1,0) as Abr,
    IF(s.mes='05',1,0) as May,
    IF(s.mes='06',1,0) as Jun,
    IF(s.mes='07',1,0) as Jul,
    IF(s.mes='08',1,0) as Ago,
    IF(s.mes='09',1,0) as Seb,
    IF(s.mes='10',1,0) as Oct,
    IF(s.mes='11',1,0) as Nov,
    IF(s.mes='12',1,0) as Dic
    FROM 
    (
    SELECT 
    (SELECT descripcion FROM `exa_temas` WHERE id=`id_tema`) as tema,
    id_preguntas,
    (SELECT pregunta FROM `exa_preguntas` WHERE id=`id_preguntas`) as preguntas, 
    (SELECT respuestas FROM `exa_respuesta` WHERE id=`id_respueta`) as respuestas, 
    `respuesta_user`, `centro`, `placa`, `grupo`, `fecha`, `st`, `empresa`, `fecha_registro`, `bk`, date_format(`fecha_registro`,'%m') as mes,
    `puesto_trabajo`, `codigo_cliente`, `area_oficina`, `area_almacen`, `usuario_hc`, 
    `flota`, `txt_actions`, `txt_comentario` 
    FROM `exa_detalle_checklist` 
    WHERE id_tema=950050427 
    AND id_grupo_preguntas <> 40 AND respuesta_user <> ''
    $WhereAnd
    ) AS s GROUP BY
    s.tema,s.centro,s.grupo,s.empresa,s.mes,s.bk
    ) AS v GROUP BY
    v.tema,v.centro,v.empresa,v.bk
    ";	

        include '../../bd/banco.php';
			$db=Db::conectar();
			$db->query("SET NAMES 'UTF8' ");		
            $select=$db->prepare($sql);
		    $select->execute();
            if (!$select){
                echo 'Error al ejecutar la consulta';
            }else{
                $arreglo['SalidaCamiones'] = $select->fetchAll(PDO::FETCH_ASSOC);
            }
            if(!empty($arreglo)){
                header("Access-Control-Allow-Origin: *");//this allows cors
                header('Content-Type: application/json');
                print json_encode($arreglo, JSON_UNESCAPED_UNICODE);
            }else{
            echo 'error';
            } 
?>