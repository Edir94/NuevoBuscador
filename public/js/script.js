$(function () {
    $('.dateRange').daterangepicker({
        locale:{
            format:'DD/MM/YYYY',
        },
        showDropdowns: false,
    });

    var oTableResultados = $('#tblResultados').DataTable({
        "language": {
            "url":"//cdn.datatables.net/plug-ins/1.10.12/i18n/Spanish.json"
        },
        "processing": true,
        //"serverSide": true,
        "searching": true,
        "ajax": {
            "url":"/api/Busqueda",
            "data":{

            },
            "datatype":"JSON"
        },
        "columns":[
            {data:'fechaPauta',name:'fechaPauta'},
            {data:'tipoPauta',name:'tipoPauta'},
            {data:'nombreMedio',name:'nombreMedio'},
            {data:'nombreSP',name:'nombreSP'},
            {data:'titular',name:'titular'},
            //{data: 'opciones', name: 'opciones', orderable: false, searchable: false}

        ],
        "order": [[ 0, "desc" ]]
     });

    $('#tblResultados tbody').on('click','tr',function(){
        var pauta = $('td',this).eq(1).text();
        alert(pauta);
    });

    $("#textoBusqueda").tokenfield();
    $("#medioBusqueda").tokenfield({
        autocomplete:{
            source:"search/medios",
        },
        showAutocompleteOnFocus: true
    });
    
});

function BuscarPautas()
{
    var textos = $('#textoBusqueda').val();
    var medios = $('#medioBusqueda').val();
    var token = $('#token').val();
    //console.log(textos+medios);
    $('#tblResultados').DataTable().clear().destroy();

    var oTableResultados = $('#tblResultados').DataTable({
        "language": {
            "url":"//cdn.datatables.net/plug-ins/1.10.12/i18n/Spanish.json"
        },
        "processing": true,
        //"serverSide": true,
        "searching": true,
        "ajax": {
            "url":"/api/Busqueda",
            "data":{
                textos:textos,
                medios:medios
            },
            "datatype":"JSON"
        },
        "columns":[
            {data:'fechaPauta',name:'fechaPauta'},
            {data:'tipoPauta',name:'tipoPauta'},
            {data:'nombreMedio',name:'nombreMedio'},
            {data:'nombreSP',name:'nombreSP'},
            {data:'titular',name:'titular'},
            //{data: 'opciones', name: 'opciones', orderable: false, searchable: false}

        ],
        "order": [[ 0, "desc" ]]
     });
}