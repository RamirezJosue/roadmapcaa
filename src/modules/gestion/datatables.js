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