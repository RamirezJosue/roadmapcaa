<?php 
	session_start();
if (isset($_COOKIE['usuarioid'])) {
                // Restaura sesion
                header( "Location: modules/hoja_ruta/hojaruta" );
                exit();
            } else {
                header( "Location: modules/logout" );
                exit();
			}	
?>
