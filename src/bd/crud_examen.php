<?php 
	require_once('banco.php');
	class CrudExamen{
		public function __construct(){}
		//inserta los datos del usuario
		public function sacarmonbredb($tb,$dbt,$id){
			$db=Db::conectar();
			$select=$db->prepare('SELECT '.$tb.' FROM '.$dbt.' WHERE '.$id.'');
			$select->execute();
			while ($registro=$select->fetch()){
			return $registro;
            }
            Db::desconectar();				
		}
		public function contardb($tb,$dbt,$id){
			$db=Db::conectar();
			$select=$db->prepare('SELECT '.$tb.' FROM '.$dbt.' WHERE '.$id.'');
			$select->execute();	
			$cuenta_col = $select->rowCount();				
			return $cuenta_col;
			Db::desconectar();
		}
		
		public function insertar_tema($centro,$descripcion,$fecha,$fecha_fin,$tipo,$para,$id_user,$id_area){
			$db=DB::conectar();
			$insert=$db->prepare('
			INSERT INTO `exa_temas`(`id`, `centro`, `descripcion`, `fecha`, `fecha_fin`, `tipo`, `estado`, `para`, `id_user`,`id_area`) 
		                VALUES (:id,:centro,:descripcion,:fecha,:fecha_fin,:tipo,:estado,:para,:id_user,:id_area)	
			');
			$insert->bindValue('id',mt_rand(111111111,999999999)); 
			$insert->bindValue('centro',$centro);
			$insert->bindValue('descripcion',$descripcion);
		    $insert->bindValue('fecha',$fecha);
			$insert->bindValue('fecha_fin',$fecha_fin);
			$insert->bindValue('tipo',$tipo);
			$insert->bindValue('estado',0);
			$insert->bindValue('para',$para);
			$insert->bindValue('id_user',$id_user);
			$insert->bindValue('id_area',$id_area);
			$insert->execute();
			Db::desconectar();
		}
		public function modifica_tema($id,$descripcion,$fecha_fin,$estado,$para,$id_area){
			$db=DB::conectar();
			$insert=$db->prepare('
		UPDATE `exa_temas` SET `descripcion`=:descripcion,`fecha_fin`=:fecha_fin,`estado`=:estado,`para`=:para,`id_area`=:id_area WHERE id=:id	
			');
			$insert->bindValue('id',$id);
			$insert->bindValue('descripcion',$descripcion);
			$insert->bindValue('fecha_fin',$fecha_fin);
			$insert->bindValue('estado',$estado);
			$insert->bindValue('para',$para);
			$insert->bindValue('id_area',$id_area);
			$insert->execute();
			Db::desconectar();
		}
		public function modifica_pregunta($id,$pregunta,$tipo_pregunta,$puntos,$centro,$user,$txt_actions,$doblecheck,$descripcion){
			$db=DB::conectar();
			$insert=$db->prepare('
        UPDATE `exa_preguntas` SET `pregunta`=:pregunta,`tipo_pregunta`=:tipo_pregunta,`puntos`=:puntos, txt_actions=:txt_actions, doblecheck=:doblecheck, descripcion=:descripcion WHERE id=:id AND `centro`=:centro AND `user`=:user	
			');
			$insert->bindValue('id',$id);
			$insert->bindValue('pregunta',$pregunta);
			$insert->bindValue('tipo_pregunta',$tipo_pregunta);
			$insert->bindValue('puntos',$puntos);
			$insert->bindValue('centro',$centro);
			$insert->bindValue('user',$user);
			$insert->bindValue('txt_actions',$txt_actions);
			$insert->bindValue('doblecheck',$doblecheck);
			$insert->bindValue('descripcion',$descripcion);
			$insert->execute();
			Db::desconectar();
		}	                                     
		public function modifica_grupo_preguntas($id,$pregunta,$centro,$user){
			$db=DB::conectar();
			$insert=$db->prepare('
		UPDATE `exa_grupo_preguntas` SET `descripcion`=:descripcion WHERE id = :id AND centro=:centro AND user=:user
			');
			$insert->bindValue('id',$id);
			$insert->bindValue('descripcion',$pregunta);
			$insert->bindValue('centro',$centro);
			$insert->bindValue('user',$user);
			$insert->execute();
			Db::desconectar();
		}		
		public function eliminar_tema($id,$idcentro,$id_user){
			$db=DB::conectar();
			$insert=$db->prepare('
		DELETE FROM `exa_temas` WHERE id = :id AND centro = :centro AND id_user = :id_user	
			');
			$insert->bindValue('id',$id);
			$insert->bindValue('centro',$idcentro);
			$insert->bindValue('id_user',$id_user);
			$insert->execute();
			Db::desconectar();
		}
		public function eliminar_respuestas($id,$idcentro,$id_user){
			$db=DB::conectar();
			$insert=$db->prepare('
		DELETE FROM `exa_respuesta` WHERE `id_pregunta`=:id_pregunta AND user=:user AND centro=:centro		
			');
			$insert->bindValue('id_pregunta',$id);
			$insert->bindValue('user',$id_user);			
			$insert->bindValue('centro',$idcentro);
			$insert->execute();
			Db::desconectar();
		}	
		public function eliminar_pregunta($id,$idcentro,$id_user){
			$db=DB::conectar();
			$insert=$db->prepare('
		DELETE FROM `exa_preguntas` WHERE id = :id AND centro = :centro AND user = :user 			
			');
			$insert->bindValue('id',$id);
			$insert->bindValue('user',$id_user);			
			$insert->bindValue('centro',$idcentro);
			$insert->execute();
			Db::desconectar();
		}	
		public function eliminar_grupo_preguntas($id,$idcentro,$id_user){
			$db=DB::conectar();
			$insert=$db->prepare('
		DELETE FROM `exa_grupo_preguntas` WHERE id = :id AND centro = :centro AND user = :user 			
			');
			$insert->bindValue('id',$id);
			$insert->bindValue('user',$id_user);			
			$insert->bindValue('centro',$idcentro);
			$insert->execute();
			Db::desconectar();
		}			
		public function insertar_grupo_preguntas($id_tema,$descripcion,$centro,$user){
			$db=DB::conectar();
			$insert=$db->prepare('
			INSERT INTO `exa_grupo_preguntas`(`id`, `id_tema`, `descripcion`, `centro`, `user`) 
			                          VALUES (null,:id_tema,:descripcion,:centro,:user)
			');
			$insert->bindValue('id_tema',$id_tema);
			$insert->bindValue('descripcion',$descripcion);
		    $insert->bindValue('centro',$centro);
			$insert->bindValue('user',$user);
			$insert->execute();
			Db::desconectar();	
		}
		public function insertar_pregunta($id_tema,$id_grupo_preguntas,$pregunta,$centro,$tipo_pregunta,$puntos,$user,$doblecheck,$txt_actions,$descripcion){
			$db=DB::conectar();
			$insert=$db->prepare('
			INSERT INTO `exa_preguntas`
			       (`id`, `id_tema`, `id_grupo_preguntas`, `pregunta`, `centro`, `tipo_pregunta`, `puntos`, `user`, `doblecheck`, `txt_actions`, `descripcion`) 
			VALUES (null,:id_tema,:id_grupo_preguntas,:pregunta,:centro,:tipo_pregunta,:puntos,:user,:doblecheck,:txt_actions,:descripcion)
			');
			$insert->bindValue('id_tema',$id_tema);
			$insert->bindValue('id_grupo_preguntas',$id_grupo_preguntas);
			$insert->bindValue('pregunta',$pregunta);
			$insert->bindValue('centro',$centro);
			$insert->bindValue('tipo_pregunta',$tipo_pregunta);
			$insert->bindValue('puntos',$puntos);
			$insert->bindValue('user',$user);
			$insert->bindValue('doblecheck',$doblecheck);
			$insert->bindValue('txt_actions',$txt_actions);
			$insert->bindValue('descripcion',$descripcion);
			$insert->execute();
			Db::desconectar();	
		}
		public function insertar_respuestas($id_tema,$id_grupo_preguntas,$id_pregunta,$tipo_pregunta,$orden,$respuestas,$res_correcta,$res_multiple,$user,$centro,$doblecheck,$txt_actions){
			$db=DB::conectar();
			$insert=$db->prepare('
			INSERT INTO `exa_respuesta`(`id`, `id_tema`, `id_grupo_preguntas`, `id_pregunta`, `tipo_pregunta`, `orden`, `respuestas`, `res_correcta`, `res_multiple` , `user`, `centro` ,doblecheck ,txt_actions)
			VALUES (null,:id_tema,:id_grupo_preguntas,:id_pregunta,:tipo_pregunta,:orden,:respuestas,:res_correcta,:res_multiple,:user,:centro,:doblecheck,:txt_actions)
			');
			$insert->bindValue('id_tema',$id_tema);
			$insert->bindValue('id_grupo_preguntas',$id_grupo_preguntas);
			$insert->bindValue('id_pregunta',$id_pregunta);
			$insert->bindValue('tipo_pregunta',$tipo_pregunta);
			$insert->bindValue('orden',$orden);
			$insert->bindValue('respuestas',$respuestas);
			$insert->bindValue('res_correcta',$res_correcta);
			$insert->bindValue('res_multiple',$res_correcta);			
			$insert->bindValue('user',$user);
			$insert->bindValue('centro',$centro);
			$insert->bindValue('doblecheck',$doblecheck);
			$insert->bindValue('txt_actions',$txt_actions);
			$insert->execute();
            Db::desconectar();			
		}
		public function insertar_respuestas_user($id_tema,$tipo_tema,$id_grupo_preguntas,$id_preguntas,$tipo_pregunta,$id_respueta,$desc_resp,$puntos_pregunta,$doble_check,$orden_resp,$resp_correcta,$respuesta_user,$user_creador,$centro,$fecha_ini_user,$fecha_fin_user,$user_registro,$placa,$grupo,$fecha){
			$db=DB::conectar();
			$insert=$db->prepare('
INSERT INTO `exa_detalle_user`(`id`, `id_tema`, `tipo_tema`, `id_grupo_preguntas`, `id_preguntas`, `tipo_pregunta`, `id_respueta`, `desc_resp`, `puntos_pregunta`, `doble_check`, `orden_resp`, `resp_correcta`, `respuesta_user`, `user_creador`, `centro`, `fecha_ini_user`, `fecha_fin_user`, `user_registro`, `placa`, `grupo`,`fecha`) 
                       VALUES (null,:id_tema,:tipo_tema,:id_grupo_preguntas,:id_preguntas,:tipo_pregunta,:id_respueta,:desc_resp,:puntos_pregunta,:doble_check,:orden_resp,:resp_correcta,:respuesta_user,:user_creador,:centro,:fecha_ini_user,:fecha_fin_user,:user_registro,:placa,:grupo,:fecha)
			');
			$insert->bindValue('id_tema',$id_tema);
			$insert->bindValue('tipo_tema',$tipo_tema);
			$insert->bindValue('id_grupo_preguntas',$id_grupo_preguntas);
			$insert->bindValue('id_preguntas',$id_preguntas);
			$insert->bindValue('tipo_pregunta',$tipo_pregunta);
			$insert->bindValue('id_respueta',$id_respueta);
			$insert->bindValue('desc_resp',$desc_resp);
			$insert->bindValue('puntos_pregunta',$puntos_pregunta);
			$insert->bindValue('doble_check',$doble_check);			
			$insert->bindValue('orden_resp',$orden_resp);
			$insert->bindValue('resp_correcta',$resp_correcta);
			$insert->bindValue('respuesta_user',$respuesta_user);
			$insert->bindValue('user_creador',$user_creador);
			$insert->bindValue('centro',$centro);
			$insert->bindValue('fecha_ini_user',$fecha_ini_user);
			$insert->bindValue('fecha_fin_user',$fecha_fin_user);
			$insert->bindValue('user_registro',$user_registro);
			$insert->bindValue('placa',$placa);
			$insert->bindValue('grupo',$grupo);
			$insert->bindValue('fecha',$fecha);			
			$insert->execute();
            Db::desconectar(); 			
			}
		public function insertar_respuestas_checklist($id_tema,$tipo_tema,$id_grupo_preguntas,$id_preguntas,$tipo_pregunta,$id_respueta,$desc_resp,$puntos_pregunta,$doble_check,$orden_resp,$resp_correcta,$respuesta_user,$user_creador,$centro,$fecha_ini_user,$fecha_fin_user,$user_registro,$placa,$grupo,$fecha,$txt_actions,$id_pregunta_grupo){
			$db=DB::conectar();
			$insert=$db->prepare('
INSERT INTO `exa_detalle_checklist`(`id`, `id_tema`, `tipo_tema`, `id_grupo_preguntas`, `id_preguntas`, `tipo_pregunta`, `id_respueta`, `desc_resp`, `puntos_pregunta`, `doble_check`, `orden_resp`, `resp_correcta`, `respuesta_user`, `user_creador`, `centro`, `fecha_ini_user`, `fecha_fin_user`, `user_registro`, `placa`, `grupo`,`fecha`,`txt_actions`,`id_pregunta_grupo`) 
                            VALUES (null,:id_tema,:tipo_tema,:id_grupo_preguntas,:id_preguntas,:tipo_pregunta,:id_respueta,:desc_resp,:puntos_pregunta,:doble_check,:orden_resp,:resp_correcta,:respuesta_user,:user_creador,:centro,:fecha_ini_user,:fecha_fin_user,:user_registro,:placa,:grupo,:fecha,:txt_actions,:id_pregunta_grupo)
			');
			$insert->bindValue('id_tema',$id_tema);
			$insert->bindValue('tipo_tema',$tipo_tema);
			$insert->bindValue('id_grupo_preguntas',$id_grupo_preguntas);
			$insert->bindValue('id_preguntas',$id_preguntas);
			$insert->bindValue('tipo_pregunta',$tipo_pregunta);
			$insert->bindValue('id_respueta',$id_respueta);
			$insert->bindValue('desc_resp',$desc_resp);
			$insert->bindValue('puntos_pregunta',$puntos_pregunta);
			$insert->bindValue('doble_check',$doble_check);			
			$insert->bindValue('orden_resp',$orden_resp);
			$insert->bindValue('resp_correcta',$resp_correcta);
			$insert->bindValue('respuesta_user',$respuesta_user);
			$insert->bindValue('user_creador',$user_creador);
			$insert->bindValue('centro',$centro);
			$insert->bindValue('fecha_ini_user',$fecha_ini_user);
			$insert->bindValue('fecha_fin_user',$fecha_fin_user);
			$insert->bindValue('user_registro',$user_registro);
			$insert->bindValue('placa',$placa);
			$insert->bindValue('grupo',$grupo);
			$insert->bindValue('fecha',$fecha);
			$insert->bindValue('id_pregunta_grupo',$id_pregunta_grupo);
			$insert->bindValue('txt_actions',$txt_actions);			
			$insert->execute();
            Db::desconectar();			
			}			
		public function insertar_respuestas_encuesta_reparto($id_tema,$tipo_tema,$id_grupo_preguntas,$id_preguntas,$tipo_pregunta,$id_respueta,$desc_resp,$puntos_pregunta,$doble_check,$orden_resp,$resp_correcta,$respuesta_user,$user_creador,$centro,$fecha_ini_user,$fecha_fin_user,$user_registro,$placa,$grupo,$fecha){
			$db=DB::conectar();
			$insert=$db->prepare('
INSERT INTO `exa_detalle_reparto`(`id`, `id_tema`, `tipo_tema`, `id_grupo_preguntas`, `id_preguntas`, `tipo_pregunta`, `id_respueta`, `desc_resp`, `puntos_pregunta`, `doble_check`, `orden_resp`, `resp_correcta`, `respuesta_user`, `user_creador`, `centro`, `fecha_ini_user`, `fecha_fin_user`, `user_registro`, `placa`, `grupo`,`fecha`) 
                       VALUES (null,:id_tema,:tipo_tema,:id_grupo_preguntas,:id_preguntas,:tipo_pregunta,:id_respueta,:desc_resp,:puntos_pregunta,:doble_check,:orden_resp,:resp_correcta,:respuesta_user,:user_creador,:centro,:fecha_ini_user,:fecha_fin_user,:user_registro,:placa,:grupo,:fecha)
			');
			$insert->bindValue('id_tema',$id_tema);
			$insert->bindValue('tipo_tema',$tipo_tema);
			$insert->bindValue('id_grupo_preguntas',$id_grupo_preguntas);
			$insert->bindValue('id_preguntas',$id_preguntas);
			$insert->bindValue('tipo_pregunta',$tipo_pregunta);
			$insert->bindValue('id_respueta',$id_respueta);
			$insert->bindValue('desc_resp',$desc_resp);
			$insert->bindValue('puntos_pregunta',$puntos_pregunta);
			$insert->bindValue('doble_check',$doble_check);			
			$insert->bindValue('orden_resp',$orden_resp);
			$insert->bindValue('resp_correcta',$resp_correcta);
			$insert->bindValue('respuesta_user',$respuesta_user);
			$insert->bindValue('user_creador',$user_creador);
			$insert->bindValue('centro',$centro);
			$insert->bindValue('fecha_ini_user',$fecha_ini_user);
			$insert->bindValue('fecha_fin_user',$fecha_fin_user);
			$insert->bindValue('user_registro',$user_registro);
			$insert->bindValue('placa',$placa);
			$insert->bindValue('grupo',$grupo);
			$insert->bindValue('fecha',$fecha);			
			$insert->execute();	
			Db::desconectar();	
			}	
		public function grabar_respuesta_user($respuesta_user,$fecha_fin_user,$id,$centro,$user_registro){
			$db=DB::conectar();
			$insert=$db->prepare('
     UPDATE `exa_detalle_user` SET `respuesta_user`=:respuesta_user, `fecha_fin_user`=:fecha_fin_user WHERE id=:id AND centro=:centro AND user_registro=:user_registro
			');
			$insert->bindValue('respuesta_user',$respuesta_user);
			$insert->bindValue('fecha_fin_user',$fecha_fin_user);
			$insert->bindValue('id',$id);
            $insert->bindValue('centro',$centro);	
            $insert->bindValue('user_registro',$user_registro);
            $insert->execute();
			Db::desconectar();	
			}
		public function grabar_respuesta_checklist($respuesta_user,$fecha_fin_user,$id,$centro,$user_registro){
			$db=DB::conectar();
			$insert=$db->prepare('
     UPDATE `exa_detalle_checklist` SET `respuesta_user`=:respuesta_user, `fecha_fin_user`=:fecha_fin_user WHERE id=:id AND centro=:centro AND user_registro=:user_registro
			');
			$insert->bindValue('respuesta_user',$respuesta_user);
			$insert->bindValue('fecha_fin_user',$fecha_fin_user);
			$insert->bindValue('id',$id);
            $insert->bindValue('centro',$centro);	
            $insert->bindValue('user_registro',$user_registro);
            $insert->execute();
			Db::desconectar();	
			}
		public function grabar_head_checklist($tb,$valor,$grupo,$centro){
			$db=DB::conectar();
			$insert=$db->prepare('
     UPDATE `exa_detalle_checklist` SET '.$tb.'="'.$valor.'" WHERE grupo=:grupo AND centro=:centro
			');
			$insert->bindValue('grupo',$grupo);
            $insert->bindValue('centro',$centro);	
            $insert->execute();
			Db::desconectar();	
			}			
		public function grabar_comentario_checklist($txt_comentario,$id_pregunta_grupo,$centro,$user_registro){
			$db=DB::conectar();
			$insert=$db->prepare('
     UPDATE `exa_detalle_checklist` SET `txt_comentario`=:txt_comentario WHERE id_pregunta_grupo=:id_pregunta_grupo AND centro=:centro AND user_registro=:user_registro
			');
			$insert->bindValue('txt_comentario',$txt_comentario);
			$insert->bindValue('id_pregunta_grupo',$id_pregunta_grupo);
            $insert->bindValue('centro',$centro);	
            $insert->bindValue('user_registro',$user_registro);
            $insert->execute();
			Db::desconectar();	
			}			
		public function grabar_respuesta_encuesta_reparto($respuesta_user,$fecha_fin_user,$id,$centro,$user_registro){
			$db=DB::conectar();
			$insert=$db->prepare('
     UPDATE `exa_detalle_reparto` SET `respuesta_user`=:respuesta_user, `fecha_fin_user`=:fecha_fin_user WHERE id=:id AND centro=:centro
			');
			$insert->bindValue('respuesta_user',$respuesta_user);
			$insert->bindValue('fecha_fin_user',$fecha_fin_user);
			$insert->bindValue('id',$id);
            $insert->bindValue('centro',$centro);
            $insert->execute();
			Db::desconectar();	
			}
		public function califica_check_flota($centro,$id_tema,$user_registro,$fecha){
			$db=Db::conectar();
			$select=$db->prepare("
SELECT b.user_registro,b.Ok,b.Nok, ((b.Ok/(b.Nok+b.Ok))) AS resultado FROM (	 	
SELECT a.user_registro,
sum(a.Ok) as Ok,
sum(a.Nok) as Nok
FROM (
SELECT 
id,
user_registro,
desc_resp,
id_grupo_preguntas,
id_preguntas,
doble_check,
respuesta_user,
tipo_pregunta,
if(tipo_pregunta='7',if(CAST(respuesta_user AS SIGNED) >= 3,1,0),if(respuesta_user = 'Si',1,0)) as Ok,
if(tipo_pregunta='7',if(CAST(respuesta_user AS SIGNED) < 3,1,0),if(respuesta_user = 'No',1,0)) as Nok    
FROM `exa_detalle_user` 
WHERE fecha = :fecha
AND centro=:centro
AND id_tema=:id_tema
AND user_registro=:user_registro
AND respuesta_user <> '' 
AND tipo_pregunta IN ('4','7')
	 ) AS a GROUP BY a.user_registro
	 ) AS b ORDER BY b.Nok DESC			
			");
			$select->bindValue('centro',$centro);
			$select->bindValue('id_tema',$id_tema);
			$select->bindValue('user_registro',$user_registro);
			$select->bindValue('fecha',$fecha);
			$select->execute();
			while ($registro=$select->fetch()){
			return $registro;
            }
		Db::desconectar();		
		}
		public function grabar_estado_checklist($grupo,$fecha){
			$db=DB::conectar();
			$insert=$db->prepare('
     UPDATE `exa_detalle_user` SET st=:st  WHERE grupo=:grupo AND fecha=:fecha
			');
			$insert->bindValue('st',1);	
            $insert->bindValue('grupo',$grupo);	
			 $insert->bindValue('fecha',$fecha);	
			$insert->execute();	
			Db::desconectar();	
			}
		public function finalizar_checklist($grupo,$centro,$id_tema){
			$db=DB::conectar();
			$insert=$db->prepare('
     UPDATE `exa_detalle_checklist` SET st=:st  WHERE grupo=:grupo AND centro=:centro AND id_tema=:id_tema
			');
			$insert->bindValue('st',1);	
            $insert->bindValue('grupo',$grupo);	
			$insert->bindValue('centro',$centro);
			$insert->bindValue('id_tema',$id_tema);			
			$insert->execute();	
			Db::desconectar();	
			}
			
		public function consultabd($sql){
			$db=Db::conectar();
			$select=$db->prepare($sql);
			$select->execute();
			while ($registro=$select->fetch()){
			return $registro;
            }
		Db::desconectar();		
		}			
		}
?>