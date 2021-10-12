<?php 
			session_start();
			if (isset($_COOKIE['usuarioid'])) {
                // Restaura sesion
                header( "Location: ../index" );
            } else {
				header( "Location: ../index?mensaje=no%20tienes%20permisos%20ó%20tu%20session%20a%20caduado" );
			}				
?>