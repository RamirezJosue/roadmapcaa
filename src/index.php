<?php
session_start();
if (isset($_SESSION["rmd"])) {
                // Restaura sesion
                header( "Location: modules/index" );
            } else {
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>:: RoadMap Delivery</title>
	<link rel="shortcut icon" href="img/roadmap.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://bootswatch.com/4/lux/bootstrap.min.css">
	<style>
	html,
body {
  height: 100%;
}

body {
  display: -ms-flexbox;
  display: -webkit-box;
  display: flex;
  -ms-flex-align: center;
  -ms-flex-pack: center;
  -webkit-box-align: center;
  align-items: center;
  -webkit-box-pack: center;
  justify-content: center;
  padding-top: 40px;
  padding-bottom: 40px;
  background-color: #f5f5f5;
}

.form-signin {
  width: 100%;
  max-width: 330px;
  padding: 15px;
  margin: 0 auto;
}
.form-signin .checkbox {
  font-weight: 400;
}
.form-signin .form-control {
  position: relative;
  box-sizing: border-box;
  height: auto;
  padding: 10px;
  font-size: 16px;
}
.form-signin .form-control:focus {
  z-index: 2;
}
.form-signin input[type="email"] {
  margin-bottom: -1px;
  border-bottom-right-radius: 0;
  border-bottom-left-radius: 0;
}
.form-signin input[type="password"] {
  margin-bottom: 10px;
  border-top-left-radius: 0;
  border-top-right-radius: 0;
}
  </style>	
  </head>
  <body class="text-center">
    <form class="form-signin" action="controller_login" method="post" >
	  <?php
		if (isset($_GET['mensaje'])){
	echo   '<div class="alert alert-danger" role="alert">
				<strong>'.$_GET['mensaje'].'</strong>
			</div>';	
		}
      ?>
      <img class="mb-4" src="img/logo.png" alt="" width="150" height="150">
      <h1 class="h3 mb-3 font-weight-normal">Bienvenido</h1>
      <label for="dni" class="sr-only">Usuario</label>
      <input type="text" id="dni" class="form-control" name="dni" placeholder="Usuario" required autofocus>
      <label for="Contraseña" class="sr-only">Contraseña</label>
      <input type="password" id="Contraseña" class="form-control" placeholder="Contraseña" name="clave" required>
	  <input type="hidden" name="entrar" value="entrar">
      <div class="checkbox mb-3">
      </div>
      <button class="btn btn-lg btn-danger btn-block" type="submit">Iniciar</button>
     <p class="mt-5 mb-3 text-muted">CD Juliaca - 2021</p>
    </form>
  </body>
</html>
<?php 
			}	
?>
