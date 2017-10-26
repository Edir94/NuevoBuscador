@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="panel panel-default">
        	<div class="panel-body">
        		<div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-4">
                            <label for="fechaPauta">Fecha:</label>
                            <span id="fechaPauta">{{ $pautasPrensa[0]->fechaPauta }}</span>
                            <!--{/!!Form::label('fechaPauta','Fecha: '.$pautasTv[0]->fechaPauta,['class'=>'','id'=>'fechaPauta'])!!}-->
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-4">
                            <label for="medio">Medio: </label>
                            <span id="medio">{{ $pautasPrensa[0]->nombreMedio }}</span>
                            <!--{/!!Form::label('medio','Medio: '.$pautasTv[0]->nombreMedio,['class'=>'','id'=>'medio'])!!}-->
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-4">
                            <label for="programa">Sección: </label>
                            <span id="programa">{{ $pautasPrensa[0]->nombreSeccion }}</span>
                            <!--{/!!Form::label('programa','Programa: '.$pautasTv[0]->nombrePrograma,['class'=>'','id'=>'programa'])!!}-->
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-4">
                            <label for="titular">Titular: </label>
                            <span id="titular">{{ $pautasPrensa[0]->titular }}</span>
                            <!--{/!!Form::label('titular','Titular: '.$pautasTv[0]->titular,['class'=>'','id'=>'titular'])!!}-->
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-4">
                            <label for="paginas">Páginas: </label>
                            <span id="paginas"></span>
                            <!--{/!!Form::label('hora','Hora: ',['class'=>'','id'=>'hora'])!!}-->
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-4">
                            <label for="lectoria">Lectoría: </label>
                            <span id="lectoria"></span>
                            <!--{/!!Form::label('hora','Hora: ',['class'=>'','id'=>'hora'])!!}-->
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-4">
                            <label for="area">Alto / Ancho: </label>
                            <span id="area"></span>
                            <!--{/!!Form::label('hora','Hora: ',['class'=>'','id'=>'hora'])!!}-->
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-4">
                            <label for="equivalencia">Valor (US$): </label>
                            <span id="equivalencia"></span>
                            <!--{/!!Form::label('valorizado','Valor (US$): ',['class'=>'','id'=>'valorizado'])!!}-->
                        </div>
                    </div><!-- ./row --> 
        	</div><!-- ./panel body -->
        </div><!-- /. panel -->
        <div class="panel panel-default">
            <div class="panel-heading" align="center">
                <button type="button" class="btn btn-primary" id="btnMostrarTexto">
                    <span class="glyphicon glyphicon-text-size"></span>
                </button>
                <button type="button" class="btn btn-primary" id="btnMostrarImagen" style="display: none;">
                    <span class="glyphicon glyphicon-picture"></span>
                </button>
            </div>
        	<div class="panel-body">
                <div class="col-xs-12 col-sm-12 col-md-12" id="textoPauta" style="display: none; overflow: scroll;">
                    <label for="texto">Texto: </label>
                    <br>
                    <span id="texto">{{ $pautasPrensa[0]->texto }}</span>
                    <!--{/!!Form::label('valorizado','Valor (US$): ',['class'=>'','id'=>'valorizado'])!!}-->
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12" id="imagenPauta" align="center">
                    <img src="http://servicios.noticiasperu.pe/medios/Recortes/2017/10/02/2017-10-0201019400015818779_1.jpg">
                </div>
        	</div>
        </div><!-- /. panel -->
    </div><!-- ./row -->
</div><!-- ./container -->
@endsection