<?php
                    $WhereAnd = '';
                if(isset($_GET['centro'])){
                    $Centro = $_GET['centro'];
                    $WhereAnd .= " AND Centro =  '$Centro' ";
                } else {
                    $WhereAnd .= " AND Centro = 'BK77' ";
                }
                if(isset($_GET['fecha'])){
                    $Fecha = $_GET['fecha'];
                    $WhereAnd .= " AND Fecha = '$Fecha' ";
                } else {
                    date_default_timezone_set("America/Lima");
                    $Fecha = date("Y-m-d",$time = time());	
                    $WhereAnd .= " AND Fecha = '$Fecha' ";
                }
                $sql ="SELECT 
                z.sic_hr,
                r.sic_hr as sic_hr_td,
                r.NrTrpsVj1Fin,
               ( SELECT COUNT(id) FROM `KPI_Picking` WHERE Viaje = 1 $WhereAnd  ) AS NroTrps,
                r.AcuTrptFin 
               FROM 
               (
               SELECT `sic_hr` FROM `KPI_Picking_horas` WHERE Centro='BK77'
               ) AS z LEFT JOIN (
               SELECT
                   z.sic_hr,
                   SUM(z.Contador) AS NrTrpsVj1Fin,
               (
                   SELECT
                       SUM(e.NrTrsFin)
                   FROM
                       (
                       SELECT
                           `Transporte`,
                           `Fin`,
                           1 AS NrTrsFin,
                           CASE WHEN TIME(`Fin`) <= '00:50:00' THEN '00:50' WHEN TIME(`Fin`) <= '01:50:00' THEN '01:50' WHEN TIME(`Fin`) <= '02:50:00' THEN '02:50' WHEN TIME(`Fin`) <= '03:50:00' THEN '03:50' WHEN TIME(`Fin`) <= '04:50:00' THEN '04:50' ELSE 'MAS..'
                   END AS sic_hr
               FROM
                   `KPI_Picking`
               WHERE
                    Fin IS NOT NULL AND Viaje = 1 $WhereAnd 
               ) AS e
               WHERE
                   e.sic_hr <= z.sic_hr
               ) AS AcuTrptFin
               FROM
                   (
                   SELECT
                       `Transporte`,
                       `Fin`,
                       1 AS `Contador`,
                       CASE WHEN TIME(`Fin`) <= '00:50:00' THEN '00:50' WHEN TIME(`Fin`) <= '01:50:00' THEN '01:50' WHEN TIME(`Fin`) <= '02:50:00' THEN '02:50' WHEN TIME(`Fin`) <= '03:50:00' THEN '03:50' WHEN TIME(`Fin`) <= '04:50:00' THEN '04:50' ELSE 'MAS..'
               END AS sic_hr
               FROM
                   `KPI_Picking`
               WHERE
                   Viaje = 1 AND Fin IS NOT NULL $WhereAnd
               ) AS z
               GROUP BY
                   z.sic_hr
                   ) AS r ON z.sic_hr = r.sic_hr ORDER BY z.sic_hr ASC"; 
            include '../../bd/banco.php';
			$db=Db::conectar();
			$db->query("SET NAMES 'UTF8' ");			
            $select=$db->prepare($sql);
		    $select->execute();
            if (!$select){
                echo 'Error al ejecutar la consulta';
            }else{
                $arr = $select->fetchAll(PDO::FETCH_ASSOC);
                foreach ($arr as $row) {
                    $sic_hr[]  = $row['sic_hr'];
                    $AcuTrptFin[] = floatval($row['AcuTrptFin']);
                    $NroTrps[] = floatval($row['NroTrps']);
                 }
                $arreglo =  [
                            "sic_hr" => isset($sic_hr) ?  $sic_hr : array(),
                            "AcuTrptFin" =>  isset($AcuTrptFin) ? $AcuTrptFin : array(),
                            "NroTrps" =>  isset($NroTrps) ? $NroTrps : array(),
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