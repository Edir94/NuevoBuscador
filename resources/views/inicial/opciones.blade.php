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
					<label>Medio:</label>
					<!--{/{ Form::text('medioBusqueda', null, ['id' =>  'medioBusqueda', 'placeholder' =>  'Escriba un medio','class'=>'form-control'])}}-->
					<input type="text" class="form-control" id="medioBusqueda" name="medioBusqueda" placeholder="Escriba un medio">
				</div><!-- /.form group -->
			</div><!-- /.row -->
	        <div class="row">
				<div class="form-group col-md-12 col-lg-12 col-sm-12 col-xs-12">
					<label>Texto:</label>
					<div class="input-group col-md-12">
						<a href="#" data-tooltip=" 'O' = 'TAB' " data-placement="bottom"><input type="text" class="form-control" id="textoBusqueda" name="textoBusqueda" placeholder="Ingrese texto a buscar"></a>
					</div><!-- /.input group -->
				</div><!-- /.form group -->
			</div><!-- /.row -->
			<div class="row">
				<div class="form-group col-md-12 col-lg-12 col-sm-12 col-xs-12">
					<label>Buscar en:</label>
					<div class="input-group">
						<div class="col-xs-12">
							<label class="checkbox-inline col-xs-6">
							    <input type="checkbox" name="checkPrensa" id="checkPrensa" value="1" checked="true"> Prensa
							</label>
							<label class="checkbox-inline">
							    <input type="checkbox" name="checkTv" id="checkTv" value="1" checked="true"> Televisión
							</label>
						</div>
						<div class="col-xs-12">
							<label class="checkbox-inline col-xs-6">
							    <input type="checkbox" name="checkRadio" id="checkRadio" value="1" checked="true"> Radio
							</label>
							<label class="checkbox-inline">
							    <input type="checkbox" name="checkInternet" id="checkInternet" value="1" checked="true"> Internet
							</label>
						</div>
					</div><!-- /.input group -->
				</div><!-- /.form group -->
			</div><!-- /.row -->

			<div class="form-group col-lg-12 col-md-10 col-sm-10 col-xs-12">                        
				<button type="button" id="BusquedaAvanzada" onclick="BuscarPautas();" class="btn btn-primary">Buscar</button>
			</div><!-- /.form group -->
	    </div><!-- /.panel body -->
    </div><!-- /.contenedorBusqueda-->
    
</div><!-- /.panel default -->
<div class="panel panel-default">
	<div class="panel-heading">
		Filtrar:
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="form-group col-md-12 col-lg-12 col-sm-12 col-xs-12">
				<label>Buscar en:</label>
				<div class="input-group">
					<div class="col-xs-12">
						<label class="checkbox-inline col-xs-6">
						    <input type="checkbox" name="filtroCheckPrensa" id="filtroCheckPrensa" value="1" checked="true"> Prensa
						</label>
						<label class="checkbox-inline">
						    <input type="checkbox" name="filtroCheckTv" id="filtroCheckTv" value="1" checked="true"> Televisión
						</label>
					</div>
					<div class="col-xs-12">
						<label class="checkbox-inline col-xs-6">
						    <input type="checkbox" name="filtroCheckRadio" id="filtroCheckRadio" value="1" checked="true"> Radio
						</label>
						<label class="checkbox-inline">
							<input type="checkbox" name="filtroCheckInternet" id="filtroCheckInternet" value="1" checked="true"> Internet
						</label>
					</div>
				</div><!-- /.input group -->
			</div><!-- /.form group -->
		</div><!-- /.row -->
		<div class="row">
			<div class="form-group col-md-12 col-lg-12 col-sm-12 col-xs-12">
				<label>Medio:</label>
				<!--{/{ Form::text('medioBusqueda', null, ['id' =>  'medioBusqueda', 'placeholder' =>  'Escriba un medio','class'=>'form-control'])}}-->
				<button type="text" class="form-control" id="filtroMedioBusqueda" name="filtroMedioBusqueda" placeholder="Escriba un medio"></button>
			</div><!-- /.form group -->
		</div><!-- /.row -->

	</div>
</div>

