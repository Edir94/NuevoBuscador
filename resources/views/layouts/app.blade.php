<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        Servicios | Noticias Perú
    </title>    
    <!--{/{ config('app.name', 'Laravel') }}-->

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}">
    <!-- Jquery UI -->
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.min.css">
    <!-- Date Picker -->
    <link rel="stylesheet" href="{{ asset('plugins/datepicker/datepicker3.css') }}">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables/jquery.dataTables.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables/extensions/Responsive/css/dataTables.responsive.css') }}">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="{{ asset('dist/css/skins/_all-skins.min.css') }}">
    <!-- Boostrap Tokenfield -->
    <link rel="stylesheet" href="{{ asset('plugins/bootstrap-tokenfield/css/bootstrap-tokenfield.min.css')}}">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-inverse navbar-static-top" rol="Navigation" style="background-color: #014B70">
            <div class="container">
                <div class="navbar-header">
                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    @if(Auth::guest())
                        <a class="navbar-brand" href="{{ url('/') }}">
                            <!--{/{ config('app.name', 'Laravel') }}-->
                            <!--Noticias Perú-->
                            <img src="../logo.png" alt="logo" width="80%" style="margin:-5px 0px 0px 0px;" id="logo-empresa">
                        </a>
                    @else
                        <a class="navbar-brand" href="{{ url('/home') }}">
                            <!--{/{ config('app.name', 'Laravel') }}-->
                            <!--Noticias Perú-->
                            <img src="../logo.png" alt="logo" width="80%" style="margin:-5px 0px 0px 0px;" id="logo-empresa">
                        </a>
                    @endif
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    @if(!Auth::guest())
                    <ul class="nav navbar-nav">
                        <li><a href="{{ url('/home') }}">Búsqueda</a></li>
                    </ul>
                    <ul class="nav navbar-nav">
                        <li><a href="{{url('/favoritos')}}">Favoritos</a></li>
                    </ul>
                    @endif
                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @if (Auth::guest())
                            <li><a href="{{ route('login') }}">Ingresar</a></li>
                            <li><a href="{{ route('register') }}">Registrarse</a></li>
                        @else
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    Hola, {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Salir
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>
        <input type="hidden" id="token" name="_token" value="{{ csrf_token() }}">
        @yield('content')
    </div>

    <!-- Scripts -->
    <!-- jQuery 2.2.3 -->
    <script src="{{ asset('plugins/jQuery/jquery-2.2.3.min.js') }}"></script>
    <!-- jQuery UI 1.12.1 -->
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <!-- Bootstrap 3.3.6 -->
    <script src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script>
    <!-- daterangepicker -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
    <script src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>
    <!-- datepicker -->
    <script src="{{ asset('plugins/datepicker/bootstrap-datepicker.js') }}"></script>
    <!-- Datatables -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    {!!Html::script('plugins/datatables/dataTables.bootstrap.min.js')!!}
    {!!Html::script('plugins/datatables/extensions/Responsive/js/dataTables.responsive.min.js')!!}
    <!-- Boostrap Tokenfield -->
    {!!Html::script('plugins/bootstrap-tokenfield/bootstrap-tokenfield.min.js')!!}


    <!--Public Scripts -->
    <!--script src="{/{ asset('js/app.js') }}"></script-->
    <script src="{{ asset('js/script.js') }}"></script>
    <script src="{{ asset('js/sandra.js') }}"></script>
</body>
</html>

