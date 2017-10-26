@extends('layouts.app')

@section('content')
@include('favoritos.add')
<div class="container">

	<div class="box">
		<div class="box-header">
			<h2 class="box-title">Temas de Interés</h2>
		</div>

		<div class="box-body">
			<div class="input-group col-xs-12">
				<div class="input-group col-xs-3 col-md-offset-4 date" id="datePickerStart">
					<div class="input-group-addon">
						<strong>Archivo: </strong>
					</div>
					<input class="form-control pull-right datePicker" type="text" name="fecha" id="fecha">
					<div class="input-group-addon">
						<i class="fa fa-calendar"></i>
					</div>
				</div>
				<div class="col-xs-5"> 
					<h3>
						<a href="#" data-target="#modalAgregarFavorito" data-toggle="modal" id="btnModalAgregar">
							<span class="glyphicon glyphicon-plus-sign">
								
							</span>
							Favoritos
						</a>
					</h3>
				</div>
			</div>
		</div>
	</div>
	<div class="container" id="contenedorFavoritos">
		<div class="panel">
			<div id="contenedor">
				<div class="panel-group" id="contenedor2">


				</div>
			</div>
		</div>
	</div>


	    	


	    
</div>

@endsection


