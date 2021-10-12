<?php
ob_start();
$accesos = basename(dirname(__FILE__));
require_once('../../includes/ini.php');
require_once('../../bd/crud_usuario.php');
$crud = new CrudUsuario();
if ($usuarioestado == 0) {
	echo $html_bloqueo;
} else {
	$arraruser = explode(',', $usuarioaccesos);
	if (in_array($accesos, $arraruser)) {
		if ($usuariotipo == 0) : $aid_super = 0;
		else : $aid_super = 1;
		endif;
		/*inicio vefifia si tiene permisos de adminrepartos */
		if (in_array("adminrepartos", $arraruser)) : $adminrepartos = 1;
		else : $adminrepartos = 0;
		endif;
		if ($aid_super == 1 || $adminrepartos == 1) : $disableform = '';
		else : $disableform = 'disabled';
		endif;
		/*fin vefifia si tiene permisos de adminrepartos */
		$bootstrapjs =  1;
		$mapasjs =  0;
		$datatablesjs = 1;
		require('../head.php');
		if (isset($_GET['fechaselec'])) :
			$fecha_form = $_GET['fechaselec'];
			$fechars = $_GET['fechaselec'];
		else :
			$fechars = $fechars;
			$fecha_form = $fecha;
		endif;
		if (isset($_GET['hc'])) {
			$hc = $_GET['hc'];
		} else {
			$hc = "";
		}
		$arraruserexcluye = array('..', '.');
		$dir = '../../modules';
		$directorio = opendir($dir);
		$carpetas_mod = array();
		while ($f = readdir($directorio)) {
			if (is_dir("$dir/$f") && !in_array($f, $arraruserexcluye)) {
				$carpetas_mod_arr[] = $f;
			} else {
			}
		}
		closedir($directorio);
		$carpetas_mod = implode(",", $carpetas_mod_arr);
        function eliminar_rol_permiso($idrol)
		{
				$db=Db::conectar();
				$select=$db->prepare("DELETE FROM `usuarios_roles_permisos` WHERE `id_rol`=:id_rol");
				$select->bindValue('id_rol',$idrol); 
				$select->execute(); 
				Db::desconectar();
		}


		switch ($hc):
			case "modificar":
				break;
			case "CambiarPassword":
				break;
			case "CambiarAccesos":
				break;
			case "EstadoUser":
				break;
			case "Covid":
				break;
			case "ModificaParametrosUser":
				break;
			case "InsertarParametrosUser":
				break;
			case "InicioParametrosUser":
				break;
			case "listarcovid":
				break;
			case "listaconductorescds":
				break;
			case "agregar_rol":
				break;
			default:
			    echo '<div class="alert alert-warning" role="alert">Para que los cambios se apliquen, el usuario deve de cerrar la cession y ingresar nuevamente...</div>';
			    if (($aid == 41960277) || ($aid == 2157339)) {
					$disabledWM = '';
				} else {
                    $disabledWM = 'disabled';
				}	
				if (isset($_POST['descripcionRol']) && $disabledWM == '') {
					$descripcionRol = $_POST['descripcionRol'];
					$db = Db::conectar();
					$select = $db->prepare("INSERT INTO `usuarios_roles` (`id`, `descripcion`) VALUES (null,'$descripcionRol')");
					$select->execute();
					Db::desconectar();
					$lastInsertId = $db->lastInsertId();
					if($lastInsertId>0){
					 echo '<div class="alert alert-success" role="alert">Se registro el nuevo rol</div>';	   
					}else{ 
					 echo '<div class="alert alert-danger" role="alert">no se pudo registrar el nuevo rol</div>';
					}
				} else { $descripcionRol = ''; }
				if (isset($_POST['checkRol']) && is_array($_POST['checkRol']) && isset($_GET['idrl']) && $disabledWM == '') {
					eliminar_rol_permiso($_GET['idrl']);
					$db = Db::conectar();
					foreach ($_POST['checkRol'] as $id_modulo => $id_rol) {
						$select = $db->prepare("INSERT INTO `usuarios_roles_permisos`(`id_rol`, `id_modulo`) VALUES ('$id_rol','$id_modulo')");
						$select->execute();
					}
					Db::desconectar();
				}
				$dbUR = Db::conectar();
				$selecUR = $dbUR->prepare("SELECT * FROM `usuarios_roles`");
				$selecUR->execute();
			echo '<div class="card mb-3">
          			<div class="card-header text-white bg-dark">Roles Registrados</div>
          				<div class="card-body">';
					echo  '<form method="POST" class="form-inline">';
						echo  '<div class="form-group">
          						  <label for="rol">Nombre del Rol : </label>&nbsp;&nbsp; 
          						  <input type="text" class="form-control form-control-sm" id="rol" name="descripcionRol" value="' . $descripcionRol . '" required>&nbsp;&nbsp;    
        						  <button type="submit" class="btn btn-danger btn-sm" '.$disabledWM.'>Agregar</button>
          					   </div>
						   </form><br>';
									echo '<h5>Seleccionar el rol a editar</h5>';
									echo '<div class="card-columns">';
				while ($rowUR = $selecUR->fetch(PDO::FETCH_ASSOC)) {
					echo '<div class="form-check">';
					echo '<a href="roles?idrl=' . $rowUR['id'] . '&nbrl='. $rowUR['descripcion'] .'" class="btn btn-link btn-sm" >' . $rowUR['descripcion'] . '</a>';
					echo '</div>';
				}
									echo '</div>';
				echo '</div>
         		 </div>';
		    if(isset($_GET['idrl'],$_GET['nbrl'])){
				$idrl = $_GET['idrl'];
                $nbrl = $_GET['nbrl'];				
				$dbR = Db::conectar();
				$selectR = $dbR->prepare("SELECT * FROM (SELECT * FROM `usuarios_modulos`) AS m left join (SELECT * FROM `usuarios_roles_permisos` WHERE id_rol = '$idrl' ) AS p ON m.id = p.id_modulo ORDER BY m.nombre ASC");
				$selectR->execute();
				echo '<div class="card">
            			<div class="card-header text-white bg-dark">Modificar y Agregar Permisos a Roles : ' . $nbrl . '</div>
                			<div class="card-body">
                    			<form method="POST">';
							echo '<div class="card-columns">';
									while ($rowR = $selectR->fetch(PDO::FETCH_ASSOC)) {
										$rowID = $rowR['id'];
										if ($rowR['id_modulo']) {
											$checked = 'checked';
										} else {
											$checked = '';
										}
									 echo '<div class="form-check">
											<input class="form-check-input" type="checkbox" ' . $checked . ' name="checkRol[' . $rowID . ']" id="inlineCheckbox1" value="' . $idrl . '" '.$disabledWM.'>
											<label class="form-check-label" for="inlineRadio1">' . $rowR['nombre'] . '</label>
										</div>';
									}
						echo '</div>';
						echo '<button type="submit" '.$disabledWM.' class="btn btn-danger btn-sm">Agregar</button>';
						echo '</form>
            			</div>
        			</div>';
				Db::desconectar();
			}

		endswitch;
	} else {
		echo $html_acceso;
	}
}
require('../footer.php');
ob_end_flush();
?>