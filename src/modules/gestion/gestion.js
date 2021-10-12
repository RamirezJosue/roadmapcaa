  var provincias_1=new Array("","Accesorio","Caja fuerte","Cambiar chapa de puerta","Cambiar cinturón de seguridad","Cambiar deposito de agua limpiaparabrisa","Cambiar funda de asiento","Guardafango","Llave de contacto/puerta","Luna de puerta","Luna posterior","Luna de techo","Parabrisas","Pintar techo cabina","Reparar asiento","Reparar bisagra de capot","Reparar piso inferior","Reparar soporte de Hand Help","Espejo retrovisor suelto","Plumilla resecado");
  var provincias_2=new Array("","Accesorio","Reparar parachoque delt","Reparar parachoque post","Reparar cortina","Reparar defensa lateral","Reparar bandeja","Reparar techo","Reparar carrocería","Reparar interior de furgón","Reparar mamparón","Reparar/cambiar riel de cortina","Reparar separador de carga","Reparar rodamiento de cortina","Reparar marco de cortinas","Reparar tubo de cortinas","Reparar varilla seguro de carga","Reparar/cambiar manija de cortina");
  var provincias_3=new Array("","Accesorio","Reparar puente de chasis","Reparar porta llanta","Traccionar chasis");
  var provincias_4=new Array("","Alineamiento","Caja de dirección","Mangueras","Pines,bocinas,terminales","Fuga de hidrolina","Servodirección");
  var provincias_5=new Array("","Accesorio","Arranque","Carga de batería","Circuitos eléctricos","Controles y tablero","Claxon","Sonido bajo de claxon","Freno de motor","Luz alta/baja","Luz ruta delantera","Luz de ruta posterior","Luz de freno","Luz direccional","Luz de retroceso","Luz interior cabina","Luz exterior de furgon","Luz interior furgón","Luz de placa","Mica rajada");
  var provincias_6=new Array("","Accesorio","Sensor");
  var provincias_7=new Array("","Alineamiento","Cambiar retén de bocamaza","Cambiar aceite de eje bocamaza","Desmontar/montar tercer eje","Rectificar bocamaza de rueda","Reparar bocamaza de rueda","Reparar eje propulsor","Reparar eje de rueda posterior");
  var provincias_8=new Array("","Cambiar protector de calor","Reparar tubo de cola","Reparar silenciador","Reparar tubo de bajada","Reparar freno de motor de escape");
  var provincias_9=new Array("","Freno de estaciónamiento","Freno hidro-neumático","Freno neumático","Freno hidráulico","Freno de servicio","Pastillas","Presión de aire","Cambiar pulmon de freno","Regulación","Zapatas");
  var provincias_10=new Array("","Inclinación","Piston central de tapa","Piston de direccion","Piston de Mastil","Elevación central","Porta uñas","Parrilla","Rodamientos","Tapa estabilizadora");
  var provincias_11=new Array("","Botiquín","Extintor","Gata hidráulica","Llave de rueda","Medidor de presion de aire","Palanca de rueda","Tubo de rueda","Porta tacos y conos","Cono","Taco");
  var provincias_12=new Array("","Corte superficial B/L","Balancear llanta","Cambiar llanta delantero","Cambiar llanta posterior","Cambiar extensión de neumático","Cambiar rodaje de rueda","Enllantar llanta delantero","Enllantar llanta posterior","Extensión","Llanta de repuesto","Nivelar presión de llanta","Perno de rueda","Pintar aro","Rotar llanta","Reparar aro","Reparar llanta","Tapón de válvula");
  var provincias_13=new Array("","Accesorio","Admisión","Combustible","Encendido","Lubricación","Refrigeración","Reparar protector de carter","Turbo");
  var provincias_14=new Array("","Accesorio","Aire comprimido","Amortiguadores","Barra estabilizadora","Bolsa de aire","Bujes","Cañería","Ejes","Muelles","Topes");
  var provincias_15=new Array("","Accesorio","Cardán","Caja de cambios","Corona","Diferencial","Embrague","Fuga fluído");
  var todassubsistemas = [
    [],
    provincias_1,
    provincias_2,
    provincias_3,
    provincias_4,
	provincias_5,
	provincias_6,
	provincias_7,
	provincias_8,
	provincias_9,
	provincias_10,
	provincias_11,
	provincias_12,
	provincias_13,
	provincias_14,
	provincias_15
  ];

  function cambia_sistema(){ 
   	//tomo el valor del select del sistema elegido 
   	var sistema 
   	sistema = document.f1.sistema[document.f1.sistema.selectedIndex].value 
   	//miro a ver si el sistema está definido 
   	if (sistema != 0) { 
      	//si estaba definido, entonces coloco las opciones de la subsistema correspondiente. 
      	//selecciono el array de subsistema adecuado 
      	mis_subsistemas=todassubsistemas[sistema]
      	//calculo el numero de provincias 
      	num_subsistemas = mis_subsistemas.length 
      	//marco el número de provincias en el select 
      	document.f1.subsistema.length = num_subsistemas 
      	//para cada provincia del array, la introduzco en el select 
      	for(i=0;i<num_subsistemas;i++){ 
         	document.f1.subsistema.options[i].value=mis_subsistemas[i] 
         	document.f1.subsistema.options[i].text=mis_subsistemas[i] 
      	}	
   	}else{ 
      	//si no había provincia seleccionada, elimino las provincias del select 
      	document.f1.subsistema.length = 1 
      	//coloco un guión en la única opción que he dejado 
      	document.f1.subsistema.options[0].value = "" 
      	document.f1.subsistema.options[0].text = "" 
   	} 
   	//marco como seleccionada la opción primera de subsistema 
   	document.f1.subsistema.options[0].selected = true 
}

        var opciones = {		
  //solución, material y tiempo
"": 										["","",""],
"Accesorio":  								["NIVEL 1","6","Mínimo No Vital"],
"Caja fuerte":  							["NIVEL 1","6","Mínimo Vital"],
"Cambiar chapa de puerta":  				["NIVEL 3","168","Mínimo No Vital"],
"Cambiar cinturón de seguridad":			["NIVEL 1","6","Mínimo Vital"],
"Cambiar deposito de agua limpiaparabrisa": ["NIVEL 4","720","Mínimo No Vital"],
"Cambiar funda de asiento":  				["NIVEL 4","720","Mínimo No Vital"],
"Llave de contacto/puerta":  				["NIVEL 1","6","Mínimo Vital"],
"Mangueras":  								["NIVEL 1","6","Mínimo Vital"],
"Circuitos eléctricos":  					["NIVEL 1","6","Mínimo No Vital"],
"Claxon":  									["NIVEL 1","6","Mínimo Vital"],
"Luz alta/baja":  							["NIVEL 1","6","Mínimo Vital"],
"Luz ruta delantera":  						["NIVEL 1","6","Mínimo Vital"],
"Luz de ruta posterior":  					["NIVEL 1","6","Mínimo Vital"],
"Luz de freno":  							["NIVEL 1","6","Mínimo Vital"],
"Luz direccional":  						["NIVEL 1","6","Mínimo Vital"],
"Luz de retroceso":  						["NIVEL 1","6","Mínimo Vital"],
"Luz interior cabina":  					["NIVEL 1","6","Mínimo No Vital"],
"Luz interior furgón":  					["NIVEL 1","6","Mínimo Vital"],
"Luz de placa":  							["NIVEL 1","6","Mínimo Vital"],
"Luz exterior de furgon":  					["NIVEL 1","6","Mínimo Vital"],
"Botiquín":  								["NIVEL 1","6","Mínimo Vital"],
"Extintor":  								["NIVEL 1","6","Mínimo Vital"],
"Gata hidráulica":  						["NIVEL 1","6","Mínimo Vital"],
"Llave de rueda":  							["NIVEL 1","6","Mínimo Vital"],
"Medidor de presion de aire":  				["NIVEL 1","6","Mínimo Vital"],
"Palanca de rueda":  						["NIVEL 1","6","Mínimo Vital"],
"Tubo de rueda":  							["NIVEL 1","6","Mínimo Vital"],
"Cono":  									["NIVEL 1","6","Mínimo Vital"],
"Taco":  									["NIVEL 1","6","Mínimo Vital"],
"Cambiar llanta delantero":  				["NIVEL 1","6","Mínimo Vital"],
"Cambiar llanta posterior":  				["NIVEL 1","6","Mínimo Vital"],
"Cambiar extensión de neumático":  		    ["NIVEL 1","6","Mínimo No Vital"],
"Extensión":  								["NIVEL 2","48","Mínimo No Vital"],
"Llanta de repuesto":  						["NIVEL 1","6","Mínimo Vital"],
"Nivelar presión de llanta":  				["NIVEL 1","6","Mínimo Vital"],
"Perno de rueda":  							["NIVEL 1","6","Mínimo Vital"],
"Rotar llanta":  							["NIVEL 1","6","Mínimo Vital"],
"Tapón de válvula":  						["NIVEL 1","6","Mínimo No Vital"],
"Bolsa de aire":  							["NIVEL 1","6","Mínimo Vital"],
"Cañería":  								["NIVEL 1","6","Mínimo Vital"],
"Luna de puerta":  							["NIVEL 2","48","Mínimo No Vital"],
"Luna posterior":  							["NIVEL 2","48","Mínimo No Vital"],
"Luna de techo":  							["NIVEL 2","48","Mínimo Vital"],
"Parabrisas":  								["NIVEL 2","48","Mínimo Vital"],
"Reparar asiento":  						["NIVEL 2","48","Mínimo No Vital"],
"Reparar soporte de Hand Help":  			["NIVEL 2","48","Mínimo No Vital"],
"Reparar mamparón":  						["NIVEL 2","48","Mínimo No Vital"],
"Reparar separador de carga":  				["NIVEL 2","48","Mínimo No Vital"],
"Reparar rodamiento de cortina":  			["NIVEL 2","48","Mínimo No Vital"],
"Reparar/cambiar manija de cortina":  		["NIVEL 2","48","Mínimo No Vital"],
"Reparar tubo de cortinas":  				["NIVEL 3","168","Mínimo No Vital"],
"Reparar marco de cortinas":  				["NIVEL 3","168","Mínimo No Vital"],
"Alineamiento direccional":                 ["NIVEL 2","48","Mínimo Vital"],
"Arranque":  								["NIVEL 2","48","Mínimo Vital"],
"Carga de batería":  						["NIVEL 2","48","Mínimo Vital"],
"Controles y tablero":  					["NIVEL 2","48","Mínimo No Vital"],
"Freno de motor":  							["NIVEL 2","48","Mínimo No Vital"],
"Sensor":  									["NIVEL 2","48","Mínimo No Vital"],
"Alineamiento Tracción":  				    ["NIVEL 2","48","Mínimo Vital"],
"Cambiar aceite de eje bocamaza":  			["NIVEL 2","48","Mínimo Vital"],
"Freno de estaciónamiento":  				["NIVEL 2","48","Mínimo Vital"],
"Freno hidro-neumático":  					["NIVEL 2","48","Mínimo Vital"],
"Freno neumático":  						["NIVEL 2","48","Mínimo Vital"],
"Freno hidráulico":  						["NIVEL 2","48","Mínimo Vital"],
"Freno de servicio":  						["NIVEL 2","48","Mínimo Vital"],
"Presión de aire":  						["NIVEL 2","48","Mínimo Vital"],
"Regulación":  								["NIVEL 2","48","Mínimo Vital"],
"Tapa estabilizadora":  					["NIVEL 2","48","Mínimo Vital"],
"Balancear llanta":  						["NIVEL 2","48","Mínimo No Vital"],
"Cambiar rodaje de rueda":  				["NIVEL 2","48","Mínimo Vital"],
"Pintar aro":  								["NIVEL 2","48","Mínimo No Vital"],
"Reparar aro":  							["NIVEL 2","48","Mínimo No Vital"],
"Reparar llanta":  							["NIVEL 2","48","Mínimo No Vital"],
"Admisión":  								["NIVEL 2","48","Mínimo Vital"],
"Combustible":  							["NIVEL 2","48","Mínimo No Vital"],
"Encendido":  								["NIVEL 2","48","Mínimo Vital"],
"Lubricación":  							["NIVEL 2","48","Mínimo No Vital"],
"Refrigeración":  							["NIVEL 2","48","Mínimo Vital"],
"Aire comprimido":  						["NIVEL 2","48","Mínimo Vital"],
"Amortiguadores":  							["NIVEL 2","48","Mínimo No Vital"],
"Ejes":  									["NIVEL 2","48","Mínimo No Vital"],
"Reparar defensa lateral":  				["NIVEL 2","48","Mínimo No Vital"],
"Reparar bandeja":  						["NIVEL 2","48","Mínimo No Vital"],
"Guardafango":  							["NIVEL 3","168","Mínimo No Vital"],
"Pintar techo cabina":  					["NIVEL 3","168","Mínimo No Vital"],
"Reparar bisagra de capot":  				["NIVEL 3","168","Mínimo No Vital"],
"Reparar piso inferior":  					["NIVEL 3","168","Mínimo No Vital"],
"Espejo retrovisor suelto":  				["NIVEL 3","168","Mínimo No Vital"],
"Reparar techo":  							["NIVEL 3","168","Mínimo No Vital"],
"Reparar carrocería ":  					["NIVEL 3","168","Mínimo No Vital"],
"Reparar interior de furgón":  				["NIVEL 3","168","Mínimo No Vital"],
"Reparar/cambiar riel de cortina":  		["NIVEL 3","168","Mínimo No Vital"],
"Reparar porta llanta":  					["NIVEL 3","168","Mínimo No Vital"],
"Caja de dirección":  						["NIVEL 3","168","Mínimo Vital"],
"Pines,bocinas,terminales":  				["NIVEL 3","168","Mínimo No Vital"],
"Servodirección":  							["NIVEL 3","168","Mínimo No Vital"],
"Alineamiento":  							["NIVEL 3","168","Mínimo No Vital"],
"Fuga de hidrolina":  						["NIVEL 3","168","Mínimo No Vital"],
"Cambiar retén de bocamaza":  				["NIVEL 3","168","Mínimo Vital"],
"Desmontar/montar tercer eje":  			["NIVEL 3","168","Mínimo No Vital"],
"Rectificar bocamaza de rueda":  			["NIVEL 3","168","Mínimo No Vital"],
"Reparar bocamaza de rueda":  				["NIVEL 3","168","Mínimo No Vital"],
"Reparar eje propulsor":  					["NIVEL 3","168","Mínimo No Vital"],
"Reparar eje de rueda posterior":  			["NIVEL 3","168","Mínimo No Vital"],
"Cambiar protector de calor":  				["NIVEL 3","168","Mínimo No Vital"],
"Reparar tubo de cola":  					["NIVEL 3","168","Mínimo No Vital"],
"Reparar silenciador":  					["NIVEL 3","168","Mínimo No Vital"],
"Reparar tubo de bajada":  					["NIVEL 3","168","Mínimo No Vital"],
"Reparar freno de motor de escape":  		["NIVEL 3","168","Mínimo No Vital"],
"Pastillas":  								["NIVEL 3","168","Mínimo Vital"],
"Zapatas":  								["NIVEL 3","168","Mínimo No Vital"],
"Inclinación":  							["NIVEL 3","168","Mínimo Vital"],
"Piston central de tapa ":  				["NIVEL 3","168","Mínimo No Vital"],
"Piston de direccion":  					["NIVEL 3","168","Mínimo No Vital"],
"Piston de Mastil":  						["NIVEL 3","168","Mínimo Vital"],
"Elevación central":  						["NIVEL 3","168","Mínimo Vital"],
"Porta uñas":  								["NIVEL 3","168","Mínimo Vital"],
"Parrilla":  								["NIVEL 3","168","Mínimo No Vital"],
"Rodamientos":  							["NIVEL 3","168","Mínimo No Vital"],
"Enllantar llanta delantero":  				["NIVEL 3","168","Mínimo No Vital"],
"Enllantar llanta posterior":  				["NIVEL 3","168","Mínimo No Vital"],
"Turbo":  									["NIVEL 3","168","Mínimo Vital"],
"Barra estabilizadora":  					["NIVEL 3","168","Mínimo No Vital"],
"Bujes":  									["NIVEL 3","168","Mínimo No Vital"],
"Muelles":  								["NIVEL 3","168","Mínimo Vital"],
"Topes":  									["NIVEL 3","168","Mínimo No Vital"],
"Cardán":  									["NIVEL 3","168","Mínimo No Vital"],
"Caja de cambios":  						["NIVEL 3","168","Mínimo No Vital"],
"Corona":  									["NIVEL 3","168","Mínimo No Vital"],
"Diferencial":  							["NIVEL 3","168","Mínimo No Vital"],
"Embrague":  								["NIVEL 3","168","Mínimo No Vital"],
"Fuga fluído":  							["NIVEL 3","168","Mínimo Vital"],
"Reparar parachoque delt":  				["NIVEL 3","168","Mínimo No Vital"],
"Reparar parachoque post":  				["NIVEL 3","168","Mínimo No Vital"],
"Reparar cortina":  						["NIVEL 4","720","Mínimo No Vital"],
"Sonido bajo de claxon":  					["NIVEL 3","168","Mínimo No Vital"],
"Porta tacos y conos":  					["NIVEL 3","168","Mínimo No Vital"],
"Reparar varilla seguro de carga":  		["NIVEL 3","168","Mínimo No Vital"],
"Reparar protector de carter":  			["NIVEL 3","168","Mínimo No Vital"],
"Corte superficial B/L":  					["NIVEL 4","720","Mínimo No Vital"],
"Reparar puente de chasis":  				["NIVEL 4","720","Mínimo No Vital"],
"Traccionar chasis":  						["NIVEL 4","720","Mínimo No Vital"],
"Pintado de unidad":  						["NIVEL 4","720","Mínimo No Vital"],
"Mica rajada":  							["NIVEL 4","720","Mínimo No Vital"],
"Plumilla resecado":  						["NIVEL 4","720","Mínimo No Vital"],
}
function cambioOpciones()
{
  var combo = document.getElementById('opciones');
  var opcion = combo.value;
  document.getElementById('nivelcorrectivo').value = opciones[opcion][0];
  document.getElementById('tiempo').value = opciones[opcion][1];
  document.getElementById('criticidad').value = opciones[opcion][2];
}
