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

		@section ('style')
				<link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
				<link rel="stylesheet" href="{{ asset('public/components/jquery-ui/themes/base/jquery-ui.css') }}" media="screen">
				<link rel="stylesheet" href="{{ asset('public/css/bootstrap.css') }}" media="screen">
				<link rel="stylesheet" href="{{ asset('public/components/bootstrap-select/dist/css/bootstrap-select.css') }}" media="screen">
				<link rel="stylesheet" href="{{ asset('public/components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css') }}" media="screen">
				<link rel="stylesheet" href="{{ asset('public/components/selectize/dist/css/selectize.bootstrap3.css') }}" media="screen">
				<link rel="stylesheet" href="{{ asset('public/components/datatables.net-bs/css/dataTables.bootstrap.css') }}" media="screen">
				<link rel="stylesheet" href="{{ asset('public/components/datatables.net-responsive-dt/css/responsive.dataTables.min.css') }}" media="screen">
				<link rel="stylesheet" href="{{ asset('public/components/highcharts/css/highcharts.css') }}" media="screen">
				<link rel="stylesheet" href="{{ asset('public/components/font-awesome/css/font-awesome.css') }}" media="screen">
				<link rel="stylesheet" href="{{ asset('public/components/loaders.css/loaders.min.css') }}" media="screen">
				<link rel="stylesheet" href="{{ asset('public/css/main.css') }}" media="screen">
		@show

		@section ('script')
				<script src="{{ asset('public/components/jquery/jquery.js') }}"></script>
				<script src="{{ asset('public/components/jquery-ui/jquery-ui.js') }}"></script>
				<script src="{{ asset('public/components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
				<script src="{{ asset('public/components/bootstrap-select/dist/js/bootstrap-select.min.js') }}"></script>
				<script src="{{ asset('public/components/moment/moment.js') }}"></script>
				<script src="{{ asset('public/components/datatables.net/js/jquery.dataTables.js') }}"></script>
				<script src="{{ asset('public/components/datatables.net-bs/js/dataTables.bootstrap.js') }}"></script>
				<script src="{{ asset('public/components/datatables.net-responsive/js/dataTables.responsive.js') }}"></script>
				<script src="{{ asset('public/components/highcharts/js/highcharts.js') }}"></script>
				<script src="{{ asset('public/components/selectize/dist/js/standalone/selectize.js') }}"></script>
				<script src="{{ asset('public/components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js') }}"></script>
				<script src="{{ asset('public/Js/main.js') }}"></script>
		@show
		<title>Recreovía</title>
	</head>
	<body>
		<!-- Menu Módulo -->
		<div class="navbar navbar-default navbar-fixed-top">
			<div class="container">
				<div class="navbar-header">
					<a href="{{ url('/welcome') }}" data-role="{{ implode($_SESSION['Usuario']['Roles']) }}" class="navbar-brand">SIM</a>
					<button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-main">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
				</div>
				<div class="navbar-collapse collapse" id="navbar-main">
					<ul class="nav navbar-nav">
					@if (
						$_SESSION['Usuario']['Permisos']['editar_profesores'] ||
						$_SESSION['Usuario']['Permisos']['editar_puntos'] ||
						$_SESSION['Usuario']['Permisos']['administrar_localidades'] ||
						$_SESSION['Usuario']['Permisos']['administrar_jornadas'] ||
						$_SESSION['Usuario']['Permisos']['gestion_global_de_sesiones'] ||
						$_SESSION['Usuario']['Permisos']['buscador_de_sesiones']
					)
						<li class="dropdown {{ $seccion && in_array($seccion, ['Profesores', 'Jornadas', 'Puntos', 'Administrar localidades', 'Gestion global de sesiones']) ? 'active' : '' }}">
							<a class="dropdown-toggle" data-toggle="dropdown" href="#">Administración <span class="caret"></span></a>
							<ul class="dropdown-menu">
								<li class="dropdown-header">Configuración</li>
								@if($_SESSION['Usuario']['Permisos']['administrar_jornadas'])
									<li class="{{ $seccion && $seccion == 'Jornadas' ? 'active' : '' }}">
										<a href="{{ url('jornadas') }}">Jornadas</a>
									</li>
								@endif
								@if($_SESSION['Usuario']['Permisos']['editar_puntos'])
									<li class="{{ $seccion && $seccion == 'Puntos' ? 'active' : '' }}">
										<a href="{{ url('puntos') }}">Puntos</a>
									</li>
								@endif
								@if($_SESSION['Usuario']['Permisos']['editar_profesores'])
									<li class="{{ $seccion && $seccion == 'Profesores' ? 'active' : '' }}">
										<a href="{{ url('profesores') }}">Profesores y Gestores</a>
									</li>
								@endif
								@if($_SESSION['Usuario']['Permisos']['administrar_localidades'])
									<li class="{{ $seccion && $seccion == 'Administrar localidades' ? 'active' : '' }}">
										<a href="{{ url('localidades/administrar') }}">Distribución de personal</a>
									</li>
								@endif
								@if($_SESSION['Usuario']['Permisos']['gestion_global_de_sesiones'] || $_SESSION['Usuario']['Permisos']['buscador_de_sesiones'])
									<li class="dropdown-header">Utilidades</li>
								@endif
								@if($_SESSION['Usuario']['Permisos']['gestion_global_de_sesiones'])
									<li class="{{ $seccion && $seccion == 'Gestion global de sesiones' ? 'active' : '' }}">
										<a href="{{ url('sesiones/administrar') }}">Gestion global de sesiones</a>
									</li>
								@endif
								@if($_SESSION['Usuario']['Permisos']['buscador_de_sesiones'])
									<li class="{{ $seccion && $seccion == 'Buscador de sesiones' ? 'active' : '' }}">
										<a href="{{ url('sesiones/buscar') }}">Buscador de sesiones</a>
									</li>
								@endif
							</ul>
						</li>
					@endif
					@if(
						(
						$_SESSION['Usuario']['Permisos']['programar_sesiones'] ||
						$_SESSION['Usuario']['Permisos']['revisar_sesiones_gestor']
						) &&
						in_array('Gestor', $_SESSION['Usuario']['Roles'])
					)
						<li class="dropdown {{ $seccion && in_array($seccion, ['Programación', 'Sesiones gestor']) ? 'active' : '' }}">
							<a class="dropdown-toggle" data-toggle="dropdown" href="#">Gestores <span class="caret"></span></a>
							<ul class="dropdown-menu">
								<li class="dropdown-header">Sesiones</li>
								<li class="{{ $seccion && $seccion == 'Programación' ? 'active' : '' }}">
									<a href="{{ url('programacion') }}">Programación</a>
								</li>
								<li class="{{ $seccion && $seccion == 'Sesiones gestor' ? 'active' : '' }}">
									<a href="{{ url('/gestores/sesiones') }}">Consulta</a>
								</li>
							</ul>
						</li>
					@endif
					@if(
						(
						$_SESSION['Usuario']['Permisos']['revisar_sesiones_profesor']
						) &&
						in_array('Profesor', $_SESSION['Usuario']['Roles'])
					)
						<li class="dropdown {{ $seccion && in_array($seccion, ['Sesiones profesor']) ? 'active' : '' }}">
							<a class="dropdown-toggle" data-toggle="dropdown" href="#">Profesores <span class="caret"></span></a>
							<ul class="dropdown-menu">
								<li class="dropdown-header">Sesiones</li>
								<li class="{{ $seccion && $seccion == 'Sesiones profesor' ? 'active' : '' }}">
									<a href="{{ url('/profesores/sesiones') }}">Consulta</a>
								</li>
							</ul>
						</li>
					@endif
					@if(
						$_SESSION['Usuario']['Permisos']['validar_reportes_jornadas'] ||
						$_SESSION['Usuario']['Permisos']['exportar_consolidado_general'] ||
						($_SESSION['Usuario']['Permisos']['gestionar_reportes_jornadas'] && in_array('Gestor', $_SESSION['Usuario']['Roles'])) ||
						($_SESSION['Usuario']['Permisos']['gestionar_reportes_jornadas'] && in_array('Profesor', $_SESSION['Usuario']['Roles']))
					)
						<li class="dropdown {{ $seccion && in_array($seccion, ['Revisar informes', 'Informes jornadas', 'Generar informe de actividades por punto']) ? 'active' : '' }}">
							<a class="dropdown-toggle" data-toggle="dropdown" href="#">Informes y consultas <span class="caret"></span></a>
							<ul class="dropdown-menu">
								<li class="dropdown-header">Informes de jornadas</li>
								@if ($_SESSION['Usuario']['Permisos']['validar_reportes_jornadas'])
									<li class="{{ $seccion && $seccion == 'Revisar informes' ? 'active' : '' }}">
										<a href="{{ url('informes/jornadas/revisar') }}">Validar</a>
									</li>
								@endif
								@if ($_SESSION['Usuario']['Permisos']['gestionar_reportes_jornadas'] && in_array('Gestor', $_SESSION['Usuario']['Roles']))
									<li class="{{ $seccion && in_array($seccion, ['Informes jornadas', 'Generar informe de actividades por punto']) ? 'active' : '' }}">
										<a href="{{ url('/informes/jornadas') }}">Consultar</a>
									</li>
								@endif
								@if ($_SESSION['Usuario']['Permisos']['gestionar_reportes_jornadas'] && in_array('Profesor', $_SESSION['Usuario']['Roles']))
									<li class="{{ $seccion && in_array($seccion, ['Informes jornadas', 'Generar informe de actividades por punto']) ? 'active' : '' }}">
										<a href="{{ url('/informes/jornadas/profesor') }}">Consultar</a>
									</li>
								@endif
								@if ($_SESSION['Usuario']['Permisos']['exportar_consolidado_general'])
									<li class="dropdown-header">Consolidado general jornadas</li>
									<li class="{{ $seccion && in_array($seccion, ['Consolidado general jornadas']) ? 'active' : '' }}">
										<a href="{{ url('/informes/consolidado_general') }}">Generar</a>
									</li>
								@endif
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
						<img src="{{ asset('public/Img/RECREOVIA.jpg') }}" width="40%" heigth="40%"/>
						<p class="lead"><h4>Módulo para la gestión y control de actividades del programa recreovía</h4></p>
					</div>
					<div class="col-lg-4 col-md-5 col-sm-6 hidden-xs">
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
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<br><br><br>
				</div>
			</div>
		</div>
		<div class="ajaxloader">
			<div class="ball-scale-multiple"><div></div><div></div><div></div></div>
			<span>PROCESANDO</span>
		</div>
		<!-- FIN Contenedor panel principal -->
	</body>
</html>
