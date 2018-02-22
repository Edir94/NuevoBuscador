$('.palabraClave').click(function(){
	$('#contenedorPautas').empty();
	var periodo = $('#selectPeriodo').val();
	var fechaInicio = $('#fechaInicioFavoritos').val();
	var fechaFin = $('#fechaFinFavoritos').val();
	var token = $('#token').val();
	var palabraClave = $(this).text();
	//alert($(this).text());
	$.ajax({
		type: "POST",
        url: "/buscarxPC",
        headers:{'X-CSRF-TOKEN': token},
        type: 'POST',
        dataType: 'json',
        data: {
            palabraClave:palabraClave,
            periodo:periodo,
            fechaInicio:fechaInicio,
            fechaFin:fechaFin
        },
        success: function(datos) {
        	//console.log(datos.length);
        	var i = 0;
        	while(i<datos.length){
        		var pauta = datos[i];
        		var texto = pauta['texto'];
        		if(texto.length>400){
        			texto = texto.substring(0,399)+"...";
        		}
        		if(pauta['nombrePrograma']==''){
        			medioprograma = pauta['nombreMedio'];
        		}else{
        			medioprograma = pauta['nombreMedio']+' / '+pauta['nombrePrograma'];
        		}
        		$('#contenedorPautas').append('<div class="row pauta" style="background-color: #D8D8D8; margin: 10px;">'+
	            				'<div class="checkPauta" style="float: left; width: 2%">'+
	            					'<input type="checkbox" name="checkPauta">'+
	            				'</div>'+
	            				'<div class="dataPauta" style="float: left; margin-left: 5px; margin-right: 5px; width: 92%">'+
	            					'<div>'+
			            				'<a href="#"><b style="color: blue;"><span>'+pauta['titular']+'</span></b></a>'+
			            			'</div>'+
			            			'<div style="text-align: justify;">'+
			            				'<span>'+texto+'</span>'+
			            			'</div>'+
			            			'<div>'+
			            				'<b><em><span>'+medioprograma+'</span></em></b>'+
			            			'</div>'+
	            				'</div>'+
	            				'<div class="opcionTipo" style="float: right; text-align: center; width: 1%; margin-left: 5px; margin-right: 10px;">'+
	            					'<div class="row">'+
		            					'<button class="btn-xs" style="width: 100%;">+</button>'+
	            					'</div>'+
	            					'<div class="row">'+
	            						'<button class="btn-xs" style="width: 100%;">=</button>'+
	            					'</div>'+
	            					'<div class="row">'+
	            						'<button class="btn-xs" style="width: 100%;">-</button>'+
	            					'</div>'+
	            				'</div>'+
            				'</div>');

        		i++;
        	}
        	$("#cabeceraPautas").text(palabraClave);
        	$("#hayClave").val(1);
        }
	});
});

$("#selectPeriodo").change(function(){
	$('#contenedorPautas').empty();
	var periodo = $(this).val();
	var fechaInicio = $('#fechaInicioFavoritos').val();
	var fechaFin = $('#fechaFinFavoritos').val();
	var token = $('#token').val();
	var palabraClave = $('#cabeceraPautas').text();
	var hayClave = $('#hayClave').val();
	var ruta = "/buscarxPC";
	if(hayClave==0){
		ruta = "/buscarxPeriodo";
	}
	$.ajax({
		type: "POST",
        url: ruta,
        headers:{'X-CSRF-TOKEN': token},
        type: 'POST',
        dataType: 'json',
        data: {
            palabraClave:palabraClave,
            periodo:periodo,
            fechaInicio:fechaInicio,
            fechaFin:fechaFin
        },
        success: function(datos) {
        	//console.log(datos.length);
        	var i = 0;
        	while(i<datos.length){
        		var pauta = datos[i];
        		var texto = pauta['texto'];
        		if(texto.length>400){
        			texto = texto.substring(0,399)+"...";
        		}
        		if(pauta['nombrePrograma']==''){
        			medioprograma = pauta['nombreMedio'];
        		}else{
        			medioprograma = pauta['nombreMedio']+' / '+pauta['nombrePrograma'];
        		}
        		$('#contenedorPautas').append('<div class="row pauta" style="background-color: #D8D8D8; margin: 10px;">'+
	            				'<div class="checkPauta" style="float: left; width: 2%">'+
	            					'<input type="checkbox" name="checkPauta">'+
	            				'</div>'+
	            				'<div class="dataPauta" style="float: left; margin-left: 5px; margin-right: 5px; width: 92%">'+
	            					'<div>'+
			            				'<a href="#"><b style="color: blue;"><span>'+pauta['titular']+'</span></b></a>'+
			            			'</div>'+
			            			'<div style="text-align: justify;">'+
			            				'<span>'+texto+'</span>'+
			            			'</div>'+
			            			'<div>'+
			            				'<b><em><span>'+pauta['tipoPauta']+' - '+medioprograma+'</span></em></b>'+
			            			'</div>'+
	            				'</div>'+
	            				'<div class="opcionTipo" style="float: right; text-align: center; width: 1%; margin-left: 5px; margin-right: 10px;">'+
	            					'<div class="row">'+
		            					'<button class="btn-xs" style="width: 100%;">+</button>'+
	            					'</div>'+
	            					'<div class="row">'+
	            						'<button class="btn-xs" style="width: 100%;">=</button>'+
	            					'</div>'+
	            					'<div class="row">'+
	            						'<button class="btn-xs" style="width: 100%;">-</button>'+
	            					'</div>'+
	            				'</div>'+
            				'</div>');

        		i++;
        	}
        }
	});
})

function AbrirPautaFavoritos(tipoMedio, idPauta){
    var ruta = "/vista"+tipoMedio+"/"+idPauta;
    window.open(ruta);
}