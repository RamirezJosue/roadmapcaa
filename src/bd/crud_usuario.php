<?php 
	require_once('banco.php');
	//require_once('usuario.php');
	class CrudUsuario{
		public function __construct(){}
		//inserta los datos del usuario
		public function insertar($nombre, $clave, $apellidos, $dni, $centro, $tipo, $accesos, $estado, $id_empresa, $puesto,$area, $division, $supervisor, $domicilio_user, $telefono, $email, $genero, $brevete, $brevete_cat,$vencimiento_brevete, $fecha_alta, $pin, $img, $user_registro){
			$db=DB::conectar();
			$insert=$db->prepare('
		INSERT INTO `usuarios`(`Id`, `nombre`, `clave`, `apellidos`, `dni`, `centro`, `tipo`, `accesos`, `estado`, `id_empresa`, `puesto`, 
								`area`, `division`, `supervisor`, `domicilio_user`, `telefono`, `email`, `genero`, `brevete`, `brevete_cat`, 
								`vencimiento_brevete`, `fecha_alta`, `pin`, `img`, `user_registro`) VALUES (
								null, :nombre, :clave, :apellidos, :dni, :centro, :tipo, :accesos, :estado, :id_empresa, :puesto, 
								:area, :division, :supervisor, :domicilio_user, :telefono, :email, :genero, :brevete, :brevete_cat, 
								:vencimiento_brevete, :fecha_alta, :pin, :img, :user_registro)
								');
			$insert->bindValue('nombre',$nombre);
			//encripta la clave desactivado 
			$insert->bindValue('clave',$clave);
			$insert->bindValue('apellidos',$apellidos);
			$insert->bindValue('dni',$dni);
			$insert->bindValue('centro',$centro);
			$insert->bindValue('tipo',0);
			$insert->bindValue('accesos',$accesos);
			$insert->bindValue('estado',$estado);
			$insert->bindValue('id_empresa',$id_empresa);
			$insert->bindValue('puesto',$puesto);
			$insert->bindValue('area',$area);
			$insert->bindValue('division',$division);
			$insert->bindValue('supervisor',$supervisor);
			$insert->bindValue('domicilio_user',$domicilio_user);
			$insert->bindValue('telefono',$telefono);
			$insert->bindValue('email',$email);
			$insert->bindValue('genero',$genero);
			$insert->bindValue('brevete',$brevete);
			$insert->bindValue('brevete_cat',$brevete_cat);
			$insert->bindValue('vencimiento_brevete',$vencimiento_brevete);
			$insert->bindValue('fecha_alta',$fecha_alta);
			$insert->bindValue('pin',$pin);
			$insert->bindValue('img',$img);
			$insert->bindValue('user_registro',$user_registro);
			$insert->execute();
			Db::desconectar();
		}
		// public function roles($id){
		// 	$db=DB::conectar();
		// 	$select=$db->prepare("SELECT um.nombre FROM ( SELECT * FROM `usuarios_roles_permisos` WHERE `id_rol`=:id_rol ) AS rp LEFT JOIN ( SELECT * FROM usuarios_modulos ) AS um ON rp.id_modulo = um.id");
		// 	$select->bindValue('id_rol',$id);
		// 	$select->execute();
        //     while ($registro=$select->fetch(PDO::FETCH_ASSOC)){
		// 		 $rol_user[] = $registro['nombre'];
        //     }
		// 	return $rol_user = implode(",",$rol_user);
		// 	Db::desconectar();	
		// }
		public function modificar_password($id,$dni,$newclave,$centro){
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
		public function modificar_estado_usuario($id,$dni,$estado,$centro){
			$db=DB::conectar();
			$insert=$db->prepare('UPDATE `usuarios` SET `estado`=:estado WHERE Id=:Id AND dni=:dni AND centro=:centro');
			$insert->bindValue('estado',$estado);
			$insert->bindValue('Id',$id);
			$insert->bindValue('dni',$dni);
			$insert->bindValue('centro',$centro);
			$insert->execute();
			Db::desconectar();
		}	
		public function modificar_parametro_usuario($id,$tb,$descripcion,$centro){
			$db=DB::conectar();
			$insert=$db->prepare("UPDATE usuario_".$tb." SET `descripcion`=:descripcion WHERE id=:id AND centro=:centro");
			$insert->bindValue('id',$id);
			$insert->bindValue('centro',$centro);
			$insert->bindValue('descripcion',$descripcion);
			$insert->execute();
			Db::desconectar();
		}
		public function modificar_parametro_usuario_empresa($id,$tb,$descripcion,$ruc,$responsable,$telefono,$domicilio,$actividad,$centro){
			$db=DB::conectar();
			$insert=$db->prepare("UPDATE usuario_".$tb." SET `descripcion`=:descripcion,`ruc`=:ruc,`responsable`=:responsable,`telefono`=:telefono,`domicilio`=:domicilio,`Actividad`=:Actividad WHERE id=:id AND centro=:centro");
			$insert->bindValue('id',$id);
			$insert->bindValue('centro',$centro);
			$insert->bindValue('descripcion',$descripcion);
			$insert->bindValue('ruc',$ruc);
			$insert->bindValue('responsable',$responsable);
			$insert->bindValue('telefono',$telefono);
			$insert->bindValue('domicilio',$domicilio);
			$insert->bindValue('Actividad',$actividad);
			$insert->execute();
			Db::desconectar();
		}
		public function agregar_parametro_usuario($tb,$descripcion,$centro){
			$db=DB::conectar();
			$insert=$db->prepare("INSERT INTO usuario_".$tb." (`id`, `descripcion`, `centro`) VALUES (NULL, :descripcion, :centro)");
			$insert->bindValue('descripcion',$descripcion);			
			$insert->bindValue('centro',$centro);
			$insert->execute();
			Db::desconectar();
		}
		public function agregar_parametro_usuario_empresa($tb,$descripcion,$ruc,$responsable,$telefono,$domicilio,$actividad,$centro){
			$db=DB::conectar();
			$insert=$db->prepare("INSERT INTO usuario_".$tb." (`id`,`centro`,`descripcion`,`ruc`,`responsable`,`telefono`,`domicilio`,`Actividad`) VALUES (NULL, :centro, :descripcion,:ruc,:responsable,:telefono,:domicilio,:Actividad)");
			$insert->bindValue('centro',$centro);
			$insert->bindValue('descripcion',$descripcion);
			$insert->bindValue('ruc',$ruc);
			$insert->bindValue('responsable',$responsable);
			$insert->bindValue('telefono',$telefono);
			$insert->bindValue('domicilio',$domicilio);
			$insert->bindValue('Actividad',$actividad);
			$insert->execute();
			Db::desconectar();
		}
		public function registrar_estado_usuario($tipo,$dni,$descripcion,$fecha_estado,$fec_hora_real,$user_registro,$centro){
			$db=DB::conectar();
			$insert=$db->prepare('
			INSERT INTO `usuario_baja`(`id`, `tipo`, `dni`, `descripcion`, `fecha_estado`, `fec_hora_real`, `user_registro`,`centro`) 
								VALUES (null,:tipo,:dni,:descripcion,:fecha_estado,:fec_hora_real,:user_registro,:centro)
								');
			//encripta la clave
			$insert->bindValue('tipo',$tipo);
			$insert->bindValue('dni',$dni);
			$insert->bindValue('descripcion',$descripcion);
			$insert->bindValue('fecha_estado',$fecha_estado);
			$insert->bindValue('fec_hora_real',$fec_hora_real);
			$insert->bindValue('user_registro',$user_registro);
			$insert->bindValue('centro',$centro);
			$insert->execute();
			Db::desconectar();
		}
		public function registrar_estado_covid($dni,$tipo_examen,$fecha_examen,$dias_cuarentena,$seguimiento_medico,$positivo,$laboratorio,$observacion,$centro){
			$db=DB::conectar();
			$insert=$db->prepare('
			INSERT INTO `usuario_covid`(`id`, `dni`, `tipo_examen`, `fecha_examen`, `dias_cuarentena`, `seguimiento_medico`, `positivo`, `laboratorio`, `observacion`, `centro`) 
								VALUES (null,:dni,:tipo_examen,:fecha_examen,:dias_cuarentena,:seguimiento_medico,:positivo,:laboratorio,:observacion,:centro)
								');
			$insert->bindValue('dni',$dni);
			$insert->bindValue('tipo_examen',$tipo_examen);			
			$insert->bindValue('fecha_examen',$fecha_examen);
			$insert->bindValue('dias_cuarentena',$dias_cuarentena);
			$insert->bindValue('seguimiento_medico',$seguimiento_medico);
			$insert->bindValue('positivo',$positivo);
			$insert->bindValue('laboratorio',$laboratorio);
			$insert->bindValue('observacion',$observacion);
			$insert->bindValue('centro',$centro);
			$insert->execute();
			Db::desconectar();
		}				
		//obtiene el usuario para el login
		public function obtenerUsuario($dni, $clave){
			$db=Db::conectar();
			$select=$db->prepare("SELECT u.nombre,u.clave,u.apellidos, u.dni, u.centro, u.tipo, u.accesos as rol,
			(SELECT GROUP_CONCAT(DISTINCT(m.accesos)) FROM(
			SELECT 
			rp.id_rol,
			um.nombre as accesos 
			FROM 
			( SELECT * FROM `usuarios_roles_permisos`) AS rp 
			LEFT JOIN 
			( SELECT * FROM `usuarios_modulos` ) AS um 
			ON rp.id_modulo = um.id
			) AS m WHERE m.id_rol = u.accesos ) as rol_accesos,
			u.estado, u.id_empresa, u.puesto, u.telefono  FROM usuarios AS u WHERE u.dni=:dni AND u.estado=1");
			$select->bindValue('dni',$dni);
			$select->execute();
			$registro=$select->fetch(PDO::FETCH_ASSOC);
			if ($clave == $registro['clave']) {				
				return $registro;
			} else 	{
				return $registro = null;

			}
			Db::desconectar();
		}
		//busca el nombre del usuario si existe
		public function buscarUsuario($dni){
			$db=Db::conectar();
			$select=$db->prepare('SELECT * FROM usuarios WHERE dni=:dni');
			$select->bindValue('dni',$dni);
			$select->execute();
			$registro=$select->fetch();
			if($registro['Id']!=NULL){
				$usado=False;
			}else{
				$usado=True;
			}	
			return $usado;
			Db::desconectar();
		}
		public function ObtenerHoraInicioTX($tdhorainicio,$id){
            $db=Db::conectar();
			$select=$db->prepare("SELECT ".$tdhorainicio." FROM tiemposvehiculosdetalle WHERE id = :id");
			$select->bindValue('id',$id);
			$select->execute(); 
			$registro=$select->fetch();
			return $registro[''.$tdhorainicio.''];
			Db::desconectar();	
		}
		public function sacarnombre($dni){
			$db=Db::conectar();
			$select=$db->prepare("SELECT * FROM usuarios WHERE dni=:dni");
			$select->bindValue('dni',$dni);
			$select->execute();
			$registro=$select->fetch();
			return ucfirst($registro['nombre'])." ".ucfirst($registro['apellidos']);
			Db::desconectar();	
		}
		public function sacarcentro($dni){
			$db=Db::conectar();
			$select=$db->prepare("SELECT * FROM usuarios WHERE dni=:dni");
			$select->bindValue('dni',$dni);
			$select->execute();
			$registro=$select->fetch();
			return $registro['centro'];	
			Db::desconectar();	
		}			
		public function EntregarPedido($id,$dni,$centro,$cjasrechazadas,$clasifica_envases,$cjasclasificadas){
          $db=Db::conectar();
          $select=$db->prepare("UPDATE t77_rs SET entregado = :entregado , dni = :dni, registrofin = :registrofin, cjasrechazadas = :cjasrechazadas, clasifica_envases = :clasifica_envases, cjasclasificadas = :cjasclasificadas  WHERE id = :id AND centro=:centro");
		  $select->bindValue('entregado',1);
		  $select->bindValue('dni',$dni);
		  $select->bindValue('cjasrechazadas',$cjasrechazadas);
		  $select->bindValue('registrofin',date("Y-m-d H:i:s",$time = time())); 
		  $select->bindValue('id',$id);
		  $select->bindValue('centro',$centro);
		  $select->bindValue('clasifica_envases',$clasifica_envases);
		  $select->bindValue('cjasclasificadas',$cjasclasificadas);
		  $select->execute(); 
		  Db::desconectar();
		}
		public function EntregarPedidoTodos($dni,$centro,$Ruta,$Fecha,$Viaje){
          $db=Db::conectar();
          $select=$db->prepare("UPDATE t77_rs 
				SET entregado = :entregado , dni = :dni, registrofin = :registrofin  
				WHERE centro=:centro AND Fecha=:Fecha AND Ruta=:Ruta AND Viaje=:Viaje AND rechazo <> 1");
		  $select->bindValue('entregado',1);
		  $select->bindValue('dni',$dni);
		  $select->bindValue('registrofin',date("Y-m-d H:i:s",$time = time())); 
		  $select->bindValue('centro',$centro);
		  $select->bindValue('Ruta',$Ruta);
		  $select->bindValue('Fecha',$Fecha);
		  $select->bindValue('Viaje',$Viaje);
		  $select->execute(); 
		  Db::desconectar();
		}		
	    public function GrabarAlerta($mr,$cjasrechazadas,$comentario,$dni,$id){
          $db=Db::conectar();
          $select=$db->prepare("UPDATE t77_rs SET alerta = :alerta , mr = :mr, cjasrechazadas = :cjasrechazadas, comentario = :comentario, Sec2 = :Sec2, fechahoraalerta = :fechahoraalerta  WHERE id = :id");
		  $select->bindValue('alerta',1);
		  $select->bindValue('mr',$mr);
		  $select->bindValue('cjasrechazadas',$cjasrechazadas);
		  $select->bindValue('comentario',$comentario);
		  $select->bindValue('Sec2',$dni); 
		  $select->bindValue('fechahoraalerta',date("Y-m-d H:i:s",$time = time())); 
		  $select->bindValue('id',$id);
		  $select->execute();
		  Db::desconectar();	
		}
	    public function modificar_eta($Llega,$id,$centro){
          $db=Db::conectar();
          $select=$db->prepare("UPDATE t77_rs SET reprogramado = :reprogramado , Llega = :Llega  WHERE id = :id AND centro=:centro");
		  $select->bindValue('reprogramado',1);
		  $select->bindValue('Llega',$Llega);
		  $select->bindValue('id',$id);
		  $select->bindValue('centro',$centro);
		  $select->execute();
		  Db::desconectar();	
		}		
		public function GrabarRechazo($dni,$id,$autoriza_rech,$cjasrechazadas,$clasifica_envases,$cjasclasificadas,$centro,$mr,$comentarios){
          $db=Db::conectar();
          $select=$db->prepare("UPDATE t77_rs SET rechazo = :rechazo , 
										          dni = :dni, 
												  registrofin = :registrofin, 
												  autoriza_rech=:autoriza_rech, 
												  cjasrechazadas = :cjasrechazadas, 
												  clasifica_envases = :clasifica_envases,
												  mr = :mr,
												  comentario = :comentario,
												  cjasclasificadas = :cjasclasificadas  WHERE id = :id AND centro=:centro");
		  $select->bindValue('rechazo',1);
		  $select->bindValue('dni',$dni);
		  $select->bindValue('registrofin',date("Y-m-d H:i:s",$time = time())); 	  
		  $select->bindValue('cjasrechazadas',$cjasrechazadas);
		  $select->bindValue('autoriza_rech',$autoriza_rech);
		  $select->bindValue('mr',$mr);
          $select->bindValue('comentario',$comentarios);		  
		  $select->bindValue('id',$id);
		  $select->bindValue('clasifica_envases',$clasifica_envases);
		  $select->bindValue('cjasclasificadas',$cjasclasificadas);
		  $select->bindValue('centro',$centro);	
		  $select->execute(); 
		  Db::desconectar();
		}
	public function GrabarClienteClasificaEnvases($id,$centro,$valor){
          $db=Db::conectar();
          $select=$db->prepare("UPDATE t77_rs SET clasifica_envases = :clasifica_envases WHERE id = :id AND centro=:centro");
		  $select->bindValue('clasifica_envases',$valor);
		  $select->bindValue('centro',$centro); 
		  $select->bindValue('id',$id);
		  $select->execute();
		  Db::desconectar();	
		}		
		public function sacarmc($cliente){
			$db=Db::conectar();
			$select=$db->prepare("SELECT supervisor,zonaac,agente FROM t77_mc WHERE codcli=:codcli");
			$select->bindValue('codcli',$cliente);
			$select->execute();
			$registro=$select->fetch();
			return $registro['supervisor'].' '.$registro['zonaac'].' '.$registro['agente'];	
			Db::desconectar();	
		}
		public function sacarmcarray($cliente){
			$db=Db::conectar();
			$select=$db->prepare("SELECT supervisor,zonaac,agente FROM t77_mc WHERE codcli=:codcli");
			$select->bindValue('codcli',$cliente);
			$select->execute();
			$registro=$select->fetch();
			return $registro;
			Db::desconectar();	
		}
		public function sacatelfrep($ruta){
			$db=Db::conectar();
			$select=$db->prepare("SELECT telefonoreparto FROM t77_em WHERE ruta=:ruta");
			$select->bindValue('ruta',$ruta);
			$select->execute();
			$registro=$select->fetch();
			return $registro['telefonoreparto'];
			Db::desconectar();	
		}
		public function ModificarTelefono($telefono,$id){
          $db=Db::conectar();
          $select=$db->prepare("UPDATE t77_em SET telefonoreparto = :telefonoreparto WHERE id = :id");
		  $select->bindValue('telefonoreparto',$telefono); 
		  $select->bindValue('id',$id);
		  $select->execute(); 
		  Db::desconectar();
		}
		public function editar_contacto_reparto($empresa,$telefonoreparto,$id,$centro){
          $db=Db::conectar();
          $select=$db->prepare("
		  UPDATE `t77_em` SET `empresa`=:empresa,`telefonoreparto`=:telefonoreparto WHERE id=:id AND centro=:centro
		  ");
		  $select->bindValue('empresa',$empresa); 
		  $select->bindValue('telefonoreparto',$telefonoreparto); 
		  $select->bindValue('id',$id);
		  $select->bindValue('centro',$centro);
		  $select->execute(); 
		  Db::desconectar();
		}
		public function eliminar_contacto_reparto($id,$centro,$ruta){
          $db=Db::conectar();
          $select=$db->prepare("DELETE FROM `t77_em` WHERE `id`=:id AND `centro`=:centro AND `ruta`=:ruta");
		  $select->bindValue('id',$id); 
		  $select->bindValue('centro',$centro); 
		  $select->bindValue('ruta',$ruta); 
		  $select->execute(); 
		  Db::desconectar();
		}
		public function agregar_contacto_reparto($centro,$ruta,$empresa,$telefonoreparto){
          $db=Db::conectar();
          $select=$db->prepare("
	INSERT INTO `t77_em`(`id`, `centro`, `ruta`, `empresa`, `telefonoreparto`) 
				 VALUES (null,:centro,:ruta,:empresa,:telefonoreparto)
		  "); 
		  $select->bindValue('centro',$centro); 
		  $select->bindValue('ruta',$ruta); 
		  $select->bindValue('empresa',$empresa); 
		  $select->bindValue('telefonoreparto',$telefonoreparto);
		  $select->execute(); 
		  Db::desconectar();
		}
		public function editar_contacto_ventas($nombreSup,$nombreAc,$telfAc,$telfSup,$id,$centro){
          $db=Db::conectar();
          $select=$db->prepare("UPDATE t77_zv_detalle SET nombreSup=:nombreSup,nombreAc=:nombreAc,telfAc=:telfAc,telfSup=:telfSup WHERE id = :id AND centro = :centro");
		  $select->bindValue('nombreSup',$nombreSup); 
		  $select->bindValue('nombreAc',$nombreAc); 
		  $select->bindValue('telfAc',$telfAc); 
		  $select->bindValue('telfSup',$telfSup); 
		  $select->bindValue('id',$id);
		  $select->bindValue('centro',$centro);
		  $select->execute(); 
		  Db::desconectar();
		}
		public function elimiar_contacto_ventas($id,$centro,$zv){
          $db=Db::conectar();
          $select=$db->prepare("DELETE FROM `t77_zv_detalle` WHERE `id`=:id AND `centro`=:centro AND `zv`=:zv");
		  $select->bindValue('id',$id); 
		  $select->bindValue('centro',$centro); 
		  $select->bindValue('zv',$zv); 
		  $select->execute(); 
		  Db::desconectar();
		}
		public function agregar_contacto_ventas($centro,$zv,$nombreSup,$nombreAc,$telfAc,$telfSup){
          $db=Db::conectar();
          $select=$db->prepare("
		  INSERT INTO `t77_zv_detalle`(`id`,`centro`,`zv`,`nombreSup`,`nombreAc`,`telfAc`,`telfSup`) 
							   VALUES (null,:centro,:zv,:nombreSup,:nombreAc,:telfAc,:telfSup)
		  "); 
		  $select->bindValue('centro',$centro); 
		  $select->bindValue('zv',$zv); 
		  $select->bindValue('nombreSup',$nombreSup); 
		  $select->bindValue('nombreAc',$nombreAc);
		  $select->bindValue('telfAc',$telfAc);
		  $select->bindValue('telfSup',$telfSup);
		  $select->execute(); 
		  Db::desconectar();
		}		
		public function contardbuser($tb,$dbt,$id){
			$db=Db::conectar();
			$select=$db->prepare('SELECT '.$tb.' FROM '.$dbt.' WHERE '.$id.'');
			$select->execute();	
			$cuenta_col = $select->rowCount();				
			return $cuenta_col;
			Db::desconectar();
		}
		public function contar_bd($sql){
			$db=Db::conectar();
			$select=$db->prepare($sql);
			$select->execute();	
			$cuenta_col = $select->rowCount();				
			return $cuenta_col;
			Db::desconectar();
		}		
		public function arrar_bd_return($tb,$dbt,$id){
			$db=Db::conectar();
			$select=$db->prepare('SELECT '.$tb.' FROM '.$dbt.' WHERE '.$id.'');
			$select->execute();	
			$registro=$select->fetch();
            $array = explode(",", $registro[''.$tb.'']);			
			return $array;
			Db::desconectar();
		}
		public function sacarmonbre_db($tb,$dbt,$id){
			$db=Db::conectar();
			$select=$db->prepare('SELECT '.$tb.' FROM '.$dbt.' WHERE '.$id.'');
			$select->execute();
			while ($registro=$select->fetch()){
			return $registro;
            }
			Db::desconectar();	
		}		
		function minutosTranscurridos($fecha_i,$fecha_f)
		{
		$to_time = strtotime($fecha_i);
		$from_time = strtotime($fecha_f);
		$minutos =(round(abs($to_time - $from_time) / 60,2));	
		return $minutos;
		Db::desconectar();
		}
		public function Insertarbacklog($indx,$vehiculo,$tipo_flota,$empresa,$anomalia_checklist,$descripcion_anomalia,$sistema,$sub_sistema,$nivel_correctivo,$meta_atencion_Hrs,$fecha_reporte_falla,$fecha_inicio_reparacion,$minimo_vital,$centro){
			$db=DB::conectar();
			$insert=$db->prepare('
INSERT INTO `t77_back_log`(`id`, `indx`, `vehiculo`, `tipo_flota`, `empresa`, `anomalia_checklist`, `descripcion_anomalia`, `sistema`, `sub_sistema`, `nivel_correctivo`, `meta_atencion_Hrs`, `fecha_reporte_falla`, `fecha_inicio_reparacion`, `fecha_fin_reparacion`, `minimo_vital`, `tiempo_atencion_Hrs`, `plan_de_accion`, `centro`) 
                   VALUES (null, :indx, :vehiculo, :tipo_flota, :empresa, :anomalia_checklist, :descripcion_anomalia, :sistema, :sub_sistema, :nivel_correctivo, :meta_atencion_Hrs, :fecha_reporte_falla, :fecha_inicio_reparacion, :fecha_fin_reparacion, :minimo_vital, :tiempo_atencion_Hrs, :plan_de_accion, :centro)
			');
			$insert->bindValue('indx',$indx);
			$insert->bindValue('vehiculo',$vehiculo);
			$insert->bindValue('tipo_flota',$tipo_flota);
			$insert->bindValue('empresa',$empresa);
			$insert->bindValue('anomalia_checklist',$anomalia_checklist);
			$insert->bindValue('descripcion_anomalia',$descripcion_anomalia);
			$insert->bindValue('sistema',$sistema);
			$insert->bindValue('sub_sistema',$sub_sistema);
			$insert->bindValue('nivel_correctivo',$nivel_correctivo);
			$insert->bindValue('meta_atencion_Hrs',$meta_atencion_Hrs);
			$insert->bindValue('fecha_reporte_falla',$fecha_reporte_falla);
			$insert->bindValue('fecha_inicio_reparacion',$fecha_inicio_reparacion);
			$insert->bindValue('fecha_fin_reparacion','0000-00-00 00:00:00');
			$insert->bindValue('minimo_vital',$minimo_vital);
			$insert->bindValue('tiempo_atencion_Hrs',0);
			$insert->bindValue('plan_de_accion','');
			$insert->bindValue('centro',$centro);
			$insert->execute();
			Db::desconectar();	
		}
		public function BuscarRegistro($sql){
			$db=Db::conectar();
			$select=$db->prepare($sql);
			$select->execute();
			$registro=$select->fetch();
			if($registro[0]!=NULL){
				$usado=False;
			}else{
				$usado=True;
			}	
			return $usado;
			Db::desconectar();
		}
		public function eliminar_registro($sql){
			$db=DB::conectar();
			$delete=$db->prepare($sql);
			$delete->execute();
			Db::desconectar();
		}
		public function comcluir_backlog($fecha_fin_reparacion,$tiempo_atencion_Hrs,$plan_de_accion,$id,$centro){
          $db=Db::conectar();
          $select=$db->prepare("
UPDATE `t77_back_log` 
SET `fecha_fin_reparacion`=:fecha_fin_reparacion,`tiempo_atencion_Hrs`=:tiempo_atencion_Hrs,`plan_de_accion`=:plan_de_accion,`estado`=:estado
WHERE id=:id AND centro=:centro
		  ");
		  $select->bindValue('fecha_fin_reparacion',$fecha_fin_reparacion); 
		  $select->bindValue('tiempo_atencion_Hrs',$tiempo_atencion_Hrs); 
		  $select->bindValue('plan_de_accion',$plan_de_accion); 
		  $select->bindValue('estado',1); 
		  $select->bindValue('id',$id);
		   $select->bindValue('centro',$centro);
		  $select->execute(); 
		  Db::desconectar();
		}
		public function check_supervisor($checksupervisor,$grupo,$user_registro,$centro){
          $db=Db::conectar();
          $select=$db->prepare("
UPDATE `exa_detalle_user` 
	SET `checksupervisor`= :checksupervisor
WHERE grupo=:grupo AND user_registro=:user_registro AND centro=:centro
		  ");
		  $select->bindValue('checksupervisor',$checksupervisor); 
		  $select->bindValue('grupo',$grupo); 
		  $select->bindValue('user_registro',$user_registro); 
		  $select->bindValue('centro',$centro); 
		  $select->execute(); 
		  Db::desconectar();
		}
		public function InsertarHoraInicioVehiculoT2($indx,$centro,$vehiculo,$ruta,$viaje,$fecha_plan,$inicio_conductor){
			$db=DB::conectar();
			$insert=$db->prepare('
INSERT INTO `t77_rs_ruta_sif`(`id`,`indx`,`centro`, `vehiculo`, `ruta`, `viaje`, `fecha_plan`, `inicio_conductor`, `salida_cd`, `llegada_cd`, `ingreso_cd`, `fin_conductor`) 
			          VALUES (null,:indx,:centro,:vehiculo,:ruta,:viaje,:fecha_plan,:inicio_conductor,:salida_cd,:llegada_cd,:ingreso_cd,:fin_conductor)
			');
			$insert->bindValue('indx',$indx);
			$insert->bindValue('centro',$centro);
			$insert->bindValue('vehiculo',$vehiculo);
			$insert->bindValue('ruta',$ruta);
			$insert->bindValue('viaje',$viaje);
			$insert->bindValue('fecha_plan',$fecha_plan);
			$insert->bindValue('inicio_conductor',$inicio_conductor);
			$insert->bindValue('salida_cd','0000-00-00 00:00:00');
			$insert->bindValue('llegada_cd','0000-00-00 00:00:00');
			$insert->bindValue('ingreso_cd','0000-00-00 00:00:00');
			$insert->bindValue('fin_conductor','0000-00-00 00:00:00');
			$insert->execute(); 
			Db::desconectar();
		}
		public function ModificarHoraInicioVehiculoT2($tb,$fecha_hora,$centro,$id){
          $db=Db::conectar();
          $select=$db->prepare('UPDATE `t77_rs_ruta_sif` SET '.$tb.'=:'.$tb.' WHERE id=:id AND centro=:centro');
		  $select->bindValue(''.$tb.'',$fecha_hora);
		  $select->bindValue('centro',$centro); 
		  $select->bindValue('id',$id);
		  $select->execute(); 
		  Db::desconectar();
		}
		public function insertar_encuesta_t2($id,$centro,$valor){
          $db=Db::conectar();
          $select=$db->prepare('UPDATE `t77_rs` SET `encuesta`=:encuesta WHERE id=:id AND centro=:centro');
		  $select->bindValue('encuesta',$valor); 		  
		  $select->bindValue('id',$id);
		  $select->bindValue('centro',$centro); 		  
		  $select->execute(); 
		  Db::desconectar();
		}
		public function insertar_repaso_t2($id,$centro,$valor){
          $db=Db::conectar();
          $select=$db->prepare('UPDATE `t77_rs` SET `repaso`=:repaso WHERE id=:id AND centro=:centro');
		  $select->bindValue('repaso',$valor);		  
		  $select->bindValue('id',$id);
		  $select->bindValue('centro',$centro); 		  
		  $select->execute(); 
		  Db::desconectar();
		}		
		public function enviar_kpi_reparto($Ruta,$centro,$Fecha){
          $db=Db::conectar();
          $select=$db->prepare('UPDATE `t77_rs` SET `st_kpi`=:st_kpi WHERE `Fecha`=:Fecha AND `Ruta`=:Ruta AND `centro`=:centro ');
		  $select->bindValue('Fecha',$Fecha);		  
		  $select->bindValue('Ruta',$Ruta);
		  $select->bindValue('centro',$centro); 	
		  $select->bindValue('st_kpi',1); 			  
		  $select->execute();
		  Db::desconectar();	
		}
		public function tiempoTranscurridoFechas($fechaInicio,$fechaFin){
		 $fecha1 = new DateTime($fechaInicio);
		 $fecha2 = new DateTime($fechaFin);
		 $fecha = $fecha1->diff($fecha2);
		 $tiempo = "";
		 //años
		 if($fecha->y > 0)
		 {
			$tiempo .= $fecha->y;
             
		 if($fecha->y == 1)
            $tiempo .= " año, ";
         else
            $tiempo .= " años, ";
		 }
		 //meses
		 if($fecha->m > 0)
		 {
         $tiempo .= $fecha->m;
             
         if($fecha->m == 1)
            $tiempo .= " mes, ";
         else
            $tiempo .= " meses, ";
		 }    
		 //dias
		 if($fecha->d > 0)
		 {
         $tiempo .= $fecha->d;
             
         if($fecha->d == 1)
            $tiempo .= " día, ";
         else
            $tiempo .= " días, ";
		 }    
		 //horas
		 if($fecha->h > 0)
		 {
         $tiempo .= $fecha->h;
             
         if($fecha->h == 1)
            $tiempo .= " hora, ";
         else
            $tiempo .= " horas, ";
		 }    
    //minutos
		 if($fecha->i > 0)
		 {
         $tiempo .= $fecha->i;
             
         if($fecha->i == 1)
            $tiempo .= " minuto";
         else
            $tiempo .= " minutos";
		 }
		 else if($fecha->i == 0) //segundos
         $tiempo .= $fecha->s." segundos";
		 return $tiempo;
		 Db::desconectar();
		}		
	}
?>