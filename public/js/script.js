$(function () {
	$('.dateRange').daterangepicker({
		locale:{
			format:'DD/MM/YYYY',
		},
		showDropdowns: false,
	});

	$('#tblResultados').DataTable({
        "language": {
            "url":"//cdn.datatables.net/plug-ins/1.10.12/i18n/Spanish.json"
        },
        "searching": true,
        //"paging": true,
      	//"lengthChange": false,
      	//"ordering": true,
      	//"info": true,
      	//"autoWidth": false
        "order": [[ 0, "desc" ]]
     });
	
});