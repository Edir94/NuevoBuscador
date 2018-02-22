@extends('layouts.app')

@section('content')
<div class="container-fluid" style="justify-content: center; display: flex;">
    <div class="col-xs-10">
        <div class="col-xs-3">
            <div class="panel panel-default" style="height: 500px;">
            	<div class="panel-heading">
            		Temas y Palabras Claves
            	</div>
            	<div class="panel-body" style="background-color: #585858;">
            		@foreach($temas as $tema)
            		<div class="panel panel-default" style="border: none;">
            			<div class="divTema" style="background-color: #585858; color: white;">
	            			<span style="margin-left: 10px;"><i class="fa fa-folder" aria-hidden="true"></i> {{ $tema->nombreTema }} </span>
	            		</div>
	            		<div class="panel panel-default" style="background-color: #585858; color: white;">
	            			@foreach($palabrasClave as $palabra)
		            			@if($palabra->idTema==$tema->idTema)
		            			<div class="divPalabrasClave">
			            			<a href="#" style="color: white; margin-left: 20px;"><span class="palabraClave"><i class="fa fa-commenting-o" aria-hidden="true"></i> {{ $palabra->palabraClave }}</span></a>
			            		</div>
			            		@endif
		            		@endforeach
	            		</div>
            		</div>
            		@endforeach
            	</div>
            </div>
        </div>
        <div class="col-xs-9">
            <div class="panel panel-default">
            	<div class="panel-heading">
            		<b id="cabeceraPautas">Zona de pautas</b>
            		<input type="hidden" name="hayClave" id="hayClave" value="0">
            	</div>
            	<div class="panel-body">
            		<div class="filtros">
            			
            		</div>
            		<div class="">
            			<div class="row" style="border: solid 1px; background-color: gray; margin: 5px;">
            				<div style="margin: 5px;" class="form-inline">
            					<div class="form-group" style="margin-left: 5px;">
            						<label>Periodo:</label>
									<div class="input-group">
										<select id="selectPeriodo" class="form-control">
											<option value="0">Hoy</option>
											<option value="1">Ayer</option>
											<option value="2">Últimos 7 días</option>
											<option value="3">Últimos 30 días</option>
											<option value="4">Seleccionar Período</option>
										</select>
									</div>
            					</div>
	            				<div class="form-group" id="divFecha" style="margin-left: 5px; display: none;">
									<label>Fecha:</label>
									<div class="input-group col-xs-4">
									    <input type="text" class="form-control pull-right dateRange" id="fechaInicioFavoritos" style="text-align: center;">
									    <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
									</div><!-- /.input group -->
									<div class="input-group col-xs-4">
									    <input type="text" class="form-control pull-right dateRange" id="fechaFinFavoritos" style="text-align: center;">
									    <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
									</div><!-- /.input group -->
								</div><!-- /.form group -->
							</div>
            			</div>
            			<div class="row" style="margin-top: 10px; margin: 10px; border: solid 1px; height: 700px; overflow-y: scroll;" id="contenedorPautas">
            				@foreach($pautas as $pauta)
            				<div class="row pauta" style="background-color: #D8D8D8; margin: 10px;">
	            				<div class="checkPauta" style="float: left; width: 2%">
	            					<input type="checkbox" name="checkPauta">
	            				</div>
	            				<div class="dataPauta" style="float: left; margin-left: 5px; margin-right: 5px; width: 92%">
	            					<div>
			            				<a href="#"><b style="color: blue;"><span>{{ $pauta['titular'] }}</span></b></a>
			            			</div>
			            			<div style="text-align: justify;">
			            				@if(strlen($pauta['texto'])>400)
			            					<span>{{ substr($pauta['texto'],0,400)."..." }}</span>
			            				@else
			            					<span>{{ $pauta['texto'] }}</span>
			            				@endif
			            			</div>
			            			<div>
			            				<b><em><span>{{ $pauta['tipoPauta'] }} - {{ $pauta['nombreMedio'] }} / {{ $pauta['nombrePrograma'] }}</span></em></b>
			            			</div>
	            				</div>
	            				<div class="opcionTipo" style="float: right; text-align: center; width: 1%; margin-left: 5px; margin-right: 10px;">
	            					<div class="row">
		            					<button class="btn-xs" style="width: 100%;">+</button>	
	            					</div>
	            					<div class="row">
	            						<button class="btn-xs" style="width: 100%;">=</button>
	            					</div>
	            					<div class="row">
	            						<button class="btn-xs" style="width: 100%;">-</button>
	            					</div>
	            				</div>
            				</div>
            				@endforeach
            			</div>
            			<div class="">
            			<div class="row" style="border: solid 1px; background-color: gray; margin: 5px;">
            				<div style="margin: 5px;" class="form-inline">
            					<div class="form-group" style="margin-left: 5px;">
            						<button>Enviar</button>
            					</div>
	            				<div class="form-group" id="divFecha" style="margin-left: 5px; display: none;">
									<label>Fecha:</label>
									<div class="input-group">
									    <input type="text" class="form-control pull-right dateRange" id="rangoFecha" style="text-align: center;">
									    <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
									</div><!-- /.input group -->
								</div><!-- /.form group -->
							</div>
            			</div>
            		</div>
            	</div>
            </div>
        </div>
    </div><!-- ./row -->
    
</div>
@endsection