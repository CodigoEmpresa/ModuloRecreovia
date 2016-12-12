<!DOCTYPE html>



<html lang="es">
  <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="description" content="">
      <meta name="author" content="">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta http-equiv="X-UA-Compatible" content="IE=edge" />
      <meta name="csrf-token" content="{{ csrf_token() }}" />

      @section('style')
          <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
          <link rel="stylesheet" href="{{ asset('public/Css/jquery-ui.css') }}" media="screen">    
          <link rel="stylesheet" href="{{ asset('public/Css/bootstrap.css') }}" media="screen">    
          <link rel="stylesheet" href="{{ asset('public/Css/bootstrap-datetimepicker.css') }}" media="screen">    
          <link rel="stylesheet" href="{{ asset('public/Css/sticky-footer.css') }}" media="screen">    
          <link rel="stylesheet" href="{{ asset('public/Css/main.css') }}" media="screen">    
      @show

      @section('script')
          <script src="{{ asset('public/Js/jquery.js') }}"></script>
          <script src="{{ asset('public/Js/jquery-ui.js') }}"></script>
          <script src="{{ asset('public/Js/bootstrap.min.js') }}"></script>
          <script src="{{ asset('public/Js/moment.js') }}"></script>
          <script src="{{ asset('public/Js/bootstrap-datetimepicker.min.js') }}"></script>
          <script src="{{ asset('public/Js/main.js') }}"></script>
      @show

      <title>Recreovía</title>
  </head>

  <body>
      
       <!-- Menu Módulo -->
       <div class="navbar navbar-default navbar-fixed-top">
        <div class="container">
          <div class="navbar-header">
            <a href="{{ url('/welcome') }}" class="navbar-brand">SIM</a>
            <button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-main">
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
          </div>
          <div class="navbar-collapse collapse" id="navbar-main">
            <ul class="nav navbar-nav">
            @if(
              $_SESSION['Usuario']['Permisos']['editar_profesores'] ||
              $_SESSION['Usuario']['Permisos']['editar_puntos'] ||
              $_SESSION['Usuario']['Permisos']['administrar_localidades'] ||
              $_SESSION['Usuario']['Permisos']['administrar_jornadas']
            )
              <li class="dropdown {{ $seccion && in_array($seccion, ['Profesores', 'Puntos', 'Administrar localidades']) ? 'active' : '' }}">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">Administración <span class="caret"></span></a>
                <ul class="dropdown-menu">
                  @if($_SESSION['Usuario']['Permisos']['administrar_jornadas'])
                    <li class="{{ $seccion && $seccion == 'Jornadas' ? 'active' : '' }}">
                      <a href="{{ url('jornadas') }}">Gestión de jornadas</a>
                    </li>
                  @endif
                  @if($_SESSION['Usuario']['Permisos']['editar_puntos'])
                    <li class="{{ $seccion && $seccion == 'Puntos' ? 'active' : '' }}">
                      <a href="{{ url('puntos') }}">Puntos de recreovía</a>
                    </li>
                  @endif
                  @if($_SESSION['Usuario']['Permisos']['editar_profesores'])
                    <li class="{{ $seccion && $seccion == 'Profesores' ? 'active' : '' }}">
                      <a href="{{ url('profesores') }}">Profesores y Gestores</a>
                    </li>
                  @endif
                  @if($_SESSION['Usuario']['Permisos']['administrar_localidades'])
                    <li class="{{ $seccion && $seccion == 'Administrar localidades' ? 'active' : '' }}">
                      <a href="{{ url('localidades/administrar') }}">Distribuir personal</a>
                    </li>
                  @endif
                </ul>
              </li>
            @endif
            @if(
              in_array('Gestor', $_SESSION['Usuario']['Roles'])
            )
              <li class="dropdown {{ $seccion && in_array($seccion, ['Programación', 'Sesiones gestor']) ? 'active' : '' }}">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">Gestores <span class="caret"></span></a>
                <ul class="dropdown-menu">
                  <li class="{{ $seccion && $seccion == 'Programación' ? 'active' : '' }}">
                    <a href="{{ url('programacion/gestores') }}">Programación de sesiones</a>
                  </li>
                  <li class="{{ $seccion && $seccion == 'Sesiones gestor' ? 'active' : '' }}">
                    <a href="{{ url('programacion/gestor/sesiones') }}">Sesiones</a>
                  </li>
                </ul>
              </li>
            @endif
            @if(
              in_array('Profesor', $_SESSION['Usuario']['Roles'])
            )
              <li class="dropdown {{ $seccion && in_array($seccion, ['Sesiones profesor']) ? 'active' : '' }}">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">Profesores <span class="caret"></span></a>
                <ul class="dropdown-menu">
                  <li class="{{ $seccion && $seccion == 'Sesiones profesor' ? 'active' : '' }}">
                    <a href="{{ url('programacion/profesor/sesiones') }}">Sesiones</a>
                  </li>
                </ul>
              </li>
            @endif
            </ul>
            <ul class="nav navbar-nav navbar-right">
              <li><a href="http://www.idrd.gov.co/sitio/idrd/" target="_blank">I.D.R.D</a></li>
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{ $_SESSION['Usuario']['Persona']['Primer_Apellido'].' '.$_SESSION['Usuario']['Persona']['Primer_Nombre'] }}<span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li>
                      <a href="{{ url('personas/'.$_SESSION['Usuario'][0].'/editar') }}">Editar</a>
                    </li>
                    <li>
                      <a href="{{ url('logout') }}">Cerrar sesión</a>
                    </li>
                </ul>
              </li>
            </ul>
          </div>
        </div>
      </div>
      <!-- FIN Menu Módulo -->
        
      <!-- Contenedor información módulo -->
      </br></br>
      <div class="container">
          <div class="page-header" id="banner">
            <div class="row">
              <div class="col-lg-8 col-md-7 col-sm-6">
                <h1>Recreovía</h1>
                <p class="lead"><h4>Módulo para la gestión y control de actividades del programa recreovía</h4></p>
              </div>
              <div class="col-lg-4 col-md-5 col-sm-6">
                 <div align="right"> 
                    <img src="{{ asset('public/Img/IDRD.JPG') }}" width="50%" heigth="40%"/>
                 </div>                    
              </div>
              <div class="col-sm-12">
                <p class="text-primary">{{ $seccion ? $seccion : '' }}</p>
              </div>
            </div>
          </div>        
      </div>
      <!-- FIN Contenedor información módulo -->

      <!-- Contenedor panel principal -->
      <div class="container">
          @yield('content')
      </div>        
      <!-- FIN Contenedor panel principal -->
  </body>

</html>





