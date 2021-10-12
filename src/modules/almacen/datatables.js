$('#asistenciarutinat2').DataTable( {
    responsive: true,
	 "searching": false,
        "paging":   false,
        "info":     false,
		"autoWidth": true
} );
$('#reclamoinicio').DataTable( {
    responsive: false,
	 "searching": false,
        "paging":   false,
        "info":     false,
		"autoWidth": true
} );
$('#resultckldt').DataTable( {
    responsive: true,
	 "searching": true,
        "paging":   true,
        "info":     true,
		"autoWidth": true,
    "columnDefs": [
        {"className": "dt-center", "targets": "_all"}
      ]		
} );
  $('#iniciochecklist').DataTable( {
    responsive: true,
	 "searching": true,
        "paging":   true,
        "info":     true,
		"autoWidth": true
} );
$(document).ready(function() {
	$('#example').DataTable({
		"columnDefs": [{
			"targets": 0
		}],
		language: {
			"sProcessing": "Procesando...",
			"sLengthMenu": "Mostrar _MENU_ resultados",
			"sZeroRecords": "No se encontraron resultados",
			"sEmptyTable": "Ningún dato disponible en esta tabla",
			"sInfo": "Mostrando resultados _START_-_END_ de  _TOTAL_",
			"sInfoEmpty": "Mostrando resultados del 0 al 0 de un total de 0 registros",
			"sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
			"sSearch": "Buscar:",
			"sLoadingRecords": "Cargando...",
			"oPaginate": {
				"sFirst": "Primero",
				"sLast": "Último",
				"sNext": "Siguiente",
				"sPrevious": "Anterior"
			},
		}
	});
});
	$(document).ready(function() {
	  $('#delta').DataTable( {
			responsive: true,	
			columnDefs: [
				{ responsivePriority: 1, targets: 2 },
				{ responsivePriority: 10001, targets: 4 },
				{ responsivePriority: 2, targets: 4 },
				{"className": "dt-center", "targets": "_all"}
			]
		} );
	} );
	$(document).ready(function() {
	  $('#transclienresponsive').DataTable( {
			responsive: true,
			columnDefs: [
				{ responsivePriority: 1, targets: 0 },
				{ responsivePriority: 10001, targets: 3 },
				{ responsivePriority: 2, targets: -1 }
			]
		} );
	} );
	$(document).ready(function() {
		$('#transportesresponsive').DataTable( {
			responsive: {
				details: {
					type: 'column'
				}
			},
			columnDefs: [ {
				className: 'dtr-control',
				orderable: true,
				targets:   0
			} ],
			order: [ 1, 'asc' ]
		} );
	} );
	
	$(document).ready(function() {
		$('#depositosclientes').DataTable( {
			responsive: {
				details: {
					type: 'column'
				}
			},
			columnDefs: [ {
				className: 'dtr-control',
				orderable: true,
				targets:   0
			} ],
			order: [ 1, 'asc' ]
		} );
	} );
	
$('#asistenciarutinat2').DataTable( {
    responsive: false,
	 "searching": false,
        "paging":   false,
        "info":     false,
		"autoWidth": true
} );
$('#asistenciarutinat2detalle').DataTable( {
    responsive: true,
	 "searching": false,
        "paging":   false,
        "info":     false,
		"autoWidth": true
} );
$('#ejecuciondereparto').DataTable( {
    responsive: false,
	 "searching": false,
        "paging":   false,
        "info":     false,
		"autoWidth": true,
    "columnDefs": [
        {"className": "dt-center", "targets": "_all"}
      ]		
} );
$('#ejecucionderepartoempresa').DataTable( {
    responsive: false,
	 "searching": false,
        "paging":   false,
        "info":     false,
		"autoWidth": true,
    "columnDefs": [
        {"className": "dt-center", "targets": "_all"}
      ]		
} );

$('#ejecucionderepartodia').DataTable( {
    responsive: false,
	 "searching": false,
        "paging":   false,
        "info":     false,
		"autoWidth": true,
    "columnDefs": [
        {"className": "dt-center", "targets": "_all"}
      ]		
} );

$('#tdlicenciasconducir').DataTable( {
    responsive: false,
	 "searching": true,
        "paging":   true,
        "info":     true,
		"autoWidth": true,		
} );   
$('#alamcencheck').DataTable( {
    responsive: true,
	 "searching": false,
        "paging":   false,
        "info":     false,
		"autoWidth": true
} ); 