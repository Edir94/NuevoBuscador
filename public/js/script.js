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
            {data: 'opciones', name: 'opciones', orderable: false, searchable: false}

        ],
        "order": [[ 0, "desc" ]]
     });

    $('.datePicker').daterangepicker({
        singleDatePicker: true,
        locale:{
            format:'DD/MM/YYYY',
        },
        showDropdowns: false,
    });



    /*$('#tblResultados tbody').on('click','tr',function(){
        var pauta = $('td',this).eq(5).text();
        alert(pauta);
        //window.open('/vistaPrensa');
    });*/
});

$("#textoBusqueda").tokenfield();
$("#medioBusqueda").tokenfield({
    autocomplete:{
        source:"search/medios",
        delay:100,
        minLength: 1,
    },
    showAutocompleteOnFocus: true
});
$('#medioBusqueda').on('tokenfield:createtoken', function (event) {
    var existingTokens = $(this).tokenfield('getTokens');
    $.each(existingTokens, function(index, token) {
        if (token.value === event.attrs.value)
            event.preventDefault();
    });
});

function BuscarPautas()
{
    var textos = $('#textoBusqueda').val();
    var medios = $('#medioBusqueda').val();
    var fechas = $('#rangoFecha').val().split(' - ');
    var fechaInicio = fechas[0];
    var fechaFin = fechas[1];
    var checkPrensa;
    var checkTv;
    var checkRadio;
    var checkInternet;
    if($('#checkPrensa').is(':checked')){
        checkPrensa = true;
    }else{
        checkPrensa = false;
    }
    if($('#checkTv').is(':checked')){
        checkTv = true;
    }else{
        checkTv = false;
    }
    if($('#checkRadio').is(':checked')){
        checkRadio = true;
    }else{
        checkRadio = false;
    }
    if($('#checkInternet').is(':checked')){
        checkInternet = true;
    }else{
        checkInternet = false;
    }

    //alert(checkPrensa + "/"+ checkTv + "/"+ checkRadio + "/"+checkInternet);
    var token = $('#token').val();
    $('#tblResultados').DataTable().clear().destroy();

    var oTableResultados = $('#tblResultados').DataTable({
        "language": {
            "url":"//cdn.datatables.net/plug-ins/1.10.12/i18n/Spanish.json"
        },
        "processing": true,
        //"serverSide": true,
        "searching": true,
        "ajax": {
            "url":"/api/Busqueda2",
            "data":{
                fechaInicio:fechaInicio,
                fechaFin:fechaFin,
                textos:textos,
                medios:medios,
                fechaInicio:fechas[0],
                fechaFin:fechas[1],
                checkPrensa:checkPrensa,
                checkTv:checkTv,
                checkRadio:checkRadio,
                checkInternet:checkInternet
            },
            "datatype":"JSON"
        },
        "columns":[
            {data:'fechaPauta',name:'fechaPauta'},
            {data:'tipoPauta',name:'tipoPauta'},
            {data:'nombreMedio',name:'nombreMedio'},
            {data:'nombreSP',name:'nombreSP'},
            {data:'titular',name:'titular'},
            {data: 'opciones', name: 'opciones', orderable: false, searchable: false}

        ],
        "order": [[ 0, "desc" ]]
     });
}

function AbrirPauta(id)
{
    //alert(id.value);
    var data = id.value.split('-');
    var tipoPauta = data[0];
    var id = data[1];
    var ruta = "/vista"+tipoPauta+"/"+id;
    //alert(tipoPauta + " ----- "+id);
    window.open(ruta);
}


$('#btnMostrarTexto').click(function() {
    $('#btnMostrarTexto').hide();
    $('#btnMostrarImagen').show();
    $('#imagenPauta').hide();
    $('#textoPauta').show();

});

$('#btnMostrarImagen').click(function() {
    $('#btnMostrarImagen').hide();
    $('#btnMostrarTexto').show();
    $('#textoPauta').hide();
    $('#imagenPauta').show();

});
    
