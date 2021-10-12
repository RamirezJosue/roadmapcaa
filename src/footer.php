</main>
<?php
	 if (isset($bootstrapjs) && $bootstrapjs == 1){
		?>
		<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
		<script>window.jQuery || document.write('<script src="../assets/js/vendor/jquery.slim.min.js"><\/script>')</script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.9.0/feather.min.js"></script>
		<script src="../../js/bootstrap-slider.js"></script>
		
		<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
				<script>
					(function() {
						console.log("funcion anomima");
						console.log(document.getElementById("tiempo"));
						console.log(moment());
					})();

					(function reloj() {
						console.log("reloj");
							//Variables
							var now = moment(); //now
							var end = moment('2021-08-26 09:09:32');

							var {
								_data
							} = moment.duration(now.diff(end));



							hora = _data.hours,
							minuto = _data.minutes
							segundo = _data.seconds
							//Codigo para evitar que solo se vea un numero en los segundos
							comprobarsegundo = new String(segundo)
							if (comprobarsegundo.length == 1)
								segundo = "0" + segundo
							//Codigo para evitar que solo se vea un numero en los minutos
							comprobarminuto = new String(minuto)
							if (comprobarminuto.length == 1)
								minuto = "0" + minuto
							//Codigo para evitar que solo se vea un numero en las horas
							comprobarhora = new String(hora)
							if (comprobarhora.length == 1)
								hora = "0" + hora
							verhora = hora + " : " + minuto + " : " + segundo
							console.log(verhora);
							document.reloj_javascript.reloj.value = verhora
							setTimeout("reloj()", 1000)
						})();
				</script>
				<script>
			

					// Update the count down every 1 second
					var x = setInterval(function() {
							console.log("reloj");
							//Variables
							var now = moment(); //now
							var end = moment('2021-08-26 09:09:32');

							var {
								_data
							} = moment.duration(now.diff(end));



							hora = _data.hours,
							minuto = _data.minutes
							segundo = _data.seconds
							//Codigo para evitar que solo se vea un numero en los segundos
							comprobarsegundo = new String(segundo)
							if (comprobarsegundo.length == 1)
								segundo = "0" + segundo
							//Codigo para evitar que solo se vea un numero en los minutos
							comprobarminuto = new String(minuto)
							if (comprobarminuto.length == 1)
								minuto = "0" + minuto
							//Codigo para evitar que solo se vea un numero en las horas
							comprobarhora = new String(hora)
							if (comprobarhora.length == 1)
								hora = "0" + hora
							verhora = hora + " : " + minuto + " : " + segundo
							console.log(verhora);
							document.reloj_javascript.reloj.value = verhora
							setTimeout("reloj()", 1000)
					}, 1000);
					</script>
		
		<?php
    }
	 if (isset($mapasjs) && $mapasjs == 1){
		?>
        <script async defer
		  src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBhczrZ1XL_KbEoHlAd9z1cm0N3l-JPrCg&callback=initMap">
		</script>
		<?php
	}
	 if (isset($datatablesjs) && $datatablesjs == 1){
		?>		 
		  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css" />
		  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.7/css/responsive.dataTables.min.css"/>
		  <script type="text/javascript" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
		  <script type="text/javascript" src="<?php echo "datatables.js"; ?>"></script>
		<?php		  
	} 
	 if (isset($datatablesjsresponsive) && $datatablesjsresponsive == 1){
		?>		 
		  <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.7/js/dataTables.responsive.min.js"></script>
		<?php		  
	} 	
	if (isset($sweetalert) && $sweetalert == 1){
		?>
		 <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>		 
		<?php		  
	}  	 
?>
		<script type="text/javascript" src="<?php echo $accesos.".js"; ?>"></script>
</body>
</html>

