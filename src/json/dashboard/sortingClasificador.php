<?php
                $WhereAnd = '';
            if(isset($_GET['centro'])){
                $centro = $_GET['centro'];
                $WhereAnd .= " AND centro =  '$centro' ";
            } else {
                $WhereAnd .= " AND centro = 'BK77'";
            }
            if(isset($_GET['fecha'])){
                $Fecha = $_GET['fecha'];
                $WhereAnd .= " AND Fecha = '$Fecha' ";
            } else {
                date_default_timezone_set("America/Lima");
                $Fecha = date("Y-m-d",$time = time());	
                $WhereAnd .= " AND Fecha >= '$Fecha' ";
            }
           include '../../bd/banco.php';
			$db=Db::conectar();
			$db->query("SET NAMES 'UTF8' ");
		    $sql ="
            SELECT 
            (SELECT apellidos FROM usuarios WHERE dni=a.id_usuario) as clasificador,
            SUM(a.CJ) as Cajas,
            SUM(a.TiempHoras) AS Horas
         FROM(
          SELECT  `id_usuario`, `CJ`, Inicio, Final , TIMESTAMPDIFF(MINUTE, Inicio, Final )/60  as TiempHoras  
          FROM `KPI_Sorting` WHERE Final IS NOT NULL   
          $WhereAnd  
            ) AS a GROUP BY 
             a.id_usuario                   
            ";			
            $select=$db->prepare($sql);
		    $select->execute();
            if (!$select){
                echo 'Error al ejecutar la consulta';
            }else{
                $arr = $select->fetchAll(PDO::FETCH_ASSOC);
                foreach ($arr as $row) {
                    $clasificador[]  = $row['clasificador'];
                    $Cajas[] = round(floatval($row['Cajas']));
                    $Horas[] = round(floatval($row['Horas']),2);
                 }
                $arreglo =  [
                            "clasificador" => isset($clasificador) ? $clasificador : array(),
                            "Cajas" => isset($Cajas) ? $Cajas : array(),
                            "Horas" => isset($Horas) ? $Horas : array(),                       
                            ];
            }
            if(!empty($arreglo)){
                header("Access-Control-Allow-Origin: *");//this allows cors
                header('Content-Type: application/json');
                print json_encode($arreglo, JSON_UNESCAPED_UNICODE);
            }else{
            echo 'error';
            }  
?>