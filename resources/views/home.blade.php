@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-xs-3">
            @include('inicial.opciones')
        </div>
        <div class="col-xs-9">
            @include('inicial.resultados')
        </div>
    </div>
    
</div>
@endsection
