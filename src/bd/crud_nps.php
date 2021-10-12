<?php 
	require_once('banco.php');
	class CrudNPS{
		public function __construct(){}
		public function insertarNPS($checklist,$pregunta,$subpregunta,$respuestas,$aid,$codigocliente,$fecharegistro,$centro,$grupo){
			$db=DB::conectar();
			$insert=$db->prepare('
			INSERT INTO `exa_array`(`id`, `checklist`, `pregunta`, `subpregunta`, `respuestas`, `aid`, `codigocliente`, `fecharegistro`, `centro`, `grupo`) 
			VALUES (null,:checklist,:pregunta,:subpregunta,:respuestas,:aid,:codigocliente,:fecharegistro,:centro,:grupo)
								 ');
			$insert->bindValue('checklist',$checklist);
			$insert->bindValue('pregunta',$pregunta);
			$insert->bindValue('subpregunta',$subpregunta);
			$insert->bindValue('respuestas',$respuestas);
			$insert->bindValue('aid',$aid);
			$insert->bindValue('codigocliente',$codigocliente);
			$insert->bindValue('fecharegistro',$fecharegistro);
			$insert->bindValue('centro',$centro);
			$insert->bindValue('grupo',$grupo);
			$insert->execute();
			Db::desconectar();
		}
		public function modificarNPS($id,$dni,$newclave,$centro){
			$db=DB::conectar();
			$insert=$db->prepare('UPDATE `usuarios` SET `clave`=:clave WHERE Id=:Id AND dni=:dni AND centro=:centro');
			//encripta la clave
			//$clave=password_hash($newclave,PASSWORD_DEFAULT);
			$insert->bindValue('clave',$newclave);
			$insert->bindValue('Id',$id);
			$insert->bindValue('dni',$dni);
			$insert->bindValue('centro',$centro);
			$insert->execute();
			Db::desconectar();
		}
		public function eliminarNPS($id,$centro,$ruta){
          $db=Db::conectar();
          $select=$db->prepare("DELETE FROM `t77_em` WHERE `id`=:id AND `centro`=:centro AND `ruta`=:ruta");
		  $select->bindValue('id',$id); 
		  $select->bindValue('centro',$centro); 
		  $select->bindValue('ruta',$ruta); 
		  $select->execute(); 
		  Db::desconectar();
		}		
	}
?>