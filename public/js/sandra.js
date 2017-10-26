$(window).on('load',function(){
	cargarfavorito();
});


//document.write(cargarfavorito());

function cargarfavorito(){
	  var route = 'temas';

	  $.get(route,function(data){

   		$.each(data,function(index,value){

   			$('#contenedor2').append('<div class="panel panel-default"><div class="panel-heading"><h4 class="panel-title"><a href="#contenedorPalabras'+value.idTema+'" data-toggle="collapse" id="tema'+value.idTema+'">'+value.nombreTema+'</a></h4></div><div id="contenedorPalabras'+value.idTema+'" class="panel-collapse collapse"></div></div>');
   			
   			cargarPalabrasClaves(value.idTema);

   			//$.get(route.function);

	    });
	    
	  });
}


function cargarPalabrasClaves(id){

	var contenedor3 = id;

	var route = 'claves/'+id;

   	$.get(route,function(data){

		$.each(data,function(index,value){
			//alert(contenedor3);
			$('#contenedorPalabras'+contenedor3).append('<div class="panel-body"><div class="col-xs-12"><div class="col-xs-6"><label id="palabra'+value.id+'">'+value.palabraClave+'</label></div><div class="col-xs-6" align="right"><span class="badge">21</span></div></div></div>');
		});   				
   	});

}


// --- TEMAS --- //

$('.text-temas').tokenfield({
    limit:1,
    showAutocompleteOnFocus: true
  });
$('.text-temas').on('tokenfield:createtoken', function (event) {
    var existingTokens = $(this).tokenfield('getTokens');
    $.each(existingTokens, function(index, token) {
        if (token.value === event.attrs.value)
            event.preventDefault();
    });
});

// --- TEMAS --- //

$('.text-claves').tokenfield({
    showAutocompleteOnFocus: true
  });
$('.text-claves').on('tokenfield:createtoken', function (event) {
    var existingTokens = $(this).tokenfield('getTokens');
    $.each(existingTokens, function(index, token) {
        if (token.value === event.attrs.value)
            event.preventDefault();
    });
});


$('#btnAgregarFvorito').click(function(){
	 var tema = $('#tema1').val();
	 var palabraClave = $('#pClave1').val();

	 if(tema != "" && palabraClave != ""){
	 	console.log(tema);
	 	console.log(palabraClave);
	 }else{
	 	console.log("malooo");
	 }

});

/*
$('#btnAgregar').click(function(){
	$('#idFavorito').append('<div class="col-xs-12"><div id="idTema" class="col-xs-5 col-sm-5 col-md-5""><input type="text" class="col-xs-12 col-sm-12 col-md-12" name="tema1" id="tema1"  value=""></div><div id="idPalabraClave" class="col-xs-5 col-sm-5 col-md-5""><input type="text" class="col-xs-12 col-sm-12 col-md-12" name="pClave1" id="pClave1" value=""></div><div class="col-xs-2 col-md-1"><button type="button" class="btn-del-temas btn btn-default btn-xs" id="btnEliminar"><span class="glyphicon glyphicon-minus"></span></button></div></div>');
});*/

function btnAgregar(obj){
	var valor = $('#idFavorito > .palabraClaves > .div-eliminar').length;
	$('#idFavorito').append('<div class="item form-group col-xs-12"><div id="idTema" class="col-xs-5 col-sm-5 col-md-5""><input type="text" class="col-xs-12 col-sm-12 col-md-12 text-temas" name="tema'+valor+'" id="tema'+valor+'"  value=""></div><div id="idPalabraClave" class="col-xs-5 col-sm-5 col-md-5""><input type="text" class="col-xs-12 col-sm-12 col-md-12 text-claves" name="pClave'+valor+'" id="pClave'+valor+'" value=""></div><div class="col-xs-2 col-md-1"><button type="button" class="btn-del-temas btn btn-default btn-xs" id="btnEliminar"><span class="glyphicon glyphicon-minus"></span></button></div></div>');
	//alert(valor);


}

function btnEliminar(obj){

}
