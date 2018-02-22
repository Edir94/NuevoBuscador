@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="panel panel-default">
        	<div class="panel-body">
        		<div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-4">
                            <label for="fechaPauta">Fecha:</label>
                            <span id="fechaPauta">{{ $pautasPrensa['fechaPauta'] }}</span>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-4">
                            <label for="medio">Medio: </label>
                            <span id="medio">{{ $pautasPrensa['nombreMedio'] }}</span>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-4">
                            <label for="programa">Sección: </label>
                            <span id="programa">{{ $pautasPrensa['nombreSeccion'] }}</span>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-4">
                            <label for="titular">Titular: </label>
                            <span id="titular">{{ $pautasPrensa['titular'] }}</span>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-4">
                            <label for="paginas">Páginas: </label>
                            <span id="paginas">{{ $pautasPrensa['paginas'] }}</span>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-4">
                            <label for="lectoria">Lectoría(S): </label>
                            <span id="lectoria">{{ $pautasPrensa['lectoria'] }}</span>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-4">
                            <label for="area">Alto / Ancho: </label>
                            <span id="area">{{ $pautasPrensa['alto'] }} x {{ $pautasPrensa['ancho'] }}</span>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-4">
                            <label for="equivalencia">Valor (US$): </label>
                            <span id="equivalencia">{{ $pautasPrensa['equivalencia'] }}</span>
                        </div>
                    </div><!-- ./row --> 
        	</div><!-- ./panel body -->
            <div class="panel-footer" align="center">
                <button type="button" class="btn btn-primary" id="btnMostrarTexto">
                    <span class="glyphicon glyphicon-text-size"></span>
                </button>
                <button type="button" class="btn btn-primary" id="btnMostrarImagen" style="display: none;">
                    <span class="glyphicon glyphicon-picture"></span>
                </button>
            </div>
        </div><!-- /. panel -->
        <div class="panel panel-default">
        	<div class="panel-body">
                <div class="col-xs-12 col-sm-12 col-md-12" id="textoPauta" style="display: none; overflow: scroll;">
                    <label for="texto">Texto: </label>
                    <br>
                    <span id="texto">{{ $pautasPrensa['texto'] }}</span>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12" id="imagenPauta">
                    <div id="paginacion" class="carousel slide" data-ride="carousel" data-interval="false">
                        <ol class="carousel-indicators" style="position: relative; padding: 15px 0px 0px 0px;">
                            @foreach($recortes as $recorte)
                                @if($recorte['codigo']==1)
                                    <li data-target="#paginacion" data-slide-to="{{ $recorte['codigo']-1 }}" class="active" style="border: 1px solid #1B1B1B;"></li>
                                @else
                                    <li data-target="#paginacion" data-slide-to="{{ $recorte['codigo']-1 }}" class="" style="border: 1px solid #1B1B1B;"></li>
                                @endif
                            @endforeach
                        </ol>
                        <ol class="carousel-indicators">
                            @foreach($recortes as $recorte)
                                @if($recorte['codigo']==1)
                                    <li data-target="#paginacion" data-slide-to="{{ $recorte['codigo']-1 }}" class="active" style="border: 1px solid #1B1B1B;"></li>
                                @else
                                    <li data-target="#paginacion" data-slide-to="{{ $recorte['codigo']-1 }}" class="" style="border: 1px solid #1B1B1B;"></li>
                                @endif
                            @endforeach
                        </ol>
                        <div id="contenedorImagenes" class="carousel-inner" align="center" role="listbox">
                            @foreach($recortes as $recorte)
                                @if($recorte['codigo']==1)
                                    <div class="item active" id="imagenPauta{{ $recorte['codigo']-1 }}">
                                        <span>
                                            <kbd>Página {{ $recorte['codigo'] }}</kbd>
                                        </span>
                                        <br>
                                        <center>
                                            <img src="{{ $recorte['rutaImagen'] }}" width="70%" class="img-responsive">
                                        </center>
                                        <br>
                                        <br>
                                        <span>
                                            <kbd>Página {{ $recorte['codigo'] }}</kbd>
                                        </span>
                                        <div class="carousel-caption"></div>
                                    </div>
                                @else
                                    <div class="item" id="imagenPauta{{ $recorte['codigo']-1 }}">
                                        <span>
                                            <kbd>Página {{ $recorte['codigo'] }}</kbd>
                                        </span>
                                        <br>
                                        <center>
                                            <img src="{{ $recorte['rutaImagen'] }}" width="70%" class="img-responsive">
                                        </center>
                                        <br>
                                        <br>
                                        <span>
                                            <kbd>Página {{ $recorte['codigo'] }}</kbd>
                                        </span>
                                        <div class="carousel-caption"></div>
                                    </div>
                                @endif
                            @endforeach
                            <a href="#paginacion" class="left carousel-control" role="button" data-slide="prev">
                                <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                                <span class="sr-only">Previous</span>
                            </a>
                            <a href="#paginacion" class="right carousel-control" role="button" data-slide="next">
                                <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                                <span class="sr-only">Next</span>
                            </a>
                        </div>
                    </div>
                </div><!-- /. imagenPauta -->
        	</div><!-- /. panel-body -->
        </div><!-- /. panel -->
    </div><!-- ./row -->
</div><!-- ./container -->
@endsection