@extends('layouts.app')
@section('content')

<div class="container">
    <div class="row">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <label for="fechaPauta">Fecha:</label>
                            <span id="fechaPauta">{{ $pautasInternet['fechaPauta'] }}</span>
                            <!--{/!!Form::label('fechaPauta','Fecha: '.$pautasTv[0]->fechaPauta,['class'=>'','id'=>'fechaPauta'])!!}-->
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <label for="medio">Medio: </label>
                            <span id="medio">{{ $pautasInternet['nombreMedio'] }}</span>
                            <!--{/!!Form::label('medio','Medio: '.$pautasTv[0]->nombreMedio,['class'=>'','id'=>'medio'])!!}-->
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <label for="titular">Titular: </label>
                            <span id="titular">{{ $pautasInternet['titular'] }}</span>
                            <!--{/!!Form::label('titular','Titular: '.$pautasTv[0]->titular,['class'=>'','id'=>'titular'])!!}-->
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <label for="link">Link: </label>
                            <a id="link" href="{{ $pautasInternet['rutaWeb'] }}" target="_blank">Ver original</a>
                            <!--{/!!Form::label('hora','Hora: ',['class'=>'','id'=>'hora'])!!}-->
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <label for="equivalencia">Valor (US$): </label>
                            <span id="equivalencia">{{ $pautasInternet['equivalencia'] }}</span>
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
                    <span id="texto">{{ $pautasInternet['texto'] }}</span>
                    <!--{/!!Form::label('valorizado','Valor (US$): ',['class'=>'','id'=>'valorizado'])!!}-->
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12" id="imagenPauta" align="center">
                    <img src="{{ $pautasInternet['rutaImagen'] }}">
                </div>
            </div>
        </div><!-- /. panel -->
    </div><!-- ./row -->
</div><!-- ./container -->
@endsection