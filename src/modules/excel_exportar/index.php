<?php 
			session_start();
			if (!isset($_SESSION['rmd'])){
                // Restaura sesion
                header( "Location: lista" );
            } else {
				header( "Location: ../../index?mensaje=no%20tienes%20permisos%20ó%20tu%20session%20a%20caduado" );
			}				
?>