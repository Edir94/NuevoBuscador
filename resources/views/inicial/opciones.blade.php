<div class="panel panel-default">
    <div class="panel-heading">
    	<a href="#contenedorBusqueda" data-toggle="collapse">Buscar por:</a>
	</div>
    <div class="panel-collapse collapse in" id="contenedorBusqueda">
	    <div class="panel-body">
	        @if (session('status'))
	            <div class="alert alert-success">
	                {{ session('status') }}
	            </div>
	        @endif
	        <div class="row">
				<div class="form-group col-md-12 col-lg-12 col-sm-12 col-xs-12">
					<label>Fecha:</label>
					<div class="input-group">
					    <input type="text" class="form-control pull-right dateRange" id="rangoFecha">
					    <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
					</div><!-- /.input group -->
				</div><!-- /.form group -->
			</div><!-- /.row -->
			<div class="row">
				<div class="form-group col-md-12 col-lg-12 col-sm-12 col-xs-12">
					<label>Tipo de Medio:</label>
					<div class="input-group">
						<div class="row">
							<div class="col-md-3 col-xs-3">
								<a href="#" data-toggle="tooltip" data-placement="top" title="Prensa"><button type="button" class="btn btn-warning btnBusquedaTipo" id="checkPrensa" value="1">PR</button></a>
							</div>
							<div class="col-md-3 col-xs-3">
								<a href="#" data-toggle="tooltip" data-placement="top" title="Televisión"><button type="button" class="btn btn-warning btnBusquedaTipo" id="checkTv" value="1">TV</button></a>
							</div>
							<div class="col-md-3 col-xs-3">
								<a href="#" data-toggle="tooltip" data-placement="top" title="Radio"><button type="button" class="btn btn-warning btnBusquedaTipo" id="checkRadio" value="1">RA</button></a>
							</div>
							<div class="col-md-3 col-xs-3">
								<a href="#" data-toggle="tooltip" data-placement="top" title="Internet"><button type="button" class="btn btn-warning btnBusquedaTipo" id="checkInternet" value="1">IN</button></a>
							</div>
						</div>
					</div><!-- /.input group -->
				</div><!-- /.form group -->
			</div><!-- /.row -->
			<div class="row">
				<div class="form-group col-md-12 col-lg-12 col-sm-12 col-xs-12">
					<label>Medio:</label>
					<!--{/{ Form::text('medioBusqueda', null, ['id' =>  'medioBusqueda', 'placeholder' =>  'Escriba un medio','class'=>'form-control'])}}-->
					<input type="text" class="form-control" id="medioBusqueda" name="medioBusqueda" placeholder="Escriba un medio" onkeyup="CheckKey(event)">
				</div><!-- /.form group -->
			</div><!-- /.row -->
	        <div class="row">
				<div class="form-group col-md-12 col-lg-12 col-sm-12 col-xs-12">
					<label>Texto:</label>
					<div class="input-group col-md-12">
						<a href="#" data-tooltip=" 'O' = 'TAB' " data-placement="bottom"><input type="text" class="form-control" id="textoBusqueda" name="textoBusqueda" placeholder="Ingrese texto a buscar" onkeyup="CheckKey(event)"></a>
					</div><!-- /.input group -->
				</div><!-- /.form group -->
			</div><!-- /.row -->

			<div class="form-group col-lg-12 col-md-10 col-sm-10 col-xs-12">                        
				<button type="submit" id="BusquedaAvanzada" onclick="BuscarPautas();" class="btn btn-primary">Buscar</button>
			</div><!-- /.form group -->
	    </div><!-- /.panel body -->
    </div><!-- /.contenedorBusqueda-->
    
</div><!-- /.panel default -->
<div class="panel panel-default" style="display: none;" id="FiltroRapido">
	<div class="panel-heading">
		Filtrar:
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="form-group col-md-12 col-lg-12 col-sm-12 col-xs-12">
				<label>Tipo de Medio:</label>
				<div class="input-group">
					<div class="row">
						<div class="col-md-3 col-xs-3">
							<a href="#" data-toggle="tooltip" data-placement="top" title="Prensa"><button type="button" class="btn btn-warning btnFiltroTipo" id="btnPrensa" value="1">PR</button></a>
						</div>
						<div class="col-md-3 col-xs-3">
							<a href="#" data-toggle="tooltip" data-placement="top" title="Televisión"><button type="button" class="btn btn-warning btnFiltroTipo" id="btnTv" value="1">TV</button></a>
						</div>
						<div class="col-md-3 col-xs-3">
							<a href="#" data-toggle="tooltip" data-placement="top" title="Radio"><button type="button" class="btn btn-warning btnFiltroTipo" id="btnRadio" value="1">RA</button></a>
						</div>
						<div class="col-md-3 col-xs-3">
							<a href="#" data-toggle="tooltip" data-placement="top" title="Internet"><button type="button" class="btn btn-warning btnFiltroTipo" id="btnInternet" value="1">IN</button></a>
						</div>
					</div>
				</div><!-- /.input group -->
			</div><!-- /.form group -->
		</div><!-- /.row -->
		<div class="row" id="cajaMedios">
			<div class="form-group col-md-12 col-lg-12 col-sm-12 col-xs-12">
				<label>Medios:</label>
				<input type="hidden" name="numeroMedios" id="numeroMedios" value="0">
				<div style="height: 300px;overflow-y: scroll;" id="contenedorMedios">
					<!--button type="button" class="btn btn-info btnFiltro" id="medioFiltro1" name="medioFiltro1" value="1">Medio 1</button-->
				</div>
			</div><!-- /.form group -->
		</div><!-- /.row -->

	</div>
</div>

