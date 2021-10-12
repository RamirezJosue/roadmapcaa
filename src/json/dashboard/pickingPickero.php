<?php
                $WhereAnd = '';
                if(isset($_GET['centro'])){
                    $Centro = $_GET['centro'];
                    $WhereAnd .= " AND Centro =  '$Centro' ";
                } else {
                    $WhereAnd .= " AND Centro = 'BK77'";
                }
                if(isset($_GET['fecha'])){
                    $Fecha = $_GET['fecha'];
                    $WhereAnd .= " AND Fecha = '$Fecha' ";
                } else {
                    date_default_timezone_set("America/Lima");
                    $Fecha = date("Y-m-d",$time = time());	
                    $WhereAnd .= " AND Fecha = '$Fecha' ";
                }
        include '../../bd/banco.php';
			$db=Db::conectar();
			$db->query("SET NAMES 'UTF8' ");
		    $sql ="SELECT 
            IFNULL((SELECT apellidos FROM usuarios WHERE dni=a.Pickinero),a.Pickinero) as Pickinero,
            SUM(`Cajas_Picking`) AS Cajas,
            SUM(`TiempHoras`) AS Horas
            FROM (
            SELECT 
            `Centro`, `Fecha`, `Placa`, `Ruta`, `Viaje`, `Cajas_Picking`, `Unidades_Picking`, `Pickinero`, `Inicio`, `Fin`,
            TIMESTAMPDIFF(MINUTE, `Inicio`, `Fin` )/60  as TiempHoras   
            FROM `KPI_Picking` WHERE `Fin` IS NOT NULL 
            $WhereAnd 
                ) AS a 
            GROUP BY
            Pickinero";			
            $select=$db->prepare($sql);
		    $select->execute();
            if (!$select){
                echo 'Error al ejecutar la consulta';
            }else{

                $arr = $select->fetchAll(PDO::FETCH_ASSOC);
                foreach ($arr as $row) {
                    $Pickinero[]  = $row['Pickinero'];
                    $Cajas[] = round(floatval($row['Cajas']));
                    $Horas[] = round(floatval($row['Horas']),2);
                 }
                $arreglo =  [
                            "Pickinero" => isset($Pickinero) ? $Pickinero : array(),
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