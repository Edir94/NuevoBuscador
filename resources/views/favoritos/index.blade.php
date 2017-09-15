@extends('layouts.app')

@section('content')
<div class="container">

	<div class="box">
		<div class="box-header">
			<h2 class="box-title">Temas de Interés</h2>
		</div>

		<div class="box-body">
			<div class="input-group col-xs-12">
				<div class="input-group col-xs-3 col-md-offset-4">
					<div class="input-group-addon">
						<strong>Archivo: </strong>
					</div>
					<input class="form-control pull-right datePicker" type="date" name="fecha_portada">
					<div class="input-group-addon">
						<i class="fa fa-calendar"></i>
					</div>
				</div>
				<div class="col-xs-5"> 
					<a href="#">
						<h3 class="col-xs-5"><span class="glyphicon glyphicon-plus-sign"></span>
						Favoritos</h3>
					</a> 
				</div>
			</div>

			<div id="contenedorFavorito">
				<div class="panel">
					<div class="panel-body panel-favoritos">
						<div class="panel-group connected-sortable droppable-area1 ui-sortable" id="lista">
							<div class="panel panel-default ui-sortable-handle">
								<div class="panel-heading">Hola
									<a href="#">
										<span class="badge badge-info" style="float:right">Resultados:
										</span>
									</a>
								</div>
							</div>
						</div>
					</div>	
				</div>
			</div>
			
		</div>			
	</div>


	    	


	    
</div>
@endsection