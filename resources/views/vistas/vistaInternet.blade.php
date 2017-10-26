@extends('layouts.app')
@section('content')

<div class="container">
    <div class="row">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <label for="fechaPauta">Fecha:</label>
                            <span id="fechaPauta">{{ $pautasInternet[0]->fechaPauta }}</span>
                            <!--{/!!Form::label('fechaPauta','Fecha: '.$pautasTv[0]->fechaPauta,['class'=>'','id'=>'fechaPauta'])!!}-->
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <label for="medio">Medio: </label>
                            <span id="medio">{{ $pautasInternet[0]->nombreMedio }}</span>
                            <!--{/!!Form::label('medio','Medio: '.$pautasTv[0]->nombreMedio,['class'=>'','id'=>'medio'])!!}-->
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <label for="titular">Titular: </label>
                            <span id="titular">{{ $pautasInternet[0]->titular }}</span>
                            <!--{/!!Form::label('titular','Titular: '.$pautasTv[0]->titular,['class'=>'','id'=>'titular'])!!}-->
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <label for="link">Link: </label>
                            <a id="link" href="{{ $pautasInternet[0]->rutaImagen }}" target="_blank">Ver original</a>
                            <!--{/!!Form::label('hora','Hora: ',['class'=>'','id'=>'hora'])!!}-->
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
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
                <div class="col-xs-12 col-sm-12 col-md-12" id="textoPauta" style="display: none;">
                    <label for="texto">Texto: </label>
                    <br>
                    <span id="texto">{{ $pautasInternet[0]->texto }}</span>
                    <!--{/!!Form::label('valorizado','Valor (US$): ',['class'=>'','id'=>'valorizado'])!!}-->
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12" id="imagenPauta" align="center">
                    <img src="http://servicios.noticiasperu.pe/medios/internet/2017/10/02/NzE4OTExNy0yMDE3LTEwLTAyIDA3OjQyOjUx.jpg">
                </div>
            </div>
        </div><!-- /. panel -->
    </div><!-- ./row -->
</div><!-- ./container -->
@endsection