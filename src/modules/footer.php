</main>
<?php
	 if (isset($bootstrapjs) && $bootstrapjs == 1){
		?>
		<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
		<script>window.jQuery || document.write('<script src="../assets/js/vendor/jquery.slim.min.js"><\/script>')</script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.9.0/feather.min.js"></script>
		<script src="../../js/bootstrap-slider.js"></script>	
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
	if (isset($momentjs) && $momentjs == 1){
		?>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>		
		<?php		  
	}
?>
	<script src="<?php echo 'https://bk77.co/modules/'.$accesos.'/'.$accesos.'.js'; ?>"></script>
</body>
</html>

