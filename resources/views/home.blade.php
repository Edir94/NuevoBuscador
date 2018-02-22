@extends('layouts.app')

@section('content')
<div class="container-fluid" style="justify-content: center; display: flex;">
    <div class="col-xs-10">
        <div class="col-xs-3">
            @include('inicial.opciones')
        </div>
        <div class="col-xs-9">
            @include('inicial.resultados')
        </div>
    </div><!-- ./row -->
    
</div>
@endsection
