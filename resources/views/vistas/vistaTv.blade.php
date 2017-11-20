@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="panel panel-default">
            <div class="panel-body" style="border: 1px solid #000000;-moz-border-radius: 7px;-webkit-border-radius: 7px;padding: 10px;">
                <div class="col-xs-12 col-sm-12 col-md-6">
                    <div class="row" style="padding: 5px;">
                        <div class="col-xs-12 col-sm-12 col-md-12 player" style="border: 1px solid #000000;-moz-border-radius: 7px;-webkit-border-radius: 7px;padding: 10px;">
                            <video id="videoTv" src="{{ $pautasTv['rutaVideo'] }}" controls autobuffer="true" autoplay preload="true" width="100%">
                            </video>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-6">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <label for="fechaPauta">Fecha:</label>
                            <span id="fechaPauta">{{ $pautasTv['fechaPauta'] }}</span>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <label for="medio">Medio: </label>
                            <span id="medio">{{ $pautasTv['nombreMedio'] }}</span>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <label for="programa">Programa: </label>
                            <span id="programa">{{ $pautasTv['nombrePrograma'] }}</span>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <label for="hora">Hora: </label>
                            <span id="hora">{{ $pautasTv['horaPauta'] }}</span>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <label for="titular">Titular: </label>
                            <span id="titular">{{ $pautasTv['titular'] }}</span>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <label for="texto">Texto: </label>
                            <br>
                            <span id="texto">{{ $pautasTv['texto'] }}</span>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <label for="equivalencia">Valor (US$): </label>
                            <span id="equivalencia">{{ $pautasTv['equivalencia'] }}</span>
                        </div>
                    </div><!-- ./row -->    
                </div>
            </div><!-- ./panel body -->
        </div><!-- /. panel -->
    </div><!-- ./row -->
</div><!-- ./container -->
@endsection