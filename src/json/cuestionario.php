<?php 
    $centro=$_GET['centro'];
    $error = 0;
    //session_start();
    //if (!isset($_COOKIE['usuarioid'])) {
    if(0 != 0){  
        echo 'error no hay session';
    } else {
        /*
        $_SESSION['usuarionombre']=$_COOKIE['usuarionombre'];
        $_SESSION['usuarioapellidos']=$_COOKIE['usuarioapellidos'];
        $_SESSION['usuarioid']=$_COOKIE['usuarioid'];
        $_SESSION['usuariocentro']=$_COOKIE['usuariocentro'];
        $_SESSION['usuariotipo']=$_COOKIE['usuariotipo'];
        $_SESSION['usuarioaccesos']=$_COOKIE['usuarioaccesos'];
        $_SESSION['usuarioestado']=$_COOKIE['usuarioestado'];	
        $_SESSION['usuarioempresa']=$_COOKIE['usuarioempresa'];
        $_SESSION['usuariopuesto']=$_COOKIE['usuariopuesto'];	
        $usuarionombre=$_COOKIE['usuarionombre'];
        $usuarioapellidos=$_COOKIE['usuarioapellidos']; 
        $aid = $_COOKIE['usuarioid'];
        $idcentro = $_COOKIE['usuariocentro'];	
        $usuariotipo=$_COOKIE['usuariotipo'];
        $usuarioaccesos=$_COOKIE['usuarioaccesos'];
        $usuarioestado=$_COOKIE['usuarioestado'];	
        $usuarioempresa=$_COOKIE['usuarioempresa'];
        $usuariopuesto=$_COOKIE['usuariopuesto'];
        */
        include '../bd/banco.php';
			$db=Db::conectar();
			$db->query("SET NAMES 'UTF8' ");
		    $sql ="SELECT 
                    b.tema,
                    b.id_preguntas,
                    b.preguntas,
                    b.centro,
                    b.empresa,
                    b.mes,
                    b.bk,
                    SUM(b.respuesta_valor) as respuesta_valor,
                    SUM(b.contador) as contador
                    FROM(
                    SELECT 
                    s.tema,s.id_preguntas,s.preguntas,s.respuestas, 
                    if(respuestas='Si',1,if(respuestas='No',0,0)) as respuesta_valor, 
                    s.contador,s.centro,s.placa,s.grupo,s.fecha,s.st,s.empresa,s.fecha_registro,date_format(s.fecha_registro,'%m') as mes,
                    s.bk,s.puesto_trabajo,s.codigo_cliente,s.area_oficina,s.area_almacen,s.usuario_hc,s.flota,s.txt_actions,s.txt_comentario
                    FROM 
                    (
                    SELECT 
                    (SELECT descripcion FROM `exa_temas` WHERE id=`id_tema`) as tema,
                    id_preguntas,
                    (SELECT pregunta FROM `exa_preguntas` WHERE id=`id_preguntas`) as preguntas, 
                    (SELECT respuestas FROM `exa_respuesta` WHERE id=`id_respueta`) as respuestas, 
                    1 as contador,
                    `respuesta_user`, `centro`, `placa`, `grupo`, `fecha`, `st`, `empresa`, `fecha_registro`, `bk`, 
                    `puesto_trabajo`, `codigo_cliente`, `area_oficina`, `area_almacen`, `usuario_hc`, 
                    `flota`, `txt_actions`, `txt_comentario` 
                    FROM `exa_detalle_checklist` 
                    WHERE id_tema=950050427 
                    AND `fecha_registro`>='2021-01-01' AND `fecha_registro` <= '2021-12-31' 
                    AND id_grupo_preguntas <> 40 AND respuesta_user <> '' AND centro='$centro'
                    ) AS s 
                    ) AS b 
                    GROUP BY  
                    b.tema,
                    b.id_preguntas,
                    b.preguntas,
                    b.centro,
                    b.empresa,
                    b.mes,
                    b.bk";			
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
    }        
?>