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
            //"url":"/api/Busqueda",
            "url":"/api/Busqueda24",
            "data":{

            },
            "datatype":"JSON"
        },
        "columns":[
            {data:'checkPauta',name:'checkPauta'},
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

$('#medioBusqueda').click(function(){
    var id="";
    if($('#checkPrensa').val()==1){
        id = id+"1";
    }
    if($('#checkTv').val()==1){
        id = id+"2";
    }
    if($('#checkRadio').val()==1){
        id = id+"3";
    }
    if($('#checkInternet').val()==1){
        id = id+"4";
    }
    $("#medioBusqueda").tokenfield({
        autocomplete:{
            //source:"search/medios/"+id,
            source:"search/medios24/"+id,
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

    var filtroMedios = 0;
    if(medios!=""){
        filtroMedios = 1;
    }

    if($('#checkPrensa').val()==1){
        checkPrensa = true;
    }else{
        checkPrensa = false;
    }
    if($('#checkTv').val()==1){
        checkTv = true;
    }else{
        checkTv = false;
    }
    if($('#checkRadio').val()==1){
        checkRadio = true;
    }else{
        checkRadio = false;
    }
    if($('#checkInternet').val()==1){
        checkInternet = true;
    }else{
        checkInternet = false;
    }

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
            //"url":"/api/Busqueda2",
            "url":"/api/BusquedaAv24",
            "data":{
                fechaInicio:fechaInicio,
                fechaFin:fechaFin,
                textos:textos,
                medios:medios,
                fechaInicio:fechaInicio,
                fechaFin:fechaFin,
                checkPrensa:checkPrensa,
                checkTv:checkTv,
                checkRadio:checkRadio,
                checkInternet:checkInternet,
                filtroMedios:filtroMedios
            },
            "datatype":"JSON"
        },
        "columns":[
            {data:'checkPauta',name:'checkPauta'},
            {data:'fechaPauta',name:'fechaPauta'},
            {data:'tipoPauta',name:'tipoPauta'},
            {data:'nombreMedio',name:'nombreMedio'},
            {data:'nombreSP',name:'nombreSP'},
            {data:'titular',name:'titular'},
            {data: 'opciones', name: 'opciones', orderable: false, searchable: false}

        ],
        initComplete: function() {
            cargarMediosFiltrado();
        },
        "order": [[ 0, "desc" ]]
     });

    $('#contenedorBusqueda').attr('class','panel-collapse collapse');
    $('#iDesplegable').attr('class','glyphicon glyphicon-triangle-bottom');
    $('#FiltroRapido').show();
}

$('#desplegable').click(function(){
    var value = $('#iDesplegable').attr('class');
    if(value == 'glyphicon glyphicon-triangle-bottom'){
        $('#iDesplegable').attr('class','glyphicon glyphicon-triangle-top');
    }else{
        $('#iDesplegable').attr('class','glyphicon glyphicon-triangle-bottom');
    }
});

function AbrirPauta(id)
{
    var data = id.value.split('-');
    var tipoPauta = data[0];
    var id = data[1];
    var ruta = "/vista"+tipoPauta+"/"+id;
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
    
$('.btnFiltro').click(function(){
    var id = $(this).attr('id');
    if($('#'+id).val()==1){
        $('#'+id).attr('class','btn btn-default btn-block btnFiltro');
        $('#'+id).val(0);
    }else{
        $('#'+id).attr('class','btn btn-info btn-block btnFiltro');
        $('#'+id).val(1);
    }
    filtradoRapidoMedios();
});

function cargarMediosFiltrado(){
    $('#contenedorMedios').empty();
    $.get('/mediosFiltrado24',function(data){
        var i=0;
        $.each(data,function(index,value){
            i++;
            $('#contenedorMedios').append('<button type="button" class="btn btn-info btn-block btnFiltro" id="medioFiltro'+i+'" name="medioFiltro'+i+'" value="1">'+value+'</button>');
            $('#numeroMedios').val(i);
        });
    }).done(function(){
        $('.btnFiltro').click(function(){
            var id = $(this).attr('id');
            if($('#'+id).val()==1){
                $('#'+id).attr('class','btn btn-default btn-block btnFiltro');
                $('#'+id).val(0);
            }else{
                $('#'+id).attr('class','btn btn-info btn-block btnFiltro');
                $('#'+id).val(1);
            }
            filtradoRapidoMedios();
        });
    });
}

function filtradoRapidoBusqueda(){
    var filtroPrensa;
    var filtroTv;
    var filtroRadio;
    var filtroInternet;

    if($('#btnPrensa').val()==1){
        filtroPrensa = true;
    }else{
        filtroPrensa = false;
    }
    if($('#btnTv').val()==1){
        filtroTv = true;
    }else{
        filtroTv = false;
    }
    if($('#btnRadio').val()==1){
        filtroRadio = true;
    }else{
        filtroRadio = false;
    }
    if($('#btnInternet').val()==1){
        filtroInternet = true;
    }else{
        filtroInternet = false;
    }
    var numeroMedios = $('#numeroMedios').val();

    var arrayMedios = new Array();
    var i=1;
    while(i<(numeroMedios+1)){
        if($('#medioFiltro'+i).val()==0){
            var nombreMedio = $("#medioFiltro"+i).text();
            arrayMedios.push(nombreMedio);
        }
        i++;
    }

    $('#tblResultados').DataTable().clear().destroy();
    var oTableResultados = $('#tblResultados').DataTable({
        "language": {
            "url":"//cdn.datatables.net/plug-ins/1.10.12/i18n/Spanish.json"
        },
        "processing": true,
        //"serverSide": true,
        "searching": true,
        "ajax": {
            "url":"/api/FiltroRapido24",
            "data":{
                filtroPrensa:filtroPrensa,
                filtroTv:filtroTv,
                filtroRadio:filtroRadio,
                filtroInternet:filtroInternet,
                arrayMedios:arrayMedios
            },
            "datatype":"JSON"
        },
        "columns":[
            {data:'checkPauta',name:'checkPauta'},
            {data:'fechaPauta',name:'fechaPauta'},
            {data:'tipoPauta',name:'tipoPauta'},
            {data:'nombreMedio',name:'nombreMedio'},
            {data:'nombreSP',name:'nombreSP'},
            {data:'titular',name:'titular'},
            {data: 'opciones', name: 'opciones', orderable: false, searchable: false}

        ],
        initComplete: function() {
            cargarMediosFiltrado();
        },
        "order": [[ 0, "desc" ]]
     });
}

$('.btnFiltroTipo').click(function(){
    var id = $(this).attr('id');
    if($('#'+id).val()==1){
        $('#'+id).attr('class','btn btn-default btnFiltroTipo');
        $('#'+id).val(0);
    }else{
        $('#'+id).attr('class','btn btn-warning btnFiltroTipo');
        $('#'+id).val(1);
    }
    filtradoRapidoBusqueda();
});

$('.btnBusquedaTipo').click(function(){
    var id = $(this).attr('id');
    if($('#'+id).val()==1){
        $('#'+id).attr('class','btn btn-default btnBusquedaTipo');
        $('#'+id).val(0);
    }else{
        $('#'+id).attr('class','btn btn-warning btnBusquedaTipo');
        $('#'+id).val(1);
    }
});

function filtradoRapidoMedios(){
    var filtroPrensa;
    var filtroTv;
    var filtroRadio;
    var filtroInternet;

    if($('#btnPrensa').val()==1){
        filtroPrensa = true;
    }else{
        filtroPrensa = false;
    }
    if($('#btnTv').val()==1){
        filtroTv = true;
    }else{
        filtroTv = false;
    }
    if($('#btnRadio').val()==1){
        filtroRadio = true;
    }else{
        filtroRadio = false;
    }
    if($('#btnInternet').val()==1){
        filtroInternet = true;
    }else{
        filtroInternet = false;
    }
    var numeroMedios = $('#numeroMedios').val();

    var arrayMedios = new Array();
    var i=1;
    while(i<(numeroMedios+1)){
        if($('#medioFiltro'+i).val()==0){
            var nombreMedio = $("#medioFiltro"+i).text();
            arrayMedios.push(nombreMedio);
        }
        i++;
    }

    $('#tblResultados').DataTable().clear().destroy();
    var oTableResultados = $('#tblResultados').DataTable({
        "language": {
            "url":"//cdn.datatables.net/plug-ins/1.10.12/i18n/Spanish.json"
        },
        "processing": true,
        //"serverSide": true,
        "searching": true,
        "ajax": {
            "url":"/api/FiltroRapido24",
            "data":{
                filtroPrensa:filtroPrensa,
                filtroTv:filtroTv,
                filtroRadio:filtroRadio,
                filtroInternet:filtroInternet,
                arrayMedios:arrayMedios
            },
            "datatype":"JSON"
        },
        "columns":[
            {data:'checkPauta',name:'checkPauta'},
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

function CheckKey(e){
    var code = e.keyCode ? e.keyCode : e.which;
    if(code === 13){
        BuscarPautas();
    }
}

document.onkeypress = function(){
    var tecla;
    tecla = (document.all) ? event.keyCode : event.which;
    if(tecla == 13){
        BuscarPautas();
    }
}

function cambiarValorPauta(id){
    //alert(id.value);
}