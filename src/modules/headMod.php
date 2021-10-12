<!doctype html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Jekyll v4.1.1">
	<meta name="theme-color" content="#971B1E">
    <title>:: RoadMap Delivery</title>
	<link rel="shortcut icon" href="../img/roadmap.ico" type="image/x-icon">
    <!-- Bootstrap core 
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous"> 
	CSS -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link href="../css/bootstrap-slider.css" rel="stylesheet"> 
   <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
      }
      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
    </style>
    <style> 
  	#map {
        height: 100%;
        }
        html, body {
        height: 100%;
        margin: 0;
        padding: 0;
        }
	</style> 
	<style>
    .loading {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: #971B1E;
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        transition: 1s all;
        opacity: 0;
    }
    .loading.show {
        opacity: 1;
    }
    .loading .spin {
        border: 3px solid hsla(185, 100%, 62%, 0.2);
        border-top-color: #3cefff;
        border-radius: 50%;
        width: 3em;
        height: 3em;
        animation: spin 1s linear infinite;
    }
    @keyframes spin {
      to {
        transform: rotate(360deg);
      }
    }            
	</style>
	<style>
		.navbar-nav li:hover > ul.dropdown-menu {
		display: block;
	}
	.dropdown-submenu {
		position:relative;
	}
	.dropdown-submenu>.dropdown-menu {
		top: 0;
		left: 100%;
		margin-top:-6px;
	}

	/* rotate caret on hover */
	.dropdown-menu > li > a:hover:after {
		text-decoration: underline;
		transform: rotate(-90deg);
	} 
	</style
    <!-- Custom styles for this template -->
    <link href="../css/navbar-top-fixed.css" rel="stylesheet">
	<link href="../css/wsflotante.css" rel="stylesheet">
	</head>
	<body>
    <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
	<a class="navbar-brand" href="<?php echo $sitio; ?>"><?php echo $usuarionombre.' '.$usuarioapellidos; ?></a>
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
	</button>
	<div class="collapse navbar-collapse" id="navbarCollapse">
    <ul class="navbar-nav mr-auto">	  
	  		<?php
		require_once('../bd/array/url.php');;;;	
		$dir = '../modules';
		$directorio = opendir($dir);
		$carpetas = array();
		while ($f = readdir($directorio)) {
		if (is_dir("$dir/$f") && in_array($f, $arraruser)) {
        $carpetas[$f] = $f;
		} else { }
		}
		closedir($directorio);
		foreach ($UrlMenu as $menu => $submenu) {
			if(isset($carpetas[$menu]) && $carpetas[$menu]==$menu) {
		 echo '<li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarScrollingDropdown" role="button" data-toggle="dropdown" aria-expanded="false">
              '.ucwords(str_replace("_", " ", $menu)).'
		      </a>';
			echo '<ul class="dropdown-menu" aria-labelledby="navbarScrollingDropdown">';		
			foreach ($submenu as $valor => $link) {
				if ($valor=='-'){	
				echo '<li><hr class="dropdown-divider"></li>';
				} else {
					if(is_array($link)){
				echo '<li class="dropdown-submenu"><a class="dropdown-item dropdown-toggle" href="#">'.ucwords(str_replace("_", " ", $valor)).'</a>';
				echo '<ul class="dropdown-menu">';
						foreach ($link as $sbvalor => $sblink){
                            if ($sbvalor=='-'){	
                                echo '<li><hr class="dropdown-divider"></li>';
                                } else {
							if(is_array($sblink)){
					echo '<li class="dropdown-submenu"><a class="dropdown-item dropdown-toggle" href="#">'.$sbvalor.'</a>';
					echo '<ul class="dropdown-menu">';
							foreach ($sblink as $ssbvalor => $ssblink){
						echo '<li><a class="dropdown-item" href="'.$sitio.'modules/'.$ssblink.'">'.$ssbvalor.'</a></li>';			
							}
					echo '</ul>';
					echo '</li>';			
							}else{
					echo '<li><a class="dropdown-item" href="'.$sitio.'modules/'.$sblink.'">'.$sbvalor.'</a></li>';		
							}
                        }
						}
				echo '</ul>';
                echo '</li>';	
					}else{
					 echo '<li><a class="dropdown-item" href="'.$sitio.'modules/'.$link.'">'.ucwords(str_replace("_", " ", $valor)).'</a></li>';	
					}
				}
				}
		 echo '</ul>';		
		 echo '</li>';				
			}
		}
		?>	  
      <li class="nav-item">
        <a class="nav-link" href="<?php echo $sitio; ?>logout">Cerrar sesión</a>
      </li>
    </ul>
	</div>
	</nav>
	<main role="main" class="container-sm">