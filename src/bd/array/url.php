<?php
$UrlMenu = array("hoja_ruta"=>array(
							"Buscar Cliente"=>"hoja_ruta/hojaruta?rs=buscar_clientes_rs",
							"-"=>2,
							"Lista Segura"=>"hoja_ruta/hojaruta?rs=listasegura",
							"Alertas"=>"hoja_ruta/hojaruta",
							),
			  "admin"=>array(
							"usuarios"=>"admin/userhc",
							"roles"=>"admin/roles",
							"depositos_t2"=>"admin/depositos-t2",
							"excel_importar"=>array(
												"Hoja de Ruta"=>"excel_importar/rs",
												"Lista Segura"=>"excel_importar/ls",
												"Clientes NPS"=>"excel_importar/nps",
												"Alistamiento CD"=>"excel_importar/alistamiento_cd",
												"-"=>2,
												"Depositos Bancarios Peru"=>"excel_importar_peru/depositos_peru",
												"Hoja de Ruta Peru"=>"excel_importar_peru/hr_peru",
												"Check Out Almacen Peru"=>"excel_importar_peru/check_out_almacen_peru",
												),	
							"excel_exportar"=>"excel_exportar/lista",
							"-"=>2,
							"Zonas de Ventas"=>"ventas/ventas",
							"Rutas de Reparto"=>"admin/telefonosrepartos",
						),
			  "repartos_t2"=>array(
							"Visitas"=>"repartos_t2/visitas",
							"Depositos"=>"repartos_t2/visitas?FMfcgxwLtkdRLdXFNVNgdJkHCHKrmRpl=DepositosBancarios",
							"Check Out Almacen"=>"repartos_t2/checkoutalmacen",
							"Indicadores Diario"=>"repartos_t2/indicadoresdiarios",
							"Mapas"=>"repartos_t2/mapasme",
							"Telefonos"=>"repartos_t2/telefonosrepartos",
							"Documentos"=>"repartos_t2/documentosdelivery",
							"Asistencia"=>"repartos_t2/reportesT2?hc=asistenciarutinat2",
							"Califica Mi Entrega"=>"repartos_t2/calificamientrega",
							"Rate My Delivery Bees"=>"repartos_t2/rmdBees", 
							"Seguimiento Action Log"=>"repartos_t2/seguimientoActionLog"
			  ),
			  "empresarios_t2"=>array(
							"Transportes"=>"empresarios_t2/transportes",
							"OWD delivery"=>"empresarios_t2/checklistrpt?ckl=2",
							"Asistencia"=>"empresarios_t2/asistenciapersonal",
							"Reclamos"=>"empresarios_t2/reclamos",	
							"Check Out Almacen"=>"empresarios_t2/checkoutT2",
							"Check Out Almacen Resumen"=>"empresarios_t2/checkoutT2?FMfcgxwLtkdRLdXFNVNgdJkHCHKrmRpl=resumen"
			  ),			  
			  "dashboard"=>array(
							"Rechazos"=>"dashboard/rechazos",
							"Ejecucion de Entregas"=>"dashboard/entregas",
							"Modulaciones"=>"dashboard/alertas",
							"Rechazos Ventas"=>"dashboard/ventas",
							"-"=>2,	
							"5s Almacen / Oficinas"=>"dashboard/dashboard5salmacen",	
							"5s Flota"=>"dashboard/dashboard5sflota",
							"Indicadores de Reparto"=>"repartos_t2/reportesT2?hc=ejecuciondereparto"	
			  ),
			  "flota"=>array(
							"Check List T2"=>"flota/checklistT2",
							"Reportes"=>"flota/reporte",
							"backlog"=>"flota/backlog?hc=backlogcrud"								
			  ),
			  "gestion"=>array(
							"OWD 5S"=>"gestion/checklistrpt?ckl=5"
			  ),
			  "planeamiento"=>array(
							"NPS"=>"planeamiento/nps"							
			  ),
			  "almacen"=>array(
							"Check Out T2"=>"almacen/checkoutT2",
							"Fefo"=>"fefo/app"
			  ),
			  "sorting"=>array(
							"Envases"=>"sorting/envases",
							"Reporte"=>"sorting/envases?j5xqi9554vUXBmoX9IHXg=reporte",
							"Dashboard"=>"sorting/dashboardSorting",
							"Fefo"=>"fefo/app"
			  ),
			  "picking"=>array(
							"Alistamiento"=>"picking/alistamiento",
							"Reporte"=>"picking/alistamiento?j5xqi9554vUXBmoX9IHXg=reporte",
							"Dashboard"=>"picking/dashboardPicking",
							"Fefo"=>"fefo/app"
			  )				  
		);
$url_excel_exportar = array(			
						"Hoja Ruta Modulaciones"=>"ModalRSexportar",
						"POC Critico"=>"ModalPOCritico",
						"Check List"=>array(
										"Salida de Camiones"=>"ModalCheckListSalida",
										"Llegada de Camiones"=>"ModalCheckListLlegada"
										 ),
						"Otros T2"=>array(
										"SA Group Text Lite"=>"myMSJ1",
										"Multi SMS Sender (MSS)"=>"myMSJ2",
										"Csv-Sf-Dia-TodosCDs"=>"myModalFechaSFObed",
										"Demoras Atención T2"=>"myModalDemoras",
										"NPS - MPILCOSA"=>"myModalnpsmpilcosa"
										 ),
						"Usuarios"=>array(
										"Usuarios RM"=>"myModalUsuariosRM",
										"Resultados COVID"=>"myModalCoviD",
										"Lista Asistencia T2 Rutina"=>"myModalListaAsiT2"
										 )
						);		
?>