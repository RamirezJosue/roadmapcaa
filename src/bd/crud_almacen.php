<?php 
	require_once('banco.php');
	class CrudAlmacen{
		public function __construct(){}
		public function InsertarSorting($centro,$Fecha,$id_usuario,$Nombre_Clasif,$Turno,$Ruta,$TREV,$TENV,$CJ,$Inicio){
			$db=DB::conectar();
			$insert=$db->prepare('INSERT INTO `KPI_Sorting`
									(`id`, `centro`, `Fecha`, `id_usuario`, `Nombre_Clasif`, `Turno`, `Ruta`, `TREV`, `TENV`, `CJ`, `Inicio`, `Final`) VALUES 
									(null,:centro,:Fecha,:id_usuario,:Nombre_Clasif,:Turno,:Ruta,:TREV,:TENV,:CJ,:Inicio, null)');
			$insert->bindValue('centro',$centro);
			$insert->bindValue('Fecha',$Fecha);
			$insert->bindValue('id_usuario',$id_usuario);
			$insert->bindValue('Nombre_Clasif',$Nombre_Clasif);
			$insert->bindValue('Turno',$Turno);
			$insert->bindValue('Ruta',$Ruta);
			$insert->bindValue('TREV',$TREV);
			$insert->bindValue('TENV',$TENV);
			$insert->bindValue('CJ',$CJ);
			$insert->bindValue('Inicio',$Inicio);
			$insert->execute();
			$lastInsertId = $db->lastInsertId();
			  if($lastInsertId>0){
				echo 'Se registro !';
			  }else{ 
				echo 'No se pudo registrar';
			  } 
			Db::desconectar();
		}
		public function ModificarSorting($id,$centro,$id_usuario,$fecha_fin,$difhoras,$difhms){
			$db=DB::conectar();
			$insert=$db->prepare('
			UPDATE `KPI_Sorting` SET `Final`=:Final,`dif1`=:dif1 ,`dif2`=:dif2 WHERE `id`=:id AND `centro`=:centro
			');
			$insert->bindValue('id',$id);
			$insert->bindValue('centro',$centro);
			$insert->bindValue('Final',$fecha_fin);
			$insert->bindValue('dif1',$difhoras);
			$insert->bindValue('dif2',$difhms);
			$insert->execute();
			Db::desconectar();
		}
		public function ModificarPickingBeginning($Transporte,$Centro,$Pickinero,$Responsable,$Inicio){
			$db=DB::conectar();
			$insert=$db->prepare('
			UPDATE `KPI_Picking` SET `Responsable`=:Responsable,`Pickinero`=:Pickinero,`Inicio`=:Inicio  
			WHERE `Transporte`=:Transporte AND Centro=:Centro
			');
			$insert->bindValue('Transporte',$Transporte);
			$insert->bindValue('Centro',$Centro);
			$insert->bindValue('Responsable',$Responsable);
			$insert->bindValue('Pickinero',$Pickinero);
			$insert->bindValue('Inicio',$Inicio);
			$insert->execute();
			Db::desconectar();
		}
		public function ModificarPickingEndup($Transporte,$Centro,$Fin){
			$db=DB::conectar();
			$insert=$db->prepare('
			UPDATE `KPI_Picking` SET `Fin`=:Fin  
			WHERE `Transporte`=:Transporte AND Centro=:Centro
			');
			$insert->bindValue('Transporte',$Transporte);
			$insert->bindValue('Centro',$Centro);
			$insert->bindValue('Fin',$Fin);
			$insert->execute();
			Db::desconectar();
		}				
		public function Eliminar($id,$centro,$ruta){
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