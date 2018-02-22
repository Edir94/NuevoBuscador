<div class="panel panel-default">
<div class="panel-body">
<div class="box">
	<div class="box-header">
        <div class="col-xs-12">
            <div class="col-xs-offset-6" align="right">
                <a href="{{ URL('/download') }}" type="button" data-toggle="tooltip" data-placement="top" title="Exportar a Excel" class="btn btn-success"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Exportar</button></a>
            </div>
            <h3 class="box-title">Resultados de Búsqueda</h3>
        </div>
    </div> 
	<div class="box-body" id="check">
        <hr>
		<table id="tblResultados" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%" >
            <hr>
    		<thead>
         		<tr>
                    <th class="col-md-1">
                        <div align="center">
                            <input type="checkbox" name="checkGeneral" class = "checkitem" id="checkGeneral" value="1" checked="true">
                        </div>
                    </th>
             		<th class="col-md-2">Fecha</th>
             		<th class="col-md-2">Tipo</th>
             		<th class="col-md-2">Medio</th>
             		<th class="col-md-2">Sec./Prog.</th>
             		<th class="col-md-4">Titular</th>
                    <th class="col-md-1">Abrir</th>
         		</tr>
    		</thead>
  		</table>
	</div>
</div>
</div>
</div>