
		$('input[type="file"]').on('change', function(){
            var ext = $( this ).val().split('.').pop();
            if ($( this ).val() != '') {
            if(ext == "xls" || ext == "xlsx" || ext == "csv"){
            }
            else
            {
                $( this ).val('');
                Swal.fire("Mensaje De Error","Extensión no permitida: " + ext+"","error");
            }
            }
        });
