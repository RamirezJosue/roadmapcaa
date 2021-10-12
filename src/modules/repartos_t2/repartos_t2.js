	// Example starter JavaScript for disabling form submissions if there are invalid fields
	(function() {
	  'use strict';
	  window.addEventListener('load', function() {
		// Fetch all the forms we want to apply custom Bootstrap validation styles to
		var forms = document.getElementsByClassName('needs-validation');
		// Loop over them and prevent submission
		var validation = Array.prototype.filter.call(forms, function(form) {
		  form.addEventListener('submit', function(event) {
			if (form.checkValidity() === false) {
			  event.preventDefault();
			  event.stopPropagation();
			}
			form.classList.add('was-validated');
		  }, false);
		});
	  }, false);
	})();
	
		   function comprobar_check_enviar(obj)
		{   
		if (obj.checked){  
	document.getElementById('boton_ck_env').style.display = "";
		} else{  
	document.getElementById('boton_ck_env').style.display = "none";
		}     
		}
		   (function() {
			 var form = document.getElementById('ruta_detalle');
			 form.addEventListener('submit', function(event) {
			   // si es false entonces que no haga el submit
			   if (!confirm('Realmente desea continuar ?')) {
				 event.preventDefault();
			   }
			 }, false);
		   })();

