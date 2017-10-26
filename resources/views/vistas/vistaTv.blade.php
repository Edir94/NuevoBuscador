@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="panel panel-default">
            <div class="panel-body" style="border: 1px solid #000000;-moz-border-radius: 7px;-webkit-border-radius: 7px;padding: 10px;">
                <div class="col-xs-12 col-sm-12 col-md-6">
                    <div class="row" style="padding: 5px;">
                        <div class="col-xs-12 col-sm-12 col-md-12 player" style="border: 1px solid #000000;-moz-border-radius: 7px;-webkit-border-radius: 7px;padding: 10px;">
                            <video id="videoTv" src="http://servicios.noticiasperu.pe/medios/tv/mp4_9/2017/10/02/201710027176149.mp4" controls autobuffer="true" autoplay preload="true" width="100%">
                            </video>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-6">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <label for="fechaPauta">Fecha:</label>
                            <span id="fechaPauta">{{ $pautasTv[0]->fechaPauta }}</span>
                            <!--{/!!Form::label('fechaPauta','Fecha: '.$pautasTv[0]->fechaPauta,['class'=>'','id'=>'fechaPauta'])!!}-->
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <label for="medio">Medio: </label>
                            <span id="medio">{{ $pautasTv[0]->nombreMedio }}</span>
                            <!--{/!!Form::label('medio','Medio: '.$pautasTv[0]->nombreMedio,['class'=>'','id'=>'medio'])!!}-->
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <label for="programa">Programa: </label>
                            <span id="programa">{{ $pautasTv[0]->nombrePrograma }}</span>
                            <!--{/!!Form::label('programa','Programa: '.$pautasTv[0]->nombrePrograma,['class'=>'','id'=>'programa'])!!}-->
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <label for="hora">Hora: </label>
                            <span id="hora"></span>
                            <!--{/!!Form::label('hora','Hora: ',['class'=>'','id'=>'hora'])!!}-->
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <label for="titular">Titular: </label>
                            <span id="titular">{{ $pautasTv[0]->titular }}</span>
                            <!--{/!!Form::label('titular','Titular: '.$pautasTv[0]->titular,['class'=>'','id'=>'titular'])!!}-->
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <label for="texto">Texto: </label>
                            <br>
                            <span id="texto">{{ $pautasTv[0]->texto }}</span>
                            <!--{/!!Form::label('valorizado','Valor (US$): ',['class'=>'','id'=>'valorizado'])!!}-->
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <label for="equivalencia">Valor (US$): </label>
                            <span id="equivalencia"></span>
                            <!--{/!!Form::label('valorizado','Valor (US$): ',['class'=>'','id'=>'valorizado'])!!}-->
                        </div>
                    </div><!-- ./row -->    
                </div>
            </div><!-- ./panel body -->
        </div><!-- /. panel -->
    </div><!-- ./row -->
</div><!-- ./container -->
@endsection