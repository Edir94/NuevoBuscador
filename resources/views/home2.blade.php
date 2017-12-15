@extends('principal.supbar')

@section('content')
<div class="container-fluid">
    <div class="row" align="center">
        <div class="" style="width: 80%;">
            <div class="panel panel-default">
                <div class="panel-heading">Inicio</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    Bienvenidos a Noticias Perú
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
