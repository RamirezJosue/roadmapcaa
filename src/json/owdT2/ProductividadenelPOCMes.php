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
    z.id_preguntas,                    
    z.preguntas,
    SUM(z.Ene) AS Ene,
    SUM(z.Feb) AS Feb,
    SUM(z.Mar) AS Mar,
    SUM(z.Abr) AS Abr,
    SUM(z.May) AS May,
    SUM(z.Jun) AS Jun,
    SUM(z.Jul) AS Jul,
    SUM(z.Ago) AS Ago,
    SUM(z.Seb) AS Sep,
    SUM(z.Oct) AS Oct,
    SUM(z.Nov) AS Nov,
    SUM(z.Dic) AS Dic
    FROM (
    SELECT 
    m.id_preguntas,                    
    m.preguntas,
    IF(m.mes='01',m.resultado,0) as Ene,
    IF(m.mes='02',m.resultado,0) as Feb,
    IF(m.mes='03',m.resultado,0) as Mar,
    IF(m.mes='04',m.resultado,0) as Abr,
    IF(m.mes='05',m.resultado,0) as May,
    IF(m.mes='06',m.resultado,0) as Jun,
    IF(m.mes='07',m.resultado,0) as Jul,
    IF(m.mes='08',m.resultado,0) as Ago,
    IF(m.mes='09',m.resultado,0) as Seb,
    IF(m.mes='10',m.resultado,0) as Oct,
    IF(m.mes='11',m.resultado,0) as Nov,
    IF(m.mes='12',m.resultado,0) as Dic
    FROM (
SELECT 
b.preguntas,
b.id_preguntas,
b.mes,
SUM(b.respuesta_valor) as respuesta_valor,
SUM(b.contador) as contador,
    CASE
        WHEN b.id_preguntas = 'xxx' THEN ((SUM(b.respuesta_valor) / SUM(b.contador))-1)*-1
        ELSE  (SUM(b.respuesta_valor) / SUM(b.contador))
    END AS resultado
FROM(
SELECT 
s.tema,s.preguntas,s.respuestas, s.id_preguntas,
CASE
    WHEN s.respuestas = 'Planea la estrategia de atencion antes de estacionar(Multiparada, acceso dificil).' THEN 1
    WHEN s.respuestas = 'Confirma con el POCs el pedido, la cobranza y el recojo antes de iniciar la descarga' THEN 1
    WHEN s.respuestas = 'Después de estacionar, ayuda en la descarga y armado de producto' THEN 1
    WHEN s.respuestas = 'Si debe quedarse en el camión, este se encuentra agilizando el proceso de carga/descarga de los sigu' THEN 1
    WHEN s.respuestas = 'De encontrar un POC de dificil acceso/mal geoposicionado que afecta los tiempos planeados, reporta e' THEN 1
    WHEN s.respuestas = 'Luego de separar productos, desplaza el camión hasta el próximo POC, donde encuentra al ayudante.' THEN 1
    WHEN s.respuestas = 'Después de estacionar, todos ayudan a inmovilizar el vehiculo.' THEN 1
    WHEN s.respuestas = 'Arma la carga en la carretilla buscando maximizar su ocupación para reducir el número de viajes(Sin' THEN 1
    WHEN s.respuestas = 'Tienen conocimiento sobre la lectura de los documentos emitidos(factura, cobranza, prestamo de envas' THEN 1
    WHEN s.respuestas = 'Si' THEN 1
    WHEN s.respuestas = 'No' THEN 0
    ELSE 0
END AS respuesta_valor,
s.contador,s.centro,s.placa,s.grupo,s.fecha,s.st,s.empresa,s.fecha_registro,date_format(s.fecha_registro,'%m') as mes,
s.bk,s.puesto_trabajo,s.codigo_cliente,s.area_oficina,s.area_almacen,s.usuario_hc,s.flota,s.txt_actions,s.txt_comentario
FROM 
(
SELECT 
(SELECT descripcion FROM `exa_temas` WHERE id=`id_tema`) as tema, id_preguntas,
(SELECT pregunta FROM `exa_preguntas` WHERE id=`id_preguntas`) as preguntas, 
(SELECT respuestas FROM `exa_respuesta` WHERE id=`id_respueta`) as respuestas, 
1 as contador,
`respuesta_user`, `centro`, `placa`, `grupo`, `fecha`, `st`, `empresa`, `fecha_registro`, `bk`, 
`puesto_trabajo`, `codigo_cliente`, `area_oficina`, `area_almacen`, `usuario_hc`, 
`flota`, `txt_actions`, `txt_comentario` 
FROM `exa_detalle_checklist` 
WHERE id_tema=228568973
AND id_grupo_preguntas <> 46 AND respuesta_user <> '' 
$WhereAnd 
) AS s  
) AS b 
GROUP BY  
b.preguntas,
b.id_preguntas,
b.mes
    ) AS m GROUP BY                      
    m.id_preguntas,                    
    m.preguntas,
    m.mes    
    ) AS z GROUP BY
    z.id_preguntas,                    
    z.preguntas";
    
        include '../../bd/banco.php';
			$db=Db::conectar();
			$db->query("SET NAMES 'UTF8' ");			
            $select=$db->prepare($sql);
		    $select->execute();
            if (!$select){
                echo 'Error al ejecutar la consulta';
            }else{
                $arreglo['VisitamercadoporseguridadMes'] = $select->fetchAll(PDO::FETCH_ASSOC);
            }
            if(!empty($arreglo)){
                header("Access-Control-Allow-Origin: *");//this allows cors
                header('Content-Type: application/json');
                print json_encode($arreglo, JSON_UNESCAPED_UNICODE);
            }else{
            echo 'error';
            }
?>