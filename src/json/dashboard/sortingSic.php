<?php
                $WhereAnd = '';
                if(isset($_GET['centro'])){
                    $centro = $_GET['centro'];
                    $WhereAnd .= " WHERE centro =  '$centro' ";
                } else {
                    $WhereAnd .= " WHERE centro = 'BK77'";
                }
                if(isset($_GET['fecha'])){
                    $Fecha = $_GET['fecha'];
                    $WhereAnd .= " AND Fecha = '$Fecha' ";
                } else {
                    date_default_timezone_set("America/Lima");
                    $fecha_actual = date("Y-m-d",$time = time());	
                    $WhereAnd .= " AND Fecha = '$fecha_actual' ";
                }
                $sql ="SELECT 
              t.sic_hr,
              r.sic_hr as sic_hr_td,
              r.AcuCjas
              FROM 
              (
                SELECT  `sic_hr` FROM `KPI_Sorting_horas` WHERE centro='BK77'
              ) AS t LEFT JOIN 
              ( SELECT 
                z.`centro`,
                z.`Fecha`,
                z.sic_hr,
                SUM(z.`CJ`) as cajas,
                /*inicio suma acumulada*/
                (SELECT SUM(e.`CJ`)
                FROM (
                SELECT
                CASE
                    WHEN TIME(`Final`) <= '07:15:00' THEN '07:15'
                    WHEN TIME(`Final`) <= '09:00:00' THEN '09:00'
                    WHEN TIME(`Final`) <= '11:00:00' THEN '11:00'
                    WHEN TIME(`Final`) <= '13:00:00' THEN '13:00'
                    WHEN TIME(`Final`) <= '15:00:00' THEN '15:00'
                    ELSE 'MAS..'
                END AS sic_hr,
                `CJ`
                FROM KPI_Sorting $WhereAnd 
                ) AS e 
                WHERE e.sic_hr <= z.sic_hr
                ) as AcuCjas
                /*fin suma acumulada*/
                FROM 
                (
                SELECT 
                `centro`,
                `Fecha`,
                CASE
                   WHEN TIME(`Final`) <= '07:15:00' THEN '07:15'
                    WHEN TIME(`Final`) <= '09:00:00' THEN '09:00'
                    WHEN TIME(`Final`) <= '11:00:00' THEN '11:00'
                    WHEN TIME(`Final`) <= '13:00:00' THEN '13:00'
                    WHEN TIME(`Final`) <= '15:00:00' THEN '15:00'
                    ELSE 'MAS..'
                END AS sic_hr,
                `CJ`, 
                `Final`
                FROM `KPI_Sorting` $WhereAnd 
                ) AS z GROUP BY 
                z.`centro`,
                z.`Fecha`,
                z.sic_hr
              ) AS r ON t.sic_hr = r.sic_hr  ORDER BY t.sic_hr ASC";
        include '../../bd/banco.php';
			$db=Db::conectar();
			$db->query("SET NAMES 'UTF8' ");			
            $select=$db->prepare($sql);
		    $select->execute();
            if (!$select){
                echo 'Error al ejecutar la consulta';
            }else{  
                while ($row=$select->fetch()){	          
                    $sic_hr[]  = $row["sic_hr"];
                    $AcuCjas[] = floatval($row["AcuCjas"]);
                 }   
                $arreglo2 =  [
                            "etiquetas" => $sic_hr,
                            "datos" => $AcuCjas,
                            ];
            }
            if(!empty($arreglo2)){
                header('Access-Control-Allow-Origin: *');
                header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
                header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
                header("Allow: GET, POST, OPTIONS, PUT, DELETE");
                header('Content-Type: application/json');
                $method = $_SERVER['REQUEST_METHOD'];
                if($method == "OPTIONS") {
                    die();
                }
                echo json_encode($arreglo2, JSON_UNESCAPED_UNICODE);
            }else{
            echo 'error';
            }
?>