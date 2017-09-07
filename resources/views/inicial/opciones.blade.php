<div class="panel panel-default">
    <div class="panel-heading">Filtrar por:</div>
    <div class="panel-body">
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif
        <div class="row">
			<div class="form-group col-md-12 col-lg-12 col-sm-12 col-xs-12">
				<label>Texto:</label>
				<div class="input-group col-md-12">
					<a href="#" data-tooltip=" 'O' = 'TAB' " data-placement="bottom"><input type="text" class="form-control" id="TextoBusqueda" name="TextoBusqueda" placeholder="Ingrese texto a buscar"></a>
				</div><!-- /.input group -->
			</div><!-- /.form group -->
		</div>
		<div class="row">
			<div class="form-group col-md-12 col-lg-12 col-sm-12 col-xs-12">
				<label>Medio:</label>
				{{ Form::text('idcanal', null, ['id' =>  'idcanal', 'placeholder' =>  'Escriba un canal','class'=>'typeahead form-control'])}}
			</div><!-- /.form group -->
		</div>
		<div class="row">
			<div class="form-group col-md-12 col-lg-12 col-sm-12 col-xs-12">
				<label>Fecha:</label>
				<div class="input-group">
				    <input type="text" class="form-control pull-right dateRange" id="rangoFecha">
				    <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
				</div><!-- /.input group -->
			</div><!-- /.form group -->
		</div>
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
		</div>

		<div class="form-group col-lg-12 col-md-10 col-sm-10 col-xs-12">                        
			<button type="button" id="BusquedaAvanzada" onclick="" class="btn btn-primary">Buscar</button>
		</div><!-- /.form group -->
    </div><!-- /.panel body -->
    
</div><!-- /.panel default -->
