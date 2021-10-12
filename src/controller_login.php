<?php 
     session_start();
	if (!isset($_SESSION['rmd'])){
		header('Location: index?mensaje=no existe session controler login');
	}
	$arrayrmd	= 	$_SESSION["rmd"];
	$aid 		=   $arrayrmd['dni'];
	$idcentro 	= 	$arrayrmd['centro'];
	require_once('bd/crud_usuario.php');
	$crud=new CrudUsuario();
	require_once('bd/banco.php');
	date_default_timezone_set("America/Lima");	
    $fecha_hora = date("Y-m-d H:i:s",$time = time());
	if (isset($_POST['registrarse'])) {
	$nombre = $_POST['nombre'];
	$clave = $_POST['clave'];
	$apellidos = $_POST['apellidos'];
	$dni = $_POST['dni'];
	$tipo = 0;
	$accesos = $_POST['accesos'];
	$estado = 1;
	$empresa = $_POST['empresa'];
	$puesto = $_POST['puesto'];
	$area = $_POST['area'];
	$division = $_POST['division'];
	$supervisor = $_POST['supervisor'];
	$domicilio = $_POST['domicilio'];
	$telefono = $_POST['telefono'];
	$email = $_POST['email'];
	$genero = $_POST['genero'];
	$brevete = $_POST['brevete'];
	$catbrevete = $_POST['catbrevete'];
	$date_brevete = $_POST['date_brevete'];
	$pin = $_POST['pin'];
	$img = 'defaul.jpeg';		
		if ($crud->buscarUsuario($_POST['dni'])) {
			$crud->insertar($nombre, $clave, $apellidos, $dni, $idcentro, $tipo, $accesos, $estado, $empresa, $puesto,$area, $division, $supervisor, $domicilio, $telefono, $email, $genero, $brevete, $catbrevete,$date_brevete, $fecha_hora, $pin, $img, $aid);
			header('Location: modules/admin/userhc?puesto='.$puesto.'');
			die();
		}else{
			header('Location: modules/admin/userhc?mensaje=El nombre de usuario ya existe');
			die();
		}		
	}elseif (isset($_POST['entrar'],$_POST['dni'],$_POST['clave'])) { //verifica si la variable entrar está definida
		$usuario=$crud->obtenerUsuario($_POST['dni'],$_POST['clave']);
		if ($usuario != NULL) {
			session_start();
            $_SESSION["rmd"] = $usuario;
			header('Location: modules/index'); //envia a la página que simula la cuenta
			die();
		}else{
		  	header('Location: index?mensaje=Tus nombre de usuario o clave son incorrectos'); // cuando los datos son incorrectos envia a la página de error
			die();  
		}
	}elseif(isset($_POST['salir'])){ // cuando presiona el botòn salir
		header('Location: index');
			die();		
		unset($_SESSION['usuario.php']); //destruye la sesión
	}
?>